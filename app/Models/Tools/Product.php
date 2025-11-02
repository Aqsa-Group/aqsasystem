<?php

namespace App\Models\Tools;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use SoftDeletes;

    protected $connection = 'tools';
    protected $table = 'products';

    protected $fillable = [
        'barcode',
        'product_name',
        'unit',
        'package_type',
        'quantity_per_package',
        'total_packages',
        'total_quantity',
        'purchase_price_per_package',
        'purchase_price_per_unit',
        'total_purchase_amount',
        'retail_price',
        'wholesale_price',
        'profit_loss_per_unit',
        'total_profit_loss',
        'country_of_origin',
        'production_year',
        'notes',
        'image_path',
        'is_active',
        'status',
        'min_stock_level',
        'max_stock_level',
        'category',
        'sub_category',
        'supplier_name',
        'supplier_contact',
        'last_purchase_date',
        'expiry_date',
        'user_id',
        'admin_id'
    ];

    protected $casts = [
        'quantity_per_package' => 'integer',
        'total_packages' => 'integer',
        'total_quantity' => 'decimal:2',
        'purchase_price_per_package' => 'decimal:2',
        'purchase_price_per_unit' => 'decimal:2',
        'total_purchase_amount' => 'decimal:2',
        'retail_price' => 'decimal:2',
        'wholesale_price' => 'decimal:2',
        'profit_loss_per_unit' => 'decimal:2',
        'total_profit_loss' => 'decimal:2',
        'is_active' => 'boolean',
        'last_purchase_date' => 'date',
        'expiry_date' => 'date'
    ];

    // روابط
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    // اسکوپ‌ها
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('total_quantity', '>', 0);
    }

    public function scopeLowStock($query)
    {
        return $query->where('total_quantity', '<=', DB::raw('min_stock_level'));
    }

    // متدهای کمکی
    public function getStockStatusAttribute()
    {
        if ($this->total_quantity <= 0) {
            return 'ناموجود';
        } elseif ($this->total_quantity <= $this->min_stock_level) {
            return 'کمبود';
        } else {
            return 'موجود';
        }
    }

    public function getPackagesCountAttribute()
    {
        if ($this->quantity_per_package > 0) {
            return floor($this->total_quantity / $this->quantity_per_package);
        }
        return 0;
    }

    public function getRemainingQuantityAttribute()
    {
        if ($this->quantity_per_package > 0) {
            return $this->total_quantity % $this->quantity_per_package;
        }
        return $this->total_quantity;
    }

    // متد برای کاهش موجودی پس از فروش
    public function decreaseStock($quantity)
    {
        $this->total_quantity -= $quantity;
        
        // محاسبه تعداد بسته‌های کامل
        if ($this->quantity_per_package > 0) {
            $fullPackages = floor($quantity / $this->quantity_per_package);
            $remainingQuantity = $quantity % $this->quantity_per_package;
            
            if ($fullPackages > 0) {
                $this->total_packages -= $fullPackages;
            }
        }
        
        $this->save();
    }

    // متد برای بررسی موجودی کافی
    public function hasEnoughStock($quantity)
    {
        return $this->total_quantity >= $quantity;
    }
}