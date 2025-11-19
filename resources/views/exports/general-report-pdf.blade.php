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

        .currency-summary {
            margin: 10px 0;
            padding: 8px;
            background: #e8f5e8;
            border-radius: 4px;
            border-right: 3px solid #4caf50;
        }

        .currency-summary h4 {
            margin: 0 0 5px 0;
            font-size: 10px;
            color: #2e7d32;
        }

        .currency-items {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .currency-item {
            background: white;
            padding: 4px 8px;
            border-radius: 3px;
            border: 1px solid #c8e6c9;
            font-size: 8px;
        }

        .currency-amount {
            font-weight: bold;
            color: #1b5e20;
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

    <!-- نمایش مجموع مبالغ هر ارز -->
    @if(isset($summary['currency_totals']) && count($summary['currency_totals']) > 0)
    <div class="currency-summary">
        <h4>📊 مجموع مبالغ بر اساس ارز:</h4>
        <div class="currency-items">
            @foreach($summary['currency_totals'] as $currency => $total)
            <div class="currency-item">
                <strong>
                    @switch($currency)
                    @case('AFN') افغانی @break
                    @case('USD') دالر @break
                    @default {{ $currency }}
                    @endswitch
                </strong>:
                <span class="currency-amount">{{ number_format($total) }}</span>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    @if($data && $data->count() > 0)
    <table>
        <thead>
            <tr>
                <th class="row-number">#</th>
                @switch($reportType)
                @case('accounting')
                <th>مارکت</th>
                <th>نوع</th>
                <th>دوکاندار</th>
                <th>مصرف</th>
                @if($report->expanses_type == 'پول برق')
                <th>درجه فعلی</th>
                <th>درجه قبلی</th>
                <th>مقدار مصرف</th>
                <th>قیمت فی کیلووات</th>
                @endif
                <th>مبلغ</th>
                <th>واحد</th>
                <th>تاریخ</th>
                <th>وضعیت</th>
                @break

                @case('withdraw_salary')
                <th>نوع</th>
                <th>برداشت از</th>
                <th>شخص</th>
                <th>مبلغ</th>
                <th>واحد پول</th>
                <th>تاریخ</th>
                <th>توضیحات</th>
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
                <th>واحد</th>
                <th>تاریخ</th>
                @break
                @case('loan')
                <th>مارکت</th>
                <th>نوع</th>
                <th>نام</th>
                <th>مبلغ اصلی</th>
                <th>پرداخت</th>
                <th>باقی</th>
                <th>واحد</th>
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
                @case('salary')
                <th>مارکت</th>
                <th>کارمند</th>
                <th>حقوق</th>
                <th>پرداخت شده</th>
                <th>باقی مانده</th>
                <th>قرضه</th>
                <th>واحد</th>
                <th>تاریخ</th>
                <th>وضعیت کسر</th>
                @break
                @endswitch
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $report)
            <tr>
                <td class="row-number">{{ $index + 1 }}</td>
                @switch($reportType)
                @case('accounting')
                <!-- Data Section -->
                <td>{{ $report->market->name ?? '-' }}</td>
                <td>{{ $report->type }}</td>
                <td>{{ $report->shopkeeper->fullname ?? '-' }}</td>
                <td>{{ $report->expanses_type }}</td>
                @if($report->expanses_type == 'پول برق')
                <td>{{ $report->current_degree ?? '-' }}</td>
                <td>{{ $report->past_degree ?? '-' }}</td>
                @php
                $current = $report->current_degree ?? null;
                $past = $report->past_degree ?? null;
                $usage = ($current !== null && $past !== null) ? ($current - $past) : '-';
                @endphp
                <td>{{ $usage }}</td>
                <td>{{ number_format($report->degree_price ?? 0) }}</td>
                @endif
                <td>{{ number_format($report->price) }}</td>
                <td>
                    @switch($report->currency)
                    @case('AFN') افغانی @break
                    @case('USD') دالر @break
                    @default {{ $report->currency }}
                    @endswitch
                </td>
                <td>{{ $report->paid_date ? \Morilog\Jalali\Jalalian::fromDateTime($report->paid_date)->format('Y/m/d')
                    : '-' }}</td>
                <td>{{ $report->cleared ? '✅' : '⏳' }}</td>
                @break

                @case('withdraw_salary')
                <td>

                    ({{ $report->record_type === 'withdraw' ? 'برداشت' : 'معاش' }})
                </td>

                <td>
                    @if($report->record_type == 'withdraw' || $report->record_type == 'withdraw_salary')
                    {{ $report->expanses_type ?? '-' }}
                    @else
                    {{ $report->reduce_from ?? '-' }}
                    @endif
                </td>

                <td>
                    {{ $report->staff->fullname
                    ?? $report->customer->fullname
                    ?? $report->shopkeeper->fullname
                    ?? '-' }}
                </td>
                <td>
                    <span class="font-bold text-gray-900">
                        {{ number_format(
                        ($report->record_type === 'withdraw')
                        ? ($report->amount ?? 0)
                        : ($report->record_type === 'salary' ? ($report->paid ?? 0) : 0)
                        ) }}
                    </span>
                </td>

                <td>
                    @switch($report->currency)
                    @case('AFN') افغانی @break
                    @case('USD') دالر @break
                    @default {{ $report->currency }}
                    @endswitch
                </td>

                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{
                    ($report->record_type == 'withdraw' || $report->record_type == 'withdraw_salary')
                    ? (\Morilog\Jalali\Jalalian::fromDateTime($report->created_at)->format('Y/m/d') ?? '-')
                    : (\Morilog\Jalali\Jalalian::fromDateTime($report->paid_date ??
                    $report->created_at)->format('Y/m/d') ?? '-')
                    }}
                </td>

                <td>{{ $report->description ?? '-' }}</td>
                @break


                @case('deposit')
                <td>{{ $report->accounting->market->name ?? '-' }}</td>
                <td>{{ $report->accounting->shopkeeper->fullname ?? '-' }}</td>
                <td>{{ $report->expanses_type }}</td>
                <td>{{ number_format($report->price) }}</td>
                <td>{{ number_format($report->paid) }}</td>
                <td>{{ number_format($report->remained) }}</td>
                <td>
                    @switch($report->currency)
                    @case('AFN') افغانی @break
                    @case('USD') دالر @break
                    @default {{ $report->currency }}
                    @endswitch
                </td>
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
                <td>
                    @switch($report->currency)
                    @case('AFN') افغانی @break
                    @case('USD') دالر @break
                    @default {{ $report->currency }}
                    @endswitch
                </td>
                <td>{{ $report->date ? \Morilog\Jalali\Jalalian::fromDateTime($report->date)->format('Y/m/d') : '-' }}
                </td>
                @break

                @case('payment')
                <td>#{{ $report->loan_id }}</td>
                <td>{{ number_format($report->amount) }}</td>
                <td>
                    @switch($report->currency)
                    @case('AFN') افغانی @break
                    @case('USD') دالر @break
                    @default {{ $report->currency }}
                    @endswitch
                </td>
                <td>{{ $report->date ? \Morilog\Jalali\Jalalian::fromDateTime($report->date)->format('Y/m/d') : '-' }}
                </td>
                <td>{{ $report->description ?? '-' }}</td>
                @break

                @case('buy')
                <td>{{ $report->market->name ?? '-' }}</td>
                <td>{{ $report->customer->fullname ?? '-' }}</td>
                <td>{{ $report->property }}</td>
                <td>{{ number_format($report->price) }}</td>
                <td>
                    @switch($report->currency)
                    @case('AFN') افغانی @break
                    @case('USD') دالر @break
                    @default {{ $report->currency }}
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
                    @case('AFN') افغانی @break
                    @case('USD') دالر @break
                    @default {{ $report->currency }}
                    @endswitch
                </td>
                <td>{{ $report->date ? \Morilog\Jalali\Jalalian::fromDateTime($report->date)->format('Y/m/d') : '-' }}
                </td>
                <td>{{ $report->details ?? '-' }}</td>
                @break

                @case('withdraw_log')
                <td>{{ $report->expanses_type }}</td>
                <td>{{ $report->recipient_name }}</td>
                <td>{{ number_format($report->amount) }}</td>
                <td>
                    @switch($report->currency)
                    @case('AFN') افغانی @break
                    @case('USD') دالر @break
                    @default {{ $report->currency }}
                    @endswitch
                </td>
                <td>{{ $report->description ?? '-' }}</td>
                <td>{{ $report->created_at ?
                    \Morilog\Jalali\Jalalian::fromDateTime($report->created_at)->format('Y/m/d') : '-' }}</td>
                @break

                @case('salary')
                <td>{{ $report->market->name ?? '-' }}</td>
                <td>{{ $report->staff->fullname ?? '-' }}</td>
                <td>{{ number_format($report->salary) }}</td>
                <td>{{ number_format($report->paid) }}</td>
                <td>{{ number_format($report->remained) }}</td>
                <td>{{ number_format($report->loan) }}</td>
                <td>
                    @switch($report->currency)
                    @case('AFN') افغانی @break
                    @case('USD') دالر @break
                    @default {{ $report->currency }}
                    @endswitch
                </td>
                <td>{{ $report->paid_date ? \Morilog\Jalali\Jalalian::fromDateTime($report->paid_date)->format('Y/m/d')
                    : '-' }}</td>
                <td>{{ $report->is_reduce ? 'فعال' : 'غیرفعال' }}</td>
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
        <div>سیستم گزارش‌گیری جامع</div>
        <div>تعداد: {{ number_format($summary['total_count']) }} | مجموع کل: {{ number_format($summary['total_amount'])
            }}</div>
        @if(isset($summary['currency_totals']) && count($summary['currency_totals']) > 0)
        <div style="margin-top: 3px;">
            @foreach($summary['currency_totals'] as $currency => $total)
            <span style="margin: 0 5px;">
                {{ $currency }}: {{ number_format($total) }}
            </span>
            @endforeach
        </div>
        @endif
    </div>

    <!-- جدول موجودی صندوق -->
    @if(isset($safeRows) && count($safeRows) > 0)
    <div style="margin-top: 20px;">
        <h4 style="text-align: center; margin-bottom: 5px;">💰 موجودی صندوق</h4>
        <table style="width:100%; border-collapse: collapse; font-size: 8px;">
            <thead>
                <tr>
                    <th style="border:1px solid #555; padding:3px;">نوع مصرف</th>
                    <th style="border:1px solid #555; padding:3px;">افغانی</th>
                    <th style="border:1px solid #555; padding:3px;">دالر</th>
                    <th style="border:1px solid #555; padding:3px;">یورو</th>
                    <th style="border:1px solid #555; padding:3px;">تومان</th>
                </tr>
            </thead>
            <tbody>
                @php
                $total_af = $total_us = $total_er = $total_ir = 0;
                @endphp
                @foreach ($safeRows as $row)
                @php
                $total_af += $row['af'];
                $total_us += $row['us'];
                $total_er += $row['er'];
                $total_ir += $row['ir'];
                @endphp
                <tr>
                    <td style="border:1px solid #ddd; padding:3px;">{{ $row['type'] }}</td>
                    <td style="border:1px solid #ddd; padding:3px; text-align:right;">{{ number_format($row['af']) }}
                    </td>
                    <td style="border:1px solid #ddd; padding:3px; text-align:right;">{{ number_format($row['us']) }}
                    </td>
                    <td style="border:1px solid #ddd; padding:3px; text-align:right;">{{ number_format($row['er']) }}
                    </td>
                    <td style="border:1px solid #ddd; padding:3px; text-align:right;">{{ number_format($row['ir']) }}
                    </td>
                </tr>
                @endforeach
                <!-- جمع کل -->
                <tr style="font-weight:bold; background:#f0f0f0;">
                    <td style="border:1px solid #555; text-align:center;">جمع کل</td>
                    <td style="border:1px solid #555; text-align:right;">{{ number_format($total_af) }}</td>
                    <td style="border:1px solid #555; text-align:right;">{{ number_format($total_us) }}</td>
                    <td style="border:1px solid #555; text-align:right;">{{ number_format($total_er) }}</td>
                    <td style="border:1px solid #555; text-align:right;">{{ number_format($total_ir) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    @endif

</body>

</html>