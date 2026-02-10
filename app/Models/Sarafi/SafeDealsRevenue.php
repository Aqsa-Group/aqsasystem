<?php

namespace App\Models\Sarafi;

use App\Models\Sarafi\SafeDeal;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Sarafi\Journals;
use App\Models\Sarafi\CurrencySafe;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SafeDealsRevenue extends Model
{
    use HasFactory;

    protected $table = 'safe_deals_revenue';
    protected $connection = 'sarafi';


    protected $fillable = [
        'user_id',
        'admin_id',
        'safe_deals_id',
        'currency',
        'amount',
        'type',
        'account_type',
        'date',
        'description',
        'safe_deal_id',
        'customer_id'
    ];

    // روابط
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }


    public function safeDeal()
    {
        return $this->belongsTo(SafeDeal::class, 'safe_deals_id');
    }

    public function getFormattedAmountAttribute()
    {
        return number_format($this->amount, 2);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }



    public function journals()
    {
        return $this->hasMany(Journals::class, 'safe_deal_revenue_id');
    }

    /**
     * Sync journals for withdraw
     */
   protected static function syncJournals($model)
{
    DB::transaction(function () use ($model) {

        $user = Auth::guard('sarafi')->user()
            ?? User::find($model->user_id);

        $adminId = $model->admin_id;
        $currency = strtolower($model->currency);
        $amount   = (float) $model->amount;

        /*
        |--------------------------------------------------------------------------
        | خواندن موجودی واقعی بر اساس account_type
        |--------------------------------------------------------------------------
        */
        if ($model->account_type === 'نقدی') {
            $account = CurrencySafe::where('admin_id', $adminId)
                ->lockForUpdate()
                ->first();

            if (!$account || !isset($account->{$currency})) {
                throw new \Exception("ارز {$currency} در صندوق یافت نشد");
            }

        } elseif ($model->account_type === 'بانکی') {
            $account = BankAccount::where('admin_id', $adminId)
                ->lockForUpdate()
                ->first();

            if (!$account || !isset($account->{$currency})) {
                throw new \Exception("ارز {$currency} در حساب بانکی یافت نشد");
            }

        } else {
            throw new \Exception("نوع حساب نامعتبر است: {$model->account_type}");
        }

        $currentBalance = (float) $account->{$currency};

        /*
        |--------------------------------------------------------------------------
        | حذف ژورنال‌های قبلی (برای ویرایش)
        |--------------------------------------------------------------------------
        */
        Journals::where('safe_deal_revenue_id', $model->id)->delete();

        /*
        |--------------------------------------------------------------------------
        | ثبت ژورنال
        |--------------------------------------------------------------------------
        */
        $safeBalance = $currentBalance;
        if ($model->account_type === 'نقدی') {
            // اگر بخوای فقط نمایش بدیم، نیازی به تغییر نیست
            if ($model->type === 'برد') {
                $safeBalance -= $amount; // فقط برای نمایش ژورنال
            } elseif ($model->type === 'رسید') {
                $safeBalance += $amount;
            }
        } else {
            // برای بانکی هم مثل نقدی، فقط نمایش
            if ($model->type === 'برد') {
                $safeBalance -= $amount;
            } elseif ($model->type === 'رسید') {
                $safeBalance += $amount;
            }
        }

        Journals::create([
            'customer_id' => null,
            'safe_deal_revenue_id' => $model->id,
            'type'        => $model->type,
            'account_type' => $model->account_type,
            'currency'    => $model->currency,
            'amount'      => $amount,
            'balance'     => null,
            'safe_balance'=> $safeBalance,
            'description' => $model->description
                . ($model->expanses_type ? ' (' . $model->expanses_type . ')' : ''),
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
            Journals::where('safe_deal_revenue_id', $model->id)->delete();
        });

        /** ---------- UPDATED ---------- */
        static::updated(function ($model) {
            self::syncJournals($model, false);
        });

        /** ---------- DELETING ---------- */
        static::deleting(function ($model) {
            // فقط ژورنال‌های مرتبط را حذف کن (به syncJournals نیازی نیست چون پول برگشته)
            Journals::where('safe_deal_revenue_id', $model->id)->delete();
        });
    }
}
