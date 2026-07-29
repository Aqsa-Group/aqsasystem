<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>گزارش خرید از شرکت‌ها</title>
    <style>
        body {
            font-family: 'vazir', sans-serif;
            font-size: 11px;
            color: #000;
            margin: 0;
            padding: 0;
        }
        .invoice {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            padding-bottom: 5px;
            border-bottom: 2px solid #000;
            margin-bottom: 10px;
        }
        .invoice-sub {
            font-size: 12px;
            font-weight: normal;
            margin-top: 3px;
        }
        .header-info {
            width: 100%;
            margin-bottom: 10px;
            font-size: 12px;
        }
        .header-info td {
            padding: 3px 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #000;
        }
        table th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
            padding: 6px 4px;
            border: 1px solid #000;
        }
        table td {
            padding: 4px 6px;
            border: 1px solid #000;
            text-align: center;
        }
        .total-row {
            font-weight: bold;
            background-color: #f9f9f9;
        }
        .grand-total {
            margin-top: 15px;
            border: 2px solid #000;
            padding: 8px;
            background-color: #f1f1f1;
            font-weight: bold;
            font-size: 14px;
            display: flex;
            justify-content: space-around;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 10px;
            border-top: 1px solid #ccc;
            padding-top: 8px;
            color: #555;
        }
        .company-name {
            font-size: 16px;
            font-weight: bold;
        }
        .company-address {
            font-size: 11px;
            color: #333;
        }
        .filter-info {
            font-size: 12px;
            margin-bottom: 10px;
            text-align: center;
        }
    </style>
</head>
<body>

    <!-- هدر فاکتور -->
    <div class="invoice">
        پرزه فروشی انجینیر محمد هارون صافی و مرادی
        <div class="invoice-sub">
            وارد کننده هر نوع پرزه جات ریکشا هندی از قبیل بجاج TVS از کشور های هند و چین
        </div>
    </div>

    <!-- اطلاعات فیلتر -->
    <div class="filter-info">
        @if(!empty($fromDate) && !empty($toDate))
            بازه: {{ $fromDate }} تا {{ $toDate }}
        @elseif(!empty($fromDate))
            از تاریخ: {{ $fromDate }}
        @elseif(!empty($toDate))
            تا تاریخ: {{ $toDate }}
        @else
            تمام تاریخ‌ها
        @endif
    </div>

    <!-- جدول اصلی -->
    @forelse($reportData as $group)
        <div style="margin-top:15px;">
            <div style="font-weight:bold; font-size:14px; padding:5px 0; border-bottom:1px solid #000; margin-bottom:5px;">
                شرکت: {{ $group['company']['name'] ?? 'بدون نام' }}
            </div>

            <table>
                <thead>
                    <tr>
                        <th style="width:8%;">شماره</th>
                        <th style="width:22%;">نام کالا</th>
                        <th style="width:15%;">قیمت کل</th>
                        <th style="width:10%;">تعداد</th>
                        <th style="width:10%;">واحد</th>
                        <th style="width:15%;">پرداختی</th>
                        <th style="width:20%;">بدهی</th>
                    </tr>
                </thead>
                <tbody>
                    @php $rowNum = 1; @endphp
                    @foreach($group['items'] as $buy)
                        <tr>
                            <td>{{ $rowNum++ }}</td>
                            <td>{{ $buy->name }}</td>
                            <td>{{ number_format($buy->total_price, 2) }}</td>
                            <td>{{ $buy->all_exist_number ?? 0 }}</td>
                            <td>{{ $buy->unit ?? 'عدد' }}</td>
                            <td>{{ number_format($buy->paid, 2) }}</td>
                            <td>{{ number_format($buy->remaining, 2) }}</td>
                        </tr>
                    @endforeach

                    <!-- ردیف‌های خالی برای پر کردن تا ۱۰ ردیف (ظاهر فاکتور) -->
                    @for ($i = count($group['items']) + 1; $i <= 10; $i++)
                        <tr>
                            <td>{{ $i }}</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    @endfor
                </tbody>
                <tfoot>
                    <tr class="total-row">
                        <td colspan="2" style="text-align:left;">جمع کل شرکت</td>
                        <td>{{ number_format($group['totals']['total_price'], 2) }}</td>
                        <td></td>
                        <td></td>
                        <td>{{ number_format($group['totals']['paid'], 2) }}</td>
                        <td>{{ number_format($group['totals']['remaining'], 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    @empty
        <p style="text-align:center; color:#999;">هیچ خریدی یافت نشد.</p>
    @endforelse

    <!-- جمع کل همه شرکت‌ها -->
    @if(!empty($reportData))
        <div class="grand-total">
            <span>جمع کل: {{ number_format($totalAll['total_price'], 2) }}</span>
            <span>کل پرداختی: {{ number_format($totalAll['paid'], 2) }}</span>
            <span>کل بدهی: {{ number_format($totalAll['remaining'], 2) }}</span>
        </div>
    @endif

    <!-- فوتر -->
    <div class="footer">
        <div>آدرس: کابل - چهار راهی ماموریت ، بلاک دوم ، گذشته از پوهنتون مستقبل ، متصل نمایندگی بجاج</div>
        <div>شماره‌های تماس: 0796471633 - 0700472377 - 0786165140 - 0779434678</div>
        <div style="margin-top:5px;">تاریخ چاپ: {{ now()->format('Y/m-d H:i') }}</div>
    </div>

</body>
</html>