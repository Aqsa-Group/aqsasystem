<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8" />
    <style>
        body {
            font-family: 'scheherazade', serif;
            direction: rtl;
            text-align: right;
            font-size: 14pt;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <h2>معلومات دوکاندار</h2>
    <table>
        <tr><th>نام و نام فامیلی</th><td><?php echo e($shopkeeper->fullname); ?></td></tr>
        <tr><th>نام پدر</th><td><?php echo e($shopkeeper->father_name); ?></td></tr>
        <tr><th>ایمیل</th><td><?php echo e($shopkeeper->email ?? '-'); ?></td></tr>
        <tr><th>شماره تلفن</th><td><?php echo e($shopkeeper->phone); ?></td></tr>
        <tr><th>نمبر تذکره</th><td><?php echo e($shopkeeper->national_id); ?></td></tr>
        <tr><th>تاریخ شروع قرارداد</th><td><?php echo e($shopkeeper->contract_start); ?></td></tr>
        <tr><th>تاریخ ختم قرارداد</th><td><?php echo e($shopkeeper->contract_end); ?></td></tr>
        <tr><th>وضعیت</th><td><?php echo e($shopkeeper->state); ?></td></tr>
    </table>

    <h2>لیست دوکان‌ها</h2>
    <table>
        <thead>
            <tr>
                <th>مارکت</th>
                <th>شماره دوکان</th>
                <th>منزل</th>
                <th>اندازه</th>
                <th>شماره میتر</th>
                <th>نوعیت</th>
                <th>قیمت</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $shopkeeper->shops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($shop->market->name ?? '-'); ?></td>
                    <td><?php echo e($shop->number); ?></td>
                    <td><?php echo e($shop->floor); ?></td>
                    <td><?php echo e($shop->size); ?></td>
                    <td><?php echo e($shop->metar_serial); ?></td>
                    <td><?php echo e($shop->type); ?></td>
                    <td><?php echo e(number_format($shop->price)); ?> افغانی</td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</body>
</html>
<?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/exports/shopkeeper.blade.php ENDPATH**/ ?>