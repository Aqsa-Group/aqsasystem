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

        .section-header {
            background-color: #2B65E5;
            color: white;
            padding: 6px;
            margin: 15px 0 8px 0;
            border-radius: 4px;
            text-align: center;
            font-weight: bold;
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

        .section-total {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
            padding: 8px;
            margin: 10px 0;
            border-radius: 4px;
        }

        .no-data {
            text-align: center;
            padding: 20px;
            color: #999;
            font-style: italic;
            background-color: #f9f9f9;
            border: 1px dashed #ddd;
            margin: 10px 0;
        }

        .grand-total {
            background-color: #d4edda;
            border: 2px solid #155724;
            padding: 10px;
            margin: 15px 0;
            border-radius: 5px;
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1><?php echo e($title); ?></h1>
        <p>تاریخ چاپ: <?php echo e($print_date); ?></p>
        <p>تعداد کل مشتریان: <?php echo e($total_customers); ?> نفر</p>
        <p>مشتریان عادی: <?php echo e($total_normal_customers); ?> نفر | مشتریان کارت صرافی: <?php echo e($total_sarafi_card_customers); ?> نفر</p>
    </div>

    <!-- بخش مشتریان عادی -->
    <?php if(count($normal_reports) > 0): ?>
    <div class="section-header">جدول مشتریان عادی</div>
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
                <th width="80">مجموع (<?php echo e($source_currency); ?>)</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $normal_reports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
        </tbody>
    </table>

    <!-- خلاصه مشتریان عادی -->
    <?php if(count($normal_totals) > 0): ?>
    <div class="section-total">خلاصه مشتریان عادی</div>
    <table>
        <thead>
            <tr class="total-row">
                <th>نام ارز</th>
                <th>نقدی</th>
                <th>بانکی</th>
                <th>مجموع</th>
                <th>بیلانس به <?php echo e($source_currency); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
                $latestProfitRate = \App\Models\Sarafi\ProfitRate::latest()->first();
                $accountTypeForConversion = 'cash';
                $defaultRates = [
                    'afn' => 66.20,
                    'usd' => 1,
                    'irr' => 110000.00,
                    'eur' => 0.93,
                    'pkr' => 277.78,
                    'aed' => 3.67,
                    'try' => 32.26,
                    'cny' => 7.24,
                ];
                $exchangeRates = [];
                foreach ($defaultRates as $currency => $fallback) {
                    $column = $currency.'_buy_'.($accountTypeForConversion === 'bank' ? 'bank' : 'cash');
                    $exchangeRates[$currency] = ($latestProfitRate->$column ?? 0) > 0 ? $latestProfitRate->$column : $fallback;
                }
            ?>

            <?php $__currentLoopData = $currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currencyCode => $currencyName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $cash = $normal_totals[$currencyCode]['cash'] ?? 0;
                    $bank = $normal_totals[$currencyCode]['bank'] ?? 0;
                    $total = $normal_totals[$currencyCode]['total'] ?? 0;

                    // فقط ارزهایی که موجودی دارند
                    if ($total == 0) continue;

                    $totalUsd = isset($exchangeRates[$currencyCode]) && $exchangeRates[$currencyCode] > 0
                                ? $total / $exchangeRates[$currencyCode]
                                : 0;
                ?>
                <tr>
                    <td><?php echo e($currencyName); ?></td>
                    <td class="currency-number"><?php echo e(number_format($cash, 2)); ?></td>
                    <td class="currency-number"><?php echo e(number_format($bank, 2)); ?></td>
                    <td class="currency-number"><?php echo e(number_format($total, 2)); ?></td>
                    <td class="currency-number"><?php echo e(number_format($totalUsd, 2)); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <tr class="total-row">
                <td colspan="4">جمع کل مشتریان عادی به <?php echo e($source_currency); ?></td>
                <td class="currency-number"><?php echo e(number_format($total_normal_usd, 2)); ?></td>
            </tr>
        </tbody>
    </table>
    <?php endif; ?>
    <?php else: ?>
    <div class="no-data">هیچ داده‌ای برای مشتریان عادی وجود ندارد</div>
    <?php endif; ?>

    <!-- بخش مشتریان کارت صرافی -->
    <?php if(count($sarafi_card_reports) > 0): ?>
    <div class="section-header">جدول مشتریان کارت صرافی</div>
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
                <th width="80">مجموع (<?php echo e($source_currency); ?>)</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $sarafi_card_reports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
        </tbody>
    </table>

    <!-- خلاصه مشتریان کارت صرافی -->
    <?php if(count($sarafi_card_totals) > 0): ?>
    <div class="section-total">خلاصه مشتریان کارت صرافی</div>
    <table>
        <thead>
            <tr class="total-row">
                <th>نام ارز</th>
                <th>نقدی</th>
                <th>بانکی</th>
                <th>مجموع</th>
                <th>بیلانس به <?php echo e($source_currency); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
                $latestProfitRate = \App\Models\Sarafi\ProfitRate::latest()->first();
                $accountTypeForConversion = 'cash';
                $defaultRates = [
                    'afn' => 66.20,
                    'usd' => 1,
                    'irr' => 110000.00,
                    'eur' => 0.93,
                    'pkr' => 277.78,
                    'aed' => 3.67,
                    'try' => 32.26,
                    'cny' => 7.24,
                ];
                $exchangeRates = [];
                foreach ($defaultRates as $currency => $fallback) {
                    $column = $currency.'_buy_'.($accountTypeForConversion === 'bank' ? 'bank' : 'cash');
                    $exchangeRates[$currency] = ($latestProfitRate->$column ?? 0) > 0 ? $latestProfitRate->$column : $fallback;
                }
            ?>

            <?php $__currentLoopData = $currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currencyCode => $currencyName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $cash = $sarafi_card_totals[$currencyCode]['cash'] ?? 0;
                    $bank = $sarafi_card_totals[$currencyCode]['bank'] ?? 0;
                    $total = $sarafi_card_totals[$currencyCode]['total'] ?? 0;

                    // فقط ارزهایی که موجودی دارند
                    if ($total == 0) continue;

                    $totalUsd = isset($exchangeRates[$currencyCode]) && $exchangeRates[$currencyCode] > 0
                                ? $total / $exchangeRates[$currencyCode]
                                : 0;
                ?>
                <tr>
                    <td><?php echo e($currencyName); ?></td>
                    <td class="currency-number"><?php echo e(number_format($cash, 2)); ?></td>
                    <td class="currency-number"><?php echo e(number_format($bank, 2)); ?></td>
                    <td class="currency-number"><?php echo e(number_format($total, 2)); ?></td>
                    <td class="currency-number"><?php echo e(number_format($totalUsd, 2)); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <tr class="total-row">
                <td colspan="4">جمع کل مشتریان کارت صرافی به <?php echo e($source_currency); ?></td>
                <td class="currency-number"><?php echo e(number_format($total_sarafi_card_usd, 2)); ?></td>
            </tr>
        </tbody>
    </table>
    <?php endif; ?>
    <?php else: ?>
    <div class="no-data">هیچ داده‌ای برای مشتریان کارت صرافی وجود ندارد</div>
    <?php endif; ?>

  

</body>

</html><?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/pdf/Sarafi/customer-balance-report-separate.blade.php ENDPATH**/ ?>