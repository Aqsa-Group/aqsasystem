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
use App\Models\Sarafi\RemittanceApproval;
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



    public $TransactionCount;
    public $LastTransactionTime;

    public $CustomerCount;
    public $LastCustomerTime;


    public $UserCount;
    public $LastUserTime;

    public $RemittanceCount;
    public $LastRemittanceTime;

    public $RemittanceApprovalCount;
    public $LastRemittanceApprovalTime;


    public $ProfitCount;
    public $LastProfitTime;


    public $LostCount;
    public $LastLostTime;




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


        // Transaction

        $startOfDay = Carbon::today()->startOfDay();
        $endOfDay = Carbon::today()->endOfDay();

        $this->TransactionCount = Transaction::whereBetween('created_at', [$startOfDay, $endOfDay])->count();

        $lastTransaction = Transaction::whereBetween('created_at', [$startOfDay, $endOfDay])
            ->latest('created_at')
            ->first();

        $this->LastTransactionTime = $lastTransaction
            ? $lastTransaction->created_at->diffForHumans()
            : 'بدون تراکنش';





        //    Customer
        $this->CustomerCount = Customer::whereBetween('created_at', [$startOfDay, $endOfDay])->count();

        $lastcustomer = Customer::whereBetween('created_at', [$startOfDay, $endOfDay])
            ->latest('created_at')
            ->first();

        $this->LastCustomerTime = $lastcustomer
            ? $lastcustomer->created_at->diffForHumans()
            : 'بدون ثبت مشتری';


        // User
        $this->UserCount = User::whereBetween('created_at', [$startOfDay, $endOfDay])->count();

        $lastuser = User::whereBetween('created_at', [$startOfDay, $endOfDay])
            ->latest('created_at')
            ->first();

        $this->LastUserTime = $lastuser
            ? $lastuser->created_at->diffForHumans()
            : 'بدون ثبت کاربر';

        // Remittance
        $this->RemittanceCount = Remittances::whereBetween('created_at', [$startOfDay, $endOfDay])->count();

        $lastremittance = Remittances::whereBetween('created_at', [$startOfDay, $endOfDay])
            ->latest('created_at')
            ->first();

        $this->LastRemittanceTime = $lastremittance
            ? $lastremittance->created_at->diffForHumans()
            : 'بدون ثبت حواله';


        // Remittance approval
        $this->RemittanceApprovalCount = RemittanceApproval::whereBetween('created_at', [$startOfDay, $endOfDay])->count();

        $lastremittanceapproval = RemittanceApproval::whereBetween('created_at', [$startOfDay, $endOfDay])
            ->latest('created_at')
            ->first();

        $this->LastRemittanceApprovalTime = $lastremittanceapproval
            ? $lastremittanceapproval->created_at->diffForHumans()
            : 'بدون  تایید حواله';


        // Profit
        $this->ProfitCount = Revenue::whereBetween('created_at', [$startOfDay, $endOfDay])->count();

        $lastprofit = Revenue::whereBetween('created_at', [$startOfDay, $endOfDay])
            ->latest('created_at')
            ->where('profit', '>', 0)
            ->first();

        $this->LastProfitTime = $lastprofit
            ? $lastprofit->created_at->diffForHumans()
            : 'بدون   فایده';


        // Lost
        $this->LostCount = Revenue::whereBetween('created_at', [$startOfDay, $endOfDay])->count();

        $lastlost = Revenue::whereBetween('created_at', [$startOfDay, $endOfDay])
            ->latest('created_at')
            ->where('lost', '<', 0)
            ->first();

        $this->LastLostTime = $lastlost
            ? $lastlost->created_at->diffForHumans()
            : 'بدون ضرر';
    }

    public function calculateTotalBalance()
    {
        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        // دریافت داده‌ها
        $safe = CurrencySafe::where('admin_id', $adminId)->first();
        $bank = BankAccount::where('admin_id', $adminId)->first();

        // دریافت نرخ‌ها - با source_currency='usd'
        $rates = ProfitRate::where('admin_id', $adminId)
            ->where('source_currency', 'usd')
            ->latest()
            ->first();

        // اگر نرخ با source_currency='usd' نبود، آخرین نرخ را بگیر
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
                Log::info("USD مقدار: {$totalAmount} USD = {$totalAmount} USD");
            } else {
                // نرخ خرید نقدی این ارز به دلار
                $rateField = $currency . '_buy_cash';

                if ($rates && isset($rates->$rateField) && $rates->$rateField > 0) {
                    // فرمول: مقدار ارز ÷ نرخ خرید نقدی = مقدار دلار
                    $converted = $totalAmount / $rates->$rateField;
                    $totalUsd += $converted;

                    // لاگ برای دیباگ
                    Log::info("تبدیل {$currency}: {$totalAmount} ÷ {$rates->$rateField} = {$converted} USD");
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
            'safe_data' => $safe ? $safe->toArray() : null,
            'bank_data' => $bank ? $bank->toArray() : null,
            'rates_source' => $rates ? $rates->source_currency : 'none',
            'rates_afn_buy_cash' => $rates ? $rates->afn_buy_cash : null,
            'rates_eur_buy_cash' => $rates ? $rates->eur_buy_cash : null,
            'rates_irr_buy_cash' => $rates ? $rates->irr_buy_cash : null,
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

        // محاسبه دقیق موجودی کل با لاگ‌های بیشتر
        Log::info('Dashboard Render - موجودی‌ها:', [
            'safe_usd' => $this->safe->usd ?? 0,
            'safe_afn' => $this->safe->afn ?? 0,
            'safe_eur' => $this->safe->eur ?? 0,
            'safe_irr' => $this->safe->irr ?? 0,
            'bank_usd' => $this->safe_account['usd'] ?? 0,
            'bank_afn' => $this->safe_account['afn'] ?? 0,
            'bank_eur' => $this->safe_account['eur'] ?? 0,
            'bank_irr' => $this->safe_account['irr'] ?? 0,
        ]);

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
