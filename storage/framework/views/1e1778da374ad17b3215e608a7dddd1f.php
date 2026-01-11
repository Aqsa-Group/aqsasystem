<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <style>
        /* فونت فارسی و جهت متن */
        body {
            font-family: Shabnam, sans-serif;
            direction: rtl;
            font-size: 14px;
            color: #1C1C1C;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }

        /* کانتینر اصلی */
        .container {
            width: 100%;
            padding: 20px 30px;
        }

        /* عنوان قرارداد */
        .title {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 20px;
            text-decoration: underline;
        }

        /* جدول مشخصات */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .info-table th,
        .info-table td {
            border: 1px solid #333;
            padding: 10px;
            text-align: right;
        }

        .info-table th {
            background-color: #f0f0f0;
            font-weight: bold;
        }

        /* خطوط جداکننده */
        .line {
            border-bottom: 1px solid #999;
            margin: 15px 0;
        }

        /* امضا */
        .signatures {
            width: 100%;
            margin-top: 50px;
        }

        .signatures div {
            width: 45%;
            display: inline-block;
            text-align: center;
        }

        .sign-line {
            border-top: 1px solid #333;
            margin-top: 50px;
            width: 80%;
            margin-left: auto;
            margin-right: auto;
        }
    </style>
</head>
<body>

<div class="container">
     <h1 style="text-align:center; font-family: amiri ,sans-serif" class="shabnam-fd ">صرافی <?php echo e(Auth::guard('sarafi')->user()->sarafi_name ?? 'صرافی'); ?></h1>



    <div class="title">قرارداد کارمند</div>
    <p>این قرارداد بین صرافی و کارمند زیر منعقد می‌گردد:</p>

    <table class="info-table">
        <tr>
            <th>نام کارمند</th>
            <td><?php echo e($staff->name); ?></td>
        </tr>
        <tr>
            <th>نام پدر</th>
            <td><?php echo e($staff->fathername); ?></td>
        </tr>
        <tr>
            <th>وظیفه / شغل</th>
            <td><?php echo e($staff->job); ?></td>
        </tr>
        <tr>
            <th>معاش</th>
            <td><?php echo e(number_format((int)$staff->salary_amount)); ?> افغانی</td>
        </tr>
        <tr>
            <th>سن</th>
            <td><?php echo e($staff->age); ?> سال</td>
        </tr>
        <tr>
            <th>جنسیت</th>
            <td><?php echo e($staff->gender == 'male' ? 'مرد' : 'زن'); ?></td>
        </tr>
        <tr>
            <th>شماره تماس</th>
            <td><?php echo e($staff->phone); ?></td>
        </tr>
        <tr>
            <th>آدرس</th>
            <td><?php echo e($staff->address); ?></td>
        </tr>
        <tr>
            <th>مدت قرارداد</th>
            <td>از <?php echo e(\Carbon\Carbon::parse($staff->contract_start)->format('Y/m/d')); ?> 
                تا <?php echo e(\Carbon\Carbon::parse($staff->contract_end)->format('Y/m/d')); ?>

            </td>
        </tr>
    </table>

    <div class="line"></div>

    <p>
        بدین وسیله کارمند فوق موافقت خود را با شرایط و قوانین  صرافی اعلام می‌دارد و متعهد می‌شود در طول مدت قرارداد وظایف خود را به نحو احسن انجام دهد.
    </p>

    <div class="line"></div>

    <div class="signatures">
        <div>
            <p> مدیریت صرافی</p>
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
<?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/pdf/Sarafi/staff-print.blade.php ENDPATH**/ ?>