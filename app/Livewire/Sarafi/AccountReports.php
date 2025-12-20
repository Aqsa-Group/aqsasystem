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
        'cny' => 'یوان',
        'gbp' => 'پوند',
        'jpy' => 'ین',
        'sar' => 'ریال',
        'inr' => 'روپیه هندی',
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

        // دریافت نرخ‌ها
        $rates = ProfitRate::latest()->first();

        foreach ($customers as $customer) {
            $report = [
                'id' => $customer->id,
                'account_number' => $customer->account_number,
                'fullname' => $customer->fullname,
                'related_customer_id' => $customer->related_customer_id,
                'related_customer_name' => $this->getRelatedCustomerName($customer->related_customer_id),
                'last_date' => null,
                'cash_balances' => [],
                'bank_balances' => [],
                'total_balance' => 0,
                'has_balance' => false
            ];

            // محاسبه موجودی نقدی و بانکی برای هر ارز
            foreach ($this->currencies as $currencyCode => $currencyName) {
                $cashBalance = $this->calculateCashBalance($customer->id, $currencyCode);
                $bankBalance = $this->calculateBankBalance($customer->id, $currencyCode);
                
                $report['cash_balances'][$currencyCode] = $cashBalance;
                $report['bank_balances'][$currencyCode] = $bankBalance;

                if ($cashBalance != 0 || $bankBalance != 0) {
                    if (!$report['last_date']) {
                        $report['last_date'] = $this->getLastTransactionDate($customer->id, $currencyCode);
                    }
                    $report['has_balance'] = true;
                }
            }

            // محاسبه مجموع موجودی به دالر با توجه به فیلتر نوع حساب
            $report['total_balance'] = $this->calculateTotalBalance($report['cash_balances'], $report['bank_balances']);

            // فقط مشتریانی که موجودی دارند نمایش داده شوند
            if ($report['has_balance']) {
                $this->reports[] = $report;
            }
        }

        // فیلتر بر اساس ارز انتخاب شده
        if ($this->selectedCurrency) {
            $this->reports = array_filter($this->reports, function ($report) {
                return ($report['cash_balances'][$this->selectedCurrency] ?? 0) != 0 || 
                       ($report['bank_balances'][$this->selectedCurrency] ?? 0) != 0;
            });
        }

        // فیلتر بر اساس تاریخ
        if ($this->date) {
            $this->filterByDate();
        }

        // فیلتر بر اساس نوع حساب
        if ($this->accountType) {
            $this->filterByAccountType();
        }

        // مرتب سازی بر اساس مجموع موجودی
        usort($this->reports, function ($a, $b) {
            return $b['total_balance'] <=> $a['total_balance'];
        });
    }

    private function filterByAccountType()
    {
        foreach ($this->reports as &$report) {
            if ($this->accountType == 'نقدی') {
                $report['total_balance'] = $this->calculateTotalBalance($report['cash_balances'], []);
            } elseif ($this->accountType == 'بانکی') {
                $report['total_balance'] = $this->calculateTotalBalance([], $report['bank_balances']);
            }
        }
    }

    private function getRelatedCustomerName($relatedCustomerId)
    {
        if (!$relatedCustomerId) return null;

        $relatedCustomer = Customer::find($relatedCustomerId);
        return $relatedCustomer ? $relatedCustomer->fullname : 'نامشخص';
    }

    private function calculateCashBalance($customerId, $currency)
    {
        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;
        
        $query = Transaction::where('customer_id', $customerId)
            ->where('currency', $currency)
            ->where('account_type', 'نقدی')
            ->where('admin_id', $adminId);

        return $query->select(DB::raw('SUM(CASE WHEN type = "رسید" THEN amount ELSE -amount END) as balance'))
            ->value('balance') ?? 0;
    }

    private function calculateBankBalance($customerId, $currency)
    {
        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;
        
        $query = Transaction::where('customer_id', $customerId)
            ->where('currency', $currency)
            ->where('account_type', 'بانکی')
            ->where('admin_id', $adminId);

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

    private function calculateTotalBalance(array $cashBalances, array $bankBalances): float
    {
        $latestProfitRate = ProfitRate::latest()->first();

        // نرخ‌های پیش‌فرض نقدی
        $defaultCashRates = [
            'afn' => 66.20,
            'usd' => 1,
            'irr' => 110000.00,
            'eur' => 70.00,
            'pkr' => 32.00,
            'aed' => 44.00,
            'try' => 60.00,
            'cny' => 43.00,
            'gbp' => 88.00,
            'jpy' => 0.67,
            'sar' => 3.75,
            'inr' => 7.14,
        ];

        // نرخ‌های پیش‌فرض بانکی
        $defaultBankRates = [
            'afn' => 66.20,
            'usd' => 1,
            'irr' => 110000.00,
            'eur' => 70.00,
            'pkr' => 32.00,
            'aed' => 44.00,
            'try' => 60.00,
            'cny' => 43.00,
            'gbp' => 88.00,
            'jpy' => 0.67,
            'sar' => 3.75,
            'inr' => 7.14,
        ];

        // تعریف نرخ‌های نقدی
        $exchangeRatesCash = [
            'afn' => $latestProfitRate->afn_buy_cash ?? $defaultCashRates['afn'],
            'usd' => $latestProfitRate->usd_buy_cash ?? $defaultCashRates['usd'],
            'irr' => $latestProfitRate->irr_buy_cash ?? $defaultCashRates['irr'],
            'eur' => $latestProfitRate->eur_buy_cash ?? $defaultCashRates['eur'],
            'pkr' => $latestProfitRate->pkr_buy_cash ?? $defaultCashRates['pkr'],
            'aed' => $latestProfitRate->aed_buy_cash ?? $defaultCashRates['aed'],
            'try' => $latestProfitRate->try_buy_cash ?? $defaultCashRates['try'],
            'cny' => $latestProfitRate->cny_buy_cash ?? $defaultCashRates['cny'],
            'gbp' => $latestProfitRate->gbp_buy_cash ?? $defaultCashRates['gbp'],
            'jpy' => $latestProfitRate->jpy_buy_cash ?? $defaultCashRates['jpy'],
            'sar' => $latestProfitRate->sar_buy_cash ?? $defaultCashRates['sar'],
            'inr' => $latestProfitRate->inr_buy_cash ?? $defaultCashRates['inr'],
        ];

        // تعریف نرخ‌های بانکی
        $exchangeRatesBank = [
            'afn' => $latestProfitRate->afn_buy_bank ?? $defaultBankRates['afn'],
            'usd' => $latestProfitRate->usd_buy_bank ?? $defaultBankRates['usd'],
            'irr' => $latestProfitRate->irr_buy_bank ?? $defaultBankRates['irr'],
            'eur' => $latestProfitRate->eur_buy_bank ?? $defaultBankRates['eur'],
            'pkr' => $latestProfitRate->pkr_buy_bank ?? $defaultBankRates['pkr'],
            'aed' => $latestProfitRate->aed_buy_bank ?? $defaultBankRates['aed'],
            'try' => $latestProfitRate->try_buy_bank ?? $defaultBankRates['try'],
            'cny' => $latestProfitRate->cny_buy_bank ?? $defaultBankRates['cny'],
            'gbp' => $latestProfitRate->gbp_buy_bank ?? $defaultBankRates['gbp'],
            'jpy' => $latestProfitRate->jpy_buy_bank ?? $defaultBankRates['jpy'],
            'sar' => $latestProfitRate->sar_buy_bank ?? $defaultBankRates['sar'],
            'inr' => $latestProfitRate->inr_buy_bank ?? $defaultBankRates['inr'],
        ];

        $totalCashUsd = 0;
        $totalBankUsd = 0;

        // محاسبه موجودی نقدی به دالر
        foreach($cashBalances as $currency => $balance) {
            if(isset($exchangeRatesCash[$currency]) && $exchangeRatesCash[$currency] > 0) {
                $totalCashUsd += $balance / $exchangeRatesCash[$currency];
            }
        }

        // محاسبه موجودی بانکی به دالر
        foreach($bankBalances as $currency => $balance) {
            if(isset($exchangeRatesBank[$currency]) && $exchangeRatesBank[$currency] > 0) {
                $totalBankUsd += $balance / $exchangeRatesBank[$currency];
            }
        }

        $grandTotalUsd = $totalCashUsd + $totalBankUsd;
        return round($grandTotalUsd, 2);
    }

    private function filterByDate()
    {
        $filteredReports = [];

        foreach ($this->reports as $report) {
            $cashBalancesAtDate = [];
            $bankBalancesAtDate = [];
            $hasBalanceAtDate = false;

            foreach ($this->currencies as $currencyCode => $currencyName) {
                $cashBalance = $this->calculateCashBalanceAtDate($report['id'], $currencyCode, $this->date);
                $bankBalance = $this->calculateBankBalanceAtDate($report['id'], $currencyCode, $this->date);
                
                $cashBalancesAtDate[$currencyCode] = $cashBalance;
                $bankBalancesAtDate[$currencyCode] = $bankBalance;

                if ($cashBalance != 0 || $bankBalance != 0) {
                    $hasBalanceAtDate = true;
                }
            }

            if ($hasBalanceAtDate) {
                $report['cash_balances'] = $cashBalancesAtDate;
                $report['bank_balances'] = $bankBalancesAtDate;
                $report['total_balance'] = $this->calculateTotalBalance($cashBalancesAtDate, $bankBalancesAtDate);
                $report['last_date'] = $this->getLastTransactionDateBefore($report['id'], $this->date);

                $filteredReports[] = $report;
            }
        }

        $this->reports = $filteredReports;
    }

    private function calculateCashBalanceAtDate($customerId, $currency, $date)
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
            ->where('account_type', 'نقدی')
            ->where('admin_id', $adminId)
            ->where('date', '<=', $gregorianDate);

        return $query->select(DB::raw('SUM(CASE WHEN type = "رسید" THEN amount ELSE -amount END) as balance'))
            ->value('balance') ?? 0;
    }

    private function calculateBankBalanceAtDate($customerId, $currency, $date)
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
            ->where('account_type', 'بانکی')
            ->where('admin_id', $adminId)
            ->where('date', '<=', $gregorianDate);

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
            $currencyMap = [
                'afn' => 'افغانی',
                'usd' => 'دالر',
                'irr' => 'تومان',
                'eur' => 'یورو',
                'pkr' => 'کلدار',
                'aed' => 'درهم',
                'try' => 'لیره',
                'cny' => 'یوان',
                'gbp' => 'پوند',
                'jpy' => 'ین',
                'sar' => 'ریال',
                'inr' => 'روپیه هندی',
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