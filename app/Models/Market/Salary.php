<?php

namespace App\Models\Market;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Salary extends Model
{

    protected $connection= 'market';
    protected $table= 'salaries';
    
    protected $fillable = [
        'market_id',
        'staff_id',
        'loan_id',
        'salary',
        'paid',
        'reduce_from',
        'remained',
        'loan',
        'currency',
        'is_reduce',
        'reduce_loan',
        'new_loan',
        'paid_date',
        'admin_id',


    ];

    protected $casts = [
        'is_reduce' => 'boolean',
        'paid_date' => 'date',
    ];

    
    public function market()
    {
        return $this->belongsTo(Market::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function loan()
    {
        return $this->belongsTo(Loan::class);
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
