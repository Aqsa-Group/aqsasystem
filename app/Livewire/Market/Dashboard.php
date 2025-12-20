<?php

namespace App\Livewire\Market;

use App\Models\Market\Booth;
use App\Models\Market\Shop;
use App\Models\Market\User;
use App\Models\Market\WithdrawLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $timezone = 'Asia/Kabul';
        $today = Carbon::now($timezone)->startOfDay();
        $tomorrow = Carbon::now($timezone)->addDay()->startOfDay();

        $user = Auth::guard('market')->user();

        $userIds = [$user->id];

        if ($user->role === 'admin') {
            $subUsers = User::where('admin_id', $user->id)->pluck('id')->toArray();
            $userIds = array_merge($userIds, $subUsers);
        }

        // Shops
        $AllShop = Shop::whereIn('admin_id', $userIds)->count();
        $Sarqofli = Shop::whereIn('admin_id', $userIds)->where('sarqofli', 'بلی')->count();
        $Qerawi = Shop::whereIn('admin_id', $userIds)->where('rent', 'بلی')->count();
        $Rent = Shop::whereIn('admin_id', $userIds)->where('type', 'کرایه')->count();
        $EmptyShop = Shop::whereIn('admin_id', $userIds)->whereNull('shopkeeper_id')->count();

        // Booths
        $AllBooth = Booth::whereIn('admin_id', $userIds)->count();
        $EmptyBooth = Booth::whereIn('admin_id', $userIds)->whereNull('shopkeeper_id')->count();

        // Withdraws
        $WithdrawAfn = WithdrawLog::whereIn('admin_id', $userIds)
            ->where('currency', 'AFN')
            ->whereBetween('created_at', [$today, $tomorrow])
            ->sum('amount');

        $WithdrawUSD = WithdrawLog::whereIn('admin_id', $userIds)
            ->where('currency', 'USD')
            ->whereBetween('created_at', [$today, $tomorrow])
            ->sum('amount');

        $WithdrawIRR = WithdrawLog::whereIn('admin_id', $userIds)
            ->where('currency', 'IRR')
            ->whereBetween('created_at', [$today, $tomorrow])
            ->sum('amount');

        $WithdrawEUR = WithdrawLog::whereIn('admin_id', $userIds)
            ->where('currency', 'EUR')
            ->whereBetween('created_at', [$today, $tomorrow])
            ->sum('amount');

        return view('livewire.market.dashboard', [
            'allshop' => $AllShop,
            'allbooth' => $AllBooth,
            'sarqofli' => $Sarqofli,
            'rent' => $Rent,
            'qerawi' => $Qerawi,
            'emptyShop' => $EmptyShop,
            'emptyBooth' => $EmptyBooth,
            'withdrawafn' => $WithdrawAfn,
            'withdrawusd' => $WithdrawUSD,
            'withdrawirr' => $WithdrawIRR,
            'withdraweur' => $WithdrawEUR,
        ]);
    }
}
