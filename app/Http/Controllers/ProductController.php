<?php

namespace App\Http\Controllers;

use App\Models\{Category, Product, Review};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index(Request $r)
    {
        $q = Product::with('category')->withAvg('reviews', 'rating');
        $q->when($r->q, fn($x, $v) => $x->where('title', 'like', "%$v%"));
        $q->when($r->category, fn($x, $v) => $x->whereHas('category', fn($c) => $c->where('slug', $v)));
        $q->when($r->min, fn($x, $v) => $x->whereRaw('COALESCE(discount_price,price)>=?', [$v]));
        $q->when($r->max, fn($x, $v) => $x->whereRaw('COALESCE(discount_price,price)<=?', [$v]));
        if ($r->type === 'free') $q->whereRaw('COALESCE(discount_price,price)=0');
        if ($r->type === 'paid') $q->whereRaw('COALESCE(discount_price,price)>0');
        $r->sort === 'terlaris' ? $q->orderByDesc('sold') : $q->latest();
        return view('catalog', ['products' => $q->paginate(12)->withQueryString(), 'categories' => Category::all()]);
    }
    public function show(Product $product)
    {
        $product->load('category', 'reviews.user')->loadAvg('reviews', 'rating');
        $u = auth()->user();
        return view('product', [
            'product' => $product,
            'owned' => $u?->owns($product),
            'wished' => $u?->wishlist()->where('products.id', $product->id)->exists(),
            'related' => Product::with('category')->where('category_id', $product->category_id)->whereKeyNot($product->id)->take(4)->get()
        ]);
    }
    public function review(Request $r, Product $product)
    {
        abort_unless($r->user()->owns($product), 403);
        $d = $r->validate(['rating' => 'required|integer|between:1,5', 'body' => 'required|string|max:1000']);
        Review::updateOrCreate(['user_id' => $r->user()->id, 'product_id' => $product->id], $d);
        return back()->with('ok', 'Terima kasih atas ulasanmu!');
    }
    public function wish(Request $r, Product $product)
    {
        $r->user()->wishlist()->toggle($product->id);
        return back();
    }
}
