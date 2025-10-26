<?php

namespace App\Livewire\ToolsPanel;

use App\Models\Tools\ShopSafe;
use App\Models\Tools\ShopConversionTransfer;
use App\Models\Tools\Customer;
use App\Models\Tools\ShopTransactions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;
use Morilog\Jalali\Jalalian;
use NumberFormatter;

class ShopConversion extends Component
{
    use WithPagination;

    // فقط حساب دریافت (مشتری)
    public $depositAccount;
    public $depositCustomerId;

    // اطلاعات تبدیل ارز
    public $from_currency = '';
    public $withdrawal_amount = '';
    public $to_currency = '';
    public $received_amount = '';
    public $currency_rate = '';
    public $transaction_date;
    public $description = '';
    public $by_sender = '';
    public $by_receiver = '';

    // برای نمایش حروفی
    public $withdrawalAmountInWords = '';
    public $receivedAmountInWords = '';
    public $currencyRateInWords = '';

    public $transactionType = 'خرید';
    public $currencies = [];
    public $customers = [];
    public $search = '';

    public $filteredCustomers;
    public $accountSearch = '';
    public $confirmDeleteId = null;
    public $editingConversionId = null;
    public $shopSafeBalance = [];
    public $showInsufficientBalanceError = false;

    // تعریف متغیر currenciesdefault
    public $currenciesdefault = [
        ['name' => 'افغانی', 'value' => 0],
        ['name' => 'دالر', 'value' => 0],
        ['name' => 'تومان', 'value' => 0],
        ['name' => 'کلدار', 'value' => 0],
        ['name' => 'خلاصه بیلانس به دالر', 'value' => 0],
    ];

    public function mount()
    {
        $this->transaction_date = Jalalian::now()->format('Y/m/d');
        $this->transactionType = 'فروش';

        $this->currencies = [
            ['code' => 'usd', 'name_fa' => 'دالر'],
            ['code' => 'afn', 'name_fa' => 'افغانی'],
            ['code' => 'irr', 'name_fa' => 'تومان'],
            ['code' => 'pkr', 'name_fa' => 'کلدار'],
        ];

        $this->updateShopSafeBalance();
        $this->updateCurrenciesDefault();

        $user = Auth::guard('tools')->user();
        if ($user) {
            $adminId = $user->admin_id ?? $user->id;
            $this->loadCustomers($adminId);
        }
    }

    /**
     * به روزرسانی موجودی صندوق
     */
    public function updateShopSafeBalance()
    {
        $user = Auth::guard('tools')->user();
        if (!$user) {
            $this->shopSafeBalance = [
                'afn' => 0,
                'usd' => 0,
                'irr' => 0,
                'pkr' => 0,
            ];
            return;
        }

        $adminId = $user->admin_id ?? $user->id;
        
        $safe = ShopSafe::where('user_id', $adminId)
            ->where('admin_id', null)
            ->first();

        if ($safe) {
            $this->shopSafeBalance = [
                'afn' => $safe->afn,
                'usd' => $safe->usd,
                'irr' => $safe->irr,
                'pkr' => $safe->pkr,
            ];
        } else {
            $this->shopSafeBalance = [
                'afn' => 0,
                'usd' => 0,
                'irr' => 0,
                'pkr' => 0,
            ];
        }
    }

    /**
     * به روزرسانی currenciesdefault بر اساس موجودی صندوق
     */
    public function updateCurrenciesDefault()
    {
        $this->currenciesdefault = [
            ['name' => 'افغانی', 'value' => $this->shopSafeBalance['afn'] ?? 0],
            ['name' => 'دالر', 'value' => $this->shopSafeBalance['usd'] ?? 0],
            ['name' => 'تومان', 'value' => $this->shopSafeBalance['irr'] ?? 0],
            ['name' => 'کلدار', 'value' => $this->shopSafeBalance['pkr'] ?? 0],
            ['name' => 'خلاصه بیلانس به دالر', 'value' => $this->calculateTotalBalanceInUsd()],
        ];
    }

    /**
     * محاسبه کل موجودی به دالر
     */
    private function calculateTotalBalanceInUsd()
    {
        $exchangeRates = [
            'afn' => 0.011,
            'usd' => 1,
            'irr' => 0.000024,
            'pkr' => 0.0036,
        ];

        $total = 0;
        foreach ($this->shopSafeBalance as $currency => $balance) {
            if (isset($exchangeRates[$currency])) {
                $total += $balance * $exchangeRates[$currency];
            }
        }

        return $total;
    }

    /**
     * بررسی موجودی کافی در صندوق
     */
    public function checkSafeBalance()
    {
        // فقط برای حالت "فروش" بررسی می‌کنیم چون فروش یعنی از صندوق پرداخت می‌کنیم
        if ($this->transactionType !== 'فروش' || !$this->from_currency || !$this->withdrawal_amount) {
            $this->showInsufficientBalanceError = false;
            return true;
        }

        $amount = (float) str_replace(',', '', $this->withdrawal_amount);
        $currentBalance = $this->shopSafeBalance[$this->from_currency] ?? 0;

        if ($currentBalance < $amount) {
            $this->showInsufficientBalanceError = true;
            return false;
        }

        $this->showInsufficientBalanceError = false;
        return true;
    }

    public function render()
    {
        $user = Auth::guard('tools')->user();

        if (!$user) {
            return view('livewire.tools-panel.shop-conversion', [
                'customers' => collect(),
                'conversionTransactions' => collect(),
            ]);
        }

        $adminId = $user->admin_id ?? $user->id;

        // بارگذاری مشتریان اگر خالی است
        if (empty($this->customers)) {
            $this->loadCustomers($adminId);
        }

        // ایجاد کوئری پایه با join
        $query = ShopConversionTransfer::select(
            'shop_conversion_transfer.*',
            'from_customer.fullname as from_customer_name',
            'from_customer.account_number as from_customer_account',
            'to_customer.fullname as to_customer_name',
            'to_customer.account_number as to_customer_account'
        )
        ->leftJoin('customers as from_customer', 'shop_conversion_transfer.form_customer', '=', 'from_customer.id')
        ->leftJoin('customers as to_customer', 'shop_conversion_transfer.to_customer', '=', 'to_customer.id')
        ->where('shop_conversion_transfer.admin_id', $adminId);

        // اعمال جستجو اگر مقدار وجود دارد
        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('from_customer.fullname', 'like', '%' . $this->search . '%')
                  ->orWhere('from_customer.account_number', 'like', '%' . $this->search . '%')
                  ->orWhere('to_customer.fullname', 'like', '%' . $this->search . '%')
                  ->orWhere('to_customer.account_number', 'like', '%' . $this->search . '%')
                  ->orWhere('shop_conversion_transfer.from_currency', 'like', '%' . $this->search . '%')
                  ->orWhere('shop_conversion_transfer.to_currency', 'like', '%' . $this->search . '%')
                  ->orWhere('shop_conversion_transfer.withdrawal_amount', 'like', '%' . $this->search . '%')
                  ->orWhere('shop_conversion_transfer.received_amount', 'like', '%' . $this->search . '%')
                  ->orWhere('shop_conversion_transfer.currency_rate', 'like', '%' . $this->search . '%')
                  ->orWhere('shop_conversion_transfer.description', 'like', '%' . $this->search . '%')
                  ->orWhere('shop_conversion_transfer.type', 'like', '%' . $this->search . '%');
            });
        }

        $conversionTransactions = $query->latest('shop_conversion_transfer.created_at')->paginate(10);

        // به روزرسانی currenciesdefault قبل از رندر
        $this->updateCurrenciesDefault();

        return view('livewire.tools-panel.shop-conversion', [
            'customers' => collect($this->customers),
            'conversionTransactions' => $conversionTransactions,
            'shopSafeBalance' => $this->shopSafeBalance,
            'showInsufficientBalanceError' => $this->showInsufficientBalanceError,
        ]);
    }

    public function showReport()
    {
        if (!$this->depositCustomerId) {
            session()->flash('error', 'لطفاً ابتدا یک مشتری را انتخاب کنید');
            return redirect()->back();
        }

        $customer = Customer::find($this->depositCustomerId);
        if (!$customer) {
            session()->flash('error', 'مشتری انتخاب شده یافت نشد');
            return redirect()->back();
        }

        session([
            'selected_customer_id' => $this->depositCustomerId,
            'selected_customer_name' => $customer->fullname,
            'selected_customer_account' => $customer->account_number
        ]);

        return redirect()->route('tools.transaction-reports');
    }

    public function search()
    {
        $this->resetPage(); 
    }

    private function loadCustomers($adminId)
    {
        $relatedUserIds = \App\Models\Tools\User::where('admin_id', $adminId)
            ->pluck('id')
            ->push($adminId)
            ->toArray();

        $this->customers = Customer::select('id', 'account_number', 'fullname')
            ->where(function ($query) use ($adminId, $relatedUserIds) {
                $query->where('admin_id', $adminId)
                    ->orWhereHas('shopTransactions', function ($t) use ($relatedUserIds) {
                        $t->whereIn('user_id', $relatedUserIds)
                            ->orWhereIn('admin_id', $relatedUserIds);
                    });
            })
            ->orderBy('fullname')
            ->get()
            ->toArray();
    }

    // متد تبدیل عدد به حروف
    private function convertAmountToWords($value, $property)
    {
        if ($value && is_numeric($value)) {
            try {
                $formatter = new NumberFormatter("fa", NumberFormatter::SPELLOUT);
                $words = $formatter->format(floatval($value));
                $words = str_replace(['دویست', 'سیصد', 'پانصد'], ['دوصد', 'سه صد', 'پنجصد'], $words);
                $this->$property = $words;
            } catch (\Exception $e) {
                $this->$property = '';
                Log::error('Error converting amount to words: ' . $e->getMessage());
            }
        } else {
            $this->$property = '';
        }
    }

    public function toggleTransactionType()
    {
        $this->transactionType = 'فروش';
        $tempCurrency = $this->from_currency;
        $this->from_currency = $this->to_currency;
        $this->to_currency = $tempCurrency;

        $tempBy = $this->by_sender;
        $this->by_sender = $this->by_receiver;
        $this->by_receiver = $tempBy;

        $this->calculateReceivedAmount();
        $this->checkSafeBalance();

        $this->dispatch('accountsSwapped');
    }

    // محاسبه خودکار مبلغ دریافت بر اساس نرخ ارز
    public function calculateReceivedAmount()
    {
        if ($this->withdrawal_amount && $this->currency_rate && $this->from_currency && $this->to_currency) {
            $fromCurrency = $this->from_currency;
            $toCurrency = $this->to_currency;
            $amount = floatval($this->withdrawal_amount);
            $rate = floatval($this->currency_rate);

            $shouldDivide = $this->shouldUseDivision($fromCurrency, $toCurrency);

            if ($shouldDivide) {
                $this->received_amount = number_format($amount / $rate, 2, '.', '');
            } else {
                $this->received_amount = number_format($amount * $rate, 2, '.', '');
            }

            $this->convertAmountToWords($this->withdrawal_amount, 'withdrawalAmountInWords');
            $this->convertAmountToWords($this->received_amount, 'receivedAmountInWords');
            $this->convertAmountToWords($this->currency_rate, 'currencyRateInWords');
        } else {
            $this->received_amount = '';
            $this->withdrawalAmountInWords = '';
            $this->receivedAmountInWords = '';
            $this->currencyRateInWords = '';
        }

        $this->checkSafeBalance();
    }

    private function shouldUseDivision($fromCurrency, $toCurrency)
    {
        $baseCurrencies = ['usd'];
        $localCurrencies = ['afn', 'irr', 'pkr'];

        if (in_array($fromCurrency, $baseCurrencies) && in_array($toCurrency, $localCurrencies)) {
            return false;
        }

        if (in_array($fromCurrency, $localCurrencies) && in_array($toCurrency, $baseCurrencies)) {
            return true;
        }

        return true;
    }

    // Event Listeners برای محاسبه خودکار
    public function updated($property)
    {
        if (in_array($property, [
            'withdrawal_amount',
            'currency_rate',
            'from_currency',
            'to_currency',
            'transactionType'
        ])) {
            $this->calculateReceivedAmount();
        }

        if ($property === 'withdrawal_amount') {
            $this->convertAmountToWords($this->withdrawal_amount, 'withdrawalAmountInWords');
        }

        if ($property === 'currency_rate') {
            $this->convertAmountToWords($this->currency_rate, 'currencyRateInWords');
        }

        if (in_array($property, ['transactionType', 'from_currency', 'withdrawal_amount'])) {
            $this->checkSafeBalance();
        }
    }

    // متد برای انتخاب حساب دریافت
    public function selectDepositAccount($customerId)
    {
        $this->depositCustomerId = $customerId;
        $this->depositAccount = $customerId;
    }

    public function submitConversion()
    {
        // بررسی موجودی کافی برای حالت فروش
        if (!$this->checkSafeBalance()) {
            session()->flash('error', 'موجودی صندوق کافی نیست!');
            return;
        }

        $this->validate([
            'depositAccount' => 'required|integer|exists:tools.customers,id',
            'from_currency' => 'required|string',
            'to_currency' => 'required|string',
            'withdrawal_amount' => 'required|numeric|min:0.01',
            'received_amount' => 'required|numeric|min:0.01',
            'currency_rate' => 'required|numeric|min:0.0001',
            'transaction_date' => 'required|date',
            'description' => 'nullable|string|max:500',
        ]);

        $user = Auth::guard('tools')->user();
        $adminId = $user->admin_id ?? $user->id;

        DB::connection('tools')->beginTransaction();

        try {
            if ($this->editingConversionId) {
                // حالت ویرایش
                $conversion = ShopConversionTransfer::find($this->editingConversionId);

                if ($conversion) {
                    // بازگرداندن تغییرات صندوق برای تراکنش قبلی
                    $this->revertShopSafeChanges($conversion);

                    // حذف تراکنش‌های قبلی
                    ShopTransactions::where('conversion_transfer_id', $conversion->id)->delete();

                    // آپدیت رکورد تبدیل ارز
                    $conversion->update([
                        'form_customer' => $this->transactionType === 'خرید' ? $this->depositAccount : null,
                        'from_currency' => $this->from_currency,
                        'withdrawal_amount' => $this->withdrawal_amount,
                        'to_customer' => $this->transactionType === 'خرید' ? null : $this->depositAccount,
                        'to_currency' => $this->to_currency,
                        'received_amount' => $this->received_amount,
                        'currency_rate' => $this->currency_rate,
                        'transaction_date' => $this->transaction_date,
                        'description' => $this->description,
                        'type' => $this->transactionType,
                    ]);

                    $conversionId = $conversion->id;
                }
            } else {
                // حالت ایجاد جدید
                $conversion = ShopConversionTransfer::create([
                    'form_customer' => $this->transactionType === 'خرید' ? $this->depositAccount : null,
                    'from_currency' => $this->from_currency,
                    'withdrawal_amount' => $this->withdrawal_amount,
                    'to_customer' => $this->transactionType === 'خرید' ? null : $this->depositAccount,
                    'to_currency' => $this->to_currency,
                    'received_amount' => $this->received_amount,
                    'currency_rate' => $this->currency_rate,
                    'transaction_date' => $this->transaction_date,
                    'description' => $this->description,
                    'user_id' => $user->id,
                    'admin_id' => $adminId,
                    'type' => $this->transactionType,
                ]);

                $conversionId = $conversion->id;
            }

            // ایجاد تراکنش‌ها و اعمال تغییرات صندوق
            $this->createConversionTransactions($conversionId, $user, $adminId);
            
            // اعمال تغییرات صندوق برای تراکنش جدید
            $this->applyShopSafeChanges();

            DB::connection('tools')->commit();

            $message = $this->editingConversionId ? 'تبدیل ارز با موفقیت ویرایش شد.' : 'تبدیل ارز با موفقیت ثبت شد.';
            session()->flash('message', $message);

            $this->updateShopSafeBalance();
            $this->updateCurrenciesDefault();
            $this->resetForm();
        } catch (\Exception $e) {
            DB::connection('tools')->rollBack();
            session()->flash('error', 'خطا در ثبت تبدیل ارز: ' . $e->getMessage());

            Log::error('Conversion transfer error: ' . $e->getMessage(), [
                'depositAccount' => $this->depositAccount,
                'transactionType' => $this->transactionType,
                'editing' => $this->editingConversionId ? 'yes' : 'no',
            ]);
        }
    }

    /**
     * ایجاد تراکنش‌های تبدیل ارز
     */
    private function createConversionTransactions($conversionId, $user, $adminId)
    {
        if ($this->transactionType === 'خرید') {
            // حالت خرید: از مشتری می‌گیریم، به صندوق دوکان واریز می‌کنیم
            
            // فقط تراکنش برداشت از مشتری ایجاد می‌کنیم
            ShopTransactions::create([
                'customer_id' => $this->depositAccount,
                'user_id' => $user->id,
                'admin_id' => $adminId,
                'currency' => $this->from_currency,
                'amount' => $this->withdrawal_amount,
                'type' => 'برد',
                'date' => $this->transaction_date,
                'description' => $this->description . ' - خرید ' . $this->getCurrencyName($this->from_currency) . ' و تبدیل به ' . $this->getCurrencyName($this->to_currency),
                'by' => 'صاحب دوکان',
                'conversion_transfer_id' => $conversionId,
            ]);
            
        } else {
            // حالت فروش: از صندوق دوکان می‌گیریم، به مشتری واریز می‌کنیم
            
            // فقط تراکنش واریز به مشتری ایجاد می‌کنیم
            ShopTransactions::create([
                'customer_id' => $this->depositAccount,
                'user_id' => $user->id,
                'admin_id' => $adminId,
                'currency' => $this->to_currency,
                'amount' => $this->received_amount,
                'type' => 'رسید',
                'date' => $this->transaction_date,
                'description' => $this->description . ' - فروش ' . $this->getCurrencyName($this->from_currency) . ' و دریافت ' . $this->getCurrencyName($this->to_currency),
                'by' => 'صاحب دوکان',
                'conversion_transfer_id' => $conversionId,
            ]);
        }
    }

    /**
     * اعمال تغییرات صندوق برای تراکنش جدید
     */
    private function applyShopSafeChanges()
    {
        $user = Auth::guard('tools')->user();
        $adminId = $user->admin_id ?? $user->id;

        $safe = ShopSafe::firstOrCreate(
            [
                'user_id' => $adminId,
                'admin_id' => null
            ],
            [
                'afn' => 0,
                'usd' => 0,
                'irr' => 0,
                'pkr' => 0,
            ]
        );

        if ($this->transactionType === 'خرید') {
            // خرید: به صندوق واریز می‌شود (مبلغ دریافتی)
            if (isset($safe->{$this->to_currency})) {
                $safe->{$this->to_currency} += floatval($this->received_amount);
            }
        } else {
            // فروش: از صندوق برداشت می‌شود (مبلغ برداشتی)
            if (isset($safe->{$this->from_currency})) {
                $safe->{$this->from_currency} -= floatval($this->withdrawal_amount);
                
                // جلوگیری از موجودی منفی
                if ($safe->{$this->from_currency} < 0) {
                    $safe->{$this->from_currency} = 0;
                }
            }
        }

        $safe->save();
    }

    /**
     * بازگرداندن تغییرات صندوق برای تراکنش قبلی (در حالت ویرایش)
     */
    private function revertShopSafeChanges($conversion)
    {
        $user = Auth::guard('tools')->user();
        $adminId = $user->admin_id ?? $user->id;

        $safe = ShopSafe::where('user_id', $adminId)
            ->where('admin_id', null)
            ->first();

        if (!$safe) return;

        // بازگرداندن تغییرات بر اساس نوع تراکنش قبلی
        if ($conversion->type === 'خرید') {
            // قبلی خرید بود: از صندوق برداشت می‌کنیم (برعکس)
            if (isset($safe->{$conversion->to_currency})) {
                $safe->{$conversion->to_currency} -= floatval($conversion->received_amount);
                if ($safe->{$conversion->to_currency} < 0) {
                    $safe->{$conversion->to_currency} = 0;
                }
            }
        } else {
            // قبلی فروش بود: به صندوق واریز می‌کنیم (برعکس)
            if (isset($safe->{$conversion->from_currency})) {
                $safe->{$conversion->from_currency} += floatval($conversion->withdrawal_amount);
            }
        }

        $safe->save();
    }


     private function generateConversionPdf($conversionId)
    {
        try {
            $conversion = ShopConversionTransfer::with(['fromCustomer', 'toCustomer', 'user'])->findOrFail($conversionId);
            
            // Check user access
            $user = Auth::guard('tools')->user();
            if ($conversion->user_id !== $user->id && $conversion->admin_id !== $user->id) {
                session()->flash('error', 'دسترسی به این تراکنش مجاز نیست.');
                return null;
            }
            
            $mpdf = new \Mpdf\Mpdf([
                'mode' => 'utf-8',
                'format' => [85, 220],
                'directionality' => 'rtl',
                'margin_top' => 5,
                'margin_bottom' => 5,
                'margin_left' => 5,
                'margin_right' => 5,
                'fontDir' => array_merge((new \Mpdf\Config\ConfigVariables())->getDefaults()['fontDir'], [
                    public_path('fonts'),
                ]),
                'fontdata' => (new \Mpdf\Config\FontVariables())->getDefaults()['fontdata'] + [
                    'Shabnam' => [
                        'R' => 'Shabnam-FD.ttf',
                    ],
                ],
                'default_font' => 'Shabnam',
            ]);

            $mpdf->SetAutoPageBreak(false);

            $html = view('pdf.Tools.shop-conversion', compact('conversion'))->render();
            $mpdf->WriteHTML($html);

            $fileName = 'تبدیل ارز و انتفال_' . $conversion->id . '_' . $conversion->type . '.pdf';

            return response()->streamDownload(function () use ($mpdf) {
                echo $mpdf->Output('', 'S');
            }, $fileName);

        } catch (\Exception $e) {
            Log::error('PDF generation error: ' . $e->getMessage());
            session()->flash('error', 'خطا در ایجاد PDF: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Print conversion transaction as PDF
     */
    public function printTransaction($conversionId)
    {
        try {
            $conversion = ShopConversionTransfer::findOrFail($conversionId);
            
            // Check user access
            $user = Auth::guard('tools')->user();
            if ($conversion->user_id !== $user->id && $conversion->admin_id !== $user->id) {
                session()->flash('error', 'دسترسی به این تراکنش مجاز نیست.');
                return redirect()->back();
            }
            
            return $this->generateConversionPdf($conversion->id);
            
        } catch (\Exception $e) {
            Log::error('Print conversion error: ' . $e->getMessage());
            session()->flash('error', 'خطا در چاپ تراکنش: ' . $e->getMessage());
            return redirect()->back();
        }
    }


    public function resetForm()
    {
        $this->reset([
            'depositAccount',
            'depositCustomerId',
            'from_currency',
            'to_currency',
            'withdrawal_amount',
            'received_amount',
            'currency_rate',
            'description',
            'editingConversionId',
            'withdrawalAmountInWords',
            'receivedAmountInWords',
            'currencyRateInWords',
        ]);

        $this->transactionType = 'خرید';
        $this->transaction_date = Jalalian::now()->format('Y/m/d');
        $this->updateShopSafeBalance();
        $this->updateCurrenciesDefault();
        $this->showInsufficientBalanceError = false;
    }

    public function editConversion($conversionId)
    {
        $conversion = ShopConversionTransfer::with(['toCustomer'])->find($conversionId);

        if ($conversion) {
            $this->editingConversionId = $conversionId;

            $this->depositAccount = $conversion->to_customer ?? $conversion->form_customer;
            $this->from_currency = $conversion->from_currency;
            $this->to_currency = $conversion->to_currency;
            $this->withdrawal_amount = $conversion->withdrawal_amount;
            $this->received_amount = $conversion->received_amount;
            $this->currency_rate = $conversion->currency_rate;
            $this->transaction_date = $conversion->transaction_date;
            $this->description = $conversion->description;
            $this->transactionType = $conversion->type;

            $this->depositCustomerId = $this->depositAccount;

            $this->convertAmountToWords($this->withdrawal_amount, 'withdrawalAmountInWords');
            $this->convertAmountToWords($this->received_amount, 'receivedAmountInWords');
            $this->convertAmountToWords($this->currency_rate, 'currencyRateInWords');

            $this->checkSafeBalance();

            $this->dispatch('edit-mode-activated', [
                'depositAccount' => $this->depositAccount,
                'depositCustomer' => $conversion->toCustomer ? $conversion->toCustomer->account_number . ' - ' . $conversion->toCustomer->fullname : ''
            ]);
        }
    }

    public function confirmDelete($conversionId)
    {
        $this->confirmDeleteId = $conversionId;
    }

    public function deleteConversion()
    {
        if (!$this->confirmDeleteId) {
            return;
        }

        DB::connection('tools')->beginTransaction();

        try {
            $conversion = ShopConversionTransfer::find($this->confirmDeleteId);

            if ($conversion) {
                // بازگرداندن تغییرات صندوق
                $this->revertShopSafeChanges($conversion);

                // حذف تراکنش‌های مرتبط
                ShopTransactions::where('conversion_transfer_id', $conversion->id)->delete();

                // حذف تبدیل ارز
                $conversion->delete();

                DB::connection('tools')->commit();
                session()->flash('message', 'تبدیل ارز با موفقیت حذف شد.');
                $this->confirmDeleteId = null;
                $this->updateShopSafeBalance();
                $this->updateCurrenciesDefault();
            }
        } catch (\Exception $e) {
            DB::connection('tools')->rollBack();
            session()->flash('error', 'خطا در حذف تبدیل ارز: ' . $e->getMessage());
            Log::error('Delete conversion error: ' . $e->getMessage());
            $this->confirmDeleteId = null;
        }
    }

    private function getCurrencyName($currencyCode)
    {
        $currencyMap = [
            'afn' => 'افغانی',
            'usd' => 'دالر',
            'irr' => 'تومان',
            'pkr' => 'کلدار',
        ];

        return $currencyMap[$currencyCode] ?? $currencyCode;
    }
}