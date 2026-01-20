<!DOCTYPE html>
<html lang="fa" dir="rtl">
@php
$currentUser = Auth::guard('sarafi')->user();
@endphp

<head>
    <meta charset="UTF-8">
    <title>تراکنش صرافی - {{ $sarafi_name ?? 'صرافی' }}</title>
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
            /* راست‌چین کردن محتوا */
            /* فاصله کل بخش امضا با بخش بالایی */
        }

        .signature-top-border {
            /* بورد بالایی */
            width: 100%;
            padding-left: 20px;
            margin-bottom: 30px;

                {
                    {
                    explode(' ', $transaction->date)[0]
                }
            }

            /* فاصله بین بورد و متن/خط پایین */
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
        <h1 style="text-align:center; font-family: amiri ,sans-serif" class="shabnam-fd "> صرافی {{
            $currentUser->sarafi_name ?? 'صرافی' }}</h1>
        <div class="header">
            <table class="header-table" style="width:100%; border-collapse: collapse;">
                <tr>
                    <td style="text-align:center;">نوع تراکنش : {{ $transaction->type }}</td>

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
            @endphp


            <tr>
                <td>حساب مشتری:</td>
                <td>{{ $transaction->customer->fullname ?? 'نامشخص' }}</td>
            </tr>

            <tr>
                <td>شماره حساب :</td>
                <td>{{ $transaction->customer->account_number ?? 'نامشخص' }}</td>
            </tr>

            <tr>
                <td>نوع حساب :</td>
                <td>{{ $transaction->account_type ?? 'نامشخص' }}</td>
            </tr>
            <tr>
                <td> ارز:</td>
                <td>
                    {{ $currenciesFa[strtolower($transaction->currency)] ?? $transaction->currency }}
                    ({{ strtoupper($transaction->currency) }})
                </td>
            </tr>

            <tr>
                <td>مبلغ:</td>
                <td>
                    {{ number_format((float)$transaction->amount) }}
                </td>
            </tr>

            <tr>
                <td>تاریخ:</td>
                <td>
                    {{ explode(' ', $transaction->date)[0] }}
                </td>
            </tr>

            <tr>
                <td>زمان:</td>
                <td>
                    {{ $transaction->created_at->format('h:i:s') }}
                    {{ $transaction->created_at->format('A') == 'AM' ? 'قبل از ظهر' : 'بعد از ظهر' }}
                </td>
            </tr>
            <tr>
                <td>شناسه تراکنش:</td>
                <td>{{ $transaction->id }}</td>
            </tr>
        </table>

        <div class="description">
            <h3>توضیحات تراکنش:</h3>
            {{ $transaction->description ?? 'بدون توضیحات بیشتر' }}
        </div>

        <div class="signature">
            <div class="signature-top-border"></div>
            <div class="signature-text"><strong>امضاء</strong></div>
            <div class="signature-line"></div>
        </div>

        <div class="contact-info">
            <table style="width:100%; border-collapse: collapse; pass">
                <tr>
                    <td>
                        <strong>تماس:</strong> 93{{ $currentUser->phone ?? '-' }}+
                    </td>
                </tr>

                <tr>
                    <td>
                        <strong>آدرس:</strong> افغانستان {{ $currentUser->address ?? '-' }}
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