<?php

namespace App\Models\Tools;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $connection = 'tools';
    protected $table = 'loans';

    protected $fillable = [
        'customer_id',
        'user_id',
        'admin_id',
        'currency',
        'amount',
        'type',
        'date',
        'description',
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

    public function getTypeNameAttribute()
    {
        return $this->type === 'رسید' ? 'دریافت' : 'پرداخت';
    }

    public function getFormattedAmountAttribute()
    {
        return number_format($this->amount, 2);
    }

    public function getCurrencyNameAttribute()
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

        return $currencyMap[$this->currency] ?? $this->currency;
    }

    public static function getCustomerBalance($customerId, $currency)
    {
        $loans = self::where('customer_id', $customerId)
                    ->where('currency', $currency)
                    ->get();

        $balance = 0;
        foreach ($loans as $loan) {
            if ($loan->type === 'برد') {
                $balance += $loan->amount; 
            } else { // رسید
                $balance -= $loan->amount; 
            }
        }

        return $balance;
    }

    public static function getCustomerTotalBalance($customerId)
    {
        $loans = self::where('customer_id', $customerId)->get();
        
        $balances = [];
        foreach ($loans as $loan) {
            if (!isset($balances[$loan->currency])) {
                $balances[$loan->currency] = 0;
            }
            
            if ($loan->type === 'برد') {
                $balances[$loan->currency] += $loan->amount;
            } else {
                $balances[$loan->currency] -= $loan->amount;
            }
        }

        return $balances;
    }

    // متد جدید برای گرفتن وضعیت قرضه بر اساس ارز
    public static function getLoanStatusByCurrency($customerId, $adminId = null)
    {
        $query = self::where('customer_id', $customerId);
        
        if ($adminId) {
            $query->where('admin_id', $adminId);
        }

        $loans = $query->get();

        $statusByCurrency = [];
        foreach ($loans as $loan) {
            $currency = $loan->currency;
            
            if (!isset($statusByCurrency[$currency])) {
                $statusByCurrency[$currency] = [
                    'total_loan' => 0,
                    'total_paid' => 0,
                    'remaining_loan' => 0
                ];
            }

            if ($loan->type === 'برد') {
                $statusByCurrency[$currency]['total_loan'] += $loan->amount;
            } else {
                $statusByCurrency[$currency]['total_paid'] += $loan->amount;
            }

            $statusByCurrency[$currency]['remaining_loan'] = 
                $statusByCurrency[$currency]['total_loan'] - $statusByCurrency[$currency]['total_paid'];
        }

        return $statusByCurrency;
    }
}