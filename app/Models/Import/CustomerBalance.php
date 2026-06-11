<?php

namespace App\Models\Import;

use Illuminate\Database\Eloquent\Model;

class CustomerBalance extends Model
{
    protected $connection = 'import';
    
protected $table = 'customer‌_balances';    
    protected $fillable = [
        'customer_id',
        'afn',
        'usd',
        'pkr',
        'eur',
        'user_id',
        'admin_id',
    ];
    
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