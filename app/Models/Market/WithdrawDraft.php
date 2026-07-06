<?php

namespace App\Models\Market;

use Illuminate\Database\Eloquent\Model;

class WithdrawDraft extends Model
{
    protected $connection = 'market';
    protected $table = 'withdraw_drafts';

    protected $fillable = [
        'expanses_type',
        'currency',
        'amount',
        'staff_id',
        'customer_id',
        'description',
        'admin_id',
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}