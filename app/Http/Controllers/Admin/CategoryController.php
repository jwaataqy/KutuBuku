<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        return view('admin.categories', ['categories' => Category::withCount('products')->orderBy('name')->get()]);
    }

    public function store(Request $r)
    {
        $d = $r->validate(['name' => 'required|max:80|unique:categories,name']);
        $base = Str::slug($d['name']);
        $slug = $base;
        $i = 2;
        while (Category::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }
        Category::create($d + ['slug' => $slug]);
        return back()->with('ok', 'Kategori ditambahkan.');
    }

    public function update(Request $r, Category $category)
    {
        $d = $r->validate(['name' => "required|max:80|unique:categories,name,{$category->id}"]);
        $category->update($d);
        return back()->with('ok', 'Kategori diperbarui.');
    }

    public function destroy(Category $category)
    {
        if ($category->products()->exists()) {
            return back()->withErrors(['delete' => 'Kategori masih dipakai oleh produk, jadi tidak bisa dihapus.']);
        }
        $category->delete();
        return back()->with('ok', 'Kategori dihapus.');
    }
}