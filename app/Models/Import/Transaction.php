<?php

namespace App\Models\Import;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $connection = 'import';
    protected $table = 'transactions';
    protected $guarded = [];

    public function sarafi()
    {
        return $this->belongsTo(Sarafi::class , 'sarafi_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class , 'customer_id');
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class , 'staff_id');
    }

    protected static function booted()
    {
        // وقتی تراکنش ایجاد شد
        static::created(function ($transaction) {
            self::applyTransaction($transaction, 'create');
        });

        // وقتی تراکنش حذف شد
        static::deleted(function ($transaction) {
            self::applyTransaction($transaction, 'delete');
        });
    }

    /**
     * هندل کردن تغییرات روی موجودی‌ها
     *
     * @param Transaction $transaction
     * @param string $action 'create' | 'delete'
     */
    protected static function applyTransaction($transaction, $action)
    {
        // نرمالایز کردن نام ارز
        $currency = strtoupper(trim(str_replace(['ٔ','‌',' '], '', $transaction->currency)));

        // فقط روی ارزهای تعریف شده
        if (!in_array($currency, ['AFN','USD','CNY','EUR','IRR','PKR'])) {
            return;
        }

        $amount = $transaction->amount;

        // اگر delete باشه همه چیز برعکس میشه
        $sign = $action === 'delete' ? -1 : 1;

        // ------------------ رسید ------------------
        if ($transaction->type === 'رسید') {
            // دوکان
            if ($transaction->person === 'دوکان') {
                $safe = Safe::first();
                if ($safe) {
                    $safe->$currency = ($safe->$currency ?? 0) - ($amount * $sign);
                    $safe->save();
                }
            }

            // صرافی
            if ($transaction->sarafi_id) {
                $sarafi = Sarafi::find($transaction->sarafi_id);
                if ($sarafi) {
                    $sarafi->$currency = ($sarafi->$currency ?? 0) + ($amount * $sign);
                    $sarafi->save();
                }
            }

            // مشتری
            if ($transaction->customer_id) {
                $customer = Customer::find($transaction->customer_id);
                if ($customer) {
                    $customer->$currency = ($customer->$currency ?? 0) + ($amount * $sign);
                    $customer->save();
                }
            }

            // کارمند
            if ($transaction->staff_id) {
                $staff = Staff::find($transaction->staff_id);
                if ($staff) {
                    $staff->$currency = ($staff->$currency ?? 0) + ($amount * $sign);
                    $staff->save();
                }
            }
        }

        // ------------------ برداشت ------------------
        if ($transaction->type === 'برداشت') {
            // دوکان
            if ($transaction->person === 'دوکان') {
                $safe = Safe::first();
                if ($safe) {
                    $safe->$currency = ($safe->$currency ?? 0) + ($amount * $sign);
                    $safe->save();
                }
            }

            // صرافی
            if ($transaction->sarafi_id) {
                $sarafi = Sarafi::find($transaction->sarafi_id);
                if ($sarafi) {
                    $sarafi->$currency = ($sarafi->$currency ?? 0) - ($amount * $sign);
                    $sarafi->save();
                }
            }

            // مشتری
            if ($transaction->customer_id) {
                $customer = Customer::find($transaction->customer_id);
                if ($customer) {
                    $customer->$currency = ($customer->$currency ?? 0) - ($amount * $sign);
                    $customer->save();
                }
            }

            // کارمند
            if ($transaction->staff_id) {
                $staff = Staff::find($transaction->staff_id);
                if ($staff) {
                    $staff->$currency = ($staff->$currency ?? 0) - ($amount * $sign);
                    $staff->save();
                }
            }
        }
    }
}
