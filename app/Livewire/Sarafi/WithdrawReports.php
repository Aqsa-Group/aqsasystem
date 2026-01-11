<?php

namespace App\Livewire\Sarafi;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Sarafi\Withdraws;
use App\Models\Sarafi\Staffs;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Mpdf\Mpdf;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;

class WithdrawReports extends Component
{
    use WithPagination;

    // فیلترها
    public $staff = '';
    public $expanses_type = '';
    public $currency = '';
    public $fromDate = '';
    public $toDate = '';

    // متغیرهای صفحه‌بندی
    public $perPage = 25;

    // ارزها
    public $currencies = [
        'AFN' => 'افغانی',
        'USD' => 'دالر',
        'EUR' => 'یورو',
        'GBP' => 'پوند',
        'PKR' => 'روپیه',
        'IRR' => 'تومان',
    ];

    // لیست کارمندان
    public $staffList = [];

    // انواع هزینه
    public $expansesTypes = [];

    public function mount()
    {
        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;
        // گرفتن لیست تمام کارمندان مرتبط با ادمین جاری
        $this->staffList = Staffs::where('admin_id', $adminId)
            ->select('id', 'name')
            ->orderBy('name')
            ->get()
            ->map(function ($staff) {
                return [
                    'id' => $staff->id,
                    'name' => $staff->name,
                ];
            })->toArray();

        // گرفتن انواع هزینه منحصر به فرد برای این ادمین
        $this->expansesTypes = Withdraws::where('admin_id', $adminId)
            ->distinct('expanses_type')
            ->whereNotNull('expanses_type')
            ->where('expanses_type', '!=', '')
            ->orderBy('expanses_type')
            ->pluck('expanses_type')
            ->toArray();
    }

    public function render()
    {
        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;
        // کوئری پایه برای ادمین جاری
        $query = Withdraws::where('admin_id', $adminId);

        // اعمال فیلتر کارمند
        if ($this->staff) {
            $query->where('staff_id', $this->staff);
        }

        // اعمال فیلتر نوع هزینه
        if ($this->expanses_type) {
            $query->where('expanses_type', $this->expanses_type);
        }

        // اعمال فیلتر ارز
        if ($this->currency) {
            $query->where('currency', $this->currency);
        }

        // اعمال فیلتر تاریخ از
        if ($this->fromDate) {
            $query->where('date', '>=', $this->fromDate);
        }

        // اعمال فیلتر تاریخ تا
        if ($this->toDate) {
            $query->where('date', '<=', $this->toDate);
        }

        // مرتب‌سازی بر اساس تاریخ
        $query->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc');

        // گرفتن تراکنش‌ها با صفحه‌بندی
        $transactions = $query->paginate($this->perPage);

        // محاسبه خلاصه گزارش بر اساس ارز (از همه داده‌ها با همان فیلترها)
        $summaryQuery = Withdraws::where('admin_id', $adminId);

        if ($this->staff) {
            $summaryQuery->where('staff_id', $this->staff);
        }
        if ($this->expanses_type) {
            $summaryQuery->where('expanses_type', $this->expanses_type);
        }
        if ($this->currency) {
            $summaryQuery->where('currency', $this->currency);
        }
        if ($this->fromDate) {
            $summaryQuery->where('date', '>=', $this->fromDate);
        }
        if ($this->toDate) {
            $summaryQuery->where('date', '<=', $this->toDate);
        }

        $summaryData = $summaryQuery->get();

        $summary = collect();

        // گروه‌بندی بر اساس ارز
        $groupedByCurrency = $summaryData->groupBy('currency');

        foreach ($groupedByCurrency as $currencyCode => $currencyTransactions) {
            $totalAmount = $currencyTransactions->sum('amount');
            $transactionCount = $currencyTransactions->count();

            $summary->push((object)[
                'currency_fa' => $this->currencies[$currencyCode] ?? $currencyCode,
                'total_amount' => $totalAmount,
                'transaction_count' => $transactionCount,
                'avg_amount' => $transactionCount > 0 ? round($totalAmount / $transactionCount, 2) : 0,
            ]);
        }

        return view('livewire.sarafi.withdraw-reports', [
            'transactions' => $transactions,
            'summary' => $summary,
        ]);
    }

    public function resetFilters()
    {
        $this->reset([
            'staff',
            'expanses_type',
            'currency',
            'fromDate',
            'toDate',
        ]);
        $this->resetPage();
    }

    public function printReport()
{
    $user = Auth::guard('sarafi')->user();
    $adminId = $user->admin_id ?? $user->id;
    
    // دریافت داده‌های فیلتر شده برای PDF با بارگذاری رابطه staff
    $query = Withdraws::where('admin_id', $adminId)
        ->with(['staff' => function($q) {
            $q->select('id', 'name');
        }]);

    if ($this->staff) {
        $query->where('staff_id', $this->staff);
    }
    if ($this->expanses_type) {
        $query->where('expanses_type', $this->expanses_type);
    }
    if ($this->currency) {
        $query->where('currency', $this->currency);
    }
    if ($this->fromDate) {
        $query->where('date', '>=', $this->fromDate);
    }
    if ($this->toDate) {
        $query->where('date', '<=', $this->toDate);
    }

    $transactions = $query->orderBy('date', 'desc')->get();

    // محاسبه خلاصه
    $summaryData = Withdraws::where('admin_id', $adminId);

    if ($this->staff) {
        $summaryData->where('staff_id', $this->staff);
    }
    if ($this->expanses_type) {
        $summaryData->where('expanses_type', $this->expanses_type);
    }
    if ($this->currency) {
        $summaryData->where('currency', $this->currency);
    }
    if ($this->fromDate) {
        $summaryData->where('date', '>=', $this->fromDate);
    }
    if ($this->toDate) {
        $summaryData->where('date', '<=', $this->toDate);
    }

    $summaryData = $summaryData->get();

    $summary = collect();
    $groupedByCurrency = $summaryData->groupBy('currency');

    foreach ($groupedByCurrency as $currencyCode => $currencyTransactions) {
        $totalAmount = $currencyTransactions->sum('amount');
        $transactionCount = $currencyTransactions->count();

        $summary->push((object)[
            'currency_fa' => $this->currencies[$currencyCode] ?? $currencyCode,
            'total_amount' => $totalAmount,
            'transaction_count' => $transactionCount,
            'avg_amount' => $transactionCount > 0 ? round($totalAmount / $transactionCount, 2) : 0,
        ]);
    }

    // اطلاعات کارمند انتخاب شده
    $staffName = '';
    if ($this->staff) {
        $staff = Staffs::find($this->staff);
        $staffName = $staff ? $staff->name : '';
    }

    // تنظیمات mPDF
    $defaultConfig = (new ConfigVariables())->getDefaults();
    $fontDirs = $defaultConfig['fontDir'];

    $defaultFontConfig = (new FontVariables())->getDefaults();
    $fontData = $defaultFontConfig['fontdata'];

    $mpdf = new Mpdf([
        'mode' => 'utf-8',
        'format' => 'A4-L',
        'default_font_size' => 10,
        'default_font' => 'dejavusans',
        'margin_left' => 15,
        'margin_right' => 15,
        'margin_top' => 25,
        'margin_bottom' => 20,
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

   

    // داده‌های ارسالی به view
    $data = [
        'transactions' => $transactions,
        'summary' => $summary,
        'staffName' => $staffName,
        'filters' => [
            'staff' => $this->staff,
            'expanses_type' => $this->expanses_type,
            'currency' => $this->currency,
            'fromDate' => $this->fromDate,
            'toDate' => $this->toDate,
        ],
        'reportDate' => now()->format('Y/m/d H:i'),
        'currencies' => $this->currencies,
    ];

    // رندر view و تبدیل به HTML
    $html = view('pdf.Sarafi.withdraw-report', $data)->render();

    // نوشتن HTML در PDF
    $mpdf->WriteHTML($html);

    // ذخیره فایل
    $fileName = 'withdraw-report-' . now()->format('Y-m-d-H-i-s') . '.pdf';
    $path = storage_path('app/public/reports/' . $fileName);

    // اطمینان از وجود دایرکتوری
    if (!file_exists(dirname($path))) {
        mkdir(dirname($path), 0777, true);
    }

    // ذخیره PDF
    $mpdf->Output($path, 'F');

    // ارسال event به JS
    $this->dispatch(
        'print-pdf',
        url: asset('storage/reports/' . $fileName)
    );

    // پیام موفقیت
    session()->flash('message', 'گزارش با موفقیت تولید شد و برای چاپ آماده است.');
}

    // متد برای گرفتن نام کارمند
    public function getStaffName($transaction)
    {
        if (!$transaction || !$transaction->staff_id) {
            return 'نامشخص';
        }

        $staff = collect($this->staffList)->firstWhere('id', $transaction->staff_id);
        return $staff ? $staff['name'] : 'نامشخص';
    }
}
