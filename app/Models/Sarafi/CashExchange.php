<?php

namespace App\Models\Sarafi;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CashExchange extends Model
{
    protected $connection = 'sarafi';
    protected $table = 'cash_exchange';

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }


    

    public function journals()
    {
        return $this->hasMany(Journals::class, 'buysell_id');
    }

    /* ================= HELPERS ================= */

    private static function currencyFa(string $code): string
    {
        return match (strtolower($code)) {
            'usd' => 'دالر',
            'afn' => 'افغانی',
            'irr' => 'ریال ایران',
            'eur' => 'یورو',
            'aed' => 'درهم امارات',
            'try' => 'لیر ترکیه',
            'cny' => 'یوان چین',
            'pkr' => 'روپیه پاکستان',
            'gbp' => 'پوند انگلیس',
            'jpy' => 'ین جاپان',
            'sar' => 'ریال سعودی',
            'inr' => 'روپیه هند',
            default => strtoupper($code),
        };
    }

    /* ================= BOOT ================= */

    protected static function booted()
    {
        /** ---------- CREATED ---------- */
        static::created(function ($model) {
            self::syncJournals($model);
        });

        /** ---------- UPDATING ---------- */
        static::updating(function ($model) {
            // حذف ژورنال‌های قبلی
            Journals::where('buysell_id', $model->id)->delete();
        });

        /** ---------- UPDATED ---------- */
        static::updated(function ($model) {
            self::syncJournals($model);
        });

        /** ---------- DELETING ---------- */
        static::deleting(function ($model) {
            Journals::where('buysell_id', $model->id)->delete();
        });
    }


      private static function syncJournals(self $model): void
    {
        $user = Auth::guard('sarafi')->user();
        $adminId = $user->admin_id ?? $user->id;

        $accountType = 'نقدی';

        DB::transaction(function () use ($model, $user, $adminId, $accountType) {

            // موجودی صندوق
            $fromBefore = (float) CurrencySafe::value($model->from_currency);
            $toBefore   = (float) CurrencySafe::value($model->to_currency);

            $fromAfter = $fromBefore - $model->amount;
            $toAfter   = $toBefore + $model->eq_amount;

            $withdrawDescription =
                "تبدیل ارز نقدی: "
                . self::currencyFa($model->from_currency)
                . " به "
                . self::currencyFa($model->to_currency)
                . " | مبلغ: "
                . number_format($model->amount, 2)
                . " | نرخ: "
                . number_format($model->exchange_rate, 6);

            $receiveDescription =
                "دریافت ارز نقدی: "
                . self::currencyFa($model->to_currency)
                . " از "
                . self::currencyFa($model->from_currency)
                . " | مبلغ: "
                . number_format($model->eq_amount, 2)
                . " | نرخ: "
                . number_format($model->exchange_rate, 6);

            // ---------- برد ----------
            Journals::create([
                'customer_id'   => null,
                'user_id'       => $user->id,
                'admin_id'      => $adminId,
                'currency'      => $model->from_currency,
                'type'          => 'برد',
                'account_type'  => $accountType,
                'amount'        => $model->amount,
                'balance'       => $fromAfter,
                'description'   => $withdrawDescription,
                'date'          => $model->date,
                'is_sell_table' => 1,
                'buysell_id'    => $model->id,
            ]);

            // ---------- رسید ----------
            Journals::create([
                'customer_id'   => null,
                'user_id'       => $user->id,
                'admin_id'      => $adminId,
                'currency'      => $model->to_currency,
                'type'          => 'رسید',
                'account_type'  => $accountType,
                'amount'        => $model->eq_amount,
                'balance'       => $toAfter,
                'description'   => $receiveDescription,
                'date'          => $model->date,
                'is_sell_table' => 1,
                'buysell_id'    => $model->id,
            ]);

            // ---------- آپدیت صندوق ----------
            CurrencySafe::query()->update([
                $model->from_currency => $fromAfter,
                $model->to_currency   => $toAfter,
            ]);
        });
    }
}
