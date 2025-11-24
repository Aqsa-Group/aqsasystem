<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>پرنت پول برق - مجتمع تجارتی عادلیار</title>

    <style>
        /* پایه */
        html,
        body {
            height: 100%;
            background: #fff;
            font-family: "Tahoma", "Arial", sans-serif;
            color: #111;
            direction: rtl;
            margin: 0;
            padding: 0;
            -webkit-print-color-adjust: exact;
        }

        /* ظرف صفحه */
        .page {
            width: 100%;
            padding: px;
            box-sizing: border-box;
        }

        /* جدول کلی دو ستون (هر ستون یک کپی از رسید) */
        .two-col {
            width: 100%;
            border-collapse: separate;
            border-spacing: 12px;
        }

        .two-col td {
            vertical-align: top;
            width: 50%;
            padding: 10px;
            box-sizing: border-box;
            border: 1px solid #777;
            background: #fff;
        }

        /* هدر هر ستون */
        .col-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 6px;
        }

        .col-header .title {
            font-size: 22px;
            font-weight: 400;
            color: #7c3a00;
            /* قهوه‌ای شبیه عکس */
        }

        .logo {
            width: 90px;
            height: 90px;
            display: inline-block;
            flex-shrink: 0;
            border-radius: 4px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #ddd;
            background: #fafafa;
        }

        .logo img {
            max-width: 100%;
            max-height: 100%;
            display: block;
        }

        /* جدول فرم‌ها داخل ستون */
        .form-table {
            width: 100%;
            height: fit-content;
            border-collapse: collapse;
            margin-bottom: 2px;
        }

        .form-table td,
        .form-table th {
            border: 1px solid #777;
            padding: 2px 2px;
            font-size: 14px;
            vertical-align: middle;
            text-align: center;
            width: 10px;
        }

        .form-table th {
            background: #fafafa;
            font-weight: 700;
            color: #111;
            font-size: 14px;
        }

        /* جدول ردیف های مبلغ */
        .amount-rows {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
        }


        .amount-rows th {
            border: 1px solid #999;
            padding: 8px;
            height: 36px;
            font-size: 13px;
        }

        .amount-rows td:first-child {
            font-weight: bold;
            font-size: 14px;
        }


        .amount-rows td {
            border: 1px solid #999;
            padding: 8px;
            height: 36px;
            font-size: 13px;
        }

        /* بخش مسؤول برق + امضا + نوت (مخصوص ستون چپ) */
        .left-sign-block {
            width: 100%;
            box-sizing: border-box;
            text-align: center;
            padding: 12px;
            /* قرارگیری سمت چپ جدول (vertical alignment handled by table cell) */
        }

        .left-sign-block .electrician {
            font-weight: 700;
            font-size: 14px;
            margin-bottom: 6px;
        }

        .left-sign-block .phone {
            font-size: 14px;
            font-weight: 800;
            margin-bottom: 14px;
            letter-spacing: 2px;
        }

        .left-sign-block .stamp {
            margin-top: 8px;
            border-top: 2px dashed #333;
            padding-top: 12px;
            font-size: 14px;
            height: 48px;
        }

        .left-sign-block .note-box {
            margin-top: 12px;
            border: 1px solid #bbb;
            height: 60px;
            padding: 6px;
            box-sizing: border-box;
            text-align: right;
            direction: rtl;
            font-size: 13px;
        }

        @font-face {
            font-family: "DimaYekan";
            src: url("/fonts/Yekan-Regular.ttf") format("truetype");
            font-weight: normal;
            font-style: normal;
        }

        @font-face {
            font-family: "times";
            src: url("/fonts/times.ttf") format("truetype");
            font-weight: normal;
            font-style: normal;
        }

        .yekan {
            font-family: "DimaYekan", sans-serif;
        }

        @font-face {
            font-family: "vazir";
            src: url("/fonts/Vazir.ttf") format("truetype");
            font-weight: normal;
            font-style: normal;
        }




        @font-face {
            font-family: "shabnam";
            src: url("/fonts/Shabnam-Medium.ttf") format("truetype");
            font-weight: normal;
            font-style: normal;
        }

        .shabnam {
            font-family: "shabnam", sans-serif;
        }



        @font-face {
            font-family: "Mj_Afrigha";
            src: url("/fonts/Mj_Afrigha.ttf") format("truetype");
            font-weight: normal;
            font-style: normal;
        }

        .Mj_Afrigha {
            font-family: "Mj_Afrigha", sans-serif;
        }




        @font-face {
            font-family: "shabnam";
            src: url("/fonts/Shabnam-FD.ttf") format("truetype");
            font-weight: normal;
            font-style: normal;
        }

        .shabnam-fd {
            font-family: "shabnam", sans-serif;
        }


        @font-face {
            font-family: "Yekan-Regular";
            src: url("/fonts/Yekan-Regular.ttf") format("truetype");
            font-weight: normal;
            font-style: normal;
        }




        .amiri {
            font-family: "Yekan-Regular", sans-serif;
        }




        .vazir {
            font-family: "vazir", sans-serif;
        }

        .times {
            font-family: "times", sans-serif;
        }

        /* ریسپانسیو برای صفحه نمایش */
        @media screen and (max-width: 900px) {
            .two-col td {
                display: block;
                width: 100%;
            }
        }

       @media print {
    @page {
        size: 210mm 150mm; /* نصف صفحه A5 landscape */
        margin: 0;
    }

    html,
    body {
        width: 210mm;
        height: 150mm;
        margin: 0;
        padding: 0;
        overflow: hidden;
    }
}



        /* دکمه‌های غیر چاپی */
        .no-print {
            position: fixed;
            top: 8px;
            left: 8px;
            z-index: 9999;
        }

        .btn {
            display: inline-block;
            margin-right: 6px;
            padding: 8px 12px;
            background: #1976d2;
            color: #fff;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }

        .btn.close {
            background: #c62828;
        }
    </style>
</head>

<body>



    <div class="page" role="main" aria-label="فرم پرداخت برق">
        <table class="two-col" role="table" aria-label="دو نسخه رسید">
            <tr>
                <!-- ستون چپ (نسخه‌ای که مسؤول برق + امضا در کنار جدول است) -->
                <td role="gridcell" aria-label="نسخه چپ">
                    <!-- هدر -->
                    <div class="col-header" role="banner" style="position: relative; height: 100px;">

                        <!-- متن‌ها وسط افقی -->
                        <div
                            style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align:center ;">
                            <div class="title" style="font-size: 16px; font-weight: bold; display: inline-flexbox">
                                مجتمع تجارتی عادلیار
                            </div>
                            <div class="subtitle" style="font-size: 14px; margin-top: 5px; font-weight:bolder;">
                                قبض برق
                            </div>
                        </div>

                        <!-- لوگو همان‌جاست -->
                        <div class="logo" aria-hidden="true"
                            style="position: absolute; left: 0; top: 50%; transform: translateY(-50%);">
                            <img src="<?php echo e(asset('assets/logo.png')); ?>" alt="لوگو"
                                style="filter: sepia(1) saturate(5) hue-rotate(10deg) brightness(0.6) contrast(1.2); max-width: 80px;">
                        </div>

                    </div>




                    <!-- جدول خلاصه اطلاعات بالا -->
                    <table class="form-table" role="table" aria-label="مشخصات">
                        <thead>
                            <tr>
                                <th style="font-weight:600;">مشتری</th>
                                <th style="font-weight:600;">مارکت</th>
                                <th style="font-weight:600;">
                                    <?php if(!empty($accounting->shop->number) ): ?>
                                    شماره دوکان
                                    <?php else: ?>
                                    شماره غرفه
                                    <?php endif; ?>
                                </th>
                                <th style="font-weight:600;">شماره مسلسل</th>
                                <th style="font-weight:600;">از تاریخ</th>
                                <th style="font-weight:600;">تا تاریخ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?php echo e($accounting->shopkeeper->fullname ?? $accounting->shopkeeper->name ?? '---'); ?>

                                </td>
                                <td><?php echo e($accounting->market->name ?? '---'); ?></td>
                                <td><?php echo e($accounting->shop->number ?? $accounting->booth->number ?? '---'); ?></td>
                                <td><?php echo e($rowNumber); ?></td>

                                <td>
                                    <?php if($accounting->paid_date): ?>
                                    <?php echo e(\Morilog\Jalali\Jalalian::fromDateTime($accounting->paid_date)->format('Y/m/d')); ?>

                                    <?php else: ?>
                                    ---
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($accounting->expiration_date): ?>
                                    <?php echo e(\Morilog\Jalali\Jalalian::fromDateTime($accounting->expiration_date)->format('Y/m/d')); ?>

                                    <?php else: ?>
                                    ---
                                    <?php endif; ?>
                                </td>

                            </tr>
                        </tbody>
                    </table>

                    <!-- جدول دو ستونه: سمت راست جدول مقادیر، سمت چپ بلوک مسؤول برق/امضاء/نوت -->
                    <table style="width:100%; border-collapse:separate; ">
                        <tr>
                            <!-- ستون مقادیر (عرض بیشتر) -->
                            <td style="width:70%; vertical-align: top; border:none !important; padding:0;">
                                <table class="amount-rows" role="table" aria-label="مقادیر" style="width:100%;">
                                    <tbody>
                                        <tr>
                                            <td>درجه فعلی</td>
                                            <td style="text-align:center;"><?php echo e($accounting->current_degree ??
                                                $accounting->current_reading ?? '---'); ?></td>
                                        </tr>
                                        <tr>
                                            <td>درجه قبلی</td>
                                            <td style="text-align:center;"><?php echo e($accounting->past_degree ??
                                                $accounting->previous_reading ?? '---'); ?></td>
                                        </tr>
                                        <tr>
                                            <td>مقدار مصرف</td>
                                            <td style="text-align:center;">
                                                <?php
                                                $current = $accounting->current_degree ?? $accounting->current_reading
                                                ?? null;
                                                $past = $accounting->past_degree ?? $accounting->previous_reading ??
                                                null;
                                                $usage = ($current !== null && $past !== null && is_numeric($current) &&
                                                is_numeric($past)) ? ($current - $past) : null;
                                                ?>
                                                <?php echo e($usage !== null ? $usage : '---'); ?> کیلووات
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>قیمت فی کیلووات</td>
                                            <td style="text-align:center;"><?php echo e(number_format($accounting->degree_price ??
                                                $accounting->rate_per_kwh ?? 0)); ?> افغانی</td>
                                        </tr>
                                        <tr>
                                            <td>مبلغ قابل تادیه</td>
                                            <td style="text-align:center;"><?php echo e(number_format($accounting->price ??
                                                $accounting->payable_amount ?? 0)); ?> افغانی</td>
                                        </tr>
                                        <tr>
                                            <td>باقیات</td>
                                            <td style="text-align:center;"><?php echo e(number_format($accounting->remained ??
                                                $accounting->balance ?? 0)); ?> افغانی</td>
                                        </tr>
                                        <?php
                                        $total= $accounting->remained + $accounting->price;
                                        ?>
                                        <tr>
                                            <td>جمع کل</td>
                                            <td style="text-align:center;"><?php echo e(number_format($total)); ?> افغانی</td>
                                        </tr>
                                        <tr>
                                            <td>مبلغ پرداخت شده</td>
                                            <td style="text-align:center;"><?php echo e(number_format($accounting->paid ??
                                                $accounting->paid_amount ?? 0)); ?> افغانی</td>
                                        </tr>

                                    </tbody>
                                </table>
                            </td>

                            <td
                                style="width:38%; vertical-align: top; padding:5px 8px; border:none !important; background:none !important;">

                                <!-- باکس مسؤول برق + شماره -->
                                <div style="
                                            border: 1px solid #444;
                                            padding: 5px;
                                            border-radius: 6px;
                                            margin-bottom: 15px;
                                            text-align: center;
                                            background: #fafafa;
                                        ">
                                    <div style="font-weight:bold; font-size:15px; margin-bottom:6px;">
                                        مسؤول برق
                                    </div>

                                    <div class="times" style="font-size:26px; font-weight:900;">
                                        ۰۷۹۹۵۵۳۳۳۳
                                    </div>
                                </div>

                                <!-- باکس مهر و امضاء با پر کردن کل ارتفاع -->
                                <div style="
                                            border: 1px solid #444;
                                            padding: 12px;
                                            border-radius: 6px;
                                            text-align: center;
                                            background: #fff;
                                            height: 100%;
                                            min-height: 210px;
                                            box-sizing: border-box;
                                        ">
                                    <div style="font-size:16px; font-weight:bold; margin-top:0;">
                                        مهر و امضاء
                                    </div>
                                </div>

                            </td>


                        </tr>
                    </table>

                </td>

                <!-- ستون راست (نسخه دوم) -->
                <td role="gridcell" aria-label="نسخه راست">
                    <div class="col-header" role="banner" style="position: relative; height: 100px;">

                        <!-- متن‌ها وسط افقی -->
                        <div
                            style="position: absolute; top: 50%; left: 54%; transform: translate(-50%, -50%); text-align: center;">
                            <div class="title" style="font-size: 20px; font-weight: bold;">
                                مجتمع تجارتی عادلیار
                            </div>
                            <div class="subtitle" style="font-size: 22px; margin-top: 5px; font-weight: bolder;">
                                قبض برق
                            </div>
                        </div>

                        <!-- لوگو همان‌جاست -->
                        <div class="logo" aria-hidden="true"
                            style="position: absolute; left: 0; top: 50%; transform: translateY(-50%);">
                            <img src="<?php echo e(asset('assets/logo.png')); ?>" alt="لوگو"
                                style="filter: sepia(1) saturate(5) hue-rotate(10deg) brightness(0.6) contrast(1.2); max-width: 80px;">
                        </div>

                    </div>



                    <table class="form-table" role="table" aria-label="مشخصات">
                        <tbody>
                            <tr>
                                <th>مشتری</th>
                                <th>مارکت</th>
                                <th style="font-weight:600;">
                                    <?php if(!empty($accounting->shop->number) ): ?>
                                    شماره دوکان
                                    <?php else: ?>
                                    شماره غرفه
                                    <?php endif; ?>
                                </th>
                                <th>شماره مسلسل</th>


                            </tr>
                            <tr>
                                <td><?php echo e($accounting->shopkeeper->fullname ?? $accounting->shopkeeper->name ?? '---'); ?>

                                </td>
                                <td><?php echo e($accounting->market->name ?? '---'); ?></td>

                                <td><?php echo e($accounting->shop->number ?? $accounting->booth->number ?? '---'); ?></td>
                                <td><?php echo e($rowNumber); ?></td>


                            </tr>
                        </tbody>
                    </table>

                    <table class="amount-rows" role="table" aria-label="مقادیر">
                        <tbody>
                            <tr>
                                <td>درجه فعلی</td>
                                <td style="text-align:center;"><?php echo e($accounting->current_degree ??
                                    $accounting->current_reading ?? '---'); ?></td>
                            </tr>
                            <tr>
                                <td>درجه قبلی</td>
                                <td style="text-align:center;"><?php echo e($accounting->past_degree ??
                                    $accounting->previous_reading ?? '---'); ?></td>
                            </tr>
                            <tr>
                                <td>مقدار مصرف</td>
                                <td style="text-align:center;">
                                    <?php
                                    $current = $accounting->current_degree ?? $accounting->current_reading
                                    ?? null;
                                    $past = $accounting->past_degree ?? $accounting->previous_reading ??
                                    null;
                                    $usage = ($current !== null && $past !== null && is_numeric($current) &&
                                    is_numeric($past)) ? ($current - $past) : null;
                                    ?>
                                    <?php echo e($usage !== null ? $usage : '---'); ?> کیلووات
                                </td>
                            </tr>
                            <tr>
                                <td>قیمت فی کیلووات</td>
                                <td style="text-align:center;"><?php echo e(number_format($accounting->degree_price ??
                                    $accounting->rate_per_kwh ?? 0)); ?> افغانی</td>
                            </tr>
                            <tr>
                                <td>مبلغ قابل تادیه</td>
                                <td style="text-align:center;"><?php echo e(number_format($accounting->price ??
                                    $accounting->payable_amount ?? 0)); ?> افغانی</td>
                            </tr>
                            <tr>
                                <td>باقیات</td>
                                <td style="text-align:center;"><?php echo e(number_format($accounting->remained ??
                                    $accounting->balance ?? 0)); ?> افغانی</td>
                            </tr>
                            <?php
                            $total= $accounting->remained + $accounting->price;
                            ?>
                            <tr>
                                <td>جمع کل</td>
                                <td style="text-align:center;"><?php echo e(number_format($total)); ?> افغانی</td>
                            </tr>
                            <tr>
                                <td>مبلغ پرداخت شده</td>
                                <td style="text-align:center;"><?php echo e(number_format($accounting->paid ??
                                    $accounting->paid_amount ?? 0)); ?> افغانی</td>
                            </tr>
                        </tbody>
                    </table>


                </td>
            </tr>
        </table>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        function downloadPDF() {
        const element = document.querySelector(".page");
        const opt = {
            margin:       0.2,
            filename:     'electricity-bill.pdf',
            image:        { type: 'jpeg', quality: 0.98 },
            html2canvas:  { scale: 2 },
            jsPDF:        { unit: 'cm', format: 'a4', orientation: 'landscape' }
        };

        html2pdf().set(opt).from(element).save().then(() => {
            setTimeout(() => {
                window.print();
            }, 500); 
        });
    }

    window.onload = function() {
        downloadPDF();
    }

    window.afterprint = function() {
        setTimeout(() => { window.close(); }, 1000);
    }
    </script>

</body>

</html><?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/print/electricity.blade.php ENDPATH**/ ?>