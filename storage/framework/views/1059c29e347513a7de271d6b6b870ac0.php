<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تبدیل ارز - <?php echo e($conversion->type); ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: "Shabnam", sans-serif;
            width: 85mm;
            margin: 0 auto;
            padding: 0;
            background-color: white;
            font-size: 12px;
            line-height: 1.4;
        }

        .document {
            width: 85mm;
            margin: 0 auto;
            background-color: white;
            padding: 8px;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 1px solid #ddd;
        }

        .header h1 {
            font-size: 16px;
            margin-bottom: 5px;
            color: #333;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            border: 1px solid #999;
            font-size: 11px;
        }

        .info-table td {
            padding: 6px 8px;
            border: 1px solid #999;
        }

        .info-table td:first-child {
            font-weight: bold;
            width: 40%;
            background-color: #f5f5f5;
        }

        .description {
            padding: 8px;
            background-color: #f9f9f9;
            border-right: 3px solid #2B65E5;
            border: 1px solid #999;
            margin-bottom: 15px;
        }

        .description h3 {
            margin-bottom: 6px;
            font-size: 12px;
            color: #333;
        }

        .signature {
            text-align: right;
            margin-bottom: 15px;
        }

        .signature-line {
            width: 150px;
            height: 1px;
            background: #777;
            margin-top: 30px;
        }

        .contact-info {
            margin-bottom: 10px;
            padding-top: 10px;
            border-top: 1px solid #999;
        }

        .note {
            font-size: 10px;
            color: #666;
            text-align: center;
            margin-top: 10px;
            padding: 8px;
            border-top: 1px dashed #999;
        }

        .amount-in-words {
            font-size: 9px;
            color: #666;
            font-style: italic;
            margin-top: 2px;
        }

        .footer {
            margin-top: 10px;
            text-align: center;
            font-size: 9px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="document">
        <div class="header">
            <h1>صرافی <?php echo e(Auth::guard('sarafi')->user()->sarafi_name ?? 'صرافی'); ?></h1>
            <div style="font-size: 11px;">
                <strong>تبدیل ارز در حساب </strong>
            </div>
            <div style="font-size: 10px; margin-top: 5px;">
                 تاریخ: <?php echo e($conversion->transaction_date); ?>

            </div>
        </div>

        <table class="info-table">
            <?php
            $currenciesFa = [
                'afn' => 'افغانی',
                'usd' => 'دالر',
                'eur' => 'یورو',
                'irr' => 'تومان',
                'aed' => 'درهم',
                'try' => 'لیره',
                'cny' => 'یوان',
                'pkr' => 'کلدار',
                'gbp' => 'پوند',
                'jpy' => 'ین',
                'sar' => 'ریال سعودی',
                'inr' => 'روپیه',
            ];

            function convertToWords($number) {
                if (!is_numeric($number)) return '';
                try {
                    $formatter = new NumberFormatter("fa", NumberFormatter::SPELLOUT);
                    $words = $formatter->format(floatval($number));
                    return str_replace(['دویست', 'سیصد', 'پانصد'], ['دوصد', 'سه صد', 'پنجصد'], $words);
                } catch (\Exception $e) {
                    return '';
                }
            }
            ?>

          <tr>
    <td>حساب</td>
    <td>
        <?php echo e($conversion->customer->fullname ?? 'نامشخص'); ?>

        <div class="amount-in-words">
            <?php echo e($conversion->customer->account_number ?? 'نامشخص'); ?>

        </div>
    </td>
    
</tr>


            <tr>
                <td>تبدیل شده از حساب</td>
                <td>
                    <?php echo e($conversion->account_type); ?>

                </td>
            </tr>

            <tr>
                <td>ارز تبدیل شده :</td>
                <td>
                    <?php echo e($currenciesFa[strtolower($conversion->from_currency)] ?? $conversion->from_currency); ?>

                </td>
            </tr>

            <tr>
                <td>مبلغ :</td>
                <td>
                    <?php echo e(number_format((float)$conversion->buy_amount)); ?>

                    
                </td>
            </tr>

           

            <tr>
                <td> ارز دریافتی:</td>
                <td>
                    <?php echo e($currenciesFa[strtolower($conversion->to_currency)] ?? $conversion->to_currency); ?>

                </td>
            </tr>

            <tr>
                <td>مبلغ دریافتی:</td>
                <td>
                    <?php echo e(number_format((float)$conversion->sell_amount, 2)); ?>

                  
                </td>
            </tr>

            <tr>
                <td>نرخ ارز:</td>
                <td>
                    <?php echo e(number_format((float)$conversion->currency_rate, 4)); ?>

                 
                </td>
            </tr>

            <tr>
                <td>زون برداشت:</td>
                <td><?php echo e($conversion->zone_sender); ?></td>
            </tr>

            <tr>
                <td>زون دریافت:</td>
                <td><?php echo e($conversion->zone_receiver); ?></td>
            </tr>

          

            <tr>
                <td>زمان ثبت:</td>
                <td>
                    <?php
                        try {
                            $time = \Carbon\Carbon::parse($conversion->created_at);
                            echo $time->format('h:i:s') . ' ' . ($time->format('A') == 'AM' ? 'ق.ظ' : 'ب.ظ');
                        } catch (Exception $e) {
                            echo $conversion->created_at;
                        }
                    ?>
                </td>
            </tr>
        </table>

        <div class="description">
            <h3>شرح تراکنش:</h3>
            <?php echo e($conversion->description ?? 'تبدیل ارز - بدون توضیحات بیشتر'); ?>

        </div>

        <div class="signature">
            <div style="margin-bottom: 25px;">امضاء مسئول</div>
            <div class="signature-line"></div>
        </div>

        <div class="contact-info">
            <div style="margin-bottom: 5px;">
                <strong>تماس:</strong> <?php echo e(Auth::guard('sarafi')->user()->phone ? '+93' . Auth::guard('sarafi')->user()->phone : 'نامشخص'); ?>

            </div>
            <div>
                <strong>آدرس:</strong> <?php echo e(Auth::guard('sarafi')->user()->address ? 'افغانستان - ' . Auth::guard('sarafi')->user()->address : 'نامشخص'); ?>

            </div>
        </div>

        <div class="note">
            نوت: این سند جهت معلومات چاپ شده، و هیچگاه سند پولی محسوب نخواهد شد.
        </div>

        <div class="footer">
            چاپ شده در: <?php echo e(\Morilog\Jalali\Jalalian::now()->format('Y/m/d H:i:s')); ?>

        </div>
    </div>
</body>
</html><?php /**PATH /home/safiullah/Documents/GitHub/AqsaSystem/resources/views/pdf/Sarafi/conversion-in-account.blade.php ENDPATH**/ ?>