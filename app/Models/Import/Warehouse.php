<?php

namespace App\Models\Import;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;

    protected $connection = 'import';
    protected $table = 'warehouses';

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






    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
