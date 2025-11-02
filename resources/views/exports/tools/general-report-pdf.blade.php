<!DOCTYPE html>
<html dir="rtl">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{ $reportTitle }}</title>
    <style>
        body {
            font-family: dejavusanscondensed, sans-serif;
            direction: rtl;
            margin: 0;
            padding: 10px;
            font-size: 9px;
            line-height: 1.2;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #333;
        }

        .header h1 {
            margin: 0;
            font-size: 14px;
            color: #333;
        }

        .summary {
            margin: 10px 0;
            padding: 8px;
            background: #f5f5f5;
            border-radius: 4px;
        }

        .summary div {
            display: inline-block;
            margin: 0 15px;
            text-align: center;
        }

        .summary .value {
            font-size: 12px;
            font-weight: bold;
            display: block;
        }

        .summary .label {
            font-size: 8px;
            color: #666;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 7px;
        }

        th {
            background: #333;
            color: white;
            padding: 4px 3px;
            border: 1px solid #555;
            text-align: center;
        }

        td {
            padding: 3px 2px;
            border: 1px solid #ddd;
            text-align: center;
        }

        tr:nth-child(even) {
            background: #f9f9f9;
        }

        .row-number {
            background: #e0e0e0;
            font-weight: bold;
            width: 25px;
        }

        .footer {
            margin-top: 15px;
            text-align: center;
            font-size: 7px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 5px;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $reportTitle }}</h1>
        <div>تاریخ تولید: {{ \Morilog\Jalali\Jalalian::now()->format('Y/m/d H:i') }}</div>
    </div>

    <div class="summary">
        <div>
            <span class="value">{{ number_format($summary['total_count']) }}</span>
            <span class="label">تعداد رکوردها</span>
        </div>
        <div>
            <span class="value">{{ number_format($summary['total_amount']) }}</span>
            <span class="label">مجموع کل</span>
        </div>
    </div>

    @if($data && $data->count() > 0)
    <table>
        <thead>
            <tr>
                <th class="row-number">#</th>
                @switch($reportType)
                @case('salary')
                <th>مبلغ</th>
                <th>واحد</th>
                <th>تاریخ</th>
                <th>توضیحات</th>
                @break
                @case('withdrawal')
                <th>نوع</th>
                <th>مبلغ</th>
                <th>واحد</th>
                <th>تاریخ</th>
                <th>توضیحات</th>
                @break
                @case('inventory')
                <th>بارکد</th>
                <th>نام محصول</th>
                <th>دسته‌بندی</th>
                <th>موجودی</th>
                <th>قیمت خرید</th>
                <th>قیمت فروش</th>
                <th>وضعیت</th>
                @break
                @case('warehouse')
                <th>بارکد</th>
                <th>نام محصول</th>
                <th>دسته‌بندی</th>
                <th>موجودی</th>
                <th>قیمت خرید</th>
                <th>قیمت فروش</th>
                <th>وضعیت</th>
                @break
                @case('sale')
                <th>شماره فاکتور</th>
                <th>نوع فروش</th>
                <th>خریدار</th>
                <th>قیمت کل</th>
                <th>دریافتی</th>
                <th>باقی‌مانده</th>
                <th>سود</th>
                <th>تاریخ</th>
                @break
                @case('sale_items')
                <th>شماره فاکتور</th>
                <th>محصول</th>
                <th>تعداد</th>
                <th>قیمت واحد</th>
                <th>قیمت کل</th>
                <th>سود</th>
                <th>تاریخ</th>
                @break
                @case('loan')
                <th>نوع</th>
                <th>مبلغ</th>
                <th>واحد</th>
                <th>تاریخ</th>
                <th>توضیحات</th>
                @break
                @case('inventory_history')
                <th>محصول</th>
                <th>نوع</th>
                <th>تعداد تغییر</th>
                <th>موجودی قبلی</th>
                <th>موجودی جدید</th>
                <th>قیمت واحد</th>
                <th>مبلغ کل</th>
                <th>تاریخ</th>
                @break
                @case('warehouse_history')
                <th>محصول</th>
                <th>نوع</th>
                <th>تعداد تغییر</th>
                <th>موجودی قبلی</th>
                <th>موجودی جدید</th>
                <th>قیمت واحد</th>
                <th>مبلغ کل</th>
                <th>تاریخ</th>
                @break
                @endswitch
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $report)
            <tr>
                <td class="row-number">{{ $index + 1 }}</td>
                @switch($reportType)
                @case('salary')
                <td>{{ number_format($report->amount) }}</td>
                <td>
                    @switch($report->currency)
                    @case('AFN') افغانی @break
                    @case('USD') دالر @break
                    @default {{ $report->currency }}
                    @endswitch
                </td>
                <td>{{ $report->date ? \Morilog\Jalali\Jalalian::fromDateTime($report->date)->format('Y/m/d') : '-' }}</td>
                <td>{{ $report->description ?? '-' }}</td>
                @break

                @case('withdrawal')
                <td>{{ $report->type }}</td>
                <td>{{ number_format($report->amount) }}</td>
                <td>
                    @switch($report->currency)
                    @case('AFN') افغانی @break
                    @case('USD') دالر @break
                    @default {{ $report->currency }}
                    @endswitch
                </td>
                <td>{{ $report->date ? \Morilog\Jalali\Jalalian::fromDateTime($report->date)->format('Y/m/d') : '-' }}</td>
                <td>{{ $report->description ?? '-' }}</td>
                @break

                @case('inventory')
                <td>{{ $report->barcode }}</td>
                <td>{{ $report->product_name }}</td>
                <td>{{ $report->category ?? '-' }}</td>
                <td>{{ number_format($report->total_quantity) }}</td>
                <td>{{ number_format($report->purchase_price_per_unit) }}</td>
                <td>{{ number_format($report->retail_price) }}</td>
                <td>{{ $report->status }}</td>
                @break

                @case('warehouse')
                <td>{{ $report->barcode }}</td>
                <td>{{ $report->product_name }}</td>
                <td>{{ $report->category ?? '-' }}</td>
                <td>{{ number_format($report->total_quantity) }}</td>
                <td>{{ number_format($report->purchase_price_per_unit) }}</td>
                <td>{{ number_format($report->retail_price) }}</td>
                <td>{{ $report->status }}</td>
                @break

                @case('sale')
                <td>{{ $report->invoice_number ?? '-' }}</td>
                <td>{{ $report->sale_type === 'retail' ? 'خرده' : 'عمده' }}</td>
                <td>{{ $report->buyer_name ?? '-' }}</td>
                <td>{{ number_format($report->total_price) }}</td>
                <td>{{ number_format($report->received_amount) }}</td>
                <td>{{ number_format($report->remaining_amount) }}</td>
                <td>{{ number_format($report->final_profit) }}</td>
                <td>{{ $report->created_at ? \Morilog\Jalali\Jalalian::fromDateTime($report->created_at)->format('Y/m/d') : '-' }}</td>
                @break

                @case('sale_items')
                <td>{{ $report->sale->invoice_number ?? '-' }}</td>
                <td>{{ $report->warehouse->product_name ?? '-' }}</td>
                <td>{{ number_format($report->quantity) }}</td>
                <td>{{ number_format($report->price_per_unit) }}</td>
                <td>{{ number_format($report->total_price) }}</td>
                <td>{{ number_format($report->profit) }}</td>
                <td>{{ $report->created_at ? \Morilog\Jalali\Jalalian::fromDateTime($report->created_at)->format('Y/m/d') : '-' }}</td>
                @break

                @case('loan')
                <td>{{ $report->type }}</td>
                <td>{{ number_format($report->amount) }}</td>
                <td>
                    @switch($report->currency)
                    @case('AFN') افغانی @break
                    @case('USD') دالر @break
                    @default {{ $report->currency }}
                    @endswitch
                </td>
                <td>{{ $report->date ? \Morilog\Jalali\Jalalian::fromDateTime($report->date)->format('Y/m/d') : '-' }}</td>
                <td>{{ $report->description ?? '-' }}</td>
                @break

                @case('inventory_history')
                <td>{{ $report->inventory->product_name ?? '-' }}</td>
                <td>{{ $report->type }}</td>
                <td>{{ number_format($report->quantity_change) }}</td>
                <td>{{ number_format($report->previous_quantity) }}</td>
                <td>{{ number_format($report->new_quantity) }}</td>
                <td>{{ number_format($report->unit_price ?? 0) }}</td>
                <td>{{ number_format($report->total_amount ?? 0) }}</td>
                <td>{{ $report->created_at ? \Morilog\Jalali\Jalalian::fromDateTime($report->created_at)->format('Y/m/d') : '-' }}</td>
                @break

                @case('warehouse_history')
                <td>{{ $report->warehouse->product_name ?? '-' }}</td>
                <td>{{ $report->type }}</td>
                <td>{{ number_format($report->quantity_change) }}</td>
                <td>{{ number_format($report->previous_quantity) }}</td>
                <td>{{ number_format($report->new_quantity) }}</td>
                <td>{{ number_format($report->unit_price ?? 0) }}</td>
                <td>{{ number_format($report->total_amount ?? 0) }}</td>
                <td>{{ $report->created_at ? \Morilog\Jalali\Jalalian::fromDateTime($report->created_at)->format('Y/m/d') : '-' }}</td>
                @break

                @endswitch
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="no-data">
        <h3>داده‌ای برای نمایش وجود ندارد</h3>
        <p>هیچ رکوردی با فیلترهای اعمال شده مطابقت ندارد.</p>
    </div>
    @endif

    <div class="footer">
        <div>سیستم گزارش‌گیری جامع - Tools</div>
        <div>تعداد: {{ number_format($summary['total_count']) }} | مجموع کل: {{ number_format($summary['total_amount']) }}</div>
    </div>
</body>
</html>