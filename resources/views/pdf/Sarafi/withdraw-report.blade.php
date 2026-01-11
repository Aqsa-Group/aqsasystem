<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>گزارش کلی برداشت‌ها</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            direction: rtl;
            margin: 0;
            padding: 0;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #2B65E5;
            padding-bottom: 10px;
        }
        
        .header h1 {
            color: #2B65E5;
            margin: 0;
            font-size: 18px;
        }
        
        .header p {
            margin: 5px 0;
            color: #666;
        }
        
        .filters {
            background: #f5f5f5;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 11px;
        }
        
        .filters table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .filters td {
            padding: 3px 5px;
            border: 1px solid #ddd;
        }
        
        .filters .label {
            background: #e9e9e9;
            font-weight: bold;
            width: 25%;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
            font-size: 10px;
        }
        
        th {
            background-color: #2B65E5;
            color: white;
            padding: 8px;
            text-align: center;
            font-weight: bold;
        }
        
        td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: center;
        }
        
        .main-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .summary-table {
            margin-top: 30px;
        }
        
        .summary-table th {
            background-color: #4CAF50;
        }
        
        .text-red {
            color: #f44336;
        }
        
        .text-green {
            color: #4CAF50;
        }
        
        .total-row {
            font-weight: bold;
            background-color: #f0f0f0;
        }
        
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 9px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        
        .no-data {
            text-align: center;
            padding: 20px;
            color: #666;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>گزارش کلی برداشت‌ها</h1>
        <p>سیستم مدیریت صرافی</p>
        <p>تاریخ گزارش: {{ $reportDate }}</p>
    </div>

    @if(!empty($staffName) || !empty($filters['expanses_type']) || !empty($filters['currency']) || !empty($filters['fromDate']) || !empty($filters['toDate']))
    <div class="filters">
        <h3 style="margin-top: 0; margin-bottom: 10px; color: #333;">فیلترهای اعمال شده</h3>
        <table>
            <tr>
                @if(!empty($staffName))
                <td class="label">کارمند</td>
                <td>{{ $staffName }}</td>
                @endif
                
                @if(!empty($filters['expanses_type']))
                <td class="label">نوع هزینه</td>
                <td>{{ $filters['expanses_type'] }}</td>
                @endif
            </tr>
            <tr>
                @if(!empty($filters['currency']))
                <td class="label">ارز</td>
                <td>{{ $currencies[$filters['currency']] ?? $filters['currency'] }}</td>
                @endif
                
                @if(!empty($filters['fromDate']) || !empty($filters['toDate']))
                <td class="label">بازه زمانی</td>
                <td>
                    {{ $filters['fromDate'] ? str_replace('-', '/', $filters['fromDate']) : 'از ابتدا' }} 
                    تا 
                    {{ $filters['toDate'] ? str_replace('-', '/', $filters['toDate']) : 'تا کنون' }}
                </td>
                @endif
            </tr>
        </table>
    </div>
    @endif

    <!-- جدول اصلی برداشت‌ها -->
    <h3 style="color: #333; margin-bottom: 10px; border-bottom: 1px solid #ddd; padding-bottom: 5px;">لیست برداشت‌ها</h3>
    
    @if($transactions->count() > 0)
    <table class="main-table">
        <thead>
            <tr>
                <th width="5%">#</th>
                <th width="20%">کارمند</th>
                <th width="15%">نوع هزینه</th>
                <th width="15%">مقدار</th>
                <th width="10%">ارز</th>
                <th width="20%">توضیحات</th>
                <th width="15%">تاریخ</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $index => $transaction)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>
                    {{ $transaction->staff ? $transaction->staff->name : 'نامشخص' }}
                </td>
                <td>{{ $transaction->expanses_type ?? '-' }}</td>
                <td class="text-red">{{ number_format($transaction->amount, 2) }}</td>
                <td>{{ $currencies[$transaction->currency] ?? $transaction->currency }}</td>
                <td>{{ $transaction->description ?? '-' }}</td>
                <td>
                      {{ explode(' ', $transaction->date)[0] }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="no-data">
        هیچ برداشتی در بازه زمانی انتخاب شده یافت نشد.
    </div>
    @endif

    <!-- جدول خلاصه -->
    @if($summary->count() > 0)
    <div class="summary-table">
        <h3 style="color: #333; margin-bottom: 10px; border-bottom: 1px solid #ddd; padding-bottom: 5px;">خلاصه گزارش بر اساس ارز</h3>
        
        <table>
            <thead>
                <tr>
                    <th width="5%">#</th>
                    <th width="25%">ارز</th>
                    <th width="25%">مجموع برداشت</th>
                </tr>
            </thead>
            <tbody>
                @foreach($summary as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->currency_fa }}</td>
                    <td class="text-red">{{ number_format($item->total_amount, 2) }}</td>
                </tr>
                @endforeach
                
                <!-- جمع کل -->
               
            </tbody>
        </table>
    </div>
    @endif

   
</body>
</html>