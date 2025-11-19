<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>پرنت پول برق - مجتمع تجارتی عادلیار</title>

    <style>
        html,
        body {
            height: 100%;
            background: #fff;
            font-family: "Tahoma", "Vazir", "Amiri", sans-serif;
            color: #111;
            direction: rtl;
        }

        /* کلی ظرف صفحه */
        .page {

            width: 100%;
            height: 100%;

        }

        /* قاب کلی دو بخش */
        .card {
            width: 100%;


        }

        /* جدول اصلی برای دو ستون */
        .two-col-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 8px;
            /* فاصله بین ستون‌ها */
        }

        .two-col-table td {
            vertical-align: top;
            width: 60%;
            padding: 14px;
            border: 1px solid #666;
        }


        /* سربرگ هر ستون */
        .header {
            margin-bottom: 8px;
        }

        .logo {
            width: 86px;
            height: 86px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 20px;
        }

        .title {
            font-size: 20px;
            font-weight: 700;
        }

        /* جدول های فرم */
        .form-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }

        .form-table td,
        .form-table th {
            border: 1px solid #777;
            padding: 6px 8px;
            font-size: 13px;
            vertical-align: middle;
        }

        .small-row td {
            padding: 6px;
        }

        /* قسمت وسط بزرگ (کادر مسئول برق و شماره) */
        .center-box {
            border: 1px solid #666;
            height: 200px;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 6px;
            background: #fafafa;
        }

        .center-box .role {
            font-size: 15px;
            margin-bottom: 6px;
        }

        .center-box .phone {
            font-size: 20px;
            font-weight: 700;
            letter-spacing: 2px;
        }

        /* لیست ردیف های مبلغ */
        .amount-rows {
            width: 100%;
            border-collapse: collapse;
        }

        .amount-rows td {
            border: 1px solid #999;
            padding: 6px 8px;
            height: 28px;
            font-size: 13px;
        }

        /* پایین ستون note */
        .note {
            margin-top: 8px;
            font-size: 12px;
        }

        /* چاپ: حذف سایه و رنگ‌های ناخواسته */
        @media print {
            .page {
                padding: 0;
            }

            .card {
                border-color: #333;
            }
        }
    </style>
</head>

<body>
    <div class="page">
        <div class="card" role="main" aria-label="فرم پرداخت برق">
            <table class="two-col-table">
                <tr>
                    <!-- ستون راست -->
                    <td>
                        <div class="column column-right">
                            <div class="header">
                                <div style="width: 100%; direction: rtl; font-family: 'DejaVu Sans'; font-size: 12pt;">
                                    <table style="width: 100%; border-collapse: collapse; border: none;">
                                        <tr>

                                            <td
                                                style="width: 90%; text-align: right; vertical-align: middle; color: #1e3a8a; border: none;">
                                                <strong style="font-size: 10pt;">مجتمع تجارتی عادلیار</strong>
                                            </td>

                                            <!-- سمت چپ: لوگو -->
                                            <td style="width: 40%; text-align: left; border: none;">
                                                <img src="{{ public_path('assets/logo.png') }}" alt="لوگو"
                                                    style="height: 80px; width:90px;" />
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div>
                                    <div style="font-size:12px; margin-top:4px;">
                                        مسلسل:
                                        <span style="border-bottom:1px solid #000; width:150px; margin-top:2px;">{{
                                            $accounting->id }}</span>
                                    </div>
                                </div>
                            </div>

                            <table class="form-table small-row" role="table">
                                <thead>
                                    <tr>
                                        <th style="width:22%;">دوکاندار</th>
                                        <th style="width:26%;">مارکت</th>
                                        <th style="width:18%;">نمبر دوکان</th>
                                        <th style="width:18%;">از تاریخ</th>
                                        <th style="width:16%;">تا تاریخ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $accounting->shopkeeper->fullname }}</td>
                                        <td>{{ $accounting->market->name }}</td>
                                        <td>{{ $accounting->shop->number ?? '-' }}</td>
                                        <td>{{
                                            \Morilog\Jalali\Jalalian::fromDateTime($accounting->paid_date)->format('Y/m/d')
                                            }}</td>
                                        <td>{{
                                            \Morilog\Jalali\Jalalian::fromDateTime($accounting->expiration_date)->format('Y/m/d')
                                            }}</td>
                                    </tr>
                                </tbody>
                            </table>

                            <div style="display: table; width: 100%;">
                                <div style="display: table-cell; width: 100%;">
                                    <table class="amount-rows">
                                        <tbody>
                                            <tr>
                                                <td>درجه فعلی</td>
                                                <td>{{ $accounting->current_degree ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td>درجه قبلی</td>
                                                <td>{{ $accounting->past_degree ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                @php
                                                $current = $accounting->current_degree ?? null;
                                                $past = $accounting->past_degree ?? null;

                                                $usage = ($current !== null && $past !== null)
                                                ? ($current - $past)
                                                : '-';
                                                @endphp
                                                <td>مقدار مصرف</td>
                                                <td>{{ $usage }}</td>
                                            </tr>
                                            <tr>
                                                <td>قیمت فی کیلووات</td>
                                                <td>{{ $accounting->degree_price }}</td>
                                            </tr>
                                            <tr>
                                                <td>مبلغ قابل پرداخت</td>
                                                <td>{{ $accounting->price }}</td>
                                            </tr>
                                            <tr>
                                                <td>مبلغ پرداخت شده</td>
                                                <td>{{ $accounting->paid }}</td>
                                            </tr>
                                            <tr>
                                                <td>باقیات</td>
                                                <td>{{ $accounting->remained }}</td>
                                            </tr>

                                        </tbody>


                                    </table>
                                </div>
                            </div>


                        </div>
        </div>
    </div>

    </td>

    <!-- ستون چپ (کپی یا فرم دیگری مشابه) -->
    <td>
        <div class="column column-left" style="margin-top: 10px;">
            <div class="header">
                <div style="width: 100%; direction: rtl; font-family: 'DejaVu Sans'; font-size: 12pt;">
                    <table style="width: 100%; border-collapse: collapse; border: none;">
                        <tr>
                            <td
                                style="width: 90%; text-align: right; vertical-align: middle; color: #1e3a8a; border: none;">
                                <strong style="font-size: 10pt;">مجتمع تجارتی عادلیار</strong>
                            </td>

                            <!-- سمت چپ: لوگو -->
                            <td style="width: 40%; text-align: left; border: none;">
                                <img src="{{ public_path('assets/logo.png') }}" alt="لوگو"
                                    style="height: 80px; width:90px;" />
                            </td>

                        </tr>
                    </table>
                </div>
                <div>
                    <div style="font-size:12px; margin-top:4px;">
                        مسلسل:
                        <span style="border-bottom:1px solid #000; width:150px; margin-top:2px;">{{
                            $accounting->id }}</span>
                    </div>
                </div>
            </div>

            <table class="form-table small-row" role="table">
                <tr>
                    <td style="width:20%;">دوکاندار</td>
                    <td style="width:30%;">مارکت مربوطه</td>
                    <td style="width:25%;">نمبر دوکان</td>
                </tr>
                <tr>
                    <td>{{ $accounting->shopkeeper->fullname }}</td>
                    <td>{{ $accounting->market->name }}</td>
                    <td>{{ $accounting->shop->number ?? '-' }}</td>
                </tr>
            </table>

            <div style="margin-top:6px;">
                <table class="amount-rows">
                    <tr>
                        <td style="width:55%;">درجه فعلی</td>
                        <td>{{ $accounting->current_degree ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>درجه قبلی</td>
                        <td>{{ $accounting->past_degree ?? '-' }}</td>
                    </tr>
                    <tr>
                        @php
                        $current = $accounting->current_degree ?? null;
                        $past = $accounting->past_degree ?? null;

                        $usage = ($current !== null && $past !== null)
                        ? ($current - $past)
                        : '-';
                        @endphp
                        <td>مقدار مصرف</td>
                        <td>{{ $usage }}</td>
                    </tr>
                    <tr>
                        <td>قیمت فی کیلووات</td>
                        <td>{{ $accounting->degree_price }}</td>
                    </tr>
                    <tr>
                        <td>مبلغ قابل پرداخت</td>
                        <td>{{ $accounting->price }}</td>
                    </tr>
                    <tr>
                        <td>مبلغ پرداخت شده</td>
                        <td>{{ $accounting->paid }}</td>
                    </tr>
                    <tr>
                        <td>باقیات</td>
                        <td>{{ $accounting->remained }}</td>
                    </tr>
                </table>
            </div>

        </div>
    </td>
    </tr>
    </table>
    </div> <!-- card -->
    </div>


</body>

</html>