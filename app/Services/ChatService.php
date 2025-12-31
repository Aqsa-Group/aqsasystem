<?php

namespace App\Services;

use App\Models\Sarafi\Message;
use App\Models\Sarafi\Conversation;
use App\Models\Sarafi\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ChatService
{
    /**
     * بررسی امکان ارسال پیام بین دو کاربر
     */
    public function canMessage($sender, $receiver): bool
    {
        // کاربر نمی‌تواند به خودش پیام بدهد
        if ($sender->id == $receiver->id) {
            return false;
        }

        // سوپر ادمین می‌تواند به همه پیام دهد
        if ($sender->role === 'superadmin') {
            return in_array($receiver->role, ['admin', 'warehouse_manager']);
        }

        // ادمین می‌تواند به سوپر ادمین و انباردارهای زیرمجموعه خود پیام دهد
        if ($sender->role === 'admin') {
            return $receiver->role === 'superadmin' || 
                   ($receiver->admin_id == $sender->id && $receiver->role === 'warehouse_manager');
        }

        // انباردار می‌تواند به سوپر ادمین و ادمین خود پیام دهد
        if ($sender->role === 'warehouse_manager') {
            return $receiver->role === 'superadmin' || $receiver->id == $sender->admin_id;
        }

        return false;
    }

    /**
     * دریافت یا ایجاد مکالمه
     */
    public function getOrCreateConversation($userId1, $userId2)
    {
        $user1 = min($userId1, $userId2);
        $user2 = max($userId1, $userId2);

        $conversation = Conversation::where('user1_id', $user1)
            ->where('user2_id', $user2)
            ->first();

        if (!$conversation) {
            $conversation = Conversation::create([
                'user1_id' => $user1,
                'user2_id' => $user2,
                'last_message_at' => now()
            ]);
        }

        return $conversation;
    }

    /**
     * آپلود فایل
     */
    public function uploadFile($file, $type)
    {
        $directory = 'chat/' . $type . 's';
        $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        
        $path = $file->storeAs($directory, $fileName, 'public');
        
        return [
            'path' => $path,
            'url' => Storage::url($path),
            'original_name' => $file->getClientOriginalName(),
            'size' => $file->getSize(),
            'mime_type' => $file->getMimeType()
        ];
    }

    /**
     * دریافت کاربران قابل چت
     */
    public function getChatUsers($currentUser)
    {
        if ($currentUser->role === 'superadmin') {
            return User::where('id', '!=', $currentUser->id)
                ->whereIn('role', ['admin', 'warehouse_manager'])
                ->select('id', 'name', 'lastname', 'sarafi_name', 'role', 'admin_id', 'phone')
                ->get();
        }

        if ($currentUser->role === 'admin') {
            return User::where(function($query) use ($currentUser) {
                $query->where('role', 'superadmin')
                    ->orWhere(function($q) use ($currentUser) {
                        $q->where('admin_id', $currentUser->id)
                          ->where('role', 'warehouse_manager');
                    });
            })
            ->select('id', 'name', 'lastname', 'sarafi_name', 'role', 'admin_id', 'phone')
            ->get();
        }

        // برای انباردار
        return User::where('role', 'superadmin')
            ->orWhere('id', $currentUser->admin_id)
            ->select('id', 'name', 'lastname', 'sarafi_name', 'role', 'admin_id', 'phone')
            ->get();
    }
}