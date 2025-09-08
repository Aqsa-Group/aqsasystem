<?php

namespace App\Models\Market;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class DepositLog extends Model
{

    protected $connection= 'market';
    protected $table = 'deposit_logs';

    protected $fillable = [
        'deposit_id',
        'user_id',
        'expanses_type',
        'market_id',
        'shop_id',
        'shopkeeper_id',
        'old_paid',
        'old_remained',
        'new_paid',
        'new_remained',
        'admin_id',

    ];

    public function deposit(): BelongsTo
    {
        return $this->belongsTo(Deposit::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function market(): BelongsTo
    {
        return $this->belongsTo(Market::class);
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function shopkeeper(): BelongsTo
    {
        return $this->belongsTo(Shopkeeper::class);
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
