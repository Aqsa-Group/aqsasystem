<?php

namespace App\Models\Market;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\Market\{
    Market,
    Shopkeeper,
    Document,
    Deposit,
    Accounting,
    Advertisment
};

class Shop extends Model
{

    protected $connection = 'market';
    protected $table = 'shops';

    protected $fillable = [
        'market_id',
        'shopkeeper_id',
        'admin_id',
        'customer_id',      
        'number',
        'floor',
        'size',
        'type',
        'price',
        'fa_price',
        'half_price',
        'north',
        'east',
        'south',
        'west',
        'side',
        'metar_serial',
    
        'sarqofli',
        'sarqofli_time',
        'sarqofli_price',
        'sarqofli_fa_price',
        'sarqofli_half_price',
    
        'rent',
        'rent_time',
        'rent_price',
        'rent_fa_price',
        'rent_half_price',
        'contract_start',
        'contract_end',
        'contract_duration',
        'collect',
        'currency'
    ];
    

    public function customer() {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    
    public function market()
    {
        return $this->belongsTo(Market::class, 'market_id');
    }
    

    public function shopkeeper()
    {
        return $this->belongsTo(Shopkeeper::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function deposits()
    {
        return $this->hasMany(Deposit::class);
    }

    public function accountings()
    {
        return $this->hasMany(Accounting::class);
    }

    public function advertisments()
    {
        return $this->hasMany(Advertisment::class);
    }

  

    
    public function sell()
    {
        return $this->hasMany(Sell::class);
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


    protected $casts = [
        'number' => 'integer',
        'floor' => 'string',
        'price' => 'integer',
        'half_price' => 'integer',
        'duration' => 'integer',
        'size' => 'string', 
    ];
}
