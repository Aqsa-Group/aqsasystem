<?php

namespace App\Exports\Import;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GeneralReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $data;
    protected $reportType;

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
        return match($this->reportType) {
            'withdraw_log' => [
                'نوع',
                'کارمند', 
                'مبلغ',
                'واحد پول',
                'توضیحات',
                'تاریخ ثبت'
            ],
            'loan' => [
                'نوع',
                'مشتری',
                'مبلغ اصلی', 
                'رسید',
                'باقی مانده',
                'برند',
                'واحد پول',
                'تاریخ'
            ],
            'sell' => [
                'شماره فاکتور',
                'نوع فروش',
                'مشتری',
                'قیمت کل', 
                'تخفیف',
                'تاریخ ثبت'
            ],
            'buy' => [
                'بارکد',
                'نام کالا', 
                'شرکت',
                'قیمت کل',
                'واحد پول',
                'تعداد',
                'تاریخ واردات'
            ],
            'transaction' => [
                'نوع',
                'شخص',
                'نام شخص',
                'مبلغ',
                'واحد پول', 
                'تاریخ تراکنش'
            ],
            'company_payment' => [
                'شرکت',
                'واحد پول',
                'کل بدهی',
                'پرداخت شده', 
                'باقی مانده',
                'تاریخ ثبت'
            ],
            default => []
        };
    }

    public function map($report): array
    {
        // Translate currency
        $currency = match($report->currency ?? null) {
            'AFN' => 'افغانی',
            'USD' => 'دالر',
            'EUR' => 'یورو',
            default => $report->currency ?? '-'
        };

        // Type translations
        $typeTranslations = [
            'electricity' => 'برق',
            'rent' => 'کرایه', 
            'water' => 'مالیه',
            'food' => 'غذا',
            'salary' => 'معاش کارمند',
            'transportation' => 'بارچلانی چین',
            'other' => 'متفرقه',
            'بردگی' => 'بردگی',
            'رسید' => 'رسید',
            'برداشت' => 'برداشت'
        ];

        return match($this->reportType) {
            'withdraw_log' => [
                $typeTranslations[$report->type] ?? $report->type,
                $report->staff->fullname ?? '-',
                $report->amount,
                $currency,
                $report->description ?? '-',
                $report->created_at ? \Morilog\Jalali\Jalalian::fromDateTime($report->created_at)->format('Y/m/d') : '-'
            ],
            'loan' => [
                $report->type,
                $report->customer->fullname ?? '-',
                $report->amount,
                $report->loan_recipt ?? 0,
                $report->reminded ?? 0,
                $report->brand ?? '-',
                $currency,
                $report->date ? \Morilog\Jalali\Jalalian::fromDateTime($report->date)->format('Y/m/d') : '-'
            ],
            'sell' => [
                $report->invoice_number ?? '-',
                $report->sale_type ?? '-',
                $report->customer->fullname ?? '-',
                $report->total_price ?? $report->price,
                $report->discount ?? 0,
                $report->created_at ? \Morilog\Jalali\Jalalian::fromDateTime($report->created_at)->format('Y/m/d') : '-'
            ],
            'buy' => [
                $report->barcode ?? '-',
                $report->name ?? '-',
                $report->company->name ?? '-',
                $report->total_price ?? $report->price,
                $currency,
                $report->all_exist_number ?? 0,
                $report->import_date ? \Morilog\Jalali\Jalalian::fromDateTime($report->import_date)->format('Y/m/d') : '-'
            ],
            'transaction' => [
                $report->type,
                $report->customer_id ? 'مشتری' : ($report->staff_id ? 'کارمند' : ($report->sarafi_id ? 'صرافی' : 'دوکان')),
                $report->customer->fullname ?? $report->staff->fullname ?? $report->sarafi->name ?? '-',
                $report->amount,
                $currency,
                $report->created_at ? \Morilog\Jalali\Jalalian::fromDateTime($report->created_at)->format('Y/m/d') : '-'
            ],
            'company_payment' => [
                $report->company->name ?? '-',
                $currency,
                $report->total_debt ?? 0,
                $report->paid_amount ?? 0,
                $report->remaining ?? 0,
                $report->created_at ? \Morilog\Jalali\Jalalian::fromDateTime($report->created_at)->format('Y/m/d') : '-'
            ],
            default => []
        };
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '3B82F6']],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'center']
            ],
            'A:Z' => [
                'alignment' => ['horizontal' => 'center', 'vertical' => 'center']
            ]
        ];
    }

    public function columnWidths(): array
    {
        return match($this->reportType) {
            'withdraw_log' => [
                'A' => 15, // نوع
                'B' => 20, // کارمند
                'C' => 15, // مبلغ
                'D' => 12, // واحد پول
                'E' => 25, // توضیحات
                'F' => 15, // تاریخ ثبت
            ],
            'loan' => [
                'A' => 12, // نوع
                'B' => 20, // مشتری
                'C' => 15, // مبلغ اصلی
                'D' => 15, // رسید
                'E' => 15, // باقی مانده
                'F' => 15, // برند
                'G' => 12, // واحد پول
                'H' => 15, // تاریخ
            ],
            'sell' => [
                'A' => 15, // شماره فاکتور
                'B' => 12, // نوع فروش
                'C' => 20, // مشتری
                'D' => 15, // قیمت کل
                'E' => 15, // تخفیف
                'F' => 15, // تاریخ ثبت
            ],
            'buy' => [
                'A' => 15, // بارکد
                'B' => 20, // نام کالا
                'C' => 20, // شرکت
                'D' => 15, // قیمت کل
                'E' => 12, // واحد پول
                'F' => 12, // تعداد
                'G' => 15, // تاریخ واردات
            ],
            'transaction' => [
                'A' => 12, // نوع
                'B' => 12, // شخص
                'C' => 20, // نام شخص
                'D' => 15, // مبلغ
                'E' => 12, // واحد پول
                'F' => 15, // تاریخ تراکنش
            ],
            'company_payment' => [
                'A' => 20, // شرکت
                'B' => 12, // واحد پول
                'C' => 15, // کل بدهی
                'D' => 15, // پرداخت شده
                'E' => 15, // باقی مانده
                'F' => 15, // تاریخ ثبت
            ],
            default => [
                'A' => 15, 'B' => 15, 'C' => 15, 'D' => 15, 
                'E' => 15, 'F' => 15, 'G' => 15, 'H' => 15
            ]
        };
    }
}