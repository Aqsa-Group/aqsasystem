<?php

namespace App\Livewire\ToolsPanel;

use App\Models\Tools\CurrencySafe;
use App\Models\Tools\ShopSafe;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Safes extends Component
{
    public function render()
    {
        $user = Auth::guard('tools')->user();

        // sarafi

        $totalAFNSarafi = CurrencySafe::where('user_id', $user->id)->sum('afn');
        $totalUSDSarafi = CurrencySafe::where('user_id', $user->id)->sum('usd');
        $totalIRRSarafi = CurrencySafe::where('user_id', $user->id)->sum('irr');
        $totalPKRSarafi = CurrencySafe::where('user_id', $user->id)->sum('pkr');

        // Shop
        $totalAFNShop = ShopSafe::where('user_id', $user->id)->sum('afn');
        $totalUSDShop = ShopSafe::where('user_id', $user->id)->sum('usd');
        $totalIRRShop = ShopSafe::where('user_id', $user->id)->sum('irr');
        $totalPKRShop = ShopSafe::where('user_id', $user->id)->sum('pkr');




        return view('livewire.tools-panel.safes', [
            // Sarafi
            'totalAFNSarafi' => $totalAFNSarafi,
            'totalUSDSarafi' => $totalUSDSarafi,
            'totalIRRSarafi' => $totalIRRSarafi,
            'totalPKRSarafi' => $totalPKRSarafi,

            // Shop
            'totalAFNShop' => $totalAFNShop,
            'totalUSDShop' => $totalUSDShop,
            'totalIRRShop' => $totalIRRShop,
            'totalPKRShop' => $totalPKRShop,

        ]);
    }
}
