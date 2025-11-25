<?php

namespace App\Livewire\Sarafi;

use App\Models\Sarafi\Customer;
use App\Models\Sarafi\ExchangeRates;
use App\Models\Sarafi\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Morilog\Jalali\Jalalian;

class GeneralReports extends Component
{
    public $selectedCategory = null;
    public $selectedSubCategory = null;
    public $search = '';
    public $selectedCustomer = '';
    public $selectedCurrency = '';
    public $date;
    public $customer_id;
    public $selectedAccount;
    public $customers;
    public $selectedCustomerBalance = [];
    public $currencyPercentages = [];
    public $currencyName = [];

    public $selectedCustomerId = null;
    public $filteredCustomers = [];
    public $reports = [];

    public $subCategories = [
        'customers' => ['گزارش بیلانس مشتریان', 'گزارش خلاصه بیلانس مشتریان', 'صورتحساب‌ها'],
        'accounts' => ['گزارش صندوق', 'حساب‌های بانکی', 'ترازنامه'],
        'transactions' => ['معاملات خرید', 'معاملات فروش', 'تراکنش‌ها'],
        'management' => ['گزارش مدیریتی', 'تحلیل فروش', 'نمودارها']
    ];

    
    public $selectedAccounts = [];
    public $selectedCustomersData = [];
    public $totalBalances = [];
    public $chartData = [];
    public $maxValue = 0;
    public $colors = [];
    public $lightColors = [];

    public $currencies = [
        'usd' => 'دالر',
        'afn' => 'افغانی',
        'irr' => 'تومان',
        'eur' => 'یورو',
        'pkr' => 'کلدار',
        'aed' => 'درهم',
        'try' => 'لیره',
        'cny' => 'یوان'
    ];

    public function mount()
    {
        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;
        $this->date = Jalalian::now()->format('Y/m/d');

        $this->selectedCategory = 'customers';
        $this->selectedSubCategory = 'گزارش بیلانس مشتریان';

        $this->generateCustomerBalanceReport();

        $relatedUserIds = \App\Models\Sarafi\User::where('admin_id', $adminId)
            ->pluck('id')
            ->push($adminId)
            ->toArray();

        $this->customers = Customer::select('id', 'account_number', 'fullname')
            ->where(function ($query) use ($adminId, $relatedUserIds) {
                $query->where('admin_id', $adminId)
                    ->orWhereHas('transactions', function ($t) use ($relatedUserIds) {
                        $t->whereIn('user_id', $relatedUserIds)
                            ->orWhereIn('admin_id', $relatedUserIds);
                    });
            })
            ->orderBy('fullname')
            ->get();

        $this->customers = collect($this->customers);

        // تعریف رنگ‌ها
        $this->colors = [
            'usd' => '#DD2424',
            'afn' => '#2563EB', 
            'irr' => '#61B138',
            'eur' => '#F59E0B',
            'pkr' => '#8B5CF6',
            'aed' => '#EC4899',
            'try' => '#06B6D4',
            'cny' => '#84CC16',
        ];
        
        $this->lightColors = [
            'usd' => '#FF6B6B',
            'afn' => '#60A5FA', 
            'irr' => '#86EFAC',
            'eur' => '#FCD34D',
            'pkr' => '#C4B5FD',
            'aed' => '#F9A8D4',
            'try' => '#67E8F9',
            'cny' => '#BEF264',
        ];
    }

    // متد برای آپدیت انتخاب‌ها
    public function updatedSelectedAccounts($value)
    {
        $this->calculateSelectedCustomersBalance();
    }

  private function calculateSelectedCustomersBalance()
{
    $this->selectedCustomersData = [];
    $this->totalBalances = [];
    $this->chartData = [];
    $this->maxValue = 0;

    if (empty($this->selectedAccounts)) {
        // حتی اگر مشتری انتخاب نشده، همه ارزها با صفر نمایش داده شوند
        foreach ($this->currencies as $currencyCode => $currencyName) {
            $this->totalBalances[$currencyCode] = [
                'total' => 0,
                'currency_name' => $currencyName,
                'color' => $this->getCurrencyColor($currencyCode)
            ];

            $this->chartData[] = [
                'currency' => $currencyName,
                'currency_code' => $currencyCode,
                'value' => 0,
                'color' => $this->getCurrencyColor($currencyCode),
                'light_color' => $this->lightColors[$currencyCode] ?? '#ffffff'
            ];
        }
        return;
    }

    $currencyTotals = [];

    // مقداردهی اولیه همه ارزها با صفر
    foreach ($this->currencies as $currencyCode => $currencyName) {
        $currencyTotals[$currencyCode] = 0;
    }

    foreach ($this->selectedAccounts as $customerId) {
        $customer = Customer::find($customerId);
        if (!$customer) continue;

        $customerData = [
            'id' => $customer->id,
            'name' => $customer->fullname,
            'account_number' => $customer->account_number,
            'balances' => []
        ];

        foreach ($this->currencies as $currencyCode => $currencyName) {
            $balance = $this->calculateBalance($customerId, $currencyCode);
            
            $customerData['balances'][$currencyCode] = [
                'balance' => $balance,
                'currency_name' => $currencyName
            ];

            $currencyTotals[$currencyCode] += $balance;
            
            // آپدیت ماکسیمم مقدار برای نمودار
            if ($balance > $this->maxValue) {
                $this->maxValue = $balance;
            }
        }

        $this->selectedCustomersData[] = $customerData;
    }

    // آماده‌سازی داده‌های کارت‌ها و نمودار برای همه ارزها
    foreach ($this->currencies as $currencyCode => $currencyName) {
        $total = $currencyTotals[$currencyCode] ?? 0;
        
        $this->totalBalances[$currencyCode] = [
            'total' => $total,
            'currency_name' => $currencyName,
            'color' => $this->getCurrencyColor($currencyCode)
        ];

        // داده‌های نمودار
        $this->chartData[] = [
            'currency' => $currencyName,
            'currency_code' => $currencyCode,
            'value' => $total,
            'color' => $this->getCurrencyColor($currencyCode),
            'light_color' => $this->lightColors[$currencyCode] ?? '#ffffff'
        ];
    }

    if ($this->maxValue === 0) {
        $this->maxValue = 1; 
    }
}

    public function processSelectedCustomers()
    {
        foreach ($this->selectedAccounts as $customerId) {
            
        }
    }

    public function selectCustomer($customerId)
    {
        $this->selectedCustomerId = $customerId;
        $this->selectedAccount = $customerId;
        $this->filteredCustomers = [];

        $customer = Customer::find($customerId);
        if ($customer) {
            $this->search = $customer->fullname;

            if (!$this->customers->contains('id', $customer->id)) {
                $this->customers->push($customer);
            }

            // محاسبه موجودی و درصدها برای مشتری انتخاب شده
            $this->calculateCustomerBalance($customerId);
            
            Log::debug("Customer selected", [
                'customer_id' => $customerId,
                'customer_name' => $customer->fullname,
                'search_value' => $this->search
            ]);
        } else {
            $this->selectedCustomerBalance = [];
            $this->currencyPercentages = [];
            $this->currencyName =[];
        }
    }

    private function calculateCustomerBalance($customerId)
    {
        $this->selectedCustomerBalance = [];
        $this->currencyPercentages = [];
        
        $totalBalanceUSD = 0;
        
        // محاسبه موجودی هر ارز
        foreach ($this->currencies as $currencyCode => $currencyName) {
            $balance = $this->calculateBalance($customerId, $currencyCode);
            if ($balance != 0) {
                $balanceUSD = $this->convertToUSD($balance, $currencyCode);
                $this->selectedCustomerBalance[$currencyCode] = [
                    'balance' => $balance,
                    'balance_usd' => $balanceUSD,
                    'currency_name' => $currencyName
                ];
                $totalBalanceUSD += $balanceUSD;
            }
        }
        
        // محاسبه درصدها
        if ($totalBalanceUSD > 0) {
            foreach ($this->selectedCustomerBalance as $currencyCode => $data) {
                $percentage = ($data['balance_usd'] / $totalBalanceUSD) * 100;
                $this->currencyPercentages[$currencyCode] = [
                    'percentage' => round($percentage, 1),
                    'balance' => $data['balance'],
                    'currency_name' => $data['currency_name'],
                    'color' => $this->getCurrencyColor($currencyCode)
                ];
            }
        }
    }

    private function convertToUSD($amount, $currency)
    {
        $latestExchangeRate = ExchangeRates::latest()->first();
        if (!$latestExchangeRate) {
            return 0;
        }

        $exchangeRates = [
            'afn' => $latestExchangeRate->afn_buy ?? 0.011,
            'usd' => 1,
            'irr' => $latestExchangeRate->irr_buy ?? 0.000024,
            'eur' => $latestExchangeRate->eur_buy ?? 1.07,
            'pkr' => $latestExchangeRate->pkr_buy ?? 0.0036,
            'aed' => $latestExchangeRate->aed_buy ?? 0.27,
            'try' => $latestExchangeRate->try_buy ?? 0.031,
            'cny' => $latestExchangeRate->cny_buy ?? 0.14,
        ];

        return isset($exchangeRates[$currency]) ? $amount / $exchangeRates[$currency] : 0;
    }

    private function getCurrencyColor($currency)
    {
        return $this->colors[$currency] ?? '#6B7280';
    }

    /**
     * روشن کردن رنگ
     */
    private function lightenColor($color, $percent)
    {
        $color = ltrim($color, '#');
        $rgb = sscanf($color, "%02x%02x%02x");
        
        $r = min(255, $rgb[0] + (255 - $rgb[0]) * $percent / 100);
        $g = min(255, $rgb[1] + (255 - $rgb[1]) * $percent / 100);
        $b = min(255, $rgb[2] + (255 - $rgb[2]) * $percent / 100);
        
        return sprintf("#%02x%02x%02x", $r, $g, $b);
    }

    /**
     * تیره کردن رنگ
     */
    private function darkenColor($color, $percent)
    {
        $color = ltrim($color, '#');
        $rgb = sscanf($color, "%02x%02x%02x");
        
        $r = max(0, $rgb[0] * (1 - $percent / 100));
        $g = max(0, $rgb[1] * (1 - $percent / 100));
        $b = max(0, $rgb[2] * (1 - $percent / 100));
        
        return sprintf("#%02x%02x%02x", $r, $g, $b);
    }

    public function updatedSelectedAccount($value)
    {
        if ($value) {
            $this->selectCustomer($value);
        }
    }

    public function selectCategory($category)
    {
        $this->selectedCategory = $category;
        $this->selectedSubCategory = null;
        $this->reports = [];
        $this->selectedCustomerId = null;
        $this->selectedCustomerBalance = [];
        $this->currencyPercentages = [];
        $this->selectedAccounts = [];
        $this->selectedCustomersData = [];
        $this->totalBalances = [];
        $this->chartData = [];
        $this->maxValue = 0;
    }

    public function updatedSelectedSubCategory($sub)
    {
        $this->selectedSubCategory = $sub;

        if ($sub === 'گزارش بیلانس مشتریان') {
            $this->generateCustomerBalanceReport();
        } else {
            $this->reports = [];
            $this->selectedCustomerId = null;
            $this->selectedCustomerBalance = [];
            $this->currencyPercentages = [];
            $this->selectedAccounts = [];
            $this->selectedCustomersData = [];
            $this->totalBalances = [];
            $this->chartData = [];
            $this->maxValue = 0;
        }
    }

    private function generateCustomerBalanceReport()
    {
        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        $baseQuery = Customer::where('admin_id', $adminId);

        if ($this->selectedCustomer) {
            $baseQuery->where('related_customer_id', $this->selectedCustomer);
        }

        if ($this->search) {
            $baseQuery->where(function ($query) {
                $query->where('fullname', 'like', '%' . $this->search . '%')
                    ->orWhere('account_number', 'like', '%' . $this->search . '%');
            });
        }

        $customers = $baseQuery->get();

        $this->reports = [];
        foreach ($customers as $customer) {
            $report = [
                'id' => $customer->id,
                'account_number' => $customer->account_number,
                'fullname' => $customer->fullname,
                'related_customer_name' => $this->getRelatedCustomerName($customer->related_customer_id),
                'last_date' => null,
                'balances' => [],
                'total_balance' => 0,
                'has_balance' => false
            ];

            foreach ($this->currencies as $currencyCode => $currencyName) {
                $balance = $this->calculateBalance($customer->id, $currencyCode);
                $report['balances'][$currencyCode] = $balance;

                if ($balance != 0 && !$report['last_date']) {
                    $report['last_date'] = $this->getLastTransactionDate($customer->id, $currencyCode);
                    $report['has_balance'] = true;
                }
            }

            $report['total_balance'] = $this->calculateTotalBalance($report['balances']);

            if ($report['has_balance']) {
                $this->reports[] = $report;
            }
        }

        if ($this->selectedCurrency) {
            $this->reports = array_filter($this->reports, function ($report) {
                return ($report['balances'][$this->selectedCurrency] ?? 0) != 0;
            });
        }
    }

    private function getRelatedCustomerName($relatedCustomerId)
    {
        if (!$relatedCustomerId) return null;
        $customer = Customer::find($relatedCustomerId);
        return $customer ? $customer->fullname : 'نامشخص';
    }

    private function calculateBalance($customerId, $currency)
    {
        return Transaction::where('customer_id', $customerId)
            ->where('currency', $currency)
            ->select(DB::raw('SUM(CASE WHEN type = "رسید" THEN amount ELSE -amount END) as balance'))
            ->value('balance') ?? 0;
    }

    private function getLastTransactionDate($customerId, $currency)
    {
        return Transaction::where('customer_id', $customerId)
            ->where('currency', $currency)
            ->max('date');
    }

    private function calculateTotalBalance($balances)
    {
        $latestExchangeRate = ExchangeRates::latest()->first();
        if (!$latestExchangeRate) {
            return 0;
        }

        $exchangeRates = [
            'afn' => $latestExchangeRate->afn_buy ?? 0.011,
            'usd' => 1,
            'irr' => $latestExchangeRate->irr_buy ?? 0.000024,
            'eur' => $latestExchangeRate->eur_buy ?? 1.07,
            'pkr' => $latestExchangeRate->pkr_buy ?? 0.0036,
            'aed' => $latestExchangeRate->aed_buy ?? 0.27,
            'try' => $latestExchangeRate->try_buy ?? 0.031,
            'cny' => $latestExchangeRate->cny_buy ?? 0.14,
        ];

        $total = 0;
        foreach ($balances as $currency => $balance) {
            if (isset($exchangeRates[$currency]) && $balance != 0) {
                $total += $balance  / $exchangeRates[$currency];
            }
        }
        return $total;
    }

    public function render()
    {
        return view('livewire.sarafi.general-reports', [
            'customers' => $this->customers,
        ]);
    }
}