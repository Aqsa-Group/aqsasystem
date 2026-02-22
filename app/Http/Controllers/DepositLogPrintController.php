<?php

namespace App\Http\Controllers;

use App\Models\Market\DepositLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Morilog\Jalali\Jalalian;
use Mpdf\Mpdf;

class DepositLogPrintController extends Controller
{


    public function generate($depositLogId)
    {
        try {
            $depositLog = DepositLog::with(['user', 'market', 'shop', 'shopkeeper'])->findOrFail($depositLogId);

            $mpdf = new \Mpdf\Mpdf([
                'mode' => 'utf-8',
                'format' => [210, 90],
                'directionality' => 'rtl',
                'margin_top' => 0,
                'margin_bottom' => 0,
                'margin_left' => 0,
                'margin_right' => 0,
                'fontDir' => array_merge(
                    (new \Mpdf\Config\ConfigVariables())->getDefaults()['fontDir'],
                    [public_path('fonts/vazir/')]
                ),
                'fontdata' => (new \Mpdf\Config\FontVariables())->getDefaults()['fontdata'] + [
                    'vazir' => [
                        'R' => 'Vazir-Light.ttf',
                        'B' => 'Vazir-Bold.ttf',
                        'useOTL' => 0xFF,
                        'useKashida' => 75,
                    ],
                ],
                'default_font' => 'vazir',
                'tempDir' => storage_path('app/mpdf'),
            ]);

            $mpdf->SetAutoPageBreak(false);
            $mpdf->autoLangToFont = true;
            $mpdf->SetDefaultFont('vazir');

            // رندر HTML
            $html = view('exports.deposit_log_print', compact('depositLog'))->render();
            $mpdf->WriteHTML($html);

            // نام فایل PDF
            $fileName = 'رسید_پرداخت_' . $depositLog->id . '_' . time() . '.pdf';
            $path = storage_path('app/public/' . $fileName);

            // ذخیره PDF روی سرور
            $mpdf->Output($path, \Mpdf\Output\Destination::FILE);

            // دانلود فایل توسط کاربر
            return response()->download($path, $fileName);
        } catch (\Exception $e) {
            Log::error('PDF generation error: ' . $e->getMessage());
            session()->flash('error', 'خطا در ایجاد PDF: ' . $e->getMessage());
            return null;
        }
    }
}
