<?php

namespace App\Models\Import;

use Illuminate\Database\Eloquent\Model;

class Sarafi extends Model
{
     protected $connection = 'import';
     protected $table = 'sarafi';
 
     protected $guarded = [];

    public function transaction(){
        $this->hasMany(Transaction::class);
     }


}
