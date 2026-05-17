<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
<meta charset="UTF-8">
<title>لیست رسیدهای دوکانداران</title>

<style>
body {
    font-family: vazir, sans-serif;
    font-size: 12px;
    background: #fff;
    margin: 0;
    color: #111;
}

.page {
    padding: 15px;
}

/* HEADER */
.header {
    text-align: center;
    margin-bottom: 15px;
}

.header h2 {
    margin: 0;
    font-size: 20px;
}

.header p {
    margin: 4px 0;
    font-weight: bold;
}

/* TABLE */
.table {
    width: 100%;
    border-collapse: collapse;
}

.table th,
.table td {
    border: 1px solid #333;
    padding: 6px;
    text-align: center;
    font-weight: bold;
}

.table th {
    background: #095264;
    color: #fff;
}

/* BADGES */
.badge-shop {
    background: #e0f2fe;
    padding: 3px 6px;
    border-radius: 4px;
    display: inline-block;
}

.badge-booth {
    background: #fef3c7;
    padding: 3px 6px;
    border-radius: 4px;
    display: inline-block;
}
</style>

</head>

<body>

<div class="page">

    {{-- HEADER --}}
    <div class="header">
        <h2>لیست رسیدهای دوکانداران</h2>
        <p>تاریخ چاپ: {{ \Morilog\Jalali\Jalalian::now()->format('Y/m/d') }}</p>
    </div>

    {{-- TABLE --}}
    <table class="table">

        <thead>
            <tr>
                <th>#</th>
                <th>نوع</th>
                <th>نوع مصرف</th>
                <th>مارکت</th>
                <th>دوکاندار</th>
                <th>واحد</th>
                <th>مبلغ</th>
                <th>ارز</th>
                <th>تاریخ</th>
            </tr>
        </thead>

        <tbody>
            @foreach($receipts as $index => $receipt)
            <tr>
                <td>{{ $index + 1 }}</td>

                <td>{{ $receipt->type }}</td>
                <td>{{ $receipt->expanses_type }}</td>

                <td>{{ $receipt->market->name ?? '—' }}</td>

                <td>{{ $receipt->shopkeeper->fullname ?? '—' }}</td>

                <td>
                    @if($receipt->shop_id)
                        <span class="badge-shop">
                            دوکان {{ $receipt->shop->number ?? '' }}
                        </span>
                    @elseif($receipt->booth_id)
                        <span class="badge-booth">
                            غرفه {{ $receipt->booth->number ?? '' }}
                        </span>
                    @else
                        —
                    @endif
                </td>

                <td>{{ number_format($receipt->amount) }}</td>

                <td>
                    @php
                        $currency = match($receipt->currency) {
                            'AFN' => 'افغانی',
                            'USD' => 'دالر',
                            'EUR' => 'یورو',
                            'IRR' => 'ریال',
                            default => $receipt->currency,
                        };
                    @endphp

                    {{ $currency }}
                </td>

                <td>
                    {{ \Morilog\Jalali\Jalalian::fromDateTime($receipt->date)->format('Y/m/d') }}
                </td>
            </tr>
            @endforeach
        </tbody>

    </table>

</div>

</body>
</html>