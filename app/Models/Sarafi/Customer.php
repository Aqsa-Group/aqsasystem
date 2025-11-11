<?php

namespace App\Models\Sarafi;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Customer extends Authenticatable
{
    protected $connection = 'sarafi';
    protected $table = 'customers';
    
    protected $fillable = [
        'fullname',
        'image',
        'id_card_image', 
        'city',
        'phone',
        'idcard_number',
        'account_number',
        'whatsapp_number',
        'password', 
        'type',
        'user_id',
        'admin_id',
        'created_by',
    ];

    protected $hidden = [
        'password',
    ];



        // Relationship with sent remittances
    public function sentRemittances()
    {
        return $this->hasMany(Remittances::class, 'customer_id');
    }

    // Relationship with received remittances
    public function receivedRemittances()
    {
        return $this->hasMany(Remittances::class, 'to_account');
    }

    // Get all remittances (both sent and received)
    public function allRemittances()
    {
        return Remittances::where('customer_id', $this->id)
            ->orWhere('to_account', $this->id)
            ->get();
    }

    
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

    public function transactions()
{
    return $this->hasMany(\App\Models\Sarafi\Transaction::class, 'customer_id');
}


}