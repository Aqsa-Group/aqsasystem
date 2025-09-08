<?php

namespace App\Models\Market;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Advertisment extends Model
{
    protected $connection = 'market';
    protected $table = 'advertisments';
    
protected $fillable =[
    'market_id',
    'shop_id',
    'admin_id',
    'for',
    'booth_id',
    'property',
    'address',
    'width',
    'hight',
    'price',
    'image',
    'currency'

];



protected $casts = [
    'image' => 'array'
];

 public function market(){
    return $this->belongsTo(Market::class);
 }

 public function shop(){
    return $this->belongsTo(Shop::class);
 }

 public function booth(){
    return $this->belongsTo(Booth::class);
 }


 public function admin()
 {
     return $this->belongsTo(User::class, 'admin_id');
 }


 
 public function sell()
 {
     return $this->hasMany(Sell::class);
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
