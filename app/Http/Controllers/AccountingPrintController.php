<?php

namespace App\Http\Controllers;

use App\Models\Market\Accounting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mpdf\Mpdf;


class AccountingPrintController extends Controller
{
    /*--------------------------------------------------------------
    | چاپ تکی
    --------------------------------------------------------------*/
    public function printView($id)
    {
        $accounting = Accounting::with(['market', 'shop', 'booth', 'shopkeeper'])
            ->findOrFail($id);

        if ($accounting->expanses_type === 'پول برق') {
            $printData = $this->preparePrintData($accounting);
            return view('print.electricity', $printData);
        }

        return view('exports.accounting_print', compact('accounting'));
    }

    /*--------------------------------------------------------------
    | چاپ انتخابی (Bulk)
    --------------------------------------------------------------*/
    public function printBulk($ids)
    {
        $ids = explode(',', $ids);

        $accountings = Accounting::with(['market', 'shop', 'booth', 'shopkeeper'])
            ->whereIn('id', $ids)
            ->where('expanses_type', 'پول برق')
            ->orderBy('created_at')
            ->get();

        if ($accountings->isEmpty()) {
            abort(404, 'هیچ قبض برقی برای چاپ یافت نشد.');
        }

        $printsData = [];
        foreach ($accountings as $acc) {
            $printsData[] = $this->preparePrintData($acc);
        }

        return view('print.electricity_bulk', compact('printsData'));
    }

    /*--------------------------------------------------------------
    | چاپ فیلتر شده 
    --------------------------------------------------------------*/
    public function printFiltered(Request $request)
    {
        if ($request->filled('ids')) {
            return $this->handleBulkPrint($request->ids);
        }

        return $this->handleFilteredPrint($request);
    }

    private function handleBulkPrint($ids)
    {
        $ids = explode(',', $ids);

        $accountings = Accounting::with(['market', 'shop', 'booth', 'shopkeeper'])
            ->whereIn('id', $ids)
            ->where('expanses_type', 'پول برق')
            ->orderBy('created_at')
            ->get();

        if ($accountings->isEmpty()) {
            abort(404, 'هیچ قبض برقی برای چاپ یافت نشد.');
        }

        $printsData = [];
        foreach ($accountings as $acc) {
            $printsData[] = $this->preparePrintData($acc);
        }

        return view('print.electricity_bulk', compact('printsData'));
    }

    private function handleFilteredPrint(Request $request)
    {
        $query = Accounting::query();

        $this->applyFilters($query, $request);

        $query->where('expanses_type', 'پول برق');

        $accountings = $query
            ->with(['market', 'shop', 'booth', 'shopkeeper'])
            ->orderBy('created_at')
            ->get();

        if ($accountings->isEmpty()) {
            abort(404, 'هیچ قبض برقی برای چاپ یافت نشد.');
        }

        $printsData = [];
        foreach ($accountings as $acc) {
            $printsData[] = $this->preparePrintData($acc);
        }

        return view('print.electricity_bulk', compact('printsData'));
    }

    /*--------------------------------------------------------------
    | فیلترها
    --------------------------------------------------------------*/
    private function applyFilters($query, Request $request)
    {
        if ($request->filled('market_id')) {
            $query->where('market_id', $request->market_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('shop_id')) {
            $query->where('shop_id', $request->shop_id);
        }

        if ($request->filled('booth_id')) {
            $query->where('booth_id', $request->booth_id);
        }

        if ($request->filled('floor')) {
            $query->where(function ($q) use ($request) {
                $q->whereHas('shop', fn($q) => $q->where('floor', $request->floor))
                  ->orWhereHas('booth', fn($q) => $q->where('floor', $request->floor));
            });
        }

        if ($request->filled('paid_date_from')) {
            $query->whereDate(
                'paid_date',
                '>=',
                \Morilog\Jalali\Jalalian::fromFormat('Y-m-d', $request->paid_date_from)->toCarbon()
            );
        }

        if ($request->filled('paid_date_until')) {
            $query->whereDate(
                'paid_date',
                '<=',
                \Morilog\Jalali\Jalalian::fromFormat('Y-m-d', $request->paid_date_until)->toCarbon()
            );
        }
    }

    /*--------------------------------------------------------------
    | آماده‌سازی داده چاپ قبض برق (مهم‌ترین بخش)
    --------------------------------------------------------------*/
    private function preparePrintData(Accounting $accounting)
    {
        /* شماره مسلسل قبض */
        $rowNumber = Accounting::where('expanses_type', 'پول برق')
            ->where('admin_id', $accounting->admin_id)
            ->where('created_at', '<=', $accounting->created_at)
            ->count();

        /*----------------------------------------------------------
        | بدهی واقعی دوره‌های قبل (فقط remained > 0)
        ----------------------------------------------------------*/
        $previousQuery = Accounting::where('expanses_type', 'پول برق')
            ->where('created_at', '<', $accounting->created_at)
            ->where('remained', '>', 0);

        if ($accounting->shop_id) {
            $previousQuery->where('shop_id', $accounting->shop_id);
        } elseif ($accounting->booth_id) {
            $previousQuery->where('booth_id', $accounting->booth_id);
        }

        $previousRemaining = (int) $previousQuery->sum('remained');

        /*----------------------------------------------------------
        | قبض فعلی
        ----------------------------------------------------------*/
        $pastDegree    = (int) ($accounting->past_degree ?? 0);
        $currentDegree = (int) ($accounting->current_degree ?? 0);
        $degreePrice   = (int) ($accounting->degree_price ?? 0);

        $consumption   = max($currentDegree - $pastDegree, 0);

        $currentPrice  = (int) ($accounting->price ?? 0);
        $currentPaid   = (int) ($accounting->paid ?? 0);
        $currentRemaining = max($currentPrice - $currentPaid, 0);

        /*----------------------------------------------------------
        | جمع کل نهایی
        ----------------------------------------------------------*/
        $totalRemaining = $previousRemaining + $currentRemaining;

        return [
            'accounting'        => $accounting,
            'rowNumber'         => $rowNumber,

            'pastDegree'        => $pastDegree,
            'currentDegree'     => $currentDegree,
            'consumption'       => $consumption,
            'degreePrice'       => $degreePrice,

            'currentPrice'      => $currentPrice,
            'currentPaid'       => $currentPaid,

            'previousRemaining' => $previousRemaining,
            'currentRemaining'  => $currentRemaining,
            'totalRemaining'    => $totalRemaining,
        ];
    }
}
