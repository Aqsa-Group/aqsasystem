<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Models\Sarafi\User;

class SetUserStatusActive
{
    public function handle(Login $event)
    {
        if ($event->guard === 'sarafi' && $event->user instanceof User) {
            $user = $event->user;

            $user->status = 1;
            $user->save();
        }
    }
}
