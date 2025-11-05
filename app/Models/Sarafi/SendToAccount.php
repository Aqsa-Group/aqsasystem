<?php

namespace App\Models\Sarafi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Sarafi\Transaction;

class SendToAccount extends Model
{
    use HasFactory, SoftDeletes;

    protected $connection = 'sarafi';
    protected $table = 'account_to_account';
    
    /**
     * فیلدهای قابل پر شدن
     */
    protected $fillable = [
        'type',
        'form_customer',
        'to_customer',
        'tax_id',
        'user_id',
        'admin_id',
        'currency',
        'withdrawal_amount',
        'tax_amount',
        'received_amount',
        'description_sender',
        'description_receiver',
        'transaction_date',
        'by_sender',
        'by_receiver',
        'zone_sender',
        'zone_receiver',
        'status',
        'tracking_code',
        'account_to_id',
        'from_account',
        'to_account'
    ];

    /**
     * فیلدهای که باید به عنوان تاریخ مدیریت شوند
     */
    protected $dates = [
        'transaction_date',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * تبدیل انواع داده
     */
    protected $casts = [
        'withdrawal_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'received_amount' => 'decimal:2',
        'transaction_date' => 'date',
    ];

    /**
     * مقادیر پیش‌فرض برای فیلدها
     */
    protected $attributes = [
        'type' => 'بدون تفاوت',
        'status' => 'completed',
    ];

    /**
     * رابطه با مشتری مبدأ
     */
    public function fromCustomer()
    {
        return $this->belongsTo(Customer::class, 'form_customer');
    }



    /**
     * رابطه با مشتری مقصد
     */
    public function toCustomer()
    {
        return $this->belongsTo(Customer::class, 'to_customer');
    }

    /**
     * رابطه با مالیات
     */
    public function tax()
    {
        return $this->belongsTo(Customer::class, 'tax_id');
    }

    /**
     * رابطه با کاربر ایجادکننده
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * رابطه با ادمین تأییدکننده
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * رابطه با تراکنش‌ها
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'account_to_id');
    }

    /**
     * اسکوپ برای انتقالات موفق
     */
    public function scopeSuccessful($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * اسکوپ برای انتقالات در انتظار
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * اسکوپ برای انتقالات ناموفق
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * اسکوپ برای نوع انتقال
     */
    public function scopeType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * محاسبه کارمزد انتقال
     */
    public function calculateFee()
    {
        if ($this->type === 'باتفاوت' && $this->tax_amount) {
            return $this->tax_amount;
        }
        return 0;
    }

    /**
     * بررسی آیا انتقال قابل برگشت است
     */
    public function isReversible()
    {
        return $this->status === 'completed' && 
               $this->transaction_date->gt(now()->subDays(1));
    }

    /**
     * دریافت کد رهگیری فرمت شده
     */
    public function getFormattedTrackingCodeAttribute()
    {
        if (!$this->tracking_code) {
            return 'TRK-' . str_pad($this->id, 8, '0', STR_PAD_LEFT);
        }
        return 'TRK-' . str_pad($this->tracking_code, 8, '0', STR_PAD_LEFT);
    }

    /**
     * دریافت مقدار برداشت فرمت شده
     */
    public function getFormattedWithdrawalAmountAttribute()
    {
        return number_format($this->withdrawal_amount, 2) . ' ' . $this->currency;
    }

    /**
     * دریافت مقدار دریافتی فرمت شده
     */
    public function getFormattedReceivedAmountAttribute()
    {
        return number_format($this->received_amount, 2) . ' ' . $this->currency;
    }

    /**
     * بررسی وضعیت انتقال
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isFailed()
    {
        return $this->status === 'failed';
    }

    /**
     * بررسی نوع انتقال
     */
    public function isWithDifference()
    {
        return $this->type === 'باتفاوت';
    }

    public function isWithoutDifference()
    {
        return $this->type === 'بدون تفاوت';
    }
}