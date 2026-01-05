<?php

namespace App\Models\Sarafi;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Message extends Model
{
    use HasFactory;

    protected $connection = 'sarafi';
    protected $table = 'messages';

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'message',
        'type',
        'media_path',
        'duration',
        'is_read'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    protected $appends = [
        'media_url',
        'has_media',
        'preview',
        'formatted_duration'
    ];

    /**
     * Get the sender of the message.
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Get the receiver of the message.
     */
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    /**
     * Get the media URL attribute.
     */
    public function getMediaUrlAttribute()
    {
        if ($this->media_path) {
            return asset('storage/' . $this->media_path);
        }
        return null;
    }

    /**
     * Check if message has media.
     */
    public function getHasMediaAttribute()
    {
        return !empty($this->media_path);
    }

    /**
     * Get message preview.
     */
    public function getPreviewAttribute()
    {
        switch ($this->type) {
            case 'image':
                return 'عکس';
            case 'audio':
                return 'پیام صوتی';
            default:
                return strlen($this->message) > 50 ? substr($this->message, 0, 50) . '...' : $this->message;
        }
    }

    /**
     * Format duration for audio messages.
     */
    public function getFormattedDurationAttribute()
    {
        if (!$this->duration) return '0:00';
        
        $minutes = floor($this->duration / 60);
        $seconds = $this->duration % 60;
        
        return sprintf("%d:%02d", $minutes, $seconds);
    }
}