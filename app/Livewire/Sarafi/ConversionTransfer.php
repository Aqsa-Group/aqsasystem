<?php

namespace App\Livewire\Sarafi;

use App\Models\Sarafi\Transaction;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ConversionTransfer extends Component
{

    public $currency;
    public $transactionType;
    public $currencies = [];

    public function render()
    {
        return view('livewire.sarafi.conversion-transfer');
    }


       public $currenciesdefault = [
        ['name' => 'افغانی', 'value' => 0],
        ['name' => 'دالر', 'value' => 0],
        ['name' => 'تومان', 'value' => 0],
        ['name' => 'یورو', 'value' => 0],
        ['name' => 'کلدار', 'value' => 0],
        ['name' => 'درهم', 'value' => 0],
        ['name' => 'لیره', 'value' => 0],
        ['name' => 'یوان', 'value' => 0],
        ['name' => 'خلاصه بیلانس به دالر', 'value' => 0],
    ];


       public function updateCustomerCurrencyBalance()
    {
        if (!$this->selectedCustomerId) {
            $this->currenciesdefault = [
                ['name' => 'افغانی', 'value' => 0],
                ['name' => 'دالر', 'value' => 0],
                ['name' => 'تومان', 'value' => 0],
                ['name' => 'یورو', 'value' => 0],
                ['name' => 'کلدار', 'value' => 0],
                ['name' => 'درهم', 'value' => 0],
                ['name' => 'لیره', 'value' => 0],
                ['name' => 'یوان', 'value' => 0],
                ['name' => 'روپیه', 'value' => 0],

                ['name' => 'خلاصه بیلانس به دالر', 'value' => 0],
            ];
            return;
        }

        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        $transactions = Transaction::where('customer_id', $this->selectedCustomerId)
            ->where('admin_id', $adminId)
            ->get();
        $balances = [
            'افغانی' => 0,
            'دالر' => 0,
            'تومان' => 0,
            'یورو' => 0,
            'کلدار' => 0,
            'درهم' => 0,
            'لیره' => 0,
            'یوان' => 0,
            'روپیه' => 0,

        ];

        foreach ($transactions as $transaction) {
            $currencyName = $this->getCurrencyName($transaction->currency);
            $amount = $transaction->type === 'رسید' ? $transaction->amount : -$transaction->amount;

            if (array_key_exists($currencyName, $balances)) {
                $balances[$currencyName] += $amount;
            }
        }

        $totalInUsd = 0;
        $exchangeRates = [
            'افغانی' => 0.011,
            'دالر' => 1,
            'تومان' => 0.000024,
            'یورو' => 1.07,
            'کلدار' => 0.0036,
            'درهم' => 0.27,
            'لیره' => 0.031,
            'یوان' => 0.14,
            'روپیه' => 0.14,

        ];

        foreach ($balances as $currency => $balance) {
            if ($currency !== 'خلاصه بیلانس به دالر' && isset($exchangeRates[$currency])) {
                $totalInUsd += $balance * $exchangeRates[$currency];
            }
        }

        $this->currenciesdefault = [
            ['name' => 'افغانی', 'value' => $balances['افغانی']],
            ['name' => 'دالر', 'value' => $balances['دالر']],
            ['name' => 'تومان', 'value' => $balances['تومان']],
            ['name' => 'یورو', 'value' => $balances['یورو']],
            ['name' => 'کلدار', 'value' => $balances['کلدار']],
            ['name' => 'درهم', 'value' => $balances['درهم']],
            ['name' => 'لیره', 'value' => $balances['لیره']],
            ['name' => 'یوان', 'value' => $balances['یوان']],
            ['name' => 'روپیه', 'value' => $balances['روپیه']],

            ['name' => 'خلاصه بیلانس به دالر', 'value' => $totalInUsd],
        ];
    }

    private function getCurrencyName($currencyCode)
    {
        $currencyMap = [
            'afn' => 'افغانی',
            'usd' => 'دالر',
            'irr' => 'تومان',
            'eur' => 'یورو',
            'pkr' => 'کلدار',
            'aed' => 'درهم',
            'try' => 'لیره',
            'cny' => 'یوان',
            'gbp' => 'پوند',
            'jpy' => 'ین',
            'sar' => 'ریال سعودی',
            'inr' => 'روپیه',
        ];

        return $currencyMap[$currencyCode] ?? $currencyCode;
    }

       public function toggleTransactionType()
    {
        $this->transactionType = $this->transactionType === 'فروش' ? 'خرید' : 'فروش';
    }


            public function mount()
    {

        $this->currencies = [
            ['code' => 'usd', 'name_fa' => 'دالر'],
            ['code' => 'afn', 'name_fa' => 'افغانی'],
            ['code' => 'eur', 'name_fa' => 'یورو'],
            ['code' => 'irr', 'name_fa' => 'تومان'],
            ['code' => 'aed', 'name_fa' => 'درهم'],
            ['code' => 'try', 'name_fa' => 'لیره'],
            ['code' => 'cny', 'name_fa' => 'یوان'],
            ['code' => 'pkr', 'name_fa' => 'کلدار'],
            ['code' => 'gbp', 'name_fa' => 'پوند'],
            ['code' => 'jpy', 'name_fa' => 'ین'],
            ['code' => 'sar', 'name_fa' => 'ریال سعودی'],
            ['code' => 'inr', 'name_fa' => 'روپیه'],
        ];

      
    }

}
