<?php

namespace App\Models\Tools;

use Illuminate\Database\Eloquent\Model;

class ShopSafe extends Model
{

    protected $connection = 'tools';
    protected $table = 'shop_safe';

    
    protected $fillable = [
        'user_id',
        'admin_id',
        'afn',
        'usd',
        'irr',
        'pkr',
    ];


      protected $casts = [
        'afn' => 'decimal:2',
        'usd' => 'decimal:2',
        'irr' => 'decimal:2',
        'pkr' => 'decimal:2',
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
