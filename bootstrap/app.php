<?php

use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use App\Http\Middleware\SetLocale;
use Illuminate\Foundation\Application;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // $middleware->append(EncryptCookies::class);
        $middleware->append(AddQueuedCookiesToResponse::class);
        // $middleware->append(StartSession::class);
        $middleware->append(SetLocale::class);

    })
    ->withExceptions(function ($exceptions): void {
        //
    })->create();
