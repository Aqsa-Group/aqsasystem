<?php

namespace App\Exports;

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
                'نوع',
                'دوکاندار', 
                'نوع مصرف',
                'مبلغ',
                'واحد پول',
                'تاریخ پرداخت',
                'وضعیت'
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
        $currency = match($report->currency ?? null) {
            'AFN' => 'افغانی',
            'USD' => 'دالر',
            'EUR' => 'یورو',
            default => $report->currency ?? '-'
        };

        return match($this->reportType) {
            'accounting' => [
                $report->market->name ?? '-',
                $report->type,
                $report->shopkeeper->fullname ?? '-',
                $report->expanses_type,
                $report->price,
                $currency,
                $report->paid_date ? \Morilog\Jalali\Jalalian::fromDateTime($report->paid_date)->format('Y/m/d') : '-',
                $report->cleared ? 'تسویه شده' : 'در انتظار'
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
                $report->person === 'مشتری' && $report->customer ? $report->customer->fullname : 
                    ($report->person === 'دوکاندار' && $report->shopkeeper ? $report->shopkeeper->fullname :
                    ($report->person === 'کارمند' && $report->staff ? $report->staff->fullname : '-')),
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
            'accounting' => [
                'A' => 15, // مارکت
                'B' => 10, // نوع
                'C' => 20, // دوکاندار
                'D' => 15, // نوع مصرف
                'E' => 15, // مبلغ
                'F' => 12, // واحد پول
                'G' => 15, // تاریخ پرداخت
                'H' => 12, // وضعیت
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
                'A' => 15, 'B' => 15, 'C' => 15, 'D' => 15, 
                'E' => 15, 'F' => 15, 'G' => 15, 'H' => 15
            ]
        };
    }
}