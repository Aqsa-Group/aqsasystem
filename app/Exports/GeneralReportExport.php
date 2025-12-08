<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GeneralReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithEvents
{
    protected $data;
    protected $reportType;
    protected $totalPaid = 0;
    protected $totalRemained = 0;
    protected $totalAll = 0;

    public function __construct($data, $reportType)
    {
        $this->data = $data;
        $this->reportType = $reportType;
    }

    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        return match ($this->reportType) {
            'withdraw_salary' => [
                'نوع',
                'برداشت از',
                'شخص',
                'مبلغ',
                'واحد پول',
                'تاریخ',
                'توضیحات'
            ],
            'salary' => [
                'مارکت',
                'کارمند',
                'حقوق',
                'پرداخت شده',
                'باقی مانده',
                'قرضه',
                'واحد پول',
                'تاریخ پرداخت',
                'وضعیت کسر'
            ],
            'accounting' => [
                'مارکت',
                'نوع مصرف',
                'نوع',
                'نمبر غرفه/دوکان',
                'طبقه',
                'مشتری',
                'درجه فعلی',
                'درجه قبلی',
                'مقدار مصرف',
                'قیمت فی کیلووات',
                'مبلغ قابل تأدیه',
                'پرداخت شده',
                'باقیات',
                'جمع کل',
                'از تاریخ',
                'تا تاریخ'
            ],
            'outside' => [
                'مارکت',
                'نوع شخص',
                'نام شخص',
                'مبلغ',
                'واحد پول',
                'تاریخ',
                'توضیحات'
            ],
            'deposit' => [
                'مارکت',
                'دوکاندار',
                'نوع هزینه',
                'مبلغ کل',
                'پرداخت شده',
                'باقی مانده',
                'تاریخ پرداخت'
            ],
            'loan' => [
                'مارکت',
                'نوع شخص',
                'نام شخص',
                'مبلغ اصلی',
                'پرداخت شده',
                'باقی مانده',
                'تاریخ'
            ],
            'payment' => [
                'کد قرضه',
                'مبلغ پرداخت',
                'واحد پول',
                'تاریخ رسید',
                'توضیحات'
            ],
            'buy' => [
                'مارکت',
                'فروشنده',
                'نوع خرید',
                'قیمت خرید',
                'واحد پول',
                'تاریخ ثبت'
            ],
            'sell' => [
                'مارکت',
                'مشتری',
                'نوع ملک',
                'قیمت فروش',
                'واحد پول',
                'تاریخ',
                'جزئیات'
            ],
            'withdraw_log' => [
                'نوع هزینه',
                'دریافت کننده',
                'مبلغ',
                'واحد پول',
                'توضیحات',
                'تاریخ ثبت'
            ],
            default => []
        };
    }

    public function map($report): array
    {
        // Translate currency
        $currency = match ($report->currency ?? null) {
            'AFN' => 'افغانی',
            'USD' => 'دالر',
            'EUR' => 'یورو',
            default => $report->currency ?? '-'
        };

        return match ($this->reportType) {
            'withdraw_salary' => [
                $report->record_type === 'withdraw' ? 'برداشت' : 'معاش',
                $report->record_type === 'withdraw'
                    ? ($report->expanses_type ?? '-')
                    : ($report->reduce_from ?? '-'),
                $report->staff->fullname
                    ?? $report->customer->fullname
                    ?? $report->shopkeeper->fullname
                    ?? '-',
                $report->record_type === 'withdraw'
                    ? ($report->amount ?? 0)
                    : ($report->paid ?? 0),
                $currency,
                $report->record_type === 'withdraw'
                    ? ($report->created_at ? \Morilog\Jalali\Jalalian::fromDateTime($report->created_at)->format('Y/m/d') : '-')
                    : ($report->paid_date ? \Morilog\Jalali\Jalalian::fromDateTime($report->paid_date)->format('Y/m/d') : '-'),
                $report->description ?? '-'
            ],
            'accounting' => [
                // مارکت
                $report->market->name ?? '-',

                // نوع مصرف
                $report->expanses_type,

                // نوع (دوکان یا غرفه)
                $report->type,

                // نمبر غرفه/دوکان
                $report->shop->number ?? $report->booth->number ?? '—',

                // طبقه
                $report->shop->floor ?? $report->booth->floor ?? '—',

                // مشتری
                $report->shopkeeper->fullname ?? '-',

                // درجه فعلی
                $report->current_degree ?? '-',

                // درجه قبلی
                $report->past_degree ?? '-',

                // مقدار مصرف
                ($report->current_degree !== null && $report->past_degree !== null)
                    ? ($report->current_degree - $report->past_degree)
                    : '-',

                // قیمت فی کیلووات
                $report->degree_price ? number_format($report->degree_price) : '-',

                // مبلغ قابل تأدیه (price)
                $report->price ? number_format($report->price) : 0,
                $report->paid? number_format($report->paid) : 0,


                isset($report->remained) ? number_format($report->remained) : '0',

                // جمع کل (price + remained) - اصلاح شده
                (isset($report->price) || isset($report->remained))
                    ? number_format((float)($report->price ?? 0) + (float)($report->remained ?? 0))
                    : '0',
                // از تاریخ (paid_date)
                $report->paid_date ? \Morilog\Jalali\Jalalian::fromDateTime($report->paid_date)->format('Y/m/d') : '-',

                // تا تاریخ (expiration_date)
                $report->expiration_date ? \Morilog\Jalali\Jalalian::fromDateTime($report->expiration_date)->format('Y/m/d') : '-'
            ],
            'salary' => [
                $report->market->name ?? '-',
                $report->staff->fullname ?? '-',
                $report->salary,
                $report->paid,
                $report->remained,
                $report->loan,
                $currency,
                $report->paid_date ? \Morilog\Jalali\Jalalian::fromDateTime($report->paid_date)->format('Y/m/d') : '-',
                $report->is_reduce ? 'فعال' : 'غیرفعال'
            ],
            'outside' => [
                $report->market->name ?? '-',
                $report->customer_id ? 'مشتری' : ($report->staff_id ? 'کارمند' : ($report->shopkeeper_id ? 'دوکاندار' : 'نامشخص')),
                $report->customer->fullname ?? $report->staff->fullname ?? $report->shopkeeper->fullname ?? '-',
                $report->paid,
                $currency,
                $report->date ? \Morilog\Jalali\Jalalian::fromDateTime($report->date)->format('Y/m/d') : '-',
                $report->description ?? '-'
            ],
            'deposit' => [
                $report->accounting->market->name ?? '-',
                $report->accounting->shopkeeper->fullname ?? '-',
                $report->expanses_type,
                $report->price,
                $report->paid,
                $report->remained,
                $report->paid_date ? \Morilog\Jalali\Jalalian::fromDateTime($report->paid_date)->format('Y/m/d') : '-'
            ],
            'loan' => [
                $report->market->name ?? '-',
                $report->person,
                $report->person === 'مشتری' && $report->customer ? $report->customer->fullname : ($report->person === 'دوکاندار' && $report->shopkeeper ? $report->shopkeeper->fullname : ($report->person === 'کارمند' && $report->staff ? $report->staff->fullname : '-')),
                $report->amount,
                $report->totalPaid(),
                $report->remainingAmount(),
                $report->date ? \Morilog\Jalali\Jalalian::fromDateTime($report->date)->format('Y/m/d') : '-'
            ],
            'payment' => [
                $report->loan_id,
                $report->amount,
                $currency,
                $report->date ? \Morilog\Jalali\Jalalian::fromDateTime($report->date)->format('Y/m/d') : '-',
                $report->description ?? '-'
            ],
            'buy' => [
                $report->market->name ?? '-',
                $report->customer->fullname ?? '-',
                $report->property,
                $report->price,
                $currency,
                $report->created_at ? \Morilog\Jalali\Jalalian::fromDateTime($report->created_at)->format('Y/m/d') : '-'
            ],
            'sell' => [
                $report->market->name ?? '-',
                $report->customer->fullname ?? '-',
                $report->property,
                $report->price,
                $currency,
                $report->date ? \Morilog\Jalali\Jalalian::fromDateTime($report->date)->format('Y/m/d') : '-',
                $report->details ?? '-'
            ],
            'withdraw_log' => [
                $report->expanses_type,
                $report->recipient_name,
                $report->amount,
                $currency,
                $report->description ?? '-',
                $report->created_at ? \Morilog\Jalali\Jalalian::fromDateTime($report->created_at)->format('Y/m/d') : '-'
            ],
            default => []
        };
    }

    public function styles(Worksheet $sheet)
    {
        $styles = [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '3B82F6']],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'center']
            ],
            'A:Z' => [
                'alignment' => ['horizontal' => 'center', 'vertical' => 'center']
            ]
        ];

        // Add footer row style for accounting report
        if ($this->reportType === 'accounting' && $this->data->count() > 0) {
            $lastRow = $this->data->count() + 2; // +1 for header, +1 for footer

            $styles["A{$lastRow}:N{$lastRow}"] = [
                'font' => ['bold' => true],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'F0F0F0']],
            ];
        }

        return $styles;
    }

    public function columnWidths(): array
    {
        return match ($this->reportType) {
            'withdraw_salary' => [
                'A' => 10, // نوع
                'B' => 15, // برداشت از
                'C' => 20, // شخص
                'D' => 15, // مبلغ
                'E' => 12, // واحد پول
                'F' => 15, // تاریخ
                'G' => 25, // توضیحات
            ],
            'accounting' => [
                'A' => 15, // مارکت
                'B' => 15, // نوع مصرف
                'C' => 10, // نوع (دوکان/غرفه)
                'D' => 18, // نمبر غرفه/دوکان
                'E' => 10, // طبقه
                'F' => 20, // مشتری
                'G' => 12, // درجه فعلی
                'H' => 12, // درجه قبلی
                'I' => 12, // مقدار مصرف
                'J' => 15, // قیمت فی کیلووات
                'K' => 18, // مبلغ قابل تأدیه
                'L' => 12, // باقیات
                'M' => 12, // جمع کل
                'N' => 15, // از تاریخ
                'O' => 15, // تا تاریخ
            ],
            'salary' => [
                'A' => 15, // مارکت
                'B' => 20, // کارمند
                'C' => 15, // حقوق
                'D' => 15, // پرداخت شده
                'E' => 15, // باقی مانده
                'F' => 15, // قرضه
                'G' => 12, // واحد پول
                'H' => 15, // تاریخ پرداخت
                'I' => 12, // وضعیت کسر
            ],
            'outside' => [
                'A' => 15, // مارکت
                'B' => 12, // نوع شخص
                'C' => 20, // نام شخص
                'D' => 15, // مبلغ
                'E' => 12, // واحد پول
                'F' => 15, // تاریخ
                'G' => 25, // توضیحات
            ],
            'deposit' => [
                'A' => 15, // مارکت
                'B' => 20, // دوکاندار
                'C' => 15, // نوع هزینه
                'D' => 15, // مبلغ کل
                'E' => 15, // پرداخت شده
                'F' => 15, // باقی مانده
                'G' => 15, // تاریخ پرداخت
            ],
            'loan' => [
                'A' => 15, // مارکت
                'B' => 12, // نوع شخص
                'C' => 20, // نام شخص
                'D' => 15, // مبلغ اصلی
                'E' => 15, // پرداخت شده
                'F' => 15, // باقی مانده
                'G' => 15, // تاریخ
            ],
            'payment' => [
                'A' => 12, // کد قرضه
                'B' => 15, // مبلغ پرداخت
                'C' => 12, // واحد پول
                'D' => 15, // تاریخ رسید
                'E' => 25, // توضیحات
            ],
            'buy' => [
                'A' => 15, // مارکت
                'B' => 20, // فروشنده
                'C' => 15, // نوع خرید
                'D' => 15, // قیمت خرید
                'E' => 12, // واحد پول
                'F' => 15, // تاریخ ثبت
            ],
            'sell' => [
                'A' => 15, // مارکت
                'B' => 20, // مشتری
                'C' => 15, // نوع ملک
                'D' => 15, // قیمت فروش
                'E' => 12, // واحد پول
                'F' => 15, // تاریخ
                'G' => 25, // جزئیات
            ],
            'withdraw_log' => [
                'A' => 15, // نوع هزینه
                'B' => 20, // دریافت کننده
                'C' => 15, // مبلغ
                'D' => 12, // واحد پول
                'E' => 25, // توضیحات
                'F' => 15, // تاریخ ثبت
            ],
            default => [
                'A' => 15,
                'B' => 15,
                'C' => 15,
                'D' => 15,
                'E' => 15,
                'F' => 15,
                'G' => 15,
                'H' => 15
            ]
        };
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                if ($this->reportType === 'accounting') {
                    $this->addAccountingFooter($event);
                }
            },
        ];
    }

private function addAccountingFooter(AfterSheet $event)
{
    $totalPrice = 0;
    $totalPaid = 0;
    $totalRemained = 0;
    $totalAll = 0;

    // محاسبه مجموع‌ها
    foreach ($this->data as $report) {
        $price = (float) ($report->price ?? 0);
        $paid = (float) ($report->paid ?? 0);
        $remained = (float) ($report->remained ?? 0);

        $totalPrice += $price;
        $totalPaid += $paid;
        $totalRemained += $remained;
        $totalAll += $price + $remained;
    }

    // تعیین ردیف آخر (Header + Data + Footer)
    $lastRow = $this->data->count() + 2;

    // قرار دادن برچسب "مجموع" در ستون J
    $event->sheet->setCellValue("J{$lastRow}", "مجموع");

    // قرار دادن مجموع‌ها در ستون‌های K تا M
    $event->sheet->setCellValue("K{$lastRow}", number_format($totalPrice));
    $event->sheet->setCellValue("L{$lastRow}", number_format($totalPaid));
    $event->sheet->setCellValue("M{$lastRow}", number_format($totalRemained));
    $event->sheet->setCellValue("N{$lastRow}", number_format($totalAll));

    // ستون‌های دیگر خالی
    foreach (range('A', 'I') as $col) {
        $event->sheet->setCellValue("{$col}{$lastRow}", "");
    }
    foreach (range('O', 'Z') as $col) { // اگر ستون‌های بیشتری دارید، این محدوده را اصلاح کنید
        $event->sheet->setCellValue("{$col}{$lastRow}", "");
    }

    // اعمال استایل به ردیف مجموع
    $event->sheet->getStyle("J{$lastRow}:N{$lastRow}")->applyFromArray([
        'font' => ['bold' => true],
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => ['rgb' => 'F0F0F0']
        ]
    ]);
}

}
