<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title> اطلاعات کاربر - <?php echo e($user->name ?? 'صرافی'); ?></title>
    <style>
        /* همه عناصر بدون حاشیه و با فونت پیشفرض */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* تعریف فونت Shabnam */
        @font-face {
            font-family: "Shabnam-FD";
            src: url("/fonts/Shabnam-FD.ttf") format("truetype");
            font-weight: normal;
            font-style: normal;
        }

        .shabnam-fd {
            font-family: "Shabnam-FD", sans-serif;
        }

        body {
            font-family: "Shabnam-FD", sans-serif;
            width: 85mm;
            margin: 0 auto;
            padding: 0;
            background-color: white;
        }

        .document {
            width: 85mm;
            margin: 0 auto;
            background-color: white;
            padding: 10px;
            line-height: 1.6;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #ddd;
        }

        .header h1 {
            font-size: 18px;
            margin-bottom: 5px;
            color: #333;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            border: 1px solid #999;
        }

        .info-table td {
            padding: 8px 10px;
            border: 1px solid #999;
            font-size: 12px;
        }

        .info-table td:first-child {
            font-weight: bold;
            width: 40%;
            background-color: #f5f5f5;
        }

        .contact-info {
            margin-top: 20px;
            padding: 10px;
            border-top: 1px solid #ddd;
        }

        .contact-info table {
            width: 100%;
            border-collapse: collapse;
        }

        .contact-info td {
            padding: 5px 0;
            font-size: 11px;
        }

        .signature {
            margin-top: 40px;
            text-align: left;
        }

        .signature-line {
            width: 180px;
            height: 1px;
            background: #777;
            margin-top: 40px;
        }

        .signature-text {
            font-size: 11px;
            margin-top: 5px;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px dashed #999;
            padding-top: 10px;
        }

        @media print {
            body {
                background-color: white;
                padding: 0;
                width: 85mm;
            }
            .document {
                box-shadow: none;
            }
        }
    </style>
</head>

<body>
    <div class="document shabnam-fd">
        <!-- هدر -->
        <div class="header">
            <h1>صرافی <?php echo e($user->company_name ?? 'زرین'); ?></h1>
            <div class="date">اطلاعات کاربر</div>
        </div>

        <!-- جدول اطلاعات کاربر -->
        <table class="info-table">
            <tr>
                <td>نام کاربر</td>
                <td><?php echo e($user->name ?? '-'); ?></td>
            </tr>

            <tr>
                <td>نام فامیلی</td>
                <td><?php echo e($user->lastname ?? '-'); ?></td>
            </tr>

            <tr>
                <td>شماره تماس</td>
                <td><?php echo e($user->phone ?? '-'); ?></td>
            </tr>

            <tr>
                <td>نام کاربری</td>
                <td><?php echo e($user->username ?? '-'); ?></td>
            </tr>

            

            <tr>
                <td>نقش کاربر</td>
                <td>
                    <?php
                        $roles = [
                            'superadmin' => 'سوپر ادمین',
                            'admin' => 'مدیر',
                            'warehouse_manager' => 'خزانه دار',
                            'internal_officer' => 'مسوول احواله جات داخلی',
                            'external_officer' => 'مسوول احواله جات خارجی'
                        ];
                    ?>
                    <?php echo e($roles[$user->role] ?? $user->role); ?>

                </td>
            </tr>

           
            <tr>
                <td>آدرس</td>
                <td><?php echo e($user->address ?? '-'); ?></td>
            </tr>

            <tr>
                <td>تاریخ ایجاد</td>
                <td>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->created_at): ?>
                        <?php echo e(\Morilog\Jalali\Jalalian::fromDateTime($user->created_at)->format('Y/m/d')); ?>

                    <?php else: ?>
                        -
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </td>
            </tr>

            <tr>
                <td>وضعیت</td>
                <td>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->status == 1): ?>
                        <span style="color: green;">فعال</span>
                    <?php else: ?>
                        <span style="color: red;">غیرفعال</span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </td>
            </tr>
        </table>

    

        <!-- فوتر -->
        <div class="footer">
            <div>این سند به صورت خودکار تولید شده است</div>
            <div>تاریخ چاپ: <?php echo e(\Morilog\Jalali\Jalalian::now()->format('Y/m/d')); ?></div>
        </div>
    </div>
</body>

</html><?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/pdf/Tools/user-print.blade.php ENDPATH**/ ?>