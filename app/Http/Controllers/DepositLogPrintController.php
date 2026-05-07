<?php

namespace App\Http\Controllers;

use App\Models\Market\DepositLog;
use Illuminate\Support\Facades\Log;
use Mpdf\Mpdf;

class DepositLogPrintController extends Controller
{
    public function generate($depositLogId)
    {
        try {

            $depositLog = DepositLog::with([
                'market',
                'shop',
                'shopkeeper',
                'deposit',
            ])->findOrFail($depositLogId);

            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => [210, 90],
                'directionality' => 'rtl',
                'margin_top' => 5,
                'margin_bottom' => 5,
                'margin_left' => 5,
                'margin_right' => 5,
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

            $html = view('exports.deposit_log_print', compact('depositLog'))->render();
            $mpdf->WriteHTML($html);

            $fileName = 'receipt_' . $depositLog->id . '.pdf';

            return response($mpdf->Output($fileName, \Mpdf\Output\Destination::STRING_RETURN))
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', "inline; filename={$fileName}");

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => 'PDF error'], 500);
        }
    }
}