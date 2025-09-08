<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>چاپ برداشت</title>
    <style>
        body {
            font-family: 'amiri', sans-serif;
            font-size: 12px;
            direction: rtl;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
            padding-bottom: 5px;
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
            vertical-align: middle;
            background-color: white;
            margin: auto;


        }

        .sign th {
            border: none;
            text-align: center;
            vertical-align: middle;
            margin: auto;

            background-color: white;



        }
    </style>
</head>

<body>

<!-- فاکتور اول --> 

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
    
    <table class="test" style="width: 100%; border-collapse: collapse; border: none; margin-top: 10px;">
        <tr>
            <td style="border: none;">
                <h4>رسید برداشت از صندوق</h4>
            </td>
            <td style="border: none; text-align: left;">
                <small><?php echo e(jdate($withdraw->created_at)->format('Y/m/d H:i')); ?></small>
            </td>
        </tr>
    </table>
    

    <table class="main">
        <tr>
            <th>نوع برداشت</th>
            <th>ارز</th>
            <th>مقدار</th>
            <th>تحویل گیرنده</th>
            <th>توضیحات</th>
        </tr>
        <tr>
            <td><?php echo e($withdraw->expanses_type); ?></td>
            <td><?php echo e($withdraw->currency); ?></td>
            <td><?php echo e(number_format($withdraw->amount)); ?></td>
            <td>
                <?php if($withdraw->staff): ?>
                    <?php echo e($withdraw->staff->fullname); ?>

                <?php elseif($withdraw->customer): ?>
                    <?php echo e($withdraw->customer->fullname); ?>

                <?php else: ?>
                    -
                <?php endif; ?>
            </td>
            <td><?php echo e($withdraw->description ?? '-'); ?></td>
        </tr>
    </table>

    <br>

    <table class="sign">
        <tr>
            <th style="padding: 25px;">امضا مدیر مالی</th>
            <th style="padding: 25x;">امضا گیرنده</th>
        </tr>

    </table>

  <div style="height:160px"></div>
<!-- فاکتور دوم --> 

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
    
    <table class="test" style="width: 100%; border-collapse: collapse; border: none; margin-top: 10px;">
        <tr>
            <td style="border: none;">
                <h4>رسید برداشت از صندوق</h4>
            </td>
            <td style="border: none; text-align: left;">
                <small><?php echo e(jdate($withdraw->created_at)->format('Y/m/d H:i')); ?></small>
            </td>
        </tr>
    </table>
    
    <table class="main">
        <tr>
            <th>نوع برداشت</th>
            <th>ارز</th>
            <th>مقدار</th>
            <th>تحویل گیرنده</th>
            <th>توضیحات</th>
        </tr>
        <tr>
            <td><?php echo e($withdraw->expanses_type); ?></td>
            <td><?php echo e($withdraw->currency); ?></td>
            <td><?php echo e(number_format($withdraw->amount)); ?></td>
            <td>
                <?php if($withdraw->staff): ?>
                    <?php echo e($withdraw->staff->fullname); ?>

                <?php elseif($withdraw->customer): ?>
                    <?php echo e($withdraw->customer->fullname); ?>

                <?php else: ?>
                    -
                <?php endif; ?>
            </td>
            <td><?php echo e($withdraw->description ?? '-'); ?></td>
        </tr>
    </table>

    <br>

    <table class="sign">
        <tr>
            <th style="padding: 25px;">امضا مدیر مالی</th>
            <th style="padding: 25x;">امضا گیرنده</th>
        </tr>

    </table>

</body>

</html>
<?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/exports/withdraw.blade.php ENDPATH**/ ?>