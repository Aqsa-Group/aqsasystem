<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;

class UpdateUserStatus
{
    /**
     * Handle the event.
     */
    public function handle($event): void
    {
        if ($event instanceof Login) {
            // وقتی یوزر لاگین شد، استاتوس = 1 (فعال)
            $event->user->status = 1;
            $event->user->save();
        }

        if ($event instanceof Logout) {
            // وقتی یوزر لاگ اوت شد، استاتوس = 0 (غیرفعال)
            $event->user->status = 0;
            $event->user->save();
        }
    }
}
