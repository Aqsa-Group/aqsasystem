<!DOCTYPE html>
<html lang="fa" dir="rtl">
@php
$currentUser = Auth::guard('sarafi')->user();

$adminUser = $currentUser->role === 'admin'
? $currentUser
: \App\Models\Sarafi\User::find($currentUser->admin_id);
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
            

            font-weight: normal;
            font-style: normal;
        }



        body {
        
            width: 72.1mm;
            margin: 0 auto;
            padding: 0;
            background-color: white;
            font-size: 10px;
            line-height: 1.4;
        }

        .document {
            width: 85mm;
            margin: 0 auto;
            background-color: white;
            padding: 8px;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 1px solid #ddd;

        }

        .header h1 {
            font-size: 10px;
            margin-bottom: 5px;
            color: #50c90a;

        }

        .header .date {
            font-size: 14px;
            color: #666;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            border: 1px solid #999;
            font-size: 11px;
        }

        .info-table2 {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
            font-size: 11px;

        }

        .info-table td {
            padding: 6px 8px;
            border: 1px solid #999;
        }

        .info-table td:first-child {
            font-weight: bold;
            width: 40%;
            background-color: #f5f5f5;
        }

        .description {
            padding-top: 2px;
            padding-bottom: 2px;
            padding-right: 8px;
            padding-left: 8px;
            background-color: #f9f9f9;
            border-right: 1px solid #2B65E5;
            border: 1px solid #999;
            margin-bottom: 15px;
        }

        .description h3 {
            margin-bottom: 6px;
            font-size: 10px;
            color: #333;
        }



        .contact-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding: 5px 0;
            border-top: 1px solid black;
        }

        .contact-item {
            flex: 1;
            text-align: center;
        }


        .signature {
            text-align: right;
            margin-bottom: 15px;
        }

        .signature-top-border {
            /* بورد بالایی */
            width: 100%;
            padding-left: 20px;


                {
                    {
                    explode(' ', $transaction->date)[0]
                }
            }

            /* فاصله بین بورد و متن/خط پایین */
        }

        .signature-text {
            margin-bottom: 20px;

        }

        .signature-line {
            width: 100px;
            height: 1px;
            background: #777;
        }


        .note {
            font-size: 12px;
            color: black;
            text-align: center;
            margin-top: 15px;
            padding: 10px;
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
        <h1 style="text-align:center ; font-size: 23px;"  ;> صرافی {{
            $currentUser->sarafi_name ?? 'صرافی' }}</h1>

        <table class="info-table2" style="width: 100%; font-size: 6px;">
            <tr>
                {{-- ستون ID --}}
                <td style="width: 60%; text-align: right;" dir="rtl">
                    نمبر سند: {{ $transaction->id }}
                </td>

                {{-- ستون زمان و تاریخ --}}
                <td style="width: 50%; text-align: left" dir="rtl">
                    {{-- قبل/بعد از ظهر --}}

                    {{ \Morilog\Jalali\Jalalian::fromDateTime($transaction->created_at)->format('Y/m/d') }}

                    {{-- ساعت --}}
                    {{ $transaction->created_at->format('h:i') }}
                    {{ $transaction->created_at->format('A') == 'AM' ? 'قبل از ظهر' : 'بعد از ظهر' }}


                    {{-- تاریخ شمسی با فرمت yyyy/mm/dd --}}
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
                <td>نوع ترانزکشن :</td>
                <td> {{ $transaction->type }} ( {{ $transaction->account_type ?? 'نامشخص' }} )</td>
            </tr>

            <tr>
                <td>مبلغ:</td>
                <td>
                    {{ number_format((float)$transaction->amount) }}    ({{
                    $currenciesFa[strtolower($transaction->currency)] ?? $transaction->currency }})

                </td>
            </tr>


         
        </table>

        <div class="description">
            <h3>توضیحات تراکنش:</h3>
            {{ $transaction->description ?? 'بدون توضیحات بیشتر' }}
        </div>

        @if(!$isShort)
        {{-- <div class="signature">
            <div class="signature-top-border"></div>
            <div class="signature-text"><strong>امضاء</strong></div>
            <div class="signature-line"></div>
        </div> --}}

        <div class="contact-info">
            <table style="width:100%; border-collapse: collapse; pass">
                <tr>
                    <td>
                        <strong>تماس:</strong> {{ $currentUser->phone ?? '-' }}+
                    </td>
                </tr>

                <tr>
                    <td>
                        <strong>آدرس شبعه اول:</strong>  {{ $currentUser->address ?? '-' }}
                    </td>
                </tr>


                <tr>
                    <td>
                        <strong>آدرس شبعه دوم:</strong>  {{ $currentUser->address2 ?? '-' }}
                    </td>
                </tr>

               
            </table>
        </div>

        <div class="note">
            نوت: سند جهت معلومات چاپ شده، و هیچگاه سند پولی محسوب نخواهد شد.
        </div>
        @endif

    </div>
</body>

</html>