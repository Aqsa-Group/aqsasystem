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
    public $debug_info = [];

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
        $rates = ProfitRate::where('admin_id', $adminId)->latest()->first();

        // ذخیره برای دیباگ
        $this->debug_info = [
            'admin_id' => $adminId,
            'has_safe' => $safe ? 'Yes' : 'No',
            'has_bank' => $bank ? 'Yes' : 'No',
            'has_rates' => $rates ? 'Yes' : 'No',
            'safe_data' => $safe ? $safe->toArray() : [],
            'bank_data' => $bank ? $bank->toArray() : [],
        ];

        $totalCashUsd = 0;
        $totalBankUsd = 0;

        // اگر نرخ‌ها موجود نباشند، فقط دالرها را حساب کن
        if (!$rates) {
            if ($safe) {
                $totalCashUsd = $safe->usd ?? 0;
            }
            if ($bank) {
                $totalBankUsd = $bank->usd ?? 0;
            }
            
            $this->total_balance_usd = round($totalCashUsd + $totalBankUsd, 2);
            return;
        }

        // لیست تمام ارزها
        $allCurrencies = ['afn', 'usd', 'eur', 'irr', 'aed', 'try', 'cny', 'pkr', 'gbp', 'jpy', 'sar', 'inr'];

        foreach ($allCurrencies as $currency) {
            // موجودی نقدی
            if ($safe && isset($safe->$currency) && $safe->$currency > 0) {
                $cashAmount = $safe->$currency;
                $rateField = $currency . '_buy_cash';
                
                if (isset($rates->$rateField) && $rates->$rateField > 0) {
                    $totalCashUsd += $cashAmount / $rates->$rateField;
                } else {
                    // لاگ برای نرخ‌های صفر یا ناموجود
                    Log::warning("نرخ خرید نقدی برای ارز {$currency} صفر یا ناموجود است. مقدار: {$cashAmount}");
                }
            }

            // موجودی بانکی
            if ($bank && isset($bank->$currency) && $bank->$currency > 0) {
                $bankAmount = $bank->$currency;
                $rateField = $currency . '_buy_bank';
                
                if (isset($rates->$rateField) && $rates->$rateField > 0) {
                    $totalBankUsd += $bankAmount / $rates->$rateField;
                } else {
                    // لاگ برای نرخ‌های صفر یا ناموجود
                    Log::warning("نرخ خرید بانکی برای ارز {$currency} صفر یا ناموجود است. مقدار: {$bankAmount}");
                }
            }
        }

        // جمع کل
        $this->total_balance_usd = round($totalCashUsd + $totalBankUsd, 2);
        
        // لاگ نهایی برای دیباگ
        Log::info('Dashboard total balance calculated', [
            'admin_id' => $adminId,
            'total_cash_usd' => $totalCashUsd,
            'total_bank_usd' => $totalBankUsd,
            'total_balance_usd' => $this->total_balance_usd,
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
            'debug_info' => $this->debug_info, // برای تست - در نهایت حذف کنید
        ]);
    }
}