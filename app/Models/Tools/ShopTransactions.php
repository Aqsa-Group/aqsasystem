<?php

namespace App\Models\Tools;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopTransactions extends Model
{
    use HasFactory;

    protected $connection = 'tools';
    protected $table = 'shopaccount_to_sarafiaccount';

    protected $fillable = [
        'customer_id',
        'user_id',
        'admin_id',
        'currency',
        'amount',
        'type',
        'by',
        'date',
        'description',
        'transaction_file',
        'conversion_transfer_id',
        'conversion_in_account_id',
        'account_to_id'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'date' => 'date',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function conversionTransfer()
    {
        return $this->belongsTo(ConversionTransfers::class, 'conversion_transfer_id');
    }

    public function accounttoid()
    {
        return $this->belongsTo(SendToAccount::class, 'account_to_id');
    }

    public function conversionInAccount()
    {
        return $this->belongsTo(ConversionInAccounts::class, 'conversion_in_account_id');
    }

    public function currencyInfo()
    {
        return $this->belongsTo(Currency::class, 'currency', 'code');
    }

    public function shopSafe()
    {
        return $this->belongsTo(ShopSafe::class, 'admin_id', 'user_id');
    }

    // Scope برای تراکنش‌های مرتبط با تبدیل ارز
    public function scopeConversionTransactions($query)
    {
        return $query->whereNotNull('conversion_transfer_id');
    }

    // Scope برای تراکنش‌های عادی (غیر تبدیل ارز)
    public function scopeRegularTransactions($query)
    {
        return $query->whereNull('conversion_transfer_id');
    }

    // دسترسی‌دهنده برای نام نوع تراکنش
    public function getTypeNameAttribute()
    {
        return $this->type === 'رسید' ? 'دریافت' : 'برداشت';
    }

    // دسترسی‌دهنده برای مبلغ فرمت شده
    public function getFormattedAmountAttribute()
    {
        return number_format($this->amount, 2);
    }

    // دسترسی‌دهنده برای نام ارز
    public function getCurrencyNameAttribute()
    {
        $currencyMap = [
            'afn' => 'افغانی',
            'usd' => 'دالر',
            'irr' => 'تومان',
            'pkr' => 'کلدار',
        ];

        return $currencyMap[$this->currency] ?? $this->currency;
    }

    /**
     * بررسی تأثیر تراکنش بر صندوق
     */
    public function getSafeImpactAttribute()
    {
        if ($this->type === 'رسید') {
            return -$this->amount; // از صندوق کم می‌شود
        } else {
            return $this->amount; // به صندوق اضافه می‌شود
        }
    }

    /**
     * بررسی موجودی صندوق قبل از ثبت تراکنش
     */
    public function hasSufficientSafeBalance()
    {
        if ($this->type !== 'رسید') {
            return true; // برای برداشت، موجودی صندوق چک نمی‌شود
        }

        $safe = ShopSafe::where('user_id', $this->admin_id)
            ->where('admin_id', null)
            ->first();

        if (!$safe) {
            return false;
        }

        $currentBalance = $safe->{$this->currency} ?? 0;
        return $currentBalance >= $this->amount;
    }
}