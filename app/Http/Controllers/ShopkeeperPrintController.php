<?php
namespace App\Http\Controllers;

use App\Models\Market\Shopkeeper;
use App\Models\Market\Document;
use Illuminate\Support\Facades\Storage;
use Mpdf\Mpdf;

class ShopkeeperPrintController extends Controller
{
    public function export($id)
    {
        $shopkeeper = Shopkeeper::with('shops.market')->findOrFail($id);
        $shop = $shopkeeper->shops->first();
        if (!$shop) {
            return back()->with('error', 'هیچ دوکانی برای این دوکاندار ثبت نشده است.');
        }

        $market = $shop->market;
        $html = view('exports.shopkeeper', compact('shopkeeper'))->render();

        mb_internal_encoding('UTF-8');

        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $mpdf = new Mpdf([
            'fontDir' => array_merge($fontDirs, [
                public_path('fonts'),
            ]),
            'fontdata' => $fontData + [
                'scheherazade' => [
                    'R' => 'ScheherazadeNew-Medium.ttf',
                ],
            ],
            'default_font' => 'scheherazade',
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_top' => 15,
            'margin_bottom' => 15,
            'margin_left' => 15,
            'margin_right' => 15,
        ]);

        $mpdf->SetDirectionality('rtl');
        $mpdf->autoLangToFont = true;

        $mpdf->WriteHTML($html);

        $fileName = 'shopkeeper_' . $shopkeeper->id . '_' . time() . '.pdf';

        $pdfContent = $mpdf->Output($fileName, \Mpdf\Output\Destination::STRING_RETURN);
        Storage::disk('public')->put('documents/' . $fileName, $pdfContent);

        Document::create([
            'shopkeeper_id' => $shopkeeper->id,
            'shop_id'       => $shop->id,
            'market_id'     => $market?->id, 
            'filename'      => $fileName,
            'original_name' => "فایل قرارد دوکاندار {$shopkeeper->fullname}",
        ]);

        return $mpdf->Output($fileName, \Mpdf\Output\Destination::DOWNLOAD);
    }
}
