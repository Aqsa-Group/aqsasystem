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
        $transactions = CashExchange::when($this->search, function ($query) {
            $query->where('description', 'like', '%' . $this->search . '%')
                ->orWhere('type', 'like', '%' . $this->search . '%');
        })->latest()->get();

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
        $this->calculateEquivalentAmount();
        $this->convertAmountToWords($value, 'exchangeRateInWords');
        $this->calculateRealTimeProfitLoss();
    }

    /**
     * Handle currency field update
     */
    public function updatedCurrency()
    {
        $this->calculateEquivalentAmount();
        if ($this->eq_amount) {
            $this->convertAmountToWords($this->eq_amount, 'eqAmountInWords');
        }
        $this->calculateRealTimeProfitLoss();
    }

    /**
     * Handle to_currency field update
     */
    public function updatedToCurrency()
    {
        $this->calculateEquivalentAmount();
        if ($this->eq_amount) {
            $this->convertAmountToWords($this->eq_amount, 'eqAmountInWords');
        }
        $this->calculateRealTimeProfitLoss();
    }

    /**
     * Handle eq_amount field update
     */
    public function updatedEqAmount($value)
    {
        $this->convertAmountToWords($value, 'eqAmountInWords');
        $this->calculateRealTimeProfitLoss();
    }

    /**
     * Handle transaction type update
     */
    public function updatedTransactionType()
    {
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

            // Convert values to numbers
            $amount = floatval(str_replace(',', '', $this->amount));
            $rate = floatval(str_replace(',', '', $this->exchange_rate));

            // Check if exchange rate is not zero
            if ($rate == 0) {
                $this->eq_amount = '';
                $this->eqAmountInWords = '';
                return;
            }

            // Calculate based on new formula
            if ($fromCurrency === 'afn' && $toCurrency === 'irr') {
                // Convert AFN to IRR: (amount × 1,000) ÷ exchange rate
                $calculatedAmount = ($amount * 1000) / $rate;
            } elseif ($fromCurrency === 'irr' && $toCurrency === 'afn') {
                // Convert IRR to AFN: (amount × exchange rate) ÷ 1,000
                $calculatedAmount = ($amount * $rate) / 1000;
            } else {
                // For other currencies use the previous logic
                $shouldDivide = $this->shouldUseDivision($fromCurrency, $toCurrency);
                if ($shouldDivide) {
                    $calculatedAmount = $amount / $rate;
                } else {
                    $calculatedAmount = $amount * $rate;
                }
            }

            // Limit to 2 decimal places
            $calculatedAmount = round($calculatedAmount, 2);

            // Save as numeric with 2 decimal places
            $this->eq_amount = $calculatedAmount;

            // Convert to words with specific decimal places
            $this->convertAmountToWords($this->amount, 'amountInWords', 2);
            $this->convertAmountToWords($calculatedAmount, 'eqAmountInWords', 2);
            $this->convertAmountToWords($this->exchange_rate, 'exchangeRateInWords', 4);
        } else {
            $this->eq_amount = '';
            $this->amountInWords = '';
            $this->eqAmountInWords = '';
            $this->exchangeRateInWords = '';
        }
    }

    /**
     * Determine calculation logic (division or multiplication) for other currencies
     */
    private function shouldUseDivision($fromCurrency, $toCurrency): bool
    {
        $baseCurrencies = ['usd', 'eur', 'gbp'];
        $localCurrencies = ['afn', 'irr', 'pkr', 'aed', 'try', 'cny', 'inr'];

        // If from base currency to local currency: multiply
        if (in_array($fromCurrency, $baseCurrencies) && in_array($toCurrency, $localCurrencies)) {
            return false;
        }

        // If from local currency to base currency: divide
        if (in_array($fromCurrency, $localCurrencies) && in_array($toCurrency, $baseCurrencies)) {
            return true;
        }

        // Default: divide
        return true;
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
                'profit'                     => $profitLoss['profit'],
                'loss'                       => $profitLoss['loss'],
                'predefined_rate'            => $profitLoss['predefined_rate'],
                'amount_with_predefined_rate' => $profitLoss['amount_with_predefined_rate'],
                'amount_with_entered_rate'   => $profitLoss['amount_with_entered_rate'],
                'difference'                 => $profitLoss['difference']
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
            'amount'           => $this->amount,
            'exchange_rate'    => $this->exchange_rate,
            'eq_amount'        => $this->eq_amount,
            'currency'         => $this->currency,
            'to_currency'      => $this->to_currency,
        ]);

        // 1️⃣ نرخ از پیش تعیین‌شده مناسب (خرید → sell_cash ، فروش → buy_cash)
        $predefinedRate = $this->getBuySellPredefinedRate();

        if (!$predefinedRate) {
            Log::warning("❌ نرخ پیش‌فرض یافت نشد");
            return $this->getDefaultProfitLossResult();
        }

        // 2️⃣ محاسبه مبلغ معادل براساس نرخ سیستم
        $amountWithPredefinedRate = $this->calculateBuySellWithPredefinedRate($predefinedRate);

        // 3️⃣ مبلغ معادل واقعی با نرخ وارد شده
        $amountWithEnteredRate = floatval($this->eq_amount);

        Log::info('📌 داده‌های پایه:', [
            'predefined_rate'             => $predefinedRate,
            'amount_with_predefined_rate' => $amountWithPredefinedRate,
            'amount_with_entered_rate'    => $amountWithEnteredRate
        ]);

        if ($this->transactionType === 'خرید') {
            $difference = $amountWithEnteredRate - $amountWithPredefinedRate;
            Log::info("💰 خرید: {$amountWithEnteredRate} - {$amountWithPredefinedRate} = {$difference}");
        } else {
$difference = $amountWithEnteredRate - $amountWithPredefinedRate;
            Log::info("💰 فروش: {$amountWithPredefinedRate} - {$amountWithEnteredRate} = {$difference}");
        }

        $differenceInUsd = $difference != 0
            ? $this->convertBuySellToUsd(abs($difference), $this->to_currency)
            : 0;

        // 6️⃣ تعیین سود یا ضرر
        $profit = $difference > 0 ? $differenceInUsd : 0;
        $loss   = $difference < 0 ? $differenceInUsd : 0;

        Log::info('🔍 نتیجه نهایی:', [
            'difference'   => $difference,
            'profit_usd'   => $profit,
            'loss_usd'     => $loss,
            'logic'        => ($this->transactionType === 'خرید')
                                ? 'خرید: واقعی - استاندارد'
                                : 'فروش: استاندارد - واقعی'
        ]);

        return [
            'profit'                     => round($profit, 4),
            'loss'                       => round($loss, 4),
            'predefined_rate'            => $predefinedRate,
            'amount_with_predefined_rate'=> $amountWithPredefinedRate,
            'amount_with_entered_rate'   => $amountWithEnteredRate,
            'difference'                 => $difference
        ];

    } catch (\Exception $e) {
        Log::error('❌ خطا در compute سود/ضرر: '.$e->getMessage());
        return $this->getDefaultProfitLossResult();
    }
}


    /**
     * دریافت نرخ از پیش تعیین شده برای خرید/فروش
     */
    private function getBuySellPredefinedRate()
    {
        $rateType = $this->getBuySellRateType();

        Log::info("جستجوی نرخ برای {$this->transactionType}: {$this->currency} → {$this->to_currency} با نوع: {$rateType}");

        // استراتژی‌های مختلف برای یافتن نرخ
        $strategies = [
            'direct_from_currency' => function () use ($rateType) {
                $profitRate = ProfitRate::where('source_currency', $this->currency)->first();
                if ($profitRate) {
                    $field = $this->to_currency . '_' . $rateType;
                    if (isset($profitRate->{$field}) && $profitRate->{$field} > 0) {
                        Log::info("✅ استراتژی 1: نرخ از رکورد {$this->currency} - فیلد {$field} = {$profitRate->{$field}}");
                        return $profitRate->{$field};
                    }
                }
                return null;
            },

            'usd_as_base' => function () use ($rateType) {
                if ($this->currency === 'usd' || $this->to_currency === 'usd') {
                    $profitRate = ProfitRate::where('source_currency', 'usd')->first();
                    if ($profitRate) {
                        if ($this->currency === 'usd') {
                            $field = $this->to_currency . '_' . $rateType;
                        } else {
                            $field = $this->currency . '_' . $this->getBuySellReverseRateType();
                        }

                        if (isset($profitRate->{$field}) && $profitRate->{$field} > 0) {
                            Log::info("✅ استراتژی 2: نرخ از رکورد USD - فیلد {$field} = {$profitRate->{$field}}");
                            return $profitRate->{$field};
                        }
                    }
                }
                return null;
            },

            'reverse_to_currency' => function () use ($rateType) {
                $profitRate = ProfitRate::where('source_currency', $this->to_currency)->first();
                if ($profitRate) {
                    $reverseRateType = $this->getBuySellReverseRateType();
                    $field = $this->currency . '_' . $reverseRateType;
                    if (isset($profitRate->{$field}) && $profitRate->{$field} > 0) {
                        Log::info("✅ استراتژی 3: نرخ از رکورد {$this->to_currency} - فیلد {$field} = {$profitRate->{$field}}");
                        return $profitRate->{$field};
                    }
                }
                return null;
            },

            'fallback_any_rate' => function () use ($rateType) {
                $profitRates = ProfitRate::all();

                // اولویت با نرخ اصلی مورد نظر
                $preferredFields = [
                    $this->to_currency . '_' . $rateType,
                ];

                // سپس سایر نرخ‌های مشابه
                $preferredFields = array_merge($preferredFields, [
                    $this->to_currency . '_sell_cash',
                    $this->to_currency . '_sell_bank',
                    $this->to_currency . '_buy_cash',
                    $this->to_currency . '_buy_bank',
                    $this->currency . '_sell_cash',
                    $this->currency . '_sell_bank',
                    $this->currency . '_buy_cash',
                    $this->currency . '_buy_bank'
                ]);

                foreach ($profitRates as $profitRate) {
                    foreach ($preferredFields as $field) {
                        if (isset($profitRate->{$field}) && $profitRate->{$field} > 0) {
                            Log::info("✅ استراتژی 4: نرخ از رکورد {$profitRate->source_currency} - فیلد {$field} = {$profitRate->{$field}}");
                            return $profitRate->{$field};
                        }
                    }
                }
                return null;
            }
        ];

        foreach ($strategies as $strategyName => $strategy) {
            $rate = $strategy();
            if ($rate !== null) {
                return $rate;
            }
        }

        Log::warning("❌ هیچ نرخ مناسبی برای {$this->currency} به {$this->to_currency} یافت نشد");
        return null;
    }

    private function getBuySellRateType()
    {
        // در حالت خرید → sell_cash
        // در حالت فروش → buy_cash
        return $this->transactionType === 'خرید' ? 'sell_cash' : 'buy_cash';
    }



    private function getBuySellReverseRateType()
    {
        // معکوس: در حالت خرید → buy_cash
        //        در حالت فروش → sell_cash
        return $this->transactionType === 'خرید' ? 'buy_cash' : 'sell_cash';
    }

    /**
     * محاسبه با نرخ از پیش تعیین شده برای خرید/فروش
     */
    private function calculateBuySellWithPredefinedRate($predefinedRate)
    {
        $amount = floatval($this->amount);

        Log::info("محاسبه {$this->transactionType} با نرخ پیش‌فرض: {$amount} {$this->currency} → {$this->to_currency} با نرخ: {$predefinedRate}");

        // موارد خاص برای تبدیل‌های شناخته شده
        if ($this->currency === 'afn' && $this->to_currency === 'irr') {
            $result = ($amount * 1000) / $predefinedRate;
            Log::info("محاسبه AFN→IRR: ({$amount} × 1000) ÷ {$predefinedRate} = {$result}");
            return $result;
        }

        if ($this->currency === 'irr' && $this->to_currency === 'afn') {
            $result = ($amount * $predefinedRate) / 1000;
            Log::info("محاسبه IRR→AFN: ({$amount} × {$predefinedRate}) ÷ 1000 = {$result}");
            return $result;
        }

        // برای سایر ارزها از منطق استاندارد استفاده می‌کنیم
        $shouldDivide = $this->shouldUseDivision($this->currency, $this->to_currency);
        if ($shouldDivide) {
            $result = $amount / $predefinedRate;
            Log::info("محاسبه {$this->transactionType} (تقسیم): {$amount} ÷ {$predefinedRate} = {$result}");
        } else {
            $result = $amount * $predefinedRate;
            Log::info("محاسبه {$this->transactionType} (ضرب): {$amount} × {$predefinedRate} = {$result}");
        }

        return $result;
    }

    /**
     * تبدیل به دالر برای خرید/فروش
     */
    private function convertBuySellToUsd($amount, $currency)
    {
        if ($currency === 'usd') {
            return $amount;
        }

        $usdProfitRate = ProfitRate::where('source_currency', 'usd')->first();

        if (!$usdProfitRate) {
            Log::warning('❌ هیچ رکورد USD در جدول profit_rate برای تبدیل به دالر یافت نشد');
            return 0;
        }

        // برای خرید/فروش از نرخ فروش نقدی استفاده می‌کنیم
        $usdRateField = $currency . '_sell_cash';
        $usdRate = $usdProfitRate->{$usdRateField} ?? null;

        Log::info("تبدیل {$currency} به دالر برای {$this->transactionType}", [
            'amount' => $amount,
            'currency' => $currency,
            'rate_field' => $usdRateField,
            'rate_value' => $usdRate
        ]);

        if (!$usdRate || $usdRate == 0) {
            Log::warning("❌ نرخ تبدیل {$currency} به دالر یافت نشد");

            $fallbackFields = [
                $currency . '_sell_cash',
                $currency . '_sell_bank',
                $currency . '_buy_cash',
                $currency . '_buy_bank'
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

        $convertedAmount = $amount / $usdRate;

        Log::info("نتیجه تبدیل به دالر برای {$this->transactionType}", [
            'original_amount' => $amount,
            'rate' => $usdRate,
            'converted_amount' => $convertedAmount
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
            $this->profit_loss_display = 'سود پیش‌بینی شده: ' . number_format($profit, 4) . ' دالر';
        } elseif ($loss > 0) {
            $this->profit_loss_display = 'ضرر پیش‌بینی شده: ' . number_format($loss, 4) . ' دالر';
        } else {
            $this->profit_loss_display = 'بدون سود/ضرر';
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

        $this->profit_loss_display = '';
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

        $shouldDivide = $this->shouldUseDivision($this->currency, $this->to_currency);

        if ($shouldDivide) {
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

        $shouldDivide = $this->shouldUseDivision($this->currency, $this->to_currency);

        if ($shouldDivide) {
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

        // Logic for balance check
        if ($this->transactionType === 'خرید') {
            // Buy: Check balance of currency we're giving
            if ($currentBalance < $requiredAmount) {
                session()->flash('message', "موجودی کافی نیست! موجودی {$this->getCurrencyName($this->currency)} شما: " . number_format($currentBalance));
                return false;
            }
        } else {
            // Sell: Check balance of currency we're selling
            $toCurrencyBalance = $this->getUserBalance($this->to_currency);
            $requiredEqAmount = floatval($this->eq_amount);
            if ($toCurrencyBalance < $requiredEqAmount) {
                session()->flash('message', "موجودی کافی نیست! موجودی {$this->getCurrencyName($this->to_currency)} شما: " . number_format($toCurrencyBalance));
                return false;
            }
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

                    $exchange = CashExchange::create([
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
                $safe = CurrencySafe::where('user_id', $userId)->first();

                if (!$safe) {
                    $safe = new CurrencySafe();
                    $safe->user_id = $userId;
                    $safe->admin_id = $adminId !== $userId ? $adminId : null;

                    $adminSafe = CurrencySafe::where('user_id', $adminId)->first();
                    if ($adminSafe) {
                        foreach ($this->currencies as $currency) {
                            $safe->{$currency['code']} = $adminSafe->{$currency['code']} ?? 0;
                        }
                    } else {
                        foreach ($this->currencies as $currency) {
                            $safe->{$currency['code']} = 0;
                        }
                    }
                }

                if ($this->transactionType === 'خرید') {
                    $safe->{$this->currency} -= $amount;
                    $safe->{$this->to_currency} += $eqAmount;
                } else {
                    $safe->{$this->currency} -= $amount;
                    $safe->{$this->to_currency} += $eqAmount;
                }
                $safe->save();

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
     * Reverse a transaction from safe
     */
    private function reverseTransaction($transaction)
    {
        $safe = CurrencySafe::where('user_id', $transaction->user_id)->first();
        if ($safe) {
            if ($transaction->type === 'خرید') {
                // Reverse buy: subtract from bought currency, add to given currency
                $safe->{$transaction->to_currency} -= $transaction->eq_amount;
                $safe->{$transaction->from_currency} += $transaction->amount;
            } else {
                // Reverse sell: subtract from received currency, add to sold currency
                $safe->{$transaction->from_currency} -= $transaction->amount;
                $safe->{$transaction->to_currency} += $transaction->eq_amount;
            }
            $safe->save();
        }
    }

    /**
     * Apply transaction to safe
     */
    private function applyTransaction($transaction)
    {
        $safe = CurrencySafe::where('user_id', $transaction->user_id)->first();
        if ($safe) {
            if ($transaction->type === 'خرید') {
                $safe->{$transaction->from_currency} -= $transaction->amount;
                $safe->{$transaction->to_currency} += $transaction->eq_amount;
            } else {
                $safe->{$transaction->from_currency} -= $transaction->amount;
                $safe->{$transaction->to_currency} += $transaction->eq_amount;
            }
            $safe->save();
        }
    }

    /**
     * Update currency safe with new transaction
     */
    private function updateCurrencySafe($userId, $adminId, $amount, $eqAmount)
    {
        $safe = CurrencySafe::where('user_id', $userId)->first();

        if (!$safe) {
            $safe = new CurrencySafe();
            $safe->user_id = $userId;
            $safe->admin_id = $adminId !== $userId ? $adminId : null;

            $adminSafe = CurrencySafe::where('user_id', $adminId)->first();
            if ($adminSafe) {
                foreach ($this->currencies as $currency) {
                    $safe->{$currency['code']} = $adminSafe->{$currency['code']} ?? 0;
                }
            } else {
                foreach ($this->currencies as $currency) {
                    $safe->{$currency['code']} = 0;
                }
            }
        }

        if ($this->transactionType === 'خرید') {
            $safe->{$this->currency} -= $amount;
            $safe->{$this->to_currency} += $eqAmount;
        } else {
            $safe->{$this->currency} -= $amount;
            $safe->{$this->to_currency} += $eqAmount;
        }
        $safe->save();
    }

    // ==================== EDIT OPERATIONS ====================

    /**
     * Load transaction data for editing
     */
    public function editTransaction($id)
    {
        try {
            $transaction = CashExchange::findOrFail($id);

            // Check user access
            $user = Auth::guard('sarafi')->user();
            if ($transaction->user_id !== $user->id && $transaction->admin_id !== $user->id) {
                session()->flash('message', 'دسترسی به این تراکنش مجاز نیست.');
                return;
            }

            // Fill form with transaction data
            $this->editingId = $transaction->id;
            $this->isEditing = true;
            $this->transactionType = $transaction->type;
            $this->currency = $transaction->from_currency;
            $this->to_currency = $transaction->to_currency;
            $this->amount = number_format($transaction->amount, 2, '.', '');
            $this->eq_amount = number_format($transaction->eq_amount, 2, '.', '');
            $this->exchange_rate = number_format($transaction->exchange_rate, 2, '.', '');
            $this->date = $transaction->date;
            $this->description = $transaction->description;

            // Convert amounts to words
            $this->convertAmountToWords($this->amount, 'amountInWords');
            $this->convertAmountToWords($this->eq_amount, 'eqAmountInWords');
            $this->convertAmountToWords($this->exchange_rate, 'exchangeRateInWords');

            // محاسبه سود/ضرر
            $this->calculateRealTimeProfitLoss();

            $this->transaction_file = null;

            session()->flash('info', 'حالت ویرایش فعال شد. داده‌های تراکنش در فرم لود شدند.');
        } catch (\Exception $e) {
            session()->flash('message', 'خطا در لود کردن تراکنش: ' . $e->getMessage());
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
        $this->date = now()->toDateString();
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

                // Return balance to safe
                $safe = CurrencySafe::where('user_id', $transaction->user_id)->first();
                if ($safe) {
                    if ($transaction->type === 'خرید') {
                        // Reverse buy: subtract from bought currency, add to given currency
                        $safe->{$transaction->to_currency} -= $transaction->eq_amount;
                        $safe->{$transaction->from_currency} += $transaction->amount;
                    } else {
                        // Reverse sell: subtract from received currency, add to sold currency
                        $safe->{$transaction->from_currency} -= $transaction->amount;
                        $safe->{$transaction->to_currency} += $transaction->eq_amount;
                    }
                    $safe->save();
                }

                // حذف سود/ضرر مرتبط
                Revenue::where('safe_exchange_id', $transaction->id)->delete();

                // Delete file
                if ($transaction->transaction_file) {
                    Storage::disk('public')->delete($transaction->transaction_file);
                }

                $transaction->delete();

                session()->flash('message', 'تراکنش با موفقیت حذف شد و موجودی بازگردانده شد.');
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

            return response()->streamDownload(function () use ($mpdf) {
                echo $mpdf->Output('', 'S');
            }, $fileName);
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
