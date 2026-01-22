<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8" />
    <title>سند بیع وفا عادلیار</title>
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
                    <td style="width: 28mm; height: 28mm; border: 1px solid #1e3a8a; text-align: center; vertical-align: middle;">
                        <img src="<?php echo e(public_path('storage/' . $shop->customer->profile_image)); ?>" alt="امضاء"
                            style="width: 28mm; height: 28mm;" />
                    </td>
            

                    <!-- جدول وسط -->
                    <td style="width: 105mm; text-align: center; padding: 0 5px;">
                        <div style="margin-bottom: 3pt; font-size: 9pt;">سند کرایه خط دوکان های فردوس پلازا!</div>
                        <table style="width: 100%; border-collapse: collapse; margin: 0 auto;">
                            <tr>
                                <td style="border: 1px solid #1e3a8a; padding: 3px;">اصل قیمت</td>
                                <td style="border: 1px solid #1e3a8a; padding: 3px;">
                                    <?php echo e(number_format($shop->price ?? 0)); ?>؋</td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid #1e3a8a; padding: 3px;">مناصفه</td>
                                <td style="border: 1px solid #1e3a8a; padding: 3px;">
                                    <?php echo e(number_format(($shop->price ?? 0) / 2)); ?>؋</td>
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
     

        
        <td
        style="width: 28mm; height: 28mm; border: 1px solid #1e3a8a; text-align: center; vertical-align: middle;">
        <img src="<?php echo e(public_path('storage/' . $shopkeeper->shopkeeper_image)); ?>" alt="امضاء"
            style="width: 28mm; height: 28mm;" />
    </td>



        </tr>
        </table>
    </div>


    <div style="direction: rtl; text-align: left; font-size: 9pt; margin-bottom: 6pt; color:black;">
        تماس مشتری: ( 0<?php echo e($shopkeeper->phone); ?> )
    </div>

    <hr style="border: none; border-top: 1px solid #1e3a8a; margin: 6pt 0;">


    <table style="width: 100%; font-size: 9pt; direction: rtl; margin-bottom: 8pt;">
        <tr>

            <td style="text-align: right; vertical-align: top;">
                تاریخ:
                <?php echo e(\Morilog\Jalali\Jalalian::fromDateTime($shopkeeper->contract_start)->format('Y/m/d') ?? '__/__/14__'); ?>

            </td>


            <td style="text-align: left; vertical-align: top;">
                نمبر ثبت:
                <span style="color: red; font-weight: bold;">
                    <?php echo e($shopkeeper->contract_number ?? ''); ?>

                </span>
                <div style="border-bottom: 1px dotted #000; width: 60mm; margin-top: 2px;"></div>
            </td>
        </tr>
    </table>




    <?php
        use Morilog\Jalali\Jalalian;

        $startDate = $shopkeeper->contract_start ? Jalalian::fromDateTime($shopkeeper->contract_start) : null;
        $endDate = $shopkeeper->contract_end ? Jalalian::fromDateTime($shopkeeper->contract_end) : null;

        $durationText = '---';
        if ($startDate && $endDate) {
            $diffDays = $startDate->toCarbon()->diffInDays($endDate->toCarbon());
            $durationText = $diffDays . ' روز';
        }
    ?>

    <div style="font-size: 10.7pt; line-height: 1.8; text-align: justify; margin-bottom: 12pt;  color:black;">
        اینجانب <?php echo e($shop->customer?->fullname); ?> ولد <?php echo e($shop->customer->father_name); ?> ولدیت
        <?php echo e($shop->customer->grand_father); ?>

        دارنده نمبر تذکره (<?php echo e($shop->customer->id_number); ?>)، در حالی که دارای اهلیت کامل شرعی و قانونی خویش می‌باشم،
        دوکان واقع در طبقه (<?php echo e($shop->floor ?? '---'); ?>)
        نمبر (<?php echo e($shop->number ?? '---'); ?>) را
        برای محترم (<?php echo e($shopkeeper->fullname ?? '---'); ?>) ولد (<?php echo e($shopkeeper->father_name ?? '---'); ?>) ولدیت
        (<?php echo e($shopkeeper->grand_father ?? '---'); ?>) دارنده تذکره (<?php echo e($shopkeeper->national_id ?? '---'); ?>)،
        از تاریخ (<?php echo e($startDate ? $startDate->format('Y/m/d') : '__ / __ / ۱۴__'); ?>) الی
        (<?php echo e($endDate ? $endDate->format('Y/m/d') : '__ / __ / ۱۴__'); ?>) به مدت
        (<?php echo e($shopkeeper->contract_duration); ?>) به مبلغ (<?php echo e(number_format($shop->price ?? 0)); ?>؋)
        معادل فارسی (<?php echo e($shop->fa_price ?? '---'); ?>) که مناصفه آن (<?php echo e(number_format(($shop->price ?? 0) / 2)); ?>؋)
        می‌باشد، به بیع وفا داده‌ام. و به اقرار خود صادق میباشم و من مشتری نیز اقرار مینمایم که طبق مندرجات فوق عمل
        نموده و هیچگونه عذر دیگری نمی آورم.
    </div>


    <div style="font-size: 10pt; margin-bottom: 18pt; color:black;">
        <strong>تکالیف بیع وفا نشین:</strong>
        <div style="margin-top: 6pt; padding-right: 16pt; line-height: 1.6;">
            <p style="margin-bottom: 2pt;">۱- پول برق و آب بدوش گروی کننده می‌باشد.</p>
            <p style="margin-bottom: 2pt;">۲- بدون استحضاری مالک به شخص دیگری به کرایه و گروی داده نمی‌تواند، گروی هذا
                به دو نسخه ترتیب شده تا واضح باشد.</p>
            <p style="margin-bottom: 2pt;">۳- در ختم میعاد به موافقه جانبین قرارداد تمدید و یا فسخ می‌گردد.</p>
            <p style="margin-bottom: 2pt;">۴- شخص گروی‌کننده مکلف است دوکان و یا اطلاق مذکور را همانطور که تحویل می‌گیرد
                تحویل بدهد.</p>
            <p style="margin-bottom: 2pt;">۵- هم بایع و هم مشتری به اقرار خویش صادق می‌باشیم.</p>
        </div>

        <div style="margin-top: 6pt; font-weight: bold;">نوت: بدون مهر و امضاء مالک مارکت، سند اعتبار ندارد.</div>
    </div>


    <div
        style="font-weight: bold; font-size: 16pt; text-align: center; page-break-inside: avoid; margin: 12pt 0; color:black;">
        و کان ذالک بمحضر المسلمین
    </div>

    <table
        style="width: 100%; margin-top: 10pt; margin-bottom: 20pt; font-weight: normal; direction: rtl; font-size: 9pt;  color:black;">
        <tr>
            <td style="text-align: center; width: 50%;">نشان یا امضاء مالک</td>
            <td style="text-align: center; width: 50%;">نشان مشتری</td>
        </tr>
    </table>

    <table
        style="width: 100%; margin-top: 10pt; font-weight: normal; direction: rtl; font-size: 9pt; page-break-inside: avoid;  color:black;">
        <tr>
            <td style="text-align: center;">شاهد</td>
            <td style="text-align: center;">شاهد</td>
            <td style="text-align: center;">شاهد</td>
        </tr>
    </table>


    </div>

    </div>

</body>

</html>
<?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/contracts/grawi_sell.blade.php ENDPATH**/ ?>