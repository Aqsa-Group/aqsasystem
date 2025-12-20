<?php

namespace App\Http\Controllers\Market\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Market\User;


class AuthUser extends Controller
{
     public function showLoginForm()
{
    
    if (Auth::guard('market')->check()) {
        return redirect()->route('market.home');
    }

    return view('Market.Auth.login');
}


    
    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');

        if (Auth::guard('market')->attempt($credentials)) {
            $request->session()->regenerate();

            /** @var User $user */
            $user = Auth::guard('market')->user();

            if ($user->status === 0) {
                $user->status = 1;
                $user->save();
            }

            return redirect()->route('market.home'); 
        }

        return back()->with('error', 'نام کاربری یا رمز  اشتباه است.');
    }

        public function logout(Request $request)
    {
        $user = Auth::guard('market')->user();
        if ($user) {
        /** @var User $user */     
            $user->status = 0;
            $user->save();
        }

        Auth::guard('market')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->header('X-Livewire') || $request->ajax()) {
            return response()->json(['redirect' => route('market.login.form')], 401);
        }

        return redirect()->route('market.login.form');
    }
}
