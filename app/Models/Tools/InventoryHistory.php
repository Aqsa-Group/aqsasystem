<?php

namespace App\Models\Tools;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryHistory extends Model
{
    use HasFactory;

    protected $connection = 'tools';
    protected $table = 'inventory_histories';

    protected $fillable = [
        'inventory_id',
        'type',
        'quantity_change',
        'previous_quantity',
        'new_quantity',
        'unit_price',
        'total_amount',
        'reference_number',
        'notes',
        'created_by',
        'user_id',
        'admin_id',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'quantity_change' => 'integer',
        'previous_quantity' => 'integer',
        'new_quantity' => 'integer',
        'created_at' => 'datetime',
    ];

    /**
     * رابطه با محصول
     */
    public function inventory()
    {
        return $this->belongsTo(Inventories::class, 'inventory_id');
    }

    /**
     * اسکوپ برای تراکنش‌های ورود
     */
    public function scopeIncoming($query)
    {
        return $query->where('type', 'ورود')->orWhere('type', 'خرید');
    }

    /**
     * اسکوپ برای تراکنش‌های خروج
     */
    public function scopeOutgoing($query)
    {
        return $query->where('type', 'خروج')->orWhere('type', 'فروش');
    }

    /**
     * فیلتر بر اساس ادمین
     */
    public function scopeByAdmin($query, $adminId)
    {
        return $query->where('admin_id', $adminId);
    }
}