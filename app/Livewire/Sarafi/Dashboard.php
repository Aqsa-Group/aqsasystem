<?php

namespace App\Livewire\Sarafi;

use App\Models\Sarafi\BankAccount;
use App\Models\Sarafi\CurrencySafe;
use App\Models\Sarafi\Customer;
use App\Models\Sarafi\ExchangeRates;
use App\Models\Sarafi\Remittances;
use App\Models\Sarafi\Revenue;
use App\Models\Sarafi\Transaction;
use App\Models\Sarafi\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

        // صندوق اصلی
        $this->safe = CurrencySafe::where('user_id', $adminId)->first();

        // حساب بانکی
        $safeAccountData = BankAccount::where('user_id', $adminId)->first();
        $this->safe_account = $safeAccountData ? $safeAccountData->toArray() : [];

        // اگر خالی بود صفر تنظیم کن
        if (empty($this->safe_account)) {
            $this->safe_account = [
                'afn' => 0,
                'usd' => 0,
                'eur' => 0,
                'irr' => 0,
                'aed' => 0,
                'try' => 0,
                'cny' => 0,
                'pkr' => 0,
                'gbp' => 0,
                'jpy' => 0,
                'sar' => 0,
                'inr' => 0,
            ];
        }

        // ترجمه ارزها
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
        | محاسبات امروز
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
        | مجموع موجودی به دالر
        |--------------------------------------------------------------------------
        */

        // جمع ارزهای صندوق و حساب بانکی
        $safe = CurrencySafe::where('admin_id', $adminId)
            ->select([
                'usd', 'afn', 'eur', 'irr', 'aed', 'try', 'cny',
                'pkr', 'gbp', 'jpy', 'sar', 'inr'
            ])->first();

        $bank = BankAccount::where('admin_id', $adminId)
            ->select([
                'usd', 'afn', 'eur', 'irr', 'aed', 'try', 'cny',
                'pkr', 'gbp', 'jpy', 'sar', 'inr'
            ])->first();

        // مجموع تمام ارزها
        $totals = [];
        foreach (['usd','afn','eur','irr','aed','try','cny','pkr','gbp','jpy','sar','inr'] as $c) {
            $totals[$c] = ($safe->$c ?? 0) + ($bank->$c ?? 0);
        }

        // نرخ ارز
        $rates = ExchangeRates::where('admin_id', $adminId)->first();

        $totalInUsd = 0;

        // USD مستقیم
        $totalInUsd += $totals['usd'];

        $map = [
            'afn' => 'afn_sell',
            'eur' => 'eur_sell',
            'irr' => 'irr_sell',
            'aed' => 'aed_sell',
            'try' => 'try_sell',
            'cny' => 'cny_sell',
            'pkr' => 'pkr_sell',
            'gbp' => 'gbp_sell',
            'jpy' => 'jpy_sell',
            'sar' => 'sar_sell',
            'inr' => 'inr_sell',
        ];

        foreach ($map as $currency => $rateColumn) {
            if ($totals[$currency] != 0 && $rates->$rateColumn != 0) {
                $totalInUsd += $totals[$currency] / $rates->$rateColumn;
            }
        }

        $this->total_balance_usd = round($totalInUsd, 2);

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
