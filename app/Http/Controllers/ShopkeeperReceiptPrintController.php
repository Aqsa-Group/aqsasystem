<?php

namespace App\Http\Controllers;

use App\Models\Market\ShopkeeperReceipt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Mpdf\Mpdf;

class ShopkeeperReceiptPrintController extends Controller
{
    // =======================================================
    // ۱. چاپ یک رسید (تکی) – سایز کوچک 210x90
    // =======================================================
    public function single(ShopkeeperReceipt $record)
    {
        try {
            $mpdf = $this->getSmallMpdf();

            $html = view('exports.shopkeeper_receipt_print', compact('record'))->render();
            $mpdf->WriteHTML($html);

            $fileName = 'receipt_' . $record->id . '.pdf';

            return response($mpdf->Output($fileName, \Mpdf\Output\Destination::STRING_RETURN))
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', "inline; filename={$fileName}");

        } catch (\Exception $e) {
            Log::error('ShopkeeperReceipt PDF single: ' . $e->getMessage());
            return response()->json(['error' => 'خطا در تولید PDF'], 500);
        }
    }

    // =======================================================
    // ۲. چاپ چند رسید انتخابی (bulk) – فرمت A4 لیستی
    // =======================================================
    public function bulk(Request $request)
    {
        try {
            $ids = explode(',', $request->get('ids', ''));
            if (empty($ids)) {
                return response()->json(['error' => 'هیچ رسیدی انتخاب نشده'], 400);
            }

            $receipts = ShopkeeperReceipt::with(['market', 'shop', 'booth', 'shopkeeper'])
                        ->whereIn('id', $ids)
                        ->get();

            $mpdf = $this->getA4Mpdf();

            $html = view('exports.shopkeeper_receipt_list_print', compact('receipts'))->render();
            $mpdf->WriteHTML($html);

            $fileName = 'receipts_bulk_' . now()->format('Ymd_His') . '.pdf';

            return response($mpdf->Output($fileName, \Mpdf\Output\Destination::STRING_RETURN))
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', "inline; filename={$fileName}");

        } catch (\Exception $e) {
            Log::error('ShopkeeperReceipt PDF bulk: ' . $e->getMessage());
            return response()->json(['error' => 'خطا در تولید PDF'], 500);
        }
    }

    // =======================================================
    // ۳. چاپ بر اساس فیلترها – فرمت A4 لیستی
    // =======================================================
    public function filtered(Request $request)
    {
        try {
            $query = ShopkeeperReceipt::with(['market', 'shop', 'booth', 'shopkeeper']);

            // اعمال فیلترها
            if ($request->filled('market_id'))      $query->where('market_id', $request->market_id);
            if ($request->filled('type'))           $query->where('type', $request->type);
            if ($request->filled('shop_id'))        $query->where('shop_id', $request->shop_id);
            if ($request->filled('booth_id'))       $query->where('booth_id', $request->booth_id);
            if ($request->filled('expanses_type'))  $query->where('expanses_type', $request->expanses_type);
            if ($request->filled('floor')) {
                $query->where(function ($q) use ($request) {
                    $q->whereHas('shop', fn($q) => $q->where('floor', $request->floor))
                      ->orWhereHas('booth', fn($q) => $q->where('floor', $request->floor));
                });
            }
            if ($request->filled('date_from')) {
                $query->whereDate('date', '>=', $request->date_from);
            }
            if ($request->filled('date_until')) {
                $query->whereDate('date', '<=', $request->date_until);
            }

            // محدودیت دسترسی مانند Resource
            $user = auth()->user();
            if ($user->role !== 'superadmin') {
                $query->where('admin_id', $user->role === 'admin' ? $user->id : $user->admin_id);
            }

            $receipts = $query->get();

            $mpdf = $this->getA4Mpdf();

            $html = view('exports.shopkeeper_receipt_list_print', compact('receipts'))->render();
            $mpdf->WriteHTML($html);

            $fileName = 'receipts_filtered_' . now()->format('Ymd_His') . '.pdf';

            return response($mpdf->Output($fileName, \Mpdf\Output\Destination::STRING_RETURN))
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', "inline; filename={$fileName}");

        } catch (\Exception $e) {
            Log::error('ShopkeeperReceipt PDF filtered: ' . $e->getMessage());
            return response()->json(['error' => 'خطا در تولید PDF'], 500);
        }
    }

    // =======================================================
    // تنظیمات mPDF – اندازه کوچک (برای رسید تکی)
    // =======================================================
    private function getSmallMpdf(): Mpdf
    {
        return new Mpdf([
            'mode'          => 'utf-8',
            'format'        => [210, 90],            // عرض 210 میلی‌متر، ارتفاع 90
            'directionality'=> 'rtl',
            'margin_top'    => 5,
            'margin_bottom' => 5,
            'margin_left'   => 5,
            'margin_right'  => 5,
            'fontDir'       => array_merge(
                (new \Mpdf\Config\ConfigVariables())->getDefaults()['fontDir'],
                [public_path('fonts/vazir/')]
            ),
            'fontdata'      => (new \Mpdf\Config\FontVariables())->getDefaults()['fontdata'] + [
                'vazir' => [
                    'R'         => 'Vazir-Light.ttf',
                    'B'         => 'Vazir-Bold.ttf',
                    'useOTL'    => 0xFF,
                    'useKashida'=> 75,
                ],
            ],
            'default_font'  => 'vazir',
            'tempDir'       => storage_path('app/mpdf'),
        ]);
    }

    // =======================================================
    // تنظیمات mPDF – اندازه A4 (برای لیست‌ها)
    // =======================================================
    private function getA4Mpdf(): Mpdf
    {
        return new Mpdf([
            'mode'          => 'utf-8',
            'format'        => 'A4-L',              // افقی برای فضای بیشتر
            'directionality'=> 'rtl',
            'margin_top'    => 10,
            'margin_bottom' => 10,
            'margin_left'   => 10,
            'margin_right'  => 10,
            'fontDir'       => array_merge(
                (new \Mpdf\Config\ConfigVariables())->getDefaults()['fontDir'],
                [public_path('fonts/vazir/')]
            ),
            'fontdata'      => (new \Mpdf\Config\FontVariables())->getDefaults()['fontdata'] + [
                'vazir' => [
                    'R'         => 'Vazir-Light.ttf',
                    'B'         => 'Vazir-Bold.ttf',
                    'useOTL'    => 0xFF,
                    'useKashida'=> 75,
                ],
            ],
            'default_font'  => 'vazir',
            'tempDir'       => storage_path('app/mpdf'),
        ]);
    }
}