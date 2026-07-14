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
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'vazir', sans-serif;
            font-size: 11px;
            line-height: 1.5;
            color: #000;
            padding: 15px;
        }

        .header {
            text-align: center;
            border-bottom: 1px solid #333;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }

        .header h1 {
            font-size: 18px;
            font-weight: bold;
            margin: 0;
        }

        .header .sub {
            font-size: 11px;
            color: #555;
            margin-top: 3px;
        }

        .filter-info {
            font-size: 10px;
            margin-bottom: 12px;
            padding: 6px 10px;
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 3px;
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .filter-info strong {
            font-weight: bold;
        }

        .filter-info span {
            font-weight: normal;
        }

        /* جلوگیری از شکستن صفحات در داخل جداول و بخش‌ها */
        .no-page-break {
            page-break-inside: avoid;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            font-size: 10px;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 4px 5px;
            text-align: center;
        }

        th {
            background-color: #eee;
            font-weight: bold;
        }

        .bg-gray {
            background-color: #f7f7f7;
        }

        .text-left {
            text-align: left;
            direction: ltr;
        }

        .text-red {
            color: #d32f2f;
        }

        .text-green {
            color: #2e7d32;
        }

        .summary-table {
            margin-top: 8px;
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }

        .summary-table th {
            background-color: #e0e0e0;
        }

        .summary-table td {
            border: 1px solid #333;
            padding: 4px 6px;
            text-align: center;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 8px;
            border-top: 1px solid #ccc;
            font-size: 9px;
            color: #666;
        }

        .badge {
            display: inline-block;
            padding: 1px 6px;
            border-radius: 2px;
            font-size: 9px;
            font-weight: bold;
            color: #000;
        }

        .badge-withdraw {
            background-color: #e3f2fd;
        }

        .badge-salary {
            background-color: #e8f5e9;
        }

        .amount-positive {
            font-weight: bold;
        }

        .amount-negative {
            font-weight: bold;
            color: #d32f2f;
        }

        /* تنظیمات شکستن صفحات */
        .page-break {
            page-break-before: auto;
        }

        tr {
            page-break-inside: avoid;
        }

        thead {
            page-break-after: avoid;
        }

        /* اگر جدول بزرگ باشد، کل جدول می‌تواند به صفحه بعد برود ولی ردیف‌ها نشکنند */
        table {
            page-break-inside: auto;
        }
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
        از برداشت: <span>{{ $filterInfo['startDate'] }}</span> |
        تا تاریخ: <span>{{ $filterInfo['endDate'] }}</span>
    </div>

    <!-- ===== جدول خلاصه ===== -->
    <div class="no-page-break">
        <h3 style="font-size:12px; margin-bottom:4px;">خلاصه برداشت‌ها و معاشات به تفکیک کارمند</h3>
        <table>
            <thead>
                <tr>
                    <th rowspan="2">#</th>
                    <th rowspan="2">نام کارمند</th>
                    @foreach($currencies as $code => $name)
                    <th colspan="2">{{ $name }}</th>
                    @endforeach
                </tr>
                <tr>
                    @foreach($currencies as $code => $name)
                    <th>برداشت</th>
                    <th>کل معاشات گرفته شده </th>
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
                    $withClass = $with < 0 ? 'text-red' : ($with> 0 ? 'text-green' : '');
                        $salClass = $sal < 0 ? 'text-red' : ($sal> 0 ? 'text-green' : '');
                            @endphp
                            <td class="{{ $withClass }} text-left">{{ number_format($with, 2) }}</td>
                            <td class="{{ $salClass }} text-left">{{ number_format($sal, 2) }}</td>
                            @endforeach
                </tr>
                @empty
                <tr>
                    <td colspan="{{ 2 + (count($currencies) * 2) }}" style="text-align:center; padding:10px;">داده‌ای
                        یافت نشد</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- جمع کل برداشت‌ها -->
    <div class="no-page-break">
        <table class="summary-table">
            <thead>
                <tr>
                    <th colspan="2">جمع کل برداشت‌ها</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="width:20%; font-weight:bold;">کل برداشت‌ها</td>
                    @foreach($currencies as $code => $name)
                    @php $amt = $totalWithdrawals[$code] ?? 0; $class = $amt < 0 ? 'text-red' : ($amt> 0 ? 'text-green'
                        : ''); @endphp
                        <td class="{{ $class }} text-left">{{ number_format($amt, 2) }}</td>
                        @endforeach
                </tr>
            </tbody>
        </table>
    </div>

    <!-- جمع کل حقوق‌ها -->
    <div class="no-page-break">
        <table class="summary-table">
            <thead>
                <tr>
                    <th colspan="2">جمع کل معاشات گرفته شده </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="width:20%; font-weight:bold;"> کل معاشات گرفته شده </td>
                    @foreach($currencies as $code => $name)
                    @php $amt = $totalSalaries[$code] ?? 0; $class = $amt < 0 ? 'text-red' : ($amt> 0 ? 'text-green' :
                        ''); @endphp
                        <td class="{{ $class }} text-left">{{ number_format($amt, 2) }}</td>
                        @endforeach
                </tr>
            </tbody>
        </table>
    </div>

    <!-- ===== جدول جزئیات تراکنش‌ها ===== -->
    <!-- اگر تراکنش وجود دارد، صفحه جدید شروع می‌شود فقط در صورت نیاز (با page-break-before: auto) -->
    <div class="page-break">
        <h3 style="font-size:12px; margin-bottom:4px; margin-top:15px;">لیست تمام تراکنش‌ها</h3>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>نام کارمند</th>
                    <th>نوع تراکنش</th>
                    <th>نوع برداشت</th>
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
                $amountClass = $tx['amount'] < 0 ? 'amount-negative' : 'amount-positive' ; @endphp <tr
                    class="{{ $index % 2 == 0 ? 'bg-gray' : '' }}">
                    <td>{{ $index + 1 }}</td>
                    <td style="text-align:right;">{{ $tx['staff_name'] }}</td>
                    <td>
                        {{ $tx['transaction_type'] === 'withdrawal' ? 'برداشت' : 'معاش' }}
                    </td>
                    <td><span class="badge {{ $badgeClass }}">{{ $tx['type'] }}</span></td>
                    <td class="text-left {{ $amountClass }}">{{ number_format($tx['amount'], 2) }}</td>
                    <td>{{ getPersianCurrencyName($tx['currency']) }}</td>
                    <td>{{ $tx['date_fa'] }}</td>
                    <td style="text-align:right; max-width:150px; word-wrap:break-word;">{{ $tx['description'] }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align:center; padding:10px;">هیچ تراکنشی یافت نشد</td>
                    </tr>
                    @endforelse
            </tbody>
        </table>
    </div>

    <div class="footer">
        این گزارش به‌صورت خودکار تولید شده است.
    </div>

</body>

</html>