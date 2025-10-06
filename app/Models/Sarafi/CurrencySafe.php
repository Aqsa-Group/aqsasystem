<?php

namespace App\Models\Sarafi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Sarafi\User;

class CurrencySafe extends Model
{
    use HasFactory;

    protected $connection = 'sarafi';
    protected $table = 'currency_safe';


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
