<?php

namespace App\Livewire\Sarafi;

use App\Models\Sarafi\Customer;
use App\Models\Sarafi\ProfitRate;
use App\Models\Sarafi\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Morilog\Jalali\Jalalian;
use Mpdf\Mpdf;

class AccountReports extends Component
{
    public $search = '';
    public $selectedCustomer = '';
    public $selectedCurrency = '';
    public $accountType = '';
    public $date;

    public $customers = [];
    public $reports = [];

    protected $currencies = [
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
        $this->loadCustomers();
        $this->generateReport();
        $this->date = Jalalian::now()->format('Y/m/d');
    }

    private function loadCustomers()
    {
        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        $this->customers = Customer::where('admin_id', $adminId)
            ->whereIn('id', function ($query) use ($adminId) {
                $query->select('related_customer_id')
                    ->from('customers')
                    ->where('admin_id', $adminId)
                    ->whereNotNull('related_customer_id');
            })
            ->orderBy('fullname')
            ->get(['id', 'fullname', 'account_number'])
            ->toArray();
    }

    public function generateReport()
    {
        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        if ($this->selectedCustomer) {
            $baseQuery = Customer::where('admin_id', $adminId)
                ->where('related_customer_id', $this->selectedCustomer);
        } else {
            $baseQuery = Customer::where('admin_id', $adminId);
        }

        if ($this->search) {
            $baseQuery->where(function ($query) {
                $query->where('fullname', 'like', '%' . $this->search . '%')
                    ->orWhere('account_number', 'like', '%' . $this->search . '%');
            });
        }

        $customers = $baseQuery->get();
        $this->reports = [];

        // تعیین نوع حساب برای تبدیل
        $accountTypeForConversion = $this->getAccountTypeForConversion();

        foreach ($customers as $customer) {
            $report = [
                'id' => $customer->id,
                'account_number' => $customer->account_number,
                'fullname' => $customer->fullname,
                'related_customer_id' => $customer->related_customer_id,
                'related_customer_name' => $this->getRelatedCustomerName($customer->related_customer_id),
                'last_date' => null,
                'balances' => [],
                'total_balance' => 0,
                'has_balance' => false
            ];

            // محاسبه موجودی برای هر ارز
            foreach ($this->currencies as $currencyCode => $currencyName) {
                $balance = $this->calculateBalance($customer->id, $currencyCode);
                $report['balances'][$currencyCode] = $balance;

                if ($balance != 0 && !$report['last_date']) {
                    $report['last_date'] = $this->getLastTransactionDate($customer->id, $currencyCode);
                    $report['has_balance'] = true;
                }
            }

            // محاسبه مجموع موجودی به دالر
            $report['total_balance'] = $this->calculateTotalBalance($report['balances'], $accountTypeForConversion);

            // فقط مشتریانی که موجودی دارند نمایش داده شوند
            if ($report['has_balance']) {
                $this->reports[] = $report;
            }
        }

        // فیلتر بر اساس ارز انتخاب شده
        if ($this->selectedCurrency) {
            $this->reports = array_filter($this->reports, function ($report) {
                return ($report['balances'][$this->selectedCurrency] ?? 0) != 0;
            });
        }

        // فیلتر بر اساس تاریخ
        if ($this->date) {
            $this->filterByDate();
        }

        // مرتب سازی بر اساس مجموع موجودی (بزرگ به کوچک)
        usort($this->reports, function ($a, $b) {
            return $b['total_balance'] <=> $a['total_balance'];
        });
    }

    private function getAccountTypeForConversion()
    {
        if ($this->accountType == 'بانکی') {
            return 'bank';
        } elseif ($this->accountType == 'نقدی') {
            return 'cash';
        }
        return 'cash'; // پیش‌فرض
    }

    private function getRelatedCustomerName($relatedCustomerId)
    {
        if (!$relatedCustomerId) return null;

        $relatedCustomer = Customer::find($relatedCustomerId);
        return $relatedCustomer ? $relatedCustomer->fullname : 'نامشخص';
    }

    private function calculateBalance($customerId, $currency)
    {
        $query = Transaction::where('customer_id', $customerId)
            ->where('currency', $currency);

        if ($this->accountType) {
            $query->where('account_type', $this->accountType);
        }

        return $query->select(DB::raw('SUM(CASE WHEN type = "رسید" THEN amount ELSE -amount END) as balance'))
            ->value('balance') ?? 0;
    }

    private function getLastTransactionDate($customerId, $currency)
    {
        $query = Transaction::where('customer_id', $customerId)
            ->where('currency', $currency);

        if ($this->accountType) {
            $query->where('account_type', $this->accountType);
        }

        return $query->max('date');
    }

    private function calculateTotalBalance($balances, $accountType = 'cash')
    {
        $latestProfitRate = ProfitRate::latest()->first();

        // اگر هیچ رکوردی در جدول profit_rate نبود، از مقادیر پیش‌فرض ثابت استفاده می‌کنیم.
        if (!$latestProfitRate) {
            // مقادیر پیش‌فرض برای نرخ خرید نقدی (بر اساس نرخ‌های واقعی)
            $exchangeRates = [
                'afn' => 66.20,     // 1 USD = 66.20 AFN (خرید نقدی)
                'usd' => 1,         // 1 USD = 1 USD
                'irr' => 110000.00, // 1 USD = 110,000 IRR (خرید نقدی)
                'eur' => 0.93,      // 1 USD = 0.93 EUR (خرید نقدی)
                'pkr' => 277.78,    // 1 USD = 277.78 PKR (خرید نقدی)
                'aed' => 3.67,      // 1 USD = 3.67 AED (خرید نقدی)
                'try' => 32.26,     // 1 USD = 32.26 TRY (خرید نقدی)
                'cny' => 7.24,      // 1 USD = 7.24 CNY (خرید نقدی)
            ];
        } else {
            if ($accountType == 'cash') {
                // نرخ‌های خرید نقدی
                $exchangeRates = [
                    'afn' => $latestProfitRate->afn_buy_cash ?: 66.20,
                    'usd' => $latestProfitRate->usd_buy_cash ?: 1,
                    'irr' => $latestProfitRate->irr_buy_cash ?: 110000.00,
                    'eur' => $latestProfitRate->eur_buy_cash ?: 0.93,
                    'pkr' => $latestProfitRate->pkr_buy_cash ?: 277.78,
                    'aed' => $latestProfitRate->aed_buy_cash ?: 3.67,
                    'try' => $latestProfitRate->try_buy_cash ?: 32.26,
                    'cny' => $latestProfitRate->cny_buy_cash ?: 7.24,
                ];
            } else {
                // نرخ‌های خرید بانکی
                $exchangeRates = [
                    'afn' => $latestProfitRate->afn_buy_bank ?: 66.20,
                    'usd' => $latestProfitRate->usd_buy_bank ?: 1,
                    'irr' => $latestProfitRate->irr_buy_bank ?: 110000.00,
                    'eur' => $latestProfitRate->eur_buy_bank ?: 0.93,
                    'pkr' => $latestProfitRate->pkr_buy_bank ?: 277.78,
                    'aed' => $latestProfitRate->aed_buy_bank ?: 3.67,
                    'try' => $latestProfitRate->try_buy_bank ?: 32.26,
                    'cny' => $latestProfitRate->cny_buy_bank ?: 7.24,
                ];
            }
        }

        $total = 0;

        foreach ($balances as $currency => $balance) {
            if (isset($exchangeRates[$currency]) && $exchangeRates[$currency] > 0 && $balance != 0) {
                // تبدیل به دالر: موجودی ارز تقسیم بر نرخ خرید
                $total += $balance / $exchangeRates[$currency];
            }
        }

        return $total;
    }

    private function filterByDate()
    {
        $filteredReports = [];

        // تعیین نوع حساب برای تبدیل
        $accountTypeForConversion = $this->getAccountTypeForConversion();

        foreach ($this->reports as $report) {
            // محاسبه موجودی تا تاریخ مشخص شده
            $balancesAtDate = [];
            $hasBalanceAtDate = false;

            foreach ($this->currencies as $currencyCode => $currencyName) {
                $balance = $this->calculateBalanceAtDate($report['id'], $currencyCode, $this->date);
                $balancesAtDate[$currencyCode] = $balance;

                if ($balance != 0) {
                    $hasBalanceAtDate = true;
                }
            }

            // اگر تا تاریخ مشخص شده موجودی داشته باشد
            if ($hasBalanceAtDate) {
                $report['balances'] = $balancesAtDate;
                $report['total_balance'] = $this->calculateTotalBalance($balancesAtDate, $accountTypeForConversion);
                $report['last_date'] = $this->getLastTransactionDateBefore($report['id'], $this->date);
                
                $filteredReports[] = $report;
            }
        }

        $this->reports = $filteredReports;
    }

    private function calculateBalanceAtDate($customerId, $currency, $date)
    {
        try {
            // تبدیل تاریخ شمسی به میلادی
            $gregorianDate = Jalalian::fromFormat('Y/m/d', $date)->toCarbon()->format('Y-m-d');
        } catch (\Exception $e) {
            // اگر تاریخ معتبر نبود، از تاریخ امروز استفاده کن
            $gregorianDate = now()->format('Y-m-d');
        }

        $query = Transaction::where('customer_id', $customerId)
            ->where('currency', $currency)
            ->where('date', '<=', $gregorianDate);

        if ($this->accountType) {
            $query->where('account_type', $this->accountType);
        }

        return $query->select(DB::raw('SUM(CASE WHEN type = "رسید" THEN amount ELSE -amount END) as balance'))
            ->value('balance') ?? 0;
    }

    private function getLastTransactionDateBefore($customerId, $date)
    {
        try {
            $gregorianDate = Jalalian::fromFormat('Y/m/d', $date)->toCarbon()->format('Y-m-d');
        } catch (\Exception $e) {
            $gregorianDate = now()->format('Y-m-d');
        }

        $query = Transaction::where('customer_id', $customerId)
            ->where('date', '<=', $gregorianDate);

        if ($this->accountType) {
            $query->where('account_type', $this->accountType);
        }

        return $query->max('date');
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->selectedCustomer = '';
        $this->selectedCurrency = '';
        $this->date = Jalalian::now()->format('Y/m/d');
        $this->accountType = '';

        $this->generateReport();
        session()->flash('message', 'تمام فیلترها بازنشانی شدند.');
    }

    public function printReport()
    {
        $latestProfitRate = ProfitRate::latest()->first();
        $sourceCurrency = 'دالر';
        
        if ($latestProfitRate && $latestProfitRate->source_currency) {
            // تابع تبدیل کد ارز به نام فارسی
            $currencyMap = [
                'afn' => 'افغانی',
                'usd' => 'دالر',
                'irr' => 'تومان',
                'eur' => 'یورو',
                'pkr' => 'کلدار',
                'aed' => 'درهم',
                'try' => 'لیره',
                'cny' => 'یوان',
            ];
            
            $currencyCode = strtolower($latestProfitRate->source_currency);
            $sourceCurrency = $currencyMap[$currencyCode] ?? $latestProfitRate->source_currency;
        }

        $printData = [
            'title' => 'گزارش بیلانس مشتریان',
            'filters' => [
                'جستجو' => $this->search ?: 'همه',
                'مشتری معرف' => $this->selectedCustomer ? $this->getCustomerName($this->selectedCustomer) : 'همه',
                'ارز' => $this->selectedCurrency ? $this->currencies[$this->selectedCurrency] : 'همه',
                'نوع حساب' => $this->accountType ?: 'همه',
                'تاریخ' => $this->date ?: 'همه'
            ],
            'reports' => $this->reports,
            'print_date' => now()->format('Y/m/d H:i'),
            'total_customers' => count($this->reports),
            'total_balance' => array_sum(array_column($this->reports, 'total_balance')),
            'currencies' => $this->currencies,
            'source_currency' => $sourceCurrency
        ];

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4-L',
            'directionality' => 'rtl',
            'margin_top' => 10,
            'margin_bottom' => 10,
            'margin_left' => 10,
            'margin_right' => 10,
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

        $html = view('pdf.Sarafi.customer-balance-report', $printData)->render();
        $mpdf->WriteHTML($html);

        $fileName = 'گزارش_بیلانس_مشتریان_' . now()->format('Y_m_d') . '.pdf';

        return response()->streamDownload(function () use ($mpdf) {
            echo $mpdf->Output('', 'S');
        }, $fileName);
    }

    private function getCustomerName($customerId)
    {
        $customer = Customer::find($customerId);
        return $customer ? $customer->fullname : 'نامشخص';
    }

    public function updatedSearch()
    {
        $this->generateReport();
    }

    public function updatedSelectedCustomer()
    {
        $this->generateReport();
    }

    public function updatedSelectedCurrency()
    {
        $this->generateReport();
    }

    public function updatedAccountType()
    {
        $this->generateReport();
    }

    public function updatedDate()
    {
        $this->generateReport();
    }

    public function refreshReport()
    {
        $this->generateReport();
        session()->flash('message', 'گزارش با موفقیت بروز رسانی شد.');
    }

    public function render()
    {
        return view('livewire.sarafi.account-reports');
    }
}