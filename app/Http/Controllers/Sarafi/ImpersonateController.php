<?php

namespace App\Http\Controllers\Sarafi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sarafi\ImpersonationToken;
use App\Models\Sarafi\User;
use Illuminate\Support\Facades\Auth;

class ImpersonateController extends Controller

{
  // در ImpersonateController
// در کنترلر ImpersonateController
public function login(Request $request)
{
    $hashed = hash('sha256', $request->token);
    
    $record = ImpersonationToken::where('token', $hashed)
        ->where('expires_at', '>', now())
        ->firstOrFail();
    
    $user = User::findOrFail($record->user_id);
    
    // پاک کردن session فعلی و ایجاد session جدید
    Auth::guard('sarafi')->logout();
    session()->flush();
    session()->regenerate();
    
    // لاگین کاربر جدید
    Auth::guard('sarafi')->login($user);
    
    // ذخیره فلگ impersonation
    session()->put([
        'is_impersonating' => true,
        'original_user_id' => $record->super_admin_id,
        'impersonation_token' => $record->id
    ]);
    
    $record->delete();
    
    return redirect()->route('sarafi.home');
}

// در جایی که می‌خواهید از impersonation خارج شوید
public function stopImpersonating()
{
    if (session()->has('is_impersonating')) {
        $originalUserId = session()->get('original_user_id');
        
        Auth::guard('sarafi')->logout();
        session()->flush();
        session()->regenerate();
        
        $originalUser = User::findOrFail($originalUserId);
        Auth::guard('sarafi')->login($originalUser);
        
        return redirect()->route('sarafi.home');
    }
}



}
