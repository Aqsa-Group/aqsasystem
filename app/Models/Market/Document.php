<?php

namespace App\Models\Market;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\Market\Shopkeeper;
use App\Models\Market\Market;
use App\Models\Market\Shop;

class Document extends Model
{

    protected $connection= 'market';
    protected $table= 'documents';
    
    protected $fillable = [
        'shopkeeper_id',
        'shop_id',
        'booth_id',
        'market_id',
        'filename',
        'original_name',
        'admin_id',
        'signed_image',
        'warranty_document',
        'id_image',
        'customer_id',
        'id_card_image'
    ];

    public function shopkeeper()
    {
        return $this->belongsTo(Shopkeeper::class);
    }

    public function shop() {
        return $this->belongsTo(Shop::class, 'shop_id');
    }
    
    public function customer() {
       
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    
        
    public function market()
    {
        return $this->belongsTo(Market::class);
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
