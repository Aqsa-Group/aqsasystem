<?php

namespace App\Models\Sarafi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staffs extends Model
{
    use HasFactory;

    protected $table = 'staffs';
    protected $connection = 'sarafi';

    protected $fillable = [
        'name',
        'fathername',
        'age',
        'gender',
        'phone',
        'address',
        'image',
        'id_card',
        'document',
        'job',
        'salary_amount',
        'contract_start',
        'contract_end',
    ];


    protected $casts = [
        'salary_amount'   => 'decimal:3',
        'contract_start'  => 'date',
        'contract_end'    => 'date',
    ];
}
