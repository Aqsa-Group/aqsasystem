<?php

namespace App\Http\Controllers;

use App\Models\Market\Outside;
use Illuminate\Http\Request;
use Mpdf\Mpdf;

class OutsideController extends Controller
{
    public function generate($id)
    {
        $outside = Outside::with(['customer', 'staff'])->findOrFail($id);

        $html = view('exports.outside', compact('outside'))->render();

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'directionality' => 'rtl',
            'margin_top' => 10,
            'margin_bottom' => 10,
            'margin_left' => 10,
            'margin_right' => 10,
            'fontDir' => array_merge((new \Mpdf\Config\ConfigVariables())->getDefaults()['fontDir'], [
                public_path('fonts'),
            ]),
            'fontdata' => (new \Mpdf\Config\FontVariables())->getDefaults()['fontdata'] + [
                'amiri' => ['R' => 'IranNastaliq.ttf'],
            ],
            'default_font' => 'amiri',
            'tempDir' => storage_path('app/mpdf/tmp'),

        ]);

        $mpdf->autoLangToFont = true;
        $mpdf->WriteHTML($html);
        $fileName = 'outside_' . $outside->id . '_' . time() . '.pdf';

        return response(
            $mpdf->Output($fileName, \Mpdf\Output\Destination::STRING_RETURN)
        )
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $fileName . '"');
    }
}
