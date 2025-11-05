<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        /* فونت و بدنه */
        body {
            font-family: "Tahoma", "Segoe UI", sans-serif;
            font-size: 11px;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            color: #2c3e50;
            background: #f7f8fa;
        }

        /* هدر اصلی صرافی */
        .main-header {
            text-align: center;
            margin-bottom: 10px;
            color: #34495e;
        }
        .main-header h2 {
            margin: 0;
            font-size: 20px;
            font-weight: 700;
        }

        /* هدر گزارش */
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 8px;
            border-bottom: 2px solid #34495e;
        }
        .header h1 {
            font-size: 18px;
            font-weight: 700;
            margin: 0;
            color: #34495e;
        }

        /* کارت اطلاعات گزارش */
        .info {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin-bottom: 20px;
            padding: 15px 20px;
            background: #ecf0f1;
            border-left: 6px solid #2980b9;
            border-radius: 6px;
            font-size: 10.5px;
        }
        .info-item {
            display: flex;
            gap: 5px;
            align-items: center;
        }
        .info-item strong {
            color: #2c3e50;
        }
        .customer-name {
            color: #2980b9;
            font-weight: 600;
        }

        /* جدول تراکنش‌ها */
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
            margin-bottom: 20px;
            font-size: 10px;
        }
        th, td {
            padding: 8px 6px;
            border: 1px solid #dfe6ec;
            text-align: center;
        }
        th {
            background-color: #2980b9;
            color: white;
            font-weight: 600;
        }
        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tbody tr:hover {
            background-color: #e1f0fb;
        }

        /* وضعیت تراکنش */
        .status-confirmed {
            background-color: #dff0d8;
            color: #3c763d;
            padding: 4px 8px;
            border-radius: 5px;
            font-size: 9px;
            font-weight: 600;
        }
        .status-pending {
            background-color: #fcf8e3;
            color: #8a6d3b;
            padding: 4px 8px;
            border-radius: 5px;
            font-size: 9px;
            font-weight: 600;
        }

        /* جدول خلاصه موجودی */
        .summary-section {
            padding: 15px;
            background: #f0f7ff;
            border: 1px solid #cce5ff;
            border-radius: 6px;
        }
        .summary-title {
            font-weight: 700;
            font-size: 12px;
            color: #2980b9;
            text-align: center;
            margin-bottom: 10px;
        }
        .summary-table th {
            background-color: #1c5f9e;
            color: white;
            font-weight: 600;
        }
        .summary-table td {
            font-weight: 500;
        }

        /* متن وقتی داده‌ای نیست */
        .no-data {
            text-align: center;
            padding: 25px 15px;
            color: #7f8c8d;
            font-style: italic;
            background: #ffffff;
            border-radius: 6px;
            margin: 20px 0;
            border: 1px dashed #d1d5da;
        }

        /* سلول‌های عددی */
        .amount-cell {
            font-family: 'Courier New', monospace;
            font-weight: 500;
        }

        .currency-header {
            font-weight: 600;
            background-color: #1c5f9e !important;
        }

        /* فوتر */
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #95a5a6;
            border-top: 1px solid #dfe6ec;
            padding-top: 12px;
        }
    </style>
</head>
<body dir="rtl">

    <!-- هدر صرافی -->
    <div class="main-header">
        <h2>صرافی {{ Auth::guard('sarafi')->user()->sarafi_name }}</h2>
    </div>

    <!-- هدر گزارش -->
    <div class="header">
        <h1>گزارش تراکنش‌های انجام شده</h1>
    </div>

    <!-- اطلاعات گزارش -->
    <div class="info">
        <div class="info-item"><strong>حساب:</strong> <span class="customer-name">{{ $customer_name }}</span></div>
        <div class="info-item"><strong>شماره حساب:</strong> <span class="customer-name">{{ $customer->account_number ?? '---' }}</span></div>
        <div class="info-item"><strong>بازه زمانی:</strong> {{ $start_date }} تا {{ $end_date }}</div>
    </div>

    <!-- جدول تراکنش‌ها -->
    @if(count($transactions) > 0)
    <table>
        <thead>
            <tr>
                <th rowspan="2">#</th>
                <th rowspan="2">تاریخ</th>
                <th rowspan="2">نوع حساب</th>
                <th rowspan="2">شماره سند</th>
                <th rowspan="2">توضیحات</th>
                <th rowspan="2">توسط</th>
                @foreach($active_currencies as $code => $currency)
                <th colspan="2" class="currency-header">{{ $currency['name_fa'] }}</th>
                @endforeach
                <th rowspan="2">وضعیت</th>
            </tr>
            <tr>
                @foreach($active_currencies as $code => $currency)
                <th>رسید</th>
                <th>برد</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $index => $transaction)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $transaction->date }}</td>
                <td>{{ $transaction->account_type }}</td>
                <td>{{ $transaction->document_number ?? 'SN-' . str_pad($transaction->id, 3, '0', STR_PAD_LEFT) }}</td>
                <td>{{ Str::limit($transaction->description, 20) }}</td>
                <td>{{ Str::limit($transaction->by, 15) }}</td>
                @foreach($active_currencies as $code => $currency)
                <td class="amount-cell">{{ $transaction->currency == $code && $transaction->type == 'رسید' ? number_format($transaction->amount) : '' }}</td>
                <td class="amount-cell">{{ $transaction->currency == $code && $transaction->type == 'برد' ? number_format($transaction->amount) : '' }}</td>
                @endforeach
                <td>
                    <span class="{{ $transaction->status == 'تأیید شده' ? 'status-confirmed' : 'status-pending' }}">
                        {{ $transaction->status ?? 'در انتظار' }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="no-data">
         هیچ تراکنشی در این بازه زمانی یافت نشد
    </div>
    @endif

    <!-- بخش خلاصه موجودی -->
    @if(count($balances) > 0)
    <div class="summary-section">
        <div class="summary-title"> خلاصه موجودی‌ها</div>
        <table class="summary-table">
            <thead>
                <tr>
                    <th>واحد پول</th>
                    <th>موجودی قبلی</th>
                    <th>رسید</th>
                    <th>برد</th>
                    <th>بیلانس</th>
                    <th>موجودی فعلی</th>
                    <th>وضعیت</th>
                </tr>
            </thead>
            <tbody>
                @foreach($balances as $balance)
                <tr>
                    <td><strong>{{ $balance['name_fa'] }}</strong></td>
                    <td class="amount-cell">{{ number_format($balance['previous_balance']) }}</td>
                    <td class="amount-cell">{{ number_format($balance['received']) }}</td>
                    <td class="amount-cell">{{ number_format($balance['spent']) }}</td>
                    <td class="amount-cell">{{ number_format($balance['balance']) }}</td>
                    <td class="amount-cell"><strong>{{ number_format($balance['current_balance']) }}</strong></td>
                    <td>
                        <span class="{{ $balance['status'] == 'طلبکار' ? 'status-confirmed' : 'status-pending' }}">
                            {{ $balance['status'] }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- فوتر -->
    <div class="footer">
         تاریخ چاپ: {{ $generated_at }} | سیستم صرافی
    </div>
</body>
</html>
