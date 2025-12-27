<?php

namespace App\Models\Sarafi;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $connection = 'sarafi';
    protected $table = 'messages';
    
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'message',
        'is_read',
        'type'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s'
    ];

    // Override the getCreatedAtAttribute method to convert to Afghanistan time
    public function getCreatedAtAttribute($value)
    {
        if (!$value) {
            return null;
        }
        
        $date = \Carbon\Carbon::parse($value)->timezone('Asia/Kabul');
        return $date->format('Y-m-d H:i:s');
    }

    public function getUpdatedAtAttribute($value)
    {
        if (!$value) {
            return null;
        }
        
        $date = \Carbon\Carbon::parse($value)->timezone('Asia/Kabul');
        return $date->format('Y-m-d H:i:s');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    // Scope for unread messages
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    // Scope for messages between two users
    public function scopeBetween($query, $user1Id, $user2Id)
    {
        return $query->where(function($q) use ($user1Id, $user2Id) {
            $q->where('sender_id', $user1Id)
              ->where('receiver_id', $user2Id);
        })->orWhere(function($q) use ($user1Id, $user2Id) {
            $q->where('sender_id', $user2Id)
              ->where('receiver_id', $user1Id);
        });
    }
}