<?php

namespace App\Models\Import;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaleItem extends Model
{

    protected $connection = 'import';
    protected $table = 'sale_items';

    protected $fillable = [
        'sale_id',
        'product_id',
        'user_id',
        'quantity',
        'warehouse_id',
        'invoice_number',
        'price_per_unit',
        'total_price',
        'profit',
        'loss'
    ];


    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }


    public function safes()
    {
        return $this->hasMany(Safe::class, 'sale_item_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
