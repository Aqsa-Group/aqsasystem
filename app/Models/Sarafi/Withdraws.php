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
    protected static function syncJournals($model, $isDeleting = false)
    {
        DB::transaction(function () use ($model, $isDeleting) {
            $user = Auth::guard('sarafi')->user();
            // اگر کاربر لاگین نیست، از user_id مدل استفاده کن
            if (!$user) {
                $user = User::find($model->user_id);
            }
            $adminId = $model->admin_id;

            $currency = $model->currency;
            $amount = (float) $model->amount;

            // موجودی قبل از برداشت - از صندوق بگیرید
            $column = strtolower($currency);
            $safe = CurrencySafe::where('admin_id', $adminId)->first();
            
            if (!$safe) {
                throw new \Exception('صندوق ارزی یافت نشد');
            }

            // محاسبه بیلانس درست
            $currentBalance = (float) $safe->{$column};
            
            if ($isDeleting) {
                // اگر در حال حذف هستیم، بیلانس بعدی = بیلانس فعلی + مبلغ (چون مبلغ برمی‌گردد)
                $afterBalance = $currentBalance + $amount;
            } else {
                // اگر در حال ایجاد یا ویرایش هستیم، بیلانس بعدی = بیلانس فعلی - مبلغ
                // نکته: در کامپوننت قبلاً مبلغ را کم کرده‌ایم، پس بیلانس فعلی صندوق درست است
                $afterBalance = $currentBalance;
                // برای محاسبه بیلانس قبل: بیلانس فعلی + مبلغ (چون قبلاً کم شده)
                $beforeBalance = $currentBalance + $amount;
            }

            // حذف ژورنال‌های قبلی (برای ویرایش)
            Journals::where('withdraw_id', $model->id)->delete();

            // فقط اگر حذف نمی‌شود، ژورنال جدید ایجاد کن
            if (!$isDeleting) {
                // ثبت ژورنال برداشت (برد)
                Journals::create([
                    'customer_id' => null,
                    'withdraw_id' => $model->id,
                    'type' => 'برد',
                    'account_type' => 'نقدی',
                    'currency' => $currency,
                    'amount' => $amount,
                    'balance' => $afterBalance,
                    'description' => $model->description . 
                        ($model->expanses_type ? ' (' . $model->expanses_type . ')' : ''),
                    'staff_id' => $model->staff_id,
                    'user_id' => $model->user_id,
                    'admin_id' => $adminId,
                    'date' => $model->date,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
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