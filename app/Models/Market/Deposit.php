<?php

namespace App\Models\Market;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class Deposit extends Model
{

    protected $connection = 'market';
    protected $table = 'deposits';

    protected $fillable = [
        'accounting_id',
        'shop_id',
        'booth_id',
        'market_id',
        'shopkeeper_id',
        'admin_id',
        'type',
        'expanses_type',
        'meter_serial',
        'past_degree',
        'current_degree',
        'price',
        'currency',
        'paid',
        'remained',
        'paid_date',
        'admin_id',

    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function accounting()
    {
        return $this->belongsTo(Accounting::class);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function booth()
    {
        return $this->belongsTo(Booth::class);
    }

    public function market()
    {
        return $this->belongsTo(Market::class);
    }

    public function shopkeeper()
    {
        return $this->belongsTo(Shopkeeper::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Boot Method
    |--------------------------------------------------------------------------
    */

    protected static function booted()
    {
        // ذخیره admin_id هنگام ساخت
        static::creating(function ($deposit) {
            if (Auth::check()) {
                $user = Auth::user();
                $deposit->admin_id = $user->role === 'admin' ? $user->id : $user->admin_id;
            }
        });

        // بروزرسانی وضعیت حساب هنگام آپدیت
        static::updated(function ($deposit) {
            if ($deposit->accounting) {
                $deposit->accounting->update([
                    'cleared'  => $deposit->remained == 0 ? 1 : 0,
                    'paid'     => $deposit->paid,
                    'remained' => $deposit->remained,
                ]);
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Query Scopes
    |--------------------------------------------------------------------------
    */

    public static function getEloquentQuery(): Builder
    {
        return parent::query()
            ->where('remained', '>', 0)
            ->with([
                'accounting',
                'accounting.market',
                'accounting.shop',
                'accounting.booth',
                'accounting.shopkeeper',
            ]);
    }
}
