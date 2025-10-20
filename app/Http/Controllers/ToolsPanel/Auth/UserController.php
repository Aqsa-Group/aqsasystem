<?php

namespace App\Http\Controllers\ToolsPanel\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tools\User;


class UserController extends Controller
{
     public function showLoginForm()
{
   
    if (Auth::guard('tools')->check()) {
        return redirect()->route('tools.home');
    }

    return view('ToolsPanel.Auth.login');
}


    
    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');

        if (Auth::guard('tools')->attempt($credentials)) {
            $request->session()->regenerate();

            /** @var User $user */
            $user = Auth::guard('tools')->user();

            if ($user->status === 0) {
                $user->status = 1;
                $user->save();
            }

            return redirect()->route('tools.home'); 
        }

        return back()->with('error', 'نام کاربری یا رمز  اشتباه است.');
    }

        public function logout(Request $request)
    {
        $user = Auth::guard('tools')->user();
        if ($user) {
        /** @var User $user */     
            $user->status = 0;
            $user->save();
        }

        Auth::guard('tools')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->header('X-Livewire') || $request->ajax()) {
            return response()->json(['redirect' => route('tools.login.form')], 401);
        }

        return redirect()->route('tools.login.form');
    }
}
