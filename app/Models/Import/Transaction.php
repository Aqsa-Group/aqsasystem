<?php

namespace App\Models\Import;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{

     protected $connection = 'import';
     protected $table = 'transactions';
     protected $guarded = [];

     public function sarafi(){
         return $this->belongsTo(Sarafi::class , 'sarafi_id');
     }


       public function customer(){
         return $this->belongsTo(Customer::class , 'customer_id');
     }

         public function staff(){
         return $this->belongsTo(Staff::class , 'staff_id');
     }
    
}
