<!DOCTYPE html>
<html lang="fa" dir="rtl">
@php
use Morilog\Jalali\Jalalian;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

$currentUser = Auth::guard('market')->user();

if ($currentUser->role === 'admin') {
    $adminUser = $currentUser;
} else {
    $adminUser = \App\Models\Market\User::find($currentUser->admin_id);
}

if ($adminUser && $adminUser->user_image && Storage::disk('public')->exists($adminUser->user_image)) {
    $logoPath = storage_path('app/public/' . $adminUser->user_image);
} else {
    $logoPath = public_path('assets/png');
}
@endphp

<head>
    <meta charset="UTF-8">
    <title>رسید برداشت</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            margin: 0 auto;
            padding: 0;
            background-color: white;
            font-size: 12px;
            font-weight: bold;
            line-height: 1.6;
            color: #000000;
            font-family: 'vazir', sans-serif;
        }

        .document {
            width: 85mm;
            margin: 0 auto;
            background-color: white;
            padding: 10px 8px;
        }

        /* ===== HEADER ===== */
        .header {
            text-align: center;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 1.5px solid #333;
        }

        .header h1 {
            font-size: 20px;
            font-weight: bolder;
            margin-bottom: 4px;
            color: #000;
            letter-spacing: 0.5px;
        }

        .header .sub-title {
            font-size: 13px;
            font-weight: normal;
            color: #444;
        }

        /* ===== جدول شماره سند و تاریخ ===== */
        .info-table2 {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
            font-size: 13px;
            font-weight: bold;
            color: #000;
        }

        .info-table2 td {
            padding: 4px 0;
        }

        /* ===== جدول اصلی اطلاعات ===== */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            border: 1.5px solid #333;
            font-size: 13px;
            font-weight: bold;
            color: #000;
        }

        .info-table td {
            padding: 6px 10px;
            border: 1px solid #333;
            font-weight: bold;
            color: #000;
            vertical-align: middle;
        }

        .info-table td:first-child {
            font-weight: bolder;
            width: 38%;
            background-color: #e9e9e9;
            text-align: center;
        }

        .info-table td:last-child {
            text-align: right;
            padding-right: 12px;
        }

       .description {
            padding: 8px;
            background-color: #f9f9f9;
            border: 1.5px solid #333;
            margin-bottom: 15px;
            text-align: right;
            color: #000;
        }

        .description h3 {
            margin-bottom: 6px;
            font-size: 14px;
            font-weight: bolder;
            color: #000;
            text-align: right;
        }


        /* ===== فوتر ===== */
        .footer-note {
            font-size: 12px;
            color: #333;
            text-align: center;
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ccc;
            font-weight: normal;
        }

        /* ===== واژه‌های کلیدی ===== */
        strong {
            font-weight: bolder;
            color: #000;
        }

        /* ===== پرینت ===== */
        @media print {
            body {
                padding: 0;
                margin: 0;
            }
            .document {
                box-shadow: none;
                padding: 6px;
            }
            .info-table td:first-child {
                background-color: #e9e9e9 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .description-box {
                background-color: #f9f9f9 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>

<body>

    <div class="document">

        <!-- ===== HEADER ===== -->
        <div class="header">
            <h1>متجمع تجارتی عادلیار</h1>
            <div class="sub-title">رسید برداشت از صندوق</div>
        </div>

        <!-- ===== شماره سند و تاریخ ===== -->
        <table class="info-table2">
            <tr>
                <td style="text-align: right; font-weight: bold;">
                    نمبر سند: <span style="font-weight: bolder;">{{ $withdrawal->id }}</span>
                </td>
                <td style="text-align: left; white-space: nowrap; font-weight: bold;">
                    {{ Jalalian::fromCarbon($withdrawal->created_at)->format('Y/m/d') }}
                    <span style="white-space: nowrap;">
                        {{ $withdrawal->created_at->format('h:i') }}
                    </span>
                </td>
            </tr>
        </table>

        <!-- ===== جدول اطلاعات اصلی ===== -->
        <table class="info-table">
            <tr>
                <td>نوع برداشت</td>
                <td>{{ $withdrawal->expanses_type }}</td>
            </tr>
            <tr>
                <td>ارز</td>
                <td>
                    {{
                        match($withdrawal->currency) {
                            'AFN' => 'افغانی',
                            'USD' => 'دالر',
                            'EUR' => 'یورو',
                            'IRR' => 'تومان',
                            default => $withdrawal->currency,
                        }
                    }}
                </td>
            </tr>
            <tr>
                <td>مبلغ برداشت</td>
                <td>{{ number_format((float)$withdrawal->amount) }}</td>
            </tr>
            <tr>
                <td>دریافت‌کننده</td>
                <td>
                    @if($withdrawal->staff)
                        {{ $withdrawal->staff->fullname }} (کارمند)
                    @elseif($withdrawal->customer)
                        {{ $withdrawal->customer->fullname }} (مشتری)
                    @else
                        <span style="color:#888;">—</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td>تاریخ برداشت</td>
                <td>{{ Jalalian::fromCarbon($withdrawal->created_at)->format('Y/m/d H:i') }}</td>
            </tr>
        </table>

        <!-- ===== باکس توضیحات (بدون border) ===== -->
        @if($withdrawal->description)
        <div class="description">
            <h3>شرح برداشت:</h3>
            <div class="desc-text">{{ $withdrawal->description }}</div>
        </div>
        @endif

     

    </div>

</body>
</html>