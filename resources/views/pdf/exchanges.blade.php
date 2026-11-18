
<!DOCTYPE html>
<html lang="fa" dir="rtl">

@php
use Morilog\Jalali\Jalalian;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

$currentUser = Auth::guard('market')->user();

if ($currentUser && $currentUser->role === 'admin') {
    $adminUser = $currentUser;
} else {
    $adminUser = $currentUser
        ? \App\Models\Market\User::find($currentUser->admin_id)
        : null;
}

// مسیر لوگو
if (
    $adminUser &&
    $adminUser->user_image &&
    Storage::disk('public')->exists($adminUser->user_image)
) {
    $logoPath = storage_path('app/public/' . $adminUser->user_image);
} else {
    $logoPath = public_path('assets/logo.png');
}

// واحدهای پول
$currencies = [
    'AFN' => 'افغانی',
    'USD' => 'دالر',
    'TOMAN' => 'تومان',
    'IRR' => 'تومان',
    'EUR' => 'یورو',
];

// فرمت مبلغ
$faNum = fn($n) => number_format((float) $n, 0);

// تاریخ شمسی
$faDate = function ($date, $fmt = 'Y/m/d H:i') {
    if (!$date) return '—';

    return Jalalian::fromDateTime($date)->format($fmt);
};

// نام ارز
$faCur = fn($c) => $c ? ($currencies[$c] ?? $c) : '—';

@endphp

<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>

    <style>
        @page {
            margin: 10mm 12mm;
        }

        body {
            font-family: 'vazir', 'amiri', 'DejaVu Sans', sans-serif;
            font-size: 10px;
            direction: rtl;
            margin: 0;
            padding: 0;
            color: #222;
        }

        /* سربرگ */
        .header {
            width: 100%;
            border-collapse: collapse;
            margin: 0 0 5px 0;
        }

        .header td {
            border: none;
            padding: 2px;
        }

        .header-title {
            width: 40%;
            text-align: right;
            vertical-align: middle;
            color: #1e3a8a;
        }

        .header-title strong {
            font-size: 14pt;
        }

        .header-logo {
            width: 20%;
            text-align: center;
            vertical-align: middle;
        }

        .header-en {
            width: 40%;
            text-align: left;
            vertical-align: middle;
            color: #1e3a8a;
        }

        /* عنوان گزارش */
        .report-info {
            width: 100%;
            border-collapse: collapse;
            margin: 5px 0;
        }

        .report-info td {
            border: none;
            padding: 4px;
        }

        .report-info h4 {
            margin: 0;
            font-size: 12px;
        }

        /* جدول */
        .main {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
        }

        .main th {
            background-color: #d6d8db !important;
            font-weight: bold;
            border: 1px solid #000;
            padding: 5px 3px;
            text-align: center;
        }

        .main td {
            border: 1px solid #000;
            padding: 5px 3px;
            text-align: center;
            vertical-align: middle;
        }

        /* امضا */
        .sign {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        .sign th {
            border: none;
            background: white;
            text-align: center;
            padding: 15px;
            font-size: 10px;
        }

        .empty {
            text-align: center;
            color: #777;
            padding: 10px !important;
        }

        th {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    </style>
</head>

<body>

    {{-- سربرگ مجتمع تجارتی عادلیار --}}
    <table class="header">
        <tr>

            {{-- نام فارسی --}}
            <td class="header-title">
                <strong>
                    مجتمع تجارتی عادلیار
                </strong>
            </td>

            {{-- لوگوی کوچک --}}
            <td class="header-logo">
                <img
                    src="{{ $logoPath }}"
                    alt="لوگو"
                    width="70"
                    height="70"
                    style="width: 70px; height: 70px; display: inline-block;"
                >
            </td>

            {{-- نام انگلیسی --}}
            <td class="header-en">
                <div style="line-height: 1.2;">
                    <div style="font-weight: bold; font-size: 14pt;">
                        ADELYAR
                    </div>

                    <div style="font-weight: bold; font-size: 7pt;">
                        COMMERCIAL COMPLEX
                    </div>
                </div>
            </td>

        </tr>
    </table>

    {{-- عنوان گزارش --}}
    <table class="report-info">
        <tr>
            <td>
                <h4>{{ $title }}</h4>
            </td>

            <td style="text-align: left;">
                <small>
                    تاریخ چاپ: {{ $faDate(now()) }}
                </small>
            </td>
        </tr>
    </table>

    {{-- جدول اطلاعات تبدیل ارز --}}
    <table class="main">
        <thead>
            <tr>
                <th>#</th>
                <th>از</th>
                <th>به</th>
                <th>مقدار</th>
                <th>واحد پول</th>
                <th>توضیحات</th>
                <th>تاریخ ثبت</th>
            </tr>
        </thead>

        <tbody>
            @forelse($exchanges as $i => $ex)
                <tr>
                    <td>{{ $i + 1 }}</td>

                    <td>{{ $ex->from_type }}</td>

                    <td>{{ $ex->to_type }}</td>

                    <td>
                        {{ $faNum($ex->amount) }}
                    </td>

                    <td>
                        {{ $faCur($ex->currency) }}
                    </td>

                    <td>
                        {{ $ex->description ?: '—' }}
                    </td>

                    <td dir="ltr">
                        {{ $faDate($ex->created_at) }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="empty">
                        داده‌ای برای چاپ وجود ندارد
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- محل امضا --}}
    <table class="sign">
        <tr>
            <th>
                امضا مدیر مالی
            </th>

            <th>
                امضا مسئول صندوق
            </th>
        </tr>
    </table>

</body>
</html>
