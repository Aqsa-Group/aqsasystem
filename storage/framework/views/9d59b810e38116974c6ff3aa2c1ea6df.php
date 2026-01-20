<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>گزارش معاملات روزانه</title>
    <style>
        =body {
            font-family: dejavusans, sans-serif;
            font-size: 9pt;
            line-height: 1.4;
            color: #000;
            margin: 0;
            padding: 0;
            direction: rtl;
            text-align: right;
        }

      

        .page-break {
            page-break-before: always;
        }

        /* هدر اصلی */
        .main-header {
            text-align: center;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 2px solid #2B65E5;
        }

        .main-header h1 {
            color: #000;
            font-size: 14pt;
            margin: 0 0 3px 0;
            font-weight: 500;
        }

        /* فیلترها */
        .filters-section {
            background: #f8f9fa;
            padding: 8px;
            border-radius: 6px;
            margin-bottom: 10px;
            border: 1px solid #dee2e6;
            font-size: 8pt;
        }

        .filters-section h3 {
            margin-top: 0;
            margin-bottom: 5px;
            font-size: 9pt;
            color: #2B65E5;
        }

        .filter-row {
            display: table;
            width: 100%;
        }

        .filter-item {
            display: table-row;
        }

        .filter-label {
            font-weight: bold;
            display: table-cell;
            padding: 2px 5px;
            width: 80px;
            vertical-align: top;
            font-size: 8pt;
        }

        .filter-value {
            display: table-cell;
            padding: 2px 5px;
            font-size: 8pt;
        }

        /* جداول */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 8px 0;
            font-size: 8pt;
        }

        th {
            background-color: #2B65E5;
            color: white;
            padding: 6px 4px;
            border: 1px solid #ddd;
            font-weight: bold;
            text-align: center;
            font-size: 8pt;
            height: 40px;
        }

        td {
            padding: 5px 4px;
            border: 1px solid #ddd;
            text-align: center;
            font-size: 8pt;
            vertical-align: middle;
        }

        .transactions-table tr:nth-child(even) {
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

        .number-cell {
            direction: ltr;
            text-align: right;
            font-family: 'Courier New', monospace;
        }

        /* جدول خلاصه */
        .summary-table th {
            background-color: #2B65E5;
        }

        /* جدول موجودی ارزها */
        .balance-table th {
            background-color: #2b23c7;
            color: white;
        }

        .balance-table td {
            text-align: center;
        }

        /* کارت‌های سود و ضرر */
        .profit-loss-table {
            width: 100%;
            margin: 15px 0;
            border-collapse: separate;
            border-spacing: 10px;
        }

        .profit-loss-table td {
            border: none;
            padding: 0;
            vertical-align: top;
        }

        .profit-card,
        .loss-card {
            padding: 10px;
            border-radius: 8px;
            min-height: 80px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            text-align: center;
        }

        .profit-card {
            background: linear-gradient(135deg, #10B981 0%, #059669 100%);
            color: white;
        }

        .loss-card {
            background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%);
            color: white;
        }

        .card-icon {
            margin: 0 auto 5px auto;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 5px;
        }

        .card-title {
            font-size: 10pt;
            font-weight: 400;
            margin: 5px 0;
        }

        .card-amount {
            font-size: 16pt;
            font-weight: 800;
            text-align: center;
            direction: ltr;
            margin-top: 5px;
            font-family: 'Courier New', monospace;
        }

        /* فوتر */
        .footer {
            margin-top: 10px;
            padding-top: 5px;
            border-top: 1px solid #dee2e6;
            text-align: center;
            font-size: 7pt;
            color: #666;
        }

        /* استایل برای چاپ */
        @media print {
            body {
                font-size: 8pt;
            }

            table {
                font-size: 7pt;
            }

            th,
            td {
                padding: 4px 3px;
            }
        }

        /* شماره ردیف */
        .row-number {
            background: #2B65E5;
            color: white;
            padding: 2px 6px;
            border-radius: 3px;
            display: inline-block;
            font-size: 8pt;
        }

        /* اطلاعات مشتری */
        .customer-info {
            background: #f8fafc;
            padding: 8px;
            border-radius: 6px;
            margin-bottom: 10px;
            border: 1px solid #e2e8f0;
            font-size: 9pt;
        }

        .customer-name {
            font-weight: bold;
            color: #1e293b;
        }

        .customer-account {
            font-size: 8pt;
            color: #64748b;
            margin-top: 3px;
        }
    </style>
</head>

<body>

    <div class="main-header keep-together">
        <h1>صفحه گزارشات معاملات روزانه حسابات و صندوق ها</h1>
        <p style="color: #666; font-size: 8pt;">تاریخ تولید: <?php echo e(\Morilog\Jalali\Jalalian::now()->format('Y/m/d H:i')); ?>

        </p>
    </div>

    <!-- نمایش فیلترها -->
    <?php if(isset($filters) &&
            (isset($filters['transactionType']) ||
                isset($filters['accountType']) ||
                isset($filters['currency']) ||
                isset($filters['fromDate']) ||
                isset($filters['toDate']))): ?>
        <div class="filters-section keep-together">
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
                        <span
                            class="filter-value"><?php echo e($currencies[$filters['currency']] ?? $filters['currency']); ?></span>
                    </div>
                <?php endif; ?>
                <?php if(isset($filters['fromDate']) && $filters['fromDate']): ?>
                    <div class="filter-item">
                        <span class="filter-label">از تاریخ:</span>
                        <span
                            class="filter-value"><?php echo e(\Morilog\Jalali\Jalalian::fromFormat('Y-m-d', $filters['fromDate'])->format('Y/m/d')); ?></span>
                    </div>
                <?php endif; ?>
                <?php if(isset($filters['toDate']) && $filters['toDate']): ?>
                    <div class="filter-item">
                        <span class="filter-label">تا تاریخ:</span>
                        <span
                            class="filter-value"><?php echo e(\Morilog\Jalali\Jalalian::fromFormat('Y-m-d', $filters['toDate'])->format('Y/m/d')); ?></span>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- اطلاعات مشتری -->
    <?php if($customerName): ?>
        <div class="customer-info keep-together">
            <div class="customer-name"><?php echo e($customerName); ?></div>
            <?php if($customerAccount): ?>
                <div class="customer-account">شماره حساب: <?php echo e($customerAccount); ?></div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- جدول تراکنش‌ها -->
    <div class="keep-together">
        <h3 style="margin: 10px 0 5px 0; font-size: 10pt; color: #2B65E5;">لیست تراکنش‌ها (<?php echo e($transactions->count()); ?>

            رکورد)</h3>
        <?php if($transactions->count() > 0): ?>
            <table class="transactions-table">
                <thead>
                    <tr>
                        <th width="30">
                            <span class="row-number">#</span>
                        </th>
                        <th width="100">نام حساب</th>
                        <th width="60">نوع معامله</th>
                        <th width="60">نوع حساب</th>
                        <th width="70">مقدار</th>
                        <th width="50">ارز</th>
                        <th width="80">بیلانس فعلی</th>
                        <th width="120">توضیحات</th>
                        <th width="80">تاریخ</th>
                    </tr>
                </thead>
                <tbody>
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
                                    <?php if(!empty($transaction->customer_id) && $transaction->customer): ?>
                                        <div style="font-size: 7pt; color: #666;">
                                            <?php echo e($transaction->customer->account_number ?? ''); ?>

                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($transaction->type); ?></td>
                            <td><?php echo e($transaction->account_type); ?></td>
                            <td class="<?php echo e($transaction->type == 'رسید' ? 'text-green' : 'text-red'); ?> number-cell">
                                <?php echo e(number_format($transaction->amount, 2)); ?>

                            </td>
                            <td><?php echo e($transaction->currency_fa); ?></td>
                            <td class="number-cell"><?php echo e(number_format($transaction->balance, 2)); ?></td>
                            <td style="text-align: right; padding: 0 5px;"><?php echo e($transaction->description); ?></td>
                            <td>
                                <div><?php echo e(explode(' ', $transaction->date)[0]); ?></div>
                                <div style="font-size: 7pt; color: #666;">
                                    <?php echo e(\Carbon\Carbon::parse($transaction->created_at)->format('H:i')); ?></div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        <?php else: ?>
            <div style="text-align: center; padding: 15px; color: #666; font-style: italic; font-size: 9pt;">
                هیچ تراکنشی برای نمایش وجود ندارد
            </div>
        <?php endif; ?>
    </div>

    <!-- جدول خلاصه گزارشات -->
    <?php if($summary->count() > 0): ?>
        <div class="keep-together">
            <h3 style="margin: 15px 0 5px 0; font-size: 10pt; color: #2B65E5;">خلاصه گزارشات</h3>
            <table class="summary-table">
                <thead>
                    <tr>
                        <th width="30">#</th>
                        <th width="70">ارز</th>
                        <th width="80">رسید نقدی</th>
                        <th width="80">برد نقدی</th>
                        <th width="80">رسید بانکی</th>
                        <th width="80">برد بانکی</th>
                        <th width="85">بیلانس نقدی</th>
                        <th width="85">بیلانس بانکی</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $summary; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($index + 1); ?></td>
                            <td style="font-weight: bold;"><?php echo e($item->currency_fa); ?></td>
                            <td class="text-green number-cell"><?php echo e(number_format($item->receipt_cash, 2)); ?></td>
                            <td class="text-red number-cell"><?php echo e(number_format($item->withdrawal_cash, 2)); ?></td>
                            <td class="text-green number-cell"><?php echo e(number_format($item->receipt_bank, 2)); ?></td>
                            <td class="text-red number-cell"><?php echo e(number_format($item->withdrawal_bank, 2)); ?></td>
                            <td class="<?php echo e($item->balance_cash >= 0 ? 'text-green' : 'text-red'); ?> number-cell">
                                <?php echo e(number_format($item->balance_cash, 2)); ?>

                            </td>
                            <td class="<?php echo e($item->balance_bank >= 0 ? 'text-green' : 'text-red'); ?> number-cell">
                                <?php echo e(number_format($item->balance_bank, 2)); ?>

                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <!-- موجودی صندوق نقدی و بانکی - با تیبل -->
    <?php if(isset($totalBalanceByCurrency) && count($totalBalanceByCurrency) > 0): ?>
        <div class="keep-together">
            <h3 style="margin: 15px 0 5px 0; font-size: 10pt; color: #2B65E5; text-align: center;">موجودی صندوق نقدی و
                بانکی</h3>

            <?php
                // ارزها را به دسته‌های 3 تایی تقسیم می‌کنیم
                $currencyChunks = array_chunk($totalBalanceByCurrency, 3, true);
            ?>

            <?php $__currentLoopData = $currencyChunks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chunk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <table class="balance-table" style="width: 100%; margin-bottom: 10px;">
                    <thead>
                        <tr>
                            <?php $__currentLoopData = $chunk; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currencyCode => $totalAmount): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <th style="width: <?php echo e(100 / count($chunk)); ?>%;">
                                    <?php echo e($currencies[$currencyCode] ?? $currencyCode); ?></th>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <?php $__currentLoopData = $chunk; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currencyCode => $totalAmount): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $safe = $currencySafeBalance[$currencyCode] ?? 0;
                                    $bank = $bankAccountBalance[$currencyCode] ?? 0;
                                ?>
                                <td>
                                    <div
                                        style="font-weight: bold; font-size: 11pt; color: #059669; margin-bottom: 5px; direction: ltr;">
                                        <?php echo e(number_format($totalAmount, 2)); ?>

                                    </div>
                                    <div style="font-size: 9pt;">
                                        <div style="margin-bottom: 3px;">
                                            <span>نقدی:</span>
                                            <span
                                                style="direction: ltr; float: left;"><?php echo e(number_format($safe, 2)); ?></span>
                                        </div>
                                        <div>
                                            <span>بانکی:</span>
                                            <span
                                                style="direction: ltr; float: left;"><?php echo e(number_format($bank, 2)); ?></span>
                                        </div>
                                    </div>
                                </td>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tr>
                    </tbody>
                </table>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>

    <!-- سود و ضرر امروز -->
    <div style="width: 100%; margin: 15px 0;">
        <table style="width: 100%; border-collapse: separate; border-spacing: 10px;">
            <tr>
                <td width="50%" style="padding: 0 5px;">
                    <div style=" color:black; padding: 15px; border-radius: 8px; text-align: center;">
                        <div style="font-size: 14pt; margin-bottom: 5px; font-weight: bold;">سود امروز</div>
                        <div style="font-size: 20pt; font-weight: bold; direction: ltr;">
                            <?php echo e(number_format($todayProfit, 2)); ?>

                        </div>
                    </div>
                </td>
                <td width="50%" style="padding: 0 5px;">
                    <div style=" color:black; padding: 15px; border-radius: 8px; text-align: center;">
                        <div style="font-size: 14pt; margin-bottom: 5px; font-weight: bold;">ضرر امروز</div>
                        <div style="font-size: 20pt; font-weight: bold; direction: ltr;">
                            <?php echo e(number_format($todayLoss, 2)); ?>

                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
<?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/pdf/Sarafi/journal.blade.php ENDPATH**/ ?>