<!DOCTYPE html>
<html dir="rtl">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php echo e($reportTitle); ?></title>
    <style>
        /* استایل بسیار ساده */
        body {
            font-family: dejavusanscondensed, sans-serif;
            direction: rtl;
            margin: 0;
            padding: 10px;
            font-size: 9px;
            line-height: 1.2;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #333;
        }

        .header h1 {
            margin: 0;
            font-size: 14px;
            color: #333;
        }

        .summary {
            margin: 10px 0;
            padding: 8px;
            background: #f5f5f5;
            border-radius: 4px;
        }

        .summary div {
            display: inline-block;
            margin: 0 15px;
            text-align: center;
        }

        .summary .value {
            font-size: 12px;
            font-weight: bold;
            display: block;
        }

        .summary .label {
            font-size: 8px;
            color: #666;
        }

        .currency-summary {
            margin: 10px 0;
            padding: 8px;
            background: #e8f5e8;
            border-radius: 4px;
            border-right: 3px solid #4caf50;
        }

        .currency-summary h4 {
            margin: 0 0 5px 0;
            font-size: 10px;
            color: #2e7d32;
        }

        .currency-items {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .currency-item {
            background: white;
            padding: 4px 8px;
            border-radius: 3px;
            border: 1px solid #c8e6c9;
            font-size: 8px;
        }

        .currency-amount {
            font-weight: bold;
            color: #1b5e20;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 7px;
        }

        th {
            background: #333;
            color: white;
            padding: 4px 3px;
            border: 1px solid #555;
            text-align: center;
        }

        td {
            padding: 3px 2px;
            border: 1px solid #ddd;
            text-align: center;
        }

        tr:nth-child(even) {
            background: #f9f9f9;
        }

        .row-number {
            background: #e0e0e0;
            font-weight: bold;
            width: 25px;
        }

        .footer {
            margin-top: 15px;
            text-align: center;
            font-size: 7px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 5px;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1><?php echo e($reportTitle); ?></h1>
        <div>تاریخ تولید: <?php echo e(\Morilog\Jalali\Jalalian::now()->format('Y/m/d H:i')); ?></div>
    </div>

    <div class="summary">
        <div>
            <span class="value"><?php echo e(number_format($summary['total_count'])); ?></span>
            <span class="label">تعداد رکوردها</span>
        </div>
        <div>
            <span class="value"><?php echo e(number_format($summary['total_amount'])); ?></span>
            <span class="label">مجموع کل</span>
        </div>
    </div>

    <!-- نمایش مجموع مبالغ هر ارز -->
    <?php if(isset($summary['currency_totals']) && count($summary['currency_totals']) > 0): ?>
    <div class="currency-summary">
        <h4>📊 مجموع مبالغ بر اساس ارز:</h4>
        <div class="currency-items">
            <?php $__currentLoopData = $summary['currency_totals']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currency => $total): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="currency-item">
                <strong>
                    <?php switch($currency):
                        case ('AFN'): ?> افغانی <?php break; ?>
                        <?php case ('USD'): ?> دالر <?php break; ?>
                        <?php default: ?> <?php echo e($currency); ?>

                    <?php endswitch; ?>
                </strong>: 
                <span class="currency-amount"><?php echo e(number_format($total)); ?></span>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <?php endif; ?>

    <?php if($data && $data->count() > 0): ?>
    <table>
        <thead>
            <tr>
                <th class="row-number">#</th>
                <?php switch($reportType):
                case ('accounting'): ?>
                <th>مارکت</th>
                <th>نوع</th>
                <th>دوکاندار</th>
                <th>مصرف</th>
                <th>مبلغ</th>
                <th>واحد</th>
                <th>تاریخ</th>
                <th>وضعیت</th>
                <?php break; ?>
                <?php case ('outside'): ?>
                <th>مارکت</th>
                <th>نوع</th>
                <th>نام</th>
                <th>مبلغ</th>
                <th>واحد</th>
                <th>تاریخ</th>
                <th>توضیحات</th>
                <?php break; ?>
                <?php case ('deposit'): ?>
                <th>مارکت</th>
                <th>دوکاندار</th>
                <th>هزینه</th>
                <th>مبلغ کل</th>
                <th>پرداخت</th>
                <th>باقی</th>
                <th>واحد</th>
                <th>تاریخ</th>
                <?php break; ?>
                <?php case ('loan'): ?>
                <th>مارکت</th>
                <th>نوع</th>
                <th>نام</th>
                <th>مبلغ اصلی</th>
                <th>پرداخت</th>
                <th>باقی</th>
                <th>واحد</th>
                <th>تاریخ</th>
                <?php break; ?>
                <?php case ('payment'): ?>
                <th>کد</th>
                <th>مبلغ</th>
                <th>واحد</th>
                <th>تاریخ</th>
                <th>توضیحات</th>
                <?php break; ?>
                <?php case ('buy'): ?>
                <th>مارکت</th>
                <th>فروشنده</th>
                <th>نوع</th>
                <th>قیمت</th>
                <th>واحد</th>
                <th>تاریخ</th>
                <?php break; ?>
                <?php case ('sell'): ?>
                <th>مارکت</th>
                <th>مشتری</th>
                <th>نوع</th>
                <th>قیمت</th>
                <th>واحد</th>
                <th>تاریخ</th>
                <th>جزئیات</th>
                <?php break; ?>
                <?php case ('withdraw_log'): ?>
                <th>هزینه</th>
                <th>دریافت کننده</th>
                <th>مبلغ</th>
                <th>واحد</th>
                <th>توضیحات</th>
                <th>تاریخ</th>
                <?php break; ?>
                <?php case ('salary'): ?>
                <th>مارکت</th>
                <th>کارمند</th>
                <th>حقوق</th>
                <th>پرداخت شده</th>
                <th>باقی مانده</th>
                <th>قرضه</th>
                <th>واحد</th>
                <th>تاریخ</th>
                <th>وضعیت کسر</th>
                <?php break; ?>
                <?php endswitch; ?>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td class="row-number"><?php echo e($index + 1); ?></td>
                <?php switch($reportType):
                case ('accounting'): ?>
                <td><?php echo e($report->market->name ?? '-'); ?></td>
                <td><?php echo e($report->type); ?></td>
                <td><?php echo e($report->shopkeeper->fullname ?? '-'); ?></td>
                <td><?php echo e($report->expanses_type); ?></td>
                <td><?php echo e(number_format($report->price)); ?></td>
                <td>
                    <?php switch($report->currency):
                    case ('AFN'): ?> افغانی <?php break; ?>
                    <?php case ('USD'): ?> دالر <?php break; ?>
                    <?php default: ?> <?php echo e($report->currency); ?>

                    <?php endswitch; ?>
                </td>
                <td><?php echo e($report->paid_date ? \Morilog\Jalali\Jalalian::fromDateTime($report->paid_date)->format('Y/m/d') : '-'); ?></td>
                <td><?php echo e($report->cleared ? '✅' : '⏳'); ?></td>
                <?php break; ?>

                <?php case ('outside'): ?>
                <td><?php echo e($report->market->name ?? '-'); ?></td>
                <td>
                    <?php if($report->customer_id): ?> مشتری
                    <?php elseif($report->staff_id): ?> کارمند
                    <?php elseif($report->shopkeeper_id): ?> دوکاندار
                    <?php else: ?> نامشخص <?php endif; ?>
                </td>
                <td><?php echo e($report->customer->fullname ?? $report->staff->fullname ?? $report->shopkeeper->fullname ?? '-'); ?></td>
                <td><?php echo e(number_format($report->paid)); ?></td>
                <td>
                    <?php switch($report->currency):
                    case ('AFN'): ?> افغانی <?php break; ?>
                    <?php case ('USD'): ?> دالر <?php break; ?>
                    <?php default: ?> <?php echo e($report->currency); ?>

                    <?php endswitch; ?>
                </td>
                <td><?php echo e($report->date ? \Morilog\Jalali\Jalalian::fromDateTime($report->date)->format('Y/m/d') : '-'); ?></td>
                <td><?php echo e($report->description ?? '-'); ?></td>
                <?php break; ?>

                <?php case ('deposit'): ?>
                <td><?php echo e($report->accounting->market->name ?? '-'); ?></td>
                <td><?php echo e($report->accounting->shopkeeper->fullname ?? '-'); ?></td>
                <td><?php echo e($report->expanses_type); ?></td>
                <td><?php echo e(number_format($report->price)); ?></td>
                <td><?php echo e(number_format($report->paid)); ?></td>
                <td><?php echo e(number_format($report->remained)); ?></td>
                <td>
                    <?php switch($report->currency):
                    case ('AFN'): ?> افغانی <?php break; ?>
                    <?php case ('USD'): ?> دالر <?php break; ?>
                    <?php default: ?> <?php echo e($report->currency); ?>

                    <?php endswitch; ?>
                </td>
                <td><?php echo e($report->paid_date ? \Morilog\Jalali\Jalalian::fromDateTime($report->paid_date)->format('Y/m/d') : '-'); ?></td>
                <?php break; ?>

                <?php case ('loan'): ?>
                <td><?php echo e($report->market->name ?? '-'); ?></td>
                <td><?php echo e($report->person); ?></td>
                <td>
                    <?php if($report->person === 'مشتری' && $report->customer): ?>
                    <?php echo e($report->customer->fullname); ?>

                    <?php elseif($report->person === 'دوکاندار' && $report->shopkeeper): ?>
                    <?php echo e($report->shopkeeper->fullname); ?>

                    <?php elseif($report->person === 'کارمند' && $report->staff): ?>
                    <?php echo e($report->staff->fullname); ?>

                    <?php else: ?> - <?php endif; ?>
                </td>
                <td><?php echo e(number_format($report->amount)); ?></td>
                <td><?php echo e(number_format($report->totalPaid())); ?></td>
                <td><?php echo e(number_format($report->remainingAmount())); ?></td>
                <td>
                    <?php switch($report->currency):
                    case ('AFN'): ?> افغانی <?php break; ?>
                    <?php case ('USD'): ?> دالر <?php break; ?>
                    <?php default: ?> <?php echo e($report->currency); ?>

                    <?php endswitch; ?>
                </td>
                <td><?php echo e($report->date ? \Morilog\Jalali\Jalalian::fromDateTime($report->date)->format('Y/m/d') : '-'); ?></td>
                <?php break; ?>

                <?php case ('payment'): ?>
                <td>#<?php echo e($report->loan_id); ?></td>
                <td><?php echo e(number_format($report->amount)); ?></td>
                <td>
                    <?php switch($report->currency):
                    case ('AFN'): ?> افغانی <?php break; ?>
                    <?php case ('USD'): ?> دالر <?php break; ?>
                    <?php default: ?> <?php echo e($report->currency); ?>

                    <?php endswitch; ?>
                </td>
                <td><?php echo e($report->date ? \Morilog\Jalali\Jalalian::fromDateTime($report->date)->format('Y/m/d') : '-'); ?></td>
                <td><?php echo e($report->description ?? '-'); ?></td>
                <?php break; ?>

                <?php case ('buy'): ?>
                <td><?php echo e($report->market->name ?? '-'); ?></td>
                <td><?php echo e($report->customer->fullname ?? '-'); ?></td>
                <td><?php echo e($report->property); ?></td>
                <td><?php echo e(number_format($report->price)); ?></td>
                <td>
                    <?php switch($report->currency):
                    case ('AFN'): ?> افغانی <?php break; ?>
                    <?php case ('USD'): ?> دالر <?php break; ?> 
                    <?php default: ?> <?php echo e($report->currency); ?>

                    <?php endswitch; ?>
                </td>
                <td><?php echo e($report->created_at ? \Morilog\Jalali\Jalalian::fromDateTime($report->created_at)->format('Y/m/d') : '-'); ?></td>
                <?php break; ?>

                <?php case ('sell'): ?>
                <td><?php echo e($report->market->name ?? '-'); ?></td>
                <td><?php echo e($report->customer->fullname ?? '-'); ?></td>
                <td><?php echo e($report->property); ?></td>
                <td><?php echo e(number_format($report->price)); ?></td>
                <td>
                    <?php switch($report->currency):
                    case ('AFN'): ?> افغانی <?php break; ?>
                    <?php case ('USD'): ?> دالر <?php break; ?>
                    <?php default: ?> <?php echo e($report->currency); ?>

                    <?php endswitch; ?>
                </td>
                <td><?php echo e($report->date ? \Morilog\Jalali\Jalalian::fromDateTime($report->date)->format('Y/m/d') : '-'); ?></td>
                <td><?php echo e($report->details ?? '-'); ?></td>
                <?php break; ?>

                <?php case ('withdraw_log'): ?>
                <td><?php echo e($report->expanses_type); ?></td>
                <td><?php echo e($report->recipient_name); ?></td>
                <td><?php echo e(number_format($report->amount)); ?></td>
                <td>
                    <?php switch($report->currency):
                    case ('AFN'): ?> افغانی <?php break; ?>
                    <?php case ('USD'): ?> دالر <?php break; ?>
                    <?php default: ?> <?php echo e($report->currency); ?>

                    <?php endswitch; ?>
                </td>
                <td><?php echo e($report->description ?? '-'); ?></td>
                <td><?php echo e($report->created_at ? \Morilog\Jalali\Jalalian::fromDateTime($report->created_at)->format('Y/m/d') : '-'); ?></td>
                <?php break; ?>

                <?php case ('salary'): ?>
                <td><?php echo e($report->market->name ?? '-'); ?></td>
                <td><?php echo e($report->staff->fullname ?? '-'); ?></td>
                <td><?php echo e(number_format($report->salary)); ?></td>
                <td><?php echo e(number_format($report->paid)); ?></td>
                <td><?php echo e(number_format($report->remained)); ?></td>
                <td><?php echo e(number_format($report->loan)); ?></td>
                <td>
                    <?php switch($report->currency):
                    case ('AFN'): ?> افغانی <?php break; ?>
                    <?php case ('USD'): ?> دالر <?php break; ?>
                    <?php default: ?> <?php echo e($report->currency); ?>

                    <?php endswitch; ?>
                </td>
                <td><?php echo e($report->paid_date ? \Morilog\Jalali\Jalalian::fromDateTime($report->paid_date)->format('Y/m/d') : '-'); ?></td>
                <td><?php echo e($report->is_reduce ? 'فعال' : 'غیرفعال'); ?></td>
                <?php break; ?>

                <?php endswitch; ?>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
    <?php else: ?>
    <div class="no-data">
        <h3>داده‌ای برای نمایش وجود ندارد</h3>
        <p>هیچ رکوردی با فیلترهای اعمال شده مطابقت ندارد.</p>
    </div>
    <?php endif; ?>

    <div class="footer">
        <div>سیستم گزارش‌گیری جامع</div>
        <div>تعداد: <?php echo e(number_format($summary['total_count'])); ?> | مجموع کل: <?php echo e(number_format($summary['total_amount'])); ?></div>
        <?php if(isset($summary['currency_totals']) && count($summary['currency_totals']) > 0): ?>
        <div style="margin-top: 3px;">
            <?php $__currentLoopData = $summary['currency_totals']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currency => $total): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <span style="margin: 0 5px;">
                <?php echo e($currency); ?>: <?php echo e(number_format($total)); ?>

            </span>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php endif; ?>
    </div>
</body>
</html><?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/exports/general-report-pdf.blade.php ENDPATH**/ ?>