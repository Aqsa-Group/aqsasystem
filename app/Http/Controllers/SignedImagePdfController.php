<?php

namespace App\Http\Controllers;

use App\Models\Market\Document;
use Mpdf\Mpdf;

class SignedImagePdfController extends Controller
{
    public function download(Document $document)
    {
        // مسیر تصاویر از storage
        $signedImage = $document->signed_image ? storage_path('app/public/' . ltrim($document->signed_image, '/')) : null;
        $warrantyImage = $document->warranty_document ? storage_path('app/public/' . ltrim($document->warranty_document, '/')) : null;
        $idImage = $document->id_image ? storage_path('app/public/' . ltrim($document->id_image, '/')) : null;

        // بررسی وجود فایل‌ها
        if ($signedImage && !file_exists($signedImage)) {
            return back()->with('error', 'فایل تصویر امضا یافت نشد.');
        }
        if ($warrantyImage && !file_exists($warrantyImage)) {
            return back()->with('error', 'فایل تصویر ضمانت یافت نشد.');
        }
        if ($idImage && !file_exists($idImage)) {
            return back()->with('error', 'فایل تصویر تذکره یافت نشد.');
        }

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'directionality' => 'rtl',
            'margin_top' => 10,
            'margin_bottom' => 10,
            'margin_left' => 10,
            'margin_right' => 10,
            'default_font' => 'dejavusans',
        ]);

        // تصویر امضا
        if ($signedImage) {
            $html = '<div style="text-align:center; padding:20px;">
                        <img src="' . $signedImage . '" style="width:100%; height:auto;" />
                     </div>';
            $mpdf->WriteHTML($html);
        }

        // تصویر ضمانت
        if ($warrantyImage) {
            $mpdf->AddPage();
            $html = '<div style="text-align:center; padding:20px;">
                        <img src="' . $warrantyImage . '" style="width:100%; height:auto;" />
                     </div>';
            $mpdf->WriteHTML($html);
        }

        // تصویر تذکره
        if ($idImage) {
            $mpdf->AddPage();
            $html = '<div style="text-align:center; padding:20px;">
                        <img src="' . $idImage . '" style="width:100%; height:auto;" />
                     </div>';
            $mpdf->WriteHTML($html);
        }

        $filename = 'signed_document_' . $document->id . '.pdf';
        return $mpdf->Output($filename, \Mpdf\Output\Destination::DOWNLOAD);
    }
}
