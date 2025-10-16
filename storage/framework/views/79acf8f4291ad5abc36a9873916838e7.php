<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>گزارش قرضه‌ها</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; direction: rtl; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 6px; text-align: center; }
        th { background: #f0f0f0; }
        tfoot td { font-weight: bold; }
    </style>
</head>
<body>
    <h2 style="text-align:center;">💳 گزارش قرضه‌ها</h2>

    <?php if($customer_name || $type || $currency || $date): ?>
        <p>فیلتر شده بر اساس:</p>
        <ul>
            <?php if($customer_name): ?> <li>نام مشتری: <?php echo e($customer_name); ?></li> <?php endif; ?>
            <?php if($type): ?> <li>نوع تراکنش: <?php echo e($type); ?></li> <?php endif; ?>
            <?php if($currency): ?> <li>نوع ارز: <?php echo e($currency); ?></li> <?php endif; ?>
            <?php if($date): ?> <li>تاریخ: <?php echo e($date); ?></li> <?php endif; ?>
        </ul>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>تاریخ</th>
                <th>نوع</th>
                <th>ارز</th>
                <th>نام مشتری</th>
                <th>مبلغ قرضه</th>
                <th>رسید</th>
                <th>باقی‌مانده</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $totalsByCustomerCurrency = [];
            ?>

            <?php $__currentLoopData = $loans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $loan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $key = $loan['customer']['name'] . '_' . $loan['currency'];
                    if(!isset($totalsByCustomerCurrency[$key])){
                        $totalsByCustomerCurrency[$key] = [
                            'total_loan' => 0,
                            'total_receipt' => 0,
                            'balance' => 0
                        ];
                    }
                    $totalsByCustomerCurrency[$key]['total_loan'] += $loan['amount'];
                    $totalsByCustomerCurrency[$key]['total_receipt'] += $loan['loan_recipt'];
                    $totalsByCustomerCurrency[$key]['balance'] = $loan['reminded'];
                ?>
                <tr>
                    <td><?php echo e($index + 1); ?></td>
                    <td><?php echo e(\Morilog\Jalali\Jalalian::fromDateTime($loan['date'])->format('Y/m/d')); ?></td>
                    <td><?php echo e($loan['type']); ?></td>
                    <td><?php echo e($loan['currency']); ?></td>
                    <td><?php echo e($loan['customer']['name']); ?></td>
                    <td><?php echo e(number_format($loan['amount'])); ?></td>
                    <td><?php echo e(number_format($loan['loan_recipt'])); ?></td>
                    <td><?php echo e(number_format($loan['reminded'])); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
        <tfoot>
            <?php $__currentLoopData = $totalsByCustomerCurrency; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $totals): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td colspan="5">جمع برای <?php echo e(explode('_', $key)[0]); ?> (<?php echo e(explode('_', $key)[1]); ?>)</td>
                    <td><?php echo e(number_format($totals['total_loan'])); ?></td>
                    <td><?php echo e(number_format($totals['total_receipt'])); ?></td>
                    <td><?php echo e(number_format($totals['balance'])); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tfoot>
    </table>
</body>
</html>
<?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/exports/loans-pdf.blade.php ENDPATH**/ ?>