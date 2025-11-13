<?php

namespace App\Models\Sarafi;

use Illuminate\Database\Eloquent\Model;

class Trash extends Model
{

       protected $connection = 'sarafi';
    protected $table = 'trash';
    protected $fillable = [
        'user_id',
        'admin_id',
        'record_id',
        'action',
        'document_type',
        'old_data',
        'new_data',
        'document_discription',
        'registered_user'
    ];

    protected $casts = [
        'old_data' => 'array',
        'new_data' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function registeredUser()
    {
        return $this->belongsTo(User::class, 'registered_user');
    }
}