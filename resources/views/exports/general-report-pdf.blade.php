<!DOCTYPE html>
<html dir="rtl">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{ $reportTitle }}</title>
    <style>
        /* استایل بسیار ساده */
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

        .footer {
            margin-top: 15px;
            text-align: center;
            font-size: 7px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 5px;
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
            <span class="label">مجموع مبالغ</span>
        </div>
    </div>

    @if($data->count() > 0)
    <table>
        <thead>
            <tr>
                @switch($reportType)
                @case('accounting')
                <th>مارکت</th>
                <th>نوع</th>
                <th>دوکاندار</th>
                <th>مصرف</th>
                <th>مبلغ</th>
                <th>تاریخ</th>
                <th>وضعیت</th>
                @break
                @case('outside')
                <th>مارکت</th>
                <th>نوع</th>
                <th>نام</th>
                <th>مبلغ</th>
                <th>واحد</th>
                <th>تاریخ</th>
                <th>توضیحات</th>
                @break
                @case('deposit')
                <th>مارکت</th>
                <th>دوکاندار</th>
                <th>هزینه</th>
                <th>مبلغ کل</th>
                <th>پرداخت</th>
                <th>باقی</th>
                <th>تاریخ</th>
                @break
                @case('loan')
                <th>مارکت</th>
                <th>نوع</th>
                <th>نام</th>
                <th>مبلغ اصلی</th>
                <th>پرداخت</th>
                <th>باقی</th>
                <th>تاریخ</th>
                @break
                @case('payment')
                <th>کد</th>
                <th>مبلغ</th>
                <th>واحد</th>
                <th>تاریخ</th>
                <th>توضیحات</th>
                @break
                @case('buy')
                <th>مارکت</th>
                <th>فروشنده</th>
                <th>نوع</th>
                <th>قیمت</th>
                <th>واحد</th>
                <th>تاریخ</th>
                @break
                @case('sell')
                <th>مارکت</th>
                <th>مشتری</th>
                <th>نوع</th>
                <th>قیمت</th>
                <th>واحد</th>
                <th>تاریخ</th>
                <th>جزئیات</th>
                @break
                @case('withdraw_log')
                <th>هزینه</th>
                <th>دریافت کننده</th>
                <th>مبلغ</th>
                <th>واحد</th>
                <th>توضیحات</th>
                <th>تاریخ</th>
                @break
                @endswitch
            </tr>
        </thead>
        <tbody>
            @foreach($data as $report)
            <tr>
                @switch($reportType)
                @case('accounting')
                <td>{{ $report->market->name ?? '-' }}</td>
                <td>{{ $report->type }}</td>
                <td>{{ $report->shopkeeper->fullname ?? '-' }}</td>
                <td>{{ $report->expanses_type }}</td>
                <td>{{ number_format($report->price) }} {{ $report->currency }}</td>
                <td>{{ $report->paid_date ? \Morilog\Jalali\Jalalian::fromDateTime($report->paid_date)->format('Y/m/d')
                    : '-' }}</td>
                <td>{{ $report->cleared ? '✅' : '⏳' }}</td>
                @break

                @case('outside')
                <td>{{ $report->market->name ?? '-' }}</td>
                <td>
                    @if($report->customer_id) مشتری
                    @elseif($report->staff_id) کارمند
                    @elseif($report->shopkeeper_id) دوکاندار
                    @else نامشخص @endif
                </td>
                <td>{{ $report->customer->fullname ?? $report->staff->fullname ?? $report->shopkeeper->fullname ?? '-'
                    }}</td>
                <td>{{ number_format($report->paid) }}</td>
                <td>
                    @switch($report->currency)
                    @case('AFN')
                    افغانی
                    @break
                    @case('USD')
                    دالر
                    @break
                    @default
                    {{ $report->currency }}
                    @endswitch
                </td>
                <td>{{ $report->date ? \Morilog\Jalali\Jalalian::fromDateTime($report->date)->format('Y/m/d') : '-' }}
                </td>
                <td>{{ Str::limit($report->description ?? '-', 3000) }}</td>
                @break

                @case('deposit')
                <td>{{ $report->accounting->market->name ?? '-' }}</td>
                <td>{{ $report->accounting->shopkeeper->fullname ?? '-' }}</td>
                <td>{{ $report->expanses_type }}</td>
                <td>{{ number_format($report->price) }}</td>
                <td>{{ number_format($report->paid) }}</td>
                <td>{{ number_format($report->remained) }}</td>
                <td>{{ $report->paid_date ? \Morilog\Jalali\Jalalian::fromDateTime($report->paid_date)->format('Y/m/d')
                    : '-' }}</td>
                @break

                @case('loan')
                <td>{{ $report->market->name ?? '-' }}</td>
                <td>{{ $report->person }}</td>
                <td>
                    @if($report->person === 'مشتری' && $report->customer)
                    {{ $report->customer->fullname }}
                    @elseif($report->person === 'دوکاندار' && $report->shopkeeper)
                    {{ $report->shopkeeper->fullname }}
                    @elseif($report->person === 'کارمند' && $report->staff)
                    {{ $report->staff->fullname }}
                    @else - @endif
                </td>
                <td>{{ number_format($report->amount) }}</td>
                <td>{{ number_format($report->totalPaid()) }}</td>
                <td>{{ number_format($report->remainingAmount()) }}</td>
                <td>{{ $report->date ? \Morilog\Jalali\Jalalian::fromDateTime($report->date)->format('Y/m/d') : '-' }}
                </td>
                @break

                @case('payment')
                <td>#{{ $report->loan_id }}</td>
                <td>{{ number_format($report->amount) }}</td>
                <td>
                    @switch($report->currency)
                    @case('AFN')
                    افغانی
                    @break
                    @case('USD')
                    دالر
                    @break
                    @default
                    {{ $report->currency }}
                    @endswitch
                </td>
                <td>{{ $report->date ? \Morilog\Jalali\Jalalian::fromDateTime($report->date)->format('Y/m/d') : '-' }}
                </td>
                <td>{{ Str::limit($report->description ?? '-', 3000) }}</td>
                @break

                @case('buy')
                <td>{{ $report->market->name ?? '-' }}</td>
                <td>{{ $report->customer->fullname ?? '-' }}</td>
                <td>{{ $report->property }}</td>
                <td>{{ number_format($report->price) }}</td>
                <td>
                    @switch($report->currency)
                    @case('AFN')
                    افغانی
                    @break
                    @case('USD')
                    دالر
                    @break
                    @default
                    {{ $report->currency }}
                    @endswitch
                </td>
                <td>{{ $report->created_at ?
                    \Morilog\Jalali\Jalalian::fromDateTime($report->created_at)->format('Y/m/d') : '-' }}</td>
                @break

                @case('sell')
                <td>{{ $report->market->name ?? '-' }}</td>
                <td>{{ $report->customer->fullname ?? '-' }}</td>
                <td>{{ $report->property }}</td>
                <td>{{ number_format($report->price) }}</td>
                <td>
                    @switch($report->currency)
                    @case('AFN')
                    افغانی
                    @break
                    @case('USD')
                    دالر
                    @break
                    @default
                    {{ $report->currency }}
                    @endswitch
                </td>
                <td>{{ $report->date ? \Morilog\Jalali\Jalalian::fromDateTime($report->date)->format('Y/m/d') : '-' }}
                </td>
                <td>{{ Str::limit($report->details ?? '-', 3000) }}</td>
                @break

                @case('withdraw_log')
                <td>{{ $report->expanses_type }}</td>
                <td>{{ $report->recipient_name }}</td>
                <td>{{ number_format($report->amount) }}</td>
                <td>
                    @switch($report->currency)
                    @case('AFN')
                    افغانی
                    @break
                    @case('USD')
                    دالر
                    @break
                    @default
                    {{ $report->currency }}
                    @endswitch
                </td>
                <td>{{ Str::limit($report->description ?? '-', 3000) }}</td>
                <td>{{ $report->created_at ?
                    \Morilog\Jalali\Jalalian::fromDateTime($report->created_at)->format('Y/m/d') : '-' }}</td>
                @break
                @endswitch
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div style="text-align: center; padding: 40px; color: #666;">
        <h3>داده‌ای برای نمایش وجود ندارد</h3>
        <p>هیچ رکوردی با فیلترهای اعمال شده مطابقت ندارد.</p>
    </div>
    @endif

    <div class="footer">
        <div>سیستم گزارش‌گیری جامع</div>
        <div>تعداد: {{ number_format($summary['total_count']) }} | مجموع: {{ number_format($summary['total_amount']) }}
        </div>
    </div>
</body>

</html>