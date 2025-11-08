<?php

namespace App\Models\Sarafi;

use Illuminate\Database\Eloquent\Model;

class Remittances extends Model
{
    protected $connection = 'sarafi';
    protected $table = 'remittance';
    protected $guarded = [];


        public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }



    /**
     * رابطه با مشتری مقصد
     */
    public function toAccount()
    {
        return $this->belongsTo(Customer::class, 'to_account');
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
    


}
