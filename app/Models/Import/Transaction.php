<?php

namespace App\Models\Import;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{

     protected $connection = 'import';
     protected $table = 'transactions';
     protected $guarded = [];

     public function sarafi(){
         return $this->belongsTo(Sarafi::class , 'sarafi_id');
     }


       public function customer(){
         return $this->belongsTo(Customer::class , 'customer_id');
     }

         public function staff(){
         return $this->belongsTo(Staff::class , 'staff_id');
     }


   protected static function booted()
{
    static::created(function ($transaction) {
        $currency = strtoupper(trim(str_replace(['ٔ','‌',' '], '', $transaction->currency)));

        if (!in_array($currency, ['AFN','USD','CNY','EUR','IRR','PKR'])) {
            return;
        }

        $amount = $transaction->amount;

        if ($transaction->type === 'رسید') {
            if ($transaction->person === 'دوکان') {
                $safe = Safe::first();
                if ($safe) {
                    $safe->$currency = ($safe->$currency ?? 0) - $amount;
                    $safe->save();
                }
            }

            if ($transaction->sarafi_id) {
                $sarafi = Sarafi::find($transaction->sarafi_id);
                if ($sarafi) {
                    $sarafi->$currency = ($sarafi->$currency ?? 0) + $amount;
                    $sarafi->save();
                }
            }

            if ($transaction->customer_id) {
                $customer = Customer::find($transaction->customer_id);
                if ($customer) {
                    $customer->$currency = ($customer->$currency ?? 0) + $amount;
                    $customer->save();
                }
            }

            if ($transaction->staff_id) {
                $staff = Staff::find($transaction->staff_id);
                if ($staff) {
                    $staff->$currency = ($staff->$currency ?? 0) + $amount;
                    $staff->save();
                }
            }
        }

        if ($transaction->type === 'برداشت') {
            if ($transaction->person === 'دوکان') {
                $safe = Safe::first();
                if ($safe) {
                    $safe->$currency = ($safe->$currency ?? 0) + $amount;
                    $safe->save();
                }
            }

            if ($transaction->sarafi_id) {
                $sarafi = Sarafi::find($transaction->sarafi_id);
                if ($sarafi) {
                    $sarafi->$currency = ($sarafi->$currency ?? 0) - $amount;
                    $sarafi->save();
                }
            }

            if ($transaction->customer_id) {
                $customer = Customer::find($transaction->customer_id);
                if ($customer) {
                    $customer->$currency = ($customer->$currency ?? 0) - $amount;
                    $customer->save();
                }
            }

            if ($transaction->staff_id) {
                $staff = Staff::find($transaction->staff_id);
                if ($staff) {
                    $staff->$currency = ($staff->$currency ?? 0) - $amount;
                    $staff->save();
                }
            }
        }
    });
}

    
}
