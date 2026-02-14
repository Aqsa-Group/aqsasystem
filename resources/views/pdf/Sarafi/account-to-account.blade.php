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
    <title>تبدیل ارز - {{ $conversion->type }}</title>
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
            font-size: 12px;
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
            font-size: 16px;
            margin-bottom: 5px;
            color: #333;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            border: 1px solid #999;
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
            padding: 8px;
            background-color: #f9f9f9;
            border-right: 3px solid #2B65E5;
            border: 1px solid #999;
            margin-bottom: 15px;
            text-align: right;

        }

        .description h3 {
            margin-bottom: 6px;
            font-size: 12px;
            color: #333;
            text-align: right;

        }


        .signature-line {
            width: 150px;
            height: 1px;
            background: #777;
            margin-top: 30px;
        }

        .contact-info {
            margin-bottom: 10px;
            padding-top: 10px;
            text-align: right;
        }

        .note {
            font-size: 10px;
            color: #666;
            text-align: center;
            margin-top: 10px;
            padding: 8px;
            border-top: 1px dashed #999;
        }

        .amount-in-words {
            font-size: 9px;
            color: #666;
            font-style: italic;
            margin-top: 2px;
        }



        @media print {
            body {
                background-color: white;
                padding: 0;
                width: 72.1mm;
            }

            .document {
                box-shadow: none;
            }
        }
    </style>
</head>

<body>
    <div class="document">
        <div class="header">
            <h1 style="text-align:center ; font-size: 23px; margin-bottom: 6px; " ;>صرافی {{ Auth::guard('sarafi')->user()->sarafi_name ??
                'صرافی' }}</h1>

            <table class="info-table2" style="width: 100%; font-size: 14px; white-space: nowrap; ">
                <tr>
                    {{-- ستون ID --}}
                    <td style="width: 60%; text-align: right;" dir="rtl">
                        نمبر سند: {{ $conversion->id }}
                    </td>

                    {{-- ستون زمان و تاریخ --}}
                    <td style="width: 40%; text-align: left; white-space: nowrap;" dir="rtl">

                        {{ \Morilog\Jalali\Jalalian::fromDateTime($conversion->created_at)->format('Y/m/d') }}

                        <span style="white-space: nowrap;">
                            {{ $conversion->created_at->format('h:i') }}
                            {{ $conversion->created_at->format('A') == 'AM' ? 'قبل از ظهر' : 'بعد از ظهر' }}
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

                function convertToWords($number) {
                if (!is_numeric($number)) return '';
                try {
                $formatter = new NumberFormatter("fa", NumberFormatter::SPELLOUT);
                $words = $formatter->format(floatval($number));
                return str_replace(['دویست', 'سیصد', 'پانصد'], ['دوصد', 'سه صد', 'پنجصد'], $words);
                } catch (\Exception $e) {
                return '';
                }
                }
                @endphp

                <tr>
                    <td>حساب برداشت:</td>
                    <td>
                        {{ $conversion->fromCustomer->fullname ?? 'نامشخص' }}
                        <div class="amount-in-words">
                            {{ $conversion->fromCustomer->account_number ?? 'نامشخص' }}
                        </div>
                    </td>
                </tr>



                <tr>
                    <td>مبلغ برداشت:</td>
                    <td>
                        {{ number_format((float)$conversion->withdrawal_amount) }} (
                        {{ $currenciesFa[strtolower($conversion->currency)] ?? $conversion->currency }}

                        )

                    </td>
                </tr>

                <tr>
                    <td>حساب دریافت:</td>
                    <td>
                        {{ $conversion->toCustomer->fullname ?? 'نامشخص' }}
                        <div class="amount-in-words">
                            {{ $conversion->toCustomer->account_number ?? 'نامشخص' }}
                        </div>
                    </td>
                </tr>



                <tr>
                    <td>مبلغ دریافت:</td>
                    <td>
                        {{ number_format((float)$conversion->received_amount, ) }}
                        (
                        {{ $currenciesFa[strtolower($conversion->currency)] ?? $conversion->currency }}

                        )

                    </td>
                </tr>

                @if ($conversion->type==='باتفاوت')
                <tr>
                    <td>مبلغ کمیشن:</td>
                    <td>
                        {{ number_format((float)$conversion->tax_amount, ) }} (
                        {{ $currenciesFa[strtolower($conversion->currency)] ?? $conversion->currency }}

                        )

                    </td>
                </tr>

                @endif

            </table>

            <div class="description">
                <h3>شرح تراکنش:</h3>
                {{ $conversion->description_sender ?? 'تبدیل ارز - بدون توضیحات بیشتر' }}<br>
                {{ $conversion->description_receiver ?? 'تبدیل ارز - بدون توضیحات بیشتر' }}
            </div>


            <div class="contact-info">
                <div style="margin-bottom: 5px;">
                    <strong>تماس:</strong> {{ Auth::guard('sarafi')->user()->phone ? '+93' .
                    Auth::guard('sarafi')->user()->phone : 'نامشخص' }}
                </div>
                <div>
                    <strong>آدرس شبعه اول:</strong> {{ $currentUser->address ?? '-' }} <br>
                    <strong>آدرس شبعه دوم:</strong> {{ $currentUser->address2 ?? '-' }} <br>

                </div>
            </div>

            <div class="note">
                نوت: این سند جهت معلومات چاپ شده، و هیچگاه سند پولی محسوب نخواهد شد.
            </div>

</body>

</html>