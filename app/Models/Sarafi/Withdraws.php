<?php

namespace App\Models\Sarafi;

use App\Models\Sarafi\Journals;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Withdraws extends Model
{
    use HasFactory;

    protected $table = 'withdraw';
    protected $connection = 'sarafi';

    protected $fillable = [
        'staff_id',
        'expanses_type',
        'amount',
        'currency',
        'date',
        'description',
        'admin_id',
        'user_id'
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function staff()
    {
        return $this->belongsTo(Staffs::class, 'staff_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id', 'id');
    }

    public function journals()
    {
        return $this->hasMany(Journals::class, 'withdraw_id');
    }

    /**
     * Sync journals for withdraw
     */
 protected static function syncJournals($model)
{
    DB::transaction(function () use ($model) {

        $user = Auth::guard('sarafi')->user();
        if (!$user) {
            $user = User::find($model->user_id);
        }

        $adminId = $model->admin_id;
        $currency = $model->currency;
        $amount   = (float) $model->amount;

        /*
        |--------------------------------------------------------------------------
        | فقط خواندن موجودی فعلی صندوق (بدون محاسبه)
        |--------------------------------------------------------------------------
        */
        $column = strtolower($currency);

        $safe = CurrencySafe::where('admin_id', $adminId)
            ->lockForUpdate()
            ->first();

        if (!$safe || !isset($safe->{$column})) {
            throw new \Exception("ارز {$currency} در صندوق یافت نشد");
        }

        $currentBalance = (float) $safe->{$column}; // ✅ فقط خواندن

     
        Journals::where('withdraw_id', $model->id)->delete();

        /*
        |--------------------------------------------------------------------------
        | ثبت ژورنال برداشت (نمایشی – بدون تغییر صندوق)
        |--------------------------------------------------------------------------
        */
        Journals::create([
            'customer_id' => null,
            'withdraw_id' => $model->id,
            'type'        => 'برد',
            'account_type'=> 'نقدی',
            'currency'    => $currency,
            'amount'      => $amount,
            'balance'     => 0,
            'safe_balance'=> $currentBalance, // ✅ دقیقاً مثل SafeDeal
            'description' => $model->description .
                ($model->expanses_type ? ' (' . $model->expanses_type . ')' : ''),
            'staff_id'    => $model->staff_id,
            'user_id'     => $model->user_id,
            'admin_id'    => $adminId,
            'date'        => $model->date,
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
    });
}

    /**
     * Boot method
     */
    protected static function booted()
    {
        /** ---------- CREATED ---------- */
        static::created(function ($model) {
            self::syncJournals($model, false);
        });

        /** ---------- UPDATING ---------- */
        static::updating(function ($model) {
            // حذف ژورنال‌های قبلی قبل از بروزرسانی
            Journals::where('withdraw_id', $model->id)->delete();
        });

        /** ---------- UPDATED ---------- */
        static::updated(function ($model) {
            // ایجاد ژورنال جدید پس از بروزرسانی
            self::syncJournals($model, false);
        });

        /** ---------- DELETING ---------- */
        static::deleting(function ($model) {
            // فقط ژورنال‌های مرتبط را حذف کن (به syncJournals نیازی نیست چون پول برگشته)
            Journals::where('withdraw_id', $model->id)->delete();
        });
    }
}