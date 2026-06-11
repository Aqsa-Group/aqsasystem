<?php

namespace App\Exports\Import;

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
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Cache-Control'       => 'no-cache, no-store, must-revalidate',
                'Pragma'              => 'no-cache',
                'Expires'             => '0'
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
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => 'inline; filename="preview.pdf"',
                'Cache-Control'       => 'no-cache, no-store, must-revalidate',
                'Pragma'              => 'no-cache',
                'Expires'             => '0'
            ]);
        } catch (\Exception $e) {
            throw new \Exception('خطا در نمایش PDF: ' . $e->getMessage());
        }
    }

    protected function createMpdf()
    {
        return new Mpdf([
            'mode'              => 'utf-8',
            'format'            => 'A4',
            'default_font_size' => 9,
            'default_font'      => 'dejavusanscondensed',
            'margin_left'       => 10,
            'margin_right'      => 10,
            'margin_top'        => 10,
            'margin_bottom'     => 10,
            'margin_header'     => 5,
            'margin_footer'     => 5,
            'autoScriptToLang'  => true,
            'autoLangToFont'    => true,
            'tempDir'           => storage_path('app/mpdf/tmp'),
        ]);
    }

    protected function generateHtml()
    {
        $reportTypes = [
            'withdraw_log'    => 'گزارش برداشت‌ها',
            'loan'            => 'گزارش قرضه ها شرکت حبیب یونس',
            'sell'            => 'گزارش فروش‌ها',
            'buy'             => 'گزارش خریدها',
            'transaction'     => 'گزارش تراکنش‌ها',
            'company_payment' => 'گزارش پرداخت شرکت',
        ];

        return view('exports.Import.general-report-pdf', [
            'data'       => $this->data,
            'reportType' => $this->reportType,
            'reportTitle'=> $reportTypes[$this->reportType] ?? 'گزارش نامشخص',
            'filters'    => $this->filters,
            'summary'    => $this->calculateSummary(),
        ])->render();
    }

    protected function calculateSummary()
    {
        // =============================================
        // گزارش قرض‌ها (CustomerStory)
        // =============================================
        if ($this->reportType === 'loan') {
            $totalBorrow  = 0;  // مجموع بردها
            $totalReceipt = 0;  // مجموع رسیدها

            foreach ($this->data as $item) {
                $amount = floatval($item->amount ?? 0);

                if ($item->type === 'برد') {
                    $totalBorrow += $amount;
                } elseif ($item->type === 'رسید') {
                    $totalReceipt += $amount;
                }
            }

            $remaining = $totalBorrow - $totalReceipt;

            return [
                'total_count'    => $this->data->count(),
                'total_borrow'   => $totalBorrow,
                'total_receipt'  => $totalReceipt,
                'remaining'      => $remaining,
            ];
        }

        // =============================================
        // سایر گزارش‌ها
        // =============================================
        $totalAmount = match ($this->reportType) {
            'withdraw_log'    => $this->data->sum('amount'),
            'sell'            => $this->data->sum('total_price'),
            'buy'             => $this->data->sum('total_price'),
            'transaction'     => $this->data->sum('amount'),
            'company_payment' => $this->data->sum('total_debt'),
            default           => 0
        };

        return [
            'total_count'  => $this->data->count(),
            'total_amount' => $totalAmount,
        ];
    }
}