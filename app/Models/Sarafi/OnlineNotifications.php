<?php

namespace App\Models\Sarafi;

use Illuminate\Database\Eloquent\Model;

class OnlineNotifications extends Model
{

    protected $table = 'online_notifications';
    protected $connection = 'sarafi';
    protected $fillable = [
        'user_id',
        'admin_id',
        'message',

    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

      public function seenByUsers()
    {
        return $this->belongsToMany(User::class, 'online_notification_user', 'online_notification_id', 'user_id');
    }   
}
