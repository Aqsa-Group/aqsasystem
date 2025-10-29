<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>فاکتور فروش</title>
    <style>
        body {
            font-family: 'Vazir', Tahoma, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            border: 2px solid #000;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 20px;
        }
        .company-info {
            margin-bottom: 20px;
        }
        .invoice-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .table th, .table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
        }
        .table th {
            background-color: #f5f5f5;
        }
        .summary {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .total {
            font-size: 18px;
            font-weight: bold;
            text-align: left;
        }
        .footer {
            text-align: center;
            margin-top: 50px;
            border-top: 1px solid #000;
            padding-top: 20px;
        }
        .barcode {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="header">
            <h1>فاکتور فروش</h1>
            <div class="company-info">
                <h2>نام شرکت/مغازه</h2>
                <p>آدرس: ...</p>
                <p>تلفن: ...</p>
            </div>
        </div>

        <div class="invoice-info">
            <div>
                <p><strong>شماره فاکتور:</strong> {{ $sale->sale_number }}</p>
                <p><strong>تاریخ:</strong> {{ $sale->created_at->format('Y/m/d') }}</p>
                <p><strong>زمان:</strong> {{ $sale->created_at->format('H:i') }}</p>
            </div>
            <div>
                @if($sale->customer)
                <p><strong>مشتری:</strong> {{ $sale->customer->fullname }}</p>
                @endif
                <p><strong>نوع فروش:</strong> {{ $sale->sale_type === 'wholesale' ? 'عمده' : 'پرچون' }}</p>
                <p><strong>صندوق دار:</strong> {{ $sale->user->name ?? 'سیستم' }}</p>
            </div>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>ردیف</th>
                    <th>نام کالا</th>
                    <th>تعداد</th>
                    <th>قیمت واحد (AFN)</th>
                    <th>مبلغ (AFN)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sale->items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->product->product_name ?? 'نامشخص' }}</td>
                    <td>{{ number_format($item->quantity) }}</td>
                    <td>{{ number_format($item->unit_price, 2) }}</td>
                    <td>{{ number_format($item->total_price, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="summary">
            <div>
                <p><strong>توضیحات:</strong></p>
                <p>{{ $sale->notes ?? '---' }}</p>
            </div>
            <div class="total">
                <p>جمع کل: {{ number_format($sale->total_amount, 2) }} AFN</p>
                <p>تخفیف: {{ number_format($sale->discount, 2) }} AFN</p>
                <p>مبلغ نهایی: {{ number_format($sale->final_amount, 2) }} AFN</p>
                <p>پرداخت شده: {{ number_format($sale->paid_amount, 2) }} AFN</p>
                @if($sale->remaining_amount > 0)
                <p>باقیمانده: {{ number_format($sale->remaining_amount, 2) }} AFN</p>
                @endif
            </div>
        </div>

        <div class="footer">
            <p>با تشکر از خرید شما</p>
            <p>تلفن پشتیبانی: ...</p>
            <div class="barcode">
                <!-- کد بارکد شماره فاکتور -->
                <p>{{ $sale->sale_number }}</p>
            </div>
        </div>
    </div>
</body>
</html>