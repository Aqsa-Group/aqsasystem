<?php

namespace App\Models\Market;


use App\Models\Market\Accounting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Safe extends Model
{
    protected $connection= 'market';
    protected $table= 'safes';
    
    protected $fillable = [
        'accounting_id',
        'admin_id',
        'af',
        'us',
        'er',
        'ir',
        'power',
        'water',
        'rent',
        'tax',
        'safai',
        'parking',
        'customer',
        'outside',
    ];


    public function accounting(){
        return $this->belongsTo(Accounting::class);
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
