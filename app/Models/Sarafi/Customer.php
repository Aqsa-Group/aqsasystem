<?php

namespace App\Models\Sarafi;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Customer extends Authenticatable
{
    protected $connection = 'sarafi';
    protected $table = 'customers';
    
    protected $fillable = [
        'fullname',
        'image',
        'id_card_image', 
        'city',
        'phone',
        'idcard_number',
        'account_number',
        'whatsapp_number',
        'password', 
        'type',
        'created_by',
    ];

    protected $hidden = [
        'password',
    ];

    
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}