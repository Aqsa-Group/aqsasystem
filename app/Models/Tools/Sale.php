<?php

namespace App\Models\Tools;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{

    protected $connection = 'tools';
    protected $table = 'sales';
  

    protected  $guarded = [];
     
    protected $casts = [
        'total_price' => 'decimal:2',
        'received_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
        'discount' => 'decimal:2',
    ];

    // روابط
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function saleItems(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    // Accessors
    public function getSaleTypeNameAttribute()
    {
        return $this->sale_type === 'retail' ? 'پرچون' : 'عمده';
    }

    public function getFormattedDateAttribute()
    {
        return \Morilog\Jalali\Jalalian::fromDateTime($this->created_at)->format('Y/m/d');
    }

    public function getPaymentStatusAttribute()
    {
        if ($this->remaining_amount == 0) {
            return 'تکمیل';
        } elseif ($this->received_amount == 0) {
            return 'پرداخت نشده';
        } else {
            return 'ناقص';
        }
    }

    public function getTotalProfitAttribute()
    {
        return $this->saleItems->sum('profit');
    }

    public function getTotalItemsAttribute()
    {
        return $this->saleItems->count();
    }

    public function getTotalQuantityAttribute()
    {
        return $this->saleItems->sum('quantity');
    }
}