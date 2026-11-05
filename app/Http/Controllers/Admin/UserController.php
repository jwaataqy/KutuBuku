<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $r)
    {
        $users = User::withCount('orders')
            ->withSum(['orders as spent' => fn ($q) => $q->where('status', 'paid')], 'total')
            ->when($r->q, fn ($q, $v) => $q->where(fn ($w) => $w->where('name', 'like', "%$v%")->orWhere('email', 'like', "%$v%")))
            ->latest()->paginate(15)->withQueryString();
        return view('admin.users', compact('users'));
    }

    public function toggleAdmin(Request $r, User $user)
    {
        abort_if($user->id === $r->user()->id, 403, 'Kamu tidak bisa mengubah akses dirimu sendiri.');
        $user->is_admin = !$user->is_admin;
        $user->save();
        return back()->with('ok', 'Akses pengguna diperbarui.');
    }
}