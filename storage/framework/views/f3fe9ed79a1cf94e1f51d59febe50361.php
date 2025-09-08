<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>چاپ رسید حسابداری</title>
    <style>
        body {
            font-family: 'amiri', serif;
            font-size: 12px;
            direction: rtl;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
            padding-bottom: 5px;
            color: #1e3a8a;
        }

        .main {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }

        th {
            background: #d6d8db;
            font-weight: bold;
            border: 1px solid #000;
            padding: 4px;
            text-align: center;
        }

        td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
        }

        .sign {
            text-align: center;
            margin-top: 50px;
        }

        .sign th {
            border: none;
            padding: 25px;
            background-color: white;
        }
    </style>
</head>

<body>

    <div style="width: 100%; direction: rtl; font-family: 'DejaVu Sans'; font-size: 12pt;">
        <table style="width: 100%; border-collapse: collapse; border: none;">
            <tr>
                <!-- Right: Persian Title -->
                <td style="width: 40%; text-align: right; vertical-align: middle; color: #1e3a8a; border: none;">
                    <strong style="font-size: 20pt;">مجتمع تجارتی عادلیار</strong>
                </td>

                <!-- Center: Logo -->
                <td style="width: 33.33%; text-align: center; border: none;">
                    <img src="<?php echo e(public_path('assets/logo.png')); ?>" alt="لوگو" style="height: 80px; width: 90px;" />
                </td>

                <!-- Left: English Title -->
                <td style="width: 33.33%; text-align: left; vertical-align: middle; color: #1e3a8a; border: none;">
                    <div style="line-height: 1.2;">
                        <div style="font-weight: bold; font-size: 18pt;">ADELYAR</div>
                        <div style="font-weight: bold; font-size: 10pt;">COMMERCIAL COMPLEX</div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <table class="main">
        <tr>
            <th>مارکت</th>
            <th>نوع ملک</th>

            <?php if($accounting->type === 'دوکان'): ?>
                <th>شماره دوکان</th>
            <?php endif; ?>

            <?php if($accounting->type === 'غرفه'): ?>
                <th>شماره غرفه</th>
            <?php endif; ?>

            <th>نام دوکاندار</th>
            <th>نوع مصرف</th>

            
            <?php if($accounting->expanses_type === 'پول برق'): ?>
                <th>شماره متر</th>
                <th>درجه قبلی</th>
                <th>درجه فعلی</th>
                <th>قیمت فی کیلوات</th>
            <?php endif; ?>

            <th>مبلغ</th>
            <?php if(!empty($accounting->paid) && $accounting->paid > 0): ?>
                <th>پرداخت شده</th>
                <th>باقی‌مانده</th>
            <?php endif; ?>
            <th>تاریخ ثبت</th>
        </tr>

        <tr>
            <td><?php echo e($accounting->market->name ?? '-'); ?></td>
            <td><?php echo e($accounting->type ?? '-'); ?></td>

            <?php if($accounting->type === 'دوکان'): ?>
                <td><?php echo e($accounting->shop->number ?? '-'); ?></td>
            <?php endif; ?>

            <?php if($accounting->type === 'غرفه'): ?>
                <td><?php echo e($accounting->booth->number ?? '-'); ?></td>
            <?php endif; ?>

            <td><?php echo e($accounting->shopkeeper->fullname ?? '-'); ?></td>
            <td><?php echo e($accounting->expanses_type ?? '-'); ?></td>

            
            <?php if($accounting->expanses_type === 'پول برق'): ?>
                <td><?php echo e($accounting->meter_serial ?? '-'); ?></td>
                <td><?php echo e($accounting->past_degree ?? '-'); ?></td>
                <td><?php echo e($accounting->current_degree ?? '-'); ?></td>
                <td><?php echo e(number_format($accounting->degree_price ?? 0)); ?></td>
            <?php endif; ?>

            <td><?php echo e(number_format($accounting->price)); ?></td>

            <?php if(!empty($accounting->paid) && $accounting->paid > 0): ?>
                <td><?php echo e(number_format($accounting->paid)); ?></td>
                <td><?php echo e(number_format($accounting->remained ?? 0)); ?></td>
            <?php endif; ?>

            <td><?php echo e(\Morilog\Jalali\Jalalian::fromDateTime($accounting->paid_date)->format('Y/m/d')); ?></td>
        </tr>
    </table>

</body>

</html>
<?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/exports/accounting_print.blade.php ENDPATH**/ ?>