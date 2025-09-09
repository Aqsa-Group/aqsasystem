<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8" />
    <title>سند سرقفلی غرفه عادلیار</title>
</head>

<body
    style="font-family: 'DejaVu Sans', sans-serif; direction: rtl; font-size: 9pt; margin: 0; padding: 0; background: white;">
    <div
        style="width: 210mm; height: 297mm; padding: 10mm 12mm; box-sizing: border-box; border: 3px solid #1e3a8a; color: #1e3a8a; display: flex; flex-direction:row; justify-content: flex-start;">
        <div style="font-weight: bold; font-size: 12pt; text-align: center; margin-bottom: 4pt;">۷۸۶</div>

        <div style="width: 100%; direction: rtl; font-family: 'DejaVu Sans'; font-size: 12pt; ">
            <table style="width: 100%;">
                <tr>
                    <!-- Right: Persian Title -->
                    <td style="width: 40%; text-align: right; vertical-align: middle;color: #1e3a8a">
                        <strong style="font-size: 20pt;">مجتمع تجارتی عادلیار</strong>
                    </td>

                    <!-- Center: Logo -->
                    <td style="width: 33.33%; text-align: center;">
                        <img src="<?php echo e(public_path('assets/logo.png')); ?>" alt="لوگو"
                            style="height: 80px;width: 90px;" />
                    </td>

                    <!-- Left: English Title -->
                    <td style="width: 33.33%; text-align: left; vertical-align: middle;color: #1e3a8a  ">
                        <div style="line-height: 1.2;">
                            <div style="font-weight: bold; font-size: 18pt;">ADELYAR</div>
                            <div style="font-weight: bold; font-size: 10pt;">COMMERCIAL COMPLEX</div>
                        </div>
                    </td>

                </tr>
            </table>
        </div>

        <div
            style="width: 100%; direction: rtl; font-family: 'DejaVu Sans'; font-size: 9pt; margin-bottom: 10pt; margin-top: 14pt">
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <!-- مربع سمت راست -->
                    <td
                        style="width: 28mm; height: 28mm; border: 1px solid #1e3a8a; text-align: center; vertical-align: middle;">
                        <img src="<?php echo e(public_path('storage/' . $customer->market->market_owner)); ?>" alt="امضاء"
                            style="width: 28mm; height: 28mm;" />
                    </td>

                    <!-- جدول وسط -->
                    <td style="width: 105mm; text-align: center; padding: 0 5px;">
                        <div style="margin-bottom: 3pt; font-size: 11pt; font-weight: bold; text-align: center;">
                            سند سرقفلی غرفه فردوس پلازا
                        </div>
                        <div style="margin-bottom: 6pt; font-size: 11pt; font-weight: bold; text-align: center;">
                            واقع جاده جنوبی مسجد جامع بزرگ هرات
                        </div>
                        <table style="width: 100%; border-collapse: collapse; margin: 0 auto;">
                            <tr>
                                <td style="border: 1px solid #1e3a8a; padding: 3px;">اصل قیمت</td>
                                <td style="border: 1px solid #1e3a8a; padding: 3px;">
                                    <?php echo e(number_format($booth->sarqofli_price ?? 0)); ?>؋</td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid #1e3a8a; padding: 3px;">مناصفه</td>
                                <td style="border: 1px solid #1e3a8a; padding: 3px;">
                                    <?php echo e(number_format(($booth->sarqofli_price ?? 0) / 2)); ?>؋</td>
                            </tr>
                        </table>
                        <div style="font-size: 7.5pt; margin-top: 3pt; direction: rtl;">
                            تماس‌ها:
                            <span style="direction: ltr; unicode-bidi: embed;">+93 (0) 790444454</span> -
                            <span style="direction: ltr; unicode-bidi: embed;">+93 (0) 799888828</span>
                        </div>

        </div>
        </td>

        <!-- مربع سمت چپ -->
        <td style="width: 28mm; height: 28mm; border: 1px solid #1e3a8a; text-align: center; vertical-align: middle;">
            <img src="<?php echo e(public_path('storage/' . $customer->profile_image)); ?>" alt="امضاء"
                style="width: 28mm; height: 28mm;" />
        </td>
        </tr>
        </table>
    </div>

    <div style="direction: rtl; text-align: left; font-size: 9pt; margin-bottom: 6pt; color:black;">
        تماس مشتری: ( 0<?php echo e($customer->phone); ?> )
    </div>

    <hr style="border: none; border-top: 1px solid #1e3a8a; margin: 6pt 0;">

    <table style="width: 100%; font-size: 9pt; direction: rtl; margin-bottom: 8pt;">
        <tr>
            <td style="text-align: right; vertical-align: top;">
                تاریخ:
                <?php echo e(\Morilog\Jalali\Jalalian::fromDateTime($customer->contract_start)->format('Y/m/d') ?? '__/__/14__'); ?>

            </td>
            <td style="text-align: left; vertical-align: top;">
                نمبر ثبت:
                <div style="border-bottom: 1px dotted #000; width: 60mm; margin-top: 2px;"></div>
            </td>
        </tr>
    </table>

     <div style="font-size: 10.7pt; line-height: 1.8; text-align: justify; margin-bottom: 12pt;  color:black;"
        class="fontes">
        اینجانب حاجی محمد داود عادلیار ولد حاجی جمعه خان ولدیت حیدرخان دارنده نمبر تذکره (۳۲۷۹۳۸۲) ج. ۲۲ ص ۱۸۷ نمبر ثبت
        ۴۹۲ در
        حالی که دارای اهلیت شرعی و قانونی خویش بوده و می‌باشم اقرار مینمایم بر اینکه (1)
        در بند غرفه نمبر (<?php echo e($booth->number ?? '_____'); ?>) در طبقه (<?php echo e($booth->floor ?? '_____'); ?>) طرف
        (<?php echo e($booth->side ?? '_____'); ?>) مارکت (<?php echo e($booth->market->name ?? '_____'); ?>)
        واقع همین جایداد متذکره که مساحت آن (<?php echo e($booth->size ?? '_____'); ?>) متر مربع و بدین
        حدود اربعه محدود است شمالاً (<?php echo e($booth->north ?? '____________________'); ?>) شرقاَ
        (<?php echo e($booth->east ?? '____________________'); ?>)
        جنوباً (<?php echo e($booth->south ?? '____________________'); ?>) غرباً (<?php echo e($booth->west ?? '____________________'); ?>)
        بالای محترم (<?php echo e($customer->fullname ?? '_____'); ?>)
        ولد (<?php echo e($customer->father_name ?? '_____'); ?>) ولدیت (<?php echo e($customer->grand_father ?? '_____'); ?>) مسکونه
        (<?php echo e($customer->address ?? '_____'); ?>) دارنده نمبر تذکره (<?php echo e($customer->id_number ?? '_____'); ?>)
        در حالی که موصوف نیز دارای اهلیت شرعی و قانونی خویش می‌باشد به مبلغ (<?php echo e(number_format($booth->sarqofli_price ?? 0)); ?>؋)
        افغانی به حرف (<?php echo e($booth->sarqofli_fa_price ?? '___'); ?>) که مناصفه آن (<?php echo e(number_format(($booth->sarqofli_price ?? 0) / 2)); ?>؋) به
        سر قفلی داده‌ام.
    </div>

    <!-- باقی شروط مثل همان دوکان -->
    <div style="font-size: 10pt; margin-bottom: 18pt; color:black;">
        <strong>تکالیف خریدار:</strong>
        <div style="margin-top: 6pt; padding-right: 16pt; line-height: 1.3;">
            <p style="margin-bottom: 2pt;">۱- خریدار مکلف است هرینه آب و برق را پرداخت نماید.</p>
            <p style="margin-bottom: 2pt;">۲- هرگاه مشتری بخواهد غرفه را به سرقفلی کرایه و رهن بدهد ...</p>
            <p style="margin-bottom: 2pt;">۳- پرداخت فیصدی مسئولیت غرفه مذکور ...</p>
            <p style="margin-bottom: 2pt;">۴- جمع آوری کرایه طبق لایحه ...</p>
            <p style="margin-bottom: 2pt;">۵- بدون مهر و امضاء مالک معتبر نیست.</p>
        </div>
        <div style="margin-top: 6pt; font-weight: bold;  color:black;">نوت: بدون مهر و امضاء مالک مارکت، سند اعتبار
            ندارد.</div>
    </div>

    <div
        style="font-weight: bold; font-size: 16pt; text-align: center; page-break-inside: avoid; margin: 12pt 0;  color:black;">
        و کان ذالک بمحضر المسلمین
    </div>

    <table style="width: 100%; margin-top: 10pt; margin-bottom: 20pt; font-size: 9pt; color:black;">
        <tr>
            <td style="text-align: center; width: 50%;">نشان یا امضاء مالک</td>
            <td style="text-align: center; width: 50%;">نشان مشتری</td>
        </tr>
    </table>

    <table style="width: 100%; margin-top: 10pt; font-size: 9pt; color:black;">
        <tr>
            <td style="text-align: center;">شاهد</td>
            <td style="text-align: center;">شاهد</td>
            <td style="text-align: center;">شاهد</td>
        </tr>
    </table>
</body>
</html>
<?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/contracts/booth_sarqofli.blade.php ENDPATH**/ ?>