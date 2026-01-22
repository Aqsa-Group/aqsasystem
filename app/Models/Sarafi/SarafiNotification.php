<?php

namespace App\Models\Sarafi;

use Illuminate\Database\Eloquent\Model;

class SarafiNotification extends Model
{
    protected $table = 'sarafi_notifications';
    protected $connection = 'sarafi';
    protected $fillable = [
        'user_id',
        'admin_id',
        'changerdeal_id',
        'type',
        'title',
        'message',
        'is_read',
    ];

     public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

}
