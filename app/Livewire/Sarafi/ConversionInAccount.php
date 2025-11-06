<?php

namespace App\Livewire\Sarafi;

use App\Models\Sarafi\ConversionInAccounts;
use App\Models\Sarafi\Customer;
use App\Models\Sarafi\ExchangeRates;
use App\Models\Sarafi\Transaction;
use App\Models\Sarafi\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;
use Morilog\Jalali\Jalalian;
use NumberFormatter;

class ConversionInAccount extends Component
{
    use WithPagination;

    // متغیرهای فرم
    public $selectedAccount;
    public $selectedCustomerId;
    public $from_currency = '';
    public $buy_amount = '';
    public $to_currency = '';
    public $sell_amount = '';
    public $currency_rate = '';
    public $transaction_date;
    public $description = '';
    public $zone_sender = '';
    public $zone_receiver = '';
    public $by_sender = '';
    public $by_receiver = '';

    // نمایش حروفی
    public $withdrawalAmountInWords = '';
    public $receivedAmountInWords = '';
    public $currencyRateInWords = '';

    // متغیرهای عمومی
    public $transactionType = 'خرید';
    public $currencies = [];
    public $customers = [];
    public $search = '';
    public $accountSearch = '';
    public $confirmDeleteId = null;
    public $editingConversionId = null;


    // اضافه کردن متغیرهای جدید برای نمایش موجودی‌ها
    public $customerCashBalances = [];
    public $customerBankBalances = [];
    public $customerTotalBalances = [];


    // موجودی ارزها
    public $currenciesdefault = [
        ['name' => 'افغانی', 'value' => 0],
        ['name' => 'دالر', 'value' => 0],
        ['name' => 'تومان', 'value' => 0],
        ['name' => 'یورو', 'value' => 0],
        ['name' => 'کلدار', 'value' => 0],
        ['name' => 'درهم', 'value' => 0],
        ['name' => 'لیره', 'value' => 0],
        ['name' => 'یوان', 'value' => 0],
        ['name' => 'روپیه', 'value' => 0],
        ['name' => 'خلاصه بیلانس به دالر', 'value' => 0],
    ];

    /**
     * مقداردهی اولیه
     */
    public function mount()
    {
        $this->transaction_date = Jalalian::now()->format('Y/m/d');
        $this->transactionType = 'خرید';

        $this->currencies = [
            ['code' => 'usd', 'name_fa' => 'دالر'],
            ['code' => 'afn', 'name_fa' => 'افغانی'],
            ['code' => 'eur', 'name_fa' => 'یورو'],
            ['code' => 'irr', 'name_fa' => 'تومان'],
            ['code' => 'aed', 'name_fa' => 'درهم'],
            ['code' => 'try', 'name_fa' => 'لیره'],
            ['code' => 'cny', 'name_fa' => 'یوان'],
            ['code' => 'pkr', 'name_fa' => 'کلدار'],
            ['code' => 'gbp', 'name_fa' => 'پوند'],
            ['code' => 'jpy', 'name_fa' => 'ین'],
            ['code' => 'sar', 'name_fa' => 'ریال سعودی'],
            ['code' => 'inr', 'name_fa' => 'روپیه'],
        ];

        $user = Auth::guard('sarafi')->user();
        if ($user) {
            $adminId = $user->admin_id ?? $user->id;
            $this->loadCustomers($adminId);
        }
    }

    /**
     * رندر کامپوننت
     */
    public function render()
    {
        $user = Auth::guard('sarafi')->user();

        if (!$user) {
            return view('livewire.sarafi.conversion-in-account', [
                'customers' => collect(),
                'conversionTransactions' => collect(),
            ]);
        }

        $adminId = $user->admin_id ?? $user->id;

        // بارگذاری مشتریان اگر خالی باشد
        if (empty($this->customers)) {
            $this->loadCustomers($adminId);
        }

        // کوئری برای تراکنش‌های تبدیل ارز
        $query = ConversionInAccounts::with(['customer'])
            ->where('admin_id', $adminId);

        // اعمال جستجو
        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->whereHas('customer', function($customerQuery) {
                    $customerQuery->where('fullname', 'like', '%' . $this->search . '%')
                                 ->orWhere('account_number', 'like', '%' . $this->search . '%');
                })
                ->orWhere('from_currency', 'like', '%' . $this->search . '%')
                ->orWhere('to_currency', 'like', '%' . $this->search . '%')
                ->orWhere('buy_amount', 'like', '%' . $this->search . '%')
                ->orWhere('sell_amount', 'like', '%' . $this->search . '%')
                ->orWhere('currency_rate', 'like', '%' . $this->search . '%')
                ->orWhere('description', 'like', '%' . $this->search . '%')
                ->orWhere('type', 'like', '%' . $this->search . '%')
                ->orWhere('zone_sender', 'like', '%' . $this->search . '%')
                ->orWhere('zone_receiver', 'like', '%' . $this->search . '%');
            });
        }

        $conversionTransactions = $query->latest('created_at')->paginate(10);

        return view('livewire.sarafi.conversion-in-account', [
            'customers' => collect($this->customers),
            'conversionTransactions' => $conversionTransactions,
        ]);
    }

    /**
     * جستجو در جدول
     */
    public function search()
    {
        $this->resetPage(); 
    }

    /**
     * بارگذاری لیست مشتریان
     */
    private function loadCustomers($adminId)
    {
        $relatedUserIds = User::where('admin_id', $adminId)
            ->pluck('id')
            ->push($adminId)
            ->toArray();

        $this->customers = Customer::select('id', 'account_number', 'fullname')
            ->where(function ($query) use ($adminId, $relatedUserIds) {
                $query->where('admin_id', $adminId)
                    ->orWhereHas('transactions', function ($t) use ($relatedUserIds) {
                        $t->whereIn('user_id', $relatedUserIds)
                            ->orWhereIn('admin_id', $relatedUserIds);
                    });
            })
            ->orderBy('fullname')
            ->get()
            ->toArray();
    }

    /**
     * انتخاب حساب مشتری
     */
    public function selectAccount($customerId)
    {
        $this->selectedCustomerId = $customerId;
        $this->selectedAccount = $customerId;
        $this->updateCustomerCurrencyBalance($customerId);
    }

    /**
     * تغییر نوع معامله (خرید/فروش)
     */
    public function toggleTransactionType()
    {
        $this->transactionType = $this->transactionType === 'خرید' ? 'فروش' : 'خرید';

        // جابجایی ارزها
        $tempCurrency = $this->from_currency;
        $this->from_currency = $this->to_currency;
        $this->to_currency = $tempCurrency;

        // جابجایی زون‌ها
        $tempZone = $this->zone_sender;
        $this->zone_sender = $this->zone_receiver;
        $this->zone_receiver = $tempZone;

        // جابجایی نام افراد
        $tempBy = $this->by_sender;
        $this->by_sender = $this->by_receiver;
        $this->by_receiver = $tempBy;

        $this->calculateReceivedAmount();
        $this->dispatch('transactionTypeToggled');
    }

    /**
     * محاسبه خودکار مبلغ دریافت بر اساس نرخ ارز
     */
    public function calculateReceivedAmount()
    {
        if ($this->buy_amount && $this->currency_rate && $this->from_currency && $this->to_currency) {
            $fromCurrency = $this->from_currency;
            $toCurrency = $this->to_currency;
            $amount = floatval($this->buy_amount);
            $rate = floatval($this->currency_rate);

            $shouldDivide = $this->shouldUseDivision($fromCurrency, $toCurrency);

            if ($shouldDivide) {
                $this->sell_amount = number_format($amount / $rate, 2, '.', '');
            } else {
                $this->sell_amount = number_format($amount * $rate, 2, '.', '');
            }

            // تبدیل به حروف
            $this->convertAmountToWords($this->buy_amount, 'withdrawalAmountInWords');
            $this->convertAmountToWords($this->sell_amount, 'receivedAmountInWords');
            $this->convertAmountToWords($this->currency_rate, 'currencyRateInWords');
        } else {
            $this->sell_amount = '';
            $this->withdrawalAmountInWords = '';
            $this->receivedAmountInWords = '';
            $this->currencyRateInWords = '';
        }
    }

    /**
     * تعیین منطق محاسبه (تقسیم یا ضرب)
     */
    private function shouldUseDivision($fromCurrency, $toCurrency): bool
    {
        $baseCurrencies = ['usd', 'eur', 'gbp'];
        $localCurrencies = ['afn', 'irr', 'pkr'];

        if (in_array($fromCurrency, $baseCurrencies) && in_array($toCurrency, $localCurrencies)) {
            return false; // ضرب
        }

        if (in_array($fromCurrency, $localCurrencies) && in_array($toCurrency, $baseCurrencies)) {
            return true; // تقسیم
        }

        return true;
    }

    /**
     * Event listeners برای محاسبه خودکار
     */
    public function updated($property)
    {
        if (in_array($property, [
            'buy_amount',
            'currency_rate', 
            'from_currency',
            'to_currency',
            'transactionType'
        ])) {
            $this->calculateReceivedAmount();
        }

        if ($property === 'buy_amount') {
            $this->convertAmountToWords($this->buy_amount, 'withdrawalAmountInWords');
        }

        if ($property === 'currency_rate') {
            $this->convertAmountToWords($this->currency_rate, 'currencyRateInWords');
        }
    }

    /**
     * ثبت تبدیل ارز
     */
    public function submitConversion()
    {
        $this->validate([
            'selectedAccount' => 'required|integer|exists:sarafi.customers,id',
            'from_currency' => 'required|string',
            'to_currency' => 'required|string', 
            'buy_amount' => 'required|numeric|min:0.01',
            'sell_amount' => 'required|numeric|min:0.01',
            'currency_rate' => 'required|numeric|min:0.0001',
            'transaction_date' => 'required|date',
            'description' => 'nullable|string|max:500',
            'zone_sender' => 'required|string',
            'zone_receiver' => 'required|string',
            'by_sender' => 'nullable|string|max:255',
            'by_receiver' => 'nullable|string|max:255',
        ]);

        $user = Auth::guard('sarafi')->user();
        
        if (!$user) {
            session()->flash('error', 'کاربر احراز هویت نشده است.');
            return;
        }

        $adminId = $user->admin_id ?? $user->id;

        DB::connection('sarafi')->beginTransaction();

        try {
            // بررسی موجودی کافی در ارز مبدا
            $fromCurrencyBalance = $this->getCustomerCurrencyBalance($this->selectedAccount, $this->from_currency);
            
            if ($fromCurrencyBalance < floatval($this->buy_amount)) {
                throw new \Exception('موجودی کافی در ارز مبدا وجود ندارد. موجودی فعلی: ' . number_format($fromCurrencyBalance));
            }

            $conversionId = null;

            if ($this->editingConversionId) {
                // حالت ویرایش
                $conversion = ConversionInAccounts::find($this->editingConversionId);

                if (!$conversion) {
                    throw new \Exception('رکورد تبدیل ارز برای ویرایش یافت نشد.');
                }

                // حذف تراکنش‌های قبلی
                Transaction::where('conversion_in_account_id', $conversion->id)->delete();

                // آپدیت رکورد تبدیل ارز
                $conversion->update([
                    'customer_id' => $this->selectedAccount,
                    'from_currency' => $this->from_currency,
                    'buy_amount' => $this->buy_amount,
                    'to_currency' => $this->to_currency,
                    'sell_amount' => $this->sell_amount,
                    'currency_rate' => $this->currency_rate,
                    'transaction_date' => $this->transaction_date,
                    'description' => $this->description,
                    'zone_sender' => $this->zone_sender,
                    'zone_receiver' => $this->zone_receiver,
                    'by_sender' => $this->by_sender,
                    'by_receiver' => $this->by_receiver,
                    'type' => $this->transactionType,
                ]);

                $conversionId = $conversion->id;

            } else {
                // حالت ایجاد جدید
                $conversion = ConversionInAccounts::create([
                    'customer_id' => $this->selectedAccount,
                    'from_currency' => $this->from_currency,
                    'buy_amount' => $this->buy_amount,
                    'to_currency' => $this->to_currency,
                    'sell_amount' => $this->sell_amount,
                    'currency_rate' => $this->currency_rate,
                    'transaction_date' => $this->transaction_date,
                    'description' => $this->description,
                    'zone_sender' => $this->zone_sender,
                    'zone_receiver' => $this->zone_receiver,
                    'by_sender' => $this->by_sender,
                    'by_receiver' => $this->by_receiver,
                    'user_id' => $user->id,
                    'admin_id' => $adminId,
                    'type' => $this->transactionType,
                ]);

                $conversionId = $conversion->id;
            }

            // ایجاد تراکنش برداشت (از ارز مبدا)
            Transaction::create([
                'customer_id' => $this->selectedAccount,
                'user_id' => $user->id,
                'admin_id' => $adminId,
                'currency' => $this->from_currency,
                'amount' => $this->buy_amount,
                'type' => 'برداشت',
                'date' => $this->transaction_date,
                'description' => $this->generateWithdrawalDescription(),
                'zone' => $this->zone_sender,
                'by' => $this->by_sender,
                'conversion_in_account_id' => $conversionId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // ایجاد تراکنش دریافت (به ارز مقصد)
            Transaction::create([
                'customer_id' => $this->selectedAccount,
                'user_id' => $user->id,
                'admin_id' => $adminId,
                'currency' => $this->to_currency,
                'amount' => $this->sell_amount,
                'type' => 'رسید',
                'date' => $this->transaction_date,
                'description' => $this->generateDepositDescription(),
                'zone' => $this->zone_receiver,
                'by' => $this->by_receiver,
                'conversion_in_account_id' => $conversionId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::connection('sarafi')->commit();

            $message = $this->editingConversionId ? 
                'تبدیل ارز با موفقیت ویرایش شد.' : 
                'تبدیل ارز با موفقیت ثبت شد.';
            
            session()->flash('message', $message);
            
            // به‌روزرسانی موجودی‌ها بعد از ثبت
            $this->updateCustomerCurrencyBalance($this->selectedAccount);
            
            // لاگ کردن عملیات موفق
            Log::info('Conversion in account completed successfully', [
                'conversion_id' => $conversionId,
                'customer_id' => $this->selectedAccount,
                'from_currency' => $this->from_currency,
                'to_currency' => $this->to_currency,
                'buy_amount' => $this->buy_amount,
                'sell_amount' => $this->sell_amount,
                'user_id' => $user->id,
                'admin_id' => $adminId,
            ]);
            
            $this->resetForm();
            
        } catch (\Exception $e) {
            DB::connection('sarafi')->rollBack();
            
            $errorMessage = 'خطا در ثبت تبدیل ارز: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
            
            Log::error('Conversion in account error: ' . $e->getMessage(), [
                'customer_id' => $this->selectedAccount,
                'from_currency' => $this->from_currency,
                'to_currency' => $this->to_currency,
                'buy_amount' => $this->buy_amount,
                'sell_amount' => $this->sell_amount,
                'user_id' => $user->id ?? 'unknown',
                'admin_id' => $adminId ?? 'unknown',
                'editing' => $this->editingConversionId ? 'yes' : 'no',
            ]);
        }
    }

    /**
     * ویرایش تبدیل ارز
     */
    public function editConversion($conversionId)
    {
        $conversion = ConversionInAccounts::with(['customer'])->find($conversionId);

        if ($conversion) {
            $this->editingConversionId = $conversionId;

            // تنظیم مقادیر فرم
            $this->selectedAccount = $conversion->customer_id;
            $this->selectedCustomerId = $conversion->customer_id;
            $this->from_currency = $conversion->from_currency;
            $this->to_currency = $conversion->to_currency;
            $this->buy_amount = $conversion->buy_amount;
            $this->sell_amount = $conversion->sell_amount;
            $this->currency_rate = $conversion->currency_rate;
            $this->transaction_date = $conversion->transaction_date;
            $this->description = $conversion->description;
            $this->zone_sender = $conversion->zone_sender;
            $this->zone_receiver = $conversion->zone_receiver;
            $this->by_sender = $conversion->by_sender;
            $this->by_receiver = $conversion->by_receiver;
            $this->transactionType = $conversion->type;

            // تبدیل به حروف
            $this->convertAmountToWords($this->buy_amount, 'withdrawalAmountInWords');
            $this->convertAmountToWords($this->sell_amount, 'receivedAmountInWords');
            $this->convertAmountToWords($this->currency_rate, 'currencyRateInWords');

            // به‌روزرسانی موجودی‌ها
            $this->updateCustomerCurrencyBalance($conversion->customer_id);

            $this->dispatch('edit-mode-activated', [
                'selectedAccount' => $this->selectedAccount,
                'selectedCustomer' => $conversion->customer ? 
                    $conversion->customer->account_number . ' - ' . $conversion->customer->fullname : ''
            ]);
        }
    }

    /**
     * تأیید حذف
     */
    public function confirmDelete($conversionId)
    {
        $this->confirmDeleteId = $conversionId;
    }

    /**
     * حذف تبدیل ارز
     */
    public function deleteConversion()
    {
        if (!$this->confirmDeleteId) {
            return;
        }

        DB::connection('sarafi')->beginTransaction();

        try {
            $conversion = ConversionInAccounts::find($this->confirmDeleteId);

            if ($conversion) {
                // حذف تراکنش‌های مرتبط
                Transaction::where('conversion_in_account_id', $conversion->id)->delete();
                
                // حذف تبدیل ارز
                $conversion->delete();

                DB::connection('sarafi')->commit();
                session()->flash('message', 'تبدیل ارز با موفقیت حذف شد.');
                $this->confirmDeleteId = null;
            }
        } catch (\Exception $e) {
            DB::connection('sarafi')->rollBack();
            session()->flash('error', 'خطا در حذف تبدیل ارز: ' . $e->getMessage());
            Log::error('Delete conversion error: ' . $e->getMessage());
            $this->confirmDeleteId = null;
        }
    }

    /**
     * ریست فرم
     */
    public function resetForm()
    {
        $this->reset([
            'selectedAccount',
            'selectedCustomerId',
            'from_currency',
            'to_currency',
            'buy_amount',
            'sell_amount',
            'currency_rate',
            'description',
            'zone_sender',
            'zone_receiver',
            'by_sender',
            'by_receiver',
            'editingConversionId',
            'withdrawalAmountInWords',
            'receivedAmountInWords',
            'currencyRateInWords',
        ]);

        $this->transactionType = 'خرید';
        $this->transaction_date = Jalalian::now()->format('Y/m/d');
        $this->updateCustomerCurrencyBalance();
    }


   // ==================== متدهای PDF ====================

    /**
     * Generate PDF for conversion transaction
     */
    private function generateConversionPdf($conversionId)
    {
        try {
            $conversion = ConversionInAccounts::with(['customer', 'user'])->findOrFail($conversionId);
            
            // Check user access
            $user = Auth::guard('sarafi')->user();
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

            $html = view('pdf.Sarafi.conversion-in-account', compact('conversion'))->render();
            $mpdf->WriteHTML($html);

            $fileName = 'تبدیل_ارز_در_حساب_' . $conversion->id . '_' . $conversion->type . '.pdf';

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
            $conversion = ConversionInAccounts::findOrFail($conversionId);
            
            // Check user access
            $user = Auth::guard('sarafi')->user();
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


    /**
     * نمایش گزارش
     */
    public function showReport()
    {
        if (!$this->selectedCustomerId) {
            session()->flash('error', 'لطفاً ابتدا یک مشتری را انتخاب کنید');
            return;
        }

        $customer = Customer::find($this->selectedCustomerId);
        if (!$customer) {
            session()->flash('error', 'مشتری انتخاب شده یافت نشد');
            return;
        }

        session([
            'selected_customer_id' => $this->selectedCustomerId,
            'selected_customer_name' => $customer->fullname,
            'selected_customer_account' => $customer->account_number
        ]);

        return redirect()->route('sarafi.transaction-reports');
    }

    // ==================== متدهای کمکی ====================

    /**
     * تولید توضیحات برای تراکنش برداشت
     */
    private function generateWithdrawalDescription(): string
    {
        $baseDescription = $this->description ? $this->description . ' - ' : '';
        $conversionType = $this->transactionType === 'خرید' ? 'خرید' : 'فروش';
        
        return $baseDescription . 'تبدیل به ' . $this->getCurrencyName($this->to_currency) . ' (' . $conversionType . ')';
    }

    /**
     * تولید توضیحات برای تراکنش دریافت
     */
    private function generateDepositDescription(): string
    {
        $baseDescription = $this->description ? $this->description . ' - ' : '';
        $conversionType = $this->transactionType === 'خرید' ? 'خرید' : 'فروش';
        
        return $baseDescription . 'تبدیل از ' . $this->getCurrencyName($this->from_currency) . ' (' . $conversionType . ')';
    }

    /**
     * دریافت موجودی یک ارز خاص برای مشتری
     */
    private function getCustomerCurrencyBalance($customerId, $currencyCode): float
    {
        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        $transactions = Transaction::where('customer_id', $customerId)
            ->where('admin_id', $adminId)
            ->where('currency', $currencyCode)
            ->get();

        $balance = 0;
        foreach ($transactions as $transaction) {
            if ($transaction->type === 'رسید') {
                $balance += floatval($transaction->amount);
            } else {
                $balance -= floatval($transaction->amount);
            }
        }

        return max($balance, 0);
    }

    /**
     * به‌روزرسانی موجودی ارزهای مشتری
     */
       public function updateCustomerCurrencyBalance()
    {
        if (!$this->selectedCustomerId) {
            $this->currenciesdefault = [
                ['name' => 'افغانی', 'value' => 0],
                ['name' => 'دالر', 'value' => 0],
                ['name' => 'تومان', 'value' => 0],
                ['name' => 'یورو', 'value' => 0],
                ['name' => 'کلدار', 'value' => 0],
                ['name' => 'درهم', 'value' => 0],
                ['name' => 'لیره', 'value' => 0],
                ['name' => 'یوان', 'value' => 0],
                ['name' => 'روپیه', 'value' => 0],
                ['name' => 'خلاصه بیلانس به دالر', 'value' => 0],
            ];

            // ریست کردن موجودی‌های تفکیک شده
            $this->customerCashBalances = [];
            $this->customerBankBalances = [];
            $this->customerTotalBalances = [];
            return;
        }

        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        $transactions = Transaction::where('customer_id', $this->selectedCustomerId)
            ->where('admin_id', $adminId)
            ->get();

        // محاسبه موجودی‌های نقدی و بانکی جداگانه
        $cashBalances = [
            'افغانی' => 0,
            'دالر' => 0,
            'تومان' => 0,
            'یورو' => 0,
            'کلدار' => 0,
            'درهم' => 0,
            'لیره' => 0,
            'یوان' => 0,
            'روپیه' => 0,
        ];

        $bankBalances = [
            'افغانی' => 0,
            'دالر' => 0,
            'تومان' => 0,
            'یورو' => 0,
            'کلدار' => 0,
            'درهم' => 0,
            'لیره' => 0,
            'یوان' => 0,
            'روپیه' => 0,
        ];

        foreach ($transactions as $transaction) {
            $currencyName = $this->getCurrencyName($transaction->currency);
            $amount = $transaction->type === 'رسید' ? $transaction->amount : -$transaction->amount;

            if ($transaction->account_type === 'نقدی') {
                if (array_key_exists($currencyName, $cashBalances)) {
                    $cashBalances[$currencyName] += $amount;
                }
            } else {
                if (array_key_exists($currencyName, $bankBalances)) {
                    $bankBalances[$currencyName] += $amount;
                }
            }
        }

        $latestExchangeRate = ExchangeRates::latest()->first();
        $exchangeRates = [
            'افغانی' => $latestExchangeRate->afn_buy ?? 0.011,
            'دالر' => 1,
            'تومان' => $latestExchangeRate->irr_buy ?? 0.000024,
            'یورو' => $latestExchangeRate->eur_buy ?? 1.07,
            'کلدار' => $latestExchangeRate->pkr_buy ?? 0.0036,
            'درهم' => $latestExchangeRate->aed_buy ?? 0.27,
            'لیره' => $latestExchangeRate->try_buy ?? 0.031,
            'یوان' => $latestExchangeRate->cny_buy ?? 0.14,
            'روپیه' => 0.14,
        ];

        // محاسبه مجموع برای نمایش در کارت‌های اصلی
        $totalBalances = [];
        foreach ($cashBalances as $currency => $balance) {
            $totalBalances[$currency] = $balance + $bankBalances[$currency];
        }

        $totalInUsd = 0;
        foreach ($totalBalances as $currency => $balance) {
            if ($currency !== 'خلاصه بیلانس به دالر' && isset($exchangeRates[$currency])) {
                $totalInUsd += $balance * $exchangeRates[$currency];
            }
        }

        $this->currenciesdefault = [
            ['name' => 'افغانی', 'value' => $totalBalances['افغانی']],
            ['name' => 'دالر', 'value' => $totalBalances['دالر']],
            ['name' => 'تومان', 'value' => $totalBalances['تومان']],
            ['name' => 'یورو', 'value' => $totalBalances['یورو']],
            ['name' => 'کلدار', 'value' => $totalBalances['کلدار']],
            ['name' => 'درهم', 'value' => $totalBalances['درهم']],
            ['name' => 'لیره', 'value' => $totalBalances['لیره']],
            ['name' => 'یوان', 'value' => $totalBalances['یوان']],
            ['name' => 'روپیه', 'value' => $totalBalances['روپیه']],
            ['name' => 'خلاصه بیلانس به دالر', 'value' => $totalInUsd],
        ];

        // ذخیره موجودی‌های تفکیک شده برای نمایش در کارت‌های جدید
        $this->customerCashBalances = $cashBalances;
        $this->customerBankBalances = $bankBalances;
        $this->customerTotalBalances = $totalBalances;
    }


    /**
     * محاسبه موجودی هر ارز
     */
    private function calculateBalances($transactions): array
    {
        $balances = [
            'افغانی' => 0,
            'دالر' => 0,
            'تومان' => 0,
            'یورو' => 0,
            'کلدار' => 0,
            'درهم' => 0,
            'لیره' => 0,
            'یوان' => 0,
            'روپیه' => 0,
        ];

        foreach ($transactions as $transaction) {
            $currencyName = $this->getCurrencyName($transaction->currency);
            $amount = $transaction->type === 'رسید' ? $transaction->amount : -$transaction->amount;

            if (array_key_exists($currencyName, $balances)) {
                $balances[$currencyName] += $amount;
            }
        }

        return $balances;
    }

    /**
     * محاسبه کل موجودی به دالر
     */
    private function calculateTotalInUsd($balances): float
    {
        $exchangeRates = [
            'افغانی' => 0.011,
            'دالر' => 1,
            'تومان' => 0.000024,
            'یورو' => 1.07,
            'کلدار' => 0.0036,
            'درهم' => 0.27,
            'لیره' => 0.031,
            'یوان' => 0.14,
            'روپیه' => 0.14,
        ];

        $totalInUsd = 0;
        foreach ($balances as $currency => $balance) {
            if (isset($exchangeRates[$currency])) {
                $totalInUsd += $balance * $exchangeRates[$currency];
            }
        }

        return $totalInUsd;
    }

    /**
     * به‌روزرسانی نمایش موجودی‌ها
     */
    private function updateCurrenciesDefault($balances, $totalInUsd)
    {
        $this->currenciesdefault = [
            ['name' => 'افغانی', 'value' => $balances['افغانی']],
            ['name' => 'دالر', 'value' => $balances['دالر']],
            ['name' => 'تومان', 'value' => $balances['تومان']],
            ['name' => 'یورو', 'value' => $balances['یورو']],
            ['name' => 'کلدار', 'value' => $balances['کلدار']],
            ['name' => 'درهم', 'value' => $balances['درهم']],
            ['name' => 'لیره', 'value' => $balances['لیره']],
            ['name' => 'یوان', 'value' => $balances['یوان']],
            ['name' => 'روپیه', 'value' => $balances['روپیه']],
            ['name' => 'خلاصه بیلانس به دالر', 'value' => $totalInUsd],
        ];
    }

    /**
     * ریست کردن موجودی‌ها
     */
    private function resetCurrenciesDefault()
    {
        $this->currenciesdefault = [
            ['name' => 'افغانی', 'value' => 0],
            ['name' => 'دالر', 'value' => 0],
            ['name' => 'تومان', 'value' => 0],
            ['name' => 'یورو', 'value' => 0],
            ['name' => 'کلدار', 'value' => 0],
            ['name' => 'درهم', 'value' => 0],
            ['name' => 'لیره', 'value' => 0],
            ['name' => 'یوان', 'value' => 0],
            ['name' => 'روپیه', 'value' => 0],
            ['name' => 'خلاصه بیلانس به دالر', 'value' => 0],
        ];
    }

    /**
     * تبدیل عدد به حروف فارسی
     */
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

    /**
     * دریافت نام ارز
     */
    private function getCurrencyName($currencyCode)
    {
        $currencyMap = [
            'afn' => 'افغانی',
            'usd' => 'دالر',
            'irr' => 'تومان',
            'eur' => 'یورو',
            'pkr' => 'کلدار',
            'aed' => 'درهم',
            'try' => 'لیره',
            'cny' => 'یوان',
            'gbp' => 'پوند',
            'jpy' => 'ین',
            'sar' => 'ریال سعودی',
            'inr' => 'روپیه',
        ];

        return $currencyMap[$currencyCode] ?? $currencyCode;
    }
}