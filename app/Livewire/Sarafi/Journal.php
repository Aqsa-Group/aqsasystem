<?php

namespace App\Livewire\Sarafi;

use App\Models\Sarafi\BankAccount;
use App\Models\Sarafi\CurrencySafe;
use App\Models\Sarafi\Customer;
use App\Models\Sarafi\Journals;
use App\Models\Sarafi\Revenue;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

    // موجودی اولیه
    private $initialCurrencySafe = [];
    private $initialBankAccount = [];

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

        // بارگیری موجودی اولیه
        $this->loadInitialBalances();
        
        // محاسبه موجودی‌ها
        $this->calculateBalances();
    }

    // بارگیری موجودی اولیه از دیتابیس
    private function loadInitialBalances()
    {
        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        $currencySafe = CurrencySafe::where('admin_id', $adminId)->first();
        $bankAccount = BankAccount::where('admin_id', $adminId)->first();

        foreach ($this->currencies as $code => $name) {
            $this->initialCurrencySafe[$code] = $currencySafe ? ($currencySafe->{$code} ?? 0) : 0;
            $this->initialBankAccount[$code] = $bankAccount ? ($bankAccount->{$code} ?? 0) : 0;
        }
    }

    // محاسبه موجودی تا تاریخ مشخص
    public function calculateBalancesForDate($date = null)
    {
        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        // اگر تاریخ مشخص نشده، از تاریخ فیلتر استفاده کن
        $targetDate = $date ?: $this->toDate;
        if (!$targetDate) {
            $targetDate = Jalalian::now()->format('Y-m-d');
        }

        // مقداردهی اولیه با موجودی اولیه
        $currencySafeBalance = $this->initialCurrencySafe;
        $bankAccountBalance = $this->initialBankAccount;
        $totalBalanceByCurrency = [];

        // محاسبه تغییرات برای هر ارز
        foreach ($this->currencies as $code => $name) {
            // محاسبه تغییرات نقدی تا تاریخ مورد نظر
            $cashTransactions = Journals::where('admin_id', $adminId)
                ->where('currency', $code)
                ->where('account_type', 'نقدی')
                ->where('date', '<=', $targetDate)
                ->get();

            // محاسبه تغییرات بانکی تا تاریخ مورد نظر
            $bankTransactions = Journals::where('admin_id', $adminId)
                ->where('currency', $code)
                ->where('account_type', 'بانکی')
                ->where('date', '<=', $targetDate)
                ->get();

            // محاسبه مجموع تراکنش‌های نقدی
            $cashChange = 0;
            foreach ($cashTransactions as $transaction) {
                if ($transaction->type == 'رسید') {
                    $cashChange += $transaction->amount;
                } else {
                    $cashChange -= $transaction->amount;
                }
            }

            // محاسبه مجموع تراکنش‌های بانکی
            $bankChange = 0;
            foreach ($bankTransactions as $transaction) {
                if ($transaction->type == 'رسید') {
                    $bankChange += $transaction->amount;
                } else {
                    $bankChange -= $transaction->amount;
                }
            }

            // موجودی نهایی = موجودی اولیه + تغییرات
            $currencySafeBalance[$code] = ($this->initialCurrencySafe[$code] ?? 0) + $cashChange;
            $bankAccountBalance[$code] = ($this->initialBankAccount[$code] ?? 0) + $bankChange;
            $totalBalanceByCurrency[$code] = $currencySafeBalance[$code] + $bankAccountBalance[$code];
        }

        return [
            'currencySafeBalance' => $currencySafeBalance,
            'bankAccountBalance' => $bankAccountBalance,
            'totalBalanceByCurrency' => $totalBalanceByCurrency,
        ];
    }

    // محاسبه موجودی فعلی (امروز)
    public function calculateCurrentBalances()
    {
        $today = Jalalian::now()->format('Y-m-d');
        return $this->calculateBalancesForDate($today);
    }

    // تابع اصلی که در mount و updated صدا زده می‌شود
    public function calculateBalances()
    {
        $balances = $this->calculateBalancesForDate($this->toDate);
        
        $this->currencySafeBalance = $balances['currencySafeBalance'];
        $this->bankAccountBalance = $balances['bankAccountBalance'];
        $this->totalBalanceByCurrency = $balances['totalBalanceByCurrency'];
    }

    public function render()
    {
        $transactions = $this->getFilteredTransactions()->paginate($this->perPage);
        $summary = $this->getSummaryData()->get();
        $customers = Customer::select('id', 'fullname', 'account_number', 'phone')->orderBy('fullname')->get();

        // محاسبه موجودی بر اساس تاریخ فیلتر
        $balances = $this->calculateBalancesForDate($this->toDate);

        // محاسبه سود و ضرر برای بازه تاریخ فیلتر
        $timezone = 'Asia/Kabul';
        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        $profitLoss = $this->calculateProfitLossForDateRange();
        $todayprofit = $profitLoss['profit'];
        $todaylost = $profitLoss['loss'];

        return view('livewire.sarafi.journal', [
            'transactions' => $transactions,
            'summary' => $summary,
            'customers' => $customers,
            'currencies' => $this->currencies,
            'currencySafeBalance' => $balances['currencySafeBalance'],
            'bankAccountBalance' => $balances['bankAccountBalance'],
            'totalBalanceByCurrency' => $balances['totalBalanceByCurrency'],
            'todayprofit' => $todayprofit,
            'todaylost' => $todaylost,
        ]);
    }

    // محاسبه سود و ضرر برای بازه تاریخ فیلتر
    private function calculateProfitLossForDateRange()
    {
        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        // اگر تاریخ فیلتر مشخص است، از آن استفاده کن
        if ($this->fromDate && $this->toDate) {
            $fromDate = Jalalian::fromFormat('Y-m-d', $this->fromDate)->toCarbon();
            $toDate = Jalalian::fromFormat('Y-m-d', $this->toDate)->toCarbon()->endOfDay();

            $profit = Revenue::where('admin_id', $adminId)
                ->whereBetween('created_at', [$fromDate, $toDate])
                ->sum('profit');

            $loss = Revenue::where('admin_id', $adminId)
                ->whereBetween('created_at', [$fromDate, $toDate])
                ->sum('lost');
        } else {
            // اگر تاریخ فیلتر مشخص نیست، امروز را حساب کن
            $today = Carbon::now('Asia/Kabul')->startOfDay();
            $tomorrow = Carbon::now('Asia/Kabul')->addDay()->startOfDay();

            $profit = Revenue::where('admin_id', $adminId)
                ->whereBetween('created_at', [$today, $tomorrow])
                ->sum('profit');

            $loss = Revenue::where('admin_id', $adminId)
                ->whereBetween('created_at', [$today, $tomorrow])
                ->sum('lost');
        }

        return [
            'profit' => $profit ?? 0,
            'loss' => $loss ?? 0,
        ];
    }

    public function printReport()
    {
        // دریافت داده‌های فیلتر شده برای PDF
        $transactions = $this->getFilteredTransactions()->get();
        $summary = $this->getSummaryData()->get();
        
        // محاسبه سود و ضرر برای بازه تاریخ فیلتر
        $profitLoss = $this->calculateProfitLossForDateRange();
        $todayProfit = $profitLoss['profit'];
        $todayLoss = $profitLoss['loss'];

        // اطلاعات مشتری انتخاب شده
        $customerName = '';
        $customerAccount = '';
        if ($this->selectedCustomer) {
            $customer = Customer::find($this->selectedCustomer);
            $customerName = $customer ? $customer->fullname : '';
            $customerAccount = $customer ? $customer->account_number : '';
        }

        // تنظیمات mPDF
        $defaultConfig = (new ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'default_font_size' => 9,
            'default_font' => 'dejavusans',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 15,
            'margin_bottom' => 15,
            'margin_header' => 5,
            'margin_footer' => 5,
            'orientation' => 'L',
            'directionality' => 'rtl',
            'fontDir' => array_merge($fontDirs, [
                public_path('fonts'),
                storage_path('fonts'),
            ]),
            'fontdata' => $fontData + [
                'dejavusans' => [
                    'R' => 'DejaVuSans.ttf',
                    'B' => 'DejaVuSans-Bold.ttf',
                    'I' => 'DejaVuSans-Oblique.ttf',
                    'BI' => 'DejaVuSans-BoldOblique.ttf',
                ]
            ],
            'tempDir' => storage_path('app/mpdf/tmp'),
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
            'autoArabic' => true,
        ]);

        // محاسبه موجودی‌ها برای PDF - بر اساس تاریخ فیلتر
        $balances = $this->calculateBalancesForDate($this->toDate);

        // داده‌های ارسالی به view
        $data = [
            'transactions' => $transactions,
            'summary' => $summary,
            'customerName' => $customerName,
            'customerAccount' => $customerAccount,
            'currencies' => $this->currencies,
            'currencySafeBalance' => $balances['currencySafeBalance'],
            'bankAccountBalance' => $balances['bankAccountBalance'],
            'totalBalanceByCurrency' => $balances['totalBalanceByCurrency'],
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

        // رندر view و تبدیل به HTML
        $html = view('pdf.Sarafi.journal', $data)->render();

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

        session()->flash('message', 'گزارش با موفقیت تولید شد و برای چاپ آماده است.');
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

        $query = Journals::where('admin_id', $adminId);

        // فیلترها
        if ($this->transactionType) $query->where('type', $this->transactionType);
        if ($this->accountType) $query->where('account_type', $this->accountType);
        if ($this->selectedCustomer) $query->where('customer_id', $this->selectedCustomer);
        if ($this->currency) $query->where('currency', $this->currency);

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

        return $query->selectRaw('
            currency,
            SUM(CASE WHEN type = "رسید" AND account_type = "نقدی" THEN amount ELSE 0 END) as receipt_cash,
            SUM(CASE WHEN type = "برد" AND account_type = "نقدی" THEN amount ELSE 0 END) as withdrawal_cash,
            SUM(CASE WHEN type = "رسید" AND account_type = "بانکی" THEN amount ELSE 0 END) as receipt_bank,
            SUM(CASE WHEN type = "برد" AND account_type = "بانکی" THEN amount ELSE 0 END) as withdrawal_bank,
            SUM(CASE WHEN type = "رسید" AND account_type = "نقدی" THEN amount ELSE 0 END) - 
            SUM(CASE WHEN type = "برد" AND account_type = "نقدی" THEN amount ELSE 0 END) as balance_cash,
            SUM(CASE WHEN type = "رسید" AND account_type = "بانکی" THEN amount ELSE 0 END) - 
            SUM(CASE WHEN type = "برد" AND account_type = "بانکی" THEN amount ELSE 0 END) as balance_bank
        ')
            ->groupBy('currency');
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