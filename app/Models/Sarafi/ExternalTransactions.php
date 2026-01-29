<?php

namespace App\Models\Sarafi;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExternalTransactions extends Model
{
    protected $connection = 'sarafi';
    protected $table = 'external_transaction';

    protected $fillable = [
        'customer_id',
        'from_currency',
        'buy_amount',
        'currency_rate',
        'to_currency',
        'sell_amount',
        'account_type',
        'by_sender',
        'by_receiver',
        'zone_sender',
        'zone_receiver',
        'description',
        'transaction_date',
        'user_id',
        'admin_id',
        'type',
        'withdraw_safe_amount',
           ];

    protected $casts = [
        'buy_amount' => 'decimal:2',
        'sell_amount' => 'decimal:2',
        'currency_rate' => 'decimal:4',
        'transaction_date' => 'date'
    ];

    /**
     * رابطه با مشتری
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    /**
     * رابطه با کاربر ایجاد کننده
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * رابطه با تراکنش‌های مرتبط
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'conversion_transfer_id');
    }

    public static function currencyFa(string $currencyCode): string
{
    $currencyMap = [
        'afn' => 'افغانی',
        'usd' => 'دالر',
        'irr' => 'تومان',
        'eur' => 'یورو',
        'pkr' => 'کلدار',
        'aed' => 'درهم',
        'try' => 'لیره',
        'cny' => 'یوان',
        'gbp' => 'پوند',
        'jpy' => 'ین',
        'sar' => 'ریال سعودی',
        'inr' => 'روپیه',
    ];

    return $currencyMap[$currencyCode] ?? $currencyCode;
}
 

    /**
     * دسترسی به نام ارز مبدا
     */
    public function getFromCurrencyNameAttribute(): string
    {
        return $this->getCurrencyName($this->from_currency);
    }

    /**
     * دسترسی به نام ارز مقصد
     */
    public function getToCurrencyNameAttribute(): string
    {
        return $this->getCurrencyName($this->to_currency);
    }

    /**
     * تبدیل کد ارز به نام فارسی
     */
    public function getCurrencyName(string $currencyCode): string
    {
        $currencyMap = [
            'afn' => 'افغانی',
            'usd' => 'دالر',
            'irr' => 'تومان',
            'eur' => 'یورو',
            'pkr' => 'کلدار',
            'aed' => 'درهم',
            'try' => 'لیره',
            'cny' => 'یوان',
            'gbp' => 'پوند',
            'jpy' => 'ین',
            'sar' => 'ریال سعودی',
            'inr' => 'روپیه',
        ];

        return $currencyMap[$currencyCode] ?? $currencyCode;
    }

    /**
     * فرمت مبلغ برداشت با واحد ارز
     */
    public function getFormattedWithdrawalAttribute(): string
    {
        return number_format($this->buy_amount) . ' ' . $this->from_currency_name;
    }

    /**
     * فرمت مبلغ دریافت با واحد ارز
     */
    public function getFormattedReceivedAttribute(): string
    {
        return number_format($this->sell_amount) . ' ' . $this->to_currency_name;
    }



      protected static function booted()
    {

    static::created(function ($model) {
            self::syncJournals($model);
        });

        // before update
        static::updating(function ($model) {
            Journals::where('withdraw_external_safe_id', $model->id)->delete();
        });

        // after update
        static::updated(function ($model) {
            self::syncJournals($model);
        });

        // before delete
        static::deleting(function ($model) {
            Journals::where('withdraw_external_safe_id', $model->id)->delete();
        });


        static::updating(function ($model) {
            $user = Auth::guard('sarafi')->user();
            $adminId = $user->admin_id ?? $user->id;
            Trash::create([
                'document_type' =>'تبدیل ارز در حساب',
                'record_id' => $model->id,
                'action' => 'ویرایش',
                'document_discription'=>  $model->description,
                'old_data' => $model->getOriginal(),
                'new_data' => $model->getAttributes(),
                'registered_user'=> $model->user_id,
                'user_id'  => $user->id,
                'admin_id' => $adminId,
            ]);
        });

        static::deleting(function ($model) {
            $user = Auth::guard('sarafi')->user();
            $adminId = $user->admin_id ?? $user->id;
            Trash::create([
                'document_type' =>'تبدیل ارز در حساب',
                'record_id' => $model->id,
                'action' => 'حذف',
                'document_discription'=>  $model->description,
                'old_data' => $model->getAttributes(),
                'registered_user'=> $model->user_id,
                'user_id'     => $user->id,
                'admin_id'         => $adminId,
            ]);
        });
    }


private static function syncJournals(self $model): void
{
    $user    = Auth::guard('sarafi')->user();
    $adminId = $user->admin_id ?? $user->id;
    $accountType = 'نقدی';

    DB::transaction(function () use ($model, $user, $adminId, $accountType) {

        // ✅ موجودی واقعی صندوق قبل از تراکنش
        $fromSafeBalance = (float) CurrencySafe::where('admin_id', $adminId)
            ->value($model->from_currency);

        $toSafeBalance = (float) CurrencySafe::where('admin_id', $adminId)
            ->value($model->to_currency);

        $withdrawDescription =
            'تبدیل ارز نقدی: '
            . $model->getCurrencyName($model->from_currency)
            . ' به '
            . $model->getCurrencyName($model->to_currency)
            . ' | مبلغ: '
            . number_format($model->buy_amount, 2)
            . ' | نرخ: '
            . number_format($model->market_buy_rate, 6);

        $receiveDescription =
            'دریافت ارز نقدی: '
            . $model->getCurrencyName($model->to_currency)
            . ' از '
            . $model->getCurrencyName($model->from_currency)
            . ' | مبلغ: '
            . number_format($model->sell_amount, 2)
            . ' | نرخ: '
            . number_format($model->market_buy_rate, 6);

        /* ---------- برد (from_currency) ---------- */
        Journals::create([
            'customer_id'   => null,
            'user_id'       => $user->id,
            'admin_id'      => $adminId,
            'currency'      => $model->from_currency,
            'type'          => 'برد',
            'account_type'  => $accountType,
            'amount'        => $model->withdraw_safe_amount,
            'balance'       => null, 
            'safe_balance'  => $fromSafeBalance - $model->withdraw_safe_amount, 
            'description'   => $withdrawDescription,
            'date'          => $model->transaction_date,
            'is_sell_table' => 0,
            'withdraw_external_safe_id' => $model->id,
        ]);

     

    });
}



}
