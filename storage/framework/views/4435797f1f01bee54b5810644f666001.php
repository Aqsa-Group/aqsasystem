<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>سند برداشت از صندوق</title>
    <style>
        body {
            font-family: 'Shabnam', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #000;
        }
        .header {
            text-align: center;
            margin-bottom: 10px;
            border-bottom: 1px solid #000;
            padding-bottom: 5px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        .info-label {
            font-weight: bold;
            width: 40%;
        }
        .info-value {
            width: 60%;
            text-align: left;
        }
        .footer {
            margin-top: 15px;
            text-align: center;
            font-size: 10px;
            border-top: 1px solid #000;
            padding-top: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>سند برداشت از صندوق</h2>
        <p>شرکت حبیب یونس لمیتید</p>
    </div>

    <div class="info-row">
        <span class="info-label">شماره سند:</span>
        <span class="info-value"><?php echo e($withdrawal->id); ?></span>
    </div>

    <div class="info-row">
        <span class="info-label">نوع برداشت:</span>
                    <?php
                                $typesFa = [
                                'electricity' => 'برق',
                                'rent' => 'کرایه',
                                'water' => 'مالیه',
                                'food' => 'غذا',
                                'salary' => 'معاش کارمند',
                                'transportation' => 'بارچلانی چین',
                                'other' => 'متفرقه',
                                ];
                                ?>

                               

        <span class="info-value"><?php echo e($typesFa[$withdrawal->type] ?? $withdrawal->type); ?></span>
    </div>

    <div class="info-row">
        <span class="info-label">مبلغ:</span>
        <span class="info-value"><?php echo e(number_format($withdrawal->amount)); ?> 
            <?php if($withdrawal->currency === 'AFN'): ?> افغانی
            <?php elseif($withdrawal->currency === 'USD'): ?> دالر
            <?php else: ?> تومان
            <?php endif; ?>
        </span>
    </div>

  <div class="info-row">
    <span class="info-label">تاریخ:</span>
    <span class="info-value">
        <?php echo e(\Morilog\Jalali\Jalalian::fromDateTime($withdrawal->created_at)->format('Y/m/d H:i')); ?>

    </span>
</div>


    <?php if($withdrawal->description): ?>
    <div class="info-row">
        <span class="info-label">توضیحات:</span>
        <span class="info-value"><?php echo e($withdrawal->description); ?></span>
    </div>
    <?php endif; ?>

    <div class="info-row">
        <span class="info-label">ثبت کننده:</span>
        <span class="info-value"><?php echo e($withdrawal->user->name ?? 'نامشخص'); ?></span>
    </div>

    <div class="footer">
        <p>تاریخ چاپ: <?php echo e(\Morilog\Jalali\Jalalian::now()->format('Y/m/d H:i')); ?></p>
    </div>
</body>
</html><?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/pdf/import/withdrawal-print.blade.php ENDPATH**/ ?>