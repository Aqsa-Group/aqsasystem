{{-- resources/views/print/customer-profile-pdf.blade.php --}}
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>گزارش مشتریان</title>
    <style>
        body {
            font-family: 'vazir', sans-serif;
            margin: 20px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #184D6C;
            padding-bottom: 10px;
        }

        .header h1 {
            font-size: 24px;
            color: #184D6C;
            margin: 0;
        }

        .header .sub {
            font-size: 14px;
            color: #555;
            margin-top: 5px;
        }

        .filter-info {
            font-size: 12px;
            background: #f5f5f5;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            direction: rtl;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 12px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 6px 8px;
            text-align: center;
        }

        th {
            background-color: #1e3c5c;
            color: #fff;
            font-weight: bold;
        }

        .sub-header {
            background-color: #34495e;
            color: #fff;
        }

        .bg-white {
            background-color: #fff;
        }

        .bg-gray {
            background-color: #f9f9f9;
        }

        .text-red {
            color: #d9534f;
        }

        .text-green {
            color: #5cb85c;
        }

        .text-gray {
            color: #888;
        }

        .font-mono {
            font-family: 'Courier New', monospace;
        }

        .total-row {
            background-color: #e9f7ef;
            font-weight: bold;
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #184D6C;
            margin: 20px 0 10px 0;
            border-bottom: 1px solid #184D6C;
            padding-bottom: 5px;
        }

        .footer {
            text-align: center;
            font-size: 11px;
            color: #888;
            margin-top: 30px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }

        @page {
            margin: 20px;
        }
    </style>
</head>

<body>

    <div class="header">
        <h1>گزارش پروفایل مشتریان</h1>
        <div class="sub">تاریخ چاپ: {{ \Morilog\Jalali\Jalalian::now()->format('Y/m/d H:i') }}</div>
    </div>

    <div class="filter-info">
        <strong>فیلترهای اعمال‌شده:</strong><br>
        مشتری: {{ $filterInfo['customer'] }} | ارز: {{ $filterInfo['currency'] }} | نوع تراکنش: {{ $filterInfo['type']
        }}<br>
        از تاریخ: {{ $filterInfo['startDate'] }} | تا تاریخ: {{ $filterInfo['endDate'] }}
    </div>

    <!-- جدول موجودی مشتریان -->
    <div class="section-title">موجودی مشتریان</div>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>نام مشتری</th>
                @foreach($currencies as $code => $name)
                <th style="background-color:#34495e;">موجودی {{ $name }}</th>
                @endforeach
                <th style="background-color:#34495e;">موجودی کرایه</th>
                <th style="background-color:#34495e;">مجموع کل</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reports as $index => $report)
            @php $rowClass = $index % 2 == 0 ? 'bg-white' : 'bg-gray'; @endphp
            <tr class="{{ $rowClass }}">
                <td>{{ $index + 1 }}</td>
                <td>{{ $report['fullname'] }}</td>
                @foreach($currencies as $code => $name)
                @php
                $bal = $report['balance_' . strtolower($code)] ?? 0;
                $class = $bal < 0 ? 'text-red' : ($bal> 0 ? 'text-green' : 'text-gray');
                    @endphp
                    <td class="font-mono {{ $class }}">{{ number_format($bal, 2) }}</td>
                    @endforeach
                    <td class="font-mono">{{ number_format($report['rent_money'], 2) }}</td>
                    <td class="font-mono font-bold">{{ number_format($report['total_balance'], 2) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="{{ 2 + count($currencies) + 2 }}">داده‌ای یافت نشد</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- جدول تراکنش‌ها -->
    <div class="section-title">لیست تمام تراکنش‌ها (برداشت‌ها و پرداخت‌های بیرونی)</div>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>نام مشتری</th>
                <th>نوع ترانزکشن</th>
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
            $rowClass = $index % 2 == 0 ? 'bg-white' : 'bg-gray';
            $isOutside = $tx['transaction_type'] === 'outside';
            $typeClass = $isOutside ? 'text-green' : 'text-blue';
            $amountClass = $tx['amount'] < 0 ? 'text-red' : 'text-green' ; @endphp <tr class="{{ $rowClass }}">
                <td>{{ $index + 1 }}</td>
                <td>{{ $tx['customer_name'] }}</td>
                <td>
                    @if($tx['transaction_type'] === 'withdrawal')
                    برداشت
                    @elseif($tx['transaction_type'] === 'outside')
                    عواید بیرونی
                    @endif
                </td>
                <td><span style="font-weight:bold;color:{{ $isOutside ? '#2e7d32' : '#1565c0' }}">{{ $tx['type']
                        }}</span></td>
                <td class="font-mono {{ $amountClass }}">{{ number_format($tx['amount'], 2) }}</td>
                <td>{{ getPersianCurrencyName($tx['currency']) }}</td>
                <td>{{ $tx['date_fa'] }}</td>
                <td>{{ $tx['description'] }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">هیچ تراکنشی یافت نشد</td>
                </tr>
                @endforelse
        </tbody>
    </table>



</body>

</html>