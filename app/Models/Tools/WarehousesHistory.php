<?php

namespace App\Models\Tools;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehousesHistory extends Model
{
    use HasFactory;

    protected $connection = 'tools';
    protected $table = 'warehouse_histories';

    protected $fillable = [
        'warehouse_id', // تغییر از inventory_id به warehouse_id
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
    ];

    /**
     * رابطه با محصول در دوکان
     */
    public function warehouse()
    {
        return $this->belongsTo(Warehouses::class, 'warehouse_id'); // تغییر رابطه
    }

    /**
     * اسکوپ برای تراکنش‌های ورود
     */
    public function scopeIncoming($query)
    {
        return $query->where('type', 'ورود')->orWhere('type', 'خرید')->orWhere('type', 'انتقال');
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