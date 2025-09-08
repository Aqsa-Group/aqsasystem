<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;

class LocaleSessionMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $uri = $request->getRequestUri();

        // فقط روی مسیرهای خاص اعمال بشه
        if (str_starts_with($uri, '/sarafi') || str_starts_with($uri, '/set-locale')) {
            return app(Pipeline::class)
                ->send($request)
                ->through([
                    \Illuminate\Cookie\Middleware\EncryptCookies::class,
                    \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
                    \Illuminate\Session\Middleware\StartSession::class,
                    \App\Http\Middleware\SetLocale::class,
                ])
                ->then(function ($request) use ($next) {
                    return $next($request);
                });
        }

        // مسیرهای دیگه بدون تغییر
        return $next($request);
    }
}
