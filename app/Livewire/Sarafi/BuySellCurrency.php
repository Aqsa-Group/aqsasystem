<?php

namespace App\Livewire\Sarafi;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Sarafi\CashExchange;
use App\Models\Sarafi\CurrencySafe;
use App\Models\Sarafi\ProfitRate;
use App\Models\Sarafi\Revenue;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use NumberFormatter;
use Morilog\Jalali\Jalalian;
use Illuminate\Support\Facades\Log;

class BuySellCurrency extends Component
{
    use WithFileUploads;


    public $calculatingFromAmount = false;
    public $calculatingFromEqAmount = false;


    // Component Properties
    public $transactionType = 'خرید';
    public $currencies = [];
    public $search = '';
    public $editingId = null;
    public $isEditing = false;
    public $confirmDeleteId = null;
    public $amountInWords = '';
    public $eqAmountInWords = '';
    public $exchangeRateInWords = '';

    // اضافه کردن properties برای سود/ضرر
    public $profit_loss_display = '';
    public $profit_loss_data = [];

    // Form Fields
    public $currency = 'usd';
    public $to_currency = 'afn';
    public $amount = '';
    public $eq_amount = '';
    public $exchange_rate = '';
    public $date;
    public $description = '';
    public $transaction_file;


    public $profit_display = '';
    public $loss_display = '';


    // Calculations
    public $totalBuy = [];
    public $totalSell = [];
    public $netAmounts = [];

    // ==================== COMPONENT LIFECYCLE METHODS ====================

    /**
     * Render the component
     */
    public function render()
    {
        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        $transactions = CashExchange::where('admin_id', $adminId)
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('description', 'like', '%' . $this->search . '%')
                        ->orWhere('type', 'like', '%' . $this->search . '%');
                });
            })
            ->latest()
            ->get();

        $this->calculateTotals();

        return view('livewire.sarafi.buy-sell-currency', [
            'transactions' => $transactions
        ]);
    }


    /**
     * Initialize component on mount
     */
    public function mount()
    {
        $this->currencies = [
            ['code' => 'usd', 'name_fa' => 'دالر'],
            ['code' => 'afn', 'name_fa' => 'افغانی'],
            ['code' => 'eur', 'name_fa' => 'یورو'],
            ['code' => 'irr', 'name_fa' => 'تومان'],
            ['code' => 'aed', 'name_fa' => 'درهم'],
            ['code' => 'try', 'name_fa' => 'لیره'],
            ['code' => 'cny', 'name_fa' => 'یوان'],
            ['code' => 'pkr', 'name_fa' => 'کلدار'],
        ];

        $this->date = Jalalian::now()->format('Y/m/d');
        $this->calculateTotals();
    }

    // ==================== FORM FIELD UPDATES ====================

    /**
     * Handle amount field update
     */
    public function updatedAmount($value)
    {
        $this->calculateEquivalentAmount();
        $this->convertAmountToWords($value, 'amountInWords');
        $this->calculateRealTimeProfitLoss();
    }

    /**
     * Handle exchange rate field update
     */
    public function updatedExchangeRate($value)
    {
        if ($this->calculatingFromAmount || $this->calculatingFromEqAmount) return;

        // اگر amount وجود دارد، از آن محاسبه کن
        if ($this->amount) {
            $this->calculatingFromAmount = true;
            $this->calculateEqAmountFromAmount();
            $this->calculatingFromAmount = false;
        }
        // اگر eq_amount وجود دارد، از آن محاسبه کن
        elseif ($this->eq_amount) {
            $this->calculatingFromEqAmount = true;
            $this->calculateAmountFromEqAmount();
            $this->calculatingFromEqAmount = false;
        }

        $this->convertAmountToWords($value, 'exchangeRateInWords');
        $this->calculateRealTimeProfitLoss();
    }
    /**
     * Handle currency field update
     */
    public function updatedCurrency()
    {
        $this->recalculateBasedOnAvailableData();
        $this->calculateRealTimeProfitLoss();
    }

    /**
     * Handle to_currency field update
     */
    public function updatedToCurrency()
    {
        $this->recalculateBasedOnAvailableData();
        $this->calculateRealTimeProfitLoss();
    }


    /**
     * Handle eq_amount field update
     */
    public function updatedEqAmount($value)
    {
        if ($this->calculatingFromAmount) return;

        $this->calculatingFromEqAmount = true;
        $this->calculateAmountFromEqAmount();
        $this->calculatingFromEqAmount = false;
        $this->calculateRealTimeProfitLoss();
    }



    public function calculateEqAmountFromAmount()
    {
        if ($this->amount && $this->exchange_rate && $this->currency && $this->to_currency) {

            $amount = floatval(str_replace(',', '', $this->amount));
            $rate   = floatval(str_replace(',', '', $this->exchange_rate));

            if ($rate <= 0) {
                $this->resetAmounts();
                return;
            }

            $calculatedAmount = 0;

            // AFN ↔ IRR (تومان)
            if ($this->currency === 'afn' && $this->to_currency === 'irr') {
                $calculatedAmount = ($amount * 1000) / $rate;
            } elseif ($this->currency === 'irr' && $this->to_currency === 'afn') {
                $calculatedAmount = ($amount * $rate) / 1000;

                // AFN → ارز خارجی (USD, EUR, ...)
            } elseif ($this->currency === 'afn') {
                $calculatedAmount = $amount / $rate;

                // ارز خارجی → AFN
            } elseif ($this->to_currency === 'afn') {
                $calculatedAmount = $amount * $rate;

                // ارز خارجی → ارز خارجی (مثلاً USD → EUR)
            } else {
                $calculatedAmount = ($amount * $rate); // اگر نرخ مستقیم داری
            }

            $calculatedAmount = round($calculatedAmount, 2);
            $this->eq_amount = $calculatedAmount;

            $this->convertAmountToWords($this->amount, 'amountInWords');
            $this->convertAmountToWords($this->eq_amount, 'eqAmountInWords');
            $this->convertAmountToWords($this->exchange_rate, 'exchangeRateInWords');
        } else {
            $this->resetAmounts();
        }
    }


    public function calculateAmountFromEqAmount()
    {
        if ($this->eq_amount && $this->exchange_rate && $this->currency && $this->to_currency) {

            $eqAmount = floatval(str_replace(',', '', $this->eq_amount));
            $rate     = floatval(str_replace(',', '', $this->exchange_rate));

            if ($rate <= 0) {
                $this->resetAmounts();
                return;
            }

            $calculatedAmount = 0;

            // AFN ↔ IRR (معکوس)
            if ($this->currency === 'afn' && $this->to_currency === 'irr') {
                $calculatedAmount = ($eqAmount * $rate) / 1000;
            } elseif ($this->currency === 'irr' && $this->to_currency === 'afn') {
                $calculatedAmount = ($eqAmount * 1000) / $rate;

                // AFN → ارز خارجی (معکوس)
            } elseif ($this->currency === 'afn') {
                $calculatedAmount = $eqAmount * $rate;

                // ارز خارجی → AFN (معکوس)
            } elseif ($this->to_currency === 'afn') {
                $calculatedAmount = $eqAmount / $rate;

                // ارز خارجی → ارز خارجی
            } else {
                $calculatedAmount = $eqAmount / $rate;
            }

            $calculatedAmount = round($calculatedAmount, 2);
            $this->amount = $calculatedAmount;

            $this->convertAmountToWords($this->amount, 'amountInWords');
            $this->convertAmountToWords($this->eq_amount, 'eqAmountInWords');
            $this->convertAmountToWords($this->exchange_rate, 'exchangeRateInWords');
        } else {
            $this->resetAmounts();
        }
    }


    private function resetAmounts()
    {
        $this->amount = '';
        $this->eq_amount = '';
        $this->amountInWords = '';
        $this->eqAmountInWords = '';
        $this->exchangeRateInWords = '';
    }



    /**
     * محاسبه مجدد بر اساس داده‌های موجود
     */
    private function recalculateBasedOnAvailableData()
    {
        if ($this->calculatingFromAmount || $this->calculatingFromEqAmount) return;

        // اگر amount وجود دارد، از آن محاسبه کن
        if ($this->amount) {
            $this->calculatingFromAmount = true;
            $this->calculateEqAmountFromAmount();
            $this->calculatingFromAmount = false;
        }
        // اگر eq_amount وجود دارد، از آن محاسبه کن
        elseif ($this->eq_amount) {
            $this->calculatingFromEqAmount = true;
            $this->calculateAmountFromEqAmount();
            $this->calculatingFromEqAmount = false;
        }
    }

    /**
     * Handle transaction type update
     */
    public function updatedTransactionType()
    {
        $this->recalculateBasedOnAvailableData();
        $this->calculateRealTimeProfitLoss();
    }

 
    // ==================== CALCULATION METHODS ====================

    /**
     * Calculate equivalent amount based on exchange rate
     */
    public function calculateEquivalentAmount()
    {
        if ($this->amount && $this->exchange_rate && $this->currency && $this->to_currency) {

            $fromCurrency = $this->currency;
            $toCurrency = $this->to_currency;

            // تبدیل مقادیر به عدد
            $amount = floatval(str_replace(',', '', $this->amount));
            $rate   = floatval(str_replace(',', '', $this->exchange_rate));

            // بررسی اینکه نرخ ارز صفر نباشد
            if ($rate == 0) {
                $this->eq_amount = '';
                $this->eqAmountInWords = '';
                return;
            }

            $calculatedAmount = 0;

            // ---------------------------------
            //   حالت‌های خاص AFN ↔ IRR
            // ---------------------------------
            if ($fromCurrency === 'afn' && $toCurrency === 'irr') {
                // افغانی → تومان
                $calculatedAmount = ($amount * 1000) / $rate;
            } elseif ($fromCurrency === 'irr' && $toCurrency === 'afn') {
                // تومان → افغانی
                $calculatedAmount = ($amount * $rate) / 1000;
            }

            // ---------------------------------
            //   حالت‌های USD ↔ AFN
            // ---------------------------------
            elseif ($fromCurrency === 'afn' && $toCurrency === 'usd') {
                // افغانی → دالر
                $calculatedAmount = $amount / $rate;
            } elseif ($fromCurrency === 'usd' && $toCurrency === 'afn') {
                // دالر → افغانی
                $calculatedAmount = $amount * $rate;
            }

            // ---------------------------------
            //   سایر تبدیل‌های عمومی
            // ---------------------------------
            else {
                // دقیقاً مثل calculateReceivedAmount
                $calculatedAmount = $amount * $rate;
            }

            // محدود به 2 رقم اعشار
            $calculatedAmount = round($calculatedAmount, 2);

            // ذخیره
            $this->eq_amount = $calculatedAmount;

            // تبدیل اعداد به حروف
            $this->convertAmountToWords($this->amount, 'amountInWords');
            $this->convertAmountToWords($calculatedAmount, 'eqAmountInWords');
            $this->convertAmountToWords($this->exchange_rate, 'exchangeRateInWords');
        } else {
            $this->eq_amount = '';
            $this->amountInWords = '';
            $this->eqAmountInWords = '';
            $this->exchangeRateInWords = '';
        }
    }


    /**
     * Convert numeric amount to Persian words
     */
    private function convertAmountToWords($value, $property)
    {
        if ($value && is_numeric($value)) {
            $formatter = new NumberFormatter("fa", NumberFormatter::SPELLOUT);
            $words = $formatter->format(floatval($value));
            $words = str_replace(['دویست', 'سیصد', 'پانصد'], ['دوصد', 'سه صد', 'پنجصد'], $words);
            $this->$property = $words;
        } else {
            $this->$property = '';
        }
    }

    /**
     * Calculate totals and net amounts
     */
    private function calculateTotals()
    {
        $user = Auth::guard('sarafi')->user();
        if (!$user) {
            return;
        }

        $userId = $user->id;
        $adminId = $user->admin_id ?? $user->id;

        // Try to find user's currency safe
        $safe = CurrencySafe::where('user_id', $userId)->first();

        // If user safe not found, use admin safe
        if (!$safe) {
            $safe = CurrencySafe::where('user_id', $adminId)->first();
        }

        // Display actual balances if safe exists
        if ($safe) {
            foreach ($this->currencies as $currency) {
                $code = $currency['code'];
                $this->netAmounts[$code] = $safe->{$code} ?? 0;
            }
        } else {
            // Display zero if no safe exists
            foreach ($this->currencies as $currency) {
                $code = $currency['code'];
                $this->netAmounts[$code] = 0;
            }
        }

        // Calculate buy and sell totals
        $this->totalBuy = array_fill_keys(array_column($this->currencies, 'code'), 0);
        $this->totalSell = array_fill_keys(array_column($this->currencies, 'code'), 0);

        $allTransactions = CashExchange::all();

        foreach ($allTransactions as $transaction) {
            if ($transaction->type === 'خرید') {
                $this->totalBuy[$transaction->to_currency] += $transaction->eq_amount;
                $this->totalSell[$transaction->from_currency] += $transaction->amount;
            } else {
                $this->totalSell[$transaction->from_currency] += $transaction->amount;
                $this->totalBuy[$transaction->to_currency] += $transaction->eq_amount;
            }
        }
    }

    // ==================== PROFIT/LOSS CALCULATION METHODS ====================

    /**
     * محاسبه سود/ضرر در زمان واقعی
     */
    public function calculateRealTimeProfitLoss()
    {
        try {
            // اگر ورودی‌ها ناقص باشد، محاسبه انجام نشود
            if (!$this->amount || !$this->exchange_rate || !$this->currency || !$this->to_currency) {
                $this->resetProfitLossDisplay();
                return;
            }

            // محاسبه سود یا ضرر
            $profitLoss = $this->calculateProfitOrLoss();

            // ست کردن نتیجه
            $this->profit_loss_data = [
                'profit' => $profitLoss['profit'],
                'loss' => $profitLoss['loss'],
                'predefined_rate' => $profitLoss['predefined_rate'],
                'amount_with_predefined_rate' => $profitLoss['amount_with_predefined_rate'],
                'amount_with_entered_rate' => $profitLoss['amount_with_entered_rate'],
                'difference' => $profitLoss['difference']
            ];

            // نمایش نتیجه روی فرم
            $this->updateProfitLossDisplay();
        } catch (\Exception $e) {
            Log::error('خطا در محاسبه سود/ضرر زمان واقعی: ' . $e->getMessage());
            $this->resetProfitLossDisplay();
        }
    }



    /**
     * محاسبه سود/ضرر خرید یا فروش ارز
     */
    private function calculateProfitOrLoss()
    {
        try {
            Log::info('=== شروع محاسبه سود/ضرر خرید/فروش ===', [
                'transaction_type' => $this->transactionType,
                'amount' => $this->amount,
                'exchange_rate' => $this->exchange_rate,
                'eq_amount' => $this->eq_amount,
                'currency' => $this->currency,
                'to_currency' => $this->to_currency,
            ]);

            // 1️⃣ دریافت نرخ از پیش تعیین شده بر اساس نوع تراکنش
            $predefinedRate = $this->getBuySellPredefinedRate();

            if ($predefinedRate === null || $predefinedRate == 0) {
                Log::warning("❌ نرخ پیش‌فرض یافت نشد یا صفر است");
                return $this->getDefaultProfitLossResult();
            }

            // 2️⃣ محاسبه مبلغ معادل براساس نرخ سیستم
            $amountWithPredefinedRate = $this->calculateBuySellWithPredefinedRate($predefinedRate);

            // 3️⃣ مبلغ معادل واقعی با نرخ وارد شده
            $amountWithEnteredRate = floatval($this->eq_amount);

            Log::info('📌 داده‌های پایه:', [
                'predefined_rate' => $predefinedRate,
                'amount_with_predefined_rate' => $amountWithPredefinedRate,
                'amount_with_entered_rate' => $amountWithEnteredRate
            ]);

            // 4️⃣ محاسبه تفاوت بر اساس نوع تراکنش
            $difference = 0;

            if ($this->transactionType === 'خرید') {
                // خرید: اگر نرخ وارد شده کمتر از نرخ فروش بازار باشد → سود
                // تفاوت = مبلغ با نرخ وارد شده - مبلغ با نرخ فروش بازار
                $difference = $amountWithEnteredRate - $amountWithPredefinedRate;
                Log::info("💰 محاسبه سود/ضرر خرید:", [
                    'formula' => "{$amountWithEnteredRate} (مبلغ با نرخ وارد شده) - {$amountWithPredefinedRate} (مبلغ با نرخ فروش بازار) = {$difference}",
                    'تفسیر' => 'مثبت = سود (ارزان‌تر از نرخ فروش بازار خریدیم)',
                    'منطق' => 'هرچی ارزانتر خریدم، فایده'
                ]);
            } else {
                // فروش: اگر نرخ وارد شده بیشتر از نرخ خرید بازار باشد → سود
                // تفاوت = مبلغ با نرخ وارد شده - مبلغ با نرخ خرید بازار
                $difference = $amountWithEnteredRate - $amountWithPredefinedRate;
                Log::info("💰 محاسبه سود/ضرر فروش:", [
                    'formula' => "{$amountWithEnteredRate} (مبلغ با نرخ وارد شده) - {$amountWithPredefinedRate} (مبلغ با نرخ خرید بازار) = {$difference}",
                    'تفسیر' => 'مثبت = سود (گران‌تر از نرخ خرید بازار فروختیم)',
                    'منطق' => 'هرچی از نرخ خرید بازار بزرگتر فروختم، فایده'
                ]);
            }

            // 5️⃣ تعیین سود یا ضرر
            $profit = $difference > 0 ? $difference : 0;
            $loss = $difference < 0 ? abs($difference) : 0;

            Log::info('🔍 سود/ضرر در ارز مقصد:', [
                'profit' => $profit,
                'loss' => $loss,
                'currency' => $this->to_currency
            ]);

            // 6️⃣ تبدیل سود/ضرر به USD
            $profitUsd = 0;
            $lossUsd = 0;

            if ($profit > 0) {
                $profitUsd = $this->convertProfitLossToUsd($profit, $this->to_currency);
                Log::info('💰 سود تبدیل به USD:', [
                    'profit_original' => $profit,
                    'profit_usd' => $profitUsd
                ]);
            }

            if ($loss > 0) {
                $lossUsd = $this->convertProfitLossToUsd($loss, $this->to_currency);
                Log::info('💸 ضرر تبدیل به USD:', [
                    'loss_original' => $loss,
                    'loss_usd' => $lossUsd
                ]);
            }

            Log::info('🔍 نتیجه نهایی در USD:', [
                'profit' => $profitUsd,
                'loss' => $lossUsd
            ]);

            return [
                'profit' => round($profitUsd, 4),
                'loss' => round($lossUsd, 4),
                'predefined_rate' => round($predefinedRate, 4),
                'amount_with_predefined_rate' => round($amountWithPredefinedRate, 4),
                'amount_with_entered_rate' => round($amountWithEnteredRate, 4),
                'difference' => round($difference, 4)
            ];
        } catch (\Exception $e) {
            Log::error('❌ خطا در compute سود/ضرر: ' . $e->getMessage());
            return $this->getDefaultProfitLossResult();
        }
    }


    /**
     * دریافت نرخ از پیش تعیین شده برای خرید/فروش
     */
    private function getBuySellPredefinedRate()
    {
        // منطق: 
        // - برای خرید: نرخ فروش بازار (صراف می‌خرد، پس باید با نرخ فروش بازار مقایسه کند)
        // - برای فروش: نرخ خرید بازار (صراف می‌فروشد، پس باید با نرخ خرید بازار مقایسه کند)
        $rateType = $this->transactionType === 'خرید' ? 'sell_cash' : 'buy_cash';

        Log::info("جستجوی نرخ بازار برای {$this->transactionType}: {$this->currency} → {$this->to_currency} با نوع: {$rateType}");

        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        $usdProfitRate = ProfitRate::where('source_currency', 'usd')
            ->where('admin_id', $adminId)
            ->latest()
            ->first();

        if (!$usdProfitRate) {
            Log::warning('❌ رکورد USD در جدول profit_rate یافت نشد');
            return null;
        }

        // حالت خاص: تبدیل AFN ↔ IRR (از رکورد AFN استفاده کن)
        if (($this->currency === 'afn' && $this->to_currency === 'irr') ||
            ($this->currency === 'irr' && $this->to_currency === 'afn')
        ) {
            Log::info("🔍 حالت خاص: تبدیل {$this->currency} ↔ {$this->to_currency}");

            $user = Auth::guard('sarafi')->user();
            $adminId = $user->admin_id ?? $user->id;

            $afnProfitRate = ProfitRate::where('source_currency', 'afn')
                ->where('admin_id', $adminId)
                ->latest()
                ->first();

            if ($afnProfitRate) {
                // تعیین فیلد بر اساس نوع نرخ
                $field = $rateType === 'sell_cash' ? 'irr_sell_cash' : 'irr_buy_cash';
                $inverseRate = $afnProfitRate->{$field} ?? 0;

                Log::info("نرخ معکوس یافت شد از رکورد AFN", [
                    'inverse_rate' => $inverseRate,
                    'rate_field' => $field,
                    'note' => 'هر 1000 تومان = ' . $inverseRate . ' افغانی'
                ]);

                if ($inverseRate > 0) {
                    return $inverseRate;
                }
            }

            Log::warning("❌ نرخ معکوس برای تبدیل {$this->currency} ↔ {$this->to_currency} یافت نشد");
            return null;
        }

        // ====================================================
        // حالت‌های USD ↔ AFN - مهم: همیشه از رکورد USD استفاده کن
        // ====================================================
        if (($this->currency === 'usd' && $this->to_currency === 'afn') ||
            ($this->currency === 'afn' && $this->to_currency === 'usd')
        ) {
            Log::info("🔍 حالت USD ↔ AFN: {$this->currency} → {$this->to_currency}");

            // همیشه از رکورد USD استفاده کن
            if ($usdProfitRate) {
                if ($this->currency === 'usd') {
                    // دلار به افغانی (فروش دلار)
                    $field = 'afn_' . $rateType; // 'afn_sell_cash' یا 'afn_buy_cash'
                    $rate = $usdProfitRate->{$field} ?? 0;

                    Log::info("نرخ {$this->transactionType} دلار به افغانی", [
                        'rate' => $rate,
                        'rate_field' => $field,
                        'note' => 'هر 1 دلار = ' . $rate . ' افغانی'
                    ]);

                    if ($rate > 0) {
                        return $rate;
                    }
                } else {
                    // افغانی به دلار (فروش افغانی)
                    // برای خرید افغانی: معکوس نرخ خرید دلار
                    // برای فروش افغانی: معکوس نرخ فروش دلار
                    $field = 'afn_' . $rateType; // 'afn_sell_cash' یا 'afn_buy_cash'
                    $dollarRate = $usdProfitRate->{$field} ?? 0;

                    if ($dollarRate > 0) {
                        $rate = 1 / $dollarRate; // معکوس نرخ
                        Log::info("معکوس نرخ دلار برای {$this->transactionType} افغانی", [
                            'dollar_rate' => $dollarRate,
                            'rate' => $rate,
                            'rate_field' => $field,
                            'note' => 'هر 1 افغانی = ' . $rate . ' دلار'
                        ]);

                        return $rate;
                    }
                }
            }

            Log::warning("❌ نرخ برای تبدیل {$this->currency} ↔ {$this->to_currency} یافت نشد");
            return null;
        }

        // ====================================================
        // سایر تبدیل‌ها - از رکورد USD استفاده کن
        // ====================================================
        if ($usdProfitRate) {
            // اگر مبدا USD باشد
            if ($this->currency === 'usd') {
                $field = $this->to_currency . '_' . $rateType;
                $rate = $usdProfitRate->{$field} ?? null;

                if ($rate && $rate > 0) {
                    Log::info("✅ نرخ مستقیم از USD: {$this->currency} → {$this->to_currency} = {$rate} (نوع: {$rateType})");
                    return $rate;
                }
            }
            // اگر مقصد USD باشد
            elseif ($this->to_currency === 'usd') {
                // معکوس نرخ: اگر نوع rateType sell_cash باشد، ما buy_cash نیاز داریم و برعکس
                $reverseRateType = $rateType === 'sell_cash' ? 'buy_cash' : 'sell_cash';
                $field = $this->currency . '_' . $reverseRateType;
                $rate = $usdProfitRate->{$field} ?? null;

                if ($rate && $rate > 0) {
                    $inverseRate = 1 / $rate;
                    Log::info("✅ معکوس نرخ از USD: {$this->currency} → {$this->to_currency} = 1/{$rate} = {$inverseRate} (نوع: {$rateType})");
                    return $inverseRate;
                }
            }
            // اگر هیچکدام USD نباشند، نرخ متقاطع
            else {
                $fromField = $this->currency . '_' . $rateType;
                $toField = $this->to_currency . '_' . $rateType;

                $fromRate = $usdProfitRate->{$fromField} ?? null;
                $toRate = $usdProfitRate->{$toField} ?? null;

                if ($fromRate && $toRate && $fromRate > 0 && $toRate > 0) {
                    $crossRate = $toRate / $fromRate;
                    Log::info("✅ نرخ متقاطع از USD: {$this->currency} → {$this->to_currency} = {$toRate} ÷ {$fromRate} = {$crossRate} (نوع: {$rateType})");
                    return $crossRate;
                }
            }
        }

        Log::warning("❌ هیچ نرخ مناسبی برای {$this->currency} به {$this->to_currency} با نوع {$rateType} یافت نشد");
        return null;
    }
    /**
     * محاسبه با نرخ از پیش تعیین شده برای خرید/فروش
     */
    private function calculateBuySellWithPredefinedRate($predefinedRate)
    {
        $amount = floatval($this->amount);

        Log::info("محاسبه مبلغ با نرخ بازار برای {$this->transactionType}: {$amount} {$this->currency} → {$this->to_currency} با نرخ: {$predefinedRate}");

        $result = 0;

        // موارد خاص برای تبدیل‌های شناخته شده
        if ($this->currency === 'afn' && $this->to_currency === 'irr') {
            // افغانی → تومان: (مبلغ × 1000) ÷ نرخ معکوس
            $result = ($amount * 1000) / $predefinedRate;
            Log::info("فرمول AFN → IRR: ({$amount} × 1000) ÷ {$predefinedRate} = {$result}");
        } elseif ($this->currency === 'irr' && $this->to_currency === 'afn') {
            // تومان → افغانی: (مبلغ × نرخ معکوس) ÷ 1000
            $result = ($amount * $predefinedRate) / 1000;
            Log::info("فرمول IRR → AFN: ({$amount} × {$predefinedRate}) ÷ 1000 = {$result}");
        }
        // برای افغانی به دلار
        elseif ($this->currency === 'afn' && $this->to_currency === 'usd') {
            // نرخ پیش‌فرض: هر 1 افغانی = X دلار
            $result = $amount * $predefinedRate;
            Log::info("فرمول AFN → USD: {$amount} × {$predefinedRate} = {$result}");
        }
        // برای دلار به افغانی
        elseif ($this->currency === 'usd' && $this->to_currency === 'afn') {
            // نرخ پیش‌فرض: هر 1 دلار = X افغانی
            $result = $amount * $predefinedRate;
            Log::info("فرمول USD → AFN: {$amount} × {$predefinedRate} = {$result}");
        }
        // برای سایر تبدیل‌ها
        else {
            // همیشه فرمول: مبلغ × نرخ
            $result = $amount * $predefinedRate;
            Log::info("فرمول عمومی: {$amount} × {$predefinedRate} = {$result}");
        }

        Log::info("نتیجه محاسبه", ['result' => $result]);

        return $result;
    }

    /**
     * تبدیل سود/ضرر به USD بر اساس منطق خاص
     */
    private function convertProfitLossToUsd($amount, $currency)
    {
        Log::info("تبدیل {$currency} به USD", ['amount' => $amount]);

        if ($currency === 'usd') {
            return $amount;
        }

        // همیشه از رکورد USD استفاده کن
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

        // برای تبدیل IRR به USD - از نرخ خرید نقدی تومان استفاده کن
        if ($currency === 'irr') {
            $rate = $usdProfitRate->irr_buy_cash ?? 0;
            Log::info("نرخ خرید نقدی IRR → USD یافت شد", ['rate' => $rate]);

            if ($rate > 0) {
                $result = $amount / $rate;
                Log::info("نتیجه تبدیل", [
                    'result' => $result,
                    'formula' => "{$amount} ÷ {$rate} = {$result}"
                ]);
                return $result;
            }
        }

        // برای تبدیل AFN به USD - از نرخ خرید نقدی افغانی استفاده کن
        if ($currency === 'afn') {
            // نرخ خرید دلار: هر دلار = چند افغانی؟
            $rate = $usdProfitRate->afn_buy_cash ?? 0;
            Log::info("نرخ خرید نقدی AFN → USD یافت شد", [
                'rate' => $rate,
                'rate_field' => 'afn_buy_cash',
                'note' => 'هر دلار = ' . $rate . ' افغانی'
            ]);

            if ($rate > 0) {
                $result = $amount / $rate;
                Log::info("نتیجه تبدیل", [
                    'result' => $result,
                    'formula' => "{$amount} ÷ {$rate} = {$result}"
                ]);
                return $result;
            }
        }

        // برای سایر ارزها - از نرخ خرید نقدی استفاده کن
        $rateField = $currency . '_buy_cash';
        $rate = $usdProfitRate->{$rateField} ?? 0;

        if ($rate <= 0) {
            $rateField = $currency . '_buy_bank';
            $rate = $usdProfitRate->{$rateField} ?? 0;
        }

        Log::info("نرخ یافت شده", [
            'rate_field' => $rateField,
            'rate' => $rate,
            'formula' => "{$amount} ÷ {$rate}"
        ]);

        if ($rate > 0) {
            $result = $amount / $rate;
            Log::info("نتیجه تبدیل", ['result' => $result]);
            return $result;
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
     * ثبت سود/ضرر در جدول revenues برای خرید/فروش
     */
    private function recordBuySellProfitLoss($exchangeId, $profitLoss)
    {
        try {
            $user = Auth::guard('sarafi')->user();
            $adminId = $user->admin_id ?? $user->id;

            if ($profitLoss['profit'] > 0 || $profitLoss['loss'] > 0) {
                Log::info('📊 در حال ثبت سود/ضرر خرید/فروش در جدول revenue...', [
                    'profit' => $profitLoss['profit'],
                    'loss' => $profitLoss['loss'],
                    'exchange_id' => $exchangeId
                ]);

                $revenueData = [
                    'currency' => 'usd',
                    'profit' => $profitLoss['profit'],
                    'lost' => $profitLoss['loss'],
                    'from' => 'خرید/فروش ارز',
                    'description' => $this->generateBuySellProfitLossDescription($profitLoss),
                    'user_id' => $user->id,
                    'admin_id' => $adminId,
                    'safe_exchange_id' => $exchangeId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $revenue = Revenue::create($revenueData);

                Log::info("✅ سود/ضرر خرید/فروش در جدول revenue ثبت شد - ID: {$revenue->id}");

                return $revenue;
            }

            Log::info('ℹ️ هیچ سود یا ضرری برای ثبت در revenue وجود ندارد');
            return null;
        } catch (\Exception $e) {
            Log::error('❌ خطا در ثبت سود/ضرر خرید/فروش: ' . $e->getMessage());
            throw $e;
        }
    }
    /**
     * تولید توضیحات برای سود/ضرر خرید/فروش
     */
    private function generateBuySellProfitLossDescription($profitLoss)
    {
        $fromCurrency = $this->getCurrencyName($this->currency);
        $toCurrency = $this->getCurrencyName($this->to_currency);

        $description = "سود/ضرر {$this->transactionType} از {$fromCurrency} به {$toCurrency} - ";
        $description .= "مبلغ: " . number_format($this->amount) . " {$this->currency} - ";
        $description .= "نرخ وارد شده: " . number_format($this->exchange_rate, 4) . " - ";
        $description .= "نرخ پیش‌فرض: " . number_format($profitLoss['predefined_rate'], 4);

        if ($profitLoss['profit'] > 0) {
            $description .= " - سود: " . number_format($profitLoss['profit'], 4) . " دالر";
        } else {
            $description .= " - ضرر: " . number_format($profitLoss['loss'], 4) . " دالر";
        }

        return $description;
    }

    /**
     * به‌روزرسانی نمایش سود/ضرر در فرم
     */
    private function updateProfitLossDisplay()
    {
        $profit = $this->profit_loss_data['profit'] ?? 0;
        $loss = $this->profit_loss_data['loss'] ?? 0;

        if ($profit > 0) {
            $this->profit_display = 'سود پیش‌بینی شده: ' . number_format($profit, 4) . ' دالر';
            $this->loss_display = '';
        } elseif ($loss > 0) {
            $this->profit_display = '';
            $this->loss_display = 'ضرر پیش‌بینی شده: ' . number_format($loss, 4) . ' دالر';
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
        $this->profit_loss_data = [
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
    // ==================== HELPER METHODS ====================

    /**
     * Get user balance for specific currency
     */
    public function getUserBalance($currency)
    {
        $user = Auth::guard('sarafi')->user();

        if (!$user) {
            return 0;
        }

        $userId = $user->id;

        // First try to find user's safe
        $safe = CurrencySafe::where('user_id', $userId)->first();

        // If user safe not found, use admin safe
        if (!$safe) {
            $adminId = $user->admin_id ?? $user->id;
            $safe = CurrencySafe::where('user_id', $adminId)->first();
        }

        return $safe->{$currency} ?? 0;
    }

    /**
     * Get currency name in Persian
     */
    public function getCurrencyName($code)
    {
        $currencyNames = [
            'usd' => 'دالر',
            'afn' => 'افغانی',
            'irr' => 'تومان',
            'eur' => 'یورو',
            'pkr' => 'کلدار',
            'aed' => 'درهم',
            'try' => 'لیره',
            'cny' => 'یوان'
        ];

        return $currencyNames[$code] ?? $code;
    }

    /**
     * Get calculation formula for display
     */
    public function getCalculationFormula()
    {
        if (!$this->amount || !$this->exchange_rate) {
            return '';
        }

        $from = $this->getCurrencyName($this->currency);
        $to = $this->getCurrencyName($this->to_currency);

        if ($this->transactionType === 'خرید') {
            return "{$this->amount} {$from} ÷ {$this->exchange_rate} = {$this->eq_amount} {$to}";
        } else {
            return "{$this->amount} {$from} × {$this->exchange_rate} = {$this->eq_amount} {$to}";
        }
    }

    /**
     * Get rate hint for user guidance
     */
    public function getRateHint()
    {
        if (!$this->currency || !$this->to_currency) {
            return '';
        }

        $from = $this->getCurrencyName($this->currency);
        $to = $this->getCurrencyName($this->to_currency);

        if ($this->transactionType === 'خرید') {
            return "📊 نرخ: هر 1 {$to} چند {$from} است؟ (مثلاً 1 {$to} = ? {$from})";
        } else {
            return "📊 نرخ: هر 1 {$from} چند {$to} است؟ (مثلاً 1 {$from} = ? {$to})";
        }
    }

    // ==================== TRANSACTION OPERATIONS ====================

    /**
     * Toggle transaction type between buy and sell
     */
    public function toggleTransactionType()
    {
        $this->transactionType = $this->transactionType === 'خرید' ? 'فروش' : 'خرید';
        $this->calculateRealTimeProfitLoss();
    }

    /**
     * Swap source and destination currencies
     */
    public function swapCurrencies()
    {
        // Swap source and destination currencies
        $temp = $this->currency;
        $this->currency = $this->to_currency;
        $this->to_currency = $temp;

        // Reverse exchange rate if exists
        if ($this->exchange_rate && floatval($this->exchange_rate) > 0) {
            $currentRate = floatval($this->exchange_rate);
            $this->exchange_rate = number_format(1 / $currentRate, 4, '.', '');
        }

        // Recalculate equivalent amount
        $this->calculateEquivalentAmount();
        $this->calculateRealTimeProfitLoss();
    }

    /**
     * Check balance before transaction
     */
    public function checkBalance()
    {
        if (!$this->amount || !$this->currency) {
            return false;
        }

        $currentBalance = $this->getUserBalance($this->currency);
        $requiredAmount = floatval($this->amount);

        // در هر دو حالت خرید و فروش، باید موجودی ارز مبدا کافی باشد
        if ($currentBalance < $requiredAmount) {
            session()->flash('message', "موجودی کافی نیست! موجودی {$this->getCurrencyName($this->currency)} شما: " . number_format($currentBalance));
            return false;
        }

        return true;
    }

    /**
     * Submit transaction form
     */
    public function submitTransaction()
    {

        $this->validate([
            'currency' => 'required|string',
            'to_currency' => 'required|string|different:currency',
            'amount' => 'required|numeric|min:0.01',
            'exchange_rate' => 'required|numeric|min:0.01',
            'eq_amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'description' => 'required|string|min:3',
            'transaction_file' => 'nullable|file|max:10240',
        ]);

        // Check balance (only for new transactions)
        if (!$this->isEditing && !$this->checkBalance()) {
            return;
        }

        try {
            DB::transaction(function () {
                $user = Auth::guard('sarafi')->user();
                $userId = $user->id;
                $adminId = $user->admin_id ?? $user->id;
                $amount = floatval($this->amount);
                $eqAmount = floatval($this->eq_amount);

                // محاسبه سود/ضرر
                $profitLoss = $this->calculateProfitOrLoss();

                // If editing existing transaction
                if ($this->isEditing && $this->editingId) {
                    $transaction = CashExchange::findOrFail($this->editingId);

                    // First reverse the previous transaction
                    $this->reverseTransaction($transaction);

                    // حذف سود/ضرر قبلی
                    Revenue::where('safe_exchange_id', $transaction->id)->delete();

                    $filePath = $transaction->transaction_file;
                    if ($this->transaction_file) {
                        // Delete previous file if exists
                        if ($filePath) {
                            Storage::disk('public')->delete($filePath);
                        }
                        $filePath = $this->transaction_file->store('transaction-files', 'public');
                    }

                    // Update transaction
                    $transaction->update([
                        'type' => $this->transactionType,
                        'from_currency' => $this->currency,
                        'amount' => $amount,
                        'to_currency' => $this->to_currency,
                        'eq_amount' => $eqAmount,
                        'exchange_rate' => $this->exchange_rate,
                        'date' => $this->date,
                        'description' => $this->description,
                        'transaction_file' => $filePath,
                    ]);

                    // Apply new changes to safe
                    $this->applyTransaction($transaction);

                    // ثبت سود/ضرر جدید
                    $this->recordBuySellProfitLoss($transaction->id, $profitLoss);

                    session()->flash('message', 'تراکنش با موفقیت ویرایش شد.');
                } else {
                    // Create new transaction
                    $filePath = null;
                    if ($this->transaction_file) {
                        $filePath = $this->transaction_file->store('transaction-files', 'public');
                    }


                    $user = Auth::guard('sarafi')->user();
                    $adminId = $user->admin_id ?? $user->id;

                    $exchange = CashExchange::create([
                        'user_id'          => $user->id,
                        'admin_id'         => $adminId,
                        'type' => $this->transactionType,
                        'from_currency' => $this->currency,
                        'amount' => $amount,
                        'to_currency' => $this->to_currency,
                        'eq_amount' => $eqAmount,
                        'exchange_rate' => $this->exchange_rate,
                        'date' => $this->date,
                        'description' => $this->description,
                        'transaction_file' => $filePath,
                    ]);

                    // Update currency safe
                    $this->updateCurrencySafe($userId, $adminId, $amount, $eqAmount);

                    // ثبت سود/ضرر
                    $this->recordBuySellProfitLoss($exchange->id, $profitLoss);

                    session()->flash('message', 'تراکنش با موفقیت ثبت شد و صندوق آپدیت شد.');
                }

                $this->resetForm();
            });
        } catch (\Exception $e) {
            session()->flash('message', 'خطا در ثبت تراکنش: ' . $e->getMessage());
            Log::error('Transaction submission error: ' . $e->getMessage());
        }
    }

    /**
     * Submit and print transaction
     */
    public function submitAndPrint()
    {
        $this->validate([
            'currency' => 'required|string',
            'to_currency' => 'required|string|different:currency',
            'amount' => 'required|numeric|min:0.01',
            'exchange_rate' => 'required|numeric|min:0.01',
            'eq_amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'description' => 'required|string|min:3',
            'transaction_file' => 'nullable|file|max:10240',
        ]);

        // Check balance
        if (!$this->checkBalance()) {
            return;
        }

        try {
            $transaction = null;
            $profitLoss = $this->calculateProfitOrLoss();

            DB::transaction(function () use (&$transaction, $profitLoss) {
                $filePath = null;
                if ($this->transaction_file) {
                    $filePath = $this->transaction_file->store('transaction-files', 'public');
                }

                $user = Auth::guard('sarafi')->user();
                $userId = $user->id;
                $adminId = $user->admin_id ?? $user->id;
                $amount = floatval($this->amount);
                $eqAmount = floatval($this->eq_amount);

                // Create transaction
                $transaction = CashExchange::create([
                    'user_id' => $userId,
                    'admin_id' => $adminId !== $userId ? $adminId : null,
                    'type' => $this->transactionType,
                    'from_currency' => $this->currency,
                    'amount' => $amount,
                    'to_currency' => $this->to_currency,
                    'eq_amount' => $eqAmount,
                    'exchange_rate' => $this->exchange_rate,
                    'date' => $this->date,
                    'description' => $this->description,
                    'transaction_file' => $filePath,
                ]);

                // Update currency safe
                $this->updateCurrencySafe($userId, $adminId, $amount, $eqAmount);

                // ثبت سود/ضرر
                $this->recordBuySellProfitLoss($transaction->id, $profitLoss);

                $this->resetForm();
            });

            // Generate PDF after successful submission
            if ($transaction) {
                return $this->generateTransactionPdf($transaction->id);
            }
        } catch (\Exception $e) {
            session()->flash('message', 'خطا در ثبت تراکنش: ' . $e->getMessage());
            Log::error('Submit and print error: ' . $e->getMessage());
        }
    }

    // ==================== CURRENCY SAFE OPERATIONS ====================

    /**
     * Reverse (rollback) a transaction from admin safe
     */
    private function reverseTransaction(CashExchange $transaction): void
    {
        $ownerId = $transaction->admin_id ?? $transaction->user_id;

        $safe = CurrencySafe::where('user_id', $ownerId)
            ->lockForUpdate()
            ->firstOrFail();


        $safe->{$transaction->from_currency} += $transaction->amount;
        $safe->{$transaction->to_currency}   -= $transaction->eq_amount;

        $safe->save();
    }


    /**
     * Apply transaction to admin safe
     */
    private function applyTransaction(CashExchange $transaction): void
    {
        $ownerId = $transaction->admin_id ?? $transaction->user_id;

        $safe = CurrencySafe::where('user_id', $ownerId)
            ->lockForUpdate()
            ->firstOrFail();


        $safe->{$transaction->from_currency} -= $transaction->amount;
        $safe->{$transaction->to_currency}   += $transaction->eq_amount;

        $safe->save();
    }


    /**
     * Update currency safe with new transaction
     */
    private function updateCurrencySafe($userId, $adminId, $amount, $eqAmount)
    {
        $ownerId = $adminId ?? $userId;

        $safe = CurrencySafe::where('user_id', $ownerId)->first();

        if (!$safe) {
            $safe = new CurrencySafe();
            $safe->user_id  = $ownerId;
            $safe->admin_id = $ownerId;

            foreach ($this->currencies as $currency) {
                $safe->{$currency['code']} = 0;
            }
        }

        if ($this->transactionType === 'خرید') {
            $safe->{$this->currency}    -= $amount;
            $safe->{$this->to_currency} += $eqAmount;
        } else {
            $safe->{$this->currency}    -= $amount;
            $safe->{$this->to_currency} += $eqAmount;
        }

        $safe->save();
    }


    // ==================== EDIT OPERATIONS ====================

    /**
     * Load transaction data for editing
     */
    public function editTransaction(int $id): void
    {
        try {
            $user = Auth::guard('sarafi')->user();

            $transaction = CashExchange::findOrFail($id);


            $ownerId = $transaction->admin_id ?? $transaction->user_id;

            if ($user->id !== $ownerId && $user->admin_id !== $ownerId) {
                session()->flash('message', 'دسترسی به این تراکنش مجاز نیست.');
                return;
            }

            $this->editingId      = $transaction->id;
            $this->isEditing      = true;
            $this->transactionType = $transaction->type;


            $this->currency     = $transaction->from_currency;
            $this->to_currency  = $transaction->to_currency;


            $this->amount        = number_format($transaction->amount, 2, '.', '');
            $this->eq_amount     = number_format($transaction->eq_amount, 2, '.', '');
            $this->exchange_rate = number_format($transaction->exchange_rate, 6, '.', '');


            $this->date        = $transaction->date;
            $this->description = $transaction->description;


            $this->convertAmountToWords($this->amount, 'amountInWords');
            $this->convertAmountToWords($this->eq_amount, 'eqAmountInWords');
            $this->convertAmountToWords($this->exchange_rate, 'exchangeRateInWords');


            $this->calculateRealTimeProfitLoss();

            $this->transaction_file = null;

            session()->flash('info', 'حالت ویرایش فعال شد. اطلاعات تراکنش با موفقیت بارگذاری گردید.');
        } catch (\Throwable $e) {
            session()->flash('message', 'خطا در بارگذاری تراکنش: ' . $e->getMessage());
        }
    }

    /**
     * Cancel edit operation
     */
    public function cancel()
    {
        $this->resetForm();
        session()->flash('info', 'ویرایش لغو شد.');
    }

    /**
     * Reset form fields
     */
    private function resetForm()
    {
        $this->reset([
            'amount',
            'eq_amount',
            'exchange_rate',
            'description',
            'transaction_file',
            'editingId',
            'isEditing',
            'amountInWords',
            'eqAmountInWords',
            'exchangeRateInWords'
        ]);
        $this->currency = 'usd';
        $this->to_currency = 'afn';
        $this->date = Jalalian::now()->format('Y/m/d');
        $this->transactionType = 'خرید';
        $this->resetProfitLossDisplay();
    }

    // ==================== DELETE OPERATIONS ====================

    /**
     * Set transaction for deletion confirmation
     */
    public function deleteTransaction($id)
    {
        $this->confirmDeleteId = $id;
    }

    /**
     * Confirm and execute deletion
     */
    public function deleteConfirmed()
    {
        if (!$this->confirmDeleteId) {
            return;
        }

        try {
            DB::transaction(function () {

                $transaction = CashExchange::findOrFail($this->confirmDeleteId);

                // 🔑 تعیین صاحب صندوق (ادمین)
                $ownerId = $transaction->admin_id ?? $transaction->user_id;

                // همیشه صندوق ادمین را بگیر
                $safe = CurrencySafe::where('user_id', $ownerId)->lockForUpdate()->first();

                if ($safe) {
                    // برگشت موجودی (خرید و فروش منطق یکسان دارند)
                    $safe->{$transaction->from_currency} += $transaction->amount;
                    $safe->{$transaction->to_currency}   -= $transaction->eq_amount;

                    $safe->save();
                }

                // حذف سود / ضرر
                Revenue::where('safe_exchange_id', $transaction->id)->delete();

                // حذف فایل
                if ($transaction->transaction_file) {
                    Storage::disk('public')->delete($transaction->transaction_file);
                }

                $transaction->delete();

                session()->flash('message', 'تراکنش با موفقیت حذف شد و موجودی صندوق اصلاح گردید.');
                $this->confirmDeleteId = null;
            });
        } catch (\Exception $e) {
            session()->flash('message', 'خطا در حذف تراکنش: ' . $e->getMessage());
            $this->confirmDeleteId = null;
        }
    }


    /**
     * Cancel deletion operation
     */
    public function cancelDelete()
    {
        $this->confirmDeleteId = null;
    }

    // ==================== PRINT OPERATIONS ====================

    /**
     * Generate transaction PDF
     */
    private function generateTransactionPdf($transactionId)
    {
        try {
            $transaction = CashExchange::findOrFail($transactionId);

            $mpdf = new \Mpdf\Mpdf([
                'mode' => 'utf-8',
                'format' => [85, 297],
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

            $html = view('pdf.Sarafi.cash-exchange', compact('transaction'))->render();
            $mpdf->WriteHTML($html);

            $fileName = 'تراکنش_صرافی_' . $transaction->id . '_' . $transaction->type . '.pdf';
            $path = storage_path('app/public/' . $fileName);

            // ذخیره PDF
            $mpdf->Output($path, 'F');

            // ارسال event به JS (Livewire v3)
            $this->dispatch('print-pdf', url: asset('storage/' . $fileName));
        } catch (\Exception $e) {
            session()->flash('message', 'خطا در ایجاد PDF: ' . $e->getMessage());
        }
    }

    /**
     * Print existing transaction
     */
    public function printTransaction($id)
    {
        try {
            $transaction = CashExchange::findOrFail($id);

            // Check user access
            $user = Auth::guard('sarafi')->user();
            if ($transaction->user_id !== $user->id && $transaction->admin_id !== $user->id) {
                session()->flash('message', 'دسترسی به این تراکنش مجاز نیست.');
                return;
            }

            return $this->generateTransactionPdf($transaction->id);
        } catch (\Exception $e) {
            session()->flash('message', 'خطا در چاپ تراکنش: ' . $e->getMessage());
        }
    }
}
