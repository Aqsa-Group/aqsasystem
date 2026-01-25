<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title><?php echo e($printData['title']); ?></title>
    <style>
        @font-face {
            font-family: 'Vazir';
            src: url(<?php echo e(storage_path('fonts/vazir.ttf')); ?>) format('truetype');
            font-weight: normal;
            font-style: normal;
        }
        
        * {
            font-family: 'Vazir', 'DejaVu Sans', sans-serif;
            box-sizing: border-box;
        }
        
        body {
            margin: 0;
            padding: 0;
            direction: rtl;
            font-size: 10pt;
            line-height: 1.4;
            color: #000;
        }
        
        .page {
            width: 21cm;
            min-height: 29.7cm;
            padding: 1.5cm;
            margin: 0 auto;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #000;
        }
        
        .header h1 {
            font-size: 18pt;
            margin: 0 0 10px 0;
            font-weight: bold;
        }
        
        .header p {
            margin: 5px 0;
            font-size: 11pt;
        }
        
        .info-section {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
        }
        
        .info-section h3 {
            margin: 0 0 10px 0;
            font-size: 12pt;
            font-weight: bold;
        }
        
        .filters-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 8px;
        }
        
        .filter-item {
            display: flex;
            justify-content: space-between;
            padding: 3px 0;
        }
        
        .filter-label {
            font-weight: bold;
            color: #333;
        }
        
        .filter-value {
            color: #666;
        }
        
        .transactions-section {
            margin-bottom: 25px;
        }
        
        .section-title {
            font-size: 13pt;
            font-weight: bold;
            text-align: center;
            margin: 15px 0 10px 0;
            padding-bottom: 5px;
            border-bottom: 1px solid #ccc;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
            font-size: 9pt;
            page-break-inside: auto;
        }
        
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
            padding: 8px 4px;
            border: 1px solid #000;
        }
        
        td {
            padding: 6px 4px;
            border: 1px solid #000;
            text-align: center;
            vertical-align: middle;
        }
        
        .text-left {
            text-align: right !important;
        }
        
        .text-center {
            text-align: center !important;
        }
        
        .text-right {
            text-align: left !important;
        }
        
        .positive {
            color: #006400 !important;
            font-weight: bold;
        }
        
        .negative {
            color: #8B0000 !important;
            font-weight: bold;
        }
        
        .number-cell {
            font-family: 'Arial', sans-serif;
            direction: ltr;
            text-align: center;
        }
        
        .summary-section {
            margin-top: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
        }
        
        .summary-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #ccc;
            text-align: center;
            font-size: 9pt;
            color: #666;
        }
        
        .col-no { width: 30px; }
        .col-date { width: 80px; }
        .col-type { width: 60px; }
        .col-currency { width: 70px; }
        .col-amount { width: 90px; }
        .col-account { width: 60px; }
        .col-desc { width: 150px; }
        
        /* برای جدول خلاصه */
        .col-sarafi { width: 120px; }
        .col-balance { width: 80px; }
        .col-total { width: 100px; }
        
        /* جلوگیری از شکستن ردیف‌ها */
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
        
        @page {
            size: A4 portrait;
            margin: 1.5cm;
        }
        
        @media print {
            .page {
                width: 100%;
                min-height: 0;
                padding: 0;
                margin: 0;
            }
            
            .no-print {
                display: none !important;
            }
            
            body {
                padding: 0.5cm;
            }
        }
    </style>
</head>
<body>
    <div class="page">
        <!-- هدر گزارش -->
        <div class="header">
            <h1><?php echo e($printData['title']); ?></h1>
            <p>تاریخ چاپ: <?php echo e($printData['print_date']); ?></p>
            <p>صرافی جاری: <?php echo e($printData['current_sarafi']); ?></p>
        </div>
        
        <!-- اطلاعات فیلترها -->
        <div class="info-section">
            <h3>فیلترهای اعمال شده:</h3>
            <div class="filters-grid">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $printData['filters']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="filter-item">
                    <span class="filter-label"><?php echo e($key); ?>:</span>
                    <span class="filter-value"><?php echo e($value); ?></span>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
        
        <!-- اگر فقط یک صرافی انتخاب شده باشد، جزئیات تراکنش‌ها -->
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($printData['reports']) === 1): ?>
        <div class="transactions-section">
            <div class="section-title">
                جزئیات معاملات با <?php echo e($printData['reports'][0]['sarafi_name']); ?>

            </div>
            
            <table>
                <thead>
                    <tr>
                        <th class="col-no">#</th>
                        <th class="col-date">تاریخ</th>
                        <th class="col-type">نوع</th>
                        <th class="col-currency">ارز</th>
                        <th class="col-amount">مبلغ</th>
                        <th class="col-account">نوع حساب</th>
                        <th class="col-desc">توضیحات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($printData['reports'][0]['transactions']) && count($printData['reports'][0]['transactions']) > 0): ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $printData['reports'][0]['transactions']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="col-no text-center"><?php echo e($index + 1); ?></td>
                            <td class="col-date text-center"><?php echo e($transaction['date']); ?></td>
                            <td class="col-type text-center <?php echo e($transaction['type'] === 'ارسال' ? 'negative' : 'positive'); ?>">
                                <?php echo e($transaction['type']); ?>

                            </td>
                            <td class="col-currency text-center"><?php echo e($transaction['currency_name']); ?></td>
                            <td class="col-amount number-cell <?php echo e($transaction['type'] === 'ارسال' ? 'negative' : 'positive'); ?>">
                                <?php echo e(number_format($transaction['amount'], 2)); ?>

                            </td>
                            <td class="col-account text-center"><?php echo e($transaction['account_type'] ?? '-'); ?></td>
                            <td class="col-desc text-right"><?php echo e($transaction['description'] ?? '-'); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">هیچ تراکنشی یافت نشد</td>
                        </tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        
        <!-- جدول خلاصه موجودی‌ها -->
        <div class="summary-section">
            <div class="section-title">
                خلاصه موجودی‌ها
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th class="col-no">#</th>
                        <th class="col-sarafi">صرافی</th>
                        <th class="col-balance">دالر</th>
                        <th class="col-balance">افغانی</th>
                        <th class="col-balance">تومان</th>
                        <th class="col-balance">کلدار</th>
                        <th class="col-balance">یورو</th>
                        <th class="col-balance">درهم</th>
                        <th class="col-balance">لیره</th>
                        <th class="col-balance">یوان</th>
                        <th class="col-total">مجموع به دالر</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(count($printData['reports']) > 0): ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $printData['reports']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="col-no text-center"><?php echo e($index + 1); ?></td>
                            <td class="col-sarafi text-center"><?php echo e($report['sarafi_name']); ?></td>
                            <td class="col-balance number-cell <?php echo e(($report['balances']['usd'] ?? 0) < 0 ? 'negative' : (($report['balances']['usd'] ?? 0) > 0 ? 'positive' : '')); ?>">
                                <?php echo e(number_format($report['balances']['usd'] ?? 0, 2)); ?>

                            </td>
                            <td class="col-balance number-cell <?php echo e(($report['balances']['afn'] ?? 0) < 0 ? 'negative' : (($report['balances']['afn'] ?? 0) > 0 ? 'positive' : '')); ?>">
                                <?php echo e(number_format($report['balances']['afn'] ?? 0, 2)); ?>

                            </td>
                            <td class="col-balance number-cell <?php echo e(($report['balances']['irr'] ?? 0) < 0 ? 'negative' : (($report['balances']['irr'] ?? 0) > 0 ? 'positive' : '')); ?>">
                                <?php echo e(number_format($report['balances']['irr'] ?? 0, 2)); ?>

                            </td>
                            <td class="col-balance number-cell <?php echo e(($report['balances']['pkr'] ?? 0) < 0 ? 'negative' : (($report['balances']['pkr'] ?? 0) > 0 ? 'positive' : '')); ?>">
                                <?php echo e(number_format($report['balances']['pkr'] ?? 0, 2)); ?>

                            </td>
                            <td class="col-balance number-cell <?php echo e(($report['balances']['eur'] ?? 0) < 0 ? 'negative' : (($report['balances']['eur'] ?? 0) > 0 ? 'positive' : '')); ?>">
                                <?php echo e(number_format($report['balances']['eur'] ?? 0, 2)); ?>

                            </td>
                            <td class="col-balance number-cell <?php echo e(($report['balances']['aed'] ?? 0) < 0 ? 'negative' : (($report['balances']['aed'] ?? 0) > 0 ? 'positive' : '')); ?>">
                                <?php echo e(number_format($report['balances']['aed'] ?? 0, 2)); ?>

                            </td>
                            <td class="col-balance number-cell <?php echo e(($report['balances']['try'] ?? 0) < 0 ? 'negative' : (($report['balances']['try'] ?? 0) > 0 ? 'positive' : '')); ?>">
                                <?php echo e(number_format($report['balances']['try'] ?? 0, 2)); ?>

                            </td>
                            <td class="col-balance number-cell <?php echo e(($report['balances']['cny'] ?? 0) < 0 ? 'negative' : (($report['balances']['cny'] ?? 0) > 0 ? 'positive' : '')); ?>">
                                <?php echo e(number_format($report['balances']['cny'] ?? 0, 2)); ?>

                            </td>
                            <td class="col-total number-cell <?php echo e($report['total_balance'] < 0 ? 'negative' : ($report['total_balance'] > 0 ? 'positive' : '')); ?>">
                                <?php echo e(number_format($report['total_balance'], 2)); ?>

                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="11" class="text-center">هیچ داده‌ای یافت نشد</td>
                        </tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
            
            <!-- جمع کل -->
            <div class="summary-info">
                <div>
                    <strong>تعداد صرافی‌ها:</strong> <?php echo e($printData['total_sarafis']); ?>

                </div>
                <div>
                    <strong>مجموع کل بیلانس:</strong>
                    <span class="<?php echo e($printData['total_balance'] < 0 ? 'negative' : ($printData['total_balance'] > 0 ? 'positive' : '')); ?>">
                        <?php echo e(number_format($printData['total_balance'], 2)); ?> دالر
                    </span>
                </div>
            </div>
        </div>
        
        <!-- فوتر -->
        <div class="footer">
            <p>این گزارش توسط سیستم صرافی‌ها تولید شده است</p>
            <p>تاریخ تولید: <?php echo e($printData['print_date']); ?></p>
        </div>
    </div>
</body>
</html><?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/pdf/Sarafi/sarafi-reports.blade.php ENDPATH**/ ?>