<?php

namespace App\Models\Tools;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ConversionInAccounts extends Model
{
    protected $connection = 'tools';
    protected $table = 'transferinaccount';
    
    protected $fillable = [
        'customer_id',
        'from_currency',
        'buy_amount', 
        'currency_rate',
        'to_currency',
        'sell_amount',
        'by_sender',
        'by_receiver',
        'zone_sender',
        'zone_receiver',
        'description',
        'transaction_date',
        'user_id',
        'admin_id',
        'type'
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
}