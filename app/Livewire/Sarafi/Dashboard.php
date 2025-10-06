<?php

namespace App\Livewire\Sarafi;

use App\Models\Sarafi\Customer;
use App\Models\Sarafi\CurrencySafe;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Component
{
    public $activeTab = 'general';
    public $safe;
    public $currencies = [];

    public function mount()
    {
        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        // گرفتن موجودی ادمین اصلی
        $this->safe = CurrencySafe::where('user_id', $adminId)->first();

        // لیست ارزها (key => label)
        $this->currencies = [
            'afn' => __('messages.safes_afn'),
            'usd' => __('messages.safes_usd'),
            'eur' => __('messages.safes_eur'),
            'irr' => __('messages.safes_irr'),
            'aed' => __('messages.safes_aed'),
            'try' => __('messages.safes_try'),
            'cny' => __('messages.safes_cny'),
            'pkr' => __('messages.safes_pkr'),
            'gbp' => __('messages.safes_gbp'),
            'jpy' => __('messages.safes_jpy'),
            'sar' => __('messages.safes_sar'),
            'inr' => __('messages.safes_inr'),
        ];
    }

    public function render()
    {
        $customerCount = Customer::count();

        return view('livewire.sarafi.dashboard', [
            'customerCount' => $customerCount,
            'safe' => $this->safe,
            'currencies' => $this->currencies,
        ]);
    }
}
