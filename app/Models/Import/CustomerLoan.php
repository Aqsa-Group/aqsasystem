<?php

namespace App\Models\Import;

use Illuminate\Database\Eloquent\Model;
use App\Models\Import\Customer;
use App\Models\Import\User;
use App\Models\Import\CustomerBalance;
use App\Models\Import\Safe;
use App\Models\Import\CustomerStory;

class CustomerLoan extends Model
{
    protected $connection = 'import';
    protected $table = 'customer_loans';

    protected $fillable = [
        'customer_id',
        'type',
        'amount',
        'currency',
        'date',
        'description',
        'user_id',
        'admin_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    // =========================
    // BOOTED
    // =========================
    protected static function booted()
    {
     

        static::deleted(function ($loan) {
            $currency = strtolower($loan->currency);
            $safeColumn = strtoupper($loan->currency);

            $balance = CustomerBalance::where('customer_id', $loan->customer_id)->first();
            $safe = Safe::first();

            if ($loan->type === 'رسید') {
                // حذف رسید: برگرداندن مشتری و صندوق به حالت قبل
                if ($balance) {
                    $balance->$currency -= $loan->amount;
                    $balance->save();
                }

                if ($safe && isset($safe->$safeColumn)) {
                    $safe->$safeColumn -= $loan->amount;
                    $safe->save();
                }
            }

            if ($loan->type === 'برد') {
                // حذف برد: برگرداندن مشتری و صندوق به حالت قبل
                if ($balance) {
                    $balance->$currency += $loan->amount;
                    $balance->save();
                }

                if ($safe && isset($safe->$safeColumn)) {
                    $safe->$safeColumn += $loan->amount;
                    $safe->save();
                }
            }

            CustomerStory::where('CustomerLoan_id', $loan->id)->delete();
        });
    }

    // =========================
    // RELATIONS
    // =========================
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}