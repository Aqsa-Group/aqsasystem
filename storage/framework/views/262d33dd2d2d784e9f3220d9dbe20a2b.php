<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>چاپ عواید بیرونی</title>
    <style>
        body {
            font-family: 'amiri', sans-serif;
            font-size: 12px;
            direction: rtl;
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
            background-color: white;
            padding: 25px;
        }
    </style>
</head>

<body>

    <?php
        $currencies = [
            'AFN' => 'افغانی',
            'USD' => 'دالر',
            'EUR' => 'یورو',
            'IRR' => 'تومان',
        ];
    ?>


    <!-- فاکتور اول -->
    <div style="width: 100%; direction: rtl; font-family: 'DejaVu Sans'; font-size: 12pt;">
        <table style="width: 100%; border-collapse: collapse; border: none;">
            <tr>
                <td style="width: 40%; text-align: right; vertical-align: middle; color: #1e3a8a; border: none;">
                    <strong style="font-size: 20pt;">مجتمع تجارتی عادلیار</strong>
                </td>

                <td style="width: 33.33%; text-align: center; border: none;">
                    <img src="<?php echo e(public_path('assets/logo.png')); ?>" alt="لوگو" style="height: 80px; width: 90px;" />
                </td>

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

            <td style="border: none; text-align: left;">
                <small><?php echo e(jdate($outside->created_at)->format('Y/m/d H:i')); ?></small>
            </td>
        </tr>
    </table>

    <table class="main">
        <tr>
            <th>نوع</th>
            <th>ارز</th>
            <th>مقدار</th>
            <th>تحویل دهنده</th>
            <th>توضیحات</th>
        </tr>
        <tr>
            <td>رسید عواید</td>
            <td><?php echo e($currencies[$outside->currency] ?? $outside->currency); ?></td>
            <td><?php echo e(number_format($outside->paid)); ?></td>
            <td>
                <?php if($outside->staff): ?>
                    <?php echo e($outside->staff->fullname); ?>

                <?php elseif($outside->customer): ?>
                    <?php echo e($outside->customer->fullname); ?>

                <?php else: ?>
                    -
                <?php endif; ?>

            </td>
            <td><?php echo e($outside->description ?? '-'); ?></td>
        </tr>
    </table>

    <br>

    <table class="sign">
        <tr>
            <th>امضا مدیر مالی</th>
            <th>امضا تحویل دهنده</th>
        </tr>
    </table>

    <div style="height:300px"></div>

    <div style="width: 100%; direction: rtl; font-family: 'DejaVu Sans'; font-size: 12pt;">
        <table style="width: 100%; border-collapse: collapse; border: none;">
            <tr>
                <td style="width: 40%; text-align: right; vertical-align: middle; color: #1e3a8a; border: none;">
                    <strong style="font-size: 20pt;">مجتمع تجارتی عادلیار</strong>
                </td>

                <td style="width: 33.33%; text-align: center; border: none;">
                    <img src="<?php echo e(public_path('assets/logo.png')); ?>" alt="لوگو" style="height: 80px; width: 90px;" />
                </td>

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

            <td style="border: none; text-align: left;">
                <small><?php echo e(jdate($outside->created_at)->format('Y/m/d H:i')); ?></small>
            </td>
        </tr>
    </table>

    <table class="main">
        <tr>
            <th>نوع </th>
            <th>ارز</th>
            <th>مقدار</th>
            <th>تحویل دهنده</th>
            <th>توضیحات</th>
        </tr>
        <tr>
            <td>رسید عواید</td>
            <td><?php echo e($currencies[$outside->currency] ?? $outside->currency); ?></td>
            <td><?php echo e(number_format($outside->paid)); ?></td>
            <td>
                <?php if($outside->staff): ?>
                    <?php echo e($outside->staff->fullname); ?>

                <?php elseif($outside->customer): ?>
                    <?php echo e($outside->customer->fullname); ?>

                <?php elseif($outside->shopkeeper): ?>
                    <?php echo e($outside->shopkeeper->fullname); ?>

                <?php else: ?>
                    -
                <?php endif; ?>

            </td>
            <td><?php echo e($outside->description ?? '-'); ?></td>
        </tr>
    </table>

    <br>

    <table class="sign">
        <tr>
            <th>امضا مدیر مالی</th>
            <th>امضا تحویل دهنده</th>
        </tr>
    </table>

</body>

</html>
<?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/exports/outside.blade.php ENDPATH**/ ?>