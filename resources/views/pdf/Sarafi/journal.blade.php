<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>گزارش معاملات روزانه</title>
    <style>
        @page {
            margin-top: 20mm;
            margin-bottom: 20mm;
            margin-left: 10mm;
            margin-right: 10mm;
        }

        @page :first {
            margin-top: 15mm;
        }

        body {
            font-family: dejavusans, sans-serif;
            font-size: 10pt;
            line-height: 1.5;
            color: #000;
            margin: 0;
            padding: 0;
            direction: rtl;
            text-align: right;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #2B65E5;
        }

        .header h1 {
            color: #2B65E5;
            font-size: 16pt;
            margin: 0 0 5px 0;
        }

        .header p {
            margin: 0;
            font-size: 9pt;
            color: #666;
        }

        .filters-section {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            border: 1px solid #dee2e6;
            font-size: 9pt;
        }

        .filters-section h3 {
            margin-top: 0;
            margin-bottom: 10px;
            font-size: 10pt;
        }

        .filter-row {
            display: table;
            width: 100%;
            margin-bottom: 5px;
        }

        .filter-item {
            display: table-row;
        }

        .filter-label {
            font-weight: bold;
            display: table-cell;
            padding: 2px 5px;
            width: 120px;
            vertical-align: top;
        }

        .filter-value {
            display: table-cell;
            padding: 2px 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
            font-size: 9pt;
            page-break-inside: auto;
        }

        th {
            background-color: #2B65E5;
            color: white;
            padding: 8px 5px;
            border: 1px solid #ddd;
            font-weight: bold;
            text-align: center;
            font-size: 9pt;
        }

        td {
            padding: 6px 5px;
            border: 1px solid #ddd;
            text-align: center;
            font-size: 8pt;
        }

        .transaction-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .text-green {
            color: #008000;
        }

        .text-red {
            color: #ff0000;
        }

        .text-blue {
            color: #0000ff;
        }

        .text-purple {
            color: #800080;
        }

        .summary-table {
            margin-top: 20px;
        }

        .summary-table th {
            background-color: #4f46e5;
        }

        .balance-table th {
            background-color: #059669;
        }

        .total-row {
            font-weight: bold;
            background-color: #e9ecef;
        }

        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #dee2e6;
            text-align: center;
            font-size: 8pt;
            color: #666;
        }

        .page-break {
            page-break-before: always;
        }

        .no-data {
            text-align: center;
            padding: 20px;
            color: #666;
            font-style: italic;
        }

        .customer-info {
            margin: 5px 0;
        }

        .transaction-date {
            font-size: 8pt;
            color: #666;
        }

        /* برای جلوگیری از شکستن ردیف‌ها در صفحات */
        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }

        thead {
            display: table-header-group;
        }

        tfoot {
            display: table-footer-group;
        }

        .summary-card {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 10px;
            background: #f8f9fa;
        }

        .summary-title {
            font-weight: bold;
            margin-bottom: 10px;
            color: #2B65E5;
            font-size: 11pt;
        }

        /* استایل برای چاپ */
        @media print {
            .no-print {
                display: none !important;
            }

            body {
                font-size: 9pt;
            }

            table {
                font-size: 8pt;
            }

            th,
            td {
                padding: 4px 3px;
            }
        }
    </style>
</head>

<body>

    <div class="header">
        <h1>گزارش معاملات روزانه حسابات و صندوق‌ها</h1>
        <p>تاریخ تولید: {{ now()->format('Y/m/d H:i') }}</p>
    </div>

    <!-- نمایش فیلترها اگر وجود داشته باشند -->
    @if(isset($filters) && (isset($filters['transactionType']) || isset($filters['accountType']) || isset($filters['currency']) || isset($filters['fromDate']) || isset($filters['toDate'])))
        <div class="filters-section">
            <h3>فیلترهای اعمال شده</h3>
            <div class="filter-row">
                @if(isset($filters['transactionType']) && $filters['transactionType'])
                    <div class="filter-item">
                        <span class="filter-label">نوع تراکنش:</span>
                        <span class="filter-value">{{ $filters['transactionType'] }}</span>
                    </div>
                @endif
                @if(isset($filters['accountType']) && $filters['accountType'])
                    <div class="filter-item">
                        <span class="filter-label">نوع حساب:</span>
                        <span class="filter-value">{{ $filters['accountType'] }}</span>
                    </div>
                @endif
                @if(isset($filters['currency']) && $filters['currency'])
                    <div class="filter-item">
                        <span class="filter-label">ارز:</span>
                        <span class="filter-value">{{ $filters['currency'] }}</span>
                    </div>
                @endif
                @if(isset($filters['fromDate']) && $filters['fromDate'])
                    <div class="filter-item">
                        <span class="filter-label">از تاریخ:</span>
                        <span class="filter-value">{{ \Morilog\Jalali\Jalalian::fromFormat('Y-m-d', $filters['fromDate'])->format('Y/m/d') }}</span>
                    </div>
                @endif
                @if(isset($filters['toDate']) && $filters['toDate'])
                    <div class="filter-item">
                        <span class="filter-label">تا تاریخ:</span>
                        <span class="filter-value">{{ \Morilog\Jalali\Jalalian::fromFormat('Y-m-d', $filters['toDate'])->format('Y/m/d') }}</span>
                    </div>
                @endif
            </div>
        </div>
    @endif

    @if ($transactions->count() > 0)
        <h3 style="margin-top: 20px; margin-bottom: 10px; font-size: 11pt;">لیست تراکنش‌ها ({{ $transactions->count() }}
            رکورد)</h3>
        <table class="transaction-table">
            <thead>
                <tr>
                    <th width="30">ردیف</th>
                    <th width="120">نام حساب</th>
                    <th width="70">نوع معامله</th>
                    <th width="70">نوع حساب</th>
                    <th width="80">مقدار</th>
                    <th width="50">ارز</th>
                    <th width="80">بیلانس فعلی</th>
                    <th width="150">توضیحات</th>
                    <th width="90">تاریخ</th>
                </tr>
            </thead>
            <tbody>

                @php
                    $currenciesFa = [
                        'afn' => 'افغانی',
                        'usd' => 'دالر',
                        'eur' => 'یورو',
                        'irr' => 'تومان',
                        'aed' => 'درهم',
                        'try' => 'لیره',
                        'cny' => 'یوان',
                        'pkr' => 'کلدار',
                        'gbp' => 'پوند',
                        'jpy' => 'ین',
                        'sar' => 'ریال سعودی',
                        'inr' => 'روپیه',
                    ];
                @endphp

                @foreach ($transactions as $index => $transaction)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            @if (empty($transaction->customer_id) && !empty($transaction->withdraw_id))
                                <div style="font-weight: bold;">برداشت</div>
                            @elseif (empty($transaction->customer_id) && $transaction->is_sell_table == 1)
                                <div style="font-weight: bold;">معامله از صندوق</div>
                            @else
                                <div style="font-weight: bold;">
                                    {{ $transaction->customer->fullname ?? 'نامشخص' }}
                                </div>
                                <div style="font-size: 7pt; color: #666;">
                                    {{ $transaction->customer->account_number ?? '' }}
                                </div>
                            @endif
                        </td>

                        <td>{{ $transaction->type }}</td>
                        <td>{{ $transaction->account_type }}</td>
                        <td class="{{ $transaction->type == 'رسید' ? 'text-green' : 'text-red' }}">
                            {{ number_format($transaction->amount, 2) }}
                        </td>
                        <td>
                            {{ $currenciesFa[strtolower($transaction->currency)] ?? $transaction->currency }}
                        </td>
                        <td dir="ltr">{{ number_format($transaction->balance, 2) }}</td>
                        <td style="text-align: right; padding: 0 5px;">{{ $transaction->description }}</td>
                        <td>
                            <div>{{ explode(' ', $transaction->date)[0] }}</div>
                            <div class="transaction-date">{{ $transaction->created_at->format('H:i') }}</div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-data">
            هیچ تراکنشی برای نمایش وجود ندارد
        </div>
    @endif

    @if (isset($summary) && $summary->count() > 0)
        <div class="page-break"></div>
        <h3 style="margin-top: 20px; margin-bottom: 10px; font-size: 11pt;">خلاصه گزارش به تفکیک ارز</h3>
        <table class="summary-table">
            <thead>
                <tr>
                    <th width="30">ردیف</th>
                    <th width="60">ارز</th>
                    <th width="80">رسید نقدی</th>
                    <th width="80">برد نقدی</th>
                    <th width="80">رسید بانکی</th>
                    <th width="80">برد بانکی</th>
                    <th width="80">باقی نقدی</th>
                    <th width="80">باقی بانکی</th>
                    <!-- اضافه کردن ستون‌های موجودی صندوق و بانک -->
                    <th width="80">موجودی صندوق</th>
                    <th width="80">موجودی بانک</th>
                    <th width="80">مجموعه کل ارز</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($summary as $index => $item)
                    @php
                        $currencyCode = strtolower($item->currency);
                        $safeBalance = $currencySafeBalance[$currencyCode] ?? 0;
                        $bankBalance = $bankAccountBalance[$currencyCode] ?? 0;
                        $totalCurrency = $totalBalanceByCurrency[$currencyCode] ?? 0;
                    @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $item->currency }}</strong></td>
                        <td dir="ltr" class="text-green">{{ number_format($item->receipt_cash, 2) }}</td>
                        <td dir="ltr" class="text-red">{{ number_format($item->withdrawal_cash, 2) }}</td>
                        <td dir="ltr" class="text-green">{{ number_format($item->receipt_bank, 2) }}</td>
                        <td dir="ltr" class="text-red">{{ number_format($item->withdrawal_bank, 2) }}</td>
                        <td dir="ltr" class="{{ $item->balance_cash >= 0 ? 'text-green' : 'text-red' }}">
                            <strong>{{ number_format($item->balance_cash, 2) }}</strong>
                        </td>
                        <td dir="ltr" class="{{ $item->balance_bank >= 0 ? 'text-green' : 'text-red' }}">
                            <strong>{{ number_format($item->balance_bank, 2) }}</strong>
                        </td>
                        <!-- موجودی صندوق -->
                        <td dir="ltr" class="text-blue">
                            {{ number_format($safeBalance, 2) }}
                        </td>
                        <!-- موجودی بانک -->
                        <td dir="ltr" class="text-blue">
                            {{ number_format($bankBalance, 2) }}
                        </td>
                        <!-- مجموعه کل ارز -->
                        <td dir="ltr" class="text-purple">
                            <strong>{{ number_format($totalCurrency, 2) }}</strong>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <!-- بخش موجودی هر ارز به صورت جداگانه -->
    @if (isset($totalBalanceByCurrency) && count($totalBalanceByCurrency) > 0)
        <div class="page-break"></div>
        <h3 style="margin-top: 20px; margin-bottom: 10px; font-size: 11pt;">موجودی هر ارز (صندوق + بانک)</h3>
        
        @php
            $currenciesDisplay = [];
            foreach ($totalBalanceByCurrency as $currencyCode => $totalAmount) {
                if ($totalAmount > 0) {
                    $safe = $currencySafeBalance[$currencyCode] ?? 0;
                    $bank = $bankAccountBalance[$currencyCode] ?? 0;
                    $currencyName = $currencies[$currencyCode] ?? $currencyCode;
                    
                    $currenciesDisplay[] = [
                        'code' => $currencyCode,
                        'name' => $currencyName,
                        'safe' => $safe,
                        'bank' => $bank,
                        'total' => $totalAmount
                    ];
                }
            }
            
            // دسته‌بندی ارزها به دو ستون
            $chunks = array_chunk($currenciesDisplay, ceil(count($currenciesDisplay) / 2));
        @endphp
        
        <table style="width: 100%; margin-bottom: 20px;">
            <tbody>
                <tr>
                    @foreach ($chunks as $column)
                        <td style="vertical-align: top; width: 50%; padding: 0 10px;">
                            @foreach ($column as $item)
                                <div style="border: 1px solid #ddd; border-radius: 5px; padding: 10px; margin-bottom: 10px; background: #f8f9fa;">
                                    <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                        <span style="font-weight: bold; font-size: 10pt;">{{ $item['name'] }}</span>
                                        <span style="font-weight: bold; color: #059669; font-size: 10pt;" dir="ltr">
                                            {{ number_format($item['total'], 2) }}
                                        </span>
                                    </div>
                                    <div style="font-size: 8pt; color: #666;">
                                        <div style="display: flex; justify-content: space-between;">
                                            <span>صندوق:</span>
                                            <span dir="ltr">{{ number_format($item['safe'], 2) }}</span>
                                        </div>
                                        <div style="display: flex; justify-content: space-between; margin-top: 2px;">
                                            <span>بانک:</span>
                                            <span dir="ltr">{{ number_format($item['bank'], 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </td>
                    @endforeach
                </tr>
            </tbody>
        </table>
    @endif

    <!-- بخش خلاصه موجودی کلی تمام ارزها -->
    @if (isset($currencySafeBalance) && isset($bankAccountBalance))
        <div class="page-break"></div>
        <h3 style="margin-top: 20px; margin-bottom: 10px; font-size: 11pt;">خلاصه موجودی کلی تمام ارزها</h3>
        
        @php
            $totalSafeAll = array_sum($currencySafeBalance);
            $totalBankAll = array_sum($bankAccountBalance);
            $grandTotalAll = $totalSafeAll + $totalBankAll;
        @endphp
        
        <table class="balance-table" style="margin-top: 15px;">
            <thead>
                <tr>
                    <th width="30">ردیف</th>
                    <th width="200">نوع موجودی</th>
                    <th width="150">مقدار</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td><strong>مجموع موجودی صندوق (همه ارزها)</strong></td>
                    <td dir="ltr" class="text-blue">{{ number_format($totalSafeAll, 2) }}</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td><strong>مجموع موجودی بانک (همه ارزها)</strong></td>
                    <td dir="ltr" class="text-blue">{{ number_format($totalBankAll, 2) }}</td>
                </tr>
                <tr style="background-color: #f0f9ff; font-weight: bold;">
                    <td>3</td>
                    <td><strong>مجموع کل موجودی (همه ارزها)</strong></td>
                    <td dir="ltr" class="text-green" style="font-size: 10pt;">
                        {{ number_format($grandTotalAll, 2) }}
                    </td>
                </tr>
            </tbody>
        </table>
        
        <!-- خلاصه عددی -->
        <div style="margin-top: 20px; padding: 15px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 8px;">
            <div style="text-align: center; margin-bottom: 10px; font-size: 11pt; font-weight: bold;">
                خلاصه موجودی کلی
            </div>
            <table style="width: 100%; color: white;">
                <tbody>
                    <tr>
                        <td style="text-align: center; padding: 5px;">
                            <div style="font-size: 9pt;">مجموع صندوق</div>
                            <div style="font-size: 14pt; font-weight: bold;" dir="ltr">{{ number_format($totalSafeAll, 2) }}</div>
                        </td>
                        <td style="text-align: center; padding: 5px;">
                            <div style="font-size: 9pt;">مجموع بانک</div>
                            <div style="font-size: 14pt; font-weight: bold;" dir="ltr">{{ number_format($totalBankAll, 2) }}</div>
                        </td>
                        <td style="text-align: center; padding: 5px;">
                            <div style="font-size: 9pt;">مجموع کل</div>
                            <div style="font-size: 16pt; font-weight: bold;" dir="ltr">{{ number_format($grandTotalAll, 2) }}</div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    @endif

    <div class="footer">
        <p>این گزارش توسط سیستم مدیریت صرافی دیجیتال تولید شده است.</p>
        <p>تعداد کل رکوردها: {{ $transactions->count() }} | تاریخ تولید: {{ now()->format('Y/m/d H:i') }}</p>
        <p>صفحه 1 از 1</p>
    </div>

</body>

</html>