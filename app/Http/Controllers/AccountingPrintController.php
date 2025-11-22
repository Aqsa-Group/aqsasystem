<?php

namespace App\Http\Controllers;

use App\Models\Market\Accounting;

class AccountingPrintController extends Controller
{
    public function printView($id)
    {
        $accounting = Accounting::with(['market', 'shop', 'booth', 'shopkeeper'])
            ->findOrFail($id);

        if ($accounting->expanses_type === 'پول برق') {
            $rowNumber = Accounting::where('expanses_type', 'پول برق')
                ->where('admin_id', $accounting->admin_id)
                ->where('created_at', '<=', $accounting->created_at)
                ->count();


            return view('print.electricity', [
                'accounting' => $accounting,
                'rowNumber' => $rowNumber
            ]);
        }

        return view('exports.accounting_print', compact('accounting'));
    }
}
