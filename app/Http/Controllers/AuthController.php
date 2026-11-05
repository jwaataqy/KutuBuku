<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $r)
    {
        $c = $r->validate(['email' => 'required|email', 'password' => 'required']);
        if (Auth::attempt($c, $r->boolean('remember'))) {
            $r->session()->regenerate();
            return redirect()->intended('/');
        }
        return back()->withErrors(['email' => 'Email atau password salah.'])->onlyInput('email');
    }
    public function register(Request $r)
    {
        $d = $r->validate(['name' => 'required|max:100', 'email' => 'required|email|unique:users', 'password' => 'required|min:8|confirmed']);
        Auth::login(User::create($d));
        return redirect('/');
    }
    public function logout(Request $r)
    {
        Auth::logout();
        $r->session()->invalidate();
        $r->session()->regenerateToken();
        return redirect('/');
    }
}
