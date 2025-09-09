<?php

namespace App\Models\Import;

use Illuminate\Database\Eloquent\Model;

class SaleReturn extends Model
{

    protected $connection = 'import';
    protected $table = 'returns';
     protected $fillable = [
        'sale_id',
        'warehouse_id',
        'quantity',
        'price_per_unit',
        'total_price',
        'user_id',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
}
