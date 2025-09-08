<?php

namespace App\Models\Market;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class LoanLog extends Model
{
    protected $connection= 'market';
    protected $table= 'loan_logs';
    
    protected $fillable = [
        'loan_id',
        'person',
        'related_id',
        'related_type',
        'currency',
        'amount',
        'expanses_type',
        'description',
        'date',
        'admin_id',

    ];

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
