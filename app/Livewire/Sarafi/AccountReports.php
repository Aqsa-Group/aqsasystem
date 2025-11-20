<?php

namespace App\Livewire\Sarafi;

use App\Models\Sarafi\Customer;
use App\Models\Sarafi\ExchangeRates;
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
    public $selectedDate = '';
    public $accountType = '';

    public $customers = [];
    public $reports = [];

    public $date;

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

            // محاسبه موجودی برای هر ارز با فیلتر تاریخ
            foreach ($this->currencies as $currencyCode => $currencyName) {
                $balance = $this->calculateBalance($customer->id, $currencyCode);
                $report['balances'][$currencyCode] = $balance;

                if ($balance != 0 && !$report['last_date']) {
                    $report['last_date'] = $this->getLastTransactionDate($customer->id, $currencyCode);
                    $report['has_balance'] = true;
                }
            }

            $report['total_balance'] = $this->calculateTotalBalance($report['balances']);

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
    }

    // فیلتر کردن گزارش بر اساس تاریخ
    private function filterByDate()
    {
        $filteredReports = [];

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
                $report['total_balance'] = $this->calculateTotalBalance($balancesAtDate);
                $report['last_date'] = $this->getLastTransactionDateBefore($report['id'], $this->date);
                
                $filteredReports[] = $report;
            }
        }

        $this->reports = $filteredReports;
    }

    // محاسبه موجودی تا تاریخ مشخص شده
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

    // تاریخ آخرین تراکنش قبل از تاریخ مشخص شده
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

    private function calculateTotalBalance($balances)
    {
        $latestExchangeRate = ExchangeRates::latest()->first();

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
                $total += $balance / $exchangeRates[$currency];
            }
        }

        return $total;
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

    // چاپ با mPDF
    public function printReport()
    {
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
            'currencies' => $this->currencies
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