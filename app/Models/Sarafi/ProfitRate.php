<?php

namespace App\Models\Sarafi;

use Illuminate\Database\Eloquent\Model;

class ProfitRate extends Model
{
    protected $table = 'profit_rate';
       protected $connection = 'sarafi';

    protected $fillable = [
        'user_id',
        'admin_id',
        'source_currency',

        'usd_buy_cash',
        'usd_buy_bank',
        'usd_sell_cash',
        'usd_sell_bank',
        'afn_buy_cash',
        'afn_buy_bank',
        'afn_sell_cash', 
        'afn_sell_bank',
        'irr_buy_cash',
        'irr_buy_bank',
        'irr_sell_cash',
        'irr_sell_bank',
        'eur_buy_cash',
        'eur_buy_bank',
        'eur_sell_cash',
        'eur_sell_bank',
        'pkr_buy_cash',
        'pkr_buy_bank',
        'pkr_sell_cash',
        'pkr_sell_bank',
        'aed_buy_cash',
        'aed_buy_bank',
        'aed_sell_cash',
        'aed_sell_bank',
        'cny_buy_cash',
        'cny_buy_bank',
        'cny_sell_cash',
        'cny_sell_bank',
        'try_buy_cash',
        'try_buy_bank',
        'try_sell_cash',
        'try_sell_bank',
    ];


public function getCurrencyNameAttribute()
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

    $currencyCode = strtolower($this->source_currency ?? 'usd');
    
    return $currencyMap[$currencyCode] ?? $this->source_currency ?? 'دالر';
}

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    protected $casts = [

    'usd_buy_cash'  => 'float',
    'usd_buy_bank'  => 'float',
    'usd_sell_cash' => 'float',
    'usd_sell_bank' => 'float',

    'afn_buy_cash'  => 'float',
    'afn_buy_bank'  => 'float',
    'afn_sell_cash' => 'float',
    'afn_sell_bank' => 'float',

    'irr_buy_cash'  => 'float',
    'irr_buy_bank'  => 'float',
    'irr_sell_cash' => 'float',
    'irr_sell_bank' => 'float',

    'eur_buy_cash'  => 'float',
    'eur_buy_bank'  => 'float',
    'eur_sell_cash' => 'float',
    'eur_sell_bank' => 'float',

    'pkr_buy_cash'  => 'float',
    'pkr_buy_bank'  => 'float',
    'pkr_sell_cash' => 'float',
    'pkr_sell_bank' => 'float',

    'aed_buy_cash'  => 'float',
    'aed_buy_bank'  => 'float',
    'aed_sell_cash' => 'float',
    'aed_sell_bank' => 'float',

    'cny_buy_cash'  => 'float',
    'cny_buy_bank'  => 'float',
    'cny_sell_cash' => 'float',
    'cny_sell_bank' => 'float',

    'try_buy_cash'  => 'float',
    'try_buy_bank'  => 'float',
    'try_sell_cash' => 'float',
    'try_sell_bank' => 'float',
];



}
