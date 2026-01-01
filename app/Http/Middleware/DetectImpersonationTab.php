<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class DetectImpersonationTab
{
    public function handle($request, Closure $next)
    {
        // بررسی اگر کاربر در حال impersonation است
        if ($request->hasCookie('laravel_impersonate_session')) {
            $impersonateSessionId = $request->cookie('laravel_impersonate_session');
            
            // اگر session اصلی داریم اما کوکی impersonation هم داریم
            if (session()->has('impersonation_data')) {
                $data = session()->get('impersonation_data');
                
                // مطابقت session ID
                if ($data['impersonate_session_id'] !== $impersonateSessionId) {
                    // این تب سوپرادمین است، پس impersonation را متوقف کنیم
                    Auth::logout();
                    session()->flush();
                    
                    // ری‌دایرکت به صفحه لاگین
                    return redirect()->route('sarafi.login');
                }
            }
            
            // برای تب impersonation، session lifetime را کوتاه کن
            config(['session.lifetime' => 10]);
        }
        
        return $next($request);
    }
}