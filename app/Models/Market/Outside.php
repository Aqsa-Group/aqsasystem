<?php

namespace App\Models\Market;


use App\Models\Market\Customer;
use App\Models\Market\Market;
use App\Models\Market\Shopkeeper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Outside extends Model
{
    protected $connection= 'market';
    protected $table= 'outsides';
    
    protected $fillable = [
        'market_id',
        'shopkeeper_id',
        'customer_id',
        'staff_id',
        'type',
        'currency',
        'paid',
        'description',
        'date',
        'admin_id',

    ];


    public function market(){
        return $this->belongsTo(Market::class);
    }

    public function customer(){
        return $this->belongsTo(Customer::class);
    }


    public function staff(){
        return $this->belongsTo(Staff::class);
    }


    public function shopkeeper(){
        return $this->belongsTo(Shopkeeper::class);
    }

    
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }



   protected static function booted()
    {
        static::creating(function ($outside) {
            if (Auth::check()) {
                $user = Auth::user();
                $outside->admin_id = ($user->role === 'superadmin' || $user->role === 'admin')
                    ? $user->id
                    : $user->admin_id;
            }
        });

        static::deleting(function ($outside) {
            DB::connection('market')->table('accountings')
                ->where('outside_id', $outside->id)
                ->delete();

            $customer = Customer::find($outside->customer_id);
            if ($customer) {
                switch ($outside->currency) {
                    case 'AFN':
                        $customer->balance_afn -= $outside->paid;
                        break;
                    case 'USD':
                        $customer->balance_usd -= $outside->paid;
                        break;
                    case 'EUR':
                        $customer->balance_eur -= $outside->paid;
                        break;
                    case 'IRR':
                        $customer->balance_irr -= $outside->paid;
                        break;
                }
                $customer->save();
            }
        });
    }
    
}
