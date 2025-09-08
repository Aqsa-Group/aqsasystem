<?php

namespace App\Models\Import;


use Illuminate\Database\Eloquent\Model;

class Safe extends Model
{
    protected $connection = 'import';
    protected $table = 'safes';
    protected $fillable = [
        'sale_id',
        'sale_item_id',
        'total',
        'user_id',

        'today',
        'last_update'
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function saleItem()
    {
        return $this->belongsTo(SaleItem::class, 'sale_item_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
