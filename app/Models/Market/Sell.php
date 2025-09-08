<?php

namespace App\Models\Market;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Sell extends Model
{

    protected $connection= 'market';
    protected $table= 'sells';
    
   protected $fillable = [
     'market_id',
     'customer_id',
     'advertisment_id',
     'admin_id',
     'booth_id',
     'property',
     'price',
     'currency',
     'date',
     'details',
   ];

   public function market(){
    return $this->belongsTo(Market::class);
   }


   public function customer(){
    return $this->belongsTo(Customer::class);
   }

 
   public function booth(){
    return $this->belongsTo(Booth::class);
   }


   public function advertisment(){
    return $this->belongsTo(Advertisment::class);
   }

   public function admin()
   {
       return $this->belongsTo(User::class, 'admin_id');
   }




   protected static function booted()
   {
       static::creating(function ($document) {
           if (Auth::check()) {
               $user = Auth::user();
               $document->admin_id = $user->role === 'admin' ? $user->id : $user->admin_id;
           }
       });
   }

}


