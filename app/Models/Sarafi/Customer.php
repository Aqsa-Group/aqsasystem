<?php

namespace App\Models\Sarafi;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $connection ='sarafi';
    protected $table = 'customers';

    protected $guarded = [];
}
