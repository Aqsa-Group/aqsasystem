<!DOCTYPE html>
<html lang="fa" dir="ltr">

<head>
    <meta charset="UTF-8">
    <title>Customer Card - <?php echo e($customer->fullname); ?></title>
    <style>
        @font-face {
            font-family: "Shabnam-FD";
            src: url("/fonts/amiri-regular.ttf") format("truetype");
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Shabnam, sans-serif;
            width: 100%;
            min-height: 100vh;
            margin: 0;
            background: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .credit-card {
            width: 380px;
            height: 220px;
            background: linear-gradient(135deg, #039e007d 0%, #31a511 100%);
            border-radius: 15px;
            padding: 15px;
            color: white;
            position: relative;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        /* هدر کارت */
        .card-header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
        }

        .bank-name {
            font-size: 14px;
            font-weight: bold;
            color: white;
            letter-spacing: 0.5px;
        }

        .platinum-badge {
            font-size: 10px;
            font-weight: bold;
            color: #ffd700;
            letter-spacing: 0.5px;
        }

        /* شماره کارت */
        .card-number-table {
            width: 100%;
            border-collapse: collapse;
            height: 60px;
        }

        .card-number {
            font-size: 18px;
            letter-spacing: 2px;
            font-family: 'Courier New', monospace;
            font-weight: bold;
            color: white;
            text-align: center;
        }

        .number-group {
            display: inline-block;
            margin: 0 8px;
        }

   

        /* QR Code */
        .qr-section {
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .qr-code {
            width: 38px;
            height: 38px;
            background: #fff;
            border-radius: 4px;
            padding: 2px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .qr-code img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .qr-text {
            font-size: 6px;
            color: #ccc;
            margin-top: 2px;
            line-height: 1.2;
            text-align: center;
        }
    </style>
</head>

<body>
    <?php
        $cardType = 'Platinum';
        if ($customer->type == 'gold') {
            $cardType = 'Gold';
        } elseif ($customer->type == 'silver') {
            $cardType = 'Silver';
        } elseif ($customer->type == 'مشتریان ثابت') {
            $cardType = 'Premium';
        }

        $accountNumber = $customer->account_number ?? '0000000000000000';
    ?>

    <div class="credit-card">
        <!-- هدر کارت -->
        <table class="card-header-table">
            <tr>
                <td width="50%">
                    <div class="bank-name">AQSA PAY</div>
                    <div class="platinum-badge">Zarrin Exchange</div>
                    <div class="platinum-badge"><?php echo e($cardType); ?> Card</div>
                </td>
                <td style="width: 18mm; height: 18mm;">
                    <img src="<?php echo e(public_path('storage/' . $customer->image)); ?>" alt="امضاء"
                        style="width: 18mm; height: 18mm;" />
                </td>
            </tr>
        </table>

        <!-- شماره کارت -->
        <table class="card-number-table">
            <tr>
                <td style="vertical-align: middle; text-align: center;">
                    <div class="card-number">
                        <?php $groups = str_split($accountNumber, 4); ?>
                        <?php $__currentLoopData = $groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <span class="number-group"><?php echo e($group); ?></span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </td>
            </tr>
        </table>

               <!-- بخش پایین کارت در یک row -->
        <table style="width: 100%; margin-top: auto;">
            <tr>
                <!-- ستون اطلاعات کارت -->
                <td style="vertical-align: middle; text-align: left; width: 70%;">
                    <div class="card-info">
                        <div class="label">CARD HOLDER</div>
                        <div class="value"><?php echo e(strtoupper($customer->fullname)); ?></div>

                        <div class="label" style="margin-top: 4px;">VALID THRU</div>
                        <div class="value"><?php echo e(\Carbon\Carbon::now()->addYears(3)->format('m/y')); ?></div>
                    </div>
                </td>

                <!-- ستون QR Code -->
                <td style="vertical-align: middle; text-align: right; width: 30%;">
                    <div class="qr-section">
                        <div class="qr-code">
                            <?php if(isset($qrCodeUrl)): ?>
                                <img src="<?php echo e($qrCodeUrl); ?>" alt="QR Code" title="اسکن کنید برای اطلاعات مشتری">
                            <?php else: ?>
                                <div style="text-align: center; color: #666; font-size: 6px; padding: 5px;">
                                    QR CODE
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="qr-text">SCAN FOR DETAILS</div>
                    </div>
                </td>
            </tr>
        </table>

    </div>
</body>

</html>
<?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/pdf/Tools/customer-card.blade.php ENDPATH**/ ?>