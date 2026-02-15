<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>پرنت پول برق - مجتمع تجارتی عادلیار</title>
    <link rel="preload" href="{{ asset('fonts/Vazir.ttf') }}" as="font" type="font/ttf" crossorigin>

    <style>
        html,
        body {
            height: auto;
            background: #fff;
            font-family: 'Vazir', Tahoma, sans-serif !important;
            color: #111;
            direction: rtl;
            -webkit-print-color-adjust: exact;
            margin: 0;
            padding: 0;
        }

        @font-face {
            font-family: 'Vazir';
            src: url('{{ asset("fonts/Vazir.ttf") }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        /* هر صفحه یک رسید A5 landscape */
        .page {
            width: 210mm;
            height: 148.5mm;
            box-sizing: border-box;
            margin: 0 auto;
            page-break-after: always;
            position: relative;
        }

        .page:last-child {
            page-break-after: avoid;
        }

        /* جدول دو ستونی (هر ستون یک کپی) */
        .two-col {
            width: 100%;
            border-collapse: separate;
            border-spacing: 10px;
            height: calc(148.5mm - 20px);
            box-sizing: border-box;
        }

        .two-col td {
            vertical-align: top;
            width: 50%;
            padding: 5px;
            box-sizing: border-box;
            border: 1px solid #777;
            background: #fff;
            height: 100%;
        }

        /* هدر هر ستون */
        .col-header {
            position: relative;
            height: 100px;
            margin-bottom: 6px;
        }

        .col-header .title {
            font-size: 20px;
            font-weight: bold;
            color: #7c3a00;
        }

        .logo {
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 90px;
            height: 90px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #ddd;
            background: #fafafa;
            border-radius: 4px;
        }

        .logo img {
            max-width: 100%;
            max-height: 100%;
            filter: sepia(1) saturate(5) hue-rotate(10deg) brightness(0.6) contrast(1.2);
        }

        .header-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
        }

        /* جدول اطلاعات بالا */
        .form-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2px;
        }

        .form-table td,
        .form-table th {
            border: 1px solid #777;
            font-size: 12px;
            padding: 2px 4px;
            text-align: start;
        }

        .form-table th {
            font-weight: 600;
            color: #111;
            font-size: 12px;

        }

        /* جدول مقادیر */
        .amount-rows {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
        }

        .amount-rows td {
            border: 1px solid #999;
            padding: 6px 8px;
            font-size: 10px;
        }


        .amount-rows td:first-child {
            font-weight: 400;
            font-size: 12px;
        }

        .amount-rows td:last-child {
            text-align: center;
        }

        /* بلوک مسؤول برق و امضاء */
        .electrician-box {
            border: 1px solid #444;
            padding: 5px;
            border-radius: 6px;
            margin-bottom: 15px;
            text-align: center;
            background: #fafafa;
        }

        .electrician-box .label {
            font-weight: bold;
            font-size: 15px;
            margin-bottom: 6px;
        }

        .electrician-box .phone {
            font-size: 26px;
            font-weight: 900;
            direction: ltr;
        }

        .signature-box {
            border: 1px solid #444;
            padding: 12px;
            border-radius: 6px;
            text-align: center;
            background: #fff;
            height: calc(100% - 90px);
            min-height: 210px;
            box-sizing: border-box;
        }

        .signature-box .label {
            font-size: 16px;
            font-weight: bold;
            margin-top: 0;
        }

        @media print {
            @page {
                size: 210mm 148.5mm;
                margin: 0;
            }

            html,
            body {
                width: 210mm;
                margin: 0;
                padding: 0;
                background: #fff;
            }

            .page {
                margin: 0;
                padding: 0;
            }
        }
    </style>
</head>

<body>
    @foreach($printsData as $data)
    @php
    $accounting = $data['accounting'];
    $rowNumber = $data['rowNumber'];
    @endphp
    <div class="page">
        <table class="two-col">
            <tr>
                <!-- ستون چپ (اصلی) -->
                <td>
                    <!-- هدر -->
                    <div class="col-header">
                        <div class="header-text">
                            <div class="title">مجتمع تجارتی عادلیار</div>
                            <div class="subtitle"
                                style="font-size: 20px; margin-top: 5px; font-weight:bolder; color: rgb(6, 28, 99); word-spacing: 1px">
                                قبض برق</div>
                        </div>
                        <div class="logo">
                            <img src="{{ asset('assets/logo.png') }}" alt="لوگو">
                        </div>
                    </div>

                    <!-- جدول مشخصات -->
                    <table class="form-table">
                        <thead>
                            <tr>
                                <th>مشتری</th>
                                <th>مارکت</th>
                                <th>{{ !empty($accounting->shop->number) ? 'شماره دوکان' : 'شماره غرفه' }}</th>
                                <th>شماره مسلسل</th>
                                <th>از تاریخ</th>
                                <th>تا تاریخ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $accounting->shopkeeper->fullname ?? $accounting->shopkeeper->name ?? '---' }}
                                </td>
                                <td>{{ $accounting->market->name ?? '---' }}</td>
                                <td>{{ $accounting->shop->number ?? $accounting->booth->number ?? '---' }}</td>
                                <td>{{ $rowNumber }}</td>
                                <td>{{ $accounting->paid_date ?
                                    \Morilog\Jalali\Jalalian::fromDateTime($accounting->paid_date)->format('Y/m/d') :
                                    '---' }}</td>
                                <td>{{ $accounting->expiration_date ?
                                    \Morilog\Jalali\Jalalian::fromDateTime($accounting->expiration_date)->format('Y/m/d')
                                    : '---' }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- بخش دو ستونی: مقادیر (راست) و مسؤول/امضاء (چپ) -->
                    <table style="width:100%; border-collapse:separate;">
                        <tr>
                            <td style="width:70%; vertical-align:top; border:none; padding:0;">
                                <table class="amount-rows">
                                    <tr>
                                        <td>درجه فعلی</td>
                                        <td>{{ $accounting->current_degree ?? '---' }}</td>
                                    </tr>
                                    <tr>
                                        <td>درجه قبلی</td>
                                        <td>{{ $accounting->past_degree ?? '---' }}</td>
                                    </tr>
                                    <tr>
                                        <td>مقدار مصرف</td>
                                        <td>{{ $data['consumption'] }} کیلووات</td>
                                    </tr>
                                    <tr>
                                        <td>قیمت فی کیلووات</td>
                                        <td>{{ number_format($accounting->degree_price ?? 0) }} افغانی</td>
                                    </tr>
                                    <tr>
                                        <td>مبلغ قابل تادیه</td>
                                        <td>{{ number_format($data['currentPrice']) }} افغانی</td>
                                    </tr>
                                    <tr>
                                        <td>از دوره‌های قبل</td>
                                        <td>{{ number_format($data['previousRemaining']) }} افغانی</td>
                                    </tr>
                                    <tr>
                                        <td>مبلغ پرداخت شده</td>
                                        <td>{{ number_format($data['currentPaid']) }} افغانی</td>
                                    </tr>
                                    <tr>
                                        <td>جمع کل</td>
                                        <td>{{ number_format($data['totalRemaining']) }} افغانی</td>
                                    </tr>
                                </table>
                            </td>
                            <td style="width:30%; vertical-align:top; border:none; padding-right:8px;">
                                <div class="electrician-box">
                                    <div class="label">مسؤول برق</div>
                                    <div class="phone">۰۷۹۹۵۵۳۳۳۳</div>
                                </div>
                                <div class="signature-box">
                                    <div class="label">مهر و امضاء</div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>

                <!-- ستون راست (کپی) -->
                <td>
                    <!-- هدر -->
                    <div class="col-header">
                        <div class="header-text" style="left:54%;">
                            <div class="title">مجتمع تجارتی عادلیار</div>
                            <div class="title" style="font-size: 20px; margin-top: 5px; font-weight:bolder; color: rgb(6, 28, 99); word-spacing: 1px">قبض برق</div>
                        </div>
                        <div class="logo">
                            <img src="{{ asset('assets/logo.png') }}" alt="لوگو">
                        </div>
                    </div>

                    <!-- جدول مشخصات (بدون تاریخ) -->
                    <table class="form-table">
                        <tbody>
                            <tr>
                                <th>مشتری</th>
                                <th>مارکت</th>
                                <th>{{ !empty($accounting->shop->number) ? 'شماره دوکان' : 'شماره غرفه' }}</th>
                                <th>شماره مسلسل</th>
                            </tr>
                            <tr>
                                <td>{{ $accounting->shopkeeper->fullname ?? $accounting->shopkeeper->name ?? '---' }}
                                </td>
                                <td>{{ $accounting->market->name ?? '---' }}</td>
                                <td>{{ $accounting->shop->number ?? $accounting->booth->number ?? '---' }}</td>
                                <td>{{ $rowNumber }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- جدول مقادیر -->
                    <table class="amount-rows">
                        <tr>
                            <td>درجه فعلی</td>
                            <td>{{ $accounting->current_degree ?? '---' }}</td>
                        </tr>
                        <tr>
                            <td>درجه قبلی</td>
                            <td>{{ $accounting->past_degree ?? '---' }}</td>
                        </tr>
                        <tr>
                            <td>مقدار مصرف</td>
                            <td>{{ $data['consumption'] }} کیلووات</td>
                        </tr>
                        <tr>
                            <td>قیمت فی کیلووات</td>
                            <td>{{ number_format($accounting->degree_price ?? 0) }} افغانی</td>
                        </tr>
                        <tr>
                            <td>مبلغ قابل تادیه</td>
                            <td>{{ number_format($data['currentPrice']) }} افغانی</td>
                        </tr>
                        <tr>
                            <td>از دوره‌های قبل</td>
                            <td>{{ number_format($data['previousRemaining']) }} افغانی</td>
                        </tr>
                        <tr>
                            <td>مبلغ پرداخت شده</td>
                            <td>{{ number_format($data['currentPaid']) }} افغانی</td>
                        </tr>
                        <tr>
                            <td>جمع کل</td>
                            <td>{{ number_format($data['totalRemaining']) }} افغانی</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
    @endforeach

    <script>
        window.onload = function() {
            setTimeout(() => {
                window.print();
                setTimeout(() => window.close(), 1000);
            }, 500);
        };
        window.afterprint = function() {
            setTimeout(() => window.close(), 1000);
        };
    </script>
</body>

</html>