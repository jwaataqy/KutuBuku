<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit(Request $r)
    {
        return view('profile', ['user' => $r->user(), 'wishlist' => $r->user()->wishlist()->with('category')->get()]);
    }
    public function update(Request $r)
    {
        $u = $r->user();
        $d = $r->validate(['name' => 'required|max:100', 'email' => "required|email|unique:users,email,{$u->id}", 'avatar' => 'nullable|image|max:2048']);
        if ($r->hasFile('avatar')) $d['avatar'] = $r->file('avatar')->store('avatars', 'public');
        $u->update($d);
        return back()->with('ok', 'Profil diperbarui.');
    }
    public function password(Request $r)
    {
        $d = $r->validate(['current' => 'required', 'password' => 'required|min:8|confirmed']);
        if (!Hash::check($d['current'], $r->user()->password)) return back()->withErrors(['current' => 'Password lama salah.']);
        $r->user()->update(['password' => $d['password']]);
        return back()->with('ok', 'Password diubah.');
    }
}
