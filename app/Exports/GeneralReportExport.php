<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GeneralReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles
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
        return match($this->reportType) {
            'accounting' => [
                $report->market->name ?? '-',
                $report->type,
                $report->shopkeeper->fullname ?? '-',
                $report->expanses_type,
                $report->price,
                $report->currency,
                $report->paid_date ? \Morilog\Jalali\Jalalian::fromDateTime($report->paid_date)->format('Y/m/d') : '-',
                $report->cleared ? 'تسویه شده' : 'در انتظار'
            ],
            'outside' => [
                $report->market->name ?? '-',
                $report->customer_id ? 'مشتری' : ($report->staff_id ? 'کارمند' : ($report->shopkeeper_id ? 'دوکاندار' : 'نامشخص')),
                $report->customer->fullname ?? $report->staff->fullname ?? $report->shopkeeper->fullname ?? '-',
                $report->paid,
                $report->currency,
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
                $report->currency,
                $report->date ? \Morilog\Jalali\Jalalian::fromDateTime($report->date)->format('Y/m/d') : '-',
                $report->description ?? '-'
            ],
            'buy' => [
                $report->market->name ?? '-',
                $report->customer->fullname ?? '-',
                $report->property,
                $report->price,
                $report->currency,
                $report->created_at ? \Morilog\Jalali\Jalalian::fromDateTime($report->created_at)->format('Y/m/d') : '-'
            ],
            'sell' => [
                $report->market->name ?? '-',
                $report->customer->fullname ?? '-',
                $report->property,
                $report->price,
                $report->currency,
                $report->date ? \Morilog\Jalali\Jalalian::fromDateTime($report->date)->format('Y/m/d') : '-',
                $report->details ?? '-'
            ],
            'withdraw_log' => [
                $report->expanses_type,
                $report->recipient_name,
                $report->amount,
                $report->currency,
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
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '3B82F6']]
            ],
        ];
    }
}