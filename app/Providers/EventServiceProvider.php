<?php

namespace App\Providers;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use App\Listeners\UpdateUserStatus;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
    \Illuminate\Auth\Events\Login::class => [
        \App\Listeners\SetUserStatusActive::class,
    ],
    \Illuminate\Auth\Events\Logout::class => [
        \App\Listeners\SetUserStatusInactive::class,
    ],
];


    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }
}
