<!DOCTYPE html>
<html dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>چاپ سند حسابداری</title>
    <style>
        @media print {
            @page {
                size: A4 landscape;
                margin: 0;
            }
            .no-print {
                display: none !important;
            }
        }
        
        body {
            font-family: 'B Nazanin', 'IranNastaliq', Tahoma, sans-serif;
            padding: 20mm;
            line-height: 1.6;
        }
        
        .header {
            text-align: center;
            margin-bottom: 10mm;
            border-bottom: 2px solid #000;
            padding-bottom: 5mm;
        }
        
        .company-name {
            font-size: 18pt;
            font-weight: bold;
        }
        
        .document-title {
            font-size: 16pt;
            margin-top: 3mm;
        }
        
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10mm;
        }
        
        .info-table td {
            padding: 2mm;
            border: 1px solid #000;
        }
        
        .info-table .label {
            font-weight: bold;
            width: 30%;
            background: #f5f5f5;
        }
        
        .no-print {
            position: fixed;
            top: 10px;
            left: 10px;
            z-index: 1000;
        }
        
        .print-btn {
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin: 5px;
        }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <div class="company-name">مجتمع تجاری عادل</div>
        <div class="document-title">سند حسابداری - {{ $accounting->expanses_type }}</div>
    </div>
    
    <table class="info-table">
        <tr>
            <td class="label">نوع هزینه:</td>
            <td>{{ $accounting->expanses_type }}</td>
            <td class="label">مارکت:</td>
            <td>{{ $accounting->market->name ?? '---' }}</td>
        </tr>
        <tr>
            <td class="label">مشترک:</td>
            <td>{{ $accounting->shopkeeper->name ?? '---' }}</td>
            <td class="label">شماره دوکان:</td>
            <td>{{ $accounting->shop->name ?? $accounting->booth->name ?? '---' }}</td>
        </tr>
        <tr>
            <td class="label">مبلغ:</td>
            <td>{{ number_format($accounting->amount) }} افغانی</td>
            <td class="label">تاریخ:</td>
            <td>{{ verta($accounting->created_at)->format('Y/m/d') }}</td>
        </tr>
        <tr>
            <td class="label">توضیحات:</td>
            <td colspan="3">{{ $accounting->description ?? '---' }}</td>
        </tr>
    </table>
    
    <div style="margin-top: 20mm; display: flex; justify-content: space-between;">
        <div style="text-align: center;">
            <div>امضاء مسئول</div>
            <div style="margin-top: 15mm;">________________</div>
        </div>
        <div style="text-align: center;">
            <div>مهر و امضاء مشترک</div>
            <div style="margin-top: 15mm;">________________</div>
        </div>
    </div>

    <div class="no-print">
        <button class="print-btn" onclick="window.print()">🖨️ چاپ</button>
        <button class="print-btn" onclick="window.close()" style="background:#dc3545;">✕ بستن</button>
    </div>

    <script>
        window.onload = function() {
            setTimeout(() => {
                window.print();
            }, 500);
        }
    </script>
</body>
</html>