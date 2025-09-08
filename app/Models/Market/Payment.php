<?php

namespace App\Models\Market;

use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Payment extends Model
{
   
    protected $connection= 'market';
    protected $table= 'payments';
    

    protected $fillable = [
        'loan_id', 'market_id', 'shopkeeper_id', 'customer_id',
        'staff_id', 'admin_id', 'currency', 'amount', 'description', 'date',
    ];

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }


    public function shopkeeper()
    {
        return $this->belongsTo(Shopkeeper::class, 'shopkeeper_id');
    }


    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
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
