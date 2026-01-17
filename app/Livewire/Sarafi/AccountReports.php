<?php

namespace App\Livewire\Sarafi;

use App\Models\Sarafi\Customer;
use App\Models\Sarafi\ProfitRate;
use App\Models\Sarafi\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Morilog\Jalali\Jalalian;

class AccountReports extends Component
{
    public $search = '';
    public $selectedCustomer = '';
    public $selectedCurrency = '';
    public $accountType = '';
    public $date;
    public $customerTypeFilter = ''; // فیلتر جدید برای نوع مشتری

    public $customers = [];
    public $reports = [];
    public $normalReports = [];
    public $sarafiCardReports = [];

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


    private function getVisibleCurrencies(array $reports): array
    {
        $visibleCurrencies = [];

        foreach ($this->currencies as $currencyCode => $currencyName) {
            foreach ($reports as $report) {
                if (($report['balances'][$currencyCode] ?? 0) != 0) {
                    $visibleCurrencies[$currencyCode] = $currencyName;
                    break;
                }
            }
        }

        return $visibleCurrencies;
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
        $query = Customer::where(function ($query) use ($adminId, $linkedCustomerIds) {
            $query->where('admin_id', $adminId);

            if (!empty($linkedCustomerIds)) {
                $query->orWhereIn('id', $linkedCustomerIds);
            }
        });

        // اعمال فیلتر جستجو
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('fullname', 'like', '%' . $this->search . '%')
                    ->orWhere('account_number', 'like', '%' . $this->search . '%');
            });
        }

        // اعمال فیلتر مشتری معرف
        if ($this->selectedCustomer) {
            $query->where('related_customer_id', $this->selectedCustomer);
        }

        // اعمال فیلتر نوع مشتری
        if ($this->customerTypeFilter) {
            if ($this->customerTypeFilter === 'sarafi_card') {
                $query->where('type', 'sarafi_card');
            } elseif ($this->customerTypeFilter === 'normal') {
                $query->where('type', '!=', 'sarafi_card');
            }
        }

        $customers = $query->get();

        // جدا کردن مشتریان به دو گروه
        $this->normalReports = [];
        $this->sarafiCardReports = [];

        foreach ($customers as $customer) {
            $report = $this->processCustomer($customer);

            if ($report['has_balance']) {
                if ($customer->type === 'sarafi_card') {
                    $this->sarafiCardReports[] = $report;
                } else {
                    $this->normalReports[] = $report;
                }
            }
        }

        // فیلتر بر اساس ارز انتخاب شده
        if ($this->selectedCurrency) {
            $this->normalReports = array_filter($this->normalReports, function ($report) {
                return ($report['balances'][$this->selectedCurrency] ?? 0) != 0;
            });

            $this->sarafiCardReports = array_filter($this->sarafiCardReports, function ($report) {
                return ($report['balances'][$this->selectedCurrency] ?? 0) != 0;
            });
        }

        if ($this->selectedCurrency) {
            $this->normalReports = array_map(function ($report) {
                foreach ($report['balances'] as $currency => $value) {
                    if ($currency !== $this->selectedCurrency) {
                        $report['balances'][$currency] = 0;
                    }
                }

                // مجموع فقط از ارز انتخاب شده
                $report['total_balance'] = $this->calculateTotalBalance(
                    [$this->selectedCurrency => $report['balances'][$this->selectedCurrency]],
                    $this->getAccountTypeForConversion()
                );

                return $report;
            }, $this->normalReports);

            $this->sarafiCardReports = array_map(function ($report) {
                foreach ($report['balances'] as $currency => $value) {
                    if ($currency !== $this->selectedCurrency) {
                        $report['balances'][$currency] = 0;
                    }
                }

                $report['total_balance'] = $this->calculateTotalBalance(
                    [$this->selectedCurrency => $report['balances'][$this->selectedCurrency]],
                    $this->getAccountTypeForConversion()
                );

                return $report;
            }, $this->sarafiCardReports);
        }


        // فیلتر بر اساس تاریخ
        if ($this->date) {
            $this->filterReportsByDate();
        }

        // مرتب سازی بر اساس مجموع موجودی
        usort($this->normalReports, function ($a, $b) {
            return $b['total_balance'] <=> $a['total_balance'];
        });

        usort($this->sarafiCardReports, function ($a, $b) {
            return $b['total_balance'] <=> $a['total_balance'];
        });

      
        $this->reports = array_merge($this->normalReports, $this->sarafiCardReports);

        $this->currencies = $this->getVisibleCurrencies($this->reports);
    }

    private function processCustomer($customer)
    {
        $report = [
            'id' => $customer->id,
            'account_number' => $customer->account_number,
            'fullname' => $customer->fullname,
            'type' => $customer->type,
            'related_customer_id' => $customer->related_customer_id,
            'related_customer_name' => $this->getRelatedCustomerName($customer->related_customer_id),
            'last_date' => null,
            'balances' => [],
            'total_balance' => 0,
            'has_balance' => false
        ];

        $accountTypeForConversion = $this->getAccountTypeForConversion();

        // محاسبه موجودی فقط برای ارزهای فعلی که باید نمایش داده شوند
        foreach ($this->currencies as $currencyCode => $currencyName) {
            $balance = $this->calculateBalance($customer->id, $currencyCode);
            if ($balance != 0) {
                $report['balances'][$currencyCode] = $balance;
                if (!$report['last_date']) {
                    $report['last_date'] = $this->getLastTransactionDate($customer->id, $currencyCode);
                }
                $report['has_balance'] = true;
            }
        }

        // محاسبه مجموع موجودی به دالر
        $report['total_balance'] = $this->calculateTotalBalance($report['balances'], $accountTypeForConversion);

        return $report;
    }


    private function filterReportsByDate()
    {
        $accountTypeForConversion = $this->getAccountTypeForConversion();

        $filterNormal = [];
        foreach ($this->normalReports as $report) {
            $filteredReport = $this->filterReportByDate($report, $accountTypeForConversion);
            if ($filteredReport['has_balance']) {
                $filterNormal[] = $filteredReport;
            }
        }
        $this->normalReports = $filterNormal;

        $filterSarafiCard = [];
        foreach ($this->sarafiCardReports as $report) {
            $filteredReport = $this->filterReportByDate($report, $accountTypeForConversion);
            if ($filteredReport['has_balance']) {
                $filterSarafiCard[] = $filteredReport;
            }
        }
        $this->sarafiCardReports = $filterSarafiCard;
    }

    private function filterReportByDate($report, $accountTypeForConversion)
    {
        $balancesAtDate = [];
        $hasBalanceAtDate = false;

        foreach ($this->currencies as $currencyCode => $currencyName) {

            // اگر ارز انتخاب شده و این ارز نیست → صفر
            if ($this->selectedCurrency && $currencyCode !== $this->selectedCurrency) {
                $balancesAtDate[$currencyCode] = 0;
                continue;
            }

            $balance = $this->calculateBalanceAtDate(
                $report['id'],
                $currencyCode,
                $this->date
            );

            $balancesAtDate[$currencyCode] = $balance;

            if ($balance != 0) {
                $hasBalanceAtDate = true;
            }
        }

        $report['balances'] = $balancesAtDate;

        // مجموع فقط بر اساس ارز انتخاب‌شده
        $report['total_balance'] = $this->calculateTotalBalance(
            $this->selectedCurrency
                ? [$this->selectedCurrency => $balancesAtDate[$this->selectedCurrency]]
                : $balancesAtDate,
            $accountTypeForConversion
        );

        $report['last_date'] = $this->getLastTransactionDateBefore(
            $report['id'],
            $this->date
        );

        $report['has_balance'] = $hasBalanceAtDate;

        return $report;
    }

    private function getAccountTypeForConversion()
    {
        if ($this->accountType == 'بانکی') {
            return 'bank';
        } elseif ($this->accountType == 'نقدی') {
            return 'cash';
        }
        return 'cash';
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
            ->where('admin_id', $adminId);

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
        ];

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
                $totalUsd += $balance / $exchangeRates[$currency];
            }
        }

        return round($totalUsd, 2);
    }

    private function calculateBalanceAtDate($customerId, $currency, $date)
    {
        try {
            $gregorianDate = Jalalian::fromFormat('Y/m/d', $date)->toCarbon()->format('Y-m-d');
        } catch (\Exception $e) {
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
        $this->customerTypeFilter = '';

        $this->generateReport();
        session()->flash('message', 'تمام فیلترها بازنشانی شدند.');
    }

    public function printReport()
    {
        if (empty($this->normalReports) && empty($this->sarafiCardReports)) {
            session()->flash('message', 'داده‌ای برای چاپ وجود ندارد');
            return;
        }

        // محاسبه مجموع‌ها برای هر گروه
        $normalTotals = $this->calculateTotalsByCurrency($this->normalReports);
        $sarafiCardTotals = $this->calculateTotalsByCurrency($this->sarafiCardReports);

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
        $normalCustomersCount = count($this->normalReports);
        $sarafiCardCustomersCount = count($this->sarafiCardReports);
        $totalCustomers = $normalCustomersCount + $sarafiCardCustomersCount;

        $allReports = array_merge($this->normalReports, $this->sarafiCardReports);
        $visibleCurrencies = $this->getVisibleCurrencies($allReports);

        $printData = [
            'title'                    => 'گزارش بیلانس مشتریان',
            'normal_reports'           => $this->normalReports,
            'sarafi_card_reports'      => $this->sarafiCardReports,
            'total_customers'          => $totalCustomers,
            'normal_customers_count'   => $normalCustomersCount,
            'sarafi_card_customers_count' => $sarafiCardCustomersCount,
            'print_date'               => now()->format('Y/m/d H:i'),
            'source_currency'          => $sourceCurrency,
            'currencies' => $visibleCurrencies,
            'normal_totals'            => $normalTotals,
            'sarafi_card_totals'       => $sarafiCardTotals,
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
            fn() => print $mpdf->Output('', 'S'),
            'گزارش_بیلانس_مشتریان_' . now()->format('Y_m_d') . '.pdf'
        );
    }
    private function calculateTotalsByCurrency($reports)
    {
        $totals = [];

        // ابتدا فقط ارزهایی که حداقل یک مشتری موجودی دارد
        $visibleCurrencies = [];
        foreach ($this->currencies as $currencyCode => $currencyName) {
            foreach ($reports as $report) {
                if (($report['balances'][$currencyCode] ?? 0) != 0) {
                    $visibleCurrencies[$currencyCode] = $currencyName;
                    break;
                }
            }
        }

        // مقداردهی اولیه مجموع‌ها برای ارزهای قابل نمایش
        foreach ($visibleCurrencies as $currencyCode => $currencyName) {
            $totals[$currencyCode] = [
                'cash' => 0,
                'bank' => 0,
                'total' => 0,
            ];
        }

        // محاسبه موجودی‌ها
        foreach ($reports as $report) {
            foreach ($visibleCurrencies as $currencyCode => $currencyName) {

                $balance = $report['balances'][$currencyCode] ?? 0;
                $totals[$currencyCode]['total'] += $balance;

                if ($this->accountType === 'نقدی') {
                    $totals[$currencyCode]['cash'] +=
                        $this->calculateBalanceByType($report['id'], $currencyCode, 'نقدی');
                } elseif ($this->accountType === 'بانکی') {
                    $totals[$currencyCode]['bank'] +=
                        $this->calculateBalanceByType($report['id'], $currencyCode, 'بانکی');
                } else {
                    $totals[$currencyCode]['cash'] +=
                        $this->calculateBalanceByType($report['id'], $currencyCode, 'نقدی');

                    $totals[$currencyCode]['bank'] +=
                        $this->calculateBalanceByType($report['id'], $currencyCode, 'بانکی');
                }
            }
        }

        // مجموع کل به دالر
        $accountTypeForConversion = $this->getAccountTypeForConversion();
        $totalBalanceUsd = $this->calculateTotalBalance(
            array_map(fn($v) => $v['total'], $totals),
            $accountTypeForConversion
        );

        return [
            'currencies' => $totals,
            'total_usd'  => $totalBalanceUsd
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

    public function updatedCustomerTypeFilter()
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
        return view('livewire.sarafi.account-reports',[
             'currencies' => $this->currencies
        ]);
    }
}
