<?php

namespace App\Livewire\Sarafi;

use App\Models\Sarafi\ConversionInAccounts;
use App\Models\Sarafi\Customer;
use App\Models\Sarafi\ExchangeRates;
use App\Models\Sarafi\ProfitRate;
use App\Models\Sarafi\Revenue;
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

    public $selectedCustomer = null;
    public $selectedAccount = null;
    public $selectedCustomerId = null;
    
    // متغیرهای فرم
    public $calculatingField = 'buy';
    public $calculating = false;

    public $from_currency = '';
    public $buy_amount = '';
    public $to_currency = '';
    public $sell_amount = '';
    public $currency_rate = '';
    public $date;
    public $description = '';
    public $zone_sender = '';
    public $zone_receiver = '';
    public $by_sender = '';
    public $by_receiver = '';
    public $accountType = 'نقدی';
    public $zones = [];
    public $from_account = 'نقدی';
    public $to_account = 'نقدی';

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
        $this->date = Jalalian::now()->format('Y/m/d');
        $this->transactionType = 'خرید';
        $this->accountType = 'نقدی';
        $this->from_account = 'نقدی';
        $this->to_account = 'نقدی';

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
     * تعیین نوع حساب (نقدی/بانکی) بر اساس نوع تراکنش
     */
    private function getAccountTypeForRate()
    {
        if ($this->transactionType === 'خرید') {
            // در حالت خرید، از حساب مبدا استفاده می‌کنیم
            return $this->from_account === 'نقدی' ? 'cash' : 'bank';
        } else {
            // در حالت فروش، از حساب مقصد استفاده می‌کنیم
            return $this->to_account === 'نقدی' ? 'cash' : 'bank';
        }
    }

    private function getCurrencyFaName($code)
    {
        $currency = collect($this->currencies)
            ->firstWhere('code', strtolower($code));

        return $currency['name_fa'] ?? strtoupper($code);
    }

    /**
     * رندر کامپوننت
     */
    public function render()
    {
        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        if (!$user) {
            return view('livewire.sarafi.conversion-in-account', [
                'customers' => collect(),
                'conversionTransactions' => collect(),
            ]);
        }

        if (empty($this->customers)) {
            $this->loadCustomers($adminId);
        }

        $query = ConversionInAccounts::with(['customer'])
            ->where('admin_id', $adminId);

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->whereHas('customer', function ($customerQuery) {
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

        $this->customers = Customer::with('admins')
            ->where('admin_id', $adminId)
            ->orWhereHas('admins', function ($q) use ($adminId) {
                $q->where('customer_admin.admin_id', $adminId);
            })
            ->orderBy('fullname')
            ->get();
    }

    /**
     * انتخاب حساب مشتری
     */
    public function selectAccount($customerId)
    {
        try {
            $this->selectedCustomerId = $customerId;
            $this->selectedAccount = $customerId;

            // پیدا کردن مشتری و ذخیره شیء
            $this->selectedCustomer = Customer::find($customerId);

            if (!$this->selectedCustomer) {
                session()->flash('error', 'مشتری یافت نشد!');
                return;
            }

            // به‌روزرسانی موجودی
            $this->updateCustomerCurrencyBalance($customerId);

            // لاگ برای دیباگ
            Log::info("Account selected successfully", [
                'id' => $customerId,
                'name' => $this->selectedCustomer->fullname,
                'image' => $this->selectedCustomer->image,
                'phone' => $this->selectedCustomer->phone,
                'account_number' => $this->selectedCustomer->account_number
            ]);

            // dispatch event اگر نیاز دارید
            $this->dispatch('account-selected', [
                'customer_id' => $customerId,
                'customer_name' => $this->selectedCustomer->fullname
            ]);
        } catch (\Exception $e) {
            Log::error("Error in selectAccount: " . $e->getMessage());
            session()->flash('error', 'خطا در انتخاب حساب: ' . $e->getMessage());
        }
    }

    /**
     * تغییر نوع معامله (خرید/فروش) - نسخه بهبود یافته
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

        // جابجایی حساب‌ها (در حالت خرید/فروش)
        $tempAccount = $this->from_account;
        $this->from_account = $this->to_account;
        $this->to_account = $tempAccount;

        // محاسبه مجدد
        if ($this->calculatingField === 'buy' && $this->buy_amount && $this->currency_rate) {
            $this->calculateReceivedAmount();
        } elseif ($this->calculatingField === 'sell' && $this->sell_amount && $this->currency_rate) {
            $this->calculateBuyAmount();
        }

        // به‌روزرسانی حروف‌نویسی
        if ($this->buy_amount) {
            $this->convertAmountToWords($this->buy_amount, 'withdrawalAmountInWords', 2);
        }
        if ($this->sell_amount) {
            $this->convertAmountToWords($this->sell_amount, 'receivedAmountInWords', 2);
        }
        if ($this->currency_rate) {
            $this->convertAmountToWords($this->currency_rate, 'currencyRateInWords', 4);
        }

        $this->dispatch('transactionTypeToggled');
    }

    /**
     * تغییر نوع حساب
     */
    public function toggleAccountType($accountField)
    {
        if ($accountField === 'from') {
            $this->from_account = $this->from_account === 'نقدی' ? 'بانکی' : 'نقدی';
        } elseif ($accountField === 'to') {
            $this->to_account = $this->to_account === 'نقدی' ? 'بانکی' : 'نقدی';
        }
        
        // محاسبه مجدد سود/ضرر
        $this->calculateRealTimeProfitLoss();
    }

    /**
     * Event listener برای تغییر فیلدها - نسخه بهبود یافته
     */
    public function updated($property)
    {
        // اگر در حال محاسبه هستیم، از حلقه جلوگیری کن
        if ($this->calculating) {
            return;
        }

        $this->calculating = true;

        try {
            // تشخیص اینکه کدام فیلد تغییر کرده
            if ($property === 'buy_amount') {
                $this->calculatingField = 'buy';
                $this->calculateReceivedAmount();

                // همیشه تبدیل به حروف
                $this->convertAmountToWords($this->buy_amount, 'withdrawalAmountInWords', 2);
            } elseif ($property === 'sell_amount') {
                $this->calculatingField = 'sell';
                $this->calculateBuyAmount();

                // همیشه تبدیل به حروف
                $this->convertAmountToWords($this->sell_amount, 'receivedAmountInWords', 2);
            } elseif ($property === 'currency_rate') {
                // اگر نرخ تغییر کرد، بر اساس آخرین فیلد ویرایش شده محاسبه کن
                if ($this->calculatingField === 'buy' && $this->buy_amount) {
                    $this->calculateReceivedAmount();
                } elseif ($this->calculatingField === 'sell' && $this->sell_amount) {
                    $this->calculateBuyAmount();
                }

                // همیشه تبدیل به حروف
                $this->convertAmountToWords($this->currency_rate, 'currencyRateInWords', 4);
            } elseif (in_array($property, ['from_currency', 'to_currency', 'transactionType'])) {
                // اگر ارزها یا نوع معامله تغییر کرد، بر اساس آخرین فیلد محاسبه کن
                if ($this->calculatingField === 'buy' && $this->buy_amount && $this->currency_rate) {
                    $this->calculateReceivedAmount();
                } elseif ($this->calculatingField === 'sell' && $this->sell_amount && $this->currency_rate) {
                    $this->calculateBuyAmount();
                }

                // وقتی ارزها تغییر کردند، اگر مقداری وجود دارد، به حروف تبدیل کن
                if ($this->buy_amount) {
                    $this->convertAmountToWords($this->buy_amount, 'withdrawalAmountInWords', 2);
                }
                if ($this->sell_amount) {
                    $this->convertAmountToWords($this->sell_amount, 'receivedAmountInWords', 2);
                }
                if ($this->currency_rate) {
                    $this->convertAmountToWords($this->currency_rate, 'currencyRateInWords', 4);
                }
            }

            // اگر حساب‌ها تغییر کردند، سود/ضرر را مجدداً محاسبه کن
            if (in_array($property, ['from_account', 'to_account'])) {
                $this->calculateRealTimeProfitLoss();
            }

            // تبدیل به حروف برای هر تغییری که روی این فیلدها تأثیر می‌گذارد
            if (in_array($property, ['transactionType', 'accountType'])) {
                if ($this->buy_amount) {
                    $this->convertAmountToWords($this->buy_amount, 'withdrawalAmountInWords', 2);
                }
                if ($this->sell_amount) {
                    $this->convertAmountToWords($this->sell_amount, 'receivedAmountInWords', 2);
                }
                if ($this->currency_rate) {
                    $this->convertAmountToWords($this->currency_rate, 'currencyRateInWords', 4);
                }
            }
        } finally {
            $this->calculating = false;
        }
    }

    /**
     * محاسبه مبلغ خرید بر اساس مبلغ فروش و نرخ ارز - نسخه بهبود یافته
     */
    public function calculateBuyAmount()
    {
        if ($this->sell_amount && $this->currency_rate && $this->from_currency && $this->to_currency) {
            $fromCurrency = $this->from_currency;
            $toCurrency = $this->to_currency;

            // تبدیل مقادیر به عدد
            $amount = floatval(str_replace(',', '', $this->sell_amount));
            $rate = floatval(str_replace(',', '', $this->currency_rate));

            // بررسی اینکه نرخ ارز صفر نباشد
            if ($rate == 0) {
                $this->buy_amount = '';
                $this->withdrawalAmountInWords = '';
                return;
            }

            // محاسبه معکوس بر اساس فرمول
            if ($fromCurrency === 'afn' && $toCurrency === 'irr') {
                // تبدیل افغانی به تومان: مبلغ فروش × نرخ ارز ÷ 1000
                $calculatedAmount = ($amount * $rate) / 1000;
            } elseif ($fromCurrency === 'irr' && $toCurrency === 'afn') {
                // تبدیل تومان به افغانی: مبلغ فروش × 1000 ÷ نرخ ارز
                $calculatedAmount = ($amount * 1000) / $rate;
            } else {
                // برای سایر ارزها، معکوس منطق قبلی
                $shouldDivide = $this->shouldUseDivision($fromCurrency, $toCurrency);
                if ($shouldDivide) {
                    // اگر در حالت عادی تقسیم می‌کردیم، حالا ضرب می‌کنیم
                    $calculatedAmount = $amount * $rate;
                } else {
                    // اگر در حالت عادی ضرب می‌کردیم، حالا تقسیم می‌کنیم
                    $calculatedAmount = $amount / $rate;
                }
            }

            // محدود کردن به 2 رقم اعشار
            $calculatedAmount = round($calculatedAmount, 2);

            // ذخیره به صورت عددی با 2 رقم اعشار
            $this->buy_amount = number_format($calculatedAmount, 2, '.', '');

            // تبدیل به حروف
            $this->convertAmountToWords($this->buy_amount, 'withdrawalAmountInWords', 2);
        } else {
            $this->buy_amount = '';
            $this->withdrawalAmountInWords = '';
        }
    }

    /**
     * محاسبه مبلغ فروش بر اساس مبلغ خرید و نرخ ارز - نسخه بهبود یافته
     */
    public function calculateReceivedAmount()
    {
        if ($this->buy_amount && $this->currency_rate && $this->from_currency && $this->to_currency) {
            $fromCurrency = $this->from_currency;
            $toCurrency = $this->to_currency;

            // تبدیل مقادیر به عدد
            $amount = floatval(str_replace(',', '', $this->buy_amount));
            $rate = floatval(str_replace(',', '', $this->currency_rate));

            // بررسی اینکه نرخ ارز صفر نباشد
            if ($rate == 0) {
                $this->sell_amount = '';
                $this->receivedAmountInWords = '';
                return;
            }

            // محاسبه بر اساس فرمول جدید
            if ($fromCurrency === 'afn' && $toCurrency === 'irr') {
                // تبدیل افغانی به تومان: (مبلغ خرید × 1,000) ÷ نرخ ارز
                $calculatedAmount = ($amount * 1000) / $rate;
            } elseif ($fromCurrency === 'irr' && $toCurrency === 'afn') {
                // تبدیل تومان به افغانی: (مبلغ خرید × نرخ ارز) ÷ 1,000
                $calculatedAmount = ($amount * $rate) / 1000;
            } else {
                // برای سایر ارزها از منطق قبلی استفاده می‌کنیم
                $shouldDivide = $this->shouldUseDivision($fromCurrency, $toCurrency);
                if ($shouldDivide) {
                    $calculatedAmount = $amount / $rate;
                } else {
                    $calculatedAmount = $amount * $rate;
                }
            }

            // محدود کردن به 2 رقم اعشار
            $calculatedAmount = round($calculatedAmount, 2);

            // ذخیره به صورت عددی با 2 رقم اعشار
            $this->sell_amount = number_format($calculatedAmount, 2, '.', '');

            // تبدیل به حروف
            $this->convertAmountToWords($this->sell_amount, 'receivedAmountInWords', 2);
        } else {
            $this->sell_amount = '';
            $this->receivedAmountInWords = '';
        }
    }

    /**
     * تنظیم دستی فیلد محاسبه (برای استفاده در view)
     */
    public function setCalculatingField($field)
    {
        $this->calculatingField = $field;
    }

    /**
     * تعیین منطق محاسبه (تقسیم یا ضرب) برای سایر ارزها
     */
    private function shouldUseDivision($fromCurrency, $toCurrency): bool
    {
        $baseCurrencies = ['usd', 'eur', 'gbp'];
        $localCurrencies = ['afn', 'irr', 'pkr', 'aed', 'try', 'cny', 'inr'];

        // اگر از ارز پایه به ارز محلی: ضرب
        if (in_array($fromCurrency, $baseCurrencies) && in_array($toCurrency, $localCurrencies)) {
            return false;
        }

        // اگر از ارز محلی به ارز پایه: تقسیم
        if (in_array($fromCurrency, $localCurrencies) && in_array($toCurrency, $baseCurrencies)) {
            return true;
        }

        // پیش‌فرض: تقسیم
        return true;
    }

    /**
     * محاسبه و نمایش سود/ضرر در زمان واقعی
     */
    public function calculateRealTimeProfitLoss()
    {
        try {
            if (!$this->buy_amount || !$this->currency_rate || !$this->from_currency || !$this->to_currency) {
                return;
            }

            $profitLoss = $this->calculateProfitOrLoss();
            
            // نمایش سود/ضرر
            $this->dispatch('show-profit-loss', [
                'profit' => $profitLoss['profit'],
                'loss' => $profitLoss['loss'],
                'predefined_rate' => $profitLoss['predefined_rate']
            ]);
            
        } catch (\Exception $e) {
            Log::error('خطا در محاسبه سود/ضرر زمان واقعی: ' . $e->getMessage());
        }
    }

    /**
     * محاسبه سود/ضرر تبدیل ارز - نسخه جهانی برای تمام ارزها
     */
    private function calculateProfitOrLoss()
    {
        try {
            Log::info('=== شروع محاسبه سود/ضرر برای تمام ارزها ===', [
                'from_currency' => $this->from_currency,
                'to_currency' => $this->to_currency,
                'buy_amount' => $this->buy_amount,
                'sell_amount' => $this->sell_amount,
                'currency_rate' => $this->currency_rate,
                'transaction_type' => $this->transactionType,
                'account_type' => $this->getAccountTypeForRate()
            ]);

            // دریافت نرخ از پیش تعیین شده
            $predefinedRate = $this->getUniversalPredefinedRate();

            if ($predefinedRate === null || $predefinedRate == 0) {
                Log::warning("❌ نرخ از پیش تعیین شده برای {$this->from_currency} به {$this->to_currency} یافت نشد");
                return $this->getDefaultProfitLossResult();
            }

            // محاسبه مبلغ با نرخ از پیش تعیین شده
            $amountWithPredefinedRate = $this->calculateUniversalWithPredefinedRate($predefinedRate);
            $amountWithEnteredRate = floatval(str_replace(',', '', $this->sell_amount));

            // محاسبه تفاوت
            $difference = $amountWithPredefinedRate - $amountWithEnteredRate;

            Log::info('محاسبات جهانی', [
                'predefined_rate' => $predefinedRate,
                'amount_with_predefined_rate' => $amountWithPredefinedRate,
                'amount_with_entered_rate' => $amountWithEnteredRate,
                'difference' => $difference
            ]);

            // تبدیل تفاوت به دالر - همیشه با نرخ خرید دلار
            $differenceInUsd = 0;
            if ($difference != 0) {
                $differenceInUsd = $this->convertToUsdUniversal(abs($difference), $this->to_currency);
            }

            $profit = $difference > 0 ? $differenceInUsd : 0;
            $loss = $difference < 0 ? $differenceInUsd : 0;

            Log::info('نتیجه نهایی جهانی', [
                'profit_usd' => $profit,
                'loss_usd' => $loss,
                'transaction_type' => $this->transactionType,
                'used_rate_type' => $this->getRateType()
            ]);

            return [
                'profit' => round($profit, 4),
                'loss' => round($loss, 4),
                'predefined_rate' => $predefinedRate,
                'amount_with_predefined_rate' => $amountWithPredefinedRate,
                'amount_with_entered_rate' => $amountWithEnteredRate,
                'difference' => $difference
            ];
        } catch (\Exception $e) {
            Log::error('❌ خطا در محاسبه سود/ضرر جهانی: ' . $e->getMessage());
            return $this->getDefaultProfitLossResult();
        }
    }

    /**
     * دریافت نرخ از پیش تعیین شده برای تمام ارزها - منطق اصلاح شده
     */
    private function getUniversalPredefinedRate()
    {
        $rateType = $this->getRateType();

        Log::info("جستجوی نرخ برای: {$this->from_currency} → {$this->to_currency} با نوع: {$rateType}", [
            'transaction_type' => $this->transactionType,
            'from_account' => $this->from_account,
            'to_account' => $this->to_account,
            'calculated_account_type' => $this->getAccountTypeForRate()
        ]);

        $strategies = [
            'direct_from_currency' => function () use ($rateType) {
                if ($this->isStandardConversion()) {
                    $user = Auth::guard('sarafi')->user();
                    $adminId = $user->admin_id ?? $user->id;

                    $profitRate = ProfitRate::where('source_currency', $this->from_currency)
                        ->where('admin_id', $adminId)
                        ->latest()
                        ->first();
                    if ($profitRate) {
                        $field = $this->to_currency . '_' . $rateType;
                        if (isset($profitRate->{$field}) && $profitRate->{$field} > 0) {
                            Log::info("✅ استراتژی 1: نرخ از رکورد {$this->from_currency} - فیلد {$field} = {$profitRate->{$field}}", [
                                'rate_type' => $rateType,
                                'transaction_type' => $this->transactionType
                            ]);
                            return $profitRate->{$field};
                        }
                    }
                }
                return null;
            },

            'usd_as_base' => function () use ($rateType) {
                // استراتژی 2: از رکورد USD استفاده کن - برای تبدیل‌هایی که شامل USD هستند
                if ($this->from_currency === 'usd' || $this->to_currency === 'usd') {
                    $user = Auth::guard('sarafi')->user();
                    $adminId = $user->admin_id ?? $user->id;

                    $profitRate = ProfitRate::where('source_currency', 'usd')
                        ->where('admin_id', $adminId)
                        ->latest()
                        ->first();
                    if ($profitRate) {
                        if ($this->from_currency === 'usd') {
                            $field = $this->to_currency . '_' . $rateType;
                        } else {
                            // برای تبدیل به USD از منطق معکوس استفاده می‌کنیم
                            $reverseRateType = $this->getReverseRateType();
                            $field = $this->from_currency . '_' . $reverseRateType;
                        }

                        if (isset($profitRate->{$field}) && $profitRate->{$field} > 0) {
                            Log::info("✅ استراتژی 2: نرخ از رکورد USD - فیلد {$field} = {$profitRate->{$field}}", [
                                'rate_type' => $rateType,
                                'reverse_rate_type' => $reverseRateType ?? 'N/A'
                            ]);
                            return $profitRate->{$field};
                        }
                    }
                }
                return null;
            },

            'reverse_to_currency' => function () use ($rateType) {
                $user = Auth::guard('sarafi')->user();
                $adminId = $user->admin_id ?? $user->id;

                $profitRate = ProfitRate::where('source_currency', $this->to_currency)
                    ->where('admin_id', $adminId)
                    ->latest()
                    ->first();
                if ($profitRate) {
                    $reverseRateType = $this->getReverseRateType();
                    $field = $this->from_currency . '_' . $reverseRateType;
                    if (isset($profitRate->{$field}) && $profitRate->{$field} > 0) {
                        Log::info("✅ استراتژی 3: نرخ از رکورد {$this->to_currency} - فیلد {$field} = {$profitRate->{$field}}", [
                            'rate_type' => $rateType,
                            'reverse_rate_type' => $reverseRateType
                        ]);
                        return $profitRate->{$field};
                    }
                }
                return null;
            },

            'fallback_any_rate' => function () use ($rateType) {
                // استراتژی 4: از هر رکوردی که نرخ دارد استفاده کن
                $user = Auth::guard('sarafi')->user();
                $adminId = $user->admin_id ?? $user->id;

                $profitRates = ProfitRate::where(function ($q) use ($adminId) {
                    $q->where('admin_id', $adminId)
                        ->orWhere('user_id', $adminId);
                })
                    ->latest()
                    ->get();

                // اولویت 1: جستجوی مستقیم با همان نوع نرخ
                foreach ($profitRates as $profitRate) {
                    $field = $this->to_currency . '_' . $rateType;
                    if (isset($profitRate->{$field}) && $profitRate->{$field} > 0) {
                        Log::info("✅ استراتژی 4-1: نرخ از رکورد {$profitRate->source_currency} - فیلد {$field} = {$profitRate->{$field}}");
                        return $profitRate->{$field};
                    }
                }

                // اولویت 2: جستجوی فیلدهای جایگزین با توجه به نوع تراکنش
                foreach ($profitRates as $profitRate) {
                    // بسته به نوع تراکنش، فیلدهای جایگزین متفاوت هستند
                    $alternativeFields = [];

                    if ($this->transactionType === 'خرید') {
                        // برای خرید: اولویت با نرخ‌های خرید
                        $alternativeFields = [
                            $this->to_currency . '_buy_cash',
                            $this->to_currency . '_buy_bank',
                            $this->to_currency . '_sell_cash',  // جایگزین
                            $this->to_currency . '_sell_bank',   // جایگزین
                        ];
                    } else {
                        // برای فروش: اولویت با نرخ‌های فروش
                        $alternativeFields = [
                            $this->to_currency . '_sell_cash',
                            $this->to_currency . '_sell_bank',
                            $this->to_currency . '_buy_cash',   // جایگزین
                            $this->to_currency . '_buy_bank',    // جایگزین
                        ];
                    }

                    foreach ($alternativeFields as $altField) {
                        if (isset($profitRate->{$altField}) && $profitRate->{$altField} > 0) {
                            Log::info("✅ استراتژی 4-2: نرخ از رکورد {$profitRate->source_currency} - فیلد جایگزین {$altField} = {$profitRate->{$altField}}");
                            return $profitRate->{$altField};
                        }
                    }
                }
                return null;
            }
        ];

        // اجرای استراتژی‌ها به ترتیب
        foreach ($strategies as $strategyName => $strategy) {
            $rate = $strategy();
            if ($rate !== null) {
                return $rate;
            }
        }

        Log::warning("❌ هیچ نرخ مناسبی برای {$this->from_currency} به {$this->to_currency} یافت نشد", [
            'transaction_type' => $this->transactionType,
            'rate_type' => $rateType
        ]);
        return null;
    }

    /**
     * بررسی آیا تبدیل استاندارد است
     */
    private function isStandardConversion()
    {
        // تبدیل‌های استاندارد که از رکورد ارز مبدا استفاده می‌کنند
        $standardConversions = [
            'afn_irr',
            'irr_afn',  // افغانی و تومان
            'afn_pkr',
            'pkr_afn',  // افغانی و کلدار
            'irr_pkr',
            'pkr_irr',  // تومان و کلدار
        ];

        $conversionKey = $this->from_currency . '_' . $this->to_currency;
        return in_array($conversionKey, $standardConversions);
    }

    /**
     * تعیین نوع نرخ مورد نیاز
     * اصلاح شده: برای خرید نرخ خرید، برای فروش نرخ فروش
     */
    private function getRateType()
    {
        // تعیین نوع حساب بر اساس نوع تراکنش
        $accountTypeForRate = $this->getAccountTypeForRate();
        
        if ($this->transactionType === 'خرید') {
            // برای خرید: نرخ فروش (چون ما از مشتری خرید می‌کنیم)
            return $accountTypeForRate === 'cash' ? 'sell_cash' : 'sell_bank';
        } else {
            // برای فروش: نرخ خرید (چون ما به مشتری می‌فروشیم)
            return $accountTypeForRate === 'cash' ? 'buy_cash' : 'buy_bank';
        }
    }

    /**
     * تعیین نوع نرخ معکوس
     */
    private function getReverseRateType()
    {
        // تعیین نوع حساب بر اساس نوع تراکنش
        $accountTypeForRate = $this->getAccountTypeForRate();
        
        // معکوس getRateType
        if ($this->transactionType === 'خرید') {
            // اگر getRateType برای خرید نرخ فروش برمی‌گرداند، ما نرخ خرید برگردانیم
            return $accountTypeForRate === 'cash' ? 'buy_cash' : 'buy_bank';
        } else {
            // اگر getRateType برای فروش نرخ خرید برمی‌گرداند، ما نرخ فروش برگردانیم
            return $accountTypeForRate === 'cash' ? 'sell_cash' : 'sell_bank';
        }
    }

    /**
     * محاسبه جهانی با نرخ از پیش تعیین شده - منطق بهبود یافته
     */
    private function calculateUniversalWithPredefinedRate($predefinedRate)
    {
        $amount = floatval(str_replace(',', '', $this->buy_amount));

        Log::info("محاسبه با نرخ پیش‌فرض: {$amount} {$this->from_currency} → {$this->to_currency} با نرخ: {$predefinedRate}", [
            'transaction_type' => $this->transactionType,
            'rate_type' => $this->getRateType()
        ]);

        // موارد خاص برای تبدیل‌های شناخته شده
        if ($this->from_currency === 'afn' && $this->to_currency === 'irr') {
            $result = ($amount * 1000) / $predefinedRate;
            Log::info("محاسبه AFN→IRR: ({$amount} × 1000) ÷ {$predefinedRate} = {$result}");
            return $result;
        }

        if ($this->from_currency === 'irr' && $this->to_currency === 'afn') {
            $result = ($amount * $predefinedRate) / 1000;
            Log::info("محاسبه IRR→AFN: ({$amount} × {$predefinedRate}) ÷ 1000 = {$result}");
            return $result;
        }

        // برای تبدیل به USD یا از USD
        if ($this->to_currency === 'usd') {
            $result = $amount / $predefinedRate;
            Log::info("محاسبه {$this->from_currency}→USD: {$amount} ÷ {$predefinedRate} = {$result}");
            return $result;
        }

        if ($this->from_currency === 'usd') {
            $result = $amount * $predefinedRate;
            Log::info("محاسبه USD→{$this->to_currency}: {$amount} × {$predefinedRate} = {$result}");
            return $result;
        }

        // برای سایر ارزها از منطق استاندارد استفاده می‌کنیم
        $shouldDivide = $this->shouldUseDivisionUniversal($this->from_currency, $this->to_currency);
        if ($shouldDivide) {
            $result = $amount / $predefinedRate;
            Log::info("محاسبه جهانی (تقسیم): {$amount} ÷ {$predefinedRate} = {$result}");
        } else {
            $result = $amount * $predefinedRate;
            Log::info("محاسبه جهانی (ضرب): {$amount} × {$predefinedRate} = {$result}");
        }

        return $result;
    }

    /**
     * تعیین منطق محاسبه برای تمام ارزها
     */
    private function shouldUseDivisionUniversal($fromCurrency, $toCurrency): bool
    {
        // ارزهای پایه (معمولاً ارزهای اصلی جهانی)
        $baseCurrencies = ['usd', 'eur', 'gbp', 'jpy', 'chf', 'cad', 'aud'];

        // ارزهای محلی (معمولاً ارزهای کشورهای خاص)
        $localCurrencies = ['afn', 'irr', 'pkr', 'aed', 'try', 'cny', 'inr', 'sar', 'qar', 'omr'];

        // اگر از ارز پایه به ارز محلی تبدیل می‌کنیم: ضرب
        if (in_array($fromCurrency, $baseCurrencies) && in_array($toCurrency, $localCurrencies)) {
            return false;
        }

        // اگر از ارز محلی به ارز پایه تبدیل می‌کنیم: تقسیم
        if (in_array($fromCurrency, $localCurrencies) && in_array($toCurrency, $baseCurrencies)) {
            return true;
        }

        // اگر هر دو ارز پایه هستند یا هر دو ارز محلی هستند: تقسیم
        return true;
    }

    /**
     * تبدیل جهانی به دالر - نسخه اصلاح شده
     */
    private function convertToUsdUniversal($amount, $currency)
    {
        if ($currency === 'usd') {
            return $amount;
        }

        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        $usdProfitRate = ProfitRate::where('source_currency', 'usd')
            ->where(function ($query) use ($adminId) {
                $query->where('admin_id', $adminId)
                    ->orWhere('user_id', $adminId);
            })
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$usdProfitRate) {
            Log::warning('❌ هیچ رکورد USD در جدول profit_rate برای تبدیل به دالر یافت نشد');
            return 0;
        }

        // استفاده از getAccountTypeForRate برای تعیین نوع حساب
        $accountTypeForRate = $this->getAccountTypeForRate();
        
        // همیشه از نرخ خرید دلار استفاده می‌کنیم (برای سود/ضرر)
        $rateType = $accountTypeForRate === 'cash' ? 'buy_cash' : 'buy_bank';
        $usdRateField = $currency . '_' . $rateType;
        $usdRate = $usdProfitRate->{$usdRateField} ?? null;

        Log::info("تبدیل {$currency} به دالر", [
            'amount' => $amount,
            'currency' => $currency,
            'rate_field' => $usdRateField,
            'rate_value' => $usdRate,
            'rate_type' => $rateType,
            'transaction_type' => $this->transactionType,
            'account_type_for_rate' => $accountTypeForRate
        ]);

        if (!$usdRate || $usdRate == 0) {
            Log::warning("❌ نرخ خرید {$currency} به دالر یافت نشد");

            // جستجوی فیلدهای جایگزین خرید
            $fallbackFields = [
                $currency . '_buy_cash',
                $currency . '_buy_bank',
                $currency . '_sell_cash',  // اگر نرخ خرید پیدا نشد
                $currency . '_sell_bank'   // از نرخ فروش استفاده می‌کنیم
            ];

            foreach ($fallbackFields as $field) {
                if (isset($usdProfitRate->{$field}) && $usdProfitRate->{$field} > 0) {
                    $usdRate = $usdProfitRate->{$field};
                    Log::info("🔀 استفاده از فیلد جایگزین برای تبدیل به دالر: {$field} = {$usdRate}");
                    break;
                }
            }

            if (!$usdRate || $usdRate == 0) {
                Log::warning("❌ هیچ نرخ تبدیلی برای {$currency} به دالر یافت نشد");
                return 0;
            }
        }

        // برای تمام ارزها از تقسیم استفاده می‌کنیم
        $convertedAmount = $amount / $usdRate;

        Log::info("نتیجه تبدیل به دالر", [
            'original_amount' => $amount,
            'rate' => $usdRate,
            'converted_amount' => $convertedAmount,
            'formula' => "{$amount} ÷ {$usdRate} = {$convertedAmount}"
        ]);

        return $convertedAmount;
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
     * تولید توضیحات برای سود/ضرر
     */
    private function generateProfitLossDescription($profitLoss)
    {
        $description = "تبدیل {$this->from_currency} به {$this->to_currency} - ";
        $description .= "نوع: {$this->transactionType} {$this->accountType} - ";

        if ($profitLoss['profit'] > 0) {
            $description .= "سود: " . number_format($profitLoss['profit'], 4) . " USD";
        } else {
            $description .= "ضرر: " . number_format($profitLoss['loss'], 4) . " USD";
        }

        $description .= " (تفاوت: " . number_format($profitLoss['difference'], 4) . " {$this->to_currency})";
        $description .= " - نرخ از پیش تعیین شده: " . number_format($profitLoss['predefined_rate'], 4);

        return $description;
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
            'date' => 'required|date',
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
            Log::info('=== شروع ثبت تبدیل ارز ===');

            // محاسبه سود/ضرر
            Log::info('در حال محاسبه سود/ضرر...');
            $profitLoss = $this->calculateProfitOrLoss();

            $conversionId = null;

            if ($this->editingConversionId) {
                // حالت ویرایش
                $conversion = ConversionInAccounts::find($this->editingConversionId);

                if (!$conversion) {
                    throw new \Exception('رکورد تبدیل ارز برای ویرایش یافت نشد.');
                }

                Transaction::where('conversion_in_account_id', $conversion->id)->delete();
                Revenue::where('conversion_in_account_id', $conversion->id)->delete();

                $conversion->update([
                    'customer_id' => $this->selectedAccount,
                    'from_currency' => $this->from_currency,
                    'buy_amount' => $this->buy_amount,
                    'to_currency' => $this->to_currency,
                    'sell_amount' => $this->sell_amount,
                    'currency_rate' => $this->currency_rate,
                    'account_type' => $this->accountType,
                    'transaction_date' => $this->date,
                    'description' => $this->description,
                    'zone_sender' => $this->zone_sender,
                    'zone_receiver' => $this->zone_receiver,
                    'by_sender' => $this->by_sender,
                    'by_receiver' => $this->by_receiver,
                    'type' => $this->transactionType,
                ]);

                $conversionId = $conversion->id;
                Log::info("✅ تبدیل ارز ویرایش شد - ID: {$conversionId}");
            } else {
                $conversion = ConversionInAccounts::create([
                    'customer_id' => $this->selectedAccount,
                    'account_type' => $this->accountType,
                    'from_currency' => $this->from_currency,
                    'buy_amount' => $this->buy_amount,
                    'to_currency' => $this->to_currency,
                    'sell_amount' => $this->sell_amount,
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
                Log::info("✅ تبدیل ارز ایجاد شد - ID: {$conversionId}");
            }

            Transaction::create([
                'customer_id' => $this->selectedAccount,
                'user_id' => $user->id,
                'admin_id' => $adminId,
                'currency' => $this->from_currency,
                'amount' => $this->buy_amount,
                'type' => 'برد',
                'date' => $this->date,
                'zone' => $this->zone_sender,
                'description' =>
                ' برداشت مبلغ ' . number_format($this->buy_amount, 2) . ' ' .
                    $this->getCurrencyFaName($this->from_currency) .
                    ' و خرید مبلغ ' . number_format($this->sell_amount, 2) . ' ' .
                    $this->getCurrencyFaName($this->to_currency) .
                    ' به نرخ ' . $this->currency_rate,
                'by' => $this->by_sender,
                'account_type' => $this->from_account,
                'conversion_in_account_id' => $conversionId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            Transaction::create([
                'customer_id' => $this->selectedAccount,
                'user_id' => $user->id,
                'admin_id' => $adminId,
                'currency' => $this->to_currency,
                'amount' => $this->sell_amount,
                'type' => 'رسید',
                'account_type' => $this->to_account,
                'date' => $this->date,
                'description' =>
                ' دریافت مبلغ ' . number_format($this->sell_amount, 2) . ' ' .
                    $this->getCurrencyFaName($this->to_currency) .
                    ' و فروش مبلغ ' . number_format($this->buy_amount, 2) . ' ' .
                    $this->getCurrencyFaName($this->from_currency) .
                    ' به نرخ ' . $this->currency_rate,
                'zone' => $this->zone_receiver,
                'by' => $this->by_receiver,
                'conversion_in_account_id' => $conversionId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            Log::info('✅ تراکنش‌های برداشت و رسید ایجاد شدند');

            Log::info('بررسی سود/ضرر برای ثبت در revenue', [
                'profit' => $profitLoss['profit'],
                'loss' => $profitLoss['loss']
            ]);

            if ($profitLoss['profit'] > 0 || $profitLoss['loss'] > 0) {
                Log::info('📊 در حال ثبت سود/ضرر در جدول revenue...');

                $revenueData = [
                    'currency' => 'usd',
                    'profit' => $profitLoss['profit'],
                    'lost' => $profitLoss['loss'],
                    'from' => 'تبدیل ارز در حساب',
                    'description' => $this->generateProfitLossDescription($profitLoss),
                    'user_id' => $user->id,
                    'admin_id' => $adminId,
                    'conversion_in_account_id' => $conversionId,
                ];

                Log::info('داده‌های revenue:', $revenueData);

                $revenue = Revenue::create($revenueData);

                Log::info("✅ سود/ضرر در جدول revenue ثبت شد - ID: {$revenue->id}");
            } else {
                Log::info('ℹ️ هیچ سود یا ضرری برای ثبت در revenue وجود ندارد');
            }

            DB::connection('sarafi')->commit();
            Log::info('✅ تراکنش دیتابیس commit شد');

            $message = $this->editingConversionId ?
                'تبدیل ارز با موفقیت ویرایش شد.' :
                'تبدیل ارز با موفقیت ثبت شد.';

            if ($profitLoss['profit'] > 0) {
                $message .= ' سود: ' . number_format($profitLoss['profit'], 4) . ' دالر';
            } elseif ($profitLoss['loss'] > 0) {
                $message .= ' ضرر: ' . number_format($profitLoss['loss'], 4) . ' دالر';
            }

            session()->flash('message', $message);

            // به‌روزرسانی موجودی‌ها بعد از ثبت
            $this->updateCustomerCurrencyBalance($this->selectedAccount);

            Log::info('=== پایان ثبت تبدیل ارز ===');

            $this->resetForm();
        } catch (\Exception $e) {
            DB::connection('sarafi')->rollBack();

            $errorMessage = 'خطا در ثبت تبدیل ارز: ' . $e->getMessage();
            session()->flash('error', $errorMessage);

            Log::error('❌ خطا در ثبت تبدیل ارز: ' . $e->getMessage(), [
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

        if (!$conversion) {
            return;
        }

        $this->editingConversionId = $conversionId;

        // ترانزکشن برداشت
        $withdrawTransaction = Transaction::where('conversion_in_account_id', $conversionId)
            ->where('type', 'برد')
            ->first();

        // ترانزکشن رسید
        $receiveTransaction = Transaction::where('conversion_in_account_id', $conversionId)
            ->where('type', 'رسید')
            ->first();

        // ---------- مقادیر اصلی ----------
        $this->selectedAccount = $conversion->customer_id;
        $this->selectedCustomerId = $conversion->customer_id;
        $this->from_currency = $conversion->from_currency;
        $this->to_currency = $conversion->to_currency;
        $this->buy_amount = $conversion->buy_amount;
        $this->sell_amount = $conversion->sell_amount;
        $this->currency_rate = $conversion->currency_rate;
        $this->date = $conversion->transaction_date;
        $this->description = $conversion->description;

        // ---------- مقادیر حساب (مهم) ----------
        $this->from_account = $withdrawTransaction?->account_type;
        $this->to_account   = $receiveTransaction?->account_type;

        // ---------- سایر فیلدها ----------
        $this->zone_sender   = $withdrawTransaction?->zone;
        $this->zone_receiver = $receiveTransaction?->zone;

        $this->by_sender   = $withdrawTransaction?->by;
        $this->by_receiver = $receiveTransaction?->by;

        // تبدیل عدد به حروف
        $this->convertAmountToWords($this->buy_amount, 'withdrawalAmountInWords');
        $this->convertAmountToWords($this->sell_amount, 'receivedAmountInWords');
        $this->convertAmountToWords($this->currency_rate, 'currencyRateInWords');

        // بروزرسانی موجودی
        $this->updateCustomerCurrencyBalance($conversion->customer_id);

        $this->dispatch('edit-mode-activated', [
            'selectedAccount' => $this->selectedAccount,
            'selectedCustomer' => $conversion->customer
                ? $conversion->customer->account_number . ' - ' . $conversion->customer->fullname
                : ''
        ]);
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

        $this->updateCustomerCurrencyBalance($this->selectedAccount);

        DB::connection('sarafi')->beginTransaction();

        try {
            $conversion = ConversionInAccounts::find($this->confirmDeleteId);

            if ($conversion) {
                // حذف تراکنش‌های مرتبط
                Transaction::where('conversion_in_account_id', $conversion->id)->delete();

                // حذف سود/ضرر مرتبط
                Revenue::where('conversion_in_account_id', $conversion->id)->delete();

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
        $this->accountType = 'نقدی';
        $this->from_account = 'نقدی';
        $this->to_account = 'نقدی';
        $this->date = Jalalian::now()->format('Y/m/d');
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
                'format' => [72.1, 297],
                'directionality' => 'rtl',
                'margin_top' => 0,
                'margin_bottom' => 0,
                'margin_left' => 0,
                'margin_right' => 0,
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

    private function getCustomerCurrencyBalance($customerId, $currencyCode): float
    {
        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        $transactions = Transaction::where('customer_id', $customerId)
            ->where('admin_id', $adminId)
            ->where('account_type', $this->accountType)
            ->where('currency', $currencyCode)
            ->get();

        $balance = 0;

        foreach ($transactions as $transaction) {
            if ($transaction->type === 'رسید') {
                $balance += (float) $transaction->amount;
            } else {
                $balance -= (float) $transaction->amount;
            }
        }

        return $balance;
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
     * تبدیل عدد به حروف فارسی - نسخه بهبود یافته
     */
    private function convertAmountToWords($value, $property, $decimals = 2)
    {
        if ($value && $value !== '' && is_numeric(str_replace(',', '', $value))) {
            try {
                // حذف کاماها و تبدیل به عدد
                $numericValue = floatval(str_replace(',', '', $value));

                // اگر عدد صفر باشد، حروف صفر نمایش داده شود
                if ($numericValue == 0) {
                    $this->$property = 'صفر';
                    return;
                }

                // گرد کردن به تعداد اعشار مورد نظر
                $roundedValue = round($numericValue, $decimals);

                // جدا کردن قسمت صحیح و اعشار
                $parts = explode('.', (string)$roundedValue);
                $integerPart = intval($parts[0]);

                // قسمت اعشار
                $fractionPart = '';
                if (isset($parts[1])) {
                    // حذف صفرهای اضافی در سمت راست
                    $fractionPart = rtrim($parts[1], '0');
                }

                // استفاده از NumberFormatter فارسی
                $formatter = new NumberFormatter("fa", NumberFormatter::SPELLOUT);

                // تبدیل قسمت صحیح به حروف
                $integerWords = $formatter->format($integerPart);

                // تبدیل قسمت اعشار به حروف
                $fractionWords = '';
                if ($fractionPart !== '' && intval($fractionPart) > 0) {
                    // اضافه کردن صفرهای سمت چپ اگر نیاز باشد
                    $fractionPart = str_pad($fractionPart, $decimals, '0', STR_PAD_RIGHT);
                    $fractionWords = $formatter->format(intval($fractionPart));
                }

                // ترکیب کلمات
                $words = $integerWords;
                if ($fractionWords !== '') {
                    $words .= ' ممیز ' . $fractionWords;
                }

                // تصحیح برخی کلمات
                $replacements = [
                    'دویست' => 'دوصد',
                    'سیصد' => 'سه‌صد',
                    'پانصد' => 'پانصد',
                    'هشتصد' => 'هشتصد',
                    'نهصد' => 'نه‌صد',
                    'و ممیز' => ' ممیز', // حذف "و" قبل از ممیز
                    '  ' => ' ', // حذف فاصله‌های اضافی
                ];

                $words = str_replace(array_keys($replacements), array_values($replacements), $words);

                // اضافه کردن واحد اگر لازم است
                if ($property === 'withdrawalAmountInWords' || $property === 'receivedAmountInWords') {
                    $currency = $property === 'withdrawalAmountInWords' ?
                        $this->getCurrencyName($this->from_currency) :
                        $this->getCurrencyName($this->to_currency);
                    $words .= ' ' . $currency;
                }

                $this->$property = $words;

                // لاگ برای دیباگ
                Log::debug("مقدار {$value} به حروف تبدیل شد: {$words}", [
                    'property' => $property,
                    'numeric_value' => $numericValue,
                    'integer_part' => $integerPart,
                    'fraction_part' => $fractionPart
                ]);
            } catch (\Exception $e) {
                Log::error('خطا در تبدیل مقدار به حروف: ' . $e->getMessage(), [
                    'value' => $value,
                    'property' => $property
                ]);
                $this->$property = '';
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