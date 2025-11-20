<?php

namespace App\Livewire\Sarafi;

use App\Models\Sarafi\BankAccount;
use App\Models\Sarafi\CurrencySafe;
use App\Models\Sarafi\Customer;
use App\Models\Sarafi\Remittances;
use App\Models\Sarafi\Transaction;
use App\Models\Sarafi\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboard extends Component
{
    public $activeTab = 'general';
    public $safe;
    public $safe_account = [];

    public $currencies = [];

    public function mount()
    {
        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        $this->safe = CurrencySafe::where('user_id', $adminId)->first();
        
        $safeAccountData = BankAccount::where('user_id', $adminId)->first();
        $this->safe_account = $safeAccountData ? $safeAccountData->toArray() : [];

        if (empty($this->safe_account)) {
            $this->safe_account = [
                'afn' => 0,
                'usd' => 0,
                'eur' => 0,
                'irr' => 0,
                'aed' => 0,
                'try' => 0,
                'cny' => 0,
                'pkr' => 0,
                'gbp' => 0,
                'jpy' => 0,
                'sar' => 0,
                'inr' => 0,
            ];
        }

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
        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        $customerCount = Customer::where('admin_id', $adminId)->count();
        $UserCount = User::where('admin_id', $adminId)->count();
        $TransactionCount = Transaction::where('admin_id', $adminId)->count();
        $Waiting=Remittances::where('admin_id',$adminId)->where('state', 0)->count();
        $RemittanceCount=Remittances::where('admin_id',$adminId)->count();

        return view('livewire.sarafi.dashboard', [
            'UserCount' => $UserCount,
            'customerCount' => $customerCount,
            'TransactionCount' => $TransactionCount,
            'safe' => $this->safe,
            'safe_account' => $this->safe_account, 
            'currencies' => $this->currencies,
            'waitting'=>$Waiting,
            'remittancecount'=>$RemittanceCount,
        ]);
    }
}