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
                    @case('withdraw_log')
                        <th>نوع</th>
                        <th>کارمند</th>
                        <th>مبلغ</th>
                        <th>واحد پول</th>
                        <th>توضیحات</th>
                        <th>تاریخ ثبت</th>
                    @break

                    @case('loan')
                        <th>نوع</th>
                        <th>مشتری</th>
                        <th>مبلغ اصلی</th>
                        <th>رسید</th>
                        <th>باقی مانده</th>
                        <th>برند</th>
                        <th>واحد پول</th>
                        <th>تاریخ</th>
                    @break

                    @case('sell')
                        <th>شماره فاکتور</th>
                        <th>نوع فروش</th>
                        <th>مشتری</th>
                        <th>قیمت کل</th>
                        <th>تخفیف</th>
                        <th>تاریخ ثبت</th>
                    @break

                    @case('buy')
                        <th>بارکد</th>
                        <th>نام کالا</th>
                        <th>شرکت</th>
                        <th>قیمت کل</th>
                        <th>واحد پول</th>
                        <th>تعداد</th>
                        <th>تاریخ واردات</th>
                    @break

                    @case('transaction')
                        <th>نوع</th>
                        <th>شخص</th>
                        <th>نام شخص</th>
                        <th>مبلغ</th>
                        <th>واحد پول</th>
                        <th>تاریخ تراکنش</th>
                    @break

                    @case('company_payment')
                        <th>شرکت</th>
                        <th>واحد پول</th>
                        <th>کل بدهی</th>
                        <th>پرداخت شده</th>
                        <th>باقی مانده</th>
                        <th>تاریخ ثبت</th>
                    @break
                @endswitch
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $report)
            <tr>
                <td class="row-number">{{ $index + 1 }}</td>
                @switch($reportType)
                    @case('withdraw_log')
                        <td>
                            @php
                                $typeTranslations = [
                                    'electricity' => 'برق',
                                    'rent' => 'کرایه',
                                    'water' => 'مالیه', 
                                    'food' => 'غذا',
                                    'salary' => 'معاش کارمند',
                                    'transportation' => 'بارچلانی چین',
                                    'other' => 'متفرقه',
                                ];
                            @endphp
                            {{ $typeTranslations[$report->type] ?? $report->type }}
                        </td>
                        <td>{{ $report->staff->fullname ?? '-' }}</td>
                        <td>{{ number_format($report->amount) }}</td>
                        <td>{{ $report->currency }}</td>
                        <td>{{ $report->description ?? '-' }}</td>
                        <td>{{ $report->created_at ? \Morilog\Jalali\Jalalian::fromDateTime($report->created_at)->format('Y/m/d') : '-' }}</td>
                    @break

                    @case('loan')
                        <td>{{ $report->type }}</td>
                        <td>{{ $report->customer->fullname ?? '-' }}</td>
                        <td>{{ number_format($report->amount) }}</td>
                        <td>{{ number_format($report->loan_recipt ?? 0) }}</td>
                        <td>{{ number_format($report->reminded ?? 0) }}</td>
                        <td>{{ $report->brand ?? '-' }}</td>
                        <td>{{ $report->currency }}</td>
                        <td>{{ $report->date ? \Morilog\Jalali\Jalalian::fromDateTime($report->date)->format('Y/m/d') : '-' }}</td>
                    @break

                    @case('sell')
                        <td>{{ $report->invoice_number ?? '-' }}</td>
                        <td>{{ $report->sale_type }}</td>
                        <td>{{ $report->customer->fullname ?? '-' }}</td>
                        <td>{{ number_format($report->total_price ?? $report->price) }}</td>
                        <td>{{ number_format($report->discount ?? 0) }}</td>
                        <td>{{ $report->created_at ? \Morilog\Jalali\Jalalian::fromDateTime($report->created_at)->format('Y/m/d') : '-' }}</td>
                    @break

                    @case('buy')
                        <td>{{ $report->barcode ?? '-' }}</td>
                        <td>{{ $report->name ?? '-' }}</td>
                        <td>{{ $report->company->name ?? '-' }}</td>
                        <td>{{ number_format($report->total_price ?? $report->price) }}</td>
                        <td>{{ $report->currency }}</td>
                        <td>{{ number_format($report->all_exist_number ?? 0) }}</td>
                        <td>{{ $report->import_date ? \Morilog\Jalali\Jalalian::fromDateTime($report->import_date)->format('Y/m/d') : '-' }}</td>
                    @break

                    @case('transaction')
                        <td>{{ $report->type }}</td>
                        <td>
                            @if($report->customer_id) مشتری
                            @elseif($report->staff_id) کارمند
                            @elseif($report->sarafi_id) صرافی
                            @else دوکان @endif
                        </td>
                        <td>{{ $report->customer->fullname ?? $report->staff->fullname ?? $report->sarafi->name ?? '-' }}</td>
                        <td>{{ number_format($report->amount) }}</td>
                        <td>{{ $report->currency }}</td>
                        <td>{{ $report->created_at ? \Morilog\Jalali\Jalalian::fromDateTime($report->created_at)->format('Y/m/d') : '-' }}</td>
                    @break

                    @case('company_payment')
                        <td>{{ $report->company->name ?? '-' }}</td>
                        <td>{{ $report->currency }}</td>
                        <td>{{ number_format($report->total_debt ?? 0) }}</td>
                        <td>{{ number_format($report->paid_amount ?? 0) }}</td>
                        <td>{{ number_format($report->remaining ?? 0) }}</td>
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