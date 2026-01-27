<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>گزارش معاملات روزانه</title>
    <style>
        @font-face {
            font-family: 'Vazir';
            src: url('{{ storage_path("fonts/vazir.ttf") }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }
        
        * {
            font-family: 'Vazir', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Vazir', sans-serif;
            direction: rtl;
            font-size: 9pt;
            line-height: 1.6;
            color: #000;
            background: #ffffff;
            padding: 0;
        }
        
        /* Header Styles */
        .header-container {
            border-bottom: 3px solid #2c5282;
            padding-bottom: 15px;
            margin-bottom: 20px;
            background: linear-gradient(to right, #f8fafc, #e6f3ff);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .header-row {
            display: table;
            width: 100%;
            margin-bottom: 10px;
        }
        
        .header-cell {
            display: table-cell;
            padding: 5px 10px;
        }
        
        .header-title {
            font-size: 16pt;
            font-weight: bold;
            color: #2c5282;
            text-align: center;
            margin-bottom: 10px;
        }
        
        .header-subtitle {
            font-size: 11pt;
            color: #4a5568;
            text-align: center;
            margin-bottom: 15px;
        }
        
        /* Report Info Table */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            overflow: hidden;
        }
        
        .info-table th {
            background: #2c5282;
            color: white;
            padding: 8px 12px;
            font-size: 9pt;
            font-weight: bold;
            border: 1px solid #2c5282;
            text-align: right;
            width: 25%;
        }
        
        .info-table td {
            padding: 8px 12px;
            border: 1px solid #e2e8f0;
            font-size: 9pt;
            text-align: right;
            background: white;
        }
        
        /* Main Tables */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            font-size: 8pt;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .data-table thead {
            background: linear-gradient(to right, #2c5282, #4a90e2);
            color: white;
        }
        
        .data-table th {
            padding: 8px 6px;
            border: 1px solid #2c5282;
            font-weight: bold;
            text-align: center;
            font-size: 8.5pt;
            white-space: nowrap;
        }
        
        .data-table tbody tr {
            border-bottom: 1px solid #e2e8f0;
            transition: background-color 0.2s;
        }
        
        .data-table tbody tr:nth-child(even) {
            background-color: #f8fafc;
        }
        
        .data-table tbody tr:hover {
            background-color: #edf2f7;
        }
        
        .data-table td {
            padding: 6px;
            border: 1px solid #e2e8f0;
            text-align: center;
            vertical-align: middle;
        }
        
        /* Status Colors */
        .status-receipt {
            color: #38a169;
            font-weight: bold;
        }
        
        .status-withdrawal {
            color: #e53e3e;
            font-weight: bold;
        }
        
        .balance-positive {
            color: #38a169;
            font-weight: bold;
            direction: ltr;
            text-align: left;
        }
        
        .balance-negative {
            color: #e53e3e;
            font-weight: bold;
            direction: ltr;
            text-align: left;
        }
        
        /* Summary Box */
        .summary-container {
            margin: 20px 0;
            border: 2px solid #2c5282;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .summary-header {
            background: linear-gradient(to right, #2c5282, #4a90e2);
            color: white;
            padding: 10px;
            font-size: 11pt;
            font-weight: bold;
            text-align: center;
        }
        
        /* Balance Cards Table */
        .balance-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        
        .balance-table td {
            padding: 10px;
            border: 1px solid #e2e8f0;
            vertical-align: top;
            width: 33.33%;
        }
        
        .balance-card {
            background: linear-gradient(135deg, #f8fafc, #edf2f7);
            border-radius: 6px;
            padding: 12px;
            height: 100%;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        
        .balance-card-header {
            display: table;
            width: 100%;
            margin-bottom: 10px;
        }
        
        .currency-name {
            display: table-cell;
            font-size: 11pt;
            font-weight: bold;
            color: #2c5282;
            text-align: right;
        }
        
        .currency-amount {
            display: table-cell;
            font-size: 12pt;
            font-weight: bold;
            color: #2d3748;
            direction: ltr;
            text-align: left;
        }
        
        .balance-details {
            font-size: 9pt;
            color: #4a5568;
        }
        
        .balance-row {
            display: table;
            width: 100%;
            margin: 4px 0;
        }
        
        .balance-label {
            display: table-cell;
            text-align: right;
            color: #718096;
        }
        
        .balance-value {
            display: table-cell;
            direction: ltr;
            text-align: left;
            font-weight: 500;
            color: #2d3748;
        }
        
        /* Profit/Loss Section */
        .profit-loss-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .profit-loss-table td {
            padding: 0;
            border: none;
        }
        
        .profit-box, .loss-box {
            padding: 20px;
            text-align: center;
            border-radius: 8px;
            height: 120px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        
        .profit-box {
            background: linear-gradient(135deg, #c6f6d5, #9ae6b4);
            border: 2px solid #38a169;
        }
        
        .loss-box {
            background: linear-gradient(135deg, #fed7d7, #fc8181);
            border: 2px solid #e53e3e;
        }
        
        .profit-title, .loss-title {
            font-size: 10pt;
            font-weight: bold;
            margin-bottom: 8px;
        }
        
        .profit-title {
            color: #22543d;
        }
        
        .loss-title {
            color: #742a2a;
        }
        
        .profit-amount, .loss-amount {
            font-size: 18pt;
            font-weight: bold;
            direction: ltr;
        }
        
        .profit-amount {
            color: #22543d;
        }
        
        .loss-amount {
            color: #742a2a;
        }
        
        /* Footer */
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #e2e8f0;
            text-align: center;
            font-size: 8pt;
            color: #718096;
        }
        
        .footer-info {
            display: table;
            width: 100%;
            margin-top: 10px;
        }
        
        .footer-cell {
            display: table-cell;
            padding: 5px;
            text-align: center;
        }
        
        /* Page Break */
        .page-break {
            page-break-before: always;
        }
        
        /* Utility Classes */
        .text-center {
            text-align: center;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-left {
            text-align: left;
        }
        
        .mb-10 {
            margin-bottom: 10px;
        }
        
        .mb-20 {
            margin-bottom: 20px;
        }
        
        .mt-10 {
            margin-top: 10px;
        }
        
        .mt-20 {
            margin-top: 20px;
        }
        
        .p-10 {
            padding: 10px;
        }
        
        .p-20 {
            padding: 20px;
        }
        
        /* Print Styles */
        @media print {
            body {
                font-size: 8pt;
            }
            
            .data-table {
                page-break-inside: avoid;
            }
            
            .summary-container {
                page-break-inside: avoid;
            }
            
            .balance-table {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>

<!-- Header Section -->
<table class="header-container" width="100%" style="text-align:center; margin: 0 auto;">
    <tr>
        <td>
            <h1 class="header-title" style="margin:0;">
                گزارش معاملات روزانه حسابات و صندوق ها
            </h1>

            <div class="header-subtitle" style="margin-top:6px;">
                @if(isset($customerName) && $customerName)
                    مشتری: {{ $customerName }} - شماره حساب: {{ $customerAccount }}
                @else
                    همه مشتریان
                @endif
            </div>
        </td>
    </tr>
</table>


<!-- Report Information -->
<table class="info-table mb-20">
    <tr>
        <th>بازه زمانی گزارش</th>
        <td>
            @if(isset($filters['fromDate']) && isset($filters['toDate']))
                از {{ $filters['fromDate'] }} تا {{ $filters['toDate'] }}
            @else
                تاریخ امروز
            @endif
        </td>
        <th>تاریخ تولید گزارش</th>
        <td>{{ \Morilog\Jalali\Jalalian::now()->format('Y/m/d H:i:s') }}</td>
    </tr>
    <tr>
        <th>نوع تراکنش</th>
        <td>{{ $filters['transactionType'] ?: 'همه انواع' }}</td>
        <th>نوع حساب</th>
        <td>{{ $filters['accountType'] ?: 'همه حساب‌ها' }}</td>
    </tr>
    <tr>
        <th>ارز</th>
        <td>{{ $filters['currency'] ? ($currencies[$filters['currency']] ?? $filters['currency']) : 'همه ارزها' }}</td>
        <th>تعداد تراکنش‌ها</th>
        <td>{{ $transactions->count() }} تراکنش</td>
    </tr>
</table>

<!-- Transactions Table -->
<div class="summary-container">
    <div class="summary-header">لیست تراکنش‌ها</div>
    <table class="data-table">
        <thead>
            <tr>
                <th width="30">ردیف</th>
                <th width="120">نام حساب</th>
                <th width="70">نوع معامله</th>
                <th width="70">نوع حساب</th>
                <th width="90">مقدار</th>
                <th width="70">ارز</th>
                <th width="100">بیلانس فعلی</th>
                <th width="150">توضیحات</th>
                <th width="100">تاریخ</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $index => $transaction)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>
                    @if (empty($transaction->customer_id) && !empty($transaction->withdraw_id))
                        <span style="color: #e53e3e; font-weight: bold;">برداشت</span>
                    @elseif(empty($transaction->customer_id) && $transaction->is_sell_table == 1)
                        <span style="color: #2c5282; font-weight: bold;">معامله از صندوق</span>
                    @else
                        <div style="font-weight: bold;">{{ $transaction->customer->fullname ?? 'نامشخص' }}</div>
                        <div style="font-size: 7pt; color: #718096;">
                            {{ $transaction->customer->account_number ?? '-' }}
                        </div>
                    @endif
                </td>
                <td>
                    <span class="{{ $transaction->type == 'رسید' ? 'status-receipt' : 'status-withdrawal' }}">
                        {{ $transaction->type }}
                    </span>
                </td>
                <td>{{ $transaction->account_type }}</td>
                <td>
                    <span class="{{ $transaction->type == 'رسید' ? 'status-receipt' : 'status-withdrawal' }}">
                        {{ number_format($transaction->amount, 2) }}
                    </span>
                </td>
                <td>{{ $transaction->currency_fa }}</td>
                <td class="{{ $transaction->balance >= 0 ? 'balance-positive' : 'balance-negative' }}">
                    {{ number_format($transaction->balance, 2) }}
                </td>
                <td>{{ $transaction->description }}</td>
                <td>
                    <div style="font-weight: bold;">{{ explode(' ', $transaction->date)[0] }}</div>
                    <div style="font-size: 7pt; color: #718096;">
                        {{ \Carbon\Carbon::parse($transaction->created_at)->format('H:i') }}
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center p-20" style="color: #718096; font-style: italic;">
                    هیچ تراکنشی در این بازه زمانی یافت نشد
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Summary Table -->
<div class="summary-container mt-20">
    <div class="summary-header">خلاصه گزارش به تفکیک ارز</div>
    <table class="data-table">
        <thead>
            <tr>
                <th>ردیف</th>
                <th>ارز</th>
                <th>رسید نقدی</th>
                <th>برد نقدی</th>
                <th>رسید بانکی</th>
                <th>برد بانکی</th>
                <th>بیلانس نقدی</th>
                <th>بیلانس بانکی</th>
            </tr>
        </thead>
        <tbody>
            @forelse($summary as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td style="font-weight: bold; color: #2c5282;">{{ $item->currency_fa }}</td>
                <td class="status-receipt">{{ number_format($item->receipt_cash, 2) }}</td>
                <td class="status-withdrawal">{{ number_format($item->withdrawal_cash, 2) }}</td>
                <td class="status-receipt">{{ number_format($item->receipt_bank, 2) }}</td>
                <td class="status-withdrawal">{{ number_format($item->withdrawal_bank, 2) }}</td>
                <td class="{{ $item->balance_cash >= 0 ? 'balance-positive' : 'balance-negative' }}">
                    {{ number_format($item->balance_cash, 2) }}
                </td>
                <td class="{{ $item->balance_bank >= 0 ? 'balance-positive' : 'balance-negative' }}">
                    {{ number_format($item->balance_bank, 2) }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center p-20" style="color: #718096; font-style: italic;">
                    داده‌ای برای نمایش وجود ندارد
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
<!-- Current Balances -->
<div class="summary-container mt-20">
    <div class="summary-header">
        موجودی صندوق نقدی و بانکی 
        @if(isset($filters['fromDate']) && isset($filters['toDate']))
            (از {{ $filters['fromDate'] }} تا {{ $filters['toDate'] }})
        @elseif(isset($filters['toDate']))
            (تا تاریخ {{ $filters['toDate'] }})
        @endif
    </div>
    
    <table style="width: 100%; border-collapse: collapse; margin: 15px 0;">
        <thead>
            <tr>
                <th style="background: #2c5282; color: white; padding: 10px; border: 1px solid #2c5282; text-align: center; width: 20%;">ارز</th>
                <th style="background: #2c5282; color: white; padding: 10px; border: 1px solid #2c5282; text-align: center; width: 20%;">موجودی نقدی</th>
                <th style="background: #2c5282; color: white; padding: 10px; border: 1px solid #2c5282; text-align: center; width: 20%;">موجودی بانکی</th>
                <th style="background: #2c5282; color: white; padding: 10px; border: 1px solid #2c5282; text-align: center; width: 20%;">مجموع</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalAll = 0;
                $totalSafe = 0;
                $totalBank = 0;
            @endphp
            
            @foreach($totalBalanceByCurrency as $currencyCode => $totalAmount)
                @php
                    $safe = $currencySafeBalance[$currencyCode] ?? 0;
                    $bank = $bankAccountBalance[$currencyCode] ?? 0;
                    $currencyName = $currencies[$currencyCode] ?? $currencyCode;
                    
                    // محاسبه مجموع‌ها
                    $totalAll += $totalAmount;
                    $totalSafe += $safe;
                    $totalBank += $bank;
                @endphp
                
                @if($totalAmount != 0)
                <tr style="border-bottom: 1px solid #e2e8f0;">
                    <td style="padding: 12px; border: 1px solid #e2e8f0; text-align: center; font-weight: bold; color: #2c5282; background: #f8fafc;">
                        {{ $currencyName }}
                    </td>
                    <td style="padding: 12px; border: 1px solid #e2e8f0; text-align: left; direction: ltr; font-family: monospace; {{ $safe >= 0 ? 'color: #38a169;' : 'color: #e53e3e;' }}">
                        {{ number_format($safe, 2) }}
                    </td>
                    <td style="padding: 12px; border: 1px solid #e2e8f0; text-align: left; direction: ltr; font-family: monospace; {{ $bank >= 0 ? 'color: #38a169;' : 'color: #e53e3e;' }}">
                        {{ number_format($bank, 2) }}
                    </td>
                    <td style="padding: 12px; border: 1px solid #e2e8f0; text-align: left; direction: ltr; font-family: monospace; font-weight: bold; {{ $totalAmount >= 0 ? 'color: #2c5282;' : 'color: #e53e3e;' }}">
                        {{ number_format($totalAmount, 2) }}
                    </td>
                 
                </tr>
                @endif
            @endforeach
            
         
        </tbody>
    </table>
    
  
</div>

<!-- Profit/Loss Section -->
<table class="profit-loss-table mt-20">
    <tr>
        <td width="50%">
            <div class="profit-box">
                <div class="profit-title">
                    @if(isset($filters['fromDate']) && isset($filters['toDate']))
                        سود بازه زمانی
                    @else
                        سود امروز
                    @endif
                </div>
                <div class="profit-amount">{{ number_format($todayProfit, 2) }}</div>
            </div>
        </td>
        <td width="50%">
            <div class="loss-box">
                <div class="loss-title">
                    @if(isset($filters['fromDate']) && isset($filters['toDate']))
                        ضرر بازه زمانی
                    @else
                        ضرر امروز
                    @endif
                </div>
                <div class="loss-amount">{{ number_format($todayLoss, 2) }}</div>
            </div>
        </td>
    </tr>
</table>

<!-- Footer -->
<div class="footer">
    <div class="footer-info">
      
        <div class="footer-cell">
            <strong>تعداد صفحات: </strong>{{ $transactions->count() > 0 ? ceil($transactions->count() / 20) : 1 }} صفحه<br>
            <strong>تعداد رکوردها: </strong>{{ $transactions->count() }} رکورد
        </div>
      
    </div>
</div>

</body>
</html>