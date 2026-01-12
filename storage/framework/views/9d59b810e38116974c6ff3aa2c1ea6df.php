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
        <p>تاریخ تولید: <?php echo e(now()->format('Y/m/d H:i')); ?></p>
    </div>

    <!-- نمایش فیلترها اگر وجود داشته باشند -->
    <?php if(isset($filters) && (isset($filters['transactionType']) || isset($filters['accountType']) || isset($filters['currency']) || isset($filters['fromDate']) || isset($filters['toDate']))): ?>
        <div class="filters-section">
            <h3>فیلترهای اعمال شده</h3>
            <div class="filter-row">
                <?php if(isset($filters['transactionType']) && $filters['transactionType']): ?>
                    <div class="filter-item">
                        <span class="filter-label">نوع تراکنش:</span>
                        <span class="filter-value"><?php echo e($filters['transactionType']); ?></span>
                    </div>
                <?php endif; ?>
                <?php if(isset($filters['accountType']) && $filters['accountType']): ?>
                    <div class="filter-item">
                        <span class="filter-label">نوع حساب:</span>
                        <span class="filter-value"><?php echo e($filters['accountType']); ?></span>
                    </div>
                <?php endif; ?>
                <?php if(isset($filters['currency']) && $filters['currency']): ?>
                    <div class="filter-item">
                        <span class="filter-label">ارز:</span>
                        <span class="filter-value"><?php echo e($filters['currency']); ?></span>
                    </div>
                <?php endif; ?>
                <?php if(isset($filters['fromDate']) && $filters['fromDate']): ?>
                    <div class="filter-item">
                        <span class="filter-label">از تاریخ:</span>
                        <span class="filter-value"><?php echo e(\Morilog\Jalali\Jalalian::fromFormat('Y-m-d', $filters['fromDate'])->format('Y/m/d')); ?></span>
                    </div>
                <?php endif; ?>
                <?php if(isset($filters['toDate']) && $filters['toDate']): ?>
                    <div class="filter-item">
                        <span class="filter-label">تا تاریخ:</span>
                        <span class="filter-value"><?php echo e(\Morilog\Jalali\Jalalian::fromFormat('Y-m-d', $filters['toDate'])->format('Y/m/d')); ?></span>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if($transactions->count() > 0): ?>
        <h3 style="margin-top: 20px; margin-bottom: 10px; font-size: 11pt;">لیست تراکنش‌ها (<?php echo e($transactions->count()); ?>

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

                <?php
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
                ?>

                <?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($index + 1); ?></td>
                        <td>
                            <?php if(empty($transaction->customer_id) && !empty($transaction->withdraw_id)): ?>
                                <div style="font-weight: bold;">برداشت</div>
                            <?php elseif(empty($transaction->customer_id) && $transaction->is_sell_table == 1): ?>
                                <div style="font-weight: bold;">معامله از صندوق</div>
                            <?php else: ?>
                                <div style="font-weight: bold;">
                                    <?php echo e($transaction->customer->fullname ?? 'نامشخص'); ?>

                                </div>
                                <div style="font-size: 7pt; color: #666;">
                                    <?php echo e($transaction->customer->account_number ?? ''); ?>

                                </div>
                            <?php endif; ?>
                        </td>

                        <td><?php echo e($transaction->type); ?></td>
                        <td><?php echo e($transaction->account_type); ?></td>
                        <td class="<?php echo e($transaction->type == 'رسید' ? 'text-green' : 'text-red'); ?>">
                            <?php echo e(number_format($transaction->amount, 2)); ?>

                        </td>
                        <td>
                            <?php echo e($currenciesFa[strtolower($transaction->currency)] ?? $transaction->currency); ?>

                        </td>
                        <td dir="ltr"><?php echo e(number_format($transaction->balance, 2)); ?></td>
                        <td style="text-align: right; padding: 0 5px;"><?php echo e($transaction->description); ?></td>
                        <td>
                            <div><?php echo e(explode(' ', $transaction->date)[0]); ?></div>
                            <div class="transaction-date"><?php echo e($transaction->created_at->format('H:i')); ?></div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="no-data">
            هیچ تراکنشی برای نمایش وجود ندارد
        </div>
    <?php endif; ?>

    <?php if(isset($summary) && $summary->count() > 0): ?>
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
                <?php $__currentLoopData = $summary; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $currencyCode = strtolower($item->currency);
                        $safeBalance = $currencySafeBalance[$currencyCode] ?? 0;
                        $bankBalance = $bankAccountBalance[$currencyCode] ?? 0;
                        $totalCurrency = $totalBalanceByCurrency[$currencyCode] ?? 0;
                    ?>
                    <tr>
                        <td><?php echo e($index + 1); ?></td>
                        <td><strong><?php echo e($item->currency); ?></strong></td>
                        <td dir="ltr" class="text-green"><?php echo e(number_format($item->receipt_cash, 2)); ?></td>
                        <td dir="ltr" class="text-red"><?php echo e(number_format($item->withdrawal_cash, 2)); ?></td>
                        <td dir="ltr" class="text-green"><?php echo e(number_format($item->receipt_bank, 2)); ?></td>
                        <td dir="ltr" class="text-red"><?php echo e(number_format($item->withdrawal_bank, 2)); ?></td>
                        <td dir="ltr" class="<?php echo e($item->balance_cash >= 0 ? 'text-green' : 'text-red'); ?>">
                            <strong><?php echo e(number_format($item->balance_cash, 2)); ?></strong>
                        </td>
                        <td dir="ltr" class="<?php echo e($item->balance_bank >= 0 ? 'text-green' : 'text-red'); ?>">
                            <strong><?php echo e(number_format($item->balance_bank, 2)); ?></strong>
                        </td>
                        <!-- موجودی صندوق -->
                        <td dir="ltr" class="text-blue">
                            <?php echo e(number_format($safeBalance, 2)); ?>

                        </td>
                        <!-- موجودی بانک -->
                        <td dir="ltr" class="text-blue">
                            <?php echo e(number_format($bankBalance, 2)); ?>

                        </td>
                        <!-- مجموعه کل ارز -->
                        <td dir="ltr" class="text-purple">
                            <strong><?php echo e(number_format($totalCurrency, 2)); ?></strong>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    <?php endif; ?>

    <!-- بخش موجودی هر ارز به صورت جداگانه -->
    <?php if(isset($totalBalanceByCurrency) && count($totalBalanceByCurrency) > 0): ?>
        <div class="page-break"></div>
        <h3 style="margin-top: 20px; margin-bottom: 10px; font-size: 11pt;">موجودی هر ارز (صندوق + بانک)</h3>
        
        <?php
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
        ?>
        
        <table style="width: 100%; margin-bottom: 20px;">
            <tbody>
                <tr>
                    <?php $__currentLoopData = $chunks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $column): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <td style="vertical-align: top; width: 50%; padding: 0 10px;">
                            <?php $__currentLoopData = $column; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div style="border: 1px solid #ddd; border-radius: 5px; padding: 10px; margin-bottom: 10px; background: #f8f9fa;">
                                    <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                        <span style="font-weight: bold; font-size: 10pt;"><?php echo e($item['name']); ?></span>
                                        <span style="font-weight: bold; color: #059669; font-size: 10pt;" dir="ltr">
                                            <?php echo e(number_format($item['total'], 2)); ?>

                                        </span>
                                    </div>
                                    <div style="font-size: 8pt; color: #666;">
                                        <div style="display: flex; justify-content: space-between;">
                                            <span>صندوق:</span>
                                            <span dir="ltr"><?php echo e(number_format($item['safe'], 2)); ?></span>
                                        </div>
                                        <div style="display: flex; justify-content: space-between; margin-top: 2px;">
                                            <span>بانک:</span>
                                            <span dir="ltr"><?php echo e(number_format($item['bank'], 2)); ?></span>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </td>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tr>
            </tbody>
        </table>
    <?php endif; ?>

    <!-- بخش خلاصه موجودی کلی تمام ارزها -->
    <?php if(isset($currencySafeBalance) && isset($bankAccountBalance)): ?>
        <div class="page-break"></div>
        <h3 style="margin-top: 20px; margin-bottom: 10px; font-size: 11pt;">خلاصه موجودی کلی تمام ارزها</h3>
        
        <?php
            $totalSafeAll = array_sum($currencySafeBalance);
            $totalBankAll = array_sum($bankAccountBalance);
            $grandTotalAll = $totalSafeAll + $totalBankAll;
        ?>
        
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
                    <td dir="ltr" class="text-blue"><?php echo e(number_format($totalSafeAll, 2)); ?></td>
                </tr>
                <tr>
                    <td>2</td>
                    <td><strong>مجموع موجودی بانک (همه ارزها)</strong></td>
                    <td dir="ltr" class="text-blue"><?php echo e(number_format($totalBankAll, 2)); ?></td>
                </tr>
                <tr style="background-color: #f0f9ff; font-weight: bold;">
                    <td>3</td>
                    <td><strong>مجموع کل موجودی (همه ارزها)</strong></td>
                    <td dir="ltr" class="text-green" style="font-size: 10pt;">
                        <?php echo e(number_format($grandTotalAll, 2)); ?>

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
                            <div style="font-size: 14pt; font-weight: bold;" dir="ltr"><?php echo e(number_format($totalSafeAll, 2)); ?></div>
                        </td>
                        <td style="text-align: center; padding: 5px;">
                            <div style="font-size: 9pt;">مجموع بانک</div>
                            <div style="font-size: 14pt; font-weight: bold;" dir="ltr"><?php echo e(number_format($totalBankAll, 2)); ?></div>
                        </td>
                        <td style="text-align: center; padding: 5px;">
                            <div style="font-size: 9pt;">مجموع کل</div>
                            <div style="font-size: 16pt; font-weight: bold;" dir="ltr"><?php echo e(number_format($grandTotalAll, 2)); ?></div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <div class="footer">
        <p>این گزارش توسط سیستم مدیریت صرافی دیجیتال تولید شده است.</p>
        <p>تعداد کل رکوردها: <?php echo e($transactions->count()); ?> | تاریخ تولید: <?php echo e(now()->format('Y/m/d H:i')); ?></p>
        <p>صفحه 1 از 1</p>
    </div>

</body>

</html><?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/pdf/Sarafi/journal.blade.php ENDPATH**/ ?>