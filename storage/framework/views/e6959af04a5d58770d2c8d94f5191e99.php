<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">
</head>

<body style="vertical-align:middle;">

    <div class="invoice">شرکت حبیب یونس لمتید
        <div>
            وارد کننده انواع پرزه جات TVS از قبیل پرزه جات موتر ، موتور سیکلت و سچرخ 
        </div>
        <table>
            <tr>
                <td>عمده</td>
                <td>پرچون</td>
            </tr>
        </table>
    </div>
   

    <table>
        <tr>
            <td>
                <?php if($sale->sale_type === 'wholesale' && $sale->customer): ?>
                     محترم: <?php echo e($sale->customer->name); ?>

                <?php endif; ?>
            </td>

            <td>
                &nbsp;&nbsp; شماره فاکتور: <?php echo e($sale->invoice_number); ?>


            </td>
            <td>
                تاریخ: <?php echo e(jdate($sale->created_at)->format('Y/m/d')); ?>


            </td>
        </tr>
    </table>




    <table style="
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
    background: url(<?php echo e(public_path('asset/logo.png')); ?>) center center no-repeat;
    background-size: 200px;
    border: 1px solid #000;
">
        <thead>
            <tr>
                <th>شماره</th>
                <th>نام جنس</th>
                <th>قیمت</th>
                <th>تعداد</th>
                <th>واحد</th>
                <th>مجموعه</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $sale->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($index + 1); ?></td>
                    <td><?php echo e($item->warehouse->name ?? '-'); ?></td>
                    <td><?php echo e(number_format($item->price_per_unit)); ?></td>
                    <td><?php echo e($item->quantity); ?></td>
                    <?php if($sale->sale_type === 'wholesale'): ?>
                    <td><?php echo e($item->warehouse->unit ?? '-'); ?></td>
                    <?php else: ?>
                        <td>عدد</td>
                    <?php endif; ?>
                    <td><?php echo e(number_format($item->total_price)); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <?php for($i = count($sale->items) + 1; $i <=15; $i++): ?>
                <tr>
                    <td><?php echo e($i); ?></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            <?php endfor; ?>
        </tbody>


    </table>

    <table>
        <tr>
            <td>
               مجموعه کل فاکتور : <?php echo e(number_format($sale->total_price)); ?>

                &nbsp;&nbsp;
            </td>
        

        </tr>
        <?php if($sale->sale_type === 'wholesale'): ?>
            <td class="label">مبلغ دریافت شده:</td>
            <td class="value"><?php echo e(number_format($sale->received_amount)); ?></td>


            <td class="label">باقیمانده:</td>
            <td class="value"><?php echo e(number_format($sale->remaining_amount)); ?></td>
        <?php endif; ?>
    </table>






    <div class="footer">
        آدرس: هرات - سی متره - باغ آزادی
        &nbsp; | &nbsp;
        شماره‌های تماس: 0796471633 - 0789495940 - 0700472377
    </div>

    <div style="height: calc(297mm - [مجموع ارتفاع محتوای شما]);"></div>

</body>

</html>
<?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/pdf/invoice.blade.php ENDPATH**/ ?>