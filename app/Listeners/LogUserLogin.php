<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Models\Market\UserLog;

class LogUserLogin
{
    public function handle(Login $event)
    {
        if ($event->user instanceof \App\Models\Market\User) {
            UserLog::create([
                'user_id' => $event->user->id,
                'login_at' => now(),
            ]);
        }
    }
}
