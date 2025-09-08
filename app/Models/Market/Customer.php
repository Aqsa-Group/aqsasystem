<?php

namespace App\Models\Market;


use App\Models\Market\Deposit;
use App\Models\Market\Market;
use App\Models\Market\Outside;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class Customer extends Model
{

    protected $connection = 'market';
    protected $table = 'customers';

    protected $fillable = [
        'market_id',
        'fullname',
        'father_name',
        'grand_father',
        'phone',
        'address',
        'job',
        'id_number',
        'id_card_image',
        'profile_image',
        'admin_id',
        'withdraw_id',
        'warranty_document',
        'rent_money'
    ];

  
    public function withdrawlog()
    {
        return $this->belongsTo(WithdrawLog::class);
    }
  

    public function market(){
        return $this->belongsTo(Market::class);
    }
    
    
    public function loan()
    {
        return $this->hasMany(Loan::class);
    }


    public function deposit(){
        return $this->belongsTo(Deposit::class);
    }


    public function shops()
    {
        return $this->hasMany(Shop::class, 'customer_id');
    }


    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }


    public function outside()
    {
        return $this->hasMany(Outside::class);
    }

    
    public function shopkeepr()
    {
        return $this->hasMany(Shopkeeper::class);
    }

    public function buys()
    {
        return $this->hasMany(Buy::class, 'customer_id');
    }


   
    public function sell()
    {
        return $this->hasMany(Sell::class);
    }


    protected static function booted()
    {
        static::creating(function ($document) {
            if (Auth::check()) {
                $user = Auth::user();
                $document->admin_id = $user->role === 'admin' ? $user->id : $user->admin_id;
            }
        });
    }




    
}
