<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;

class ReviewController extends Controller
{
    public function index()
    {
        return view('admin.reviews', ['reviews' => Review::with('user', 'product')->latest()->paginate(15)]);
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return back()->with('ok', 'Ulasan dihapus.');
    }
}