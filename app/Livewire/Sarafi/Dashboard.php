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
public function render()
{
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

    /*
    |--------------------------------------------------------------------------
    | محاسبه مجموع موجودی (نقدی + بانکی) به دالر
    |--------------------------------------------------------------------------
    */

    // اصلاح: استفاده از admin_id بجای user_id برای یکپارچگی
    $safe = CurrencySafe::where('admin_id', $adminId)->first();
    $bank = BankAccount::where('admin_id', $adminId)->first();
    $rates = ProfitRate::where('admin_id', $adminId)->latest()->first();

    $totalCashUsd = 0;
    $totalBankUsd = 0;

    $currencyMap = [
        'afn' => ['cash' => 'afn_buy_cash', 'bank' => 'afn_buy_bank'],
        'usd' => ['cash' => 'usd_buy_cash', 'bank' => 'usd_buy_bank'],
        'eur' => ['cash' => 'eur_buy_cash', 'bank' => 'eur_buy_bank'],
        'irr' => ['cash' => 'irr_buy_cash', 'bank' => 'irr_buy_bank'],
        'aed' => ['cash' => 'aed_buy_cash', 'bank' => 'aed_buy_bank'],
        'try' => ['cash' => 'try_buy_cash', 'bank' => 'try_buy_bank'],
        'cny' => ['cash' => 'cny_buy_cash', 'bank' => 'cny_buy_bank'],
        'pkr' => ['cash' => 'pkr_buy_cash', 'bank' => 'pkr_buy_bank'],
        'gbp' => ['cash' => 'gbp_buy_cash', 'bank' => 'gbp_buy_bank'],
        'jpy' => ['cash' => 'jpy_buy_cash', 'bank' => 'jpy_buy_bank'],
        'sar' => ['cash' => 'sar_buy_cash', 'bank' => 'sar_buy_bank'],
        'inr' => ['cash' => 'inr_buy_cash', 'bank' => 'inr_buy_bank'],
    ];

    if ($rates) {
        foreach ($currencyMap as $currency => $cols) {
            // مقدار نقدی
            if ($safe && isset($safe->$currency)) {
                $cashAmount = $safe->$currency;
                $cashRate = $rates->{$cols['cash']} ?? 0;
                
                if ($cashRate > 0) {
                    $totalCashUsd += $cashAmount / $cashRate;
                }
            }

            // مقدار بانکی
            if ($bank && isset($bank->$currency)) {
                $bankAmount = $bank->$currency;
                $bankRate = $rates->{$cols['bank']} ?? 0;
                
                if ($bankRate > 0) {
                    $totalBankUsd += $bankAmount / $bankRate;
                }
            }
        }
    }

    $this->total_balance_usd = round($totalCashUsd + $totalBankUsd, 2);

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
