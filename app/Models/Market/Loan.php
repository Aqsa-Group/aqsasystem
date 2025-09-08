<?php

namespace App\Models\Market;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Loan extends Model
{
    protected $connection= 'market';
    protected $table= 'loans';
    
    protected $fillable = [
        'market_id',
        'shopkeeper_id',
        'staff_id',
        'admin_id',
        'customer_id',
        'type',
        'amount',
        'description',
        'date',
        'person',
        'admin_id',
        'currency',
    ];

    public function market()
    {
        return $this->belongsTo(Market::class);
    }

    public function shopkeeper()
    {
        return $this->belongsTo(Shopkeeper::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function salary()
    {
        return $this->hasMany(Salary::class);
    }




    public function payments()
    {
        return $this->hasMany(\App\Models\Market\Payment::class);
    }

    public function totalPaid(): int
    {
        return $this->payments()->sum('amount');
    }

    public function remainingAmount(): int
    {
        return $this->amount - $this->totalPaid();
    }


    
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
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
