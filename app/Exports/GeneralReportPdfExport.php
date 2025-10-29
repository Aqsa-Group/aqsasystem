<?php

namespace App\Exports;

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
        // تنظیمات ساده‌تر برای mPDF
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

    protected function generateHtml()
    {
        $reportTypes = [
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

        return view('exports.general-report-pdf', [
            'data' => $this->data,
            'reportType' => $this->reportType,
            'reportTitle' => $reportTypes[$this->reportType] ?? 'گزارش نامشخص',
            'filters' => $this->filters,
            'summary' => $this->calculateSummary(),
        ])->render();
    }

    protected function calculateSummary()
    {
        $totalAmount = match($this->reportType) {
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