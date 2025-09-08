<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;

class SetLocale
{
    /**
     * Handle an incoming request.
     */
    public function handle($request, Closure $next)
    {
        if ($request->is('sarafi/*') || $request->is('sarafi')) {
            $default = config('app.locale', 'fa');
            $locale = Session::get('locale', Cookie::get('locale', $default));
            $available = ['fa', 'ps', 'en'];
            if (! in_array($locale, $available)) {
                $locale = $default;
            }
            App::setLocale($locale);
        }
    
        return $next($request);
    }
    
}
