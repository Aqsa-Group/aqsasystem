<?php

namespace App\Models\Import;

use Illuminate\Database\Eloquent\Model;

class CustomerStory extends Model
{
    protected $connection = 'import';

    protected $table = 'customer_stories';

    protected $fillable = [
        'customer_id',
        'type',
        'amount',
        'currency',
        'date',
        'description',
        'user_id',
        'admin_id',
        'CustomerLoan_id',
        'sale_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
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
    
      public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
 
    public function loan()
    {
        return $this->belongsTo(CustomerLoan::class, 'CustomerLoan_id');
    }
}