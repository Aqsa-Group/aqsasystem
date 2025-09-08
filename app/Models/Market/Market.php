<?php

namespace App\Models\Market;


use App\Models\Market\Booth;
use App\Models\Market\Customer;
use App\Models\Market\Deposit;
use App\Models\Market\Document;
use App\Models\Market\Outside;
use App\Models\Market\Shop;
use App\Models\Market\Shopkeeper;
use App\Models\Market\Staff;




use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Market extends Model
{

    protected $connection = 'market';
    protected $table ='markets';

    protected $fillable = [
        'name',
        'location',
        'total_shop',
        'floor',
        'booth',
        'booth_number',
        'stock',
        'parking',
        'admin_id',
        'market_owner'

    ];

    protected static function booted(): void
    {
        static::creating(function ($market) {
            if (Auth::check()) {
                $market->admin_id = Auth::user()->role === 'admin'
                    ? Auth::id()
                    : Auth::user()->admin_id;
            }
        });
    }

    public function shops()
    {
        return $this->hasMany(Shop::class);
    }

    public function sell()
    {
        return $this->hasMany(Sell::class);
    }

    
    public function booth()
    {
        return $this->hasMany(Booth::class);
    }


    public function loan()
    {
        return $this->hasMany(Loan::class);
    }


    public function advertisment()
    {
        return $this->hasMany(Advertisment::class);
    }

    public function salary()
    {
        return $this->hasMany(Salary::class);
    }

    public function shopkeepers()
    {
        return $this->hasMany(Shopkeeper::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }


    public function users()
    {
        return $this->hasMany(User::class, 'related_market');
    }

    public function depositLogs()
{
    return $this->hasMany(DepositLog::class);
}



    public function deposit(){
        return $this->hasMany(Deposit::class);
    }

 


    public function accountings()
    {
        return $this->hasMany(Accounting::class);
    }
    

    
  

    public function outside()
    {
        return $this->hasMany(Outside::class);
    }




public function customer(){
    return $this->hasMany(Customer::class);
}



public function stuff()
{
    return $this->hasMany(staff::class);
}




}
