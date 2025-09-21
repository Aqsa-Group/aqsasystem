<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use App\Models\Sarafi\User;

class SetUserStatusInactive
{
    public function handle(Logout $event)
    {
        if ($event->guard === 'sarafi' && $event->user instanceof User) {
            $user = $event->user;

            // تغییر وضعیت به غیرفعال
            $user->status = 0;
            $user->save();
        }
    }
}
