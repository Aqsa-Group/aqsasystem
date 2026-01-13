<?php

namespace App\Livewire\Sarafi;

use App\Models\Sarafi\Customer;
use App\Models\Sarafi\Revenue;
use App\Models\Sarafi\Transaction;
use App\Models\Sarafi\WithdrawRevenue; // اضافه کردن این خط
use App\Models\Sarafi\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Morilog\Jalali\Jalalian;

class Revenues extends Component
{
    public $selectedAccount;
    public $customers = [];
    public $currencies = [];
    
    public $selectedCustomerId = null;
    public $date;
    public $clock;
    public $search = '';
    public $toAccount;
    public $zones = [];

    
    public $filteredCustomers = [];
    
    
    public $amount = '';
    public $description = '';
    public $currency = 'usd';

    public function render()
    {
        $timezone = 'Asia/Kabul';
        $today = Carbon::now($timezone)->startOfDay();
        $tomorrow = Carbon::now($timezone)->addDay()->startOfDay();

        $startOfWeek = Carbon::now($timezone)->startOfWeek();  
        $startOfMonth = Carbon::now($timezone)->startOfMonth(); 
        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        /*
        |--------------------------------------------------------------------------
        | امروز - فقط revenue بدون درنظرگیری برداشت‌ها
        |--------------------------------------------------------------------------
        */
        $todayprofit = Revenue::where('admin_id', $adminId)
            ->whereBetween('created_at', [$today, $tomorrow])
            ->sum('profit');

        $todaylost = Revenue::where('admin_id', $adminId)
            ->whereBetween('created_at', [$today, $tomorrow])
            ->sum('lost');

        $todayplus = $todayprofit - $todaylost;

        /*
        |--------------------------------------------------------------------------
        | این هفته - فقط revenue بدون درنظرگیری برداشت‌ها
        |--------------------------------------------------------------------------
        */
        $weekprofit = Revenue::where('admin_id', $adminId)
            ->where('created_at', '>=', $startOfWeek)
            ->sum('profit');

        $weeklost = Revenue::where('admin_id', $adminId)
            ->where('created_at', '>=', $startOfWeek)
            ->sum('lost');

        $weekplus = $weekprofit - $weeklost;

        /*
        |--------------------------------------------------------------------------
        | این ماه - فقط revenue بدون درنظرگیری برداشت‌ها
        |--------------------------------------------------------------------------
        */
        $monthprofit = Revenue::where('admin_id', $adminId)
            ->where('created_at', '>=', $startOfMonth)
            ->sum('profit');

        $monthlost = Revenue::where('admin_id', $adminId)
            ->where('created_at', '>=', $startOfMonth)
            ->sum('lost');

        $monthplus = $monthprofit - $monthlost;

        /*
        |--------------------------------------------------------------------------
        | مجموع کلی - با درنظرگیری برداشت‌ها
        |--------------------------------------------------------------------------
        */
        $totalRevenueProfit = Revenue::where('admin_id', $adminId)->sum('profit');
        $totalRevenueLost = Revenue::where('admin_id', $adminId)->sum('lost');
        $totalRevenuePlus = $totalRevenueProfit - $totalRevenueLost;
        
        // محاسبه مجموع برداشت‌ها
        $totalWithdraws = WithdrawRevenue::where('admin_id', $adminId)->sum('amount');
        
        $totalprofit = $totalRevenueProfit;
        $totallost = $totalRevenueLost;
        $totalplus = $totalRevenuePlus - $totalWithdraws;

        // دریافت لیست برداشت‌ها برای نمایش در جدول
        $withdraws = WithdrawRevenue::with('customer')
            ->when($this->selectedCustomerId, function($query) {
                $query->where('customer_id', $this->selectedCustomerId);
            })
            ->where('admin_id', $adminId)
            ->latest()
            ->get();

        return view('livewire.sarafi.revenues', [
            // امروز
            'todayprofit' => $todayprofit,
            'todaylost'   => $todaylost,
            'todayplus'   => $todayplus,

            // هفته
            'weekprofit' => $weekprofit,
            'weeklost'   => $weeklost,
            'weekplus'   => $weekplus,

            // ماه
            'monthprofit' => $monthprofit,
            'monthlost'   => $monthlost,
            'monthplus'   => $monthplus,

            // مجموع کلی
            'totalprofit' => $totalprofit,
            'totallost'   => $totallost,
            'totalplus'   => $totalplus,

            // برداشت‌ها
            'withdraws' => $withdraws,
        ]);
    }
public function mount()
{
    $this->date = Jalalian::now()->format('Y/m/d');
    $this->clock = now()->format('H:i:s');

    // تغییر این بخش
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

    $this->loadCustomers();
}

    private function loadCustomers()
    {
        $user = Auth::guard('sarafi')->user();
        if (!$user) {
            $this->customers = collect();
            return;
        }

        $adminId = $user->admin_id ?? $user->id;
        $relatedUserIds = User::where('admin_id', $adminId)
            ->pluck('id')
            ->push($adminId)
            ->toArray();

        $this->customers = Customer::select('id', 'account_number', 'fullname')
            ->where('admin_id', $adminId)
            ->orderBy('fullname')
            ->get();
    }

    public function updatedSearch($value)
    {
        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        if (empty($value)) {
            $this->selectedCustomerId = null;
            $this->filteredCustomers = [];
            return;
        }

        $this->filteredCustomers = Customer::where('admin_id', $adminId)
            ->where(function ($query) use ($value) {
                $query->where('fullname', 'like', "%{$value}%")
                    ->orWhere('account_number', 'like', "%{$value}%");
            })
            ->limit(15)
            ->get();

        if (count($this->filteredCustomers) === 1) {
            $this->selectCustomer($this->filteredCustomers[0]['id']);
        } else {
            $this->selectedCustomerId = null;
        }
    }

    public function selectCustomer($customerId)
    {
        $this->selectedCustomerId = $customerId;
        $this->selectedAccount = $customerId;

        $customer = Customer::find($customerId);
        if ($customer) {
            $this->search = $customer->fullname;
        }
    }

    /**
     * ثبت برداشت از سود
     */
    public function submitRemittance()
    {
        $this->validate([
            'selectedAccount' => 'required',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'description' => 'required|string|min:3',
        ]);

        try {
            $user = Auth::guard('sarafi')->user();
            $adminId = $user->admin_id ?? $user->id;

            // بررسی موجودی کافی
            $totalRevenueProfit = Revenue::where('admin_id', $adminId)->sum('profit');
            $totalRevenueLost = Revenue::where('admin_id', $adminId)->sum('lost');
            $totalRevenuePlus = $totalRevenueProfit - $totalRevenueLost;
            
            $totalWithdraws = WithdrawRevenue::where('admin_id', $adminId)->sum('amount');
            $availableBalance = $totalRevenuePlus - $totalWithdraws;

            if ($this->amount > $availableBalance) {
                session()->flash('error', 'موجودی کافی نیست! موجودی قابل برداشت: ' . number_format($availableBalance, 2) . ' دالر');
                return;
            }

            // ثبت برداشت
            WithdrawRevenue::create([
                'customer_id' => $this->selectedAccount,
                'user_id' => $user->id,
                'admin_id' => $adminId,
                'currency' => $this->currency,
                'amount' => $this->amount,
                'date' => $this->date,
                'description' => $this->description,
            ]);

               Transaction::create([
                'customer_id' => $this->selectedAccount,
                'user_id' => $user->id,
                'type' => 'رسید',
                'account_type' => 'نقدی',
                'admin_id' => $adminId,
                'currency' => $this->currency,
                'amount' => $this->amount,
                'date' => $this->date,
                'zone' => $user->zone,
                'by'=>$user->name,
                'description' => $this->description,
            ]);


            session()->flash('message', 'برداشت با موفقیت ثبت شد.');
            
            // ریست فیلدهای فرم
            $this->reset(['amount', 'description']);

        } catch (\Exception $e) {
            session()->flash('error', 'خطا در ثبت برداشت: ' . $e->getMessage());
        }
    }

    /**
     * حذف فیلتر
     */
    public function clearFilter()
    {
        $this->selectedCustomerId = null;
        $this->search = '';
        $this->filteredCustomers = [];
    }

    /**
     * پاک کردن جستجو و فیلتر
     */
    public function clearSearchAndFilter()
    {
        $this->clearFilter();
    }
}