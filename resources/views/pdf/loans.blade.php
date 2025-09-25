<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>گزارش قرضه‌ها</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; direction: rtl; text-align: right; }
        h2 { text-align: center; }
        table { width: 100%; border-collapse: collapse; font-size: 12px; margin-top: 15px; }
        th, td { border: 1px solid #444; padding: 6px; }
        th { background: #eee; }
    </style>
</head>
<body>
    <h2>💳 گزارش قرضه‌ها</h2>

  <table style="width: 100%; border-collapse: collapse; font-size: 11px;">
    <thead>
        <tr>
            <th>#</th>
            <th>تاریخ</th>
            <th>نوع</th>
            <th>ارز</th>
            <th>مشتری</th>
            <th>مبلغ</th>
            <th>رسید</th>
            <th>باقی‌مانده</th>
            <th>ثبت شده</th>
        </tr>
    </thead>
    <tbody>
        @forelse($loans as $index => $loan)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $loan['date'] }}</td>
                <td>{{ $loan['type'] }}</td>
                <td>{{ $loan['currency'] }}</td>
                <td>{{ $loan['customer_name'] }}</td>
                <td style="color:blue; font-weight:bold">{{ $loan['amount'] }}</td>
                <td style="color:green; font-weight:bold">{{ $loan['loan_recipt'] }}</td>
                <td style="color:red; font-weight:bold">{{ $loan['reminded'] }}</td>
                <td>{{ $loan['created_at'] }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="9" style="text-align:center">هیچ قرضه‌ای ثبت نشده است.</td>
            </tr>
        @endforelse
    </tbody>
</table>

</body>
</html>
