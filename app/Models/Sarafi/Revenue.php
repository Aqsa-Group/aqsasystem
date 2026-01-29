<?php

namespace App\Models\Sarafi;

use App\Livewire\Sarafi\ExternalTransactions;
use Illuminate\Database\Eloquent\Model;

class Revenue extends Model
{
    
    protected $connection = 'sarafi';
    
    protected $table = 'revenue';

    protected $fillable = [
        'currency',
        'profit', 
        'lost',
        'from',
        'description',
        'user_id',
        'admin_id',
        'conversion_in_account_id',
        'conversion_transfer_in_account_id',
        'safe_exchange_id',
        'external_transaction_id'

    ];

    public function conversion()
    {
        return $this->belongsTo(ConversionInAccounts::class, 'conversion_in_account_id');
    }

      public function conversiontransfer()
    {
        return $this->belongsTo(ConversionTransfers::class, 'conversion_transfer_in_account_id');
    }


       public function conversionexchange()
    {
        return $this->belongsTo(CashExchange::class, 'safe_exchange_id');
    }


        public function externalTransaction()
    {
        return $this->belongsTo(ExternalTransactions::class, 'external_transaction_id');
    }



    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Accessor برای نام فارسی ارز
     */
    public function getCurrencyNameAttribute()
    {
        $currencyNames = [
            'usd' => 'دالر',
            'afn' => 'افغانی',
            'eur' => 'یورو',
            'irr' => 'تومان',
            'aed' => 'درهم',
            'try' => 'لیره',
            'cny' => 'یوان',
            'pkr' => 'کلدار',
            'gbp' => 'پوند',
            'jpy' => 'ین',
            'sar' => 'ریال سعودی',
            'inr' => 'روپیه',
        ];

        return $currencyNames[$this->currency] ?? $this->currency;
    }

    /**
     * Accessor برای نام فارسی ارز کوتاه
     */
    public function getCurrencyFaAttribute()
    {
        return $this->currency_name;
    }

}
