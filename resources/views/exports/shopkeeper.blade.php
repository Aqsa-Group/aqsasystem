<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8" />
    <style>
        body {
            font-family: 'scheherazade', serif;
            direction: rtl;
            text-align: right;
            font-size: 14pt;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <h2>معلومات دوکاندار</h2>
    <table>
        <tr><th>نام و نام فامیلی</th><td>{{ $shopkeeper->fullname }}</td></tr>
        <tr><th>نام پدر</th><td>{{ $shopkeeper->father_name }}</td></tr>
        <tr><th>ایمیل</th><td>{{ $shopkeeper->email ?? '-' }}</td></tr>
        <tr><th>شماره تلفن</th><td>{{ $shopkeeper->phone }}</td></tr>
        <tr><th>نمبر تذکره</th><td>{{ $shopkeeper->national_id }}</td></tr>
        <tr><th>تاریخ شروع قرارداد</th><td>{{ $shopkeeper->contract_start }}</td></tr>
        <tr><th>تاریخ ختم قرارداد</th><td>{{ $shopkeeper->contract_end }}</td></tr>
        <tr><th>وضعیت</th><td>{{ $shopkeeper->state }}</td></tr>
    </table>

    <h2>لیست دوکان‌ها</h2>
    <table>
        <thead>
            <tr>
                <th>مارکت</th>
                <th>شماره دوکان</th>
                <th>منزل</th>
                <th>اندازه</th>
                <th>شماره میتر</th>
                <th>نوعیت</th>
                <th>قیمت</th>
            </tr>
        </thead>
        <tbody>
            @foreach($shopkeeper->shops as $shop)
                <tr>
                    <td>{{ $shop->market->name ?? '-' }}</td>
                    <td>{{ $shop->number }}</td>
                    <td>{{ $shop->floor }}</td>
                    <td>{{ $shop->size }}</td>
                    <td>{{ $shop->metar_serial }}</td>
                    <td>{{ $shop->type }}</td>
                    <td>{{ number_format($shop->price) }} افغانی</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
