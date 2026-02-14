<?php

namespace App\Livewire\Sarafi;

use App\Models\Sarafi\BankAccount;
use App\Models\Sarafi\ConversionTransfers;
use App\Models\Sarafi\CurrencySafe;
use App\Models\Sarafi\Customer;
use App\Models\Sarafi\ExchangeRates;
use App\Models\Sarafi\ProfitRate;
use App\Models\Sarafi\Revenue;
use App\Models\Sarafi\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;
use Morilog\Jalali\Jalalian;
use NumberFormatter;

class ConversionTransfer extends Component
{
    use WithPagination;


    public $calculating = false;
    public $calculatingField = 'withdrawal';

    // حساب برداشت
    public $withdrawalAccount = null;
    public $withdrawalCustomerId;
    public $withdrawalCustomer = null;

    // حساب دریافت
    public $depositAccount;
    public $depositCustomerId;

    // اطلاعات تبدیل ارز
    public $from_currency = '';
    public $withdrawal_amount = '';
    public $to_currency = '';
    public $received_amount = '';
    public $currency_rate = '';
    public $date;
    public $description = '';
    public $zone_sender = '';
    public $zone_receiver = '';
    public $by_sender = '';
    public $by_receiver = '';

    // برای نمایش حروفی
    public $withdrawalAmountInWords = '';
    public $receivedAmountInWords = '';
    public $currencyRateInWords = '';

    // تنظیمات فرم
    public $transactionType = 'خرید';
    public $from_account = 'نقدی';
    public $to_account = 'نقدی';
    public $currencies = [];
    public $customers = [];
    public $search = '';
    public $filteredCustomers;
    public $accountSearch = '';
    public $confirmDeleteId = null;
    public $editingConversionId = null;

    // متغیرهای موجودی‌ها
    public $customerCashBalances = [];
    public $customerBankBalances = [];
    public $customerTotalBalances = [];
    public $zones = [];

    // متغیرهای سود/ضرر
    public $profit_display = '';
    public $loss_display = '';
    public $profit_loss_display = [];
    public $deleting = false;

    // موجودی‌های پیش‌فرض
    public $currenciesdefault = [
        ['name' => 'افغانی', 'value' => 0],
        ['name' => 'دالر', 'value' => 0],
        ['name' => 'تومان', 'value' => 0],
        ['name' => 'یورو', 'value' => 0],
        ['name' => 'کلدار', 'value' => 0],
        ['name' => 'درهم', 'value' => 0],
        ['name' => 'لیره', 'value' => 0],
        ['name' => 'یوان', 'value' => 0],
        ['name' => 'خلاصه بیلانس به دالر', 'value' => 0],
    ];

    /**
     * رندر صفحه
     */
    public function render()
    {
        $user = Auth::guard('sarafi')->user();

        if (!$user) {
            return view('livewire.sarafi.conversion-transfer', [
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
        $query = ConversionTransfers::select(
            'conversion_transfer.*',
            'from_customer.fullname as from_customer_name',
            'from_customer.account_number as from_customer_account',
            'to_customer.fullname as to_customer_name',
            'to_customer.account_number as to_customer_account'
        )
            ->leftJoin('customers as from_customer', 'conversion_transfer.form_customer', '=', 'from_customer.id')
            ->leftJoin('customers as to_customer', 'conversion_transfer.to_customer', '=', 'to_customer.id')
            ->where('conversion_transfer.admin_id', $adminId);

        // اعمال جستجو اگر مقدار وجود دارد
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('from_customer.fullname', 'like', '%' . $this->search . '%')
                    ->orWhere('from_customer.account_number', 'like', '%' . $this->search . '%')
                    ->orWhere('to_customer.fullname', 'like', '%' . $this->search . '%')
                    ->orWhere('to_customer.account_number', 'like', '%' . $this->search . '%')
                    ->orWhere('conversion_transfer.from_currency', 'like', '%' . $this->search . '%')
                    ->orWhere('conversion_transfer.to_currency', 'like', '%' . $this->search . '%')
                    ->orWhere('conversion_transfer.withdrawal_amount', 'like', '%' . $this->search . '%')
                    ->orWhere('conversion_transfer.received_amount', 'like', '%' . $this->search . '%')
                    ->orWhere('conversion_transfer.currency_rate', 'like', '%' . $this->search . '%')
                    ->orWhere('conversion_transfer.description', 'like', '%' . $this->search . '%')
                    ->orWhere('conversion_transfer.type', 'like', '%' . $this->search . '%')
                    ->orWhere('conversion_transfer.zone_sender', 'like', '%' . $this->search . '%')
                    ->orWhere('conversion_transfer.zone_receiver', 'like', '%' . $this->search . '%');
            });
        }

        $conversionTransactions = $query->latest('conversion_transfer.created_at')->paginate(10);

        return view('livewire.sarafi.conversion-transfer', [
            'customers' => collect($this->customers),
            'conversionTransactions' => $conversionTransactions,
        ]);
    }

    /**
     * نمایش گزارش مشتری
     */
    public function showReport()
    {
        if (!$this->withdrawalCustomerId) {
            session()->flash('error', 'لطفاً ابتدا یک مشتری را برای حساب برداشت انتخاب کنید');
            return redirect()->back();
        }

        $customer = Customer::find($this->withdrawalCustomerId);
        if (!$customer) {
            session()->flash('error', 'مشتری انتخاب شده یافت نشد');
            return redirect()->back();
        }

        session([
            'selected_customer_id' => $this->withdrawalCustomerId,
            'selected_customer_name' => $customer->fullname,
            'selected_customer_account' => $customer->account_number
        ]);

        return redirect()->route('sarafi.transaction-reports');
    }

    /**
     * جستجوی تراکنش‌ها
     */
    public function search()
    {
        $this->resetPage();
    }

    /**
     * بارگذاری مشتریان
     */
    private function loadCustomers($adminId)
    {
        $relatedUserIds = \App\Models\Sarafi\User::where('admin_id', $adminId)
            ->pluck('id')
            ->push($adminId)
            ->toArray();

        $this->customers = Customer::select('id', 'account_number', 'fullname', 'admin_id')
            ->with(['admins' => function ($query) use ($adminId) {
                $query->where('customer_admin.admin_id', $adminId);
            }])
            ->where(function ($query) use ($adminId, $relatedUserIds) {
                $query->where('admin_id', $adminId)
                    ->orWhereHas('admins', function ($q) use ($adminId) {
                        $q->where('customer_admin.admin_id', $adminId);
                    })
                    ->orWhereHas('transactions', function ($t) use ($relatedUserIds) {
                        $t->whereIn('user_id', $relatedUserIds)
                            ->orWhereIn('admin_id', $relatedUserIds);
                    });
            })
            ->orderBy('fullname')
            ->get()
            ->map(function ($customer) use ($adminId) {
                return [
                    'id' => $customer->id,
                    'account_number' => $customer->account_number,
                    'fullname' => $customer->fullname,
                    'admin_id' => $customer->admin_id,
                    'is_mine' => $customer->admin_id == $adminId,
                    'is_linked' => $customer->admins->isNotEmpty() && $customer->admin_id != $adminId
                ];
            })
            ->toArray();
    }

    /**
     * تبدیل عدد به حروف
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
     * تغییر نوع تراکنش (خرید/فروش)
     */
    public function toggleTransactionType()
    {
        $this->transactionType = $this->transactionType === 'خرید' ? 'فروش' : 'خرید';

        $this->calculateReceivedAmount();
        $this->dispatch('transactionTypeToggled');
    }

    /**
     * محاسبه مبلغ دریافت بر اساس مبلغ برداشت (منطق اصلی)
     */
    public function calculateReceivedAmount()
    {
        if ($this->withdrawal_amount && $this->currency_rate && $this->from_currency && $this->to_currency) {
            $fromCurrency = $this->from_currency;
            $toCurrency = $this->to_currency;

            $amount = floatval(str_replace(',', '', $this->withdrawal_amount));
            $rate = floatval(str_replace(',', '', $this->currency_rate));

            if ($rate == 0) {
                $this->received_amount = '';
                $this->receivedAmountInWords = '';
                return;
            }

            $calculatedAmount = 0;

            // حالت‌های خاص AFN ↔ IRR
            if ($fromCurrency === 'afn' && $toCurrency === 'irr') {
                $calculatedAmount = ($amount * 1000) / $rate;
            } elseif ($fromCurrency === 'irr' && $toCurrency === 'afn') {
                $calculatedAmount = ($amount * $rate) / 1000;
            }
            // حالت‌های USD ↔ AFN
            elseif ($fromCurrency === 'afn' && $toCurrency === 'usd') {
                $calculatedAmount = $amount / $rate;
            } elseif ($fromCurrency === 'usd' && $toCurrency === 'afn') {
                $calculatedAmount = $amount * $rate;
            }
            // سایر تبدیل‌های عمومی
            else {
                $calculatedAmount = $amount * $rate;
            }

            $calculatedAmount = round($calculatedAmount, 2);
            $this->received_amount = $calculatedAmount;

            // تبدیل به حروف
            $this->convertAmountToWords($this->withdrawal_amount, 'withdrawalAmountInWords');
            $this->convertAmountToWords($calculatedAmount, 'receivedAmountInWords');
            $this->convertAmountToWords($this->currency_rate, 'currencyRateInWords');

            // محاسبه سود/ضرر
            $this->calculateRealTimeProfitLoss();
        } else {
            $this->received_amount = '';
            $this->withdrawalAmountInWords = '';
            $this->receivedAmountInWords = '';
            $this->currencyRateInWords = '';
            $this->resetProfitLossDisplay();
        }
    }

    /**
     * Event Listeners برای محاسبه خودکار دوطرفه
     */
    public function updated($property)
    {
        // جلوگیری از حلقه بی‌نهایت
        if ($this->calculating) {
            return;
        }

        $this->calculating = true;

        try {
            // تشخیص اینکه کدام فیلد تغییر کرده است
            if ($property === 'withdrawal_amount') {
                $this->calculatingField = 'withdrawal';
                $this->calculateReceivedAmount();
            } elseif ($property === 'received_amount') {
                $this->calculatingField = 'received';
                $this->calculateWithdrawalAmount();
            } elseif (in_array($property, ['currency_rate', 'from_currency', 'to_currency', 'transactionType', 'from_account', 'to_account'])) {
                // اگر فیلدهای دیگر تغییر کردند، بر اساس آخرین فیلد محاسبه کنیم
                if ($this->calculatingField === 'withdrawal' && $this->withdrawal_amount) {
                    $this->calculateReceivedAmount();
                } elseif ($this->calculatingField === 'received' && $this->received_amount) {
                    $this->calculateWithdrawalAmount();
                }
            }
        } finally {
            $this->calculating = false;
        }

        // تبدیل به حروف
        if ($property === 'withdrawal_amount') {
            $this->convertAmountToWords($this->withdrawal_amount, 'withdrawalAmountInWords');
        }

        if ($property === 'currency_rate') {
            $this->convertAmountToWords($this->currency_rate, 'currencyRateInWords');
        }

        if ($property === 'received_amount') {
            $this->convertAmountToWords($this->received_amount, 'receivedAmountInWords');
        }
    }


    /**
     * محاسبه مبلغ برداشت بر اساس مبلغ دریافت (منطق معکوس)
     */
    public function calculateWithdrawalAmount()
    {
        if ($this->received_amount && $this->currency_rate && $this->from_currency && $this->to_currency) {
            $fromCurrency = $this->from_currency;
            $toCurrency = $this->to_currency;

            $amount = floatval(str_replace(',', '', $this->received_amount));
            $rate = floatval(str_replace(',', '', $this->currency_rate));

            if ($rate == 0) {
                $this->withdrawal_amount = '';
                $this->withdrawalAmountInWords = '';
                return;
            }

            $calculatedAmount = 0;

            // حالت‌های خاص AFN ↔ IRR (معکوس)
            if ($fromCurrency === 'afn' && $toCurrency === 'irr') {
                $calculatedAmount = ($amount * $rate) / 1000;
            } elseif ($fromCurrency === 'irr' && $toCurrency === 'afn') {
                $calculatedAmount = ($amount * 1000) / $rate;
            }
            // حالت‌های USD ↔ AFN (معکوس)
            elseif ($fromCurrency === 'afn' && $toCurrency === 'usd') {
                $calculatedAmount = $amount * $rate;
            } elseif ($fromCurrency === 'usd' && $toCurrency === 'afn') {
                $calculatedAmount = $amount / $rate;
            }
            // سایر تبدیل‌های عمومی (معکوس)
            else {
                $calculatedAmount = $amount / $rate;
            }

            $calculatedAmount = round($calculatedAmount, 2);
            $this->withdrawal_amount = $calculatedAmount;

            // تبدیل به حروف
            $this->convertAmountToWords($calculatedAmount, 'withdrawalAmountInWords');
            $this->convertAmountToWords($this->received_amount, 'receivedAmountInWords');
            $this->convertAmountToWords($this->currency_rate, 'currencyRateInWords');

            // محاسبه سود/ضرر
            $this->calculateRealTimeProfitLoss();
        } else {
            $this->withdrawal_amount = '';
            $this->withdrawalAmountInWords = '';
            $this->receivedAmountInWords = '';
            $this->currencyRateInWords = '';
            $this->resetProfitLossDisplay();
        }
    }



    /**
     * تنظیم فیلد محاسبه هنگام کلیک روی input
     */
    public function setCalculatingField($field)
    {
        $this->calculatingField = $field;
    }


    /**
     * محاسبه و نمایش سود/ضرر در زمان واقعی
     */
    public function calculateRealTimeProfitLoss()
    {
        try {
            if (!$this->withdrawal_amount || !$this->currency_rate || !$this->from_currency || !$this->to_currency) {
                $this->resetProfitLossDisplay();
                return;
            }

            $profitLoss = $this->calculateProfitOrLoss();

            $this->profit_loss_display = [
                'profit' => $profitLoss['profit'],
                'loss' => $profitLoss['loss'],
                'predefined_rate' => $profitLoss['predefined_rate'],
                'amount_with_predefined_rate' => $profitLoss['amount_with_predefined_rate'],
                'amount_with_entered_rate' => $profitLoss['amount_with_entered_rate'],
                'difference' => $profitLoss['difference'],
                'rate_type_used' => $profitLoss['rate_type_used']
            ];

            $this->updateProfitLossDisplay();
        } catch (\Exception $e) {
            Log::error('خطا در محاسبه سود/ضرر زمان واقعی: ' . $e->getMessage());
            $this->resetProfitLossDisplay();
        }
    }

    /**
     * به‌روزرسانی نمایش سود/ضرر
     */
    private function updateProfitLossDisplay()
    {
        $profit = $this->profit_loss_display['profit'] ?? 0;
        $loss = $this->profit_loss_display['loss'] ?? 0;
        $rateType = $this->profit_loss_display['rate_type_used'] ?? 'sell';

        if ($profit > 0) {
            $this->profit_display = 'سود: ' . number_format($profit, 4) . ' دالر (با نرخ ' . ($rateType === 'sell' ? 'فروش' : 'خرید') . ')';
            $this->loss_display = '';
        } elseif ($loss > 0) {
            $this->profit_display = '';
            $this->loss_display = 'ضرر: ' . number_format($loss, 4) . ' دالر (با نرخ ' . ($rateType === 'sell' ? 'فروش' : 'خرید') . ')';
        } else {
            $this->profit_display = 'بدون سود/ضرر';
            $this->loss_display = '';
        }
    }

    /**
     * ریست نمایش سود/ضرر
     */
    private function resetProfitLossDisplay()
    {
        $this->profit_loss_display = [
            'profit' => 0,
            'loss' => 0,
            'predefined_rate' => 0,
            'amount_with_predefined_rate' => 0,
            'amount_with_entered_rate' => 0,
            'difference' => 0
        ];

        $this->profit_display = '';
        $this->loss_display = '';
    }

    /**
     * محاسبه سود/ضرر تبدیل ارز
     */
    private function calculateProfitOrLoss()
    {
        try {
            Log::info('=== شروع محاسبه سود/ضرر ===', [
                'from_currency' => $this->from_currency,
                'to_currency' => $this->to_currency,
                'withdrawal_amount' => $this->withdrawal_amount,
                'received_amount' => $this->received_amount,
                'currency_rate' => $this->currency_rate,
                'transaction_type' => $this->transactionType,
                'rate_type_used' => $this->getTransferRateType()
            ]);

            $predefinedRate = $this->getTransferPredefinedRate();

            if ($predefinedRate === null || $predefinedRate == 0) {
                Log::warning("نرخ از پیش تعیین شده یافت نشد");
                return $this->getDefaultProfitLossResult();
            }

            // محاسبه مقدار به ارز مقصد با نرخ پیش‌فرض
            $amountWithPredefinedRate = $this->calculateTransferWithPredefinedRate($predefinedRate);

            // مقدار دریافتی واقعی (از کاربر/سیستم)
            $amountWithEnteredRate = floatval($this->received_amount);

            // اگر ارز مقصد USD باشد، مقدار پیش‌فرض را هم به USD تبدیل کن
            if ($this->to_currency === 'usd') {
                $amountWithPredefinedRate = $this->convertTransferToUsd($amountWithPredefinedRate, $this->to_currency);
            }

            Log::info('مقادیر محاسبه شده', [
                'transaction_type' => $this->transactionType,
                'rate_type' => $this->getTransferRateType(),
                'predefined_rate' => $predefinedRate,
                'amount_with_predefined_rate' => $amountWithPredefinedRate,
                'amount_with_entered_rate' => $amountWithEnteredRate
            ]);

            // تفاوت سود/ضرر
            $difference = $amountWithPredefinedRate - $amountWithEnteredRate;

            $profit = $difference > 0 ? $difference : 0;
            $loss = $difference < 0 ? abs($difference) : 0;

            return [
                'profit' => round($profit, 4),
                'loss' => round($loss, 4),
                'predefined_rate' => round($predefinedRate, 4),
                'amount_with_predefined_rate' => round($amountWithPredefinedRate, 4),
                'amount_with_entered_rate' => round($amountWithEnteredRate, 4),
                'difference' => round($difference, 4),
                'rate_type_used' => $this->getTransferRateType()
            ];
        } catch (\Exception $e) {
            Log::error('❌ خطا در محاسبه سود/ضرر: ' . $e->getMessage());
            return $this->getDefaultProfitLossResult();
        }
    }


    /**
     * تعیین نوع حساب (نقدی/بانکی) بر اساس نوع تراکنش
     */
    private function getAccountTypeForRate()
    {
        if ($this->transactionType === 'خرید') {
            return $this->from_account === 'نقدی' ? 'cash' : 'bank';
        } else {
            return $this->to_account === 'نقدی' ? 'cash' : 'bank';
        }
    }


    /**
     * دریافت نرخ از پیش تعیین شده برای انتقال
     */
    private function getTransferPredefinedRate()
    {

        $rateType = $this->getTransferRateType();
        $accountType = $this->getAccountTypeForRate();

        Log::info("جستجوی نرخ برای انتقال: {$this->from_currency} → {$this->to_currency} با نوع: {$rateType} و حساب: {$accountType}");
        if (($this->from_currency === 'afn' && $this->to_currency === 'irr') ||
            ($this->from_currency === 'irr' && $this->to_currency === 'afn')
        ) {
            $user = Auth::guard('sarafi')->user();
            $adminId = $user->admin_id ?? $user->id;

            $afnProfitRate = ProfitRate::where('source_currency', 'afn')
                ->where('admin_id', $adminId)
                ->latest()
                ->first();

            if ($afnProfitRate) {
                $field = 'irr_' . $rateType . '_' . $accountType;
                $rate = $afnProfitRate->{$field} ?? 0;

                if ($rate > 0) {
                    return $rate;
                }
            }

            Log::warning("❌ نرخ برای تبدیل {$this->from_currency} ↔ {$this->to_currency} یافت نشد");
            return null;
        }

        // حالت خاص: تبدیل USD ↔ AFN
        if (($this->from_currency === 'usd' && $this->to_currency === 'afn') ||
            ($this->from_currency === 'afn' && $this->to_currency === 'usd')
        ) {
            $user = Auth::guard('sarafi')->user();
            $adminId = $user->admin_id ?? $user->id;

            $usdProfitRate = ProfitRate::where('source_currency', 'usd')
                ->where('admin_id', $adminId)
                ->latest()
                ->first();

            if ($usdProfitRate) {
                $field = 'afn_' . $rateType . '_' . $accountType;
                $rate = $usdProfitRate->{$field} ?? 0;

                if ($rate > 0) {
                    return $rate;
                }
            }

            Log::warning("❌ نرخ برای تبدیل {$this->from_currency} ↔ {$this->to_currency} یافت نشد");
            return null;
        }

        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        $usdProfitRate = ProfitRate::where('source_currency', 'usd')
            ->where('admin_id', $adminId)
            ->latest()
            ->first();

        if ($usdProfitRate) {
            if ($this->from_currency === 'usd') {
                $field = $this->to_currency . '_' . $rateType . '_' . $accountType;
                $rate = $usdProfitRate->{$field} ?? null;

                if ($rate && $rate > 0) {
                    return $rate;
                }
            } else if ($this->to_currency === 'usd') {
                $field = $this->from_currency . '_' . $rateType . '_' . $accountType;
                $rate = $usdProfitRate->{$field} ?? null;

                if ($rate && $rate > 0) {
                    return 1 / $rate;
                }
            } else {
                $fromField = $this->from_currency . '_' . $rateType . '_' . $accountType;
                $toField = $this->to_currency . '_' . $rateType . '_' . $accountType;

                $fromRate = $usdProfitRate->{$fromField} ?? null;
                $toRate = $usdProfitRate->{$toField} ?? null;

                if ($fromRate && $toRate && $fromRate > 0 && $toRate > 0) {
                    return $toRate / $fromRate;
                }
            }
        }

        Log::warning("❌ هیچ نرخ مناسبی برای {$this->from_currency} به {$this->to_currency} با نوع {$rateType} یافت نشد");
        return null;
    }



    /**
     * تعیین نوع نرخ مورد نیاز برای انتقال
     */
    private function getTransferRateType()
    {
        return $this->transactionType === 'خرید' ? 'sell' : 'buy';
    }
    /**
     * محاسبه با نرخ از پیش تعیین شده
     */
    private function calculateTransferWithPredefinedRate($predefinedRate)
    {
        $amount = floatval(str_replace(',', '', $this->withdrawal_amount));

        Log::info("محاسبه انتقال با نرخ پیش‌فرض", [
            'amount' => $amount,
            'from_currency' => $this->from_currency,
            'to_currency' => $this->to_currency,
            'predefined_rate' => $predefinedRate,
            'transaction_type' => $this->transactionType,
            'rate_type' => $this->getTransferRateType()
        ]);

        $result = 0;

        // حالت های خاص ارز
        if ($this->from_currency === 'irr' && $this->to_currency === 'afn') {
            $result = ($amount * $predefinedRate) / 1000;
        } elseif ($this->from_currency === 'afn' && $this->to_currency === 'irr') {
            $result = ($amount * 1000) / $predefinedRate;
        } elseif ($this->from_currency === 'afn' && $this->to_currency === 'usd') {
            // مستقیماً AFN ÷ نرخ USD
            $result = $amount / $predefinedRate;
        } elseif ($this->from_currency === 'usd' && $this->to_currency === 'afn') {
            $result = $amount * $predefinedRate;
        } else {
            // سایر ارزها
            $result = $amount * $predefinedRate;
        }

        return $result;
    }

    /**
     * تبدیل به دالر
     */
    /**
     * تبدیل به دالر
     */
    private function convertTransferToUsd($amount, $currency)
    {
        if ($currency === 'usd') {
            return $amount;
        }

        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        $usdProfitRate = ProfitRate::where('source_currency', 'usd')
            ->where('admin_id', $adminId)
            ->latest()
            ->first();

        if (!$usdProfitRate) {
            Log::warning("رکورد USD یافت نشد");
            return 0;
        }

        $rateType = $this->getTransferRateType();
        $accountType = $this->getAccountTypeForRate(); // تغییر این خط

        $buyField = $currency . '_buy_' . $accountType;
        $rate = $usdProfitRate->{$buyField} ?? 0;

        if ($rate <= 0) {
            $sellField = $currency . '_sell_' . $accountType;
            $rate = $usdProfitRate->{$sellField} ?? 0;
        }

        if ($rate > 0) {
            return $amount / $rate;
        }

        Log::warning("نرخ تبدیل برای {$currency} یافت نشد");
        return 0;
    }

    /**
     * نتیجه پیش‌فرض برای سود/ضرر
     */
    private function getDefaultProfitLossResult()
    {
        return [
            'profit' => 0,
            'loss' => 0,
            'predefined_rate' => 0,
            'amount_with_predefined_rate' => 0,
            'amount_with_entered_rate' => 0,
            'difference' => 0
        ];
    }

    /**
     * ثبت سود/ضرر
     */
    private function recordTransferProfitLoss($conversionId, $profitLoss)
    {
        try {
            $user = Auth::guard('sarafi')->user();
            $adminId = $user->admin_id ?? $user->id;

            if ($profitLoss['profit'] > 0 || $profitLoss['loss'] > 0) {
                $revenueData = [
                    'currency' => 'usd',
                    'profit' => $profitLoss['profit'],
                    'lost' => $profitLoss['loss'],
                    'from' => 'انتقال بین حساب‌ها',
                    'description' => $this->generateTransferProfitLossDescription($profitLoss),
                    'user_id' => $user->id,
                    'admin_id' => $adminId,
                    'conversion_transfer_in_account_id' => $conversionId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $revenue = Revenue::create($revenueData);
                return $revenue;
            }

            return null;
        } catch (\Exception $e) {
            Log::error('❌ خطا در ثبت سود/ضرر انتقال: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * تولید توضیحات برای سود/ضرر
     */
    private function generateTransferProfitLossDescription($profitLoss)
    {
        $fromCurrency = $this->getCurrencyName($this->from_currency);
        $toCurrency = $this->getCurrencyName($this->to_currency);

        $description = "سود/ضرر انتقال از {$fromCurrency} به {$toCurrency} - ";
        $description .= "مبلغ: " . number_format($this->withdrawal_amount) . " {$this->from_currency} - ";
        $description .= "حساب مبدا: {$this->from_account} - ";
        $description .= "حساب مقصد: {$this->to_account} - ";
        $description .= "نرخ وارد شده: " . number_format($this->currency_rate, 4) . " - ";
        $description .= "نرخ پیش‌فرض: " . number_format($profitLoss['predefined_rate'], 4);

        if ($profitLoss['profit'] > 0) {
            $description .= " - سود: " . number_format($profitLoss['profit'], 4) . " دالر";
        } else {
            $description .= " - ضرر: " . number_format($profitLoss['loss'], 4) . " دالر";
        }

        return $description;
    }

    /**
     * اعمال تغییرات صندوق برای تبدیل ارز
     */
    private function applySafeChanges($conversion, $adminId, $user)
    {
        try {
            Log::info("🔄 در حال اعمال تغییرات صندوق برای تبدیل ارز");

            $fromCurrencyColumn = strtolower($conversion->from_currency);
            $toCurrencyColumn = strtolower($conversion->to_currency);

            Log::info("پارامترهای صندوق", [
                'from_account' => $conversion->from_account,
                'to_account' => $conversion->to_account,
                'from_currency' => $fromCurrencyColumn,
                'to_currency' => $toCurrencyColumn,
                'withdrawal_amount' => $conversion->withdrawal_amount,
                'received_amount' => $conversion->received_amount
            ]);

            // ===== نقدی → بانکی =====
            if ($conversion->from_account === 'نقدی' && $conversion->to_account === 'بانکی') {
                Log::info("💵→🏦 حالت نقدی → بانکی");

                // کم کردن از صندوق نقدی (ارز مبدا)
                $safe = CurrencySafe::firstOrCreate([
                    'admin_id' => $adminId,
                ], [
                    'user_id' => $user->id,
                    'afn' => 0,
                    'usd' => 0,
                    'irr' => 0,
                    'eur' => 0,
                    'pkr' => 0,
                    'aed' => 0,
                    'try' => 0,
                    'cny' => 0,
                ]);

                $safe->decrement($fromCurrencyColumn, $conversion->withdrawal_amount);
                Log::info("✅ کم کردن {$conversion->withdrawal_amount} {$fromCurrencyColumn} از صندوق نقدی");

                // اضافه کردن به حساب بانکی (ارز مقصد)
                $bank = BankAccount::firstOrCreate([
                    'admin_id' => $adminId,
                ], [
                    'user_id' => $user->id,
                    'afn' => 0,
                    'usd' => 0,
                    'irr' => 0,
                    'eur' => 0,
                    'pkr' => 0,
                    'aed' => 0,
                    'try' => 0,
                    'cny' => 0,
                ]);

                $bank->increment($toCurrencyColumn, $conversion->received_amount);
                Log::info("✅ اضافه کردن {$conversion->received_amount} {$toCurrencyColumn} به حساب بانکی");
            }
            // ===== بانکی → نقدی =====
            elseif ($conversion->from_account === 'بانکی' && $conversion->to_account === 'نقدی') {
                Log::info("🏦→💵 حالت بانکی → نقدی");

                // کم کردن از حساب بانکی (ارز مبدا)
                $bank = BankAccount::where('admin_id', $adminId)->first();
                if ($bank) {
                    $bank->decrement($fromCurrencyColumn, $conversion->withdrawal_amount);
                    Log::info("✅ کم کردن {$conversion->withdrawal_amount} {$fromCurrencyColumn} از حساب بانکی");
                } else {
                    Log::warning("حساب بانکی برای ادمین {$adminId} یافت نشد. ایجاد رکورد جدید...");
                    $bank = BankAccount::create([
                        'admin_id' => $adminId,
                        'user_id' => $user->id,
                        $fromCurrencyColumn => -$conversion->withdrawal_amount,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                // اضافه کردن به صندوق نقدی (ارز مقصد)
                $safe = CurrencySafe::firstOrCreate([
                    'admin_id' => $adminId,
                ], [
                    'user_id' => $user->id,
                    $toCurrencyColumn => $conversion->received_amount,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $safe->increment($toCurrencyColumn, $conversion->received_amount);
                Log::info("✅ اضافه کردن {$conversion->received_amount} {$toCurrencyColumn} به صندوق نقدی");
            }
            // ===== حالت‌های دیگر =====
            elseif (($conversion->from_account === 'نقدی' && $conversion->to_account === 'نقدی') ||
                ($conversion->from_account === 'بانکی' && $conversion->to_account === 'بانکی')
            ) {
                Log::info("ℹ️ {$conversion->from_account}→{$conversion->to_account}: نیازی به تغییر صندوق نیست");
            } else {
                Log::warning("⚠️ حالت نامشخص: {$conversion->from_account}→{$conversion->to_account}");
            }

            Log::info("✅ تغییرات صندوق با موفقیت اعمال شد");
        } catch (\Exception $e) {
            Log::error('❌ خطا در اعمال تغییرات صندوق: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * برگرداندن تغییرات صندوق هنگام حذف یا ویرایش
     */
    private function reverseSafeChanges($conversion, $adminId, $user)
    {
        try {
            Log::info("🔁 در حال برگرداندن تغییرات صندوق برای تبدیل ارز ID: {$conversion->id}");

            $fromCurrencyColumn = strtolower($conversion->from_currency);
            $toCurrencyColumn = strtolower($conversion->to_currency);

            Log::info("پارامترهای برگرداندن صندوق", [
                'from_account' => $conversion->from_account,
                'to_account' => $conversion->to_account,
                'from_currency' => $conversion->from_currency,
                'to_currency' => $conversion->to_currency,
                'withdrawal_amount' => $conversion->withdrawal_amount,
                'received_amount' => $conversion->received_amount
            ]);

            // ===== حالت ۱: نقدی → بانکی =====
            if ($conversion->from_account === 'نقدی' && $conversion->to_account === 'بانکی') {
                Log::info("⏪ برگرداندن حالت نقدی → بانکی");

                // معکوس: اضافه کردن به صندوق نقدی (ارز مبدا)
                $safe = CurrencySafe::where('admin_id', $adminId)->first();
                if ($safe) {
                    $safe->increment($fromCurrencyColumn, $conversion->withdrawal_amount);
                    Log::info("✅ اضافه کردن {$conversion->withdrawal_amount} {$fromCurrencyColumn} به صندوق نقدی");
                } else {
                    Log::warning("صندوق نقدی برای ادمین {$adminId} یافت نشد");
                }

                // معکوس: کم کردن از حساب بانکی (ارز مقصد)
                $bank = BankAccount::where('admin_id', $adminId)->first();
                if ($bank) {
                    $bank->decrement($toCurrencyColumn, $conversion->received_amount);
                    Log::info("✅ کم کردن {$conversion->received_amount} {$toCurrencyColumn} از حساب بانکی");
                } else {
                    Log::warning("حساب بانکی برای ادمین {$adminId} یافت نشد");
                }
            }
            // ===== حالت ۲: بانکی → نقدی =====
            elseif ($conversion->from_account === 'بانکی' && $conversion->to_account === 'نقدی') {
                Log::info("⏪ برگرداندن حالت بانکی → نقدی");

                // معکوس: اضافه کردن به حساب بانکی (ارز مبدا)
                $bank = BankAccount::where('admin_id', $adminId)->first();
                if ($bank) {
                    $bank->increment($fromCurrencyColumn, $conversion->withdrawal_amount);
                    Log::info("✅ اضافه کردن {$conversion->withdrawal_amount} {$fromCurrencyColumn} به حساب بانکی");
                } else {
                    Log::warning("حساب بانکی برای ادمین {$adminId} یافت نشد");
                }

                // معکوس: کم کردن از صندوق نقدی (ارز مقصد)
                $safe = CurrencySafe::where('admin_id', $adminId)->first();
                if ($safe) {
                    $safe->decrement($toCurrencyColumn, $conversion->received_amount);
                    Log::info("✅ کم کردن {$conversion->received_amount} {$toCurrencyColumn} از صندوق نقدی");
                } else {
                    Log::warning("صندوق نقدی برای ادمین {$adminId} یافت نشد");
                }
            }
            // ===== حالت‌های دیگر =====
            elseif (($conversion->from_account === 'نقدی' && $conversion->to_account === 'نقدی') ||
                ($conversion->from_account === 'بانکی' && $conversion->to_account === 'بانکی')
            ) {
                Log::info("ℹ️ {$conversion->from_account}→{$conversion->to_account}: نیازی به برگرداندن تغییرات صندوق نیست");
            } else {
                Log::warning("⚠️ حالت نامشخص: {$conversion->from_account}→{$conversion->to_account}");
            }

            Log::info("✅ تغییرات صندوق با موفقیت برگردانده شد");
        } catch (\Exception $e) {
            Log::error('❌ خطا در برگرداندن تغییرات صندوق: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * ثبت تبدیل ارز
     */
    public function submitConversion()
    {
        $this->validate([
            'withdrawalAccount' => 'required|integer|exists:sarafi.customers,id',
            'depositAccount' => 'required|integer|exists:sarafi.customers,id',
            'from_currency' => 'required|string',
            'to_currency' => 'required|string',
            'from_account' => 'required|string',
            'to_account' => 'required|string',
            'withdrawal_amount' => 'required|numeric|min:0.01',
            'received_amount' => 'required|numeric|min:0.01',
            'currency_rate' => 'required|numeric|min:0.0001',
            'date' => 'required|date',
            'description' => 'nullable|string|max:500',
            'zone_sender' => 'required|string',
            'zone_receiver' => 'required|string',
            'by_sender' => 'nullable|string|max:255',
            'by_receiver' => 'nullable|string|max:255',
        ]);

        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        DB::connection('sarafi')->beginTransaction();

        $senderCustomer = Customer::find($this->withdrawalAccount);
        $receiverCustomer = Customer::find($this->depositAccount);

        $senderName = $senderCustomer?->fullname ?? 'نامشخص';
        $receiverName = $receiverCustomer?->fullname ?? 'نامشخص';
        try {
            // محاسبه سود/ضرر
            Log::info('در حال محاسبه سود/ضرر برای انتقال...');
            $profitLoss = $this->calculateProfitOrLoss();

            // ذخیره رکورد قدیمی برای ویرایش
            $oldConversion = null;
            if ($this->editingConversionId) {
                $oldConversion = ConversionTransfers::find($this->editingConversionId);

                // برگرداندن تغییرات صندوق قدیمی
                if ($oldConversion) {
                    $this->reverseSafeChanges($oldConversion, $adminId, $user);
                }
            }

            if ($this->editingConversionId && $oldConversion) {
                // حالت ویرایش
                $conversion = $oldConversion;

                // آپدیت رکورد تبدیل ارز
                $conversion->update([
                    'form_customer' => $this->withdrawalAccount,
                    'from_currency' => $this->from_currency,
                    'withdrawal_amount' => $this->withdrawal_amount,
                    'to_customer' => $this->depositAccount,
                    'to_currency' => $this->to_currency,
                    'from_account' => $this->from_account,
                    'to_account' => $this->to_account,
                    'received_amount' => $this->received_amount,
                    'currency_rate' => $this->currency_rate,
                    'transaction_date' => $this->date,
                    'description' => $this->description,
                    'zone_sender' => $this->zone_sender,
                    'zone_receiver' => $this->zone_receiver,
                    'by_sender' => $this->by_sender,
                    'by_receiver' => $this->by_receiver,
                    'type' => $this->transactionType,
                ]);

                $conversionId = $conversion->id;

                // حذف تراکنش‌های قبلی
                Transaction::where('conversion_transfer_id', $conversion->id)->delete();
                // حذف سود/ضرر قبلی
                Revenue::where('conversion_transfer_in_account_id', $conversion->id)->delete();
            } else {
                // حالت ایجاد جدید
                $conversion = ConversionTransfers::create([
                    'form_customer' => $this->withdrawalAccount,
                    'from_currency' => $this->from_currency,
                    'withdrawal_amount' => $this->withdrawal_amount,
                    'to_customer' => $this->depositAccount,
                    'to_currency' => $this->to_currency,
                    'from_account' => $this->from_account,
                    'to_account' => $this->to_account,
                    'received_amount' => $this->received_amount,
                    'currency_rate' => $this->currency_rate,
                    'transaction_date' => $this->date,
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

            // ایجاد تراکنش برداشت برای حساب مبدا
            Transaction::create([
                'customer_id' => $this->withdrawalAccount,
                'user_id' => $user->id,
                'admin_id' => $adminId,
                'account_type' => $this->from_account,
                'currency' => $this->from_currency,
                'amount' => $this->withdrawal_amount,
                'type' => 'برد',
                'date' => $this->date,
                'description' => "ارز {$this->getCurrencyName($this->from_currency)} به ارز {$this->getCurrencyName($this->to_currency)} تبدیل شد و انتقال داده شد به حساب {$receiverName} به نرخ {$this->currency_rate}",
                'zone' => $this->zone_sender,
                'by' => $this->by_sender,
                'conversion_transfer_id' => $conversionId,
            ]);

            // ایجاد تراکنش دریافت برای حساب مقصد
            Transaction::create([
                'customer_id' => $this->depositAccount,
                'user_id' => $user->id,
                'admin_id' => $adminId,
                'currency' => $this->to_currency,
                'amount' => $this->received_amount,
                'account_type' => $this->to_account,
                'type' => 'رسید',
                'date' => $this->date,
                'description' => 'دریافت ارز ' . $this->getCurrencyName($this->to_currency) .
                    ' از حساب ' . $senderName,
                'zone' => $this->zone_receiver,
                'by' => $this->by_receiver,
                'conversion_transfer_id' => $conversionId,
            ]);


            // ثبت سود/ضرر
            $this->recordTransferProfitLoss($conversionId, $profitLoss);

            DB::connection('sarafi')->commit();
           $this->generateConversionPdf($conversionId);


            $message = $this->editingConversionId ? 'تبدیل ارز با موفقیت ویرایش شد.' : 'تبدیل ارز با موفقیت ثبت شد.';

            if ($profitLoss['profit'] > 0) {
                $message .= ' سود: ' . number_format($profitLoss['profit'], 4) . ' دالر';
            } elseif ($profitLoss['loss'] > 0) {
                $message .= ' ضرر: ' . number_format($profitLoss['loss'], 4) . ' دالر';
            }

            session()->flash('message', $message);
            $this->resetForm();
        } catch (\Exception $e) {
            DB::connection('sarafi')->rollBack();
            session()->flash('error', 'خطا در ثبت تبدیل ارز: ' . $e->getMessage());

            Log::error('Conversion transfer error: ' . $e->getMessage(), [
                'withdrawalAccount' => $this->withdrawalAccount,
                'depositAccount' => $this->depositAccount,
                'transactionType' => $this->transactionType,
                'editing' => $this->editingConversionId ? 'yes' : 'no',
            ]);
        }
    }

    /**
     * به‌روزرسانی موجودی‌های ارزی مشتری
     */
    public function updateCustomerCurrencyBalance()
    {
        if (!$this->withdrawalCustomerId) {
            $this->resetCurrenciesDefault();
            $this->customerCashBalances = [];
            $this->customerBankBalances = [];
            $this->customerTotalBalances = [];
            return;
        }

        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        $transactions = Transaction::where('customer_id', $this->withdrawalCustomerId)
            ->where('admin_id', $adminId)
            ->get();

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

        $this->customerCashBalances = $cashBalances;
        $this->customerBankBalances = $bankBalances;
        $this->customerTotalBalances = $totalBalances;
    }

    /**
     * ریست کردن موجودی‌های پیش‌فرض
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
     * انتخاب حساب برداشت
     */
    public function selectWithdrawalAccount($customerId)
    {
        $this->withdrawalAccount = $customerId;
        $this->withdrawalCustomer = Customer::find($customerId);
        $this->withdrawalCustomerId = $customerId;
        $this->accountSearch = '';
        $this->filteredCustomers = null;
        $this->updateCustomerCurrencyBalance($customerId);
    }

    /**
     * انتخاب حساب دریافت
     */
    public function selectDepositAccount($customerId)
    {
        $this->depositCustomerId = $customerId;
        $this->depositAccount = $customerId;
    }

    /**
     * ریست فرم
     */
    public function resetForm()
    {
        $this->reset([
            'withdrawalAccount',
            'depositAccount',
            'withdrawalCustomerId',
            'depositCustomerId',
            'from_currency',
            'to_currency',
            'withdrawal_amount',
            'received_amount',
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
            'profit_display',
            'loss_display',
        ]);

        $this->transactionType = 'خرید';
        $this->date = Jalalian::now()->format('Y/m/d');
        $this->resetProfitLossDisplay();
    }

    /**
     * ویرایش تبدیل ارز
     */
    public function editConversion($conversionId)
    {
        $conversion = ConversionTransfers::with(['fromCustomer', 'toCustomer'])->find($conversionId);

        if ($conversion) {
            $this->editingConversionId = $conversionId;

            $this->withdrawalAccount = $conversion->form_customer;
            $this->depositAccount = $conversion->to_customer;
            $this->from_currency = $conversion->from_currency;
            $this->to_currency = $conversion->to_currency;
            $this->withdrawal_amount = $conversion->withdrawal_amount;
            $this->from_account = $conversion->from_account;
            $this->to_account = $conversion->to_account;
            $this->received_amount = $conversion->received_amount;
            $this->currency_rate = $conversion->currency_rate;
            $this->date = $conversion->transaction_date;
            $this->description = $conversion->description;
            $this->zone_sender = $conversion->zone_sender;
            $this->zone_receiver = $conversion->zone_receiver;
            $this->by_sender = $conversion->by_sender;
            $this->by_receiver = $conversion->by_receiver;
            $this->transactionType = $conversion->type;

            $this->withdrawalCustomerId = $conversion->form_customer;
            $this->depositCustomerId = $conversion->to_customer;

            $this->convertAmountToWords($this->withdrawal_amount, 'withdrawalAmountInWords');
            $this->convertAmountToWords($this->received_amount, 'receivedAmountInWords');
            $this->convertAmountToWords($this->currency_rate, 'currencyRateInWords');

            $this->updateCustomerCurrencyBalance($conversion->form_customer);
            $this->calculateRealTimeProfitLoss();

            $this->dispatch('edit-mode-activated', [
                'withdrawalAccount' => $this->withdrawalAccount,
                'depositAccount' => $this->depositAccount,
                'withdrawalCustomer' => $conversion->fromCustomer ? $conversion->fromCustomer->account_number . ' - ' . $conversion->fromCustomer->fullname : '',
                'depositCustomer' => $conversion->toCustomer ? $conversion->toCustomer->account_number . ' - ' . $conversion->toCustomer->fullname : ''
            ]);
        }
    }

    /**
     * تأیید حذف
     */
    public function confirmDelete($conversionId)
    {
        $this->confirmDeleteId = $conversionId;
        Log::info("تأیید حذف تبدیل ارز با ID: {$conversionId}");
    }

    /**
     * لغو حذف
     */
    public function cancelDelete()
    {
        $this->confirmDeleteId = null;
        $this->deleting = false;
    }

    /**
     * حذف تبدیل ارز
     */
    public function deleteConversion()
    {
        if (!$this->confirmDeleteId) {
            session()->flash('error', 'شناسه تبدیل ارز برای حذف مشخص نیست.');
            return;
        }

        $this->deleting = true;
        DB::connection('sarafi')->beginTransaction();

        try {
            Log::info("در حال حذف تبدیل ارز با ID: {$this->confirmDeleteId}");

            $conversion = ConversionTransfers::find($this->confirmDeleteId);

            if (!$conversion) {
                throw new \Exception('رکورد تبدیل ارز یافت نشد.');
            }

            Log::info("رکورد تبدیل ارز پیدا شد: {$conversion->id}", [
                'from_currency' => $conversion->from_currency,
                'to_currency' => $conversion->to_currency,
                'from_account' => $conversion->from_account,
                'to_account' => $conversion->to_account,
                'withdrawal_amount' => $conversion->withdrawal_amount,
                'received_amount' => $conversion->received_amount
            ]);

            // برگرداندن تغییرات صندوق
            $user = Auth::guard('sarafi')->user();
            $adminId = $user->admin_id ?? $user->id;

            // حذف تراکنش‌های مرتبط
            $transactionsDeleted = Transaction::where('conversion_transfer_id', $conversion->id)->delete();
            Log::info("{$transactionsDeleted} تراکنش مرتبط حذف شد");

            // حذف سود/ضرر مرتبط
            $revenuesDeleted = Revenue::where('conversion_transfer_in_account_id', $conversion->id)->delete();
            Log::info("{$revenuesDeleted} رکورد سود/ضرر مرتبط حذف شد");

            // حذف تبدیل ارز
            $conversion->delete();
            Log::info("رکورد تبدیل ارز با موفقیت حذف شد");

            DB::connection('sarafi')->commit();

            session()->flash('message', 'تبدیل ارز با موفقیت حذف شد و موجودی صندوق‌ها به حالت اول برگردانده شد.');
            $this->dispatch('refreshComponent');
        } catch (\Exception $e) {
            DB::connection('sarafi')->rollBack();
            session()->flash('error', 'خطا در حذف تبدیل ارز: ' . $e->getMessage());

            Log::error('Delete conversion error: ' . $e->getMessage(), [
                'conversion_id' => $this->confirmDeleteId,
                'user_id' => Auth::guard('sarafi')->user()->id ?? 'unknown'
            ]);
        } finally {
            $this->confirmDeleteId = null;
            $this->deleting = false;
        }
    }

    /**
     * دریافت نام فارسی ارز
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

    /**
     * چاپ تراکنش
     */
    public function printTransaction($conversionId)
    {
        try {
            $conversion = ConversionTransfers::findOrFail($conversionId);

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
     * ایجاد PDF برای تراکنش
     */
    private function generateConversionPdf($conversionId)
    {
        try {
            $conversion = ConversionTransfers::with(['fromCustomer', 'toCustomer', 'user'])->findOrFail($conversionId);

            $user = Auth::guard('sarafi')->user();
            if ($conversion->user_id !== $user->id && $conversion->admin_id !== $user->id) {
                session()->flash('error', 'دسترسی به این تراکنش مجاز نیست.');
                return null;
            }

            $mpdf = new \Mpdf\Mpdf([
                'mode' => 'utf-8',
                'format' => [72.1, 297],
                'directionality' => 'rtl',
                'margin_top' => 0,
                'margin_bottom' => 0,
                'margin_left' => 0,
                'margin_right' => 0,
                  'fontDir' => array_merge(
                    (new \Mpdf\Config\ConfigVariables())->getDefaults()['fontDir'],
                    [public_path('fonts/vazir/')]
                ),
                'fontdata' => (new \Mpdf\Config\FontVariables())->getDefaults()['fontdata'] + [
                    'vazir' => [

                        'R' => 'Vazir-Light.ttf',
                        'B' => 'Vazir-Bold.ttf',
                        'useOTL' => 0xFF,
                        'useKashida' => 75,
                    ],
                ],
                'default_font' => 'vazir',
                'tempDir' => storage_path('app/mpdf'),
            ]);

            $mpdf->SetAutoPageBreak(false);

            $html = view('pdf.Sarafi.conversion-transaction', compact('conversion'))->render();
            $mpdf->WriteHTML($html);

            $fileName = 'تبدیل_ارز_' . $conversion->id . '_' . $conversion->type . '.pdf';

            $path = storage_path('app/public/' . $fileName);

            // ذخیره PDF
            $mpdf->Output($path, 'F');

            // ارسال event به JS (Livewire v3)
            $this->dispatch('print-pdf', url: asset('storage/' . $fileName));
        } catch (\Exception $e) {
            Log::error('PDF generation error: ' . $e->getMessage());
            session()->flash('error', 'خطا در ایجاد PDF: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * مقداردهی اولیه
     */
    public function mount()
    {
        $this->date = Jalalian::now()->format('Y/m/d');
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
            $this->loadZones($adminId);
        }
    }

    /**
     * بارگذاری زون‌ها
     */

    private function loadZones($adminId)
    {
        $zones = \App\Models\Sarafi\User::where(function ($query) use ($adminId) {
            $query->where('id', $adminId)
                ->orWhere('admin_id', $adminId);
        })
            ->whereNotNull('zone')
            ->where('zone', '!=', '')
            ->pluck('zone')
            ->unique()
            ->values()
            ->toArray();

        if (empty($zones)) {
            $zones = ['غرب', 'مرکز', 'شمال', 'جنوب', 'شرق'];
        }

        $this->zones = $zones;

        if (!$this->zone_sender) {
            $this->zone_sender = $zones[0];
        }

        if (!$this->zone_receiver) {
            $this->zone_receiver = $zones[0];
        }
    }
}
