<?php

namespace App\Http\Controllers;

use App\Models\Market\Document;
use App\Models\Market\Shopkeeper;
use Mpdf\Mpdf;
use Illuminate\Support\Facades\Auth;

class ContractPrintController extends Controller
{
    public function generate($id)
    {
        $shopkeeper = Shopkeeper::with(['shops.market', 'booth.market'])->findOrFail($id);

        // اول shop رو بگیر
        $shop = $shopkeeper->shops->first();

        // اگر shop نبود، برو booth بگیر
        $booth = $shopkeeper->booth->first();

        if (!$shop && !$booth) {
            return back()->with('error', 'هیچ دوکانی یا غرفه‌ای برای این دوکاندار ثبت نشده است.');
        }

        // ---------------- Shop Contract ----------------
        if ($shop) {
            $type = $shop->type;
            $hasCustomer = !is_null($shop->customer_id);

            $view = match (true) {
                $type === 'کرایه' && $hasCustomer => 'contracts.rent_sell',
                $type === 'گروی' && $hasCustomer => 'contracts.grawi_sell',
                $type === 'کرایه'                 => 'contracts.rent',
                $type === 'سرقفلی'               => 'contracts.sarqofli',
                $type === 'گروی'                 => 'contracts.grawi',
                default                          => abort(404, 'نوع قرارداد نامعتبر است.'),
            };

            $html = view($view, compact('shopkeeper', 'shop'))->render();
            $contractType = $shop->type;
            $marketId = $shop->market_id;
            $shopId = $shop->id;
            $boothId = null;
        }

        // ---------------- Booth Contract ----------------
        if (!$shop && $booth) {
            $type = $booth->type;

            $view = match ($type) {
                'کرایه'   => 'contracts.booth_rent',
                'گروی'    => 'contracts.booth_grawi',
                'سرقفلی'  => 'contracts.booth_sarqofli',
                default   => abort(404, 'نوع قرارداد غرفه نامعتبر است.'),
            };

            $html = view($view, compact('shopkeeper', 'booth'))->render();
            $contractType = $booth->type;
            $marketId = $booth->market_id;
            $shopId = null;
            $boothId = $booth->id;
        }

        // ---------------- Generate PDF ----------------
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

        // ---------------- Save Document ----------------
        $user = Auth::guard('market')->user();

        if ($user->role === 'superadmin' || $user->role === 'admin') {
            $adminId = $user->id;
        } else {
            $adminId = $user->admin_id;
        }

        Document::create([
            'shopkeeper_id'     => $shopkeeper->id,
            'shop_id'           => $shopId,
            'booth_id'          => $boothId,
            'market_id'         => $marketId,
            'type'              => $contractType,
            'warranty_document' => $shopkeeper->warranty_document,
            'id_image'          => $shopkeeper->id_image,
            'admin_id'          => $adminId,
        ]);

        $fileName = 'contract_' . $shopkeeper->id . '_' . time() . '.pdf';
        return $mpdf->Output($fileName, \Mpdf\Output\Destination::DOWNLOAD);
    }
}
