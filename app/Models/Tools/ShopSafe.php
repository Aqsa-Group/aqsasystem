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
