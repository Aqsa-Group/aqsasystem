<?php

namespace App\Http\Controllers;

use App\Models\Market\DepositLog;
use Mpdf\Mpdf;
use Illuminate\Http\Request;
use Morilog\Jalali\Jalalian;
use Carbon\Carbon;

class DepositLogPrintController extends Controller
{
    public function generate($id)
    {
        $depositLog = DepositLog::with(['user', 'market', 'shop', 'shopkeeper'])->findOrFail($id);

        $html = view('exports.deposit_log_print', compact('depositLog'))->render();

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => [300 , 60],
            'directionality' => 'rtl',
            'margin_top' => 10,
            'margin_bottom' => 10,
            'margin_left' => 10,
            'margin_right' => 10,
            'fontDir' => array_merge((new \Mpdf\Config\ConfigVariables())->getDefaults()['fontDir'], [
                public_path('fonts'),
            ]),
            'fontdata' => (new \Mpdf\Config\FontVariables())->getDefaults()['fontdata'] + [
                'amiri' => ['R' => 'IranNastaliq.ttf'],  // یا فونت دلخواه خودت
            ],
            'default_font' => 'amiri',
        ]);

        $mpdf->autoLangToFont = true;
        $mpdf->WriteHTML($html);

        $fileName = 'deposit_log_' . $depositLog->id . '_' . time() . '.pdf';
        return $mpdf->Output($fileName, \Mpdf\Output\Destination::DOWNLOAD);
    }
}
