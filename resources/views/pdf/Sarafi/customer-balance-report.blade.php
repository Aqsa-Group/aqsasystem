<!DOCTYPE html>
<html dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: DejaVu Sans, Shabnam, sans-serif;
            direction: rtl;
            text-align: right;
            font-size: 12px;
            line-height: 1.4;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #333;
            padding-bottom: 8px;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
            color: #2B65E5;
        }

        .header p {
            margin: 5px 0;
            font-size: 12px;
            color: #666;
        }

        .filters {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
        }

        .filters h3 {
            margin: 0 0 8px 0;
            font-size: 14px;
            color: #333;
        }

        .filter-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
        }

        .filter-item {
            display: flex;
            justify-content: space-between;
            padding: 3px 0;
        }

        .filter-label {
            font-weight: bold;
            color: #555;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 10px;
        }

        th {
            background-color: #2B65E5;
            color: white;
            padding: 6px 4px;
            border: 1px solid #ddd;
            font-weight: bold;
            text-align: center;
        }

        td {
            padding: 5px 3px;
            border: 1px solid #ddd;
            text-align: center;
        }

        .currency-number {
            text-align: left;
            direction: ltr;
            font-family: 'Courier New', monospace;
        }

        .total-row {
            background-color: #e8f5e8;
            font-weight: bold;
        }

        .footer {
            margin-top: 15px;
            padding-top: 8px;
            border-top: 1px solid #ddd;
            font-size: 10px;
            color: #666;
        }

        .no-data {
            text-align: center;
            padding: 20px;
            color: #999;
            font-style: italic;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        <p>تاریخ چاپ: {{ $print_date }}</p>
        <p>تعداد مشتریان: {{ $total_customers }} نفر</p>
    </div>

    @if(count($reports) > 0)
    <table>
        <thead>
            <tr>
                <th width="30">#</th>
                <th width="80">نمبرحساب</th>
                <th width="120">نام حساب</th>
                <th width="100">مشتری معرف</th>
                <th width="70">آخرین تاریخ</th>
                @foreach($currencies as $currencyCode => $currencyName)
                <th width="60">{{ $currencyName }}</th>
                @endforeach
                   @php
                    $latestExchangeRate = \App\Models\Sarafi\ExchangeRates::latest()->first();
                    $sourceCurrency = $latestExchangeRate->source_currency ?? 'دالر';
                    @endphp
                <th width="80">بیلانس به {{$sourceCurrency }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reports as $index => $report)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $report['account_number'] }}</td>
                <td>{{ $report['fullname'] }}</td>
                <td>{{ $report['related_customer_name'] ?? '-' }}</td>
                <td>{{ $report['last_date'] ? \Carbon\Carbon::parse($report['last_date'])->format('Y/m/d') : '-' }}</td>
                @foreach($currencies as $currencyCode => $currencyName)
                <td class="currency-number">{{ number_format($report['balances'][$currencyCode] ?? 0, 2) }}</td>
                @endforeach
                <td class="currency-number">{{ number_format($report['total_balance'], 2) }}</td>
            </tr>
            @endforeach




        </tbody>
    </table>


    @if(isset($totals) && count($totals) > 0)
    <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
        <thead>
            <tr class="total-row" style="background-color: #2B65E5; color: white; text-align: center;">
                <th style="padding:5px;">نام ارز</th>
                <th style="padding:5px;">نقدی</th>
                <th style="padding:5px;">بانکی</th>
                <th style="padding:5px;">مجموع</th>
                <th style="padding:5px;">بیلانس به دالر</th>
            </tr>
        </thead>
        <tbody>
            @php
                $latestProfitRate = \App\Models\Sarafi\ProfitRate::latest()->first();
                $accountTypeForConversion = 'cash';
                $defaultRates = [
                    'afn' => 66.20,
                    'usd' => 1,
                    'irr' => 110000.00,
                    'eur' => 0.93,
                    'pkr' => 277.78,
                    'aed' => 3.67,
                    'try' => 32.26,
                    'cny' => 7.24,
                ];
                $exchangeRates = [];
                foreach ($defaultRates as $currency => $fallback) {
                    $column = $currency.'_buy_'.($accountTypeForConversion === 'bank' ? 'bank' : 'cash');
                    $exchangeRates[$currency] = ($latestProfitRate->$column ?? 0) > 0 ? $latestProfitRate->$column : $fallback;
                }

                $grandTotalUsd = 0; // جمع کل دلاری
            @endphp

            @foreach($currencies as $currencyCode => $currencyName)
                @php
                    $cash = $totals[$currencyCode]['cash'] ?? 0;
                    $bank = $totals[$currencyCode]['bank'] ?? 0;
                    $total = $totals[$currencyCode]['total'] ?? 0;

                    // فقط ارزهایی که موجودی دارند
                    if ($total == 0) continue;

                    $totalUsd = isset($exchangeRates[$currencyCode]) && $exchangeRates[$currencyCode] > 0
                                ? $total / $exchangeRates[$currencyCode]
                                : 0;

                    $grandTotalUsd += $totalUsd;
                @endphp
                <tr class="total-row" style="text-align:center;" style="background-color: white">
                    <td style="padding:5px;">{{ $currencyName }}</td>
                    <td style="padding:5px; text-align:right;" dir="ltr">{{ number_format($cash, 2) }}</td>
                    <td style="padding:5px; text-align:right;" dir="ltr">{{ number_format($bank, 2) }}</td>
                    <td style="padding:5px; text-align:right;" dir="ltr">{{ number_format($total, 2) }}</td>
                    <td style="padding:5px; text-align:right;" dir="ltr">{{ number_format($totalUsd, 2) }}</td>
                </tr>
            @endforeach

            <tr class="total-row" style="font-weight:bold; background:#d0e9d0; text-align:center;">
                <td colspan="4" style="padding:5px;">جمع کل بیلانس به دالر</td>
                <td style="padding:5px; text-align:right;" dir="ltr">{{ number_format($grandTotalUsd, 2) }} دالر</td>
            </tr>
        </tbody>
    </table>
@endif

    @else
    <div class="no-data">
        هیچ داده‌ای برای نمایش وجود ندارد
    </div>
    @endif



</body>

</html>