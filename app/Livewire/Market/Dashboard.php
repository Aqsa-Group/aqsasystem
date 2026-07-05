<?php

namespace App\Livewire\Market;

use App\Models\Market\Booth;
use App\Models\Market\Shop;
use App\Models\Market\User;
use App\Models\Market\WithdrawLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
{
    $timezone = 'Asia/Kabul';
    $today = Carbon::now($timezone)->startOfDay();
    $tomorrow = Carbon::now($timezone)->addDay()->startOfDay();

    $user = Auth::guard('market')->user();

    // -------- کاربران مرتبط --------
    $userIds = [$user->id];

    if ($user->role === 'admin') {
        $subUsers = User::where('admin_id', $user->id)->pluck('id')->toArray();
        $userIds = array_merge($userIds, $subUsers);
    }

    // -------- Shops --------
    $shopsQuery = Shop::whereIn('admin_id', $userIds);

    $AllShop   = $shopsQuery->count();
    $Sarqofli  = (clone $shopsQuery)->where('sarqofli', 'بلی')->count();
    $Qerawi    = (clone $shopsQuery)->where('rent', 'بلی')->count();
    $Rent      = (clone $shopsQuery)->where('type', 'کرایه')->count();
    $EmptyShop = (clone $shopsQuery)->whereNull('shopkeeper_id')->count();

    // -------- Booths --------
    $boothsQuery = Booth::whereIn('admin_id', $userIds);

    $AllBooth   = $boothsQuery->count();
    $EmptyBooth = (clone $boothsQuery)->whereNull('shopkeeper_id')->count();

    // -------- Withdraws (TODAY - داینامیک) --------
    $withdraws = WithdrawLog::whereIn('admin_id', $userIds)
        ->whereBetween('created_at', [$today, $tomorrow])
        ->select('currency', DB::raw('SUM(amount) as total'))
        ->groupBy('currency')
        ->pluck('total', 'currency');

    $withdrawCards = [
        'AFN' => abs($withdraws['AFN'] ?? 0),
        'USD' => abs($withdraws['USD'] ?? 0),
        'EUR' => abs($withdraws['EUR'] ?? 0),
        'IRR' => abs($withdraws['IRR'] ?? 0),
    ];

    // -------- Cash (داینامیک‌تر) --------
    $cashQuery = DB::connection('market')->table('accountings');

    if ($user->role !== 'superadmin') {
        $adminId = ($user->role === 'admin') ? $user->id : $user->admin_id;
        $cashQuery->where('admin_id', $adminId);
    }

    $cash = $cashQuery
        ->select('currency', DB::raw('SUM(paid) as total'))
        ->groupBy('currency')
        ->pluck('total', 'currency');

    $cashCards = [
        'AFN' => $cash['AFN'] ?? 0,
        'USD' => $cash['USD'] ?? 0,
        'EUR' => $cash['EUR'] ?? 0,
        'IRR' => $cash['IRR'] ?? 0,
    ];

    return view('livewire.market.dashboard', [
        'allshop'     => $AllShop,
        'allbooth'    => $AllBooth,
        'sarqofli'    => $Sarqofli,
        'rent'        => $Rent,
        'qerawi'      => $Qerawi,
        'emptyShop'   => $EmptyShop,
        'emptyBooth'  => $EmptyBooth,

        'withdrawCards' => $withdrawCards,
        'cashCards'     => $cashCards,
    ]);
}
}