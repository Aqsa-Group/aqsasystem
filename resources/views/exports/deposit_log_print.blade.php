<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>چاپ رسید پرداخت - مجتمع تجارتی عادلیار</title>
    <style>
        html,
        body {
            height: 100%;
            background: #fff;
            color: #111;
            direction: rtl;
            font-family: 'Vazir', sans-serif;
            -webkit-print-color-adjust: exact;
            font-size: 13px;
            margin: 0;
            padding: 0;
        }

        .page {
            width: 100%;
            box-sizing: border-box;
            padding: 15px;
        }

        /* هدر مینیمال */
        .col-header {
            position: relative;
            height: 100px;
            margin-bottom: 15px;
        }

        .col-header .title {
            font-size: 22px;
            font-weight: bold;
            color: #111;
            text-align: center;
        }

        .col-header .subtitle {
            font-size: 16px;
            font-weight: bold;
            color: #222;
            text-align: center;
            margin-top: 2px;
        }

        .logo {
            width: 80px;
            height: 80px;
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .logo img {
            max-width: 100%;
            max-height: 100%;
            display: block;
        }

        /* جدول مشخصات */
        .form-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .form-table th,
        .form-table td {
            border: 1px solid #444;
            padding: 6px;
            text-align: center;
            font-weight: bold;
        }

        .form-table th {
            background: #095264;
            color: #ffffff;
        }


        .amount-rows {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .amount-rows th,
        .amount-rows td {
            border: 1px solid #444;
            padding: 8px;
            font-weight: bold;
            text-align: center;
        }

        /* ستون اول تیره و متن سفید */
        .amount-rows td:first-child {
            background: #095264;
            /* تیره مینیمال */
            color: #ffffff;
            /* متن سفید */
            font-weight: bold;
            text-align: right;
        }

        /* ستون دوم سفید و متن تیره */
        .amount-rows td:last-child {
            background: #fff;
            color: #111;
            text-align: center;
        }

        /* باکس مسئول برق و امضا مینیمال */
        .sign-block {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }

        .electrician-box,
        .stamp-box {
            border: 1px solid #444;
            border-radius: 6px;
            padding: 12px;
            flex: 1;
            text-align: center;
            background: #fff;
            color: #111;
        }

        .electrician-box .title {
            font-weight: bold;
            font-size: 15px;
            margin-bottom: 6px;
        }

        .electrician-box .phone {
            font-size: 20px;
            font-weight: bold;
        }

        .stamp-box {
            min-height: 120px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 16px;
        }

     
    </style>
</head>

<body>
    <div class="page">
        <!-- هدر -->
        <div class="col-header">
            <div class="header-text">
                <div class="title">مجتمع تجارتی عادلیار</div>
                <div class="subtitle">رسید پول {{$depositLog->expanses_type ?? '-' }}</div>
            </div>

        </div>

        <!-- جدول مشخصات -->
        <table class="form-table">
            <thead>
                <tr>
                    <th>مارکت</th>
                    <th>شماره دوکان</th>
                    <th>دوکاندار</th>
                    <th>نوع هزینه</th>
                    <th>بابت ماه</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $depositLog->market->name ?? '-' }}</td>
                    <td>{{ $depositLog->shop->number ?? $depositLog->booth->number ?? '-' }}</td>
                    <td>{{ $depositLog->shopkeeper->fullname ?? '-' }}</td>
                    <td>{{ $depositLog->expanses_type ?? '-' }}</td>
                    <td>
                        @php
                        $jalali = \Morilog\Jalali\Jalalian::fromDateTime($depositLog->deposit->paid_date);
                        $months = [
                        1 => 'حمل',2 => 'ثور',3 => 'جوزا',4 => 'سرطان',
                        5 => 'اسد',6 => 'سنبله',7 => 'میزان',8 => 'عقرب',
                        9 => 'قوس',10 => 'جدی',11 => 'دلو',12 => 'حوت',
                        ];
                        @endphp
                        {{ $months[$jalali->getMonth()] }} {{ $jalali->getYear() }}
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- جدول مقادیر -->
        <table class="amount-rows">
            <tbody>
                <tr>
                    <td>پرداخت قبلی</td>
                    <td>{{ number_format($depositLog->old_paid ?? 0) }} افغانی</td>
                </tr>
                <tr>
                    <td>پرداخت جدید</td>
                    <td>{{ number_format($depositLog->new_paid ?? 0) }} افغانی</td>
                </tr>
                <tr>
                    <td>الباقی</td>
                    <td>{{ number_format($depositLog->remaining ?? 0) }} افغانی</td>
                </tr>
            </tbody>
        </table>


    </div>
</body>

</html>