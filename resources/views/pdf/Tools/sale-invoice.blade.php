<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>فاکتور فروش</title>
    <style>
        @font-face {
            font-family: 'Vazir';
            src: url('https://cdn.jsdelivr.net/gh/rastikerdar/vazir-font@v30.1.0/dist/Vazir.woff2') format('woff2'),
                 url('https://cdn.jsdelivr.net/gh/rastikerdar/vazir-font@v30.1.0/dist/Vazir.woff') format('woff');
            font-weight: normal;
            font-style: normal;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Vazir', 'Segoe UI', Tahoma, sans-serif;
        }
        
        body {
            background: #f8fafc;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            line-height: 1.6;
        }
        
        .container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            width: 100%;
            max-width: 700px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }
        
        .header {
            background: #1e293b;
            color: white;
            padding: 24px;
            text-align: center;
            border-bottom: 1px solid #334155;
        }
        
        .header h1 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 8px;
        }
        
        .header p {
            font-size: 0.9rem;
            opacity: 0.8;
        }
        
        .info-section {
            padding: 24px;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }
        
        .info-item {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        
        .info-label {
            font-size: 0.8rem;
            color: #64748b;
            font-weight: 500;
        }
        
        .info-value {
            font-size: 0.9rem;
            color: #1e293b;
            font-weight: 600;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .items-table thead {
            background: #f8fafc;
            border-bottom: 2px solid #e2e8f0;
        }
        
        .items-table th {
            padding: 16px 12px;
            text-align: center;
            font-weight: 600;
            color: #475569;
            font-size: 0.85rem;
        }
        
        .items-table td {
            padding: 14px 12px;
            text-align: center;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.85rem;
            color: #334155;
        }
        
        .items-table tbody tr:last-child td {
            border-bottom: none;
        }
        
        .total-section {
            padding: 24px;
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
        }
        
        .total-grid {
            display: grid;
            gap: 12px;
            max-width: 300px;
            margin-right: auto;
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
        }
        
        .total-label {
            font-size: 0.9rem;
            color: #475569;
            font-weight: 500;
        }
        
        .total-value {
            font-size: 0.9rem;
            color: #1e293b;
            font-weight: 600;
        }
        
        .remaining {
            color: #dc2626;
        }
        
        .paid {
            color: #059669;
        }
        
        .footer {
            text-align: center;
            padding: 16px;
            background: #f1f5f9;
            color: #64748b;
            font-size: 0.8rem;
            border-top: 1px solid #e2e8f0;
        }
        
        .type-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        
        .retail {
            background: #dbeafe;
            color: #1e40af;
        }
        
        .wholesale {
            background: #fef3c7;
            color: #92400e;
        }
        
        @media print {
            body {
                background: white !important;
                padding: 0 !important;
            }
            
            .container {
                box-shadow: none !important;
                border: none !important;
                border-radius: 0 !important;
                max-width: none !important;
            }
            
            .header {
                background: #1e293b !important;
                -webkit-print-color-adjust: exact;
            }
        }
        
        @media (max-width: 640px) {
            .info-grid {
                grid-template-columns: 1fr;
                gap: 12px;
            }
            
            .items-table {
                font-size: 0.8rem;
            }
            
            .items-table th,
            .items-table td {
                padding: 12px 8px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>فاکتور فروش</h1>
            <p>شماره: {{ $sale->invoice_number }}</p>
        </div>
        
        <div class="info-section">
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">تاریخ</span>
                    <span class="info-value">{{ \Morilog\Jalali\Jalalian::fromDateTime($sale->created_at)->format('Y/m/d H:i') }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">نوع فروش</span>
                    <span class="info-value">
                        <span class="type-badge {{ $sale->sale_type === 'retail' ? 'retail' : 'wholesale' }}">
                            {{ $sale->sale_type === 'retail' ? 'پرچون' : 'عمده' }}
                        </span>
                    </span>
                </div>
                @if($sale->customer)
                <div class="info-item">
                    <span class="info-label">مشتری</span>
                    <span class="info-value">{{ $sale->customer->fullname }}</span>
                </div>
                @if($sale->customer->phone)
                <div class="info-item">
                    <span class="info-label">تلفن</span>
                    <span class="info-value">{{ $sale->customer->phone }}</span>
                </div>
                @endif
                @endif
            </div>
        </div>

        <table class="items-table">
            <thead>
                <tr>
                    <th>ردیف</th>
                    <th>محصول</th>
                    <th>تعداد</th>
                    <th>قیمت واحد</th>
                    <th>مبلغ کل</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sale->saleItems as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->warehouse->product_name }}</td>
                    <td>{{ number_format($item->quantity) }}</td>
                    <td>{{ number_format($item->price_per_unit) }}</td>
                    <td>{{ number_format($item->total_price) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total-section">
            <div class="total-grid">
                <div class="total-row">
                    <span class="total-label">مبلغ کل:</span>
                    <span class="total-value">{{ number_format($sale->total_price) }} افغانی</span>
                </div>
                
                @if($sale->discount > 0)
                <div class="total-row">
                    <span class="total-label">تخفیف:</span>
                    <span class="total-value">- {{ number_format($sale->discount) }} افغانی</span>
                </div>
                @endif
                
                <div class="total-row">
                    <span class="total-label">پرداختی:</span>
                    <span class="total-value paid">{{ number_format($sale->received_amount) }} افغانی</span>
                </div>
                
                @if($sale->remaining_amount > 0)
                <div class="total-row">
                    <span class="total-label">باقی مانده:</span>
                    <span class="total-value remaining">{{ number_format($sale->remaining_amount) }} افغانی</span>
                </div>
                @else
                <div class="total-row">
                    <span class="total-label">وضعیت:</span>
                    <span class="total-value paid">تکمیل شده</span>
                </div>
                @endif
            </div>
        </div>
        
        <div class="footer">
            <p>با تشکر از خرید شما - این فاکتور به صورت خودکار تولید شده است</p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                window.print();
            }, 500);
        });
    </script>
</body>
</html>