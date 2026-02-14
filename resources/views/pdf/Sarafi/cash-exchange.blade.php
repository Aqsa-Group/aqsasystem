<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>تبدیل ارز صرافی - {{ Auth::guard('sarafi')->user()->sarafi_name ?? 'صرافی' }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }





        body {
            width: 72.1mm;
            margin: 0 auto;
            padding: 0;
            background-color: white;
        }

        .document {
            width: 72.1mm;
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
            margin-bottom: 10px;
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
            padding: 5px 0;
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
                width: 72.1mm;
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
        <h1 style="text-align:center;">صرافی {{ Auth::guard('sarafi')->user()->sarafi_name }}</h1>
          <table class="info-table2" style="width: 100%; font-size: 14px; white-space: nowrap; ">
            <tr>
                {{-- ستون ID --}}
                <td style="width: 60%; text-align: right;" dir="rtl">
                    نمبر سند: {{ $transaction->id }}
                </td>

                {{-- ستون زمان و تاریخ --}}
                <td style="width: 40%; text-align: left; white-space: nowrap;" dir="rtl">

                    {{ \Morilog\Jalali\Jalalian::fromDateTime($transaction->created_at)->format('Y/m/d') }}

                    <span style="white-space: nowrap;">
                        {{ $transaction->created_at->format('h:i') }}
                        {{ $transaction->created_at->format('A') == 'AM' ? 'قبل از ظهر' : 'بعد از ظهر' }}
                    </span>

                </td>

            </tr>
        </table>

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
            $jy = ($gy <= 1600) ? 0 : 979; $gy -=($gy <=1600) ? 621 : 1600; $gy2=($gm> 2) ? ($gy + 1) : $gy;
                $days = (365 * $gy) + ((int)(($gy2 + 3) / 4)) - ((int)(($gy2 + 99) / 100))
                + ((int)(($gy2 + 399) / 400)) - 80 + $gd + $g_d_m[$gm-1];
                $jy += 33 * ((int)($days / 12053));
                $days %= 12053;
                $jy += 4 * ((int)($days / 1461));
                $days %= 1461;
                $jy += (int)(($days - 1) / 365);
                if ($days > 365) $days = ($days-1) % 365;
                $jm = ($days < 186) ? 1 + (int)($days / 31) : 7 + (int)(($days - 186) / 30); $jd=1 + (($days < 186) ?
                    ($days % 31) : (($days - 186) % 30)); return [$jy, $jm, $jd]; } @endphp <tr>
                    <td>مبلغ برداشت:</td>
                    <td>{{ number_format((float)$transaction->amount) }}
                        (
                        {{ $currenciesFa[strtolower($transaction->from_currency)] ?? $transaction->from_currency }}
                        )
                    </td>
                    </tr>



                    <tr>
                        <td>نرخ ارز:</td>
                        <td>{{ number_format((float)$transaction->exchange_rate, 2) }}</td>
                    </tr>

                    <tr>
                        <td> مبلغ دریافتی:</td>
                        <td>{{ number_format((float)$transaction->eq_amount, 2) }}
                            (
                            {{ $currenciesFa[strtolower($transaction->to_currency)] ?? $transaction->to_currency }}
                            )
                        </td>
                    </tr>




                 
        </table>

        <div class="description">
            <h3>شرح تراکنش:</h3>
            {{ $transaction->description ?? 'تبدیل ارز - بدون توضیحات بیشتر' }}
        </div>


    

    @if($isShort)
    <div style="text-align:center; font-size:10px; margin-bottom:5px;">
        @if(!empty($barcodeImage))
        <img src="data:image/png;base64,{{ $barcodeImage }}" alt="بارکد تراکنش"
            style="width:120px; height:20px; display:block; margin:5px auto;">
        @endif

        @php
        $amount = (int) ($transaction->journal->safe_balance ?? 0);
        $digits = str_split((string) $amount);
        $count = max(count($digits), 1);
        $cellWidth = floor(120 / $count);
        @endphp

        <div style="direction:ltr;">
            <table style="margin:0 auto; border-collapse:collapse; width:120px; table-layout:fixed;">
                <tr>
                    @foreach($digits as $digit)
                    <td style="
                        width:{{ $cellWidth }}px;
                        font-size:12px;
                        text-align:center;
                        padding:0;
                        overflow:hidden;
                    ">
                        {{ $digit }}
                    </td>
                    @endforeach
                </tr>
            </table>
        </div>
    </div>
    @endif
    
    </div>

</body>

</html>