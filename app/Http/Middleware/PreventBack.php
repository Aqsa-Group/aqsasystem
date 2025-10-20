<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PreventBack
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', '0');

        if ($request->is('sarafi/*') || $request->is('sarafi')) {
            if (!Auth::guard('sarafi')->check()) {
                return redirect()->route('sarafi.login.form');
            }
        }

        if ($request->is('tools/*') || $request->is('tools')) {
            if (!Auth::guard('tools')->check()) {
                return redirect()->route('tools.login.form');
            }
        }

        return $response;
    }
}
