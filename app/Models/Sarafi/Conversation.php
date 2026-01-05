<?php

namespace App\Models\Sarafi;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Conversation extends Model
{
    use HasFactory;

    protected $connection = 'sarafi';
    protected $table = 'conversations';

    protected $fillable = [
        'user1_id',
        'user2_id',
        'last_message',
        'last_message_type',
        'last_message_at',
        'unread_count_user1',
        'unread_count_user2'
    ];

    protected $casts = [
        'last_message_at' => 'datetime'
    ];

    protected $appends = [
        'other_user',
        'unread_count'
    ];

    /**
     * Get the first user in conversation.
     */
    public function user1()
    {
        return $this->belongsTo(User::class, 'user1_id');
    }

    /**
     * Get the second user in conversation.
     */
    public function user2()
    {
        return $this->belongsTo(User::class, 'user2_id');
    }

    /**
     * Get the other user in conversation.
     */
    public function getOtherUserAttribute()
    {
        $currentUserId = auth()->guard('sarafi')->id();
        
        if ($this->user1_id == $currentUserId) {
            return $this->user2;
        } else {
            return $this->user1;
        }
    }

    /**
     * Get unread count for current user.
     */
    public function getUnreadCountAttribute()
    {
        $currentUserId = auth()->guard('sarafi')->id();
        
        if ($this->user1_id == $currentUserId) {
            return $this->unread_count_user1;
        } else {
            return $this->unread_count_user2;
        }
    }
}