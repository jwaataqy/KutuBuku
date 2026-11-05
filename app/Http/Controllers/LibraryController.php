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
        $disk = Storage::disk('s3');
        abort_unless($product->file_path && $disk->exists($product->file_path), 404, 'File belum tersedia.');
        $ext = pathinfo($product->file_path, PATHINFO_EXTENSION);
        return redirect($disk->temporaryUrl(
            $product->file_path,
            now()->addMinutes(5),
            ['ResponseContentDisposition' => 'attachment; filename="' . Str::slug($product->title) . '.' . $ext . '"']
        ));
    }
}
