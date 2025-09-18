<?php

namespace App\Models\Import;

use Illuminate\Database\Eloquent\Model;

class Exchange extends Model
{
    protected $connection='import';
    protected $table='exchange';
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
        static::created(function ($exchange) {
            $from = str_replace(['ٔ','‌',' '], '', $exchange->from);
            $to = str_replace(['ٔ','‌',' '], '', $exchange->to);
            $amount = $exchange->amount;
            $total = $exchange->total;

            if ($exchange->sarafi_id) {
                $sarafi = Sarafi::find($exchange->sarafi_id);
                if ($sarafi) {
                    $sarafi->$from = ($sarafi->$from ?? 0) - $amount;
                    $sarafi->$to = ($sarafi->$to ?? 0) + $total;
                    $sarafi->save();
                }
            }

            if ($exchange->type === 'تبدیل ارز  دوکان') {
                $safe = Safe::first(); 
                if ($safe) {
                    $safe->$from = ($safe->$from ?? 0) - $amount;
                    $safe->$to = ($safe->$to ?? 0) + $total;
                    $safe->save();
                }
            }

            if ($exchange->customer_id) {
                $customer = Customer::find($exchange->customer_id);
                if ($customer) {
                    $customer->$from = ($customer->$from ?? 0) - $amount;
                    $customer->$to = ($customer->$to ?? 0) + $total;
                    $customer->save();
                }
            }

            if ($exchange->staff_id) {
                $staff = Staff::find($exchange->staff_id);
                if ($staff) {
                    $staff->$from = ($staff->$from ?? 0) - $amount;
                    $staff->$to = ($staff->$to ?? 0) + $total;
                    $staff->save();
                }
            }

            // if ($exchange->type === 'تبدیل ارز در حساب متفرقه') {
               
            // }
        });
    }

}
