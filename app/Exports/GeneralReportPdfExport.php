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
            'directionality' => 'rtl',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 10,
            'margin_bottom' => 10,
            'margin_header' => 5,
            'margin_footer' => 5,
            'fontDir' => array_merge(
                (new \Mpdf\Config\ConfigVariables())->getDefaults()['fontDir'],
                [public_path('fonts/vazir/')]
            ),
            'fontdata' => (new \Mpdf\Config\FontVariables())->getDefaults()['fontdata'] + [
                'vazir' => [

                    'R' => 'Vazir-Light.ttf',
                    'B' => 'Vazir-Bold.ttf',
                    'useOTL' => 0xFF,
                    'useKashida' => 75,
                ],
            ],
            'default_font' => 'vazir',
            'tempDir' => storage_path('app/mpdf'),
        ]);
    }
  protected function getSafeSummaryRows()
{
    $user = \Illuminate\Support\Facades\Auth::guard('market')->user();

    if (!$user) {
        return [];
    }

    $query = \Illuminate\Support\Facades\DB::connection('market')
        ->table('accountings')
        ->select('expanses_type', 'currency', DB::raw('SUM(paid) as total_paid'));

    if ($user->role !== 'superadmin') {
        $adminId = $user->role === 'admin'
            ? $user->id
            : $user->admin_id;

        $query->where('admin_id', $adminId);
    }

    $results = $query
        ->groupBy('expanses_type', 'currency')
        ->get();

    $grouped = $results->groupBy('expanses_type');

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
            'fund' => 'گزارش صندوق',

            'withdraw_log' => 'گزارش برداشت‌ها',
        ];

        $reportTitle = $reportTypes[$this->reportType] ?? 'گزارش نامشخص';

        $safeRows = $this->getSafeSummaryRows();

        // محاسبه summary
        $summary = $this->calculateSummary();

        return view('exports.general-report-pdf', [
            'data' => $this->data,
            'reportType' => $this->reportType,
            'reportTitle' => $reportTitle,
            'filters' => $this->filters,
            'summary' => $summary, // استفاده از summary محاسبه شده
            'safeRows' => $safeRows,
        ])->render();
    }

    protected function calculateSummary()
    {
        $totalAmount = 0;
        $accountingTotals = [];
        $currencyReceipts = [];
        $currencyWithdrawals = [];

        switch ($this->reportType) {
            case 'withdraw_salary':
                $totalAmount = $this->data->sum(function ($item) {
                    if (isset($item->record_type) && $item->record_type === 'withdraw') {
                        return (float)($item->amount ?? 0);
                    } else {
                        return (float)($item->paid ?? 0);
                    }
                });
                break;

            case 'accounting':
                $totalPrice = $this->data->sum('price');
                $totalPaid = $this->data->sum('paid');
                $totalRemained = $this->data->sum('remained');
                $totalAll = $this->data->sum(function ($item) {
                    return ($item->price ?? 0) + ($item->remained ?? 0);
                });

                $totalAmount = $totalPrice;
                $accountingTotals = [
                    'total_price' => $totalPrice,
                    'total_paid' => $totalPaid,
                    'total_remained' => $totalRemained,
                    'total_all' => $totalAll,
                ];
                break;

            case 'fund':
                $totalAmount = $this->data->sum('paid');
                foreach ($this->data as $item) {
                    $currency = $item->currency ?? 'نامشخص';
                    $paid = (float) ($item->paid ?? 0);
                    if ($paid > 0) {
                        $currencyReceipts[$currency] = ($currencyReceipts[$currency] ?? 0) + $paid;
                    } elseif ($paid < 0) {
                        $currencyWithdrawals[$currency] = ($currencyWithdrawals[$currency] ?? 0) + $paid;
                    }
                }
                // تبدیل مقادیر برداشت به مثبت برای نمایش
                foreach ($currencyWithdrawals as $cur => $val) {
                    $currencyWithdrawals[$cur] = abs($val);
                }
                break;

            case 'outside':
                $totalAmount = $this->data->sum('paid');
                break;

            case 'deposit':
                $totalAmount = $this->data->sum('price');
                break;

            case 'salary':
                $totalAmount = $this->data->sum('paid');
                break;

            case 'loan':
                $totalAmount = $this->data->sum('amount');
                break;

            case 'payment':
                $totalAmount = $this->data->sum('amount');
                break;

            case 'buy':
                $totalAmount = $this->data->sum('price');
                break;

            case 'sell':
                $totalAmount = $this->data->sum('price');
                break;

            case 'withdraw_log':
                $totalAmount = $this->data->sum('amount');
                break;

            default:
                $totalAmount = 0;
        }

        $currencyTotals = $this->calculateCurrencyTotals($this->data);

        return [
            'total_count' => $this->data->count(),
            'total_amount' => $totalAmount,
            'currency_totals' => $currencyTotals,
            'accounting_totals' => $accountingTotals,
            'report_type' => $this->getReportTypeLabel(),
            'current_date' => \Morilog\Jalali\Jalalian::now()->format('Y/m/d'),
            'fund_receipts' => $currencyReceipts,
            'fund_withdrawals' => $currencyWithdrawals,
        ];
    }


    protected function calculateCurrencyTotals($data)
    {
        $currencyTotals = [];

        foreach ($data as $item) {
            $currency = $item->currency ?? 'نامشخص';

            $amount = match ($this->reportType) {
                'withdraw_salary' => isset($item->record_type) && $item->record_type === 'withdraw' ?
                    $item->amount : ($item->paid ?? 0),
                'accounting' => $item->price ?? 0,
                'outside' => $item->paid ?? 0,
                'deposit' => $item->price ?? 0,
                'salary' => $item->paid ?? 0,
                'loan' => $item->amount ?? 0,
                'payment' => $item->amount ?? 0,
                'fund' => $item->paid ?? 0,
                'buy' => $item->price ?? 0,
                'sell' => $item->price ?? 0,
                'withdraw_log' => $item->amount ?? 0,
                default => 0
            };

            if (!isset($currencyTotals[$currency])) {
                $currencyTotals[$currency] = 0;
            }

            $currencyTotals[$currency] += $amount;
        }

        return $currencyTotals;
    }

    private function getReportTypeLabel()
    {
        $types = [
            'withdraw_salary' => 'برداشت‌ها و معاش کارمندان',
            'accounting' => 'حسابداری',
            'outside' => 'عواید بیرونی',
            'salary' => 'معاش کارمندان',
            'deposit' => 'تسویه نشده‌ها',
            'loan' => 'بردگی‌ها',
            'payment' => 'رسیدها',
            'buy' => 'خریدها',
            'sell' => 'فروش‌ها',
            'withdraw_log' => 'برداشت‌ها',
        ];

        return $types[$this->reportType] ?? 'نامشخص';
    }
}
