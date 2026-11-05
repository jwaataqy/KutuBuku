<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Category, OrderItem, Product};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    private function rules(): array
    {
        return [
            'category_id'    => 'required|exists:categories,id',
            'title'          => 'required|max:200',
            'author'         => 'required|max:120',
            'description'    => 'required',
            'preview'        => 'nullable',
            'price'          => 'required|integer|min:0',
            'discount_price' => 'nullable|integer|min:0|lt:price',
            'format'         => 'required|in:PDF,EPUB,ZIP',
            'file'           => 'nullable|file|max:51200|extensions:pdf,epub,zip',
        ];
    }

    private function uniqueSlug(string $title): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i = 2;
        while (Product::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }

    public function index(Request $r)
    {
        $products = Product::with('category')
            ->when($r->q, fn ($q, $v) => $q->where('title', 'like', "%$v%"))
            ->latest()->paginate(15)->withQueryString();
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.products.form', ['product' => new Product, 'categories' => Category::orderBy('name')->get()]);
    }

    public function store(Request $r)
    {
        $d = $r->validate($this->rules());
        $d['slug'] = $this->uniqueSlug($d['title']);
        $d['featured'] = $r->boolean('featured');
        if ($r->hasFile('file')) {
            $d['file_path'] = $r->file('file')->store('ebooks');
        }
        unset($d['file']);
        Product::create($d);
        return redirect()->route('admin.products.index')->with('ok', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        return view('admin.products.form', ['product' => $product, 'categories' => Category::orderBy('name')->get()]);
    }

    public function update(Request $r, Product $product)
    {
        $d = $r->validate($this->rules());
        $d['featured'] = $r->boolean('featured');
        if ($r->hasFile('file')) {
            if ($product->file_path) {
                Storage::delete($product->file_path);
            }
            $d['file_path'] = $r->file('file')->store('ebooks');
        }
        unset($d['file']);
        $product->update($d);
        return redirect()->route('admin.products.index')->with('ok', 'Produk diperbarui.');
    }

    public function destroy(Product $product)
    {
        if (OrderItem::where('product_id', $product->id)->exists()) {
            return back()->withErrors(['delete' => 'Produk ini sudah pernah dibeli, jadi tidak bisa dihapus agar perpustakaan pembeli tetap aman.']);
        }
        if ($product->file_path) {
            Storage::delete($product->file_path);
        }
        $product->delete();
        return back()->with('ok', 'Produk dihapus.');
    }
}