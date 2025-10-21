<?php

namespace App\Models\Tools;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Tools\User;

class CurrencySafe extends Model
{
    use HasFactory;

    protected $connection = 'tools';
    protected $table = 'currency_safe';


    protected $fillable = [
        'user_id',
        'admin_id',
        'usd',
        'afn',
        'irr',
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
