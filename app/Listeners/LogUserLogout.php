<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use App\Models\Market\UserLog;

class LogUserLogout
{
    public function handle(Logout $event)
    {
        if ($event->user instanceof \App\Models\Market\User) {
            $log = UserLog::where('user_id', $event->user->id)
                ->whereNull('logout_at')
                ->latest()
                ->first();

            if ($log) {
                $log->update(['logout_at' => now()]);
            }
        }
    }
}
