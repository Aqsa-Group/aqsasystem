<!DOCTYPE html>
<html lang="fa" dir="rtl">
@php
use Morilog\Jalali\Jalalian;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

// فرض بر این است که $withdrawal از کنترلر به ویو ارسال شده است
$currentUser = Auth::guard('market')->user();

if ($currentUser->role === 'admin') {
    $adminUser = $currentUser;
} else {
    $adminUser = \App\Models\Market\User::find($currentUser->admin_id);
}

// تعیین مسیر لوگو برای mPDF (از public_path استفاده میکنیم)
if ($adminUser && $adminUser->user_image && Storage::disk('public')->exists($adminUser->user_image)) {
    // مسیر کامل فایل در سیستم (برای mPDF)
    $logoPath = storage_path('app/public/' . $adminUser->user_image);
} else {
    // لوگوی پیش‌فرض (فایل باید در public/assets/logo.png وجود داشته باشد)
    $logoPath = public_path('assets/logo.png');
}
@endphp
<head>
    <meta charset="UTF-8">
    <title>رسید برداشت</title>
    <style>
        body {
            font-family: 'vazir', 'amiri', sans-serif;
            font-size: 12px;
            direction: rtl;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 10px;
            padding-bottom: 5px;
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
            margin: auto;
        }
        .sign th {
            border: none;
            text-align: center;
            vertical-align: middle;
            margin: auto;
            background-color: white;
        }
        .gap {
            height: 120px; /* فاصله بین دو کپی */
        }
        /* برای چاپ رنگ پس‌زمینه */
        th {
            background-color: #d6d8db !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .page-break {
            page-break-after: avoid;
        }
    </style>
</head>
<body>

@php
    $dateStr = Jalalian::fromCarbon($withdrawal->created_at)->format('Y/m/d H:i');
    // تعیین دریافت‌کننده
    $receiver = '-';
    if ($withdrawal->staff) {
        $receiver = $withdrawal->staff->fullname . ' (کارمند)';
    } elseif ($withdrawal->customer) {
        $receiver = $withdrawal->customer->fullname . ' (مشتری)';
    }
    // ارز
    $currency = $withdrawal->currency;
    // مبلغ
    $amount = number_format((float)$withdrawal->amount);
    // نوع برداشت
    $expansesType = $withdrawal->expanses_type;
    // توضیحات
    $description = $withdrawal->description ?? '-';
@endphp

{{-- ===== کپی اول ===== --}}
<div style="width: 100%; direction: rtl; font-family: 'vazir', 'DejaVu Sans'; font-size: 12pt;">
    <table style="width: 100%; border-collapse: collapse; border: none;">
        <tr>
            <td style="width: 40%; text-align: right; vertical-align: middle; color: #1e3a8a; border: none;">
                <strong style="font-size: 20pt;">مجتمع تجارتی عادلیار</strong>
            </td>
            <td style="width: 33.33%; text-align: center; border: none;">
                <img src="{{ $logoPath }}" alt="لوگو" style="height: 80px; width: 90px;" />
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

<table style="width: 100%; border-collapse: collapse; border: none; margin-top: 10px;">
    <tr>
        <td style="border: none;">
            <h4>رسید برداشت از صندوق</h4>
        </td>
        <td style="border: none; text-align: left;">
            <small>{{ $dateStr }}</small>
        </td>
    </tr>
</table>

<table class="main">
    <tr>
        <th>نوع برداشت</th>
        <th>ارز</th>
        <th>مقدار</th>
        <th>تحویل گیرنده</th>
        <th>توضیحات</th>
    </tr>
    <tr>
        <td>{{ $expansesType }}</td>
        <td>{{ $currency }}</td>
        <td>{{ $amount }}</td>
        <td>{{ $receiver }}</td>
        <td>{{ $description }}</td>
    </tr>
</table>

<br>

<table class="sign">
    <tr>
        <th style="padding: 25px;">امضا مدیر مالی</th>
        <th style="padding: 25px;">امضا گیرنده</th>
    </tr>
</table>

<div class="gap"></div>

{{-- ===== کپی دوم (دقیقاً مشابه) ===== --}}
<div style="width: 100%; direction: rtl; font-family: 'vazir', 'DejaVu Sans'; font-size: 12pt;">
    <table style="width: 100%; border-collapse: collapse; border: none;">
        <tr>
            <td style="width: 40%; text-align: right; vertical-align: middle; color: #1e3a8a; border: none;">
                <strong style="font-size: 20pt;">مجتمع تجارتی عادلیار</strong>
            </td>
            <td style="width: 33.33%; text-align: center; border: none;">
                <img src="{{ $logoPath }}" alt="لوگو" style="height: 80px; width: 90px;" />
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

<table style="width: 100%; border-collapse: collapse; border: none; margin-top: 10px;">
    <tr>
        <td style="border: none;">
            <h4>رسید برداشت از صندوق</h4>
        </td>
        <td style="border: none; text-align: left;">
            <small>{{ $dateStr }}</small>
        </td>
    </tr>
</table>

<table class="main">
    <tr>
        <th>نوع برداشت</th>
        <th>ارز</th>
        <th>مقدار</th>
        <th>تحویل گیرنده</th>
        <th>توضیحات</th>
    </tr>
    <tr>
        <td>{{ $expansesType }}</td>
        <td>{{ $currency }}</td>
        <td>{{ $amount }}</td>
        <td>{{ $receiver }}</td>
        <td>{{ $description }}</td>
    </tr>
</table>

<br>

<table class="sign">
    <tr>
        <th style="padding: 25px;">امضا مدیر مالی</th>
        <th style="padding: 25px;">امضا گیرنده</th>
    </tr>
</table>

</body>
</html>