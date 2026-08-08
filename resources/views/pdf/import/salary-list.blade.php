<!DOCTYPE html>
<html dir="rtl" lang="fa">
<head>
    <meta charset="UTF-8">
    <title>گزارش معاش</title>
    <style>
        body {
            font-family: 'vazir', 'DejaVu Sans', sans-serif;
            margin: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #4a90d9;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 24px;
            color: #2c3e50;
            margin: 0;
        }
        .header .sub {
            font-size: 14px;
            color: #7f8c8d;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }
        th {
            background: #4a90d9;
            color: #fff;
            padding: 10px 8px;
            border: 1px solid #3a7bc8;
            text-align: center;
        }
        td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: center;
        }
        tr:nth-child(even) {
            background: #f9f9f9;
        }
        .amount {
            font-weight: bold;
            color: #27ae60;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #95a5a6;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .total {
            margin-top: 20px;
            text-align: left;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>  پرداخت معاش</h1>
        <div class="sub">تاریخ چاپ: {{ \Morilog\Jalali\Jalalian::now()->format('Y/m/d') }}</div>
        @if(isset($staffName))
            <div class="sub">کارمند: {{ $staffName }}</div>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>نام کارمند</th>
                <th>مبلغ (افغانی)</th>
                <th>تاریخ</th>
                <th>توضیحات</th>
            </tr>
        </thead>
        <tbody>
            @forelse($salaries as $index => $salary)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $salary->staff->name ?? 'نامشخص' }}</td>
                    <td class="amount">{{ number_format($salary->amount) }}</td>
<td>{{ \Carbon\Carbon::parse($salary->date)->format('Y-m-d') }}</td>                    <td>{{ $salary->description ?? '—' }}</td>
                </tr>
            @empty
                <tr><td colspan="5">هیچ داده‌ای برای نمایش وجود ندارد.</td></tr>
            @endforelse
        </tbody>
    </table>


    <div class="footer">این گزارش به‌صورت خودکار توسط سیستم مدیریت معاش تهیه شده است.</div>
</body>
</html>