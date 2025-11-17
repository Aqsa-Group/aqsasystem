<?php

namespace App\Http\Controllers;

use App\Models\Market\Accounting;
use Mpdf\Mpdf;
use Morilog\Jalali\Jalalian;
use Carbon\Carbon;

class AccountingPrintController extends Controller
{
    public function generate($id)
    {
        $accounting = Accounting::with(['market', 'shop', 'booth', 'shopkeeper'])->findOrFail($id);

        if ($accounting->expanses_type === 'پول برق') {
            return $this->generateElectricityPDF($accounting);
        } else {
            return $this->generateGeneralPDF($accounting);
        }
    }

    private function generateElectricityPDF($accounting)
    {
        $html = view('exports.accounting_electricity_print', compact('accounting'))->render();

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => [210,120],
            'directionality' => 'rtl',
            'margin_top' => 5,
            'margin_bottom' => 5,
            'margin_left' => 5,
            'margin_right' => 5,
            'fontDir' => array_merge((new \Mpdf\Config\ConfigVariables())->getDefaults()['fontDir'], [
                public_path('fonts'),
            ]),
            'fontdata' => (new \Mpdf\Config\FontVariables())->getDefaults()['fontdata'] + [
                'amiri' => ['R' => 'IranNastaliq.ttf'],
            ],
            'default_font' => 'amiri',
        ]);

        $mpdf->autoLangToFont = true;
        $mpdf->WriteHTML($html);

        $fileName = 'electricity_bill_' . $accounting->id . '_' . time() . '.pdf';
        return $mpdf->Output($fileName, \Mpdf\Output\Destination::DOWNLOAD);
    }

    private function generateGeneralPDF($accounting)
    {
        $html = view('exports.accounting_print', compact('accounting'))->render();

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4-L',
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
        ]);

        $mpdf->autoLangToFont = true;
        $mpdf->WriteHTML($html);

        $fileName = 'accounting_' . $accounting->id . '_' . time() . '.pdf';
        return $mpdf->Output($fileName, \Mpdf\Output\Destination::DOWNLOAD);
    }
}