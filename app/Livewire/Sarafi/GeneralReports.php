<?php

namespace App\Livewire\Sarafi;

use App\Models\Sarafi\Customer;
use App\Models\Sarafi\ProfitRate;
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
    public $demands = [];

    public $colors = [];
    public $lightColors = [];

    // متغیرهای جدید برای گزارش خلاصه
    public $selectedAccounts = [];
    public $totalBalances = [];
    public $chartData = [];
    public $maxValue = 0;
    public $selectedCustomersData = [];

    public $subCategories = [
        'customers' => ['گزارش بیلانس مشتریان', 'گزارش خلاصه بیلانس مشتریان', 'طلب مشتری ها'],
        'accounts' => ['گزارش صندوق', 'حساب‌های بانکی', 'ترازنامه'],
        'transactions' => ['معاملات خرید', 'معاملات فروش', 'تراکنش‌ها'],
        'management' => ['گزارش مدیریتی', 'تحلیل فروش', 'نمودارها']
    ];

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
            $this->currencyName = [];
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

            // فقط اگر موجودی غیر صفر باشد، محاسبه کن
            if ($balance != 0) {
                $balanceUSD = $this->convertToUSD($balance, $currencyCode);
                $this->selectedCustomerBalance[$currencyCode] = [
                    'balance' => $balance,
                    'balance_usd' => $balanceUSD,
                    'currency_name' => $currencyName
                ];
                $totalBalanceUSD += $balanceUSD;

                Log::debug("Balance calculated for {$currencyCode}", [
                    'balance' => $balance,
                    'balance_usd' => $balanceUSD,
                    'total_usd' => $totalBalanceUSD
                ]);
            }
        }

        // محاسبه درصدها فقط اگر مجموع بیشتر از صفر باشد
        if ($totalBalanceUSD > 0) {
            foreach ($this->selectedCustomerBalance as $currencyCode => $data) {
                $percentage = ($data['balance_usd'] / $totalBalanceUSD) * 100;
                $this->currencyPercentages[$currencyCode] = [
                    'percentage' => round($percentage, 2),
                    'balance' => $data['balance'],
                    'currency_name' => $data['currency_name'],
                    'color' => $this->getCurrencyColor($currencyCode)
                ];

                Log::debug("Percentage calculated for {$currencyCode}", [
                    'balance_usd' => $data['balance_usd'],
                    'total_usd' => $totalBalanceUSD,
                    'percentage' => $percentage
                ]);
            }
        } else {
            Log::debug("No balance found for customer", ['customer_id' => $customerId]);
        }

        Log::debug("Final percentages", [
            'currencyPercentages' => $this->currencyPercentages,
            'totalBalanceUSD' => $totalBalanceUSD
        ]);
    }

    private function convertToUSD($amount, $currency)
    {
        if ($currency === 'usd') {
            return $amount;
        }

        $latestProfitRate = ProfitRate::latest()->first();
        if (!$latestProfitRate) {
            Log::warning('No profit rate found');
            return 0;
        }

        // نرخ‌های تبدیل - استفاده از نرخ خرید نقدی (پیش‌فرض)
        $exchangeRates = [
            'afn' => $latestProfitRate->afn_buy_cash ?? 66.20,
            'irr' => $latestProfitRate->irr_buy_cash ?? 110000.00,
            'eur' => $latestProfitRate->eur_buy_cash ?? 0.93,
            'pkr' => $latestProfitRate->pkr_buy_cash ?? 277.78,
            'aed' => $latestProfitRate->aed_buy_cash ?? 3.67,
            'try' => $latestProfitRate->try_buy_cash ?? 32.26,
            'cny' => $latestProfitRate->cny_buy_cash ?? 7.24,
        ];

        $result = isset($exchangeRates[$currency]) ? $amount / $exchangeRates[$currency] : 0;

        Log::debug("Currency conversion", [
            'amount' => $amount,
            'currency' => $currency,
            'rate' => $exchangeRates[$currency] ?? 'N/A',
            'result' => $result
        ]);

        return $result;
    }

    public function getCurrencyColor($currency)
    {
        $colors = [
            'usd' => '#DD2424',
            'afn' => '#2563EB',
            'irr' => '#61B138',
            'eur' => '#F59E0B',
            'pkr' => '#8B5CF6',
            'aed' => '#EC4899',
            'try' => '#06B6D4',
            'cny' => '#84CC16',
        ];

        return $colors[$currency] ?? '#6B7280';
    }

    public function getCurrencyGradient($currency)
    {
        $gradients = [
            'usd' => ['#FF6B6B', '#DC2626'],
            'afn' => ['#60A5FA', '#1D4ED8'],
            'irr' => ['#86EFAC', '#16A34A'],
            'eur' => ['#FCD34D', '#D97706'],
            'pkr' => ['#A78BFA', '#7C3AED'],
            'aed' => ['#F9A8D4', '#DB2777'],
            'try' => ['#67E8F9', '#0891B2'],
            'cny' => ['#A3E635', '#65A30D'],
        ];

        return $gradients[$currency] ?? ['#9CA3AF', '#4B5563'];
    }

    /**
     * روشن کردن رنگ
     */
    public function lightenColor($color, $percent)
    {
        $color = ltrim($color, '#');
        if (strlen($color) == 3) {
            $color = $color[0] . $color[0] . $color[1] . $color[1] . $color[2] . $color[2];
        }
        $rgb = sscanf($color, "%02x%02x%02x");

        $r = min(255, $rgb[0] + (255 - $rgb[0]) * $percent / 100);
        $g = min(255, $rgb[1] + (255 - $rgb[1]) * $percent / 100);
        $b = min(255, $rgb[2] + (255 - $rgb[2]) * $percent / 100);

        return sprintf("#%02x%02x%02x", $r, $g, $b);
    }

    /**
     * تیره کردن رنگ
     */
    public function darkenColor($color, $percent)
    {
        $color = ltrim($color, '#');
        if (strlen($color) == 3) {
            $color = $color[0] . $color[0] . $color[1] . $color[1] . $color[2] . $color[2];
        }
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
        $this->demands = [];

        $this->selectedCustomerId = null;
        $this->selectedCustomerBalance = [];
        $this->currencyPercentages = [];
        $this->selectedAccounts = [];
        $this->selectedCustomersData = [];
    }

    public function updatedSelectedSubCategory($sub)
    {
        $this->selectedSubCategory = $sub;

        if ($sub === 'گزارش بیلانس مشتریان') {
            $this->generateCustomerBalanceReport();
        } elseif ($sub === 'گزارش خلاصه بیلانس مشتریان') {
            $this->generateSummaryReport();
        } elseif ($sub === 'طلب مشتری ها') {
            $this->generateCustomerBalanceReport();
        } else {
            $this->reports = [];
            $this->demands = [];
            $this->selectedCustomerId = null;
            $this->selectedCustomerBalance = [];
            $this->currencyPercentages = [];
        }
    }

    // متد جدید برای تولید گزارش خلاصه
    public function generateSummaryReport()
    {
        $this->totalBalances = [];
        $this->chartData = [];
        $this->maxValue = 0;
        $this->selectedCustomersData = [];

        if (empty($this->selectedAccounts)) {
            return;
        }

        // محاسبه مجموع موجودی‌ها برای هر ارز
        foreach ($this->currencies as $currencyCode => $currencyName) {
            $total = 0;
            foreach ($this->selectedAccounts as $customerId) {
                $balance = $this->calculateBalance($customerId, $currencyCode);
                $total += $balance;
            }

            $this->totalBalances[$currencyCode] = [
                'total' => $total,
                'currency_name' => $currencyName
            ];

            $this->chartData[] = [
                'currency' => strtoupper($currencyName),
                'value' => abs($total)
            ];

            $this->maxValue = max($this->maxValue, abs($total));
        }

        // آماده‌سازی داده‌های مشتریان انتخاب شده
        foreach ($this->selectedAccounts as $customerId) {
            $customer = Customer::find($customerId);
            if ($customer) {
                $customerBalances = [];
                foreach ($this->currencies as $currencyCode => $currencyName) {
                    $balance = $this->calculateBalance($customerId, $currencyCode);
                    if (abs($balance) > 0.001) {
                        $customerBalances[$currencyCode] = [
                            'balance' => $balance,
                            'currency_name' => $currencyName
                        ];
                    }
                }

                $this->selectedCustomersData[] = [
                    'id' => $customer->id,
                    'name' => $customer->fullname,
                    'account_number' => $customer->account_number,
                    'balances' => $customerBalances
                ];
            }
        }
    }

    // متد برای پاسخ به تغییرات selectedAccounts
    public function updatedSelectedAccounts($value)
    {
        $this->generateSummaryReport();
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

        $this->demands = [];

        foreach ($customers as $customer) {
            $demand = [
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
                $balance = $balance > 0 ? $balance : 0;

                $demand['balances'][$currencyCode] = $balance;

                if ($balance != 0 && !$demand['last_date']) {
                    $demand['last_date'] = $this->getLastTransactionDate($customer->id, $currencyCode);
                    $demand['has_balance'] = true;
                }
            }

            $demand['total_balance'] = $this->calculateTotalBalance($demand['balances']);

            if ($demand['has_balance']) {
                $this->demands[] = $demand;
            }
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
            ->select(DB::raw('
                SUM(
                    CASE 
                        WHEN type = "رسید" THEN amount 
                        ELSE -amount 
                    END
                ) as balance
            '))
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
        $latestProfitRate = ProfitRate::latest()->first();
        if (!$latestProfitRate) {
            return 0;
        }

        // استفاده از نرخ خرید نقدی (cash) به عنوان پیش‌فرض
        $exchangeRates = [
            'afn' => $latestProfitRate->afn_buy_cash ?? 66.20,
            'usd' => 1,
            'irr' => $latestProfitRate->irr_buy_cash ?? 110000.00,
            'eur' => $latestProfitRate->eur_buy_cash ?? 0.93,
            'pkr' => $latestProfitRate->pkr_buy_cash ?? 277.78,
            'aed' => $latestProfitRate->aed_buy_cash ?? 3.67,
            'try' => $latestProfitRate->try_buy_cash ?? 32.26,
            'cny' => $latestProfitRate->cny_buy_cash ?? 7.24,
        ];

        $total = 0;
        foreach ($balances as $currency => $balance) {
            if (isset($exchangeRates[$currency]) && $exchangeRates[$currency] > 0 && $balance != 0) {
                $total += $balance / $exchangeRates[$currency];
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