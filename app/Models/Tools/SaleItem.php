<?php

namespace App\Models\Tools;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaleItem extends Model
{
    protected $connection = 'tools';
    protected $table = 'sale_items';


    protected  $guarded = [];
    protected $casts = [
        'quantity' => 'decimal:2',
        'profit' => 'decimal:2',
        'loss' => 'decimal:2',
        'price_per_unit' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    // روابط
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouses::class);
    }

    // Accessors
    public function getProfitPercentageAttribute()
    {
        $purchasePrice = $this->warehouse->purchase_price_per_unit ?? 0;
        if ($purchasePrice == 0) return 0;

        return (($this->price_per_unit - $purchasePrice) / $purchasePrice) * 100;
    }
}
