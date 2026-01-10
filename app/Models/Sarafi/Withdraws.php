<?php

namespace App\Models\Sarafi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdraws extends Model
{
        use HasFactory;

     protected $fillable = [
        'staff_id',
        'expanses_type',
        'amount',
        'currency',
        'date',
        'description',
    ];

    protected $casts = [
        'date'        => 'date',
    ];

    public function staff()
    {
        return $this->belongsTo(Staffs::class, 'staff_id');
    }
}
