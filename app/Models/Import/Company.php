<?php

namespace App\Models\Import;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $guarded = [];
    protected $connection = 'import';
    protected $table = 'company';


 public function buy(){
        return $this->hasMany(Buy::class);
    }


    

}
