<?php

namespace App\Models\Import;

use Illuminate\Database\Eloquent\Model;
use App\Models\Import\Warehouse;

class Inventory extends Model
{

    protected $connection = 'import';
    protected $table = 'inventories';
    protected $fillable = [
        'barcode',
        'name',
        'quantity',
        'total_price',
        'unit',
        'user_id',

        'price',
        'brand',
        'big_quantity',
        'big_whole_price',
        'all_exist_number',
        'big_unit_price',
        'retail_price',
        'product_image',
        'import_date',
    ];


  protected $casts = [
    'import_date'     => 'date',
    'price'           => 'decimal:2',
    'total_price'     => 'decimal:2',
    'big_whole_price' => 'decimal:2',
    'big_unit_price'  => 'decimal:2',
    'retail_price'    => 'decimal:2',
];

     protected static function booted()
    {
        static::saving(function ($inventory) {
            $inventory->total_price = $inventory->all_exist_number * $inventory->price;
        });
    }



    public function warehouse()
    {
        return $this->hasMany(Warehouse::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
