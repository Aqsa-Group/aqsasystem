<?php

namespace App\Models\Sarafi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RemittanceApproval extends Model
{
    use HasFactory;
   
    protected $connection = 'sarafi';
    protected $table = 'remittance_approvals';
    
    protected $fillable = [
        'remittance_id',
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
        'remittance_image',
        'approved',
        'approved_by',
        'approved_at',
        'approval_notes'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'date' => 'date',
        'approved_at' => 'datetime',
    ];

    // Relationships
    public function remittance()
    {
        return $this->belongsTo(Remittances::class, 'remittance_id');
    }

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

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Scope for pending approvals
    public function scopePending($query)
    {
        return $query->where('approved', 0);
    }

    public function scopeApproved($query)
    {
        return $query->where('approved', 1);
    }

    public function scopeRejected($query)
    {
        return $query->where('approved', 2);
    }
}