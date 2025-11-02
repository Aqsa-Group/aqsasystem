<?php

namespace App\Models\Tools;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Customer extends Authenticatable
{
    protected $connection = 'tools';
    protected $table = 'customers';

    protected $fillable = [
        'fullname',
        'image',
        'id_card_image',
        'city',
        'phone',
        'idcard_number',
        'account_number',
        'password',
        'user_id',
        'admin_id',
        'created_by',
    ];

    protected $hidden = [
        'password',
    ];


    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
    
    public function loan()
    {
        return $this->hasMany(Loan::class);
    }
    
    public function transactions()
    {
        return $this->hasMany(\App\Models\Sarafi\Transaction::class, 'customer_id');
    }

     public function shopTransactions()
    {
        return $this->hasMany(ShopTransactions::class, 'customer_id');
    }




        public function sales()
    {
        return $this->hasMany(Sale::class);
    }
    
}
