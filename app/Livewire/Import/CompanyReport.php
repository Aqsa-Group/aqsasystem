<?php

namespace App\Livewire\Import;

use Livewire\Component;
use App\Models\Import\Buy;
use App\Models\Import\Company;
use Carbon\Carbon;
use Mpdf\Mpdf;
use Morilog\Jalali\Jalalian;
use Illuminate\Support\Facades\Log;

class CompanyReport extends Component
{
    public $company_id = '';
    public $from_date = '';
    public $to_date = '';
    public $reportData = [];
    public $totalAll = [
        'total_price' => 0,
        'paid'        => 0,
        'remaining'   => 0,
    ];
    public $message = '';

    protected $rules = [
        'company_id' => 'nullable|exists:import.company,id',
        'from_date'  => 'nullable|date_format:Y/m/d',
        'to_date'    => 'nullable|date_format:Y/m/d|after_or_equal:from_date',
    ];

    public function updated($property)
    {
        $this->validateOnly($property);
        // بعد از اعتبارسنجی، گزارش را دوباره بارگذاری می‌کنیم
        $this->loadReport();
    }

    /**
     * بارگذاری گزارش بر اساس فیلترهای فعلی
     */
    protected function loadReport()
    {
        $query = Buy::with('company')->whereNotNull('company_id');

        if (!empty($this->company_id)) {
            $query->where('company_id', $this->company_id);
        }

        // فیلتر بر اساس created_at
        if (!empty($this->from_date)) {
            try {
                $from = Jalalian::fromFormat('Y/m/d', $this->from_date)->toCarbon()->startOfDay();
                $query->where('created_at', '>=', $from);
            } catch (\Exception $e) {
                $this->message = 'تاریخ از تاریخ نامعتبر است.';
                $this->reportData = [];
                $this->totalAll = ['total_price' => 0, 'paid' => 0, 'remaining' => 0];
                return;
            }
        }

        if (!empty($this->to_date)) {
            try {
                $to = Jalalian::fromFormat('Y/m/d', $this->to_date)->toCarbon()->endOfDay();
                $query->where('created_at', '<=', $to);
            } catch (\Exception $e) {
                $this->message = 'تاریخ تا تاریخ نامعتبر است.';
                $this->reportData = [];
                $this->totalAll = ['total_price' => 0, 'paid' => 0, 'remaining' => 0];
                return;
            }
        }

        $buys = $query->orderBy('company_id')->orderBy('created_at', 'desc')->get();

        if ($buys->isEmpty()) {
            $this->message = 'هیچ خریدی در بازه تاریخ انتخاب شده یافت نشد.';
            $this->reportData = [];
            $this->totalAll = ['total_price' => 0, 'paid' => 0, 'remaining' => 0];
            return;
        }

        $grouped = $buys->groupBy('company_id')->map(function ($items) {
            $company = $items->first()->company;
            return [
                'company' => $company,
                'items'   => $items,
                'totals'  => [
                    'total_price' => $items->sum('total_price'),
                    'paid'        => $items->sum('paid'),
                    'remaining'   => $items->sum('remaining'),
                ]
            ];
        });

        $this->reportData = $grouped->values()->toArray();
        $this->totalAll['total_price'] = $buys->sum('total_price');
        $this->totalAll['paid']        = $buys->sum('paid');
        $this->totalAll['remaining']   = $buys->sum('remaining');
        $this->message = '';
    }

    public function printPdf()
    {
        try {
            // اگر داده‌ای وجود نداشته باشد، ابتدا بارگذاری می‌کنیم
            if (empty($this->reportData)) {
                $this->loadReport();
            }

            if (empty($this->reportData)) {
                session()->flash('error', 'هیچ داده‌ای برای چاپ وجود ندارد.');
                return;
            }

            $html = view('pdf.company-report', [
                'reportData' => $this->reportData,
                'totalAll'   => $this->totalAll,
                'fromDate'   => $this->from_date,
                'toDate'     => $this->to_date,
            ])->render();

            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4',
                'directionality' => 'rtl',
                'margin_top' => 15,
                'margin_bottom' => 15,
                'margin_left' => 15,
                'margin_right' => 15,
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

            $mpdf->SetAutoPageBreak(true, 15);
            $mpdf->WriteHTML($html);

            $fileName = 'company_report_' . now()->format('YmdHis') . '.pdf';
            $path = storage_path('app/public/' . $fileName);

            if (file_exists($path)) {
                unlink($path);
            }

            $mpdf->Output($path, 'F');

            if (!file_exists($path)) {
                throw new \Exception('فایل PDF ایجاد نشد.');
            }

            $this->dispatch('print-pdf', url: asset('storage/' . $fileName) . '?t=' . time());

        } catch (\Throwable $e) {
            Log::error('Company Report PDF Error', [
                'message' => $e->getMessage(),
                'line'    => $e->getLine(),
                'file'    => $e->getFile(),
            ]);
            session()->flash('error', 'خطا در ایجاد PDF: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $companies = Company::orderBy('name')->get();
        // بارگذاری گزارش در هر بار رندر (اولیه و پس از تغییرات)
        $this->loadReport();
        return view('livewire.import.company-report', compact('companies'));
    }
}