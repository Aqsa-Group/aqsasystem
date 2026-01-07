<?php

namespace App\Models\Sarafi;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Sarafi\User;
use App\Models\Sarafi\Customer;

class Journals extends Model
{
    use HasFactory;

    protected $connection = 'sarafi';
    protected $table = 'journal';

    /**
     * فیلدهای قابل پر شدن
     */
    protected $fillable = [
        'customer_id',
        'user_id',
        'admin_id',
        'currency',
        'type',          
        'account_type',  
        'amount',
        'balance',
        'description',
        'date'
        
    ];

    /**
     * تبدیل نوع داده‌ها
     */
    protected $casts = [
        'amount'  => 'decimal:2',
        'balance' => 'decimal:2',
        'date'    => 'date',
    ];

    /* =======================
     |       Relations
     |=======================*/

    // مشتری
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // کاربر ثبت‌کننده
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ادمین (در صورت وجود)
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /* =======================
     |       Scopes (اختیاری)
     |=======================*/

    public function scopeCurrency($query, $currency)
    {
        if ($currency) {
            $query->where('currency', $currency);
        }
    }

    public function scopeAccountType($query, $type)
    {
        if ($type) {
            $query->where('account_type', $type);
        }
    }

    public function scopeTransactionType($query, $type)
    {
        if ($type) {
            $query->where('type', $type);
        }
    }

    public function scopeBetweenDates($query, $from, $to)
    {
        if ($from && $to) {
            $query->whereBetween('date', [$from, $to]);
        }
    }


     protected $appends = ['currency_fa'];

    public function getCurrencyFaAttribute()
    {
        return config('currencies.' . $this->currency) ?? $this->currency;
    }
}
