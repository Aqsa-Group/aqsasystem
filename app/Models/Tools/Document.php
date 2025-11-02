<?php

namespace App\Models\Tools;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $connection='tools';
    protected $table='documents';

    
    protected $fillable = [
        'sale_id',
        'invoice_number',
        'buyer_name',
        'total_amount',
        'paid_amount',
        'sale_type',
        'file_path',
        'discount'
    ];

    /**
     * Get the sale that this document belongs to.
     */
    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
}