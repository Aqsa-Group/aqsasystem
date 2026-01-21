<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: "Tahoma", "Segoe UI", sans-serif;
            font-size: 12px;
            line-height: 1.6;
            margin: 0;
            padding: 15px;
            color: #2c3e50;
            background: #ffffff;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #2980b9;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
            font-weight: 700;
            color: #2980b9;
        }

        .customer-info {
            text-align: center;
            margin-bottom: 20px;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 5px;
        }
        .customer-name {
            font-weight: 700;
            color: #2980b9;
        }

        .simple-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .simple-table th {
            background-color: #2980b9;
            color: white;
            font-weight: 700;
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }
        .simple-table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }
        .simple-table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .amount-cell {
            font-family: 'Courier New', monospace;
            font-weight: 600;
            text-align: left;
            direction: ltr;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 11px;
            color: #7f8c8d;
            border-top: 1px solid #dee2e6;
            padding-top: 10px;
        }
    </style>
</head>
<body dir="rtl">

    <!-- هدر -->
    <div class="header">
        <h1>خلاصه موجودی‌های {{ $customer_name ?? '---' }}</h1>
        <p>صرافی {{ Auth::guard('sarafi')->user()->sarafi_name ?? '---' }}</p>
    </div>

    <!-- تاریخ گزارش -->
    <div class="customer-info">
        <p>تاریخ گزارش: {{ $generated_at ?? Jalalian::now()->format('Y/m/d H:i:s') }}</p>
    </div>

    <!-- جدول ساده -->
    @if(isset($balances) && count($balances) > 0)
    <table class="simple-table">
        <thead>
            <tr>
                <th style="width: 60%;">واحد پول</th>
                <th style="width: 40%;">موجودی فعلی</th>
            </tr>
        </thead>
        <tbody>
            @foreach($balances as $balance)
            <tr>
                <td><strong>{{ $balance['name_fa'] ?? '---' }}</strong></td>
                <td class="amount-cell">{{ number_format($balance['current_balance'] ?? 0) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div style="text-align: center; padding: 40px; color: #7f8c8d; font-style: italic;">
        هیچ موجودی فعالی وجود ندارد
    </div>
    @endif

    <!-- فوتر -->
    <div class="footer">
        چاپ شده در {{ $generated_at ?? Jalalian::now()->format('Y/m/d H:i:s') }}
    </div>

</body>
</html>