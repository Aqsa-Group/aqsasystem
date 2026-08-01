<?php

namespace App\Models\Market;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerConversion extends Model
{
    use HasFactory;

    protected $connection = 'market';

    protected $table = 'customer_conversions';

    protected $fillable = [
        'customer_id',
        'admin_id',
        'from_currency',
        'to_currency',
        'withdraw_amount',
        'receive_amount',
        'rate',
        'description',
        'transaction_date',
    ];

    protected $casts = [
        'withdraw_amount' => 'double',
        'receive_amount'  => 'double',
        'rate'            => 'double',
    ];

  
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

   
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}