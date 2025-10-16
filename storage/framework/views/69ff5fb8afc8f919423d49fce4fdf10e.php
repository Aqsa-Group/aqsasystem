<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>گزارش قرضه‌ها</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; direction: rtl; text-align: right; }
        h2 { text-align: center; }
        table { width: 100%; border-collapse: collapse; font-size: 12px; margin-top: 15px; }
        th, td { border: 1px solid #444; padding: 6px; }
        th { background: #eee; }
    </style>
</head>
<body>
    <h2>💳 گزارش قرضه‌ها</h2>

  <table style="width: 100%; border-collapse: collapse; font-size: 11px;">
    <thead>
        <tr>
            <th>#</th>
            <th>تاریخ</th>
            <th>نوع</th>
            <th>ارز</th>
            <th>مشتری</th>
            <th>مبلغ</th>
            <th>رسید</th>
            <th>باقی‌مانده</th>
            <th>ثبت شده</th>
        </tr>
    </thead>
    <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $loans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $loan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><?php echo e($index + 1); ?></td>
                <td><?php echo e($loan['date']); ?></td>
                <td><?php echo e($loan['type']); ?></td>
                <td><?php echo e($loan['currency']); ?></td>
                <td><?php echo e($loan['customer_name']); ?></td>
                <td style="color:blue; font-weight:bold"><?php echo e($loan['amount']); ?></td>
                <td style="color:green; font-weight:bold"><?php echo e($loan['loan_recipt']); ?></td>
                <td style="color:red; font-weight:bold"><?php echo e($loan['reminded']); ?></td>
                <td><?php echo e($loan['created_at']); ?></td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="9" style="text-align:center">هیچ قرضه‌ای ثبت نشده است.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

</body>
</html>
<?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/pdf/loans.blade.php ENDPATH**/ ?>