<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
<meta charset="UTF-8">
<title>رسید پرداخت</title>

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
        <h2>مجتمع تجارتی عادلیار</h2>
        <p>رسید پرداخت - {{ $depositLog->expanses_type }}</p>
    </div>

    {{-- INFO --}}
    <table class="table">
        <tr>
            <th>مارکت</th>
            <th>شماره دوکان</th>
            <th>دوکاندار</th>
            <th>نوع هزینه</th>
        </tr>
        <tr>
            <td>{{ $depositLog->market->name ?? '-' }}</td>
            <td>{{ $depositLog->shop->number ?? '-' }}</td>
            <td>{{ $depositLog->shopkeeper->fullname ?? '-' }}</td>
            <td>{{ $depositLog->expanses_type }}</td>
        </tr>
    </table>

    {{-- 💡 CLEAN FINANCIAL LOGIC --}}
    @php
        $price = $depositLog->deposit->price ?? 0;

        $oldPaid = $depositLog->old_paid ?? 0;
        $newPaid = $depositLog->new_paid ?? 0;

        $oldRemaining = max($price - $oldPaid, 0);
        $newRemaining = max($price - $newPaid, 0);
    @endphp

    {{-- AMOUNT --}}
    <table class="amount">

        <tr>
            <td class="label">کل بدهی</td>
            <td class="value">{{ number_format($price) }}</td>
        </tr>

        <tr>
            <td class="label">پرداخت فعلی</td>
            <td class="value">{{ number_format($newPaid) }}</td>
        </tr>

        <tr>
            <td class="label">باقی‌مانده فعلی</td>
            <td class="value">{{ number_format($newRemaining) }}</td>
        </tr>

    </table>

</div>
</body>

</html>