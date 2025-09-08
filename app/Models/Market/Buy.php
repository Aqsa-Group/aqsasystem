<?php

namespace App\Models\Market;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\Market\Customer;

class Buy extends Model
{

    protected $connection = 'market';
    protected $table = 'buys';
    protected $guarded=[];

    
    protected static function booted()
    {
        static::creating(function ($buy) {
            if (Auth::check()) {
                $user = Auth::user();
                $buy->admin_id = $user->role === 'admin' ? $user->id : $user->admin_id;
            }
        });
    
        static::created(function ($buy) {
            if ($buy->customer_id && $buy->price) {
                $customer = $buy->customer;
    
                switch ($buy->currency) {
                    case 'AFN':
                        $customer->balance_afn += $buy->price;
                        break;
                    case 'USD':
                        $customer->balance_usd += $buy->price;
                        break;
                    case 'EUR':
                        $customer->balance_eur += $buy->price;
                        break;
                    case 'IRR':
                        $customer->balance_irr += $buy->price;
                        break;
                }
    
                $customer->save();
            }
        });
    }
    

   public function customer()
   {
       return $this->belongsTo(Customer::class, 'customer_id');
   }

}
