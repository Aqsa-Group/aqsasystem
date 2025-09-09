<?php

namespace App\Models\Import;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Import\Customer;

class Sale extends Model
{

    protected $connection = 'import';
    protected $table = 'sales';

    protected $fillable = [
        'invoice_number',
        'sale_type',
        'user_id',
        'customer_id',
        'total_price',
        'discount'
    ];


    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }


    public function safes()
    {
        return $this->hasMany(Safe::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
