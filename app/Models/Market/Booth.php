<?php

namespace App\Models\Market;


use Illuminate\Database\Eloquent\Model;
use App\Models\Market\Market;
use App\Models\Market\Shopkeeper;
use App\Models\Market\Document;
use App\Models\Market\Deposit;

use Illuminate\Support\Facades\Auth;
class Booth extends Model
{
    protected $connection = 'market';
    protected $table = 'booths';

    protected $fillable = [
        'market_id',
        'shopkeeper_id',
        'metar_serial',
        'number',
        'floor',
        'size',
        'status',
        'type',
        'price',
        'admin_id',
        'fa_price'
        
    ];

    public function sell()
    {
        return $this->hasMany(Sell::class);
    }


    public function advertisment()
    {
        return $this->hasMany(Advertisment::class);
    }

    public function market()
    {
        return $this->belongsTo(Market::class);
    }


    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }


    
    public function deposit()
    {
        return $this->belongsTo(Deposit::class);
    }

    public function accountings()
    {
        return $this->hasMany(Accounting::class);
    }


    
    public function shopkeeper()
    {
        return $this->belongsTo(Shopkeeper::class);
    }


    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    protected static function booted(): void
{
    static::creating(function ($shop) {
        if (Auth::check()) {
            $shop->admin_id = Auth::user()->role === 'admin'
                ? Auth::id()
                : Auth::user()->admin_id;
        }
    });
}

}

