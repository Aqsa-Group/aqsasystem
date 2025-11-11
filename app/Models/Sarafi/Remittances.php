<?php

namespace App\Models\Sarafi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Remittances extends Model
{
    use HasFactory;
   
    protected $connection = 'sarafi';
    protected $table = 'remittance';
    
    protected $fillable = [
        'customer_id',
        'to_account',
        'user_id',
        'admin_id',
        'source_account',
        'currency',
        'amount',
        'date',
        'clock',
        'tracking_code',
        'from_bank',
        'to_bank',
        'zone',
        'giver_name',
        'description',
        'remittance_image'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'date' => 'date',
    ];

    // Relationships
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function recipient()
    {
        return $this->belongsTo(Customer::class, 'to_account');
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