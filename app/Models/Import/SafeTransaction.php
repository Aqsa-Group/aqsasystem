<?php

namespace App\Models\Import;

use Illuminate\Database\Eloquent\Model;

class SafeTransaction extends Model
{
       protected $connection = 'import';
    protected $table = 'safe_transactions';

    protected $fillable = [
        'amount',
        'note',
    ];
}
