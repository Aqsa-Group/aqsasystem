<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
<meta charset="UTF-8">
<title>رسید دوکاندار</title>

<style>
body {
    font-family: vazir, sans-serif;
    font-size: 13px;
    background: #fff;
    color: #111;
    margin: 0;
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
    margin: 3px 0;
    font-weight: bold;
}

/* TABLE INFO */
.table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 10px;
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

/* AMOUNT SECTION */
.amount {
    width: 100%;
    border-collapse: collapse;
}

.amount td {
    border: 1px solid #444;
    padding: 8px;
    font-weight: bold;
}

.label {
    background: #095264;
    color: #fff;
    text-align: right;
}

.value {
    text-align: center;
}
</style>

</head>

<body>
<div class="page">

    {{-- HEADER --}}
    <div class="header">
        <h2>رسید دوکاندار</h2>
        <p>شماره رسید: {{ $record->id }}</p>
    </div>

    {{-- INFO --}}
    <table class="table">
        <tr>
            <th>مارکت</th>
            <th>شماره دوکان</th>
            <th>دوکاندار</th>
            <th>نوع مصرف</th>
        </tr>
        <tr>
            <td>{{ $record->market->name ?? '-' }}</td>
            <td>{{ $record->shop->number ?? '-' }}</td>
            <td>{{ $record->shopkeeper->fullname ?? '-' }}</td>
            <td>{{ $record->expanses_type }}</td>
        </tr>
    </table>

    {{-- AMOUNT --}}
    @php
        $amount = $record->amount ?? 0;

        $currency = match($record->currency) {
            'AFN' => 'افغانی',
            'USD' => 'دالر',
            'EUR' => 'یورو',
            'IRR' => 'ریال',
            default => $record->currency,
        };
    @endphp

    <table class="amount">

        <tr>
            <td class="label">مبلغ</td>
            <td class="value">{{ number_format($amount) }} {{ $currency }}</td>
        </tr>

        <tr>
            <td class="label">تاریخ</td>
            <td class="value">
                {{ \Morilog\Jalali\Jalalian::fromDateTime($record->date)->format('Y/m/d') }}
            </td>
        </tr>

        @if($record->description)
        <tr>
            <td class="label">توضیحات</td>
            <td class="value">{{ $record->description }}</td>
        </tr>
        @endif

    </table>

</div>
</body>

</html>