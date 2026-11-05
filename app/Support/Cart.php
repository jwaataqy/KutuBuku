<?php

namespace App\Support;

use App\Models\{Product, Voucher};

class Cart
{
    public static function summary(): array
    {
        $items = Product::with('category')->whereIn('id', session('cart', []))->get();
        $subtotal = $items->sum('final_price');
        $voucher = session('voucher') ? Voucher::where('code', session('voucher'))->where('active', true)->first() : null;
        $discount = $voucher ? intdiv($subtotal * $voucher->percent, 100) : 0;
        return ['items' => $items, 'subtotal' => $subtotal, 'discount' => $discount, 'voucher' => $voucher, 'total' => $subtotal - $discount];
    }
}
