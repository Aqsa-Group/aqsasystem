<?php

namespace App\Http\Controllers;

use App\Models\Market\Salary;
use Illuminate\Http\Request;
use Mpdf\Mpdf;
use Morilog\Jalali\Jalalian;

class SalaryPrintController extends Controller
{
    public function generate($id)
    {
        $salary = Salary::with(['market', 'staff'])->findOrFail($id);

        $html = view('exports.salary_print', compact('salary'))->render();

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'directionality' => 'rtl',
            'margin_top' => 15,
            'margin_bottom' => 15,
            'margin_left' => 15,
            'margin_right' => 15,
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

        $fileName = 'salary_payment_' . $salary->id . '_' . time() . '.pdf';
        return $mpdf->Output($fileName, \Mpdf\Output\Destination::DOWNLOAD);
    }
}
