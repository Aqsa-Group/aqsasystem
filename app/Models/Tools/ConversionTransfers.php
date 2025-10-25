<?php

namespace App\Models\Tools;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConversionTransfers extends Model
{
    use HasFactory;

    protected $connection = 'tools';
    protected $table = 'conversion_transfer';

    protected $fillable = [
        'form_customer',
        'from_currency',
        'withdrawal_amount',
        'to_customer',
        'to_currency',
        'received_amount',
        'currency_rate',
        'transaction_date',
        'description',
        'zone_sender',
        'zone_receiver',
        'by_sender',
        'by_receiver',
        'user_id',
        'admin_id',
        'type'
    ];

    protected $casts = [
        'withdrawal_amount' => 'decimal:2',
        'received_amount' => 'decimal:2',
        'currency_rate' => 'decimal:6',
    ];

    public function fromCustomer()
    {
        return $this->belongsTo(Customer::class, 'form_customer');
    }

    public function toCustomer()
    {
        return $this->belongsTo(Customer::class, 'to_customer');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'conversion_transfer_id');
    }

    public function withdrawalTransaction()
    {
        return $this->hasOne(Transaction::class, 'conversion_transfer_id')
                    ->where('type', 'برداشت');
    }

    public function depositTransaction()
    {
        return $this->hasOne(Transaction::class, 'conversion_transfer_id')
                    ->where('type', 'رسید');
    }

    // دسترسی‌دهنده برای نام ارز مبدا
    public function getFromCurrencyNameAttribute()
    {
        return $this->getCurrencyName($this->from_currency);
    }

    // دسترسی‌دهنده برای نام ارز مقصد
    public function getToCurrencyNameAttribute()
    {
        return $this->getCurrencyName($this->to_currency);
    }

    // دسترسی‌دهنده برای مبلغ برداشت فرمت شده
    public function getFormattedWithdrawalAmountAttribute()
    {
        return number_format($this->withdrawal_amount, 2);
    }

    // دسترسی‌دهنده برای مبلغ دریافت فرمت شده
    public function getFormattedReceivedAmountAttribute()
    {
        return number_format($this->received_amount, 2);
    }

    // دسترسی‌دهنده برای نرخ ارز فرمت شده
    public function getFormattedCurrencyRateAttribute()
    {
        return number_format($this->currency_rate, 4);
    }

    private function getCurrencyName($currencyCode)
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
}