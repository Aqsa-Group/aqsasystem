<?php

namespace App\Models\Market;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ShopkeeperReceipt extends Model
{
    protected $connection = 'market';
    protected $table = 'shopkeeper_receipts';

    protected $fillable = [
        'market_id',
        'shopkeeper_id',
        'shop_id',
        'booth_id',
        'amount',
        'currency',
        'expanses_type',
        'description',
        'date',
        'admin_id',
        'type',             // دوکان / غرفه
    ];

    protected $casts = [
        'date'   => 'date',
        'amount' => 'integer',
    ];

    /* ========================== روابط ========================== */
    public function market()
    {
        return $this->belongsTo(Market::class, 'market_id');
    }

    public function shopkeeper()
    {
        return $this->belongsTo(Shopkeeper::class, 'shopkeeper_id');
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }

    public function booth()
    {
        return $this->belongsTo(Booth::class, 'booth_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /* =================== همگام‌سازی با حسابداری و لاگ =================== */
    protected static function booted()
    {
        // ۱. ایجاد رسید
        static::created(function ($receipt) {
            // ایجاد رکورد حسابداری
            Accounting::create([
                'shopkeeper_receipt_id' => $receipt->id,
                'type'            => $receipt->type,
                'shop_id'         => $receipt->shop_id,
                'booth_id'        => $receipt->booth_id,
                'market_id'       => $receipt->market_id,
                'shopkeeper_id'   => $receipt->shopkeeper_id,
                'admin_id'        => $receipt->admin_id,
                'expanses_type'   => $receipt->expanses_type,
                'price'           => $receipt->amount,
                'currency'        => $receipt->currency,
                'paid'            => $receipt->amount,
                'remained'        => 0,
                'cleared'         => true,
                'paid_date'       => $receipt->date,
                'expiration_date' => $receipt->date,
            ]);

            // ثبت لاگ در deposit_logs
            DepositLog::create([
                'shopkeeper_receipt_id' => $receipt->id,
                'deposit_id'            => null,               // مربوط به رسید است، نه سپرده
                'user_id'               => $receipt->admin_id,
                'admin_id'              => $receipt->admin_id,
                'expanses_type'         => $receipt->expanses_type,
                'market_id'             => $receipt->market_id,
                'shop_id'               => $receipt->shop_id,
                'shopkeeper_id'         => $receipt->shopkeeper_id,
                'old_paid'              => 0,
                'old_remained'          => 0,
                'new_paid'              => $receipt->amount,
                'new_remained'          => 0,
            ]);
        });

        // ۲. ویرایش رسید
        static::updated(function ($receipt) {
            // گرفتن مقادیر قدیمی از حسابداری (قبل از به‌روزرسانی)
            $oldAccounting = Accounting::where('shopkeeper_receipt_id', $receipt->id)->first();
            $oldPaid = $oldAccounting->paid ?? 0;
            $oldRemained = $oldAccounting->remained ?? 0;

            // به‌روزرسانی رکورد حسابداری
            Accounting::where('shopkeeper_receipt_id', $receipt->id)->update([
                'type'            => $receipt->type,
                'shop_id'         => $receipt->shop_id,
                'booth_id'        => $receipt->booth_id,
                'market_id'       => $receipt->market_id,
                'shopkeeper_id'   => $receipt->shopkeeper_id,
                'admin_id'        => $receipt->admin_id,
                'expanses_type'   => $receipt->expanses_type,
                'price'           => $receipt->amount,
                'currency'        => $receipt->currency,
                'paid'            => $receipt->amount,
                'remained'        => 0,
                'cleared'         => true,
                'paid_date'       => $receipt->date,
                'expiration_date' => $receipt->date,
            ]);

            // ثبت لاگ ویرایش در deposit_logs
            DepositLog::create([
                'shopkeeper_receipt_id' => $receipt->id,
                'deposit_id'            => null,
                'user_id'               => $receipt->admin_id,
                'admin_id'              => $receipt->admin_id,
                'expanses_type'         => $receipt->expanses_type,
                'market_id'             => $receipt->market_id,
                'shop_id'               => $receipt->shop_id,
                'shopkeeper_id'         => $receipt->shopkeeper_id,
                'old_paid'              => $oldPaid,
                'old_remained'          => $oldRemained,
                'new_paid'              => $receipt->amount,
                'new_remained'          => 0,
            ]);
        });

        // ۳. حذف رسید
        static::deleted(function ($receipt) {
            // حذف رکورد حسابداری
            Accounting::where('shopkeeper_receipt_id', $receipt->id)->delete();

        });
    }
}