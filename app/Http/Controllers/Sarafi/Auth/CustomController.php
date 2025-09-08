<?php

namespace App\Http\Controllers\Sarafi\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomController extends Controller
{
    
    public function showLoginForm()
    {
        return view('Sarafi.Auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');
    
        if (Auth::guard('sarafi')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('sarafi.home'); // هدایت به داشبورد
        }
    
        return back()->with('error', 'نام کاربری یا پسورد اشتباه است.');
    }
    
    
    public function logout(Request $request)
    {
        Auth::guard('sarafi')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('sarafi.login.form');
    }
}
