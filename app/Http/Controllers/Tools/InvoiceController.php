<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use App\Models\Tools\Sale;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function print($id)
    {
        $sale = Sale::with(['items', 'customer'])->findOrFail($id);
        
        return view('tools.invoices.print', compact('sale'));
    }
}