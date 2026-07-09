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
<html dir="rtl" lang="fa">
<head>
    <meta charset="UTF-8">
    <title>گزارش دوکانداران</title>
    <style>
        body { font-family: 'vazir', sans-serif; margin: 20px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #184D6C; padding-bottom: 10px; }
        .header h1 { font-size: 24px; color: #184D6C; margin: 0; }
        .header .sub { font-size: 14px; color: #555; margin-top: 5px; }
        .filter-info { font-size: 12px; background: #f5f5f5; padding: 10px; border-radius: 5px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 12px; }
        th, td { border: 1px solid #ccc; padding: 6px 8px; text-align: center; }
        th { background-color: #1e3c5c; color: #fff; font-weight: bold; }
        .sub-header { background-color: #34495e; color: #fff; }
        .bg-white { background-color: #fff; }
        .bg-gray { background-color: #f9f9f9; }
        .text-red { color: #d9534f; }
        .text-green { color: #5cb85c; }
        .text-gray { color: #888; }
        .font-mono { font-family: 'Courier New', monospace; }
        .font-bold { font-weight: bold; }
        .section-title { font-size: 16px; font-weight: bold; color: #184D6C; margin: 20px 0 10px 0; border-bottom: 1px solid #184D6C; padding-bottom: 5px; }
        .footer { text-align: center; font-size: 11px; color: #888; margin-top: 30px; border-top: 1px solid #ddd; padding-top: 10px; }
        @page { margin: 20px; }
    </style>
</head>
<body>

<div class="header">
    <h1>گزارش پروفایل دوکانداران</h1>
    <div class="sub">تاریخ چاپ: {{ \Morilog\Jalali\Jalalian::now()->format('Y/m/d H:i') }}</div>
</div>

<div class="filter-info">
    <strong>فیلترهای اعمال‌شده:</strong><br>
    دوکاندار: {{ $filterInfo['shopkeeper'] }} | مارکت: {{ $filterInfo['market'] }} | نوع مصرف: {{ $filterInfo['expanses_type'] }}<br>
    ارز: {{ $filterInfo['currency'] }} | نوع تراکنش: {{ $filterInfo['type'] }}<br>
    از تاریخ: {{ $filterInfo['startDate'] }} | تا تاریخ: {{ $filterInfo['endDate'] }}
</div>

<!-- جدول خلاصه -->
<div class="section-title">خلاصه آخرین باقیمانده هر نوع مصرف</div>
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>دوکاندار</th>
            <th>شماره دوکان</th>
            <th>مارکت</th>
            @foreach($expansesTypes as $type)
                <th style="background-color:#34495e;">{{ $type }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @forelse($summary as $index => $row)
            @php $rowClass = $index % 2 == 0 ? 'bg-white' : 'bg-gray'; @endphp
            <tr class="{{ $rowClass }}">
                <td>{{ $index + 1 }}</td>
                <td>{{ $row['shopkeeper_name'] }}</td>
                <td>{{ $row['shop_number'] }}</td>
                <td>{{ $row['market_name'] }}</td>
              @foreach($expansesTypes as $type)
    @php
        $originalBalance = $row['balances'][$type] ?? 0;
        // معکوس کردن علامت برای نمایش بدهی به‌صورت منفی
        $balance = -$originalBalance;
        $class = $balance < 0 ? 'text-red-600 font-bold' : ($balance > 0 ? 'text-green-600' : 'text-gray-500');
    @endphp
    <td class="px-2 py-3 text-left font-mono {{ $class }} border-l border-gray-200" dir="ltr">{{ number_format($balance, 2) }}</td>
@endforeach
            </tr>
        @empty
            <tr><td colspan="{{ 4 + count($expansesTypes) }}">داده‌ای یافت نشد</td></tr>
        @endforelse
    </tbody>
</table>

<!-- جدول تراکنش‌ها -->
<div class="section-title">لیست تمام تراکنش‌ها</div>
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>دوکاندار</th>
            <th>شماره دوکان</th>
            <th>مارکت</th>
            <th>نوع مصرف</th>
            <th>مبلغ پرداختی</th>
            <th>ارز</th>
            <th>باقیمانده</th>
            <th>تاریخ</th>
        </tr>
    </thead>
    <tbody>
        @forelse($transactions as $index => $tx)
         @php
    $rowClass = $index % 2 == 0 ? 'bg-white' : 'bg-gray-50';
    $paidClass = $tx['paid'] < 0 ? 'text-red-600' : ($tx['paid'] > 0 ? 'text-green-600' : 'text-gray-500');
    // معکوس کردن علامت remained
    $remainedValue = -$tx['remained'];
    $remainedClass = $remainedValue < 0 ? 'text-red-600 font-bold' : ($remainedValue > 0 ? 'text-green-600' : 'text-gray-500');
@endphp
            <tr class="{{ $rowClass }}">
                <td>{{ $index + 1 }}</td>
                <td>{{ $tx['shopkeeper_name'] }}</td>
                <td>{{ $tx['shop_number'] }}</td>
                <td>{{ $tx['market_name'] }}</td>
                <td>{{ $tx['expanses_type'] }}</td>
                <td class="font-mono {{ $paidClass }}">{{ number_format($tx['paid'], 2) }}</td>
                <td>{{ getPersianCurrencyName($tx['currency']) }}</td>
                <td class="font-mono {{ $remainedClass }}">{{ number_format($tx['remained'], 2) }}</td>
                <td>{{ $tx['date_fa'] }}</td>
            </tr>
        @empty
            <tr><td colspan="9">هیچ تراکنشی یافت نشد</td></tr>
        @endforelse
    </tbody>
</table>

<div class="footer">این گزارش به صورت خودکار تولید شده است.</div>
</body>
</html>