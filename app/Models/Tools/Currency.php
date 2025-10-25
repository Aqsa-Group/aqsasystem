<?php

namespace App\Models\Tools;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Tools\User;

class Currency extends Model
{
    use HasFactory;

    protected $connection = 'tools';
    protected $table = 'currencies';


    protected $fillable = [
        'user_id',
        'admin_id',
        'usd',
        'afn',
        'eur',
        'irr',
        'aed',
        'try',
        'cny',
        'pkr',
        'gbp',
        'jpy',
        'sar',
        'inr',
    ];

  
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
