<?php

namespace App\Http\Controllers;

use App\Models\{Product, Voucher};
use App\Support\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        return view('cart', Cart::summary());
    }
    public function add(Request $r, Product $product)
    {
        if ($r->user()?->owns($product)) return redirect()->route('library')->with('ok', 'Buku ini sudah ada di perpustakaanmu.');
        $c = session('cart', []);
        $c[] = $product->id;
        session(['cart' => array_values(array_unique($c))]);
        return $r->boolean('buy') ? redirect()->route('cart') : back()->with('ok', 'Ditambahkan ke keranjang.');
    }
    public function remove(Product $product)
    {
        session(['cart' => array_values(array_diff(session('cart', []), [$product->id]))]);
        return back();
    }
    public function voucher(Request $r)
    {
        $v = Voucher::where('code', strtoupper(trim($r->code)))->where('active', true)->first();
        if (!$v) return back()->withErrors(['code' => 'Kode voucher tidak valid.']);
        session(['voucher' => $v->code]);
        return back()->with('ok', 'Voucher dipakai: hemat ' . $v->percent . '%');
    }
}
