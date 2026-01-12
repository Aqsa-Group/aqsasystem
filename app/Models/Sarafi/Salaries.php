<?php

namespace App\Models\Sarafi;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Salaries extends Model
{
    use HasFactory;

    protected $table = 'salary';
    protected $connection ='sarafi';

    protected $fillable = [
        'user_id',
        'admin_id',
        'staff_id',
        'customer_id',
        'amount',
        'currency',
        'payment_method',
        'paid_date',
        'description',
    ];

    protected $casts = [
        'paid_date' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function staff()
    {
        return $this->belongsTo(Staffs::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

   
}
