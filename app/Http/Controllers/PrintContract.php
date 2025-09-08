<?php

namespace App\Http\Controllers;

use App\Models\Market\Customer;
use App\Models\Market\Document;
use App\Models\Market\Shop;
use App\Models\Market\Shopkeeper;
use Illuminate\Support\Facades\Auth;
use Mpdf\Mpdf;

class PrintContract extends Controller
{
    public function generate($shopId)
    {
        $shop = Shop::with('customer.market')->findOrFail($shopId);

        $customer = $shop->customer;

        if (!$customer) {
            return back()->with('error', 'این دوکان به هیچ مشتری متصل نیست.');
        }

        $sarqofli = trim($shop->sarqofli ?? '') === 'بلی';
        $rent     = trim($shop->rent ?? '') === 'بلی';

        if (!$sarqofli && !$rent) {
            abort(404, 'نوع قرارداد نامعتبر است.');
        }

        $view = $sarqofli ? 'contracts.sarqofli2' : 'contracts.mortagage';

        $html = view($view, compact('customer', 'shop'))->render();

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'directionality' => 'rtl',
            'margin_top' => 5,
            'margin_bottom' => 5,
            'margin_left' => 8,
            'margin_right' => 8,
            'fontDir' => array_merge((new \Mpdf\Config\ConfigVariables())->getDefaults()['fontDir'], [
                public_path('fonts'),
            ]),
            'fontdata' => (new \Mpdf\Config\FontVariables())->getDefaults()['fontdata'] + [
                'amiri' => ['R' => 'amiri-regular.ttf'],
            ],
            'default_font' => 'amiri',
        ]);

        $mpdf->autoLangToFont = true;
        $mpdf->WriteHTML($html);

        $user = Auth::guard('market')->user();
        $adminId = ($user->role === 'superadmin' || $user->role === 'admin') ? $user->id : $user->admin_id;

        Document::create([
            'customer_id'      => $customer->id,
            'shop_id'          => $shop->id,
            'market_id'        => $shop->market_id,
            'type'             => $shop->type,
            'warranty_document'=> $customer->warranty_document,
            'id_image'         => $customer->id_card_image,
            'admin_id'         => $adminId,
        ]);

        $fileName = 'shopkeeper_' . $shop->id . '_' . time() . '.pdf';
        return $mpdf->Output($fileName, \Mpdf\Output\Destination::DOWNLOAD);
    }
}
