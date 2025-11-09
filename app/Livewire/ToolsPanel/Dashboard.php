<?php

namespace App\Livewire\ToolsPanel;

use App\Models\Tools\Customer;
use App\Models\Tools\Inventories;
use App\Models\Tools\Loan;
use App\Models\Tools\SaleItem;
use App\Models\Tools\ShopSafe;
use App\Models\Tools\User;
use App\Models\Tools\Warehouses;
use App\Models\Tools\Withdrawals;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Models\Tools\Sale;

class Dashboard extends Component
{
    public array $months;
    public array $profitPerMonth;
    public array $lossPerMonth;

    public int $salesToday;
    public int $profitToday;
    public int $lossToday;
    public int $customersTotal;
    public int $transactionsToday;
    public int $balanceTotal;

    public function mount()
    {
        $this->months = ['حمل', 'ثور', 'جوزا', 'سرطان', 'اسد', 'سنبله', 'میزان', 'عقرب', 'قوس', 'جدی', 'دلو', 'حوت'];
        
        $this->getRealChartData();
        
        $this->salesToday = rand(80000, 160000);
        $this->profitToday = rand(5000, 45000);
        $this->lossToday = rand(0, 15000);
        $this->customersTotal = rand(80, 240);
        $this->transactionsToday = rand(10, 120);
        $this->balanceTotal = rand(2000000, 9000000);
    }

    private function getRealChartData()
    {
        $user = Auth::guard('tools')->user();
        $adminId = $user->admin_id ?? $user->id;
        $timezone = 'Asia/Kabul';
        $currentYear = Carbon::now($timezone)->year;

        $this->profitPerMonth = [];
        $this->lossPerMonth = [];

        // محاسبه سود و ضرر برای هر ماه
        for ($month = 1; $month <= 12; $month++) {
            $monthStart = Carbon::create($currentYear, $month, 1, 0, 0, 0, $timezone)->startOfMonth();
            $monthEnd = Carbon::create($currentYear, $month, 1, 0, 0, 0, $timezone)->endOfMonth();

            // محاسبه سود ماهانه از مدل Sale
            $monthlyProfit = Sale::where('admin_id', $adminId)
                ->whereBetween('created_at', [$monthStart, $monthEnd])
                ->sum('final_profit');
            
            $this->profitPerMonth[] = (int)$monthlyProfit;

            // محاسبه ضرر ماهانه از مدل SaleItem
            $monthlyLoss = SaleItem::whereHas('sale', function($query) use ($adminId) {
                    $query->where('admin_id', $adminId);
                })
                ->whereBetween('created_at', [$monthStart, $monthEnd])
                ->sum('loss');
            
            $this->lossPerMonth[] = (int)$monthlyLoss;
        }
    }

    public function render()
    {
        $user = Auth::guard('tools')->user();
        $adminId = $user->admin_id ?? $user->id;
        $timezone = 'Asia/Kabul';

        $today = Carbon::now($timezone)->startOfDay();
        $tomorrow = Carbon::now($timezone)->addDay()->startOfDay();

        $monthStart = Carbon::now($timezone)->startOfMonth();
        $monthEnd = Carbon::now($timezone)->endOfMonth();

        $customerCount = Customer::where('admin_id', $adminId)->count();
        $UserCount = User::where('admin_id', $adminId)->count();
        $LoanUsdcome = Loan::where('admin_id', $adminId)->where('currency', 'usd')->where('type', 'رسید')->sum('amount');
        $LoanUsdwithdraw = Loan::where('admin_id', $adminId)->where('currency', 'usd')->where('type', 'برد')->sum('amount');
        $totalUsdLoan = $LoanUsdwithdraw - $LoanUsdcome;

        $LoanAFNcome = Loan::where('admin_id', $adminId)->where('currency', 'afn')->where('type', 'رسید')->sum('amount');
        $LoanAFNwithdraw = Loan::where('admin_id', $adminId)->where('currency', 'afn')->where('type', 'برد')->sum('amount');
        $totalAFNLoan =  $LoanAFNwithdraw  - $LoanAFNcome;
        $Inventorytotalprice = Inventories::where('admin_id', $adminId)->sum('total_purchase_amount');
        $Warehousetotalprice = Warehouses::where('user_id', $user->id)->sum('total_purchase_amount');
        $shopsafeafn = ShopSafe::where('admin_id', $adminId)->sum('afn');
        $TotalStock = $Inventorytotalprice + $Warehousetotalprice +  $shopsafeafn;

        $Withdrawals = Withdrawals::where('admin_id', $adminId)
            ->where('currency', 'afn')
            ->whereBetween('created_at', [$today, $tomorrow])
            ->sum('amount');

        $Todayprofit = Sale::where('admin_id', $adminId)
            ->whereBetween('created_at', [$today, $tomorrow])
            ->sum('final_profit');

        $Todaysale = Sale::where('admin_id', $adminId)
            ->whereBetween('created_at', [$today, $tomorrow])
            ->sum('total_price');

       $ThisMonthSale = Sale::where('admin_id', $adminId)
            ->whereBetween('created_at', [$monthStart, $monthEnd])
            ->sum('total_price');

        return view('livewire.tools-panel.dashboard', [
            'countcustomer' => $customerCount,
            'usercount' => $UserCount,
            'totalUsdLoan' => $totalUsdLoan,
            'totalAFNLoan' => $totalAFNLoan,
            'inventorytotalprice' => $Inventorytotalprice,
            'warehousetotalprice' => $Warehousetotalprice,
            'totalstock' => $TotalStock,
            'withdrawals' => $Withdrawals,
            'todayprofit' => $Todayprofit,
            'todaysale' => $Todaysale,
            'thismonthsale' => $ThisMonthSale,
        ]);
    }
}