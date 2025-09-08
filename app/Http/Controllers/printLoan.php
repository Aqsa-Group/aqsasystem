<?php

namespace App\Http\Controllers;

use App\Models\Market\LoanLog;
use Illuminate\Http\Request;
use App\Models\Market\Loan;
use Mpdf\Mpdf;


class printLoan extends Controller
{

    public function generate($id)
    {
        $loan = Loan::with(['customer', 'staff' , 'shopkeeper'])->findOrFail($id);
    
        $html = view('exports.loanLog', compact('loan'))->render();
    
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
        ]);
    
        $mpdf->autoLangToFont = true;
        $mpdf->WriteHTML($html);
    
        $fileName = 'loan_' . $loan->id . '_' . time() . '.pdf';
        return $mpdf->Output($fileName, \Mpdf\Output\Destination::DOWNLOAD);
    }
    
}
