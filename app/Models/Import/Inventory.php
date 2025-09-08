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
        'import_date' => 'date',
    ];





    public function warehouse()
    {
        return $this->hasMany(Warehouse::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
