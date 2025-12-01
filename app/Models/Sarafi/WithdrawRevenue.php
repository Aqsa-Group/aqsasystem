<?php

namespace App\Models\Sarafi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WithdrawRevenue extends Model
{
    use HasFactory;

    protected $table = 'withdraw_revenue';
    protected $connection = 'sarafi';

    protected $fillable = [
        'customer_id',
        'user_id',
        'admin_id',
        'currency',
        'amount',
        'date',
        'description'
    ];


      /**
     * Accessor برای نام فارسی ارز
     */
    public function getCurrencyNameAttribute()
    {
        $currencyNames = [
            'usd' => 'دالر',
            'afn' => 'افغانی',
            'eur' => 'یورو',
            'irr' => 'تومان',
            'aed' => 'درهم',
            'try' => 'لیره',
            'cny' => 'یوان',
            'pkr' => 'کلدار',
            'gbp' => 'پوند',
            'jpy' => 'ین',
            'sar' => 'ریال سعودی',
            'inr' => 'روپیه',
        ];

        return $currencyNames[$this->currency] ?? $this->currency;
    }

    /**
     * Accessor برای نام فارسی ارز کوتاه
     */
    public function getCurrencyFaAttribute()
    {
        return $this->currency_name;
    }
    /**
     * رابطه با مشتری
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * رابطه با کاربر
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * رابطه با ادمین
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}