<?php

namespace App\Models\Sarafi;

use App\Models\Sarafi\Customer;
use App\Models\Sarafi\User;
use App\Models\Sarafi\Currency;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

        protected $connection = 'sarafi';
    protected $table = 'transactions';

    protected $fillable = [
        'customer_id',
        'user_id',
        'admin_id',
        'currency',
        'amount',
        'type',
        'zone',
        'by',
        'date',
        'description',
        'transaction_file',
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

 
  
}
