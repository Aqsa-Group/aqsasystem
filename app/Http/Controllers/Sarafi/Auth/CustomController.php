<?php

namespace App\Http\Controllers\Sarafi\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Sarafi\User;

class CustomController extends Controller
{

 public function showLoginForm()
{
   
    if (Auth::guard('sarafi')->check()) {
        return redirect()->route('sarafi.home');
    }

    return view('Sarafi.Auth.login');
}


    
    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');

        if (Auth::guard('sarafi')->attempt($credentials)) {
            $request->session()->regenerate();

            /** @var User $user */
            $user = Auth::guard('sarafi')->user();

            if ($user->status === 0) {
                $user->status = 1;
                $user->save();
            }

            return redirect()->route('sarafi.home'); // هدایت به داشبورد
        }

        return back()->with('error', 'نام کاربری یا پسورد اشتباه است.');
    }

    public function logout(Request $request)
{
    $user = Auth::guard('sarafi')->user();
    if ($user) {
     /** @var User $user */     
        $user->status = 0;
        $user->save();
    }

    Auth::guard('sarafi')->logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    if ($request->header('X-Livewire') || $request->ajax()) {
        return response()->json(['redirect' => route('sarafi.login.form')], 401);
    }

    return redirect()->route('sarafi.login.form');
}

}
