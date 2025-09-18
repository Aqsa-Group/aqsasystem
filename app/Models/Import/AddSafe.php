<?php

namespace App\Models\Import;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddSafe extends Model
{
    use HasFactory;

    protected $table = '_add_safe';
    protected $connection= 'import';

    protected $fillable = [
        'amount',
        'currency',
        'description',
    ];
}
