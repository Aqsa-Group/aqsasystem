<?php

namespace App\Models\Sarafi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ConversionTransfers extends Model
{
    use HasFactory;

    protected $connection = 'sarafi';
    protected $table = 'conversion_transfer';

    protected $fillable = [
        'form_customer',
        'from_currency',
        'withdrawal_amount',
        'from_account',
        'to_account',
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
        'currency_rate' => 'decimal:3',
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


      protected static function booted()
    {

        static::updating(function ($model) {
            $user = Auth::guard('sarafi')->user();
            $adminId = $user->admin_id ?? $user->id;
            Trash::create([
                'document_type' =>'تبدیل ارز و انتقال',
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
                'document_type' =>'تبدیل ارز و انتقال',
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
}