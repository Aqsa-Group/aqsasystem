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
                case ('salary'): ?>
                <th>مبلغ</th>
                <th>واحد</th>
                <th>تاریخ</th>
                <th>توضیحات</th>
                <?php break; ?>
                <?php case ('withdrawal'): ?>
                <th>نوع</th>
                <th>مبلغ</th>
                <th>واحد</th>
                <th>تاریخ</th>
                <th>توضیحات</th>
                <?php break; ?>
                <?php case ('inventory'): ?>
                <th>بارکد</th>
                <th>نام محصول</th>
                <th>دسته‌بندی</th>
                <th>موجودی</th>
                <th>قیمت خرید</th>
                <th>قیمت فروش</th>
                <th>وضعیت</th>
                <?php break; ?>
                <?php case ('warehouse'): ?>
                <th>بارکد</th>
                <th>نام محصول</th>
                <th>دسته‌بندی</th>
                <th>موجودی</th>
                <th>قیمت خرید</th>
                <th>قیمت فروش</th>
                <th>وضعیت</th>
                <?php break; ?>
                <?php case ('sale'): ?>
                <th>شماره فاکتور</th>
                <th>نوع فروش</th>
                <th>خریدار</th>
                <th>قیمت کل</th>
                <th>دریافتی</th>
                <th>باقی‌مانده</th>
                <th>سود</th>
                <th>تاریخ</th>
                <?php break; ?>
                <?php case ('sale_items'): ?>
                <th>شماره فاکتور</th>
                <th>محصول</th>
                <th>تعداد</th>
                <th>قیمت واحد</th>
                <th>قیمت کل</th>
                <th>سود</th>
                <th>تاریخ</th>
                <?php break; ?>
                <?php case ('loan'): ?>
                <th>نوع</th>
                <th>مبلغ</th>
                <th>واحد</th>
                <th>تاریخ</th>
                <th>توضیحات</th>
                <?php break; ?>
                <?php case ('inventory_history'): ?>
                <th>محصول</th>
                <th>نوع</th>
                <th>تعداد تغییر</th>
                <th>موجودی قبلی</th>
                <th>موجودی جدید</th>
                <th>قیمت واحد</th>
                <th>مبلغ کل</th>
                <th>تاریخ</th>
                <?php break; ?>
                <?php case ('warehouse_history'): ?>
                <th>محصول</th>
                <th>نوع</th>
                <th>تعداد تغییر</th>
                <th>موجودی قبلی</th>
                <th>موجودی جدید</th>
                <th>قیمت واحد</th>
                <th>مبلغ کل</th>
                <th>تاریخ</th>
                <?php break; ?>
                <?php endswitch; ?>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td class="row-number"><?php echo e($index + 1); ?></td>
                <?php switch($reportType):
                case ('salary'): ?>
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

                <?php case ('withdrawal'): ?>
                <td><?php echo e($report->type); ?></td>
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

                <?php case ('inventory'): ?>
                <td><?php echo e($report->barcode); ?></td>
                <td><?php echo e($report->product_name); ?></td>
                <td><?php echo e($report->category ?? '-'); ?></td>
                <td><?php echo e(number_format($report->total_quantity)); ?></td>
                <td><?php echo e(number_format($report->purchase_price_per_unit)); ?></td>
                <td><?php echo e(number_format($report->retail_price)); ?></td>
                <td><?php echo e($report->status); ?></td>
                <?php break; ?>

                <?php case ('warehouse'): ?>
                <td><?php echo e($report->barcode); ?></td>
                <td><?php echo e($report->product_name); ?></td>
                <td><?php echo e($report->category ?? '-'); ?></td>
                <td><?php echo e(number_format($report->total_quantity)); ?></td>
                <td><?php echo e(number_format($report->purchase_price_per_unit)); ?></td>
                <td><?php echo e(number_format($report->retail_price)); ?></td>
                <td><?php echo e($report->status); ?></td>
                <?php break; ?>

                <?php case ('sale'): ?>
                <td><?php echo e($report->invoice_number ?? '-'); ?></td>
                <td><?php echo e($report->sale_type === 'retail' ? 'خرده' : 'عمده'); ?></td>
                <td><?php echo e($report->buyer_name ?? '-'); ?></td>
                <td><?php echo e(number_format($report->total_price)); ?></td>
                <td><?php echo e(number_format($report->received_amount)); ?></td>
                <td><?php echo e(number_format($report->remaining_amount)); ?></td>
                <td><?php echo e(number_format($report->final_profit)); ?></td>
                <td><?php echo e($report->created_at ? \Morilog\Jalali\Jalalian::fromDateTime($report->created_at)->format('Y/m/d') : '-'); ?></td>
                <?php break; ?>

                <?php case ('sale_items'): ?>
                <td><?php echo e($report->sale->invoice_number ?? '-'); ?></td>
                <td><?php echo e($report->warehouse->product_name ?? '-'); ?></td>
                <td><?php echo e(number_format($report->quantity)); ?></td>
                <td><?php echo e(number_format($report->price_per_unit)); ?></td>
                <td><?php echo e(number_format($report->total_price)); ?></td>
                <td><?php echo e(number_format($report->profit)); ?></td>
                <td><?php echo e($report->created_at ? \Morilog\Jalali\Jalalian::fromDateTime($report->created_at)->format('Y/m/d') : '-'); ?></td>
                <?php break; ?>

                <?php case ('loan'): ?>
                <td><?php echo e($report->type); ?></td>
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

                <?php case ('inventory_history'): ?>
                <td><?php echo e($report->inventory->product_name ?? '-'); ?></td>
                <td><?php echo e($report->type); ?></td>
                <td><?php echo e(number_format($report->quantity_change)); ?></td>
                <td><?php echo e(number_format($report->previous_quantity)); ?></td>
                <td><?php echo e(number_format($report->new_quantity)); ?></td>
                <td><?php echo e(number_format($report->unit_price ?? 0)); ?></td>
                <td><?php echo e(number_format($report->total_amount ?? 0)); ?></td>
                <td><?php echo e($report->created_at ? \Morilog\Jalali\Jalalian::fromDateTime($report->created_at)->format('Y/m/d') : '-'); ?></td>
                <?php break; ?>

                <?php case ('warehouse_history'): ?>
                <td><?php echo e($report->warehouse->product_name ?? '-'); ?></td>
                <td><?php echo e($report->type); ?></td>
                <td><?php echo e(number_format($report->quantity_change)); ?></td>
                <td><?php echo e(number_format($report->previous_quantity)); ?></td>
                <td><?php echo e(number_format($report->new_quantity)); ?></td>
                <td><?php echo e(number_format($report->unit_price ?? 0)); ?></td>
                <td><?php echo e(number_format($report->total_amount ?? 0)); ?></td>
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
</html><?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/exports/tools/general-report-pdf.blade.php ENDPATH**/ ?>