<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>چاپ رسید پرداخت</title>
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
            <th>شماره دوکان</th>
            <th>دوکاندار</th>
            <th>نوع هزینه</th>
            <th>پرداخت قبلی</th>
            <th>پرداخت جدید</th>
            <th>باقی قبلی</th>
            <th>باقی جدید</th>
           
        </tr>
        <tr>
       
            <td><?php echo e($depositLog->market->name ?? '-'); ?></td>
            <td><?php echo e($depositLog->shop->number ?? '-'); ?></td>
            <td><?php echo e($depositLog->shopkeeper->fullname ?? '-'); ?></td>
            <td><?php echo e($depositLog->expanses_type); ?></td>
            <td><?php echo e(number_format($depositLog->old_paid)); ?></td>
            <td><?php echo e(number_format($depositLog->new_paid)); ?></td>
            <td><?php echo e(number_format($depositLog->old_remained)); ?></td>
            <td><?php echo e(number_format($depositLog->new_remained)); ?></td>
          
        </tr>
    </table>



</body>

</html>
<?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/exports/deposit_log_print.blade.php ENDPATH**/ ?>