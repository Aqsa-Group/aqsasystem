<?php

namespace App\Models\Import;

use Illuminate\Database\Eloquent\Model;

class Buy extends Model
{
    protected $connection = 'import';
    protected $table = 'buy';
    protected $guarded = [];

    public function user()
{
    return $this->belongsTo(User::class);
}

    
}
