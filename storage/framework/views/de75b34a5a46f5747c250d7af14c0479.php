<!DOCTYPE html>
<html dir="rtl">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php echo e($reportTitle); ?></title>
    <style>
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

    <?php if($data && $data->count() > 0): ?>
    <table>
        <thead>
            <tr>
                <th class="row-number">#</th>
                <?php switch($reportType):
                    case ('withdraw_log'): ?>
                        <th>نوع</th>
                        <th>کارمند</th>
                        <th>مبلغ</th>
                        <th>واحد پول</th>
                        <th>توضیحات</th>
                        <th>تاریخ ثبت</th>
                    <?php break; ?>

                    <?php case ('loan'): ?>
                        <th>نوع</th>
                        <th>مشتری</th>
                        <th>مبلغ اصلی</th>
                        <th>رسید</th>
                        <th>باقی مانده</th>
                        <th>برند</th>
                        <th>واحد پول</th>
                        <th>تاریخ</th>
                    <?php break; ?>

                    <?php case ('sell'): ?>
                        <th>شماره فاکتور</th>
                        <th>نوع فروش</th>
                        <th>مشتری</th>
                        <th>قیمت کل</th>
                        <th>تخفیف</th>
                        <th>تاریخ ثبت</th>
                    <?php break; ?>

                    <?php case ('buy'): ?>
                        <th>بارکد</th>
                        <th>نام کالا</th>
                        <th>شرکت</th>
                        <th>قیمت کل</th>
                        <th>واحد پول</th>
                        <th>تعداد</th>
                        <th>تاریخ واردات</th>
                    <?php break; ?>

                    <?php case ('transaction'): ?>
                        <th>نوع</th>
                        <th>شخص</th>
                        <th>نام شخص</th>
                        <th>مبلغ</th>
                        <th>واحد پول</th>
                        <th>تاریخ تراکنش</th>
                    <?php break; ?>

                    <?php case ('company_payment'): ?>
                        <th>شرکت</th>
                        <th>واحد پول</th>
                        <th>کل بدهی</th>
                        <th>پرداخت شده</th>
                        <th>باقی مانده</th>
                        <th>تاریخ ثبت</th>
                    <?php break; ?>
                <?php endswitch; ?>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td class="row-number"><?php echo e($index + 1); ?></td>
                <?php switch($reportType):
                    case ('withdraw_log'): ?>
                        <td>
                            <?php
                                $typeTranslations = [
                                    'electricity' => 'برق',
                                    'rent' => 'کرایه',
                                    'water' => 'مالیه', 
                                    'food' => 'غذا',
                                    'salary' => 'معاش کارمند',
                                    'transportation' => 'بارچلانی چین',
                                    'other' => 'متفرقه',
                                ];
                            ?>
                            <?php echo e($typeTranslations[$report->type] ?? $report->type); ?>

                        </td>
                        <td><?php echo e($report->staff->fullname ?? '-'); ?></td>
                        <td><?php echo e(number_format($report->amount)); ?></td>
                        <td><?php echo e($report->currency); ?></td>
                        <td><?php echo e($report->description ?? '-'); ?></td>
                        <td><?php echo e($report->created_at ? \Morilog\Jalali\Jalalian::fromDateTime($report->created_at)->format('Y/m/d') : '-'); ?></td>
                    <?php break; ?>

                    <?php case ('loan'): ?>
                        <td><?php echo e($report->type); ?></td>
                        <td><?php echo e($report->customer->fullname ?? '-'); ?></td>
                        <td><?php echo e(number_format($report->amount)); ?></td>
                        <td><?php echo e(number_format($report->loan_recipt ?? 0)); ?></td>
                        <td><?php echo e(number_format($report->reminded ?? 0)); ?></td>
                        <td><?php echo e($report->brand ?? '-'); ?></td>
                        <td><?php echo e($report->currency); ?></td>
                        <td><?php echo e($report->date ? \Morilog\Jalali\Jalalian::fromDateTime($report->date)->format('Y/m/d') : '-'); ?></td>
                    <?php break; ?>

                    <?php case ('sell'): ?>
                        <td><?php echo e($report->invoice_number ?? '-'); ?></td>
                        <td><?php echo e($report->sale_type); ?></td>
                        <td><?php echo e($report->customer->fullname ?? '-'); ?></td>
                        <td><?php echo e(number_format($report->total_price ?? $report->price)); ?></td>
                        <td><?php echo e(number_format($report->discount ?? 0)); ?></td>
                        <td><?php echo e($report->created_at ? \Morilog\Jalali\Jalalian::fromDateTime($report->created_at)->format('Y/m/d') : '-'); ?></td>
                    <?php break; ?>

                    <?php case ('buy'): ?>
                        <td><?php echo e($report->barcode ?? '-'); ?></td>
                        <td><?php echo e($report->name ?? '-'); ?></td>
                        <td><?php echo e($report->company->name ?? '-'); ?></td>
                        <td><?php echo e(number_format($report->total_price ?? $report->price)); ?></td>
                        <td><?php echo e($report->currency); ?></td>
                        <td><?php echo e(number_format($report->all_exist_number ?? 0)); ?></td>
                        <td><?php echo e($report->import_date ? \Morilog\Jalali\Jalalian::fromDateTime($report->import_date)->format('Y/m/d') : '-'); ?></td>
                    <?php break; ?>

                    <?php case ('transaction'): ?>
                        <td><?php echo e($report->type); ?></td>
                        <td>
                            <?php if($report->customer_id): ?> مشتری
                            <?php elseif($report->staff_id): ?> کارمند
                            <?php elseif($report->sarafi_id): ?> صرافی
                            <?php else: ?> دوکان <?php endif; ?>
                        </td>
                        <td><?php echo e($report->customer->fullname ?? $report->staff->fullname ?? $report->sarafi->name ?? '-'); ?></td>
                        <td><?php echo e(number_format($report->amount)); ?></td>
                        <td><?php echo e($report->currency); ?></td>
                        <td><?php echo e($report->created_at ? \Morilog\Jalali\Jalalian::fromDateTime($report->created_at)->format('Y/m/d') : '-'); ?></td>
                    <?php break; ?>

                    <?php case ('company_payment'): ?>
                        <td><?php echo e($report->company->name ?? '-'); ?></td>
                        <td><?php echo e($report->currency); ?></td>
                        <td><?php echo e(number_format($report->total_debt ?? 0)); ?></td>
                        <td><?php echo e(number_format($report->paid_amount ?? 0)); ?></td>
                        <td><?php echo e(number_format($report->remaining ?? 0)); ?></td>
                        <td><?php echo e($report->created_at ? \Morilog\Jalali\Jalalian::fromDateTime($report->created_at)->format('Y/m/d') : '-'); ?></td>
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
        <div>سیستم گزارش‌گیری جامع - Tools</div>
        <div>تعداد: <?php echo e(number_format($summary['total_count'])); ?> | مجموع کل: <?php echo e(number_format($summary['total_amount'])); ?></div>
    </div>
</body>
</html><?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/exports/Import/general-report-pdf.blade.php ENDPATH**/ ?>