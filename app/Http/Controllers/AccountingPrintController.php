<?php

namespace App\Http\Controllers;

use App\Models\Market\Accounting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountingPrintController extends Controller
{
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

    public function printBulk($ids)
    {
        $ids = explode(',', $ids);
        
        $accountings = Accounting::with(['market', 'shop', 'booth', 'shopkeeper'])
            ->whereIn('id', $ids)
            ->orderBy('created_at')
            ->get();
            
        $printsData = [];
        foreach ($accountings as $acc) {
            if ($acc->expanses_type === 'پول برق') {
                $printsData[] = $this->preparePrintData($acc);
            }
        }
        
        return view('print.electricity_bulk', [
            'printsData' => $printsData
        ]);
    }
    
    public function printFiltered(Request $request)
    {
        // بررسی کنید که آیا ids در پارامترهای request وجود دارد (بخشی از bulk action)
        if ($request->has('ids') && $request->ids) {
            // حالت اول: چاپ انتخابی (از bulk action)
            return $this->handleBulkPrint($request->ids);
        } else {
            // حالت دوم: چاپ فیلتر شده (از header action)
            return $this->handleFilteredPrint($request);
        }
    }
    
    private function handleBulkPrint($ids)
    {
        $ids = explode(',', $ids);
        
        $accountings = Accounting::with(['market', 'shop', 'booth', 'shopkeeper'])
            ->whereIn('id', $ids)
            ->where('expanses_type', 'پول برق')
            ->orderBy('created_at')
            ->get();
            
        $printsData = [];
        foreach ($accountings as $acc) {
            $printsData[] = $this->preparePrintData($acc);
        }
        
        if (empty($printsData)) {
            abort(404, 'هیچ قبض برقی برای چاپ یافت نشد.');
        }
        
        return view('print.electricity_bulk', [
            'printsData' => $printsData
        ]);
    }
    
    private function handleFilteredPrint(Request $request)
    {
        $user = Auth::user();
        $query = Accounting::query();
        
        // اعمال دسترسی کاربر
       
        // اعمال فیلترهای ارسال شده
        $this->applyFilters($query, $request);
        
        // فقط قبض‌های برق
        $query->where('expanses_type', 'پول برق');
        
        $accountings = $query->with(['market', 'shop', 'booth', 'shopkeeper'])
            ->orderBy('created_at')
            ->get();
            
        $printsData = [];
        foreach ($accountings as $acc) {
            $printsData[] = $this->preparePrintData($acc);
        }
        
        if (empty($printsData)) {
            abort(404, 'هیچ قبض برقی برای چاپ یافت نشد.');
        }
        
        return view('print.electricity_bulk', [
            'printsData' => $printsData
        ]);
    }
    
 
    
    private function applyFilters($query, $request)
    {
        if ($request->has('market_id') && $request->market_id) {
            $query->where('market_id', $request->market_id);
        }
        
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }
        
        if ($request->has('shop_id') && $request->shop_id) {
            $query->where('shop_id', $request->shop_id);
        }
        
        if ($request->has('booth_id') && $request->booth_id) {
            $query->where('booth_id', $request->booth_id);
        }
        
        if ($request->has('floor') && $request->floor) {
            $query->where(function($q) use ($request) {
                $q->whereHas('shop', function($q) use ($request) {
                    $q->where('floor', $request->floor);
                })->orWhereHas('booth', function($q) use ($request) {
                    $q->where('floor', $request->floor);
                });
            });
        }
        
        if ($request->has('paid_date_from') && $request->paid_date_from) {
            $query->whereDate('paid_date', '>=', \Morilog\Jalali\Jalalian::fromFormat('Y-m-d', $request->paid_date_from)->toCarbon());
        }
        
        if ($request->has('paid_date_until') && $request->paid_date_until) {
            $query->whereDate('paid_date', '<=', \Morilog\Jalali\Jalalian::fromFormat('Y-m-d', $request->paid_date_until)->toCarbon());
        }
    }
    
private function preparePrintData($accounting)
{
    // شماره ردیف قبض
    $rowNumber = Accounting::where('expanses_type', 'پول برق')
        ->where('admin_id', $accounting->admin_id)
        ->where('created_at', '<=', $accounting->created_at)
        ->count();

    /*
     |--------------------------------------------------------------------------
     | بدهی‌های قبلی همین دوکان (نه بقیه جاها)
     |--------------------------------------------------------------------------
     */
    $previousAccountings = Accounting::where('shop_id', $accounting->shop_id)
        ->where('expanses_type', 'پول برق')
        ->where('created_at', '<', $accounting->created_at)
        ->orderBy('created_at', 'asc')
        ->get();

    $previousRemaining = 0;

    foreach ($previousAccountings as $item) {
        $price = $item->price ?? 0;
        $paid  = $item->paid ?? 0;

        $remaining = max($price - $paid, 0);
        $previousRemaining += $remaining;
    }

    /*
     |--------------------------------------------------------------------------
     | محاسبه قبض فعلی از روی درجه
     |--------------------------------------------------------------------------
     */
    $pastDegree    = $accounting->past_degree ?? 0;
    $currentDegree = $accounting->current_degree ?? 0;
    $degreePrice   = $accounting->degree_price ?? 0;

    $consumption = max($currentDegree - $pastDegree, 0);
    $currentPrice = $consumption * $degreePrice;

    $currentPaid = $accounting->paid ?? 0;

    $currentRemaining = max($currentPrice - $currentPaid, 0);

    /*
     |--------------------------------------------------------------------------
     | جمع کل
     |--------------------------------------------------------------------------
     */
    $totalRemaining = $previousRemaining + $currentRemaining;

    return [
        'accounting'        => $accounting,
        'rowNumber'         => $rowNumber,
        'pastDegree'        => $pastDegree,
        'currentDegree'     => $currentDegree,
        'consumption'       => $consumption,
        'degreePrice'       => $degreePrice,
        'currentPrice'      => $currentPrice,
        'previousRemaining' => $previousRemaining,
        'currentPaid'       => $currentPaid,
        'totalRemaining'    => $totalRemaining,
    ];
}



}