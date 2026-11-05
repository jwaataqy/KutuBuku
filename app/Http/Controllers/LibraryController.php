<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LibraryController extends Controller
{
    public function index(Request $r)
    {
        return view('library', ['products' => $r->user()->library()->with('category')->get()]);
    }
    public function download(Request $r, Product $product)
    {
        abort_unless($r->user()->owns($product), 403);

        if (
            $product->file_path &&
            Storage::disk('local')->exists($product->file_path)
        ) {
            return Storage::disk('local')->download(
                $product->file_path
            );
        }

        return response()->streamDownload(
            fn() => print("Berkas contoh: {$product->title}. Letakkan file asli di storage/app/{$product->file_path}"),
            Str::slug($product->title) . '.txt'
        );
    }
}
