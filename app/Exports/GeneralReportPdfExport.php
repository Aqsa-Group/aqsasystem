<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Mpdf\Mpdf;

class GeneralReportPdfExport
{
    protected $data;
    protected $reportType;
    protected $filters;

    public function __construct($data, $reportType, $filters = [])
    {
        $this->data = $data;
        $this->reportType = $reportType;
        $this->filters = $filters;
    }

    public function download()
    {
        try {
            $mpdf = $this->createMpdf();
            $html = $this->generateHtml();
            $mpdf->WriteHTML($html);

            $filename = 'general_report_' . $this->reportType . '_' . now()->format('Y_m_d') . '.pdf';

            return response($mpdf->Output('', 'S'), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0'
            ]);
        } catch (\Exception $e) {
            throw new \Exception('خطا در تولید PDF: ' . $e->getMessage());
        }
    }

    public function stream()
    {
        try {
            $mpdf = $this->createMpdf();
            $html = $this->generateHtml();
            $mpdf->WriteHTML($html);

            return response($mpdf->Output('', 'S'), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="preview.pdf"',
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0'
            ]);
        } catch (\Exception $e) {
            throw new \Exception('خطا در نمایش PDF: ' . $e->getMessage());
        }
    }

    protected function createMpdf()
    {
        return new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'default_font_size' => 9,
            'default_font' => 'dejavusanscondensed',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 10,
            'margin_bottom' => 10,
            'margin_header' => 5,
            'margin_footer' => 5,
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
        ]);
    }
protected function getSafeSummaryRows()
{
    $user = \Illuminate\Support\Facades\Auth::guard('market')->user();

    if (!$user) {
        return []; 
    }

    // اگر admin_id نداشت، از id خودش استفاده می‌کنیم
    $adminId = $user->admin_id ?? $user->id;

    $query = \Illuminate\Support\Facades\DB::connection('market')
        ->table('accountings')
        ->select('expanses_type', 'currency', \Illuminate\Support\Facades\DB::raw('SUM(paid) as total_paid'));

    // 🔹 اگر نقش superadmin نیست، فقط اطلاعات مربوط به adminId خودش را ببیند
    if ($user->role !== 'superadmin') {
        $query->where('admin_id', $adminId);
    }

    // 🔹 فیلترهای تاریخ
    if (!empty($this->filters['from_date'])) {
        $query->whereDate('created_at', '>=', $this->filters['from_date']);
    }

    if (!empty($this->filters['to_date'])) {
        $query->whereDate('created_at', '<=', $this->filters['to_date']);
    }

    // 🔹 فیلتر نوع صندوق (در صورت وجود)
    if (!empty($this->filters['expanses_type'])) {
        $query->where('expanses_type', $this->filters['expanses_type']);
    }

    // 🔹 فیلتر نوع ارز (در صورت وجود)
    if (!empty($this->filters['currency'])) {
        $query->where('currency', $this->filters['currency']);
    }

    $data = $query->groupBy('expanses_type', 'currency')->get();

    // 🔹 گروه‌بندی داده‌ها
    $grouped = $data->groupBy('expanses_type');

    $rows = [];
    foreach ($grouped as $type => $group) {
        $rows[] = [
            'type' => $type,
            'af' => $group->firstWhere('currency', 'AFN')?->total_paid ?? 0,
            'us' => $group->firstWhere('currency', 'USD')?->total_paid ?? 0,
            'er' => $group->firstWhere('currency', 'EUR')?->total_paid ?? 0,
            'ir' => $group->firstWhere('currency', 'IRR')?->total_paid ?? 0,
        ];
    }

    return $rows;
}


protected function generateHtml()
{
    // عنوان گزارش بر اساس نوع گزارش
    $reportTypes = [
        'withdraw_salary' => 'گزارش برداشت‌ها و معاش کارمندان',
        'accounting' => 'گزارش حسابداری',
        'outside' => 'گزارش عواید بیرونی',
        'deposit' => 'گزارش تسویه نشده‌ها',
        'salary' => 'گزارش معاش کارمندان',
        'loan' => 'گزارش بردگی‌ها',
        'payment' => 'گزارش رسیدها',
        'buy' => 'گزارش خریدها',
        'sell' => 'گزارش فروش‌ها',
        'withdraw_log' => 'گزارش برداشت‌ها',
    ];

    $reportTitle = $reportTypes[$this->reportType] ?? 'گزارش نامشخص';

    // گرفتن موجودی صندوق با فیلترها
    $safeRows = $this->getSafeSummaryRows();

    // بازگشت HTML رندر شده
    return view('exports.general-report-pdf', [
        'data' => $this->data,
        'reportType' => $this->reportType,
        'reportTitle' => $reportTitle,
        'filters' => $this->filters,
        'summary' => $this->calculateSummary(),
        'safeRows' => $safeRows,
    ])->render();
}


    protected function calculateSummary()
    {
       $totalAmount = match ($this->reportType) {
    'withdraw_salary' => $this->data->sum(function ($item) {
        $amount = (float) ($item->amount ?? 0);
        $paid   = (float) ($item->paid ?? 0);

        // اگر نوع رکورد "برداشت" است، فقط amount را حساب نکن، بلکه همه را جمع کن
        if (isset($item->record_type) && $item->record_type === 'برداشت') {
            return $amount + $paid ;
        }

        // در سایر موارد هم همین‌طور همه مقادیر غیر تهی را جمع کن
        return $amount + $paid;
    }),

            'accounting' => $this->data->sum('price'),
            'outside' => $this->data->sum('paid'),
            'deposit' => $this->data->sum('price'),
            'salary' => $this->data->sum('salary'),
            'loan' => $this->data->sum('amount'),
            'payment' => $this->data->sum('amount'),
            'buy' => $this->data->sum('price'),
            'sell' => $this->data->sum('price'),
            'withdraw_log' => $this->data->sum('amount'),
            default => 0
        };

        return [
            'total_count' => $this->data->count(),
            'total_amount' => $totalAmount,
        ];
    }
}
