<?php

namespace App\Models\Sarafi;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChangerDeal extends Model
{
    use HasFactory;

    protected $table = 'changerdeals';
    protected $connection = 'sarafi';


    
    protected $fillable = [
        'from_customer',
        'to_customer',
        'from_sarafi',
        'to_sarafi',
        'currency',
        'zone',
        'amount',
        'date',
        'description',
        'user_id',
        'admin_id',
        'account_type'
    ];

    // رابطه با مشتری فرستنده
    public function fromCustomer()
    {
        return $this->belongsTo(Customer::class, 'from_customer', 'id');
    }

    // رابطه با مشتری گیرنده
    public function toCustomer()
    {
        return $this->belongsTo(Customer::class, 'to_customer', 'id');
    }

    // رابطه با صرافی فرستنده (کاربر)
    public function fromSarafiUser()
    {
        return $this->belongsTo(User::class, 'from_sarafi', 'id');
    }

    // رابطه با صرافی گیرنده (کاربر)
    public function toSarafiUser()
    {
        return $this->belongsTo(User::class, 'to_sarafi', 'id');
    }

    // رابطه با کاربر ثبت کننده
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // رابطه با ادمین
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id', 'id');
    }
}