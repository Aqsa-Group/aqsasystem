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

    public function updatedSearch()
    {
        $this->generateReport();
    }

    public function updatedDate()
    {
        $this->generateReport();
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

        // دریافت ID مشتریان لینک شده
        $linkedCustomerIds = DB::connection('sarafi')
            ->table('customer_admin')
            ->where('admin_id', $adminId)
            ->pluck('customer_id')
            ->toArray();

        // ساخت کوئری پایه برای مشتریان
        $baseQuery = Customer::where(function ($query) use ($adminId, $linkedCustomerIds) {
            $query->where('admin_id', $adminId);

            if (!empty($linkedCustomerIds)) {
                $query->orWhereIn('id', $linkedCustomerIds);
            }
        });

        // اعمال فیلتر جستجو
        if ($this->search) {
            $baseQuery->where(function ($q) {
                $q->where('fullname', 'like', '%' . $this->search . '%')
                    ->orWhere('account_number', 'like', '%' . $this->search . '%');
            });
        }

        // اعمال فیلتر مشتری معرف
        if ($this->selectedCustomer) {
            $baseQuery->where('related_customer_id', $this->selectedCustomer);
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

                if ($balance != 0) {
                    if (!$report['last_date']) {
                        $report['last_date'] = $this->getLastTransactionDate($customer->id, $currencyCode);
                    }
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

        // مرتب سازی بر اساس مجموع موجودی
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

        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;
        $query = Transaction::where('customer_id', $customerId)
            ->where('currency', $currency)
            ->where('admin_id', $adminId);

        if ($this->accountType) {
            $query->where('account_type', $this->accountType);
        }

        return $query->select(DB::raw('SUM(CASE WHEN type = "رسید" THEN amount ELSE -amount END) as balance'))
            ->value('balance') ?? 0;
    }

    private function getLastTransactionDate($customerId, $currency)
    {
        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;
        $query = Transaction::where('customer_id', $customerId)
            ->where('currency', $currency)
            ->where('admin_id', $adminId);;

        if ($this->accountType) {
            $query->where('account_type', $this->accountType);
        }

        return $query->max('date');
    }
    private function calculateTotalBalance(array $balances, string $accountType = 'cash'): float
    {
        $latestProfitRate = ProfitRate::latest()->first();

        $defaultRates = [
            'afn' => 66.20,
            'usd' => 1,
            'irr' => 110000.00,
            'eur' => 0.93,
            'pkr' => 277.78,
            'aed' => 3.67,
            'try' => 32.26,
            'cny' => 7.24,
            'gbp' => 0.79,
            'jpy' => 150,
            'sar' => 3.75,
            'inr' => 83,
        ];

        // اگر نرخ وجود داشت
        if ($latestProfitRate) {
            $exchangeRates = [];

            foreach ($defaultRates as $currency => $fallback) {
                $column = $currency . '_buy_' . ($accountType === 'bank' ? 'bank' : 'cash');

                $exchangeRates[$currency] =
                    ($latestProfitRate->$column ?? 0) > 0
                    ? $latestProfitRate->$column
                    : $fallback;
            }
        } else {
            $exchangeRates = $defaultRates;
        }

        $totalUsd = 0;

        foreach ($balances as $currency => $balance) {
            if (
                isset($exchangeRates[$currency]) &&
                $exchangeRates[$currency] > 0 &&
                $balance != 0
            ) {
                // تبدیل به USD
                $totalUsd += $balance / $exchangeRates[$currency];
            }
        }

        return round($totalUsd, 2);
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

        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;
        $query = Transaction::where('customer_id', $customerId)
            ->where('currency', $currency)
            ->where('date', '<=', $gregorianDate)
            ->where('admin_id', $adminId);


        if ($this->accountType) {
            $query->where('account_type', $this->accountType);
        }

        return $query->select(DB::raw('SUM(CASE WHEN type = "رسید" THEN amount ELSE -amount END) as balance'))
            ->value('balance') ?? 0;
    }

    private function getLastTransactionDateBefore($customerId, $date)
    {

        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        try {
            $gregorianDate = Jalalian::fromFormat('Y/m/d', $date)->toCarbon()->format('Y-m-d');
        } catch (\Exception $e) {
            $gregorianDate = now()->format('Y-m-d');
        }

        $query = Transaction::where('customer_id', $customerId)
            ->where('date', '<=', $gregorianDate)
            ->where('admin_id', $adminId);

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
    // گزارش ساخته شده توسط generateReport
    $reports = $this->reports;

    // اگر داده‌ای نیست
    if (empty($reports)) {
        session()->flash('message', 'داده‌ای برای چاپ وجود ندارد');
        return;
    }

    // محاسبه مجموع‌ها
    $totalsData = $this->calculateTotalsByCurrency();

    // ارز مرجع
    $latestProfitRate = ProfitRate::latest()->first();
    $sourceCurrency = 'دالر';

    if ($latestProfitRate && $latestProfitRate->source_currency) {
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

    // تعداد مشتریان
    $totalCustomers = count($reports);

    // داده‌های ارسال‌شده به PDF
    $printData = [
        'title'            => 'گزارش بیلانس مشتریان',
        'reports'          => $reports,
        'total_customers'  => $totalCustomers,
        'print_date'       => now()->format('Y/m/d H:i'),
        'source_currency'  => $sourceCurrency,
        'currencies'       => $this->currencies,        // ⭐ خیلی مهم
        'totals'           => $totalsData['currencies'], // برای جدول جمع کل
        'total_usd'        => $totalsData['total_usd'],
    ];

    // تنظیمات mPDF
    $mpdf = new \Mpdf\Mpdf([
        'mode'             => 'utf-8',
        'format'           => 'A4-L',
        'directionality'   => 'rtl',
        'margin_top'       => 10,
        'margin_bottom'    => 10,
        'margin_left'      => 10,
        'margin_right'     => 10,
        'default_font'     => 'Shabnam',
    ]);

    // رندر Blade
    $html = view('pdf.Sarafi.customer-balance-report', $printData)->render();
    $mpdf->WriteHTML($html);

    // دانلود PDF
    return response()->streamDownload(
        fn () => print $mpdf->Output('', 'S'),
        'گزارش_بیلانس_مشتریان_' . now()->format('Y_m_d') . '.pdf'
    );
}



    private function getCustomerName($customerId)
    {
        $customer = Customer::find($customerId);
        return $customer ? $customer->fullname : 'نامشخص';
    }




    private function calculateTotalsByCurrency()
    {
        $totals = [];

        foreach ($this->currencies as $currencyCode => $currencyName) {
            $totals[$currencyCode] = [
                'cash' => 0,
                'bank' => 0,
                'total' => 0,
            ];
        }

        foreach ($this->reports as $report) {
            foreach ($this->currencies as $currencyCode => $currencyName) {
                if (isset($report['balances'][$currencyCode])) {
                    $totals[$currencyCode]['total'] += $report['balances'][$currencyCode];

                    // تفکیک بر اساس نوع حساب
                    $transactionsCash = $this->calculateBalanceByType($report['id'], $currencyCode, 'نقدی');
                    $transactionsBank = $this->calculateBalanceByType($report['id'], $currencyCode, 'بانکی');

                    $totals[$currencyCode]['cash'] += $transactionsCash;
                    $totals[$currencyCode]['bank'] += $transactionsBank;
                }
            }
        }

        // محاسبه مجموع به دالر
        $accountTypeForConversion = 'cash'; // پیش‌فرض
        $totalBalanceUsd = $this->calculateTotalBalance(
            array_map(fn($v) => $v['total'], $totals),
            $accountTypeForConversion
        );



        return [
            'currencies' => $totals,
            'total_usd' => $totalBalanceUsd
        ];
    }

    private function calculateBalanceByType($customerId, $currency, $accountType)
    {
        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        return Transaction::where('customer_id', $customerId)
            ->where('currency', $currency)
            ->where('account_type', $accountType)
            ->where('admin_id', $adminId)
            ->select(DB::raw('SUM(CASE WHEN type = "رسید" THEN amount ELSE -amount END) as balance'))
            ->value('balance') ?? 0;
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
