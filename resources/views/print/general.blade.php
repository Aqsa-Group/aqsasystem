@php
if (!function_exists('getPersianCurrencyName')) {
    function getPersianCurrencyName($currencyCode) {
        $map = [
            'AFN' => 'افغانی',
            'USD' => 'دالر',
            'EUR' => 'یورو',
            'IRR' => 'تومان',
            'PKR' => 'کلدار',
            'AED' => 'درهم',
            'TRY' => 'لیره',
            'CNY' => 'یوان',
            'GBP' => 'پوند',
            'JPY' => 'ین',
            'SAR' => 'ریال سعودی',
            'INR' => 'روپیه',
        ];
        return $map[$currencyCode] ?? $currencyCode;
    }
}
@endphp

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>گزارش کارمندان</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'vazir', sans-serif;
            font-size: 11px;
            line-height: 1.5;
            color: #000;
            padding: 15px;
        }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 15px; }
        .header h1 { font-size: 20px; font-weight: bold; }
        .header .sub { font-size: 12px; color: #555; margin-top: 5px; }
        .filter-info {
            font-size: 11px;
            margin-bottom: 15px;
            padding: 8px;
            background: #f5f5f5;
            border-radius: 4px;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .filter-info span { font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; font-size: 10px; }
        th, td { border: 1px solid #333; padding: 4px 6px; text-align: center; }
        th { background-color: #1e3c5c; color: #fff; font-weight: bold; }
        .bg-gray { background-color: #f9f9f9; }
        .text-left { text-align: left; direction: ltr; }
        .text-red { color: #d32f2f; }
        .text-green { color: #2e7d32; }
        .text-blue { color: #1565c0; }
        .summary-table { margin-top: 10px; width: 100%; border-collapse: collapse; font-size: 10px; }
        .summary-table th { background-color: #2b4f72; }
        .summary-table td { border: 1px solid #333; padding: 4px 6px; text-align: center; }
        .footer { text-align: center; margin-top: 20px; padding-top: 10px; border-top: 1px solid #ccc; font-size: 10px; color: #666; }
        .page-break { page-break-before: always; }
        .badge-withdraw { background-color: #1565c0; color: #fff; padding: 1px 8px; border-radius: 4px; font-size: 9px; }
        .badge-salary { background-color: #2e7d32; color: #fff; padding: 1px 8px; border-radius: 4px; font-size: 9px; }
        .amount-positive { color: #2e7d32; font-weight: bold; }
        .amount-negative { color: #d32f2f; font-weight: bold; }
    </style>
</head>
<body>

<div class="header">
    <h1>گزارش جامع کارمندان</h1>
    <div class="sub">تاریخ چاپ: {{ \Morilog\Jalali\Jalalian::now()->format('Y/m/d H:i') }}</div>
</div>

<div class="filter-info">
    <strong>فیلترها:</strong>
    کارمند: <span>{{ $filterInfo['staff'] }}</span> |
    ارز: <span>{{ $filterInfo['currency'] }}</span> |
    نوع تراکنش: <span>{{ $filterInfo['type'] }}</span> |
    از تاریخ: <span>{{ $filterInfo['startDate'] }}</span> |
    تا تاریخ: <span>{{ $filterInfo['endDate'] }}</span>
</div>

<!-- ===== جدول خلاصه ===== -->
<h3 style="font-size:13px; margin-bottom:5px;">خلاصه برداشت‌ها و حقوق‌ها به تفکیک کارمند</h3>
<table>
    <thead>
        <tr>
            <th rowspan="2">#</th>
            <th rowspan="2">نام کارمند</th>
            @foreach($currencies as $code => $name)
                <th colspan="2" style="background-color:#34495e;">{{ $name }}</th>
            @endforeach
        </tr>
        <tr>
            @foreach($currencies as $code => $name)
                <th style="background-color:#4a6a8a;">برداشت</th>
                <th style="background-color:#4a6a8a;">حقوق</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @forelse($reports as $index => $report)
        <tr class="{{ $index % 2 == 0 ? 'bg-gray' : '' }}">
            <td>{{ $index + 1 }}</td>
            <td style="text-align:right;">{{ $report['fullname'] }}</td>
            @foreach($currencies as $code => $name)
                @php
                $with = $report['withdrawals'][$code] ?? 0;
                $sal = $report['salaries'][$code] ?? 0;
                $withClass = $with < 0 ? 'text-red' : ($with > 0 ? 'text-green' : '');
                $salClass = $sal < 0 ? 'text-red' : ($sal > 0 ? 'text-green' : '');
                @endphp
                <td class="{{ $withClass }} text-left">{{ number_format($with, 2) }}</td>
                <td class="{{ $salClass }} text-left">{{ number_format($sal, 2) }}</td>
            @endforeach
        </tr>
        @empty
        <tr><td colspan="{{ 2 + (count($currencies) * 2) }}" style="text-align:center; padding:15px;">داده‌ای یافت نشد</td></tr>
        @endforelse
    </tbody>
</table>

<!-- جمع کل برداشت‌ها -->
<table class="summary-table">
    <thead><tr><th colspan="2" style="background-color:#1a3a5a;">جمع کل برداشت‌ها</th></tr></thead>
    <tbody>
        <tr>
            <td style="width:20%; background:#e3f2fd; font-weight:bold;">کل برداشت‌ها</td>
            @foreach($currencies as $code => $name)
                @php $amt = $totalWithdrawals[$code] ?? 0; $class = $amt < 0 ? 'text-red' : ($amt > 0 ? 'text-green' : ''); @endphp
                <td class="{{ $class }} text-left">{{ number_format($amt, 2) }}</td>
            @endforeach
        </tr>
    </tbody>
</table>

<!-- جمع کل حقوق‌ها -->
<table class="summary-table">
    <thead><tr><th colspan="2" style="background-color:#1a3a5a;">جمع کل حقوق‌ها</th></tr></thead>
    <tbody>
        <tr>
            <td style="width:20%; background:#e8f5e9; font-weight:bold;">کل حقوق‌ها</td>
            @foreach($currencies as $code => $name)
                @php $amt = $totalSalaries[$code] ?? 0; $class = $amt < 0 ? 'text-red' : ($amt > 0 ? 'text-green' : ''); @endphp
                <td class="{{ $class }} text-left">{{ number_format($amt, 2) }}</td>
            @endforeach
        </tr>
    </tbody>
</table>

<!-- ===== جدول جزئیات تراکنش‌ها ===== -->
<div class="page-break"></div>
<h3 style="font-size:13px; margin-bottom:5px; margin-top:15px;">لیست تمام تراکنش‌ها</h3>
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>نام کارمند</th>
            <th>نوع تراکنش</th>
            <th>مبلغ</th>
            <th>ارز</th>
            <th>تاریخ</th>
            <th>توضیحات</th>
        </tr>
    </thead>
    <tbody>
        @forelse($transactions as $index => $tx)
        @php
        $isWithdraw = $tx['transaction_type'] !== 'salary';
        $badgeClass = $isWithdraw ? 'badge-withdraw' : 'badge-salary';
        $amountClass = $tx['amount'] < 0 ? 'amount-negative' : 'amount-positive';
        @endphp
        <tr class="{{ $index % 2 == 0 ? 'bg-gray' : '' }}">
            <td>{{ $index + 1 }}</td>
            <td style="text-align:right;">{{ $tx['staff_name'] }}</td>
            <td><span class="{{ $badgeClass }}">{{ $tx['type'] }}</span></td>
            <td class="text-left {{ $amountClass }}">{{ number_format($tx['amount'], 2) }}</td>
            <td>{{ getPersianCurrencyName($tx['currency']) }}</td>
            <td>{{ $tx['date_fa'] }}</td>
            <td style="text-align:right; max-width:150px; word-wrap:break-word;">{{ $tx['description'] }}</td>
        </tr>
        @empty
        <tr><td colspan="7" style="text-align:center; padding:15px;">هیچ تراکنشی یافت نشد</td></tr>
        @endforelse
    </tbody>
</table>

<div class="footer">
    این گزارش به‌صورت خودکار تولید شده است.
</div>

</body>
</html>