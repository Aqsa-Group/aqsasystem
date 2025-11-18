<?php

namespace App\Http\Controllers\Gym\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Gym\User;


class UserControllers extends Controller
{
     public function showLoginForm()
{
   
    if (Auth::guard('gyms')->check()) {
        return redirect()->route('gym.home');
    }

    return view('Gym.Auth.login');
}


    
    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');

        if (Auth::guard('gyms')->attempt($credentials)) {
            $request->session()->regenerate();

            /** @var User $user */
            $user = Auth::guard('gyms')->user();

            if ($user->status === 0) {
                $user->status = 1;
                $user->save();
            }

            return redirect()->route('gym.home'); 
        }

        return back()->with('error', 'نام کاربری یا رمز  اشتباه است.');
    }

        public function logout(Request $request)
    {
        $user = Auth::guard('gyms')->user();
        if ($user) {
        /** @var User $user */     
            $user->status = 0;
            $user->save();
        }

        Auth::guard('gyms')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->header('X-Livewire') || $request->ajax()) {
            return response()->json(['redirect' => route('gym.login.form')], 401);
        }

        return redirect()->route('gym.login.form');
    }
}
