<?php

namespace App\Http\Controllers;

use App\Models\Sarafi\Message;
use App\Models\Sarafi\Conversation;
use App\Models\Sarafi\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ChatController extends Controller
{
    // Send a message
    public function sendMessage(Request $request)
    {
        Log::info('=== CHAT SEND MESSAGE START ===');
        Log::info('Request data:', $request->all());
        
        try {
            // اصلاح کامل validation
            $validator = \Validator::make($request->all(), [
                'receiver_id' => [
                    'required',
                    function ($attribute, $value, $fail) {
                        if (!User::where('id', $value)->exists()) {
                            $fail('کاربر دریافت‌کننده معتبر نیست.');
                        }
                    }
                ],
                'message' => 'required|string|max:1000'
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
            Log::info('Sender:', [
                'id' => $sender->id,
                'name' => $sender->name,
                'role' => $sender->role,
                'admin_id' => $sender->admin_id
            ]);

            $receiver = User::find($request->receiver_id);
            if (!$receiver) {
                Log::error('Receiver not found with ID: ' . $request->receiver_id);
                return response()->json([
                    'success' => false,
                    'error' => 'کاربر دریافت‌کننده یافت نشد'
                ], 404);
            }
            
            Log::info('Receiver:', [
                'id' => $receiver->id,
                'name' => $receiver->name,
                'role' => $receiver->role,
                'admin_id' => $receiver->admin_id
            ]);

            // Check authorization
            $canMessage = $this->canMessage($sender, $receiver);
            Log::info('Can message check:', ['result' => $canMessage]);
            
            if (!$canMessage) {
                Log::warning('User not authorized to send message');
                return response()->json([
                    'success' => false,
                    'error' => 'شما اجازه ارسال پیام به این کاربر را ندارید'
                ], 403);
            }

            DB::connection('sarafi')->beginTransaction();

            try {
                // Create message
                $message = Message::create([
                    'sender_id' => $sender->id,
                    'receiver_id' => $receiver->id,
                    'message' => $request->message,
                    'is_read' => false
                ]);
                
                Log::info('Message created:', ['id' => $message->id]);

                // Create or update conversation
                $conversation = $this->getOrCreateConversation($sender->id, $receiver->id);
                Log::info('Conversation:', ['id' => $conversation->id]);
                
                $conversation->update([
                    'last_message' => $request->message,
                    'last_message_at' => now(),
                ]);
                
                // Increment unread count for receiver
                if ($conversation->user1_id == $receiver->id) {
                    $conversation->increment('unread_count_user1');
                } else {
                    $conversation->increment('unread_count_user2');
                }

                DB::connection('sarafi')->commit();
                
                Log::info('=== CHAT SEND MESSAGE SUCCESS ===');

                return response()->json([
                    'success' => true,
                    'message' => $message->load('sender')
                ]);

            } catch (\Exception $e) {
                DB::connection('sarafi')->rollBack();
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
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'error' => 'خطا در ارسال پیام: ' . $e->getMessage()
            ], 500);
        }
    }

    // Get messages between users
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

        // Get messages
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
              // ✅ اضافه کردن image_url به sender و receiver
              $messageArray = $message->toArray();
              
              // اضافه کردن تصویر sender
              if ($message->sender && $message->sender->user_image) {
                  $messageArray['sender']['image_url'] = asset('storage/' . $message->sender->user_image);
              } else {
                  $messageArray['sender']['image_url'] = asset('assets/sarafi/avatar.svg');
              }
              
              // اضافه کردن تصویر receiver
              if ($message->receiver && $message->receiver->user_image) {
                  $messageArray['receiver']['image_url'] = asset('storage/' . $message->receiver->user_image);
              } else {
                  $messageArray['receiver']['image_url'] = asset('assets/sarafi/avatar.svg');
              }
              
              return $messageArray;
          });

        // Mark messages as read
        Message::where('sender_id', $otherUser->id)
               ->where('receiver_id', $currentUser->id)
               ->where('is_read', false)
               ->update(['is_read' => true]);

        // Update conversation unread count
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

    // ✅ تابع جدید برای دریافت فقط پیام‌های جدید
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
                // ✅ اضافه کردن image_url به sender و receiver
                $messageArray = $message->toArray();
                
                // اضافه کردن تصویر sender
                if ($message->sender && $message->sender->user_image) {
                    $messageArray['sender']['image_url'] = asset('storage/' . $message->sender->user_image);
                } else {
                    $messageArray['sender']['image_url'] = asset('assets/sarafi/avatar.svg');
                }
                
                // اضافه کردن تصویر receiver
                if ($message->receiver && $message->receiver->user_image) {
                    $messageArray['receiver']['image_url'] = asset('storage/' . $message->receiver->user_image);
                } else {
                    $messageArray['receiver']['image_url'] = asset('assets/sarafi/avatar.svg');
                }
                
                return $messageArray;
            });

        Log::info('Found ' . $messages->count() . ' new messages');

        // Mark messages as read (only new ones from the other user)
        if ($messages->count() > 0) {
            Message::where('sender_id', $otherUser->id)
                   ->where('receiver_id', $currentUser->id)
                   ->where('is_read', false)
                   ->where('id', '>', $lastMessageId)
                   ->update(['is_read' => true]);

            // Update conversation unread count
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

    // Get conversations list
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
                
                // ✅ اضافه کردن user_image به other_user
                return [
                    'id' => $conversation->id,
                    'other_user' => [
                        'id' => $otherUser->id,
                        'name' => $otherUser->name,
                        'lastname' => $otherUser->lastname,
                        'user_image' => $otherUser->user_image,
                        'image_url' => $otherUser->user_image ? asset('storage/' . $otherUser->user_image) : asset('assets/sarafi/avatar.svg'),
                    ],
                    'last_message' => $conversation->last_message,
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

    // Get users available for chat
    public function getChatUsers()
    {
        $currentUser = Auth::guard('sarafi')->user();

        $users = User::where('id', '!=', $currentUser->id)
            ->where(function ($query) use ($currentUser) {
                // ✅ همه بتوانند سوپر ادمین را ببینند
                $query->where('role', 'superadmin');

                // ✅ سوپر ادمین همه را ببیند
                if ($currentUser->role === 'superadmin') {
                    $query->orWhereIn('role', [
                        'admin',
                        'warehouse_manager',
                        'internal_officer',
                        'external_officer'
                    ]);
                }

                // ✅ ادمین فقط خزانه‌دارهای خودش
                if ($currentUser->role === 'admin') {
                    $query->orWhere(function ($q) use ($currentUser) {
                        $q->where('role', 'warehouse_manager')
                          ->where('admin_id', $currentUser->id);
                    });
                }

                // ✅ خزانه‌دار فقط ادمین خودش
                if ($currentUser->role === 'warehouse_manager') {
                    $query->orWhere('id', $currentUser->admin_id);
                }
            })
            ->select('id', 'name', 'lastname', 'sarafi_name', 'role', 'admin_id', 'phone', 'user_image')
            ->get()
            ->map(function ($user) {
                // ✅ اضافه کردن image_url
                $userArray = $user->toArray();
                $userArray['image_url'] = $user->user_image ? asset('storage/' . $user->user_image) : asset('assets/sarafi/avatar.svg');
                return $userArray;
            });

        return response()->json([
            'success' => true,
            'users' => $users
        ]);
    }

    // Get unread message count
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

    // Search users for chat
    public function searchUsers(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:2'
        ]);

        $currentUser = Auth::guard('sarafi')->user();
        $query = $request->query;

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
                // ✅ اضافه کردن image_url
                $userArray = $user->toArray();
                $userArray['image_url'] = $user->user_image ? asset('storage/' . $user->user_image) : asset('assets/sarafi/avatar.svg');
                return $userArray;
            });

        return response()->json([
            'success' => true,
            'users' => $users
        ]);
    }

    // Mark all messages as read
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

    // Test endpoint for debugging
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
                'image_url' => $user->user_image ? asset('storage/' . $user->user_image) : asset('assets/sarafi/avatar.svg'),
            ],
            'session' => session()->all(),
            'auth' => auth()->check(),
            'guard' => auth()->guard()->getName(),
            'users_count' => User::count(),
            'messages_count' => Message::count(),
            'conversations_count' => Conversation::count()
        ]);
    }

    private function canMessage($sender, $receiver)
    {
        // جلوگیری از پیام به خود
        if ($sender->id === $receiver->id) {
            return false;
        }

        // ✅ سوپر ادمین ↔ همه
        if ($sender->role === 'superadmin' || $receiver->role === 'superadmin') {
            return true;
        }

        // ✅ ادمین ↔ خزانه‌دارهای خودش
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
}