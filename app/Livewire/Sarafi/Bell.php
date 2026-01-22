<?php
namespace App\Livewire\Sarafi;

use Livewire\Component;
use App\Models\Sarafi\SarafiNotification;
use Illuminate\Support\Facades\Auth;

class Bell extends Component
{
    public $notifications = [];
    public $count = 0;


    public bool $open = false;

public function toggle()
{
    $this->open = ! $this->open;
}


    public function mount()
    {
        $this->refreshData();
    }

    public function refreshData()
    {
        $user = Auth::guard('sarafi')->user();

        $this->notifications = SarafiNotification::where('user_id', $user->id)
            ->latest()
            ->take(10)
            ->get();

        $this->count = SarafiNotification::where('user_id', $user->id)
            ->where('is_read', false)
            ->count();
    }

    public function markAsRead($id)
    {
        SarafiNotification::where('id', $id)
            ->update(['is_read' => true]);

        $this->refreshData();
    }

    public function render()
    {
        return view('livewire.sarafi.bell');
    }
}
