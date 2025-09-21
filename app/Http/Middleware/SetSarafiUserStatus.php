<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SetSarafiUserStatus
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::guard('sarafi')->user();

        if ($user && $user->status === 0) {
            $user->status = 1; 
            /** @var User $user */
            $user->save();
        }

        return $next($request);
    }
}
