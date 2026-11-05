<?php

namespace App\Http\Controllers;

use App\Models\{Category, Product};

class HomeController extends Controller
{
    public function index()
    {
        $b = fn() => Product::with('category')->withAvg('reviews', 'rating');
        return view('home', [
            'latest' => $b()->latest()->take(4)->get(),
            'bestsellers' => $b()->orderByDesc('sold')->take(4)->get(),
            'recommended' => $b()->where('featured', true)->take(4)->get(),
            'promo' => $b()->whereNotNull('discount_price')->take(4)->get(),
            'categories' => Category::withCount('products')->get(),
        ]);
    }
}
