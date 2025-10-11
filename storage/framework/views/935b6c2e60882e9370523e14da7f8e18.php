<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <style>
        body {
            font-family: Shabnam, sans-serif;
            font-size: 10px;
            line-height: 1.3;
            margin: 0;
            padding: 15px;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #2B65E5;
            padding-bottom: 8px;
        }
        .header h1 {
            color: #2B65E5;
            margin: 0;
            font-size: 16px;
        }
        .info {
            margin-bottom: 12px;
            padding: 8px;
            background: #f5f5f5;
            border-radius: 4px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 8px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 5px 3px;
            text-align: center;
        }
        th {
            background-color: #2B65E5;
            color: white;
            font-weight: bold;
        }
        .footer {
            margin-top: 15px;
            text-align: center;
            font-size: 9px;
            color: #666;
        }
        .status-confirmed {
            background-color: #d4edda;
            color: #155724;
            padding: 2px 4px;
            border-radius: 3px;
            font-size: 7px;
        }
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
            padding: 2px 4px;
            border-radius: 3px;
            font-size: 7px;
        }
        .summary-section {
            margin-top: 15px;
            padding: 10px;
            background: #e7f3ff;
            border-radius: 4px;
            border-right: 3px solid #2B65E5;
        }
        .summary-title {
            font-weight: bold;
            color: #2B65E5;
            text-align: center;
            margin-bottom: 8px;
            font-size: 11px;
        }
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8px;
        }
        .summary-table th {
            background-color: #1e4bb6;
            padding: 4px;
        }
        .summary-table td {
            padding: 4px;
        }
    </style>
</head>
<body dir="rtl">
    <div class="header">
        <h1>گزارش تراکنش‌های مالی</h1>
    </div>
    
    <div class="info">
        <strong>مشتری:</strong> <?php echo e($customer_name); ?><br>
        <strong>بازه زمانی:</strong> <?php echo e($start_date); ?> تا <?php echo e($end_date); ?><br>
        <strong>تعداد تراکنش‌ها:</strong> <?php echo e(count($transactions)); ?><br>
        <strong>تاریخ تولید:</strong> <?php echo e($generated_at); ?>

    </div>

    <table>
        <thead>
            <tr>
                <th rowspan="2">#</th>
                <th rowspan="2">تاریخ</th>
                <th rowspan="2">شماره سند</th>
                <th rowspan="2">توضیحات</th>
                <th rowspan="2">توسط</th>

                <!-- نمایش داینامیک ارزهای پیش‌فرض -->
                <?php $__currentLoopData = $active_currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $currency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <th colspan="2"><?php echo e($currency['name_fa']); ?></th>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <th rowspan="2">وضعیت</th>
            </tr>
            <tr>
                <?php $__currentLoopData = $active_currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $currency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <th>رسید</th>
                <th>برد</th>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($index + 1); ?></td>
                <td><?php echo e($transaction->date); ?></td>
                <td><?php echo e($transaction->document_number ?? 'SN-' . $transaction->id); ?></td>
                <td><?php echo e($transaction->description); ?></td>
                <td><?php echo e($transaction->by); ?></td>

                <!-- نمایش مقادیر برای هر ارز پیش‌فرض -->
                <?php $__currentLoopData = $active_currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $currency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <td><?php echo e($transaction->currency == $code && $transaction->type == 'رسید' ? number_format($transaction->amount) : ''); ?></td>
                <td><?php echo e($transaction->currency == $code && $transaction->type == 'برد' ? number_format($transaction->amount) : ''); ?></td>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <td>
                    <span class="<?php echo e($transaction->status == 'تأیید شده' ? 'status-confirmed' : 'status-pending'); ?>">
                        <?php echo e($transaction->status ?? 'در انتظار'); ?>

                    </span>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

    <!-- بخش موجودی کل -->
    <div class="summary-section">
        <div class="summary-title">موجودی کل</div>
        <table class="summary-table">
            <thead>
                <tr>
                    <th>واحد پول</th>
                    <th>موجودی قبلی</th>
                    <th>رسید</th>
                    <th>برد</th>
                    <th>بیلانس</th>
                    <th>موجودی فعلی</th>
                    <th>وضعیت</th>6511439357409954 - احمد
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $balances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $balance): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($balance['name_fa']); ?></td>
                    <td><?php echo e(number_format($balance['previous_balance'])); ?></td>
                    <td><?php echo e(number_format($balance['received'])); ?></td>
                    <td><?php echo e(number_format($balance['spent'])); ?></td>
                    <td><?php echo e(number_format($balance['balance'])); ?></td>
                    <td><?php echo e(number_format($balance['current_balance'])); ?></td>
                    <td>
                        <span class="<?php echo e($balance['status'] == 'طلبکار' ? 'status-confirmed' : 'status-pending'); ?>">
                            <?php echo e($balance['status']); ?>

                        </span>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>

    <div class="footer">
        تاریخ چاپ: <?php echo e($generated_at); ?> | سیستم صرافی
    </div>
</body>
</html><?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/pdf/Sarafi/transactions-report.blade.php ENDPATH**/ ?>