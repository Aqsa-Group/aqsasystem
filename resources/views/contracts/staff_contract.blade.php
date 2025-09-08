<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8" />
    <title>قرارداد کارمند</title>
</head>

<body
    style="font-family: 'DejaVu Sans', sans-serif; direction: rtl; font-size: 9pt; margin: 0; padding: 0; background: white;">
    <div
        style="width: 210mm; height: 297mm; padding: 10mm 12mm; box-sizing: border-box; border: 3px solid #1e3a8a; color: #1e3a8a; display: flex; flex-direction:row; justify-content: flex-start;">

        <div style="font-weight: bold; font-size: 12pt; text-align: center; margin-bottom: 4pt;">۷۸۶</div>

        <div style="width: 100%; direction: rtl; font-family: 'DejaVu Sans'; font-size: 12pt;">
            <table style="width: 100%;">
                <tr>
                    <!-- Right: Persian Title -->
                    <td style="width: 40%; text-align: right; vertical-align: middle;color: #1e3a8a">
                        <strong style="font-size: 20pt;">مجتمع تجارتی عادلیار</strong>
                    </td>

                    <!-- Center: Logo -->
                    <td style="width: 33.33%; text-align: center;">
                        <img src="{{ public_path('assets/logo.png') }}" alt="لوگو"
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
                        @if($staff->market && $staff->market->market_owner)
                        <img src="{{ public_path('storage/' . $staff->market->market_owner) }}" alt="امضاء مدیر"
                            style="width: 28mm; height: 28mm;" />
                        @else
                        ---
                        @endif
                    </td>

                    <!-- جدول وسط (فقط وظیفه و معاش) -->
                    <td style="width: 105mm; text-align: center; padding: 0 5px;">
                        <table style="width: 100%; border-collapse: collapse; margin: 0 auto; border: 1px solid #1e3a8a;">
                            <tr>
                                <th style="border: 1px solid #1e3a8a; padding: 5px; background: #e0e7ff;">وظیفه</th>
                                <th style="border: 1px solid #1e3a8a; padding: 5px; background: #e0e7ff;">معاش ماهانه (؋)</th>
                            </tr>
                            <tr>
                                <td style="border: 1px solid #1e3a8a; padding: 5px; text-align: center;">{{ $staff->job }}</td>
                                <td style="border: 1px solid #1e3a8a; padding: 5px; text-align: center;">{{ number_format($staff->salary) }}</td>
                            </tr>
                        </table>
                    </td>

                    <!-- مربع سمت چپ -->
            
                    <td
                    style="width: 28mm; height: 28mm; border: 1px solid #1e3a8a; text-align: center; vertical-align: middle;">
                    <img src="{{ public_path('storage/' . $staff->profile_image) }}" alt="امضاء کارمند"
                        style="width: 28mm; height: 28mm;" />
                </td>
                </tr>
            </table>
        </div>

        @php
        use Morilog\Jalali\Jalalian;
    
        $start = $staff->contract_start ? Jalalian::fromDateTime($staff->contract_start) : null;
        $end = $staff->contract_end ? Jalalian::fromDateTime($staff->contract_end) : null;
    
        $durationText = 'مدت نامشخص';
        if ($start && $end) {
            $diff = $start->toCarbon()->diff($end->toCarbon());
    
            if ($diff->y > 0) {
                $durationText = $diff->y . ' سال';
                if ($diff->m > 0) {
                    $durationText .= ' و ' . $diff->m . ' ماه';
                }
            } elseif ($diff->m > 0) {
                $durationText = $diff->m . ' ماه';
                if ($diff->d > 0) {
                    $durationText .= ' و ' . $diff->d . ' روز';
                }
            } else {
                $durationText = $diff->d . ' روز';
            }
        }
    @endphp
    <hr>
    <div style="font-size: 10.7pt; line-height: 1.8; text-align: justify;  color:black; margin-top: 10px;">
        اینجانب {{ $staff->market->owner_name ?? 'حاجی محمد داود عادلیار' }} ولد {{ $staff->market->owner_father_name ?? 'حاجی جمعه خان' }} ولدیت {{ $staff->market->owner_grand_father ?? 'حیدرخان' }} دارنده نمبر تذکره {{ $staff->market->owner_id_number ?? '۳۲۷۹۳۸۲' }} ج. ۲۲ ص ۱۸۷ نمبر ثبت ۴۹۲ در حالی که دارای اهلیت شرعی و قانونی خویش بوده و می‌باشم، {{ $staff->fullname ?? 'مصطفی اکبری' }}، ساکن {{ $staff->address ?? 'ناحیه ۲ سینما' }} را بعنوان {{ $staff->job ?? 'صفاکار' }} در مارکت {{ $staff->market->name ?? '_____' }}، به مدت {{ $durationText }} استخدام نموده‌ام و ایشان این قرارداد را امضا نموده و متعهد می‌گردد در انجام وظایف محوله نهایت دقت و تلاش را به عمل آورد و تمامی مقررات داخلی مارکت را رعایت نماید. همچنین مارکت متعهد می‌گردد حقوق قانونی کارمند را به موقع پرداخت نماید.
    </div>
    
    
        <div style="font-size: 10pt; margin-bottom: 18pt; color:black;">
            <strong>تعهدات کارمند:</strong>
            <div style="margin-top: 6pt; padding-right: 16pt; line-height: 1.3;">
                <p style="margin-bottom: 2pt;">۱- رعایت دقیق قوانین و مقررات داخلی مارکت.</p>
                <p style="margin-bottom: 2pt;">۲- انجام وظایف محوله با کیفیت و مسئولیت پذیری.</p>
                <p style="margin-bottom: 2pt;">۳- حفظ اسرار و اطلاعات کاری مارکت.</p>
                <p style="margin-bottom: 2pt;">۴- رعایت نظم و انضباط کاری.</p>
                <p style="margin-bottom: 2pt;">۵- همکاری کامل با مدیریت مارکت.</p>
            </div>
            <div style="margin-top: 6pt; font-weight: bold; color:black;">
                نوت: بدون امضاء مدیر مارکت، قرارداد اعتبار ندارد.
            </div>
        </div>

        <div style="font-weight: bold; font-size: 16pt; text-align: center; page-break-inside: avoid; margin: 12pt 0; color:black;">
            و کان ذالک بمحضر المسلمین
        </div>

        <table style="width: 100%; margin-top: 10pt; margin-bottom: 20pt; font-weight: normal; direction: rtl; font-size: 9pt; color:black;">
            <tr>
                <td style="text-align: center; width: 50%;">امضاء مدیر مارکت</td>
                <td style="text-align: center; width: 50%;">امضاء کارمند</td>
            </tr>
        </table>

        <table style="width: 100%; margin-top: 10pt; font-weight: normal; direction: rtl; font-size: 9pt; page-break-inside: avoid; color:black;">
            <tr>
                <td style="text-align: center;">شاهد</td>
                <td style="text-align: center;">شاهد</td>
                <td style="text-align: center;">شاهد</td>
            </tr>
        </table>

    </div>

</body>

</html>
