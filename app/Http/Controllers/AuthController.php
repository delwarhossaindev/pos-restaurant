<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required' => 'ইমেইল দিন',
            'email.email' => 'সঠিক ইমেইল দিন',
            'password.required' => 'পাসওয়ার্ড দিন',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->remember)) {
            $user = Auth::user();
            if (!$user->is_active) {
                Auth::logout();
                return back()->withErrors(['email' => 'আপনার অ্যাকাউন্ট নিষ্ক্রিয় করা হয়েছে।']);
            }

            if ($user->isKitchen()) {
                return redirect()->route('kitchen.index');
            }
            return redirect()->route('dashboard');
        }

        return back()->withErrors(['email' => 'ইমেইল বা পাসওয়ার্ড ভুল।'])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
