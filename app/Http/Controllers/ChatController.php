<?php

namespace App\Http\Controllers;

use App\Models\Sarafi\Message;
use App\Models\Sarafi\Conversation;
use App\Models\Sarafi\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use getID3;

class ChatController extends Controller
{
    // ارسال پیام (متن، عکس، صوت)
      // ارسال پیام (متن، عکس، صوت)
    public function sendMessage(Request $request)
    {
        Log::info('=== CHAT SEND MESSAGE START ===');
        Log::info('Request type:', ['type' => $request->type]);
        Log::info('Has file:', ['has' => $request->hasFile('media')]);
        Log::info('Request all:', $request->except(['media']));
        
        try {
            // اعتبارسنجی داینامیک بر اساس نوع پیام
            $rules = [
                'receiver_id' => [
                    'required',
                    'integer',
                    function ($attribute, $value, $fail) {
                        if (!User::where('id', $value)->exists()) {
                            $fail('کاربر دریافت‌کننده معتبر نیست.');
                        }
                    }
                ],
                'type' => 'required|in:text,image,audio',
            ];

            // قوانین خاص برای هر نوع پیام
            if ($request->type == 'text') {
                $rules['message'] = 'required|string|max:1000';
                $rules['media'] = 'nullable';
            } elseif ($request->type == 'image') {
                $rules['media'] = 'required|file|mimes:jpg,jpeg,png,gif,webp|max:5120'; // 5MB
                $rules['message'] = 'nullable|string|max:1000';
            } elseif ($request->type == 'audio') {
                $rules['media'] = 'required|file|mimes:mp3,wav,ogg,m4a,mp4,webm|max:10240'; // 10MB
                $rules['message'] = 'nullable|string|max:1000';
            }

            $validator = Validator::make($request->all(), $rules, [
                'receiver_id.required' => 'شناسه دریافت‌کننده الزامی است.',
                'receiver_id.integer' => 'شناسه دریافت‌کننده باید عدد باشد.',
                'type.required' => 'نوع پیام الزامی است.',
                'type.in' => 'نوع پیام باید متن، عکس یا صوت باشد.',
                'message.required' => 'متن پیام الزامی است.',
                'message.max' => 'متن پیام نباید بیشتر از 1000 کاراکتر باشد.',
                'media.required' => 'فایل الزامی است.',
                'media.file' => 'فایل ارسالی معتبر نیست.',
                'media.mimes' => 'فرمت فایل نامعتبر است. فرمت‌های مجاز: :values',
                'media.max' => 'حجم فایل باید کمتر از :max کیلوبایت باشد.',
            ]);

            if ($validator->fails()) {
                Log::error('Validation failed:', $validator->errors()->toArray());
                return response()->json([
                    'success' => false,
                    'error' => 'خطا در اعتبارسنجی',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            Log::info('Validation passed');

            $sender = Auth::guard('sarafi')->user();
            $receiver = User::find($request->receiver_id);
            
            if (!$receiver) {
                Log::error('Receiver not found with ID: ' . $request->receiver_id);
                return response()->json([
                    'success' => false,
                    'error' => 'کاربر دریافت‌کننده یافت نشد'
                ], 404);
            }

            // بررسی مجوز ارسال پیام
            if (!$this->canMessage($sender, $receiver)) {
                Log::warning('User not authorized to send message');
                return response()->json([
                    'success' => false,
                    'error' => 'شما اجازه ارسال پیام به این کاربر را ندارید'
                ], 403);
            }

            DB::connection('sarafi')->beginTransaction();

            try {
                $messageContent = $request->message;
                $mediaPath = null;
                $duration = null;

                // پردازش فایل‌های رسانه‌ای
                if ($request->hasFile('media') && $request->file('media')->isValid()) {
                    $file = $request->file('media');
                    $originalName = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();
                    $mimeType = $file->getMimeType();
                    $size = $file->getSize();
                    
                    Log::info('File details:', [
                        'name' => $originalName,
                        'extension' => $extension,
                        'mime' => $mimeType,
                        'size' => $size,
                        'type' => $request->type
                    ]);
                    
                    // ایجاد پوشه بر اساس نوع فایل
                    $folder = $request->type == 'image' ? 'chat/images' : 'chat/audios';
                    
                    // ایجاد پوشه اگر وجود ندارد
                    if (!Storage::disk('public')->exists($folder)) {
                        Storage::disk('public')->makeDirectory($folder, 0755, true);
                    }
                    
                    // نام فایل منحصر به فرد
                    $fileName = time() . '_' . uniqid() . '.' . $extension;
                    
                    // ذخیره فایل
                    $mediaPath = $file->storeAs($folder, $fileName, 'public');
                    
                    // محاسبه مدت زمان صوت
                    if ($request->type == 'audio') {
                        try {
                            $duration = $this->getAudioDuration($file);
                            Log::info('Audio duration calculated:', ['duration' => $duration]);
                        } catch (\Exception $e) {
                            Log::error('Error calculating audio duration:', ['error' => $e->getMessage()]);
                            $duration = 0; // مقدار پیش‌فرض
                        }
                    }
                    
                    // متن پیش‌فرض برای پیام‌های رسانه‌ای
                    if (empty($messageContent)) {
                        $messageContent = $request->type == 'image' ? 'عکس ارسال شد' : 'پیام صوتی ارسال شد';
                    }
                } elseif ($request->type != 'text') {
                    throw new \Exception('فایل ارسالی معتبر نیست.');
                }

                // ایجاد پیام
                $message = Message::create([
                    'sender_id' => $sender->id,
                    'receiver_id' => $receiver->id,
                    'message' => $messageContent,
                    'type' => $request->type,
                    'media_path' => $mediaPath,
                    'duration' => $duration,
                    'is_read' => false
                ]);
                
                Log::info('Message created:', [
                    'id' => $message->id, 
                    'type' => $request->type,
                    'has_media' => !empty($mediaPath)
                ]);

                // ایجاد یا به‌روزرسانی مکالمه
                $conversation = $this->getOrCreateConversation($sender->id, $receiver->id);
                
                $previewText = $this->getMessagePreview($request->type, $request->message);
                $conversation->update([
                    'last_message' => $previewText,
                    'last_message_type' => $request->type,
                    'last_message_at' => now(),
                ]);
                
                // افزایش تعداد پیام‌های خوانده نشده
                if ($conversation->user1_id == $receiver->id) {
                    $conversation->increment('unread_count_user1');
                } else {
                    $conversation->increment('unread_count_user2');
                }

                DB::connection('sarafi')->commit();
                
                Log::info('=== CHAT SEND MESSAGE SUCCESS ===');

                // بارگذاری روابط و اضافه کردن URL کامل
                $message->load(['sender', 'receiver']);
                $messageData = $this->addUrlsToMessage($message);

                return response()->json([
                    'success' => true,
                    'message' => $messageData
                ]);

            } catch (\Exception $e) {
                DB::connection('sarafi')->rollBack();
                Log::error('Transaction error in sendMessage:', [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]);
                throw $e;
            }

        } catch (ValidationException $e) {
            Log::error('Validation exception:', $e->errors());
            return response()->json([
                'success' => false,
                'error' => 'خطا در اعتبارسنجی',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Exception in sendMessage:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return response()->json([
                'success' => false,
                'error' => 'خطا در ارسال پیام: ' . $e->getMessage()
            ], 500);
        }
    }
    // دریافت پیام‌های بین دو کاربر (همه پیام‌ها)
    public function getMessages($userId)
    {
        Log::info('Getting messages for user: ' . $userId);
        
        $currentUser = Auth::guard('sarafi')->user();
        $otherUser = User::find($userId);
        
        if (!$otherUser) {
            return response()->json([
                'success' => false,
                'error' => 'کاربر یافت نشد'
            ], 404);
        }

        // دریافت پیام‌ها
        $messages = Message::where(function($query) use ($currentUser, $otherUser) {
            $query->where('sender_id', $currentUser->id)
                  ->where('receiver_id', $otherUser->id);
        })->orWhere(function($query) use ($currentUser, $otherUser) {
            $query->where('sender_id', $otherUser->id)
                  ->where('receiver_id', $currentUser->id);
        })->orderBy('created_at', 'asc')
          ->with(['sender', 'receiver'])
          ->get()
          ->map(function ($message) {
              return $this->addUrlsToMessage($message);
          });

        // علامت‌گذاری پیام‌ها به عنوان خوانده شده
        Message::where('sender_id', $otherUser->id)
               ->where('receiver_id', $currentUser->id)
               ->where('is_read', false)
               ->update(['is_read' => true]);

        // به‌روزرسانی تعداد پیام‌های خوانده نشده در مکالمه
        $conversation = $this->getOrCreateConversation($currentUser->id, $otherUser->id);
        if ($conversation->user1_id == $currentUser->id) {
            $conversation->update(['unread_count_user1' => 0]);
        } else {
            $conversation->update(['unread_count_user2' => 0]);
        }

        return response()->json([
            'success' => true,
            'messages' => $messages
        ]);
    }

    // دریافت فقط پیام‌های جدید
    public function getNewMessages($userId)
    {
        Log::info('Getting NEW messages for user: ' . $userId);
        
        $currentUser = Auth::guard('sarafi')->user();
        $otherUser = User::find($userId);
        
        if (!$otherUser) {
            return response()->json([
                'success' => false,
                'error' => 'کاربر یافت نشد'
            ], 404);
        }

        // دریافت last_message_id از query string
        $lastMessageId = request()->input('last_message_id', 0);
        
        Log::info('Last message ID from client: ' . $lastMessageId);

        // دریافت فقط پیام‌های جدیدتر از last_message_id
        $messages = Message::where(function($query) use ($currentUser, $otherUser) {
                $query->where('sender_id', $currentUser->id)
                      ->where('receiver_id', $otherUser->id);
            })
            ->orWhere(function($query) use ($currentUser, $otherUser) {
                $query->where('sender_id', $otherUser->id)
                      ->where('receiver_id', $currentUser->id);
            })
            ->where('id', '>', $lastMessageId)
            ->orderBy('created_at', 'asc')
            ->with(['sender', 'receiver'])
            ->get()
            ->map(function ($message) {
                return $this->addUrlsToMessage($message);
            });

        Log::info('Found ' . $messages->count() . ' new messages');

        // علامت‌گذاری پیام‌های جدید به عنوان خوانده شده
        if ($messages->count() > 0) {
            Message::where('sender_id', $otherUser->id)
                   ->where('receiver_id', $currentUser->id)
                   ->where('is_read', false)
                   ->where('id', '>', $lastMessageId)
                   ->update(['is_read' => true]);

            // به‌روزرسانی تعداد پیام‌های خوانده نشده
            $conversation = $this->getOrCreateConversation($currentUser->id, $otherUser->id);
            if ($conversation->user1_id == $currentUser->id) {
                $conversation->update(['unread_count_user1' => 0]);
            } else {
                $conversation->update(['unread_count_user2' => 0]);
            }
        }

        return response()->json([
            'success' => true,
            'messages' => $messages
        ]);
    }

    // دریافت لیست مکالمات
    public function getConversations()
    {
        $user = Auth::guard('sarafi')->user();
        Log::info('Getting conversations for user: ' . $user->id);
        
        $conversations = Conversation::where('user1_id', $user->id)
            ->orWhere('user2_id', $user->id)
            ->with(['user1', 'user2'])
            ->orderBy('last_message_at', 'desc')
            ->get()
            ->map(function ($conversation) use ($user) {
                $otherUser = $conversation->user1_id == $user->id ? $conversation->user2 : $conversation->user1;
                $unreadCount = $conversation->user1_id == $user->id ? 
                    $conversation->unread_count_user1 : $conversation->unread_count_user2;
                
                return [
                    'id' => $conversation->id,
                    'other_user' => [
                        'id' => $otherUser->id,
                        'name' => $otherUser->name,
                        'lastname' => $otherUser->lastname,
                        'user_image' => $otherUser->user_image,
                        'image_url' => $otherUser->user_image ? 
                            asset('storage/' . $otherUser->user_image) : 
                            asset('assets/sarafi/avatar.svg'),
                    ],
                    'last_message' => $conversation->last_message,
                    'last_message_type' => $conversation->last_message_type,
                    'last_message_at' => $conversation->last_message_at,
                    'unread_count' => $unreadCount,
                    'created_at' => $conversation->created_at
                ];
            });

        return response()->json([
            'success' => true,
            'conversations' => $conversations
        ]);
    }

    // دریافت کاربران قابل چت
    public function getChatUsers()
    {
        $currentUser = Auth::guard('sarafi')->user();

        $users = User::where('id', '!=', $currentUser->id)
            ->where(function ($query) use ($currentUser) {
                // همه می‌توانند سوپر ادمین را ببینند
                $query->where('role', 'superadmin');

                // سوپر ادمین همه را می‌بیند
                if ($currentUser->role === 'superadmin') {
                    $query->orWhereIn('role', [
                        'admin',
                        'warehouse_manager',
                        'internal_officer',
                        'external_officer'
                    ]);
                }

                // ادمین فقط خزانه‌دارهای خودش
                if ($currentUser->role === 'admin') {
                    $query->orWhere(function ($q) use ($currentUser) {
                        $q->where('role', 'warehouse_manager')
                          ->where('admin_id', $currentUser->id);
                    });
                }

                // خزانه‌دار فقط ادمین خودش
                if ($currentUser->role === 'warehouse_manager') {
                    $query->orWhere('id', $currentUser->admin_id);
                }
            })
            ->select('id', 'name', 'lastname', 'sarafi_name', 'role', 'admin_id', 'phone', 'user_image')
            ->get()
            ->map(function ($user) {
                $userArray = $user->toArray();
                $userArray['image_url'] = $user->user_image ? 
                    asset('storage/' . $user->user_image) : 
                    asset('assets/sarafi/avatar.svg');
                return $userArray;
            });

        return response()->json([
            'success' => true,
            'users' => $users
        ]);
    }

    // دریافت تعداد پیام‌های خوانده نشده
    public function getUnreadCount()
    {
        $user = Auth::guard('sarafi')->user();
        
        $count = Message::where('receiver_id', $user->id)
            ->where('is_read', false)
            ->count();

        return response()->json([
            'success' => true,
            'count' => $count
        ]);
    }

    // جستجوی کاربران
    public function searchUsers(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'query' => 'required|string|min:2'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $currentUser = Auth::guard('sarafi')->user();
        $query = $request->input('query');

        $users = User::where('id', '!=', $currentUser->id)
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('lastname', 'like', "%{$query}%")
                  ->orWhere('sarafi_name', 'like', "%{$query}%")
                  ->orWhere('phone', 'like', "%{$query}%");
            })
            ->select('id', 'name', 'lastname', 'sarafi_name', 'role', 'admin_id', 'phone', 'user_image')
            ->limit(20)
            ->get()
            ->map(function ($user) {
                $userArray = $user->toArray();
                $userArray['image_url'] = $user->user_image ? 
                    asset('storage/' . $user->user_image) : 
                    asset('assets/sarafi/avatar.svg');
                return $userArray;
            });

        return response()->json([
            'success' => true,
            'users' => $users
        ]);
    }

    // علامت‌گذاری همه پیام‌ها به عنوان خوانده شده
    public function markAllAsRead()
    {
        $user = Auth::guard('sarafi')->user();
        
        Message::where('receiver_id', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        Conversation::where('user1_id', $user->id)
            ->update(['unread_count_user1' => 0]);
            
        Conversation::where('user2_id', $user->id)
            ->update(['unread_count_user2' => 0]);

        return response()->json([
            'success' => true,
            'message' => 'همه پیام‌ها خوانده شدند'
        ]);
    }

    // حذف پیام
    public function deleteMessage($messageId)
    {
        $user = Auth::guard('sarafi')->user();
        
        $message = Message::find($messageId);
        
        if (!$message) {
            return response()->json([
                'success' => false,
                'error' => 'پیام یافت نشد'
            ], 404);
        }
        
        // بررسی مالکیت پیام
        if ($message->sender_id !== $user->id) {
            return response()->json([
                'success' => false,
                'error' => 'شما اجازه حذف این پیام را ندارید'
            ], 403);
        }
        
        // حذف فایل رسانه‌ای اگر وجود دارد
        if ($message->media_path && Storage::disk('public')->exists($message->media_path)) {
            Storage::disk('public')->delete($message->media_path);
        }
        
        $message->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'پیام با موفقیت حذف شد'
        ]);
    }

   // دریافت اطلاعات فایل صوت - بهبود یافته
    public function getAudioInfo(Request $request)
    {
        try {
            Log::info('Audio info request received');
            
            $validator = Validator::make($request->all(), [
                'audio' => 'required|file|mimes:mp3,wav,ogg,m4a,mp4,webm|max:10240'
            ], [
                'audio.required' => 'فایل صوتی الزامی است.',
                'audio.file' => 'فایل ارسالی معتبر نیست.',
                'audio.mimes' => 'فرمت فایل باید mp3, wav, ogg, m4a, mp4 یا webm باشد.',
                'audio.max' => 'حجم فایل باید کمتر از 10 مگابایت باشد.'
            ]);

            if ($validator->fails()) {
                Log::error('Audio info validation failed:', $validator->errors()->toArray());
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $file = $request->file('audio');
            $duration = $this->getAudioDuration($file);
            
            Log::info('Audio info calculated:', [
                'duration' => $duration,
                'size' => $file->getSize(),
                'type' => $file->getMimeType(),
                'name' => $file->getClientOriginalName()
            ]);
            
            return response()->json([
                'success' => true,
                'duration' => $duration,
                'size' => $file->getSize(),
                'type' => $file->getMimeType(),
                'name' => $file->getClientOriginalName()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in getAudioInfo:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'error' => 'خطا در پردازش فایل صوتی: ' . $e->getMessage()
            ], 500);
        }
    }
    // تست endpoint
    public function test()
    {
        $user = Auth::guard('sarafi')->user();
        
        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'role' => $user->role,
                'admin_id' => $user->admin_id,
                'user_image' => $user->user_image,
                'image_url' => $user->user_image ? 
                    asset('storage/' . $user->user_image) : 
                    asset('assets/sarafi/avatar.svg'),
            ],
          'auth' => Auth::guard('sarafi')->check(),
            'users_count' => User::count(),
            'messages_count' => Message::count(),
            'conversations_count' => Conversation::count()
        ]);
    }

    // توابع کمکی خصوصی
    private function canMessage($sender, $receiver)
    {
        // جلوگیری از پیام به خود
        if ($sender->id === $receiver->id) {
            return false;
        }

        // سوپر ادمین ↔ همه
        if ($sender->role === 'superadmin' || $receiver->role === 'superadmin') {
            return true;
        }

        // ادمین ↔ خزانه‌دارهای خودش
        if ($sender->role === 'admin') {
            return $receiver->role === 'warehouse_manager'
                && $receiver->admin_id === $sender->id;
        }

        if ($sender->role === 'warehouse_manager') {
            return $receiver->role === 'admin'
                && $sender->admin_id === $receiver->id;
        }

        // سایر نقش‌ها (internal / external) فقط با سوپر ادمین
        return false;
    }

    private function getOrCreateConversation($userId1, $userId2)
    {
        $user1 = min($userId1, $userId2);
        $user2 = max($userId1, $userId2);

        $conversation = Conversation::where('user1_id', $user1)
            ->where('user2_id', $user2)
            ->first();

        if (!$conversation) {
            Log::info('Creating new conversation between ' . $user1 . ' and ' . $user2);
            $conversation = Conversation::create([
                'user1_id' => $user1,
                'user2_id' => $user2,
                'last_message_at' => now()
            ]);
        }

        return $conversation;
    }

    private function addUrlsToMessage($message)
    {
        $messageArray = $message->toArray();
        
        // اضافه کردن URL تصویر sender
        if ($message->sender && $message->sender->user_image) {
            $messageArray['sender']['image_url'] = asset('storage/' . $message->sender->user_image);
        } else {
            $messageArray['sender']['image_url'] = asset('assets/sarafi/avatar.svg');
        }
        
        // اضافه کردن URL تصویر receiver
        if ($message->receiver && $message->receiver->user_image) {
            $messageArray['receiver']['image_url'] = asset('storage/' . $message->receiver->user_image);
        } else {
            $messageArray['receiver']['image_url'] = asset('assets/sarafi/avatar.svg');
        }
        
        // اضافه کردن URL کامل برای فایل‌های رسانه‌ای
        if ($message->media_path) {
            $messageArray['media_url'] = asset('storage/' . $message->media_path);
        }
        
        return $messageArray;
    }

     // بهبود تابع محاسبه مدت زمان صوت
    private function getAudioDuration($file)
    {
        try {
            // بررسی وجود کتابخانه getID3
            if (!class_exists('getID3')) {
                Log::warning('getID3 library not found, using fallback');
                return 0;
            }
            
            $getID3 = new \getID3();
            
            // تحلیل فایل
            $fileInfo = $getID3->analyze($file->getPathname());
            
            Log::info('Audio analysis:', [
                'playtime' => $fileInfo['playtime_seconds'] ?? null,
                'format' => $fileInfo['fileformat'] ?? null,
                'audio_format' => $fileInfo['audio']['dataformat'] ?? null
            ]);
            
            if (isset($fileInfo['playtime_seconds'])) {
                $duration = (int) ceil($fileInfo['playtime_seconds']);
                Log::info('Duration calculated successfully:', ['duration' => $duration]);
                return $duration;
            }
            
            // راه‌حل جایگزین برای فایل‌های خاص
            if (isset($fileInfo['audio']['bitrate']) && isset($fileInfo['filesize'])) {
                $bitrate = $fileInfo['audio']['bitrate'];
                $filesize = $fileInfo['filesize'];
                if ($bitrate > 0) {
                    $duration = (int) ceil(($filesize * 8) / $bitrate);
                    Log::info('Duration calculated from bitrate:', ['duration' => $duration]);
                    return $duration;
                }
            }
            
            Log::warning('Could not determine audio duration');
            return 0;
            
        } catch (\Exception $e) {
            Log::error('Error in getAudioDuration:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return 0;
        }
    }

    private function getMessagePreview($type, $message)
    {
        switch ($type) {
            case 'image':
                return 'عکس';
            case 'audio':
                return 'پیام صوتی';
            default:
                return strlen($message) > 50 ? substr($message, 0, 50) . '...' : $message;
        }
    }
}