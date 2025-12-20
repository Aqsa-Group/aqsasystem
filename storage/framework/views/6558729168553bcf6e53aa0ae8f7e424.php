<!DOCTYPE html>
<html dir="rtl">

<head>
    <meta charset="UTF-8">
    <title><?php echo e($title); ?></title>
    <style>
        body {
            font-family: DejaVu Sans, Shabnam, sans-serif;
            direction: rtl;
            text-align: right;
            font-size: 12px;
            line-height: 1.4;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #333;
            padding-bottom: 8px;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
            color: #2B65E5;
        }

        .header p {
            margin: 5px 0;
            font-size: 12px;
            color: #666;
        }

        .filters {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
        }

        .filters h3 {
            margin: 0 0 8px 0;
            font-size: 14px;
            color: #333;
        }

        .filter-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
        }

        .filter-item {
            display: flex;
            justify-content: space-between;
            padding: 3px 0;
        }

        .filter-label {
            font-weight: bold;
            color: #555;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 10px;
        }

        th {
            background-color: #2B65E5;
            color: white;
            padding: 6px 4px;
            border: 1px solid #ddd;
            font-weight: bold;
            text-align: center;
        }

        td {
            padding: 5px 3px;
            border: 1px solid #ddd;
            text-align: center;
        }

        .currency-number {
            text-align: left;
            direction: ltr;
            font-family: 'Courier New', monospace;
        }

        .total-row {
            background-color: #e8f5e8;
            font-weight: bold;
        }

        .footer {
            margin-top: 15px;
            padding-top: 8px;
            border-top: 1px solid #ddd;
            font-size: 10px;
            color: #666;
        }

        .no-data {
            text-align: center;
            padding: 20px;
            color: #999;
            font-style: italic;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1><?php echo e($title); ?></h1>
        <p>تاریخ چاپ: <?php echo e($print_date); ?></p>
        <p>تعداد مشتریان: <?php echo e($total_customers); ?> نفر</p>
    </div>

    <?php if(count($reports) > 0): ?>
    <table>
        <thead>
            <tr>
                <th width="30">#</th>
                <th width="80">نمبرحساب</th>
                <th width="120">نام حساب</th>
                <th width="100">مشتری معرف</th>
                <th width="70">آخرین تاریخ</th>
                <?php $__currentLoopData = $currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currencyCode => $currencyName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <th width="60"><?php echo e($currencyName); ?></th>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                   <?php
                    $latestExchangeRate = \App\Models\Sarafi\ExchangeRates::latest()->first();
                    $sourceCurrency = $latestExchangeRate->source_currency ?? 'دالر';
                    ?>
                <th width="80">بیلانس به <?php echo e($sourceCurrency); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $reports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($index + 1); ?></td>
                <td><?php echo e($report['account_number']); ?></td>
                <td><?php echo e($report['fullname']); ?></td>
                <td><?php echo e($report['related_customer_name'] ?? '-'); ?></td>
                <td><?php echo e($report['last_date'] ? \Carbon\Carbon::parse($report['last_date'])->format('Y/m/d') : '-'); ?></td>
                <?php $__currentLoopData = $currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currencyCode => $currencyName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <td class="currency-number"><?php echo e(number_format($report['balances'][$currencyCode] ?? 0, 2)); ?></td>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <td class="currency-number"><?php echo e(number_format($report['total_balance'], 2)); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<tr class="total-row">
    <td colspan="<?php echo e(count($currencies) + 5); ?>">مجموع کل</td>
    <td class="currency-number">
        <?php echo e(number_format($total_balance, 2)); ?>

    </td>
</tr>

        </tbody>
    </table>
    <?php else: ?>
    <div class="no-data">
        هیچ داده‌ای برای نمایش وجود ندارد
    </div>
    <?php endif; ?>


</body>

</html><?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/pdf/Sarafi/customer-balance-report.blade.php ENDPATH**/ ?>