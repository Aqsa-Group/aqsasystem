<?php

namespace App\Models\Sarafi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExchangeRates extends Model
{
    use HasFactory;

    protected $connection = 'sarafi';
    protected $table = 'exchange_rate';

    protected $fillable = [
        'user_id',
        'admin_id',
        'source_currency',
        'afn_buy',
        'afn_sell',
        'irr_buy',
        'irr_sell',
        'eur_buy',
        'eur_sell',
        'pkr_buy',
        'pkr_sell',
        'aed_buy',
        'aed_sell',
        'cny_buy',
        'cny_sell',
        'try_buy',
        'try_sell',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
