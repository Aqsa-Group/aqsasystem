@php
    $currencies = [
        'AFN' => 'افغانی',
        'USD' => 'دالر',
        'TOMAN' => 'تومان',
        'EUR' => 'یورو',
    ];

    // بدون اعشار + جداکننده هزارگان
    $faNum = fn($n) => number_format((float) $n, 0);

    // تاریخ شمسی با ارقام انگلیسی
    $faDate = function ($date, $fmt = 'Y/m/d H:i') {
        if (!$date)
            return '—';
        return \Hekmatinasser\Verta\Verta::instance($date)->format($fmt);
    };

    $faCur = fn($c) => $c ? ($currencies[$c] ?? $c) : '—';
@endphp
<!DOCTYPE html>
<html dir="rtl" lang="fa">

<head>
    <meta charset="utf-8">
    <style>
        body {
            direction: rtl;
            text-align: right;
            color: #222;
        }

        h1 {
            text-align: center;
            margin: 0 0 10px;
            font-size: 18px;
        }

        .meta {
            font-size: 11px;
            color: #666;
            margin-bottom: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #999;
            padding: 6px 8px;
            font-size: 11px;
        }

        th {
            background: #f0f0f0;
        }

        .empty {
            text-align: center;
            color: #999;
        }

        .footer {
            margin-top: 15px;
            font-size: 10px;
            color: #888;
            text-align: center;
        }

        .num {
            direction: ltr;
            text-align: right;
        }
    </style>
</head>

<body>
    <h1>{{ $title }}</h1>

    <table>
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
                    <td class="num">{{ $i + 1 }}</td>
                    <td>{{ $ex->from_type }}</td>
                    <td>{{ $ex->to_type }}</td>
                    <td class="num">{{ $faNum($ex->amount) }}</td>
                    <td>{{ $faCur($ex->currency) }}</td>
                    <td>{{ $ex->description ?: '—' }}</td>
                    <td class="num">{{ $faDate($ex->created_at) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="empty">داده‌ای برای چاپ وجود ندارد</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">این سند به‌صورت خودکار تولید شده است.</div>
</body>

</html>
