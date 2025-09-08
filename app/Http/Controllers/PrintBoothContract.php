<?php

namespace App\Http\Controllers;

use App\Models\Market\Customer;
use App\Models\Market\Document;
use App\Models\Market\Booth;
use Illuminate\Support\Facades\Auth;
use Mpdf\Mpdf;

class PrintBoothContract extends Controller
{
    public function generate($boothId)
    {
        $booth = Booth::with('customer.market')->findOrFail($boothId);

        $customer = $booth->customer;

        if (!$customer) {
            return back()->with('error', 'این غرفه به هیچ مشتری متصل نیست.');
        }

        $sarqofli = trim($booth->sarqofli ?? '') === 'بلی';
        $rent     = trim($booth->rent ?? '') === 'بلی';

        if (!$sarqofli && !$rent) {
            abort(404, 'نوع قرارداد غرفه نامعتبر است.');
        }

        // انتخاب ویو
        $view = $sarqofli ? 'contracts.booth_sarqofli' : 'contracts.booth_mortagage';

        $html = view($view, compact('customer', 'booth'))->render();

        // تنظیمات PDF
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

        // ذخیره در جدول Document
        $user = Auth::guard('market')->user();
        $adminId = ($user->role === 'superadmin' || $user->role === 'admin') ? $user->id : $user->admin_id;

        Document::create([
            'customer_id'      => $customer->id,
            'booth_id'         => $booth->id,
            'market_id'        => $booth->market_id,
            'type'             => $booth->type,
            'warranty_document'=> $customer->warranty_document,
            'id_image'         => $customer->id_card_image,
            'admin_id'         => $adminId,
        ]);

        $fileName = 'booth_' . $booth->id . '_' . time() . '.pdf';
        return $mpdf->Output($fileName, \Mpdf\Output\Destination::DOWNLOAD);
    }
}
