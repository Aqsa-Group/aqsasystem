<?php

namespace App\Models\Sarafi;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SafeDeal extends Model
{
    protected $table = 'safe_deals';
     protected $connection = 'sarafi';


    protected $fillable = [
        'from',
        'to',
        'from_currency',
        'to_currency',
        'withdraw_amount',
        'currency_rate',
        'receive_amount',
        'date',
        'description',
        'customer_id',
        'user_id',
        'admin_id',
    ];

    /* ================= RELATIONS ================= */

    /**
     * مشتری مربوطه
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }


    public function journal()
{
    return $this->hasOne(Journals::class, 'safe_deal_id', 'id');
}


    /**
     * کاربر ثبت کننده
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * مدیر مربوطه
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
