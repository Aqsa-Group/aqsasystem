<?php

namespace App\Models\Market;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\Market\{
    Deposit,
    Shop,
    Booth,
    Market,
    Shopkeeper,
    Safe,
    User
};

class Accounting extends Model
{
    protected $connection = 'market';
    protected $table = 'accountings';

    protected $fillable = [
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
        'cleared',
        'price',
        'currency',
        'paid',
        'remained',
        'paid_date',
        'expiration_date',
        'degree_price',
        'outside_id',
        'shopkeeper_receipt_id',
        'exchange_id'
    ];

    protected $casts = [
        'paid' => 'integer',
    ];

    /* ===================== Accessors ===================== */

    public function getAmountAttribute()
    {
        return abs((int) $this->paid);
    }

    public function getDirectionAttribute()
    {
        return $this->paid < 0 ? 'برداشت' : 'دریافت';
    }

    /* ===================== Relations ===================== */

    public function deposit()
    {
        return $this->hasOne(Deposit::class);
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
    public function safe()
    {
        return $this->hasMany(Safe::class);
    }
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function receipt()
    {
        return $this->belongsTo(ShopkeeperReceipt::class, 'shopkeeper_receipt_id');
    }
    /* ===================== Boot ===================== */


    protected static function booted()
    {
        static::creating(function ($accounting) {
            self::handleAdmin($accounting);
            self::calculate($accounting);
        });

        static::updating(function ($accounting) {
            self::handleAdmin($accounting);
            self::calculate($accounting);
        });

        static::created(function ($accounting) {
            self::syncDeposit($accounting);
        });

        static::updated(function ($accounting) {
            self::syncDeposit($accounting);
        });
    }

    /* ===================== Logic ===================== */

    private static function handleAdmin($accounting)
    {
        if (Auth::check() && !$accounting->admin_id) {
            $user = Auth::user();
            $accounting->admin_id = $user->role === 'admin'
                ? $user->id
                : $user->admin_id;
        }
    }

    private static function calculate($accounting)
    {
        if ($accounting->expanses_type !== 'پول برق') {
            return;
        }

        $lastRemained = self::where('expanses_type', 'پول برق')
            ->when($accounting->shop_id, fn($q) => $q->where('shop_id', $accounting->shop_id))
            ->when($accounting->booth_id, fn($q) => $q->where('booth_id', $accounting->booth_id))
            ->when($accounting->id, fn($q) => $q->where('id', '<', $accounting->id))
            ->latest('id')
            ->value('remained') ?? 0;

        $paid = $accounting->paid ?? 0;

        $accounting->remained = round(
            ($lastRemained + $accounting->price) - $paid,
            2
        );

        $accounting->cleared = $accounting->remained <= 0;
    }

    private static function syncDeposit($accounting)
    {
        $data = [
            'admin_id'       => $accounting->admin_id,
            'shop_id'        => $accounting->shop_id,
            'booth_id'       => $accounting->booth_id,
            'market_id'      => $accounting->market_id,
            'shopkeeper_id'  => $accounting->shopkeeper_id,
            'type'           => $accounting->type,
            'expanses_type'  => $accounting->expanses_type,
            'meter_serial'   => $accounting->meter_serial,
            'past_degree'    => $accounting->past_degree,
            'current_degree' => $accounting->current_degree,
            'price'          => $accounting->price,
            'currency'       => $accounting->currency,
            'paid'           => $accounting->paid ?? 0,
            'remained'       => $accounting->remained,
            'paid_date'      => $accounting->paid_date,
        ];

        $accounting->deposit()->updateOrCreate(
            ['accounting_id' => $accounting->id],
            $data
        );
    }
}
