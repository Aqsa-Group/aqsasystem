<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>چاپ رسید حسابداری</title>
    <style>
        body {
            font-family: 'amiri', serif;
            font-size: 12px;
            direction: rtl;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
            padding-bottom: 5px;
            color: #1e3a8a;
        }

        .main {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }

        th {
            background: #d6d8db;
            font-weight: bold;
            border: 1px solid #000;
            padding: 4px;
            text-align: center;
        }

        td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
        }

        .sign {
            text-align: center;
            margin-top: 50px;
        }

        .sign th {
            border: none;
            padding: 25px;
            background-color: white;
        }
    </style>
</head>

<body>

    <div style="width: 100%; direction: rtl; font-family: 'DejaVu Sans'; font-size: 12pt;">
        <table style="width: 100%; border-collapse: collapse; border: none;">
            <tr>
                <!-- Right: Persian Title -->
                <td style="width: 40%; text-align: right; vertical-align: middle; color: #1e3a8a; border: none;">
                    <strong style="font-size: 20pt;">مجتمع تجارتی عادلیار</strong>
                </td>

                <!-- Center: Logo -->
                <td style="width: 33.33%; text-align: center; border: none;">
                    <img src="{{ public_path('assets/logo.png') }}" alt="لوگو" style="height: 80px; width: 90px;" />
                </td>

                <!-- Left: English Title -->
                <td style="width: 33.33%; text-align: left; vertical-align: middle; color: #1e3a8a; border: none;">
                    <div style="line-height: 1.2;">
                        <div style="font-weight: bold; font-size: 18pt;">ADELYAR</div>
                        <div style="font-weight: bold; font-size: 10pt;">COMMERCIAL COMPLEX</div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <table class="main">
        <tr>
            <th>مارکت</th>
            <th>نوع ملک</th>

            @if ($accounting->type === 'دوکان')
                <th>شماره دوکان</th>
            @endif

            @if ($accounting->type === 'غرفه')
                <th>شماره غرفه</th>
            @endif

            <th>نام دوکاندار</th>
            <th>نوع مصرف</th>

            {{-- ستون های اضافی فقط وقتی پول برق است --}}
            @if ($accounting->expanses_type === 'پول برق')
                <th>شماره متر</th>
                <th>درجه قبلی</th>
                <th>درجه فعلی</th>
                <th>قیمت فی کیلوات</th>
            @endif

            <th>مبلغ</th>
            @if (!empty($accounting->paid) && $accounting->paid > 0)
                <th>پرداخت شده</th>
                <th>باقی‌مانده</th>
            @endif
            <th>تاریخ ثبت</th>
        </tr>

        <tr>
            <td>{{ $accounting->market->name ?? '-' }}</td>
            <td>{{ $accounting->type ?? '-' }}</td>

            @if ($accounting->type === 'دوکان')
                <td>{{ $accounting->shop->number ?? '-' }}</td>
            @endif

            @if ($accounting->type === 'غرفه')
                <td>{{ $accounting->booth->number ?? '-' }}</td>
            @endif

            <td>{{ $accounting->shopkeeper->fullname ?? '-' }}</td>
            <td>{{ $accounting->expanses_type ?? '-' }}</td>

            {{-- مقداردهی ستون های اضافی پول برق --}}
            @if ($accounting->expanses_type === 'پول برق')
                <td>{{ $accounting->meter_serial ?? '-' }}</td>
                <td>{{ $accounting->past_degree ?? '-' }}</td>
                <td>{{ $accounting->current_degree ?? '-' }}</td>
                <td>{{ number_format($accounting->degree_price ?? 0) }}</td>
            @endif

            <td>{{ number_format($accounting->price) }}</td>

            @if (!empty($accounting->paid) && $accounting->paid > 0)
                <td>{{ number_format($accounting->paid) }}</td>
                <td>{{ number_format($accounting->remained ?? 0) }}</td>
            @endif

            <td>{{ \Morilog\Jalali\Jalalian::fromDateTime($accounting->paid_date)->format('Y/m/d') }}</td>
        </tr>
    </table>

</body>

</html>
