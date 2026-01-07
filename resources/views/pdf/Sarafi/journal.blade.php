<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>گزارش معاملات روزانه</title>
    <style>
        @page {
            margin-top: 20mm;
            margin-bottom: 20mm;
            margin-left: 10mm;
            margin-right: 10mm;
        }
        
        @page :first {
            margin-top: 15mm;
        }
        
        body {
            font-family: dejavusans, sans-serif;
            font-size: 10pt;
            line-height: 1.5;
            color: #000;
            margin: 0;
            padding: 0;
            direction: rtl;
            text-align: right;
        }
        
        .header {
            text-align: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #2B65E5;
        }
        
        .header h1 {
            color: #2B65E5;
            font-size: 16pt;
            margin: 0 0 5px 0;
        }
        
        .header p {
            margin: 0;
            font-size: 9pt;
            color: #666;
        }
        
        .filters-section {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            border: 1px solid #dee2e6;
            font-size: 9pt;
        }
        
        .filters-section h3 {
            margin-top: 0;
            margin-bottom: 10px;
            font-size: 10pt;
        }
        
        .filter-row {
            display: table;
            width: 100%;
            margin-bottom: 5px;
        }
        
        .filter-item {
            display: table-row;
        }
        
        .filter-label {
            font-weight: bold;
            display: table-cell;
            padding: 2px 5px;
            width: 120px;
            vertical-align: top;
        }
        
        .filter-value {
            display: table-cell;
            padding: 2px 5px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
            font-size: 9pt;
            page-break-inside: auto;
        }
        
        th {
            background-color: #2B65E5;
            color: white;
            padding: 8px 5px;
            border: 1px solid #ddd;
            font-weight: bold;
            text-align: center;
            font-size: 9pt;
        }
        
        td {
            padding: 6px 5px;
            border: 1px solid #ddd;
            text-align: center;
            font-size: 8pt;
        }
        
        .transaction-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .text-green {
            color: #008000;
        }
        
        .text-red {
            color: #ff0000;
        }
        
        .summary-table {
            margin-top: 20px;
        }
        
        .summary-table th {
            background-color: #4f46e5;
        }
        
        .total-row {
            font-weight: bold;
            background-color: #e9ecef;
        }
        
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #dee2e6;
            text-align: center;
            font-size: 8pt;
            color: #666;
        }
        
        .page-break {
            page-break-before: always;
        }
        
        .no-data {
            text-align: center;
            padding: 20px;
            color: #666;
            font-style: italic;
        }
        
        .customer-info {
            margin: 5px 0;
        }
        
        .transaction-date {
            font-size: 8pt;
            color: #666;
        }
        
        /* برای جلوگیری از شکستن ردیف‌ها در صفحات */
        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }
        
        thead {
            display: table-header-group;
        }
        
        tfoot {
            display: table-footer-group;
        }
        
        /* استایل برای چاپ */
        @media print {
            .no-print {
                display: none !important;
            }
            
            body {
                font-size: 9pt;
            }
            
            table {
                font-size: 8pt;
            }
            
            th, td {
                padding: 4px 3px;
            }
        }
    </style>
</head>
<body>

<div class="header">
    <h1>گزارش معاملات روزانه حسابات و صندوق‌ها</h1>
    <p>تاریخ تولید: {{ now()->format('Y/m/d H:i') }}</p>
</div>

@if($transactions->count() > 0)
    <h3 style="margin-top: 20px; margin-bottom: 10px; font-size: 11pt;">لیست تراکنش‌ها ({{ $transactions->count() }} رکورد)</h3>
    <table class="transaction-table">
        <thead>
            <tr>
                <th width="30">ردیف</th>
                <th width="120">نام حساب</th>
                <th width="70">نوع معامله</th>
                <th width="70">نوع حساب</th>
                <th width="80">مقدار</th>
                <th width="50">ارز</th>
                <th width="80">بیلانس فعلی</th>
                <th width="150">توضیحات</th>
                <th width="90">تاریخ</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $index => $transaction)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>
                    <div style="font-weight: bold;">{{ $transaction->customer->fullname ?? 'نامشخص' }}</div>
                    <div style="font-size: 7pt; color: #666;">{{ $transaction->customer->account_number ?? '' }}</div>
                </td>
                <td>{{ $transaction->type }}</td>
                <td>{{ $transaction->account_type }}</td>
                <td class="{{ $transaction->type == 'رسید' ? 'text-green' : 'text-red' }}">
                    {{ number_format($transaction->amount, 2) }}
                </td>
                <td>{{ $transaction->currency }}</td>
                <td>{{ number_format($transaction->balance, 2) }}</td>
                <td style="text-align: right; padding: 0 5px;">{{ $transaction->description }}</td>
                <td>
                    <div>{{ $transaction->date }}</div>
                    <div class="transaction-date">{{ $transaction->created_at->format('H:i') }}</div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@else
    <div class="no-data">
        هیچ تراکنشی برای نمایش وجود ندارد
    </div>
@endif

@if($summary->count() > 0)
    <div class="page-break"></div>
    <h3 style="margin-top: 20px; margin-bottom: 10px; font-size: 11pt;">خلاصه گزارش به تفکیک ارز</h3>
    <table class="summary-table">
        <thead>
            <tr>
                <th width="30">ردیف</th>
                <th width="60">ارز</th>
                <th width="80">رسید نقدی</th>
                <th width="80">برد نقدی</th>
                <th width="80">رسید بانکی</th>
                <th width="80">برد بانکی</th>
                <th width="80">بیلانس نقدی</th>
                <th width="80">بیلانس بانکی</th>
                <th width="80">مجموع کل</th>
            </tr>
        </thead>
        <tbody>
            @foreach($summary as $index => $item)
            @php
                $total = $item->balance_cash + $item->balance_bank;
            @endphp
            <tr>
                <td>{{ $index + 1 }}</td>
                <td><strong>{{ $item->currency }}</strong></td>
                <td class="text-green">{{ number_format($item->receipt_cash, 2) }}</td>
                <td class="text-red">{{ number_format($item->withdrawal_cash, 2) }}</td>
                <td class="text-green">{{ number_format($item->receipt_bank, 2) }}</td>
                <td class="text-red">{{ number_format($item->withdrawal_bank, 2) }}</td>
                <td class="{{ $item->balance_cash >= 0 ? 'text-green' : 'text-red' }}">
                    <strong>{{ number_format($item->balance_cash, 2) }}</strong>
                </td>
                <td class="{{ $item->balance_bank >= 0 ? 'text-green' : 'text-red' }}">
                    <strong>{{ number_format($item->balance_bank, 2) }}</strong>
                </td>
                <td class="total-row">
                    <strong>{{ number_format($total, 2) }}</strong>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endif

<div class="footer">
    <p>این گزارش توسط سیستم مدیریت صرافی دیجیتال تولید شده است.</p>
    <p>تعداد کل رکوردها: {{ $transactions->count() }} | تاریخ تولید: {{ now()->format('Y/m/d H:i') }}</p>
</div>

</body>
</html>