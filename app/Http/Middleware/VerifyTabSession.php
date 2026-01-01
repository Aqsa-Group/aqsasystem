<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class VerifyTabSession
{
    public function handle($request, Closure $next)
    {
        $tabId = $request->header('X-Tab-ID');
        
        if ($tabId) {
            // ذخیره tabId در session
            $currentTabId = session()->get('current_tab_id');
            
            if ($currentTabId && $currentTabId !== $tabId) {
                // تب متفاوت است - بررسی کنیم آیا impersonation در حال انجام است؟
                if (session()->has('impersonation_data')) {
                    // اگر impersonation فعال است، logout کن
                    Auth::logout();
                    session()->flush();
                    return redirect()->route('sarafi.login');
                }
            }
            
            session()->put('current_tab_id', $tabId);
        }
        
        return $next($request);
    }
}