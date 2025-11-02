<?php

namespace App\Exports\Tools;

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
                'مبلغ',
                'واحد پول',
                'تاریخ',
                'توضیحات'
            ],
            'withdrawal' => [
                'نوع',
                'مبلغ',
                'واحد پول',
                'تاریخ',
                'توضیحات'
            ],
            'inventory' => [
                'بارکد',
                'نام محصول',
                'دسته‌بندی',
                'واحد',
                'نوع بسته',
                'تعداد در بسته',
                'تعداد کل بسته',
                'موجودی کل',
                'قیمت خرید (بسته)',
                'قیمت خرید (واحد)',
                'قیمت خرده',
                'قیمت عمده',
                'وضعیت',
                'فعال'
            ],
            'warehouse' => [
                'بارکد',
                'نام محصول',
                'دسته‌بندی',
                'واحد',
                'نوع بسته',
                'تعداد در بسته',
                'تعداد کل بسته',
                'موجودی کل',
                'قیمت خرید (بسته)',
                'قیمت خرید (واحد)',
                'قیمت خرده',
                'قیمت عمده',
                'وضعیت',
                'فعال'
            ],
            'sale' => [
                'شماره فاکتور',
                'نوع فروش',
                'خریدار',
                'قیمت کل',
                'مبلغ دریافتی',
                'مبلغ باقی‌مانده',
                'تخفیف',
                'سود نهایی',
                'مرجوعی',
                'تاریخ'
            ],
            'sale_items' => [
                'شماره فاکتور',
                'محصول',
                'تعداد',
                'قیمت واحد',
                'قیمت کل',
                'سود',
                'ضرر',
                'تاریخ'
            ],
            'loan' => [
                'نوع',
                'مبلغ',
                'واحد پول',
                'تاریخ',
                'توضیحات'
            ],
            'inventory_history' => [
                'محصول',
                'نوع تراکنش',
                'تعداد تغییر',
                'موجودی قبلی',
                'موجودی جدید',
                'قیمت واحد',
                'مبلغ کل',
                'شماره مرجع',
                'تاریخ'
            ],
            'warehouse_history' => [
                'محصول',
                'نوع تراکنش',
                'تعداد تغییر',
                'موجودی قبلی',
                'موجودی جدید',
                'قیمت واحد',
                'مبلغ کل',
                'شماره مرجع',
                'تاریخ'
            ],
            default => []
        };
    }

    public function map($report): array
    {
        $currency = match($report->currency ?? null) {
            'AFN' => 'افغانی',
            'USD' => 'دالر',
            'EUR' => 'یورو',
            default => $report->currency ?? '-'
        };

        return match($this->reportType) {
            'salary' => [
                number_format($report->amount),
                $currency,
                $report->date ? \Morilog\Jalali\Jalalian::fromDateTime($report->date)->format('Y/m/d') : '-',
                $report->description ?? '-'
            ],
            'withdrawal' => [
                $report->type,
                number_format($report->amount),
                $currency,
                $report->date ? \Morilog\Jalali\Jalalian::fromDateTime($report->date)->format('Y/m/d') : '-',
                $report->description ?? '-'
            ],
            'inventory' => [
                $report->barcode,
                $report->product_name,
                $report->category ?? '-',
                $report->unit,
                $report->package_type,
                $report->quantity_per_package,
                $report->total_packages,
                $report->total_quantity,
                number_format($report->purchase_price_per_package),
                number_format($report->purchase_price_per_unit),
                number_format($report->retail_price),
                number_format($report->wholesale_price),
                $report->status,
                $report->is_active ? 'فعال' : 'غیرفعال'
            ],
            'warehouse' => [
                $report->barcode,
                $report->product_name,
                $report->category ?? '-',
                $report->unit,
                $report->package_type,
                $report->quantity_per_package,
                $report->total_packages,
                $report->total_quantity,
                number_format($report->purchase_price_per_package),
                number_format($report->purchase_price_per_unit),
                number_format($report->retail_price),
                number_format($report->wholesale_price),
                $report->status,
                $report->is_active ? 'فعال' : 'غیرفعال'
            ],
            'sale' => [
                $report->invoice_number ?? '-',
                $report->sale_type === 'retail' ? 'خرده' : 'عمده',
                $report->buyer_name ?? '-',
                number_format($report->total_price),
                number_format($report->received_amount),
                number_format($report->remaining_amount),
                number_format($report->discount),
                number_format($report->final_profit),
                $report->is_return ? 'بله' : 'خیر',
                $report->created_at ? \Morilog\Jalali\Jalalian::fromDateTime($report->created_at)->format('Y/m/d') : '-'
            ],
            'sale_items' => [
                $report->sale->invoice_number ?? '-',
                $report->warehouse->product_name ?? '-',
                number_format($report->quantity),
                number_format($report->price_per_unit),
                number_format($report->total_price),
                number_format($report->profit),
                number_format($report->loss),
                $report->created_at ? \Morilog\Jalali\Jalalian::fromDateTime($report->created_at)->format('Y/m/d') : '-'
            ],
            'loan' => [
                $report->type,
                number_format($report->amount),
                $currency,
                $report->date ? \Morilog\Jalali\Jalalian::fromDateTime($report->date)->format('Y/m/d') : '-',
                $report->description ?? '-'
            ],
            'inventory_history' => [
                $report->inventory->product_name ?? '-',
                $report->type,
                number_format($report->quantity_change),
                number_format($report->previous_quantity),
                number_format($report->new_quantity),
                number_format($report->unit_price ?? 0),
                number_format($report->total_amount ?? 0),
                $report->reference_number ?? '-',
                $report->created_at ? \Morilog\Jalali\Jalalian::fromDateTime($report->created_at)->format('Y/m/d') : '-'
            ],
            'warehouse_history' => [
                $report->warehouse->product_name ?? '-',
                $report->type,
                number_format($report->quantity_change),
                number_format($report->previous_quantity),
                number_format($report->new_quantity),
                number_format($report->unit_price ?? 0),
                number_format($report->total_amount ?? 0),
                $report->reference_number ?? '-',
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
            'salary' => [
                'A' => 15, 'B' => 12, 'C' => 15, 'D' => 25
            ],
            'withdrawal' => [
                'A' => 15, 'B' => 15, 'C' => 12, 'D' => 15, 'E' => 25
            ],
            'inventory' => [
                'A' => 15, 'B' => 25, 'C' => 15, 'D' => 10, 'E' => 12,
                'F' => 12, 'G' => 12, 'H' => 12, 'I' => 15, 'J' => 15,
                'K' => 12, 'L' => 12, 'M' => 12, 'N' => 10
            ],
            'warehouse' => [
                'A' => 15, 'B' => 25, 'C' => 15, 'D' => 10, 'E' => 12,
                'F' => 12, 'G' => 12, 'H' => 12, 'I' => 15, 'J' => 15,
                'K' => 12, 'L' => 12, 'M' => 12, 'N' => 10
            ],
            'sale' => [
                'A' => 12, 'B' => 10, 'C' => 20, 'D' => 15,
                'E' => 15, 'F' => 15, 'G' => 12, 'H' => 15, 'I' => 10, 'J' => 15
            ],
            'sale_items' => [
                'A' => 12, 'B' => 25, 'C' => 12, 'D' => 12, 'E' => 15,
                'F' => 12, 'G' => 12, 'H' => 15
            ],
            'loan' => [
                'A' => 15, 'B' => 15, 'C' => 12, 'D' => 15, 'E' => 25
            ],
            'inventory_history' => [
                'A' => 25, 'B' => 15, 'C' => 12, 'D' => 12, 'E' => 12,
                'F' => 12, 'G' => 15, 'H' => 15, 'I' => 15
            ],
            'warehouse_history' => [
                'A' => 25, 'B' => 15, 'C' => 12, 'D' => 12, 'E' => 12,
                'F' => 12, 'G' => 15, 'H' => 15, 'I' => 15
            ],
            default => [
                'A' => 15, 'B' => 15, 'C' => 15, 'D' => 15, 'E' => 15
            ]
        };
    }
}