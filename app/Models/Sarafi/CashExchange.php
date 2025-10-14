<?php

namespace App\Models\Sarafi;

use Illuminate\Database\Eloquent\Model;

class CashExchange extends Model
{
    protected $connection = 'sarafi';
    protected $table = 'cash_exchange';

    protected $guarded = [];

     public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

}


