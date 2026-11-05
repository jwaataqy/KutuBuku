@extends('admin.layout')
@php
    $edit = $product->exists;
    $in = 'w-full rounded-md border border-ink/15 bg-white px-3 py-2.5 focus:border-gold focus:outline-none';
@endphp
@section('title', $edit ? 'Edit Produk' : 'Tambah Produk')
@section('heading', $edit ? 'Edit Produk' : 'Tambah Produk')
@section('content')
    <form method="POST" enctype="multipart/form-data"
        action="{{ $edit ? route('admin.products.update', $product) : route('admin.products.store') }}"
        class="max-w-3xl space-y-5 rounded-lg border border-ink/10 bg-white p-8 text-sm">
        @csrf @if ($edit)
            @method('PUT')
        @endif
        <div class="grid gap-5 md:grid-cols-2">
            <div><label class="mb-1 block font-medium">Judul</label><input name="title"
                    value="{{ old('title', $product->title) }}" required class="{{ $in }}"></div>
            <div><label class="mb-1 block font-medium">Penulis</label><input name="author"
                    value="{{ old('author', $product->author) }}" required class="{{ $in }}"></div>
            <div><label class="mb-1 block font-medium">Kategori</label><select name="category_id"
                    class="{{ $in }}">
                    @foreach ($categories as $c)
                        <option value="{{ $c->id }}" @selected(old('category_id', $product->category_id) == $c->id)>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div><label class="mb-1 block font-medium">Format</label><select name="format" class="{{ $in }}">
                    @foreach (['PDF', 'EPUB', 'ZIP'] as $f)
                        <option @selected(old('format', $product->format) == $f)>{{ $f }}</option>
                    @endforeach
                </select>
            </div>
            <div><label class="mb-1 block font-medium">Harga normal (Rp, 0 = gratis)</label><input type="number"
                    min="0" name="price" value="{{ old('price', $product->price ?? 0) }}" required
                    class="{{ $in }}"></div>
            <div><label class="mb-1 block font-medium">Harga diskon (opsional)</label><input type="number" min="0"
                    name="discount_price" value="{{ old('discount_price', $product->discount_price) }}"
                    class="{{ $in }}"></div>
        </div>
        <div><label class="mb-1 block font-medium">File e-book (PDF / EPUB / ZIP, maks 50 MB)</label><input type="file"
                name="file" accept=".pdf,.epub,.zip">
            @if ($edit && $product->file_path)
                <p class="mt-1 text-xs text-ink/50">File saat ini: {{ basename($product->file_path) }}
                    {{ \Illuminate\Support\Facades\Storage::disk('s3')->exists($product->file_path) ? '' : '(belum diunggah)' }}</p></p>
            @endif
        </div>
        <div><label class="mb-1 block font-medium">Deskripsi</label>
            <textarea name="description" rows="4" required class="{{ $in }}">{{ old('description', $product->description) }}</textarea>
        </div>
        <div><label class="mb-1 block font-medium">Preview isi (opsional)</label>
            <textarea name="preview" rows="5" class="{{ $in }}">{{ old('preview', $product->preview) }}</textarea>
        </div>
        <label class="flex items-center gap-2"><input type="checkbox" name="featured" value="1"
                @checked(old('featured', $product->featured)) class="rounded border-ink/30"> Tampilkan di Rekomendasi homepage</label>
        <div class="flex gap-3"><button
                class="rounded-full bg-ink px-7 py-3 font-semibold text-paper hover:bg-forest">Simpan</button><a
                href="{{ route('admin.products.index') }}" class="rounded-full border border-ink/20 px-7 py-3">Batal</a>
        </div>
    </form>
@endsection
