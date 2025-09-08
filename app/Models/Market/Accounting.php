<?php

namespace App\Models\Market;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\Market\Deposit;
use App\Models\Market\Shop;
use App\Models\Market\Booth;
use App\Models\Market\Market;
use App\Models\Market\Shopkeeper;
use App\Models\Market\Safe;
use App\Models\Market\User;

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
        'degree_price'
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

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

    /*
    |--------------------------------------------------------------------------
    | Boot Method
    |--------------------------------------------------------------------------
    */

    protected static function booted()
    {
        // هنگام ایجاد Accounting
        static::creating(function ($accounting) {
            // تعیین عنوان expanse
            if ($accounting->expanses_type === 'کرایه') {
                $shop = Shop::find($accounting->shop_id);
                $accounting->expanses_type = ($shop && $shop->customer_id && $shop->collect === 'market')
                    ? 'کرایه دوکان‌های گروی و سرقفلی'
                    : 'کرایه';
            }

            // تعیین admin_id
            if (Auth::check() && !$accounting->admin_id) {
                $user = Auth::user();
                $accounting->admin_id = $user->role === 'admin' ? $user->id : $user->admin_id;
            }
        });

        // بعد از ساخته شدن Accounting
        static::created(function ($accounting) {
            // ایجاد Deposit
            $accounting->deposit()->create([
                'accounting_id'   => $accounting->id,
                'shop_id'         => $accounting->shop_id,
                'booth_id'        => $accounting->booth_id,
                'market_id'       => $accounting->market_id,
                'shopkeeper_id'   => $accounting->shopkeeper_id,
                'type'            => $accounting->type,
                'expanses_type'   => $accounting->expanses_type,
                'meter_serial'    => $accounting->meter_serial,
                'past_degree'     => $accounting->past_degree,
                'current_degree'  => $accounting->current_degree,
                'price'           => $accounting->price,
                'currency'        => $accounting->currency,
                'paid'            => $accounting->paid ?? 0,
                'remained'        => $accounting->price - ($accounting->paid ?? 0),
                'paid_date'       => $accounting->paid_date,
            ]);

            // بروزرسانی rent_money مشتری مرتبط
            if ($accounting->paid > 0 && $accounting->shop_id) {
                $shop = Shop::find($accounting->shop_id);
                if ($shop && $shop->customer_id) {
                    $customer = Customer::find($shop->customer_id);
                    if ($customer) {
                        $customer->rent_money = ($customer->rent_money ?? 0) + $accounting->paid;
                        $customer->save();
                    }
                }
            }
        });

        // قبل از بروزرسانی Accounting
        static::updating(function ($accounting) {
            $originalPaid = $accounting->getOriginal('paid');
            $newPaid = $accounting->paid;

            if ($originalPaid != $newPaid && $accounting->shop_id) {
                $shop = Shop::find($accounting->shop_id);
                if ($shop && $shop->customer_id) {
                    $customer = Customer::find($shop->customer_id);
                    if ($customer) {
                        // کم کردن مقدار قدیم و اضافه کردن مقدار جدید
                        $customer->rent_money = ($customer->rent_money ?? 0) - $originalPaid + $newPaid;
                        $customer->save();
                    }
                }
            }
        });
    }
}
