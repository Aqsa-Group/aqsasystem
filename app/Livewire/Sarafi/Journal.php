<?php

namespace App\Livewire\Sarafi;

use App\Models\Sarafi\BankAccount;
use App\Models\Sarafi\CurrencySafe;
use App\Models\Sarafi\Customer;
use App\Models\Sarafi\Journals;
use App\Models\Sarafi\Revenue;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;
use Morilog\Jalali\Jalalian;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;
use Mpdf\Mpdf;

class Journal extends Component
{
    use WithPagination;

    // فیلترها
    public $transactionType = '';
    public $accountType = '';
    public $selectedCustomer = '';
    public $fromDate = '';
    public $toDate = '';
    public $currency = '';
    public $search = '';
    public $perPage = 10000;
    public $currencies = [];

    // موجودی‌ها
    public $currencySafeBalance = [];
    public $bankAccountBalance = [];
    public $totalBalanceByCurrency = [];

    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        // تنظیم تاریخ امروز به صورت شمسی
        $todayJalali = Jalalian::now();
        $this->fromDate = $todayJalali->format('Y-m-d');
        $this->toDate = $todayJalali->format('Y-m-d');

        // تعریف ارزها با کد و نام فارسی
        $this->currencies = [
            'afn' => 'افغانی',
            'usd' => 'دالر',
            'eur' => 'یورو',
            'irr' => 'تومان',
            'aed' => 'درهم',
            'try' => 'لیره',
            'cny' => 'یوان',
            'pkr' => 'کلدار',
            'gbp' => 'پوند',
            'jpy' => 'ین',
            'sar' => 'ریال',
            'inr' => 'روپیه',
        ];

        // محاسبه موجودی‌ها
        $this->calculateBalances();
    }

    // محاسبه موجودی‌های صندوق و حساب بانکی به تفکیک ارز
    public function calculateBalances()
    {
        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        $isToday = $this->toDate === Jalalian::now()->format('Y-m-d');

        $currencySafe = CurrencySafe::where('admin_id', $adminId)->first();
        $bankAccount  = BankAccount::where('admin_id', $adminId)->first();

        foreach ($this->currencies as $code => $name) {

            if ($isToday) {
                $safe = $currencySafe->{$code} ?? 0;
                $bank = $bankAccount->{$code} ?? 0;
            } else {

                $safe = Journals::where('admin_id', $adminId)
                    ->where('currency', $code)
                    ->where('account_type', 'نقدی')
                    ->where('date', '<=', $this->toDate)
                    ->orderByDesc('date')
                    ->orderByDesc('id')
                    ->value('safe_balance') ?? 0;

                $bank = Journals::where('admin_id', $adminId)
                    ->where('currency', $code)
                    ->where('account_type', 'بانکی')
                    ->where('date', '<=', $this->toDate)
                    ->orderByDesc('date')
                    ->orderByDesc('id')
                    ->value('safe_balance') ?? 0;
            }

            $this->currencySafeBalance[$code] = $safe;
            $this->bankAccountBalance[$code]  = $bank;
            $this->totalBalanceByCurrency[$code] = $safe + $bank;
        }
    }

    private function getDateRange()
    {
        $date = $this->toDate ?: Jalalian::now()->format('Y/m/d');

        $date = str_replace('-', '/', $date);

        try {
            $start = Jalalian::fromFormat('Y/m/d', $date)
                ->toCarbon()
                ->startOfDay()
                ->timezone('UTC');
        } catch (\Exception $e) {
            $start = now('UTC')->startOfDay();
        }

        $end = (clone $start)->addDay();

        return [$start, $end];
    }


    public function render()
    {
        $transactions = $this->getFilteredTransactions()->paginate($this->perPage);
        $summary = $this->getSummaryData()->get();
        $customers = Customer::select('id', 'fullname', 'account_number', 'phone')
            ->orderBy('fullname')
            ->get();

        $this->calculateBalances();

        [$start, $end] = $this->getDateRange();

        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        $todayprofit = Revenue::where('admin_id', $adminId)
            ->whereBetween('created_at', [$start, $end])
            ->sum('profit');

        $todaylost = Revenue::where('admin_id', $adminId)
            ->whereBetween('created_at', [$start, $end])
            ->sum('lost');

        return view('livewire.sarafi.journal', [
            'transactions' => $transactions,
            'summary' => $summary,
            'customers' => $customers,
            'currencies' => $this->currencies,
            'currencySafeBalance' => $this->currencySafeBalance,
            'bankAccountBalance' => $this->bankAccountBalance,
            'totalBalanceByCurrency' => $this->totalBalanceByCurrency,
            'todayprofit' => $todayprofit,
            'todaylost' => $todaylost,
        ]);
    }

    public function printReport()
    {
        $transactions = $this->getFilteredTransactions()->get();
        $summary = $this->getSummaryData()->get();

        [$start, $end] = $this->getDateRange();

        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        $todayProfit = Revenue::where('admin_id', $adminId)
            ->whereBetween('created_at', [$start, $end])
            ->sum('profit');

        $todayLoss = Revenue::where('admin_id', $adminId)
            ->whereBetween('created_at', [$start, $end])
            ->sum('lost');

        $customerName = '';
        $customerAccount = '';

        if ($this->selectedCustomer) {
            $customer = Customer::find($this->selectedCustomer);
            $customerName = $customer?->fullname ?? '';
            $customerAccount = $customer?->account_number ?? '';
        }

        $this->calculateBalances();

        $data = [
            'transactions' => $transactions,
            'summary' => $summary,
            'customerName' => $customerName,
            'customerAccount' => $customerAccount,
            'currencies' => $this->currencies,
            'currencySafeBalance' => $this->currencySafeBalance,
            'bankAccountBalance' => $this->bankAccountBalance,
            'totalBalanceByCurrency' => $this->totalBalanceByCurrency,
            'todayProfit' => $todayProfit,
            'todayLoss' => $todayLoss,
            'filters' => [
                'transactionType' => $this->transactionType,
                'accountType' => $this->accountType,
                'currency' => $this->currency,
                'fromDate' => $this->fromDate,
                'toDate' => $this->toDate,
            ],
        ];

        $html = view('pdf.Sarafi.journal', $data)->render();

        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => 'L',
            'directionality' => 'rtl',
            'default_font' => 'dejavusans',
            'tempDir' => storage_path('app/mpdf/tmp'),
        ]);

        $mpdf->WriteHTML($html);

        $fileName = 'journal-report-' . now()->format('Y-m-d-H-i-s') . '.pdf';
        $path = storage_path('app/public/reports/' . $fileName);

        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 0777, true);
        }

        $mpdf->Output($path, 'F');

        $this->dispatch(
            'print-pdf',
            url: asset('storage/reports/' . $fileName)
        );

        session()->flash('message', 'گزارش با موفقیت تولید شد و آماده چاپ است.');
    }

    private function getFilteredTransactions()
    {
        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        $query = Journals::with(['customer', 'user'])
            ->where('admin_id', $adminId)
            ->when($this->transactionType, fn($query) => $query->where('type', $this->transactionType))
            ->when($this->accountType, fn($query) => $query->where('account_type', $this->accountType))
            ->when($this->selectedCustomer, fn($query) => $query->where('customer_id', $this->selectedCustomer))
            ->when($this->currency, fn($query) => $query->where('currency', $this->currency))
            ->when($this->search, function ($query) {
                $query->where(
                    fn($q) =>
                    $q->where('description', 'like', '%' . $this->search . '%')
                        ->orWhereHas(
                            'customer',
                            fn($c) =>
                            $c->where('fullname', 'like', '%' . $this->search . '%')
                                ->orWhere('account_number', 'like', '%' . $this->search . '%')
                        )
                );
            });

        // فیلتر بر اساس تاریخ - مستقیماً با تاریخ شمسی
        if ($this->fromDate) {
            $query->where('date', '>=', $this->fromDate);
        }

        if ($this->toDate) {
            $query->where('date', '<=', $this->toDate);
        }

        // اگر هیچ تاریخی انتخاب نشده، امروز را نمایش بده
        if (!$this->fromDate && !$this->toDate) {
            $todayJalali = Jalalian::now()->format('Y-m-d');
            $query->where('date', $todayJalali);
        }

        return $query->orderBy('date', 'desc')->orderBy('created_at', 'desc');
    }

    private function getSummaryData()
    {
        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        $query = Journals::query()
            ->leftJoin('customers', 'customers.id', '=', 'journal.customer_id')
            ->where('journal.admin_id', $adminId);

        // فیلترها
        if ($this->transactionType) {
            $query->where('journal.type', $this->transactionType);
        }

        if ($this->accountType) {
            $query->where('journal.account_type', $this->accountType);
        }

        if ($this->selectedCustomer) {
            $query->where('journal.customer_id', $this->selectedCustomer);
        }

        if ($this->currency) {
            $query->where('journal.currency', $this->currency);
        }

        // فیلتر تاریخ شمسی
        if ($this->fromDate) {
            $query->where('journal.date', '>=', $this->fromDate);
        }

        if ($this->toDate) {
            $query->where('journal.date', '<=', $this->toDate);
        }

        if (!$this->fromDate && !$this->toDate) {
            $todayJalali = Jalalian::now()->format('Y-m-d');
            $query->where('journal.date', $todayJalali);
        }

        return $query->selectRaw('
        journal.currency,

        /* ---------- نقدی ---------- */
        SUM(
            CASE 
                WHEN journal.type = "رسید"
                AND journal.account_type = "نقدی"
                THEN journal.amount
                ELSE 0
            END
        ) AS receipt_cash,

        SUM(
            CASE 
                WHEN journal.type = "برد"
                AND journal.account_type = "نقدی"
                THEN journal.amount
                ELSE 0
            END
        ) AS withdrawal_cash,

        /* ---------- بانکی (غیر کارت صرافی) ---------- */
        SUM(
            CASE 
                WHEN journal.type = "رسید"
                AND journal.account_type = "بانکی"
                AND (customers.type IS NULL OR customers.type <> "sarafi_card")
                THEN journal.amount
                ELSE 0
            END
        ) AS receipt_bank,

        SUM(
            CASE 
                WHEN journal.type = "برد"
                AND journal.account_type = "بانکی"
                AND (customers.type IS NULL OR customers.type <> "sarafi_card")
                THEN journal.amount
                ELSE 0
            END
        ) AS withdrawal_bank,

        /* ---------- بیلانس ---------- */
        (
            SUM(
                CASE 
                    WHEN journal.type = "رسید"
                    AND journal.account_type = "نقدی"
                    THEN journal.amount
                    ELSE 0
                END
            )
            -
            SUM(
                CASE 
                    WHEN journal.type = "برد"
                    AND journal.account_type = "نقدی"
                    THEN journal.amount
                    ELSE 0
                END
            )
        ) AS balance_cash,

        (
            SUM(
                CASE 
                    WHEN journal.type = "رسید"
                    AND journal.account_type = "بانکی"
                    AND (customers.type IS NULL OR customers.type <> "sarafi_card")
                    THEN journal.amount
                    ELSE 0
                END
            )
            -
            SUM(
                CASE 
                    WHEN journal.type = "برد"
                    AND journal.account_type = "بانکی"
                    AND (customers.type IS NULL OR customers.type <> "sarafi_card")
                    THEN journal.amount
                    ELSE 0
                END
            )
        ) AS balance_bank
    ')
            ->groupBy('journal.currency');
    }

    public function resetFilters()
    {
        $this->reset([
            'transactionType',
            'accountType',
            'selectedCustomer',
            'currency',
            'search'
        ]);

        // تنظیم مجدد تاریخ به امروز
        $todayJalali = Jalalian::now();
        $this->fromDate = $todayJalali->format('Y-m-d');
        $this->toDate = $todayJalali->format('Y-m-d');

        $this->resetPage();
    }

    public function updated()
    {
        $this->resetPage();
        $this->calculateBalances();
    }
}
