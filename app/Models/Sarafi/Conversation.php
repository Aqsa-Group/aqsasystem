<?php

namespace App\Models\Sarafi;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $connection = 'sarafi';
    protected $table = 'conversations';
    
    protected $fillable = [
        'user1_id',
        'user2_id',
        'last_message',
        'last_message_at',
        'unread_count_user1',
        'unread_count_user2'
    ];

    protected $casts = [
        'last_message_at' => 'datetime:Y-m-d H:i:s'
    ];

    // Override the getLastMessageAtAttribute method
    public function getLastMessageAtAttribute($value)
    {
        if (!$value) {
            return null;
        }
        
        $date = \Carbon\Carbon::parse($value)->timezone('Asia/Kabul');
        return $date->format('Y-m-d H:i:s');
    }

    public function user1()
    {
        return $this->belongsTo(User::class, 'user1_id');
    }

    public function user2()
    {
        return $this->belongsTo(User::class, 'user2_id');
    }

    // Get other user in conversation
    public function getOtherUser($currentUserId)
    {
        return $this->user1_id == $currentUserId ? $this->user2 : $this->user1;
    }

    // Get unread count for specific user
    public function getUnreadCountForUser($userId)
    {
        return $this->user1_id == $userId ? $this->unread_count_user1 : $this->unread_count_user2;
    }

    // Mark as read for specific user
    public function markAsReadForUser($userId)
    {
        if ($this->user1_id == $userId) {
            $this->update(['unread_count_user1' => 0]);
        } else {
            $this->update(['unread_count_user2' => 0]);
        }
    }

    // Update unread count
    public function incrementUnreadCount($receiverId)
    {
        if ($this->user1_id == $receiverId) {
            $this->increment('unread_count_user1');
        } else {
            $this->increment('unread_count_user2');
        }
    }
}