<?php

namespace App\Livewire\Sarafi;

use App\Models\Sarafi\BankAccount;
use App\Models\Sarafi\CurrencySafe;
use App\Models\Sarafi\Customer;
use App\Models\Sarafi\Remittances;
use App\Models\Sarafi\Revenue;
use App\Models\Sarafi\Transaction;
use App\Models\Sarafi\User;
use App\Models\Sarafi\ProfitRate;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Illuminate\Support\Facades\Log;

class Dashboard extends Component
{
    public $activeTab = 'general';
    public $safe;
    public $safe_account = [];
    public $currencies = [];
    public $total_balance_usd = 0;

    public function mount()
    {
        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        // صندوق نقدی
        $this->safe = CurrencySafe::where('admin_id', $adminId)->first();

        // حساب بانکی
        $bank = BankAccount::where('admin_id', $adminId)->first();
        $this->safe_account = $bank ? $bank->toArray() : [];

        // مقدار پیش‌فرض ارزها
        $defaults = [
            'afn' => 0, 'usd' => 0, 'eur' => 0, 'irr' => 0,
            'aed' => 0, 'try' => 0, 'cny' => 0, 'pkr' => 0,
            'gbp' => 0, 'jpy' => 0, 'sar' => 0, 'inr' => 0,
        ];

        $this->safe_account = array_merge($defaults, $this->safe_account ?? []);

        // ترجمه نام ارزها
        $this->currencies = [
            'afn' => __('messages.safes_afn'),
            'usd' => __('messages.safes_usd'),
            'eur' => __('messages.safes_eur'),
            'irr' => __('messages.safes_irr'),
            'aed' => __('messages.safes_aed'),
            'try' => __('messages.safes_try'),
            'cny' => __('messages.safes_cny'),
            'pkr' => __('messages.safes_pkr'),
            'gbp' => __('messages.safes_gbp'),
            'jpy' => __('messages.safes_jpy'),
            'sar' => __('messages.safes_sar'),
            'inr' => __('messages.safes_inr'),
        ];
    }

    public function calculateTotalBalance()
    {
        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        // دریافت داده‌ها
        $safe = CurrencySafe::where('admin_id', $adminId)->first();
        $bank = BankAccount::where('admin_id', $adminId)->first();
        
        // دریافت نرخ‌ها - با source_currency='usd' (ردیف 32)
        $rates = ProfitRate::where('admin_id', $adminId)
            ->where('source_currency', 'usd')
            ->latest()
            ->first();

        // اگر نرخ با source_currency='usd' نبود، از ردیف‌های دیگر استفاده کن
        if (!$rates) {
            $rates = ProfitRate::where('admin_id', $adminId)
                ->latest()
                ->first();
        }

        $totalUsd = 0;

        // اگر هیچکدام از داده‌ها موجود نباشند
        if (!$safe && !$bank) {
            $this->total_balance_usd = 0;
            return;
        }

        // لیست تمام ارزها
        $allCurrencies = ['afn', 'usd', 'eur', 'irr', 'aed', 'try', 'cny', 'pkr', 'gbp', 'jpy', 'sar', 'inr'];

        // بررسی نوع نرخ‌ها
        $rateSource = $rates ? $rates->source_currency : 'unknown';
        Log::info('نرخ‌های استفاده شده:', [
            'source_currency' => $rateSource,
            'afn_buy_cash' => $rates ? $rates->afn_buy_cash : null,
            'irr_buy_cash' => $rates ? $rates->irr_buy_cash : null,
            'eur_buy_cash' => $rates ? $rates->eur_buy_cash : null,
            'usd_buy_cash' => $rates ? $rates->usd_buy_cash : null,
        ]);

        foreach ($allCurrencies as $currency) {
            // مقدار کل این ارز (نقدی + بانکی)
            $totalAmount = 0;
            
            if ($safe && isset($safe->$currency)) {
                $totalAmount += $safe->$currency;
            }
            
            if ($bank && isset($bank->$currency)) {
                $totalAmount += $bank->$currency;
            }
            
            // اگر مقدار صفر است، ادامه بده
            if ($totalAmount == 0) {
                continue;
            }
            
            // تبدیل به دلار
            if ($currency === 'usd') {
                // خودش دلار است
                $totalUsd += $totalAmount;
                Log::info("USD: {$totalAmount} USD = {$totalAmount} USD");
            } else {
                // نرخ خرید نقدی این ارز
                $rateField = $currency . '_buy_cash';
                
                if ($rates && isset($rates->$rateField)) {
                    $rate = $rates->$rateField;
                    
                    // اگر نرخ صفر یا منفی است، نادیده بگیر
                    if ($rate <= 0) {
                        Log::warning("نرخ خرید نقدی برای ارز {$currency} نامعتبر است: {$rate}", [
                            'currency' => $currency,
                            'amount' => $totalAmount,
                            'rate' => $rate
                        ]);
                        continue;
                    }
                    
                    // محاسبه بر اساس source_currency
                    if ($rateSource === 'usd') {
                        // source_currency='usd' (ردیف 32)
                        // یعنی: 1 USD = [rate] [currency]
                        // پس برای تبدیل: amount [currency] ÷ rate = مقدار USD
                        $converted = $totalAmount / $rate;
                        $totalUsd += $converted;
                        
                        Log::info("تبدیل {$currency} (source=USD): {$totalAmount} ÷ {$rate} = {$converted} USD");
                    } else {
                        // source_currencyهای دیگر (افغانی، یورو، تومان)
                        // این‌ها نشان می‌دهند هر 1 [source_currency] چقدر از ارزهای دیگر می‌خرد
                        // این حالت پیچیده است و نیاز به محاسبه معکوس دارد
                        Log::warning("تبدیل پیچیده برای source_currency={$rateSource} و ارز={$currency}");
                        
                        // برای سادگی، از نرخ‌های پیش‌فرض استفاده می‌کنیم
                        $defaultRates = [
                            'afn' => 66.20,      // 1 USD = 66.20 AFN
                            'irr' => 132400.00,  // 1 USD = 132,400 IRR
                            'eur' => 0.86,       // 1 USD = 0.86 EUR
                            'aed' => 3.67,       // 1 USD = 3.67 AED
                            'try' => 32.00,      // 1 USD = 32.00 TRY
                            'cny' => 7.20,       // 1 USD = 7.20 CNY
                            'pkr' => 277.78,     // 1 USD = 277.78 PKR
                            'gbp' => 0.79,       // 1 USD = 0.79 GBP
                            'jpy' => 150.00,     // 1 USD = 150.00 JPY
                            'sar' => 3.75,       // 1 USD = 3.75 SAR
                            'inr' => 83.00,      // 1 USD = 83.00 INR
                        ];
                        
                        if (isset($defaultRates[$currency])) {
                            $defaultRate = $defaultRates[$currency];
                            $converted = $totalAmount / $defaultRate;
                            $totalUsd += $converted;
                            
                            Log::info("تبدیل {$currency} (پیش‌فرض): {$totalAmount} ÷ {$defaultRate} = {$converted} USD");
                        }
                    }
                } else {
                    // اگر نرخ موجود نیست
                    Log::warning("نرخ خرید نقدی برای ارز {$currency} موجود نیست", [
                        'currency' => $currency,
                        'amount' => $totalAmount,
                        'rate_field' => $rateField
                    ]);
                }
            }
        }
        
        $this->total_balance_usd = round($totalUsd, 2);
        
        // لاگ نهایی
        Log::info('محاسبه موجودی کل Dashboard', [
            'admin_id' => $adminId,
            'total_usd' => $this->total_balance_usd,
            'rate_source' => $rateSource,
            'safe_exists' => $safe ? 'Yes' : 'No',
            'bank_exists' => $bank ? 'Yes' : 'No',
            'rates_exists' => $rates ? 'Yes' : 'No',
        ]);
    }

    public function render()
    {
        // محاسبه مجموع موجودی
        $this->calculateTotalBalance();

        $timezone = 'Asia/Kabul';
        $today = Carbon::now($timezone)->startOfDay();
        $tomorrow = Carbon::now($timezone)->addDay()->startOfDay();

        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        /*
        |--------------------------------------------------------------------------
        | آمار امروز
        |--------------------------------------------------------------------------
        */
        $todayprofit = Revenue::where('admin_id', $adminId)
            ->whereBetween('created_at', [$today, $tomorrow])
            ->sum('profit');

        $todaylost = Revenue::where('admin_id', $adminId)
            ->whereBetween('created_at', [$today, $tomorrow])
            ->sum('lost');

        $customerCount = Customer::where('admin_id', $adminId)->count();
        $UserCount = User::where('admin_id', $adminId)->count();

        $TransactionCount = Transaction::where('admin_id', $adminId)
            ->whereBetween('created_at', [$today, $tomorrow])
            ->count();

        $Waiting = Remittances::where('admin_id', $adminId)
            ->where('state', 0)
            ->count();

        $RemittanceCount = Remittances::where('admin_id', $adminId)
            ->whereBetween('created_at', [$today, $tomorrow])
            ->count();

        return view('livewire.sarafi.dashboard', [
            'UserCount' => $UserCount,
            'customerCount' => $customerCount,
            'TransactionCount' => $TransactionCount,
            'safe' => $this->safe,
            'safe_account' => $this->safe_account,
            'currencies' => $this->currencies,
            'waitting' => $Waiting,
            'remittancecount' => $RemittanceCount,
            'todayprofit' => $todayprofit,
            'todaylost' => $todaylost,
            'total_balance_usd' => $this->total_balance_usd,
        ]);
    }
}