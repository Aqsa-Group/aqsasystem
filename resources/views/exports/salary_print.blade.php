<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>چاپ رسید پرداخت معاش</title>
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
            vertical-align: middle;
            background-color: white;
            margin: 40px auto 0 auto;
            /* فاصله از بالا */
            border-collapse: separate;
            border-spacing: 80px 0;
            /* فاصله بین ستون‌ها */
        }

        .sign th {
            border: none;
            text-align: center;
            vertical-align: middle;
            background-color: white;
            padding: 25px;
        }
    </style>
</head>

<body>

    <div style="width: 100%; direction: rtl; font-family: 'DejaVu Sans'; font-size: 12pt;">
        <table style="width: 100%; border-collapse: collapse; border: none;">
            <tr>
                <td style="width: 40%; text-align: right; vertical-align: middle; color: #1e3a8a; border: none;">
                    <strong style="font-size: 20pt;">مجتمع تجارتی عادلیار</strong>
                </td>
                <td style="width: 33.33%; text-align: center; border: none;">
                    <img src="{{ public_path('assets/logo.png') }}" alt="لوگو" style="height: 80px; width: 90px;" />
                </td>
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
            <th>نام کارمند</th>
            <th>معاش</th>
            @if (!empty($salary->reduce_loan) && $salary->reduce_loan > 0)
                <th>رسید قرض</th>
            @endif
            <th>رسید معاش</th>
            <th>باقی‌مانده معاش</th>
            <th>تاریخ پرداخت</th>


        </tr>
        <tr>
            <td>{{ $salary->market->name ?? '-' }}</td>
            <td>{{ $salary->staff->fullname ?? '-' }}</td>
            <td>{{ number_format($salary->salary) }}</td>
            @if (!empty($salary->reduce_loan) && $salary->reduce_loan > 0)
                <td>{{ number_format($salary->reduce_loan) }}</td>
            @endif
            <td>{{ number_format($salary->paid ?? 0) }}</td>
            <td>{{ number_format($salary->remained ?? 0) }}</td>
            <td>{{ \Morilog\Jalali\Jalalian::fromDateTime($salary->paid_date)->format('Y/m/d') }}</td>


        </tr>
    </table>

    <table class="sign">
        <tr>
            <th>امضا مدیر مالی</th>
            <th>امضا کارمند</th>
        </tr>
    </table>

    <div style="height:300px"></div>


    <div style="width: 100%; direction: rtl; font-family: 'DejaVu Sans'; font-size: 12pt;">
        <table style="width: 100%; border-collapse: collapse; border: none;">
            <tr>
                <td style="width: 40%; text-align: right; vertical-align: middle; color: #1e3a8a; border: none;">
                    <strong style="font-size: 20pt;">مجتمع تجارتی عادلیار</strong>
                </td>
                <td style="width: 33.33%; text-align: center; border: none;">
                    <img src="{{ public_path('assets/logo.png') }}" alt="لوگو" style="height: 80px; width: 90px;" />
                </td>
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
            <th>نام کارمند</th>
            <th>معاش</th>
            @if (!empty($salary->reduce_loan) && $salary->reduce_loan > 0)
                <th>رسید قرض</th>
            @endif
            <th>رسید معاش</th>
            <th>باقی‌مانده معاش</th>
            <th>تاریخ پرداخت</th>


        </tr>
        <tr>
            <td>{{ $salary->market->name ?? '-' }}</td>
            <td>{{ $salary->staff->fullname ?? '-' }}</td>
            <td>{{ number_format($salary->salary) }}</td>
            @if (!empty($salary->reduce_loan) && $salary->reduce_loan > 0)
                <td>{{ number_format($salary->reduce_loan) }}</td>
            @endif
            <td>{{ number_format($salary->paid ?? 0) }}</td>
            <td>{{ number_format($salary->remained ?? 0) }}</td>
            <td>{{ \Morilog\Jalali\Jalalian::fromDateTime($salary->paid_date)->format('Y/m/d') }}</td>


        </tr>
    </table>

    <table class="sign">
        <tr>
            <th>امضا مدیر مالی</th>
            <th>امضا کارمند</th>
        </tr>
    </table>


</body>

</html>
