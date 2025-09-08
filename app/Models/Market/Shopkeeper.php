<?php

namespace App\Models\Market;


use Illuminate\Database\Eloquent\Model;
use App\Models\Market\Shop;
use App\Models\Market\Market;
use App\Models\Market\Document;
use App\Models\Market\User;
use App\Models\Market\Deposit;
use App\Models\Market\Booth;
use Illuminate\Support\Facades\Auth;


class Shopkeeper extends Model
{

    protected $connection= 'market';
    protected $table= 'shopkeepers';
    
    protected $fillable = [
        'fullname',
        'father_name',
        'grand_father',
        'address',
        'phone',
        'shop_activity',
        'national_id',
        'contract_number',
        'contract_start',
        'contract_duration',
        'contract_end',
        'warranty_document',
        'id_image',
        'shopkeeper_image',
        'market_id',
        'booth_id',
        'property_type',
        'admin_id',
        'username',
        'password'

    ];

    public function shops()
    {
        return $this->hasMany(Shop::class);
    }

    public function customer()
    {
        return $this->hasOne(Customer::class);
    }

    public function loan()
    {
        return $this->hasMany(Loan::class);
    }


       public function booth()
    {
        return $this->hasMany(Booth::class, 'shopkeeper_id', 'id');
    }

    public function accountings()
    {
        return $this->hasMany(Accounting::class);
    }

    public function depositLogs()
    {
        return $this->hasMany(DepositLog::class);
    }


    public function market()
    {
        return $this->belongsTo(Market::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }


    public function deposit()
    {
        return $this->hasMany(Deposit::class);
    }

    public function outside()
    {
        return $this->hasMany(Outside::class);
    }






    protected static function booted(): void
    {
        static::creating(function ($shopkeeper) {
            if (Auth::check()) {
                $shopkeeper->admin_id = Auth::user()->role === 'admin'
                    ? Auth::id()
                    : Auth::user()->admin_id;
            }
        });
    }
}
