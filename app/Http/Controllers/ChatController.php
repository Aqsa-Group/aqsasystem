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
          ->get();

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
                
                return [
                    'id' => $conversation->id,
                    'other_user' => $otherUser,
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
        Log::info('Getting chat users for: ' . $currentUser->id . ' - ' . $currentUser->role);
        
        $users = collect();
        
        if ($currentUser->role === 'superadmin') {
            $users = User::where('id', '!=', $currentUser->id)
                ->whereIn('role', ['admin', 'warehouse_manager'])
                ->select('id', 'name', 'lastname', 'sarafi_name', 'role', 'admin_id', 'phone')
                ->get();
        } elseif ($currentUser->role === 'admin') {
            $users = User::where(function($query) use ($currentUser) {
                $query->where('role', 'superadmin')
                    ->orWhere(function($q) use ($currentUser) {
                        $q->where('admin_id', $currentUser->id)
                          ->where('role', 'warehouse_manager');
                    });
            })
            ->select('id', 'name', 'lastname', 'sarafi_name', 'role', 'admin_id', 'phone')
            ->get();
        } else {
            $users = User::where('role', 'superadmin')
                ->orWhere('id', $currentUser->admin_id)
                ->select('id', 'name', 'lastname', 'sarafi_name', 'role', 'admin_id', 'phone')
                ->get();
        }
        
        Log::info('Found users:', ['count' => $users->count()]);

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
            ->select('id', 'name', 'lastname', 'sarafi_name', 'role', 'admin_id', 'phone')
            ->limit(20)
            ->get();

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
                'admin_id' => $user->admin_id
            ],
            'session' => session()->all(),
            'auth' => auth()->check(),
            'guard' => auth()->guard()->getName(),
            'users_count' => User::count(),
            'messages_count' => Message::count(),
            'conversations_count' => Conversation::count()
        ]);
    }

    // Private helper methods
    private function canMessage($sender, $receiver)
    {
        Log::info('Checking if user can message:', [
            'sender_id' => $sender->id,
            'sender_role' => $sender->role,
            'receiver_id' => $receiver->id,
            'receiver_role' => $receiver->role,
            'receiver_admin_id' => $receiver->admin_id
        ]);

        // Can't message yourself
        if ($sender->id == $receiver->id) {
            Log::info('Cannot message yourself');
            return false;
        }

        // Super admin can message all admins and warehouse managers
        if ($sender->role === 'superadmin') {
            $allowed = in_array($receiver->role, ['admin', 'warehouse_manager']);
            Log::info('Super admin check result:', ['allowed' => $allowed]);
            return $allowed;
        }

        // Admin can message super admin and their own warehouse managers
        if ($sender->role === 'admin') {
            $allowed = $receiver->role === 'superadmin' || 
                       ($receiver->admin_id == $sender->id && $receiver->role === 'warehouse_manager');
            Log::info('Admin check result:', [
                'allowed' => $allowed,
                'receiver_admin_id' => $receiver->admin_id,
                'sender_id' => $sender->id
            ]);
            return $allowed;
        }

        // Warehouse managers can message their admin and super admin
        if ($sender->role === 'warehouse_manager') {
            $allowed = $receiver->role === 'superadmin' || $receiver->id == $sender->admin_id;
            Log::info('Warehouse manager check result:', ['allowed' => $allowed]);
            return $allowed;
        }

        Log::info('Default case: not allowed');
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