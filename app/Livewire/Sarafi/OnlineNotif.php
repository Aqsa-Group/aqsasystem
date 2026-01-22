<?php

namespace App\Livewire\Sarafi;

use Livewire\Component;
use App\Models\Sarafi\OnlineNotifications;
use Illuminate\Support\Facades\Auth;

class OnlineNotif extends Component
{
    public $message = '';
    public $editId = null;

    public function save()
    {
        $this->validate(['message' => 'required|string|max:500']);
        $currentUser = Auth::guard('sarafi')->user();

        OnlineNotifications::create([
            'user_id' => $currentUser->id,
            'admin_id' => $currentUser->id,
            'message' => $this->message,
        ]);

        $this->message = '';
        session()->flash('success', 'پیام با موفقیت ثبت شد.');
    }

    public function render()
    {
        $currentUser = Auth::guard('sarafi')->user();
        $notifications = OnlineNotifications::latest()->take(5)->get();

        return view('livewire.sarafi.online-notif', [
            'notifications' => $notifications,
            'currentUser' => $currentUser,
        ]);
    }
}
