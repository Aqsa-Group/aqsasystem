<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        /* فونت و بدنه */
        body {
            font-family: "Tahoma", "Segoe UI", sans-serif;
            font-size: 11px;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            color: #2c3e50;
            background: #f7f8fa;
        }

        /* هدر اصلی صرافی */
        .main-header {
            text-align: center;
            margin-bottom: 10px;
            color: #34495e;
        }
        .main-header h2 {
            margin: 0;
            font-size: 20px;
            font-weight: 700;
        }

        /* هدر گزارش */
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 8px;
            border-bottom: 2px solid #34495e;
        }
        .header h1 {
            font-size: 18px;
            font-weight: 700;
            margin: 0;
            color: #34495e;
        }

        /* کارت اطلاعات گزارش */
        .info {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin-bottom: 20px;
            padding: 15px 20px;
            background: #ecf0f1;
            border-left: 6px solid #2980b9;
            border-radius: 6px;
            font-size: 10.5px;
        }
        .info-item {
            display: flex;
            gap: 5px;
            align-items: center;
        }
        .info-item strong {
            color: #2c3e50;
        }
        .customer-name {
            color: #2980b9;
            font-weight: 600;
        }

        /* جدول تراکنش‌ها */
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
            margin-bottom: 20px;
            font-size: 10px;
        }
        th, td {
            padding: 8px 6px;
            border: 1px solid #dfe6ec;
            text-align: center;
        }
        th {
            background-color: #2980b9;
            color: white;
            font-weight: 600;
        }
        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tbody tr:hover {
            background-color: #e1f0fb;
        }

        /* وضعیت تراکنش */
        .status-confirmed {
            background-color: #dff0d8;
            color: #3c763d;
            padding: 4px 8px;
            border-radius: 5px;
            font-size: 9px;
            font-weight: 600;
        }
        .status-pending {
            background-color: #fcf8e3;
            color: #8a6d3b;
            padding: 4px 8px;
            border-radius: 5px;
            font-size: 9px;
            font-weight: 600;
        }

        /* جدول خلاصه موجودی */
        .summary-section {
            padding: 15px;
            background: #f0f7ff;
            border: 1px solid #cce5ff;
            border-radius: 6px;
        }
        .summary-title {
            font-weight: 700;
            font-size: 12px;
            color: #2980b9;
            text-align: center;
            margin-bottom: 10px;
        }
        .summary-table th {
            background-color: #1c5f9e;
            color: white;
            font-weight: 600;
        }
        .summary-table td {
            font-weight: 500;
        }

        /* متن وقتی داده‌ای نیست */
        .no-data {
            text-align: center;
            padding: 25px 15px;
            color: #7f8c8d;
            font-style: italic;
            background: #ffffff;
            border-radius: 6px;
            margin: 20px 0;
            border: 1px dashed #d1d5da;
        }

        /* سلول‌های عددی */
        .amount-cell {
            font-family: 'Courier New', monospace;
            font-weight: 500;
        }

        .currency-header {
            font-weight: 600;
            background-color: #1c5f9e !important;
        }

        /* فوتر */
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #95a5a6;
            border-top: 1px solid #dfe6ec;
            padding-top: 12px;
        }
    </style>
</head>
<body dir="rtl">

    <!-- هدر صرافی -->
    <div class="main-header">
        <h2>صرافی <?php echo e(Auth::guard('sarafi')->user()->sarafi_name); ?></h2>
    </div>

    <!-- هدر گزارش -->
    <div class="header">
        <h1>گزارش تراکنش‌های انجام شده</h1>
    </div>

    <!-- اطلاعات گزارش -->
    <div class="info">
        <div class="info-item"><strong>حساب:</strong> <span class="customer-name"><?php echo e($customer_name); ?></span></div>
        <div class="info-item"><strong>شماره حساب:</strong> <span class="customer-name"><?php echo e($customer->account_number ?? '---'); ?></span></div>
        <div class="info-item"><strong>بازه زمانی:</strong> <?php echo e($start_date); ?> تا <?php echo e($end_date); ?></div>
    </div>

    <!-- جدول تراکنش‌ها -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($transactions) > 0): ?>
    <table>
        <thead>
            <tr>
                <th rowspan="2">#</th>
                <th rowspan="2">تاریخ</th>
                <th rowspan="2">نوع حساب</th>
                <th rowspan="2">شماره سند</th>
                <th rowspan="2">توضیحات</th>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $active_currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $currency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <th colspan="2" class="currency-header"><?php echo e($currency['name_fa']); ?></th>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
               
            </tr>
            <tr>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $active_currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $currency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <th>رسید</th>
                <th>برد</th>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($index + 1); ?></td>
<td><?php echo e(explode(' ', $transaction->date)[0]); ?></td>
                <td><?php echo e($transaction->account_type); ?></td>
                <td><?php echo e($transaction->document_number ?? 'SN-' . str_pad($transaction->id, 3, '0', STR_PAD_LEFT)); ?></td>
                <td><?php echo e(Str::limit($transaction->description, 20)); ?></td>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $active_currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $currency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <td class="amount-cell"><?php echo e($transaction->currency == $code && $transaction->type == 'رسید' ? number_format($transaction->amount) : ''); ?></td>
                <td class="amount-cell"><?php echo e($transaction->currency == $code && $transaction->type == 'برد' ? number_format($transaction->amount) : ''); ?></td>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
             
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </tbody>
    </table>
    <?php else: ?>
    <div class="no-data">
         هیچ تراکنشی در این بازه زمانی یافت نشد
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- بخش خلاصه موجودی -->
   <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($balances) > 0): ?>
<div class="summary-section">
    <div class="summary-title"> خلاصه موجودی‌ها</div>
    <table class="summary-table">
        <thead>
            <tr>
                <th>واحد پول</th>
                <th>موجودی قبلی</th>
                <th>رسید</th>
                <th>برد</th>
                <th>بیلانس دوره</th>
                <th>موجودی فعلی</th>
                <th>وضعیت</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $totalPrevious = 0;
                $totalReceived = 0;
                $totalSpent = 0;
                $totalBalance = 0;
                $totalCurrent = 0;
            ?>
            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $balances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $balance): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $totalPrevious += $balance['previous_balance'] ?? 0;
                    $totalReceived += $balance['received'] ?? 0;
                    $totalSpent += $balance['spent'] ?? 0;
                    $totalBalance += $balance['balance'] ?? 0;
                    $totalCurrent += $balance['current_balance'] ?? 0;
                ?>
                <tr>
                    <td><strong><?php echo e($balance['name_fa']); ?></strong></td>
                    <td class="amount-cell" dir="ltr"><?php echo e(number_format($balance['previous_balance'] ?? 0)); ?></td>
                    <td class="amount-cell" dir="ltr"><?php echo e(number_format($balance['received'])); ?></td>
                    <td class="amount-cell" dir="ltr"><?php echo e(number_format($balance['spent'])); ?></td>
                    <td class="amount-cell" dir="ltr"><?php echo e(number_format($balance['balance'])); ?></td>
                    <td class="amount-cell" dir="ltr"><strong><?php echo e(number_format($balance['current_balance'])); ?></strong></td>
                    <td>
                        <span class="<?php echo e($balance['status'] == 'طلبکار' ? 'status-confirmed' : 'status-pending'); ?>">
                            <?php echo e($balance['status']); ?>

                        </span>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            
       
        </tbody>
    </table>
</div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- فوتر -->
    <div class="footer">
         تاریخ چاپ: <?php echo e($generated_at); ?> | سیستم صرافی
    </div>
</body>
</html>
<?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/pdf/Sarafi/transactions-report.blade.php ENDPATH**/ ?>