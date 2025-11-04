<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>نرخ ارز</title>
    <style>
        body {
            font-family: 'Shabnam', sans-serif;
            font-size: 10px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 10px;
            border-bottom: 1px dashed #000;
            padding-bottom: 5px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            padding: 3px 2px;
            text-align: center;
            border: 1px solid #000;
        }
        .table th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        .currency-row {
            border-bottom: 1px solid #ddd;
        }
        .footer {
            text-align: center;
            margin-top: 10px;
            font-size: 8px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2 style="margin: 0; font-size: 12px;">نرخ ارز</h2>
        <p style="margin: 2px 0; font-size: 9px;">
            تاریخ: {{ \Morilog\Jalali\Jalalian::fromDateTime($exchangeRate->created_at)->format('Y/m/d') }}
        </p>
        <p style="margin: 2px 0; font-size: 9px;">
            واحد اصلی: {{ $exchangeRate->source_currency }}
        </p>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>ارز</th>
                <th>خرید</th>
                <th>فروش</th>
            </tr>
        </thead>
        <tbody>
            <tr class="currency-row">
                <td>افغانی</td>
                <td>{{ number_format($exchangeRate->afn_buy, 2) }}</td>
                <td>{{ number_format($exchangeRate->afn_sell, 2) }}</td>
            </tr>
            <tr class="currency-row">
                <td>تومان</td>
                <td>{{ number_format($exchangeRate->irr_buy, 2) }}</td>
                <td>{{ number_format($exchangeRate->irr_sell, 2) }}</td>
            </tr>
            <tr class="currency-row">
                <td>یورو</td>
                <td>{{ number_format($exchangeRate->eur_buy, 2) }}</td>
                <td>{{ number_format($exchangeRate->eur_sell, 2) }}</td>
            </tr>
            <tr class="currency-row">
                <td>کلدار</td>
                <td>{{ number_format($exchangeRate->pkr_buy, 2) }}</td>
                <td>{{ number_format($exchangeRate->pkr_sell, 2) }}</td>
            </tr>
            <tr class="currency-row">
                <td>لیره</td>
                <td>{{ number_format($exchangeRate->try_buy, 2) }}</td>
                <td>{{ number_format($exchangeRate->try_sell, 2) }}</td>
            </tr>
            <tr class="currency-row">
                <td>درهم</td>
                <td>{{ number_format($exchangeRate->aed_buy, 2) }}</td>
                <td>{{ number_format($exchangeRate->aed_sell, 2) }}</td>
            </tr>
            <tr class="currency-row">
                <td>یوان چین</td>
                <td>{{ number_format($exchangeRate->cny_buy, 2) }}</td>
                <td>{{ number_format($exchangeRate->cny_sell, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>چاپ شده در: {{ \Morilog\Jalali\Jalalian::now()->format('Y/m/d H:i') }}</p>
        <p>کاربر: {{ $exchangeRate->user->name ?? 'سیستم' }}</p>
    </div>
</body>
</html>