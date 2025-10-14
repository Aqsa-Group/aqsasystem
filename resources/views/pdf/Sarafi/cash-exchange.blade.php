<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>تبدیل ارز صرافی - {{ Auth::guard('sarafi')->user()->sarafi_name ?? 'صرافی' }}</title>
    <style>
        /* همه عناصر بدون حاشیه و با فونت پیشفرض */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* تعریف فونت Shabnam */
        @font-face {
            font-family: "Shabnam-FD";
            src: url("/fonts/Shabnam-FD.ttf") format("truetype");
            font-weight: normal;
            font-style: normal;
        }

        .shabnam-fd {
            font-family: "Shabnam-FD", sans-serif;
        }

        body {
            font-family: "Shabnam-FD", sans-serif;
            width: 85mm;
            margin: 0 auto;
            padding: 0;
            background-color: white;
        }

        .document {
            width: 85mm;
            margin: 0 auto;
            background-color: white;
            padding: 10px;
            line-height: 1.6;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
        }

        .header h1 {
            font-size: 18px;
            margin-bottom: 5px;
            color: #333;
        }

        .header .date {
            font-size: 14px;
            color: #666;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            border: 1px solid #999;
        }

        .info-table td {
            padding: 8px 10px;
            border: 1px solid #999;
        }

        .info-table td:first-child {
            font-weight: bold;
            width: 40%;
            background-color: #f5f5f5;
        }

        .description {
            padding: 10px;
            background-color: #f9f9f9;
            border-right: 3px solid #2B65E5;
            border-top: 1px solid black;
            border-left: 1px solid black;
            border-bottom: 1px solid black;
        }

        .description h3 {
            margin-bottom: 8px;
            font-size: 14px;
            color: #333;
        }

        .contact-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding: 5px 0;
            margin-top: 80px;
            border-top: 1px solid black;
        }

        .contact-item {
            flex: 1;
            text-align: center;
        }

        .signature {
            text-align: right;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
        }

        .signature-top-border {
            width: 100%;
            padding-left: 20px;
            margin-bottom: 30px;
        }

        .signature-text {
            margin-bottom: 60px;
        }

        .signature-line {
            width: 180px;
            height: 1px;
            background: #777;
        }

        .note {
            font-size: 16px;
            color: black;
            text-align: center;
            margin-top: 20px;
            padding: 14px;
            border-radius: 3px;
            border-top: 1px #999 dashed;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 12px;
            color: black;
        }

        @media print {
            body {
                background-color: white;
                padding: 0;
                width: 85mm;
            }

            .document {
                box-shadow: none;
                border: 1px solid #000;
            }
        }

        .header-table {
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>

<body>
    <div class="document">
        <h1 style="text-align:center; font-family: amiri ,sans-serif" class="shabnam-fd">صرافی {{ Auth::guard('sarafi')->user()->sarafi_name }}</h1>
        
        <div class="header">
            <table class="header-table" style="width:100%; border-collapse: collapse;">
                <tr>
                    <td style="text-align:right;">نوع معامله: {{ $conversion->type }}</td>
                    <td style="text-align:left;">تاریخ ثبت معامله: {{ $conversion->transaction_date }}</td>
                </tr>
            </table>
        </div>

        <table class="info-table">
            @php
            $currenciesFa = [
                'afn' => 'افغانی',
                'usd' => 'دالر',
                'eur' => 'یورو',
                'irr' => 'تومان',
                'aed' => 'درهم',
                'try' => 'لیره',
                'cny' => 'یوان',
                'pkr' => 'کلدار',
                'gbp' => 'پوند',
                'jpy' => 'ین',
                'sar' => 'ریال سعودی',
                'inr' => 'روپیه',
            ];

            // تابع تبدیل تاریخ میلادی به شمسی
            function gregorianToJalali($gy, $gm, $gd) {
                $g_d_m = [0,31,59,90,120,151,181,212,243,273,304,334];
                $jy = ($gy <= 1600) ? 0 : 979;
                $gy -= ($gy <= 1600) ? 621 : 1600;
                $gy2 = ($gm > 2) ? ($gy + 1) : $gy;
                $days = (365 * $gy) + ((int)(($gy2 + 3) / 4)) - ((int)(($gy2 + 99) / 100)) 
                      + ((int)(($gy2 + 399) / 400)) - 80 + $gd + $g_d_m[$gm-1];
                $jy += 33 * ((int)($days / 12053)); 
                $days %= 12053;
                $jy += 4 * ((int)($days / 1461));
                $days %= 1461;
                $jy += (int)(($days - 1) / 365);
                if ($days > 365) $days = ($days-1) % 365;
                $jm = ($days < 186) ? 1 + (int)($days / 31) : 7 + (int)(($days - 186) / 30);
                $jd = 1 + (($days < 186) ? ($days % 31) : (($days - 186) % 30));
                return [$jy, $jm, $jd];
            }
            @endphp

            <tr>
                <td>حساب برداشت:</td>
                <td>{{ $conversion->fromCustomer->fullname ?? 'نامشخص' }}</td>
            </tr>

            <tr>
                <td>شماره حساب برداشت:</td>
                <td>{{ $conversion->fromCustomer->account_number ?? 'نامشخص' }}</td>
            </tr>

            <tr>
                <td>از ارز:</td>
                <td>{{ $currenciesFa[strtolower($conversion->from_currency)] ?? $conversion->from_currency }}</td>
            </tr>

            <tr>
                <td>مبلغ برداشت:</td>
                <td>{{ number_format((float)$conversion->withdrawal_amount) }}</td>
            </tr>

            <tr>
                <td>حساب دریافت:</td>
                <td>{{ $conversion->toCustomer->fullname ?? 'نامشخص' }}</td>
            </tr>

            <tr>
                <td>شماره حساب دریافت:</td>
                <td>{{ $conversion->toCustomer->account_number ?? 'نامشخص' }}</td>
            </tr>

            <tr>
                <td>به ارز:</td>
                <td>{{ $currenciesFa[strtolower($conversion->to_currency)] ?? $conversion->to_currency }}</td>
            </tr>

            <tr>
                <td>مبلغ دریافت:</td>
                <td>{{ number_format((float)$conversion->received_amount, 2) }}</td>
            </tr>

            <tr>
                <td>نرخ ارز:</td>
                <td>{{ number_format((float)$conversion->currency_rate, 4) }}</td>
            </tr>

            <tr>
                <td>زون برداشت:</td>
                <td>{{ $conversion->zone_sender }}</td>
            </tr>

            <tr>
                <td>زون دریافت:</td>
                <td>{{ $conversion->zone_receiver }}</td>
            </tr>

            <tr>
                <td>مسئول برداشت:</td>
                <td>{{ $conversion->by_sender ?? 'نامشخص' }}</td>
            </tr>

            <tr>
                <td>مسئول دریافت:</td>
                <td>{{ $conversion->by_receiver ?? 'نامشخص' }}</td>
            </tr>

            <tr>
                <td>زمان ثبت:</td>
                <td>
                    @php
                        try {
                            $time = \Carbon\Carbon::parse($conversion->created_at);
                            echo $time->format('h:i:s') . ' ' . ($time->format('A') == 'AM' ? 'ق.ظ' : 'ب.ظ');
                        } catch (Exception $e) {
                            echo $conversion->created_at;
                        }
                    @endphp
                </td>
            </tr>
        </table>

        <div class="description">
            <h3>شرح تراکنش:</h3>
            {{ $conversion->description ?? 'تبدیل ارز - بدون توضیحات بیشتر' }}
        </div>

        <div class="signature">
            <div class="signature-top-border"></div>
            <div class="signature-text"><strong>امضاء</strong></div>
            <div class="signature-line"></div>
        </div>

        <div class="contact-info">
            <table style="width:100%; border-collapse: collapse;">
                <tr>
                    <td>
                        <strong>تماس:</strong> +93{{ Auth::guard('sarafi')->user()->phone }}
                    </td>
                </tr>

                <tr>
                    <td>
                        <strong>آدرس:</strong> افغانستان {{ Auth::guard('sarafi')->user()->address }}
                    </td>
                </tr>
            </table>
        </div>

        <div class="note">
            نوت: سند جهت معلومات چاپ شده، و هیچگاه سند پولی محسوب نخواهد شد.
        </div>

    </div>
</body>

</html>