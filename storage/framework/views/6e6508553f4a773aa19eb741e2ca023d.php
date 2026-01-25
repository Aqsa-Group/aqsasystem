<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Shabnam, sans-serif;
            direction: rtl;
            font-size: 14px;
            color: #1C1C1C;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            padding: 10px 15px;
        }

        .title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 15px;
            text-decoration: underline;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .info-table th,
        .info-table td {
            border: 1px solid #333;
            padding: 8px;
            text-align: right;
        }

        .info-table th {
            background-color: #f0f0f0;
            font-weight: bold;
        }

        .line {
            border-bottom: 1px solid #999;
            margin: 15px 0;
        }

        .signatures {
            width: 100%;
            margin-top: 40px;
        }

        .signatures div {
            width: 45%;
            display: inline-block;
            text-align: center;
        }

        .sign-line {
            border-top: 1px solid #333;
            margin-top: 35px;
            width: 80%;
            margin-left: auto;
            margin-right: auto;
        }
    </style>
</head>
<body>

<div class="container">
    <h1 style="text-align:center;">صرافی <?php echo e($withdraw->user->sarafi_name ?? 'صرافی'); ?></h1>

    <div class="title">برداشت کارمند</div>
    <p>این رسید مربوط به برداشت کارمند زیر می‌باشد:</p>

    <table class="info-table">
        <tr>
            <th>نام کارمند</th>
            <td><?php echo e($withdraw->staff->name ?? ''); ?></td>
        </tr>
        <tr>
            <th>نام پدر</th>
            <td><?php echo e($withdraw->staff->fathername ?? ''); ?></td>
        </tr>
        <tr>
            <th>وظیفه / شغل</th>
            <td><?php echo e($withdraw->staff->job ?? ''); ?></td>
        </tr>
        <tr>
            <th>نوع هزینه</th>
            <td><?php echo e($withdraw->expanses_type); ?></td>
        </tr>
        <tr>
            <th>مبلغ</th>
            <td><?php echo e(number_format($withdraw->amount)); ?> <?php echo e($withdraw->currency); ?></td>
        </tr>
       <tr>
    <th>تاریخ برداشت</th>
    <td>
        <?php echo e($withdraw->date); ?> <?php echo e(\Carbon\Carbon::parse($withdraw->created_at)->format('H:i')); ?>

    </td>
</tr>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($withdraw->description): ?>
        <tr>
            <th>توضیحات</th>
            <td><?php echo e($withdraw->description); ?></td>
        </tr>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </table>

    <div class="line"></div>

    <p>
        بدین وسیله برداشت فوق مورد تأیید صرافی بوده و کارمند مبلغ فوق را دریافت نمود.
    </p>

    <div class="line"></div>

    <div class="signatures">
        <div>
            <p>مدیریت صرافی</p>
            <div class="sign-line"></div>
        </div>
        <div>
            <p>امضای کارمند</p>
            <div class="sign-line"></div>
        </div>
    </div>
</div>

</body>
</html>
<?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/pdf/Sarafi/withdraw-print.blade.php ENDPATH**/ ?>