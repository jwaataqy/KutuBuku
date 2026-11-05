@extends('layout')
@section('title', 'Katalog')
@section('content')
    <div class="mx-auto grid max-w-6xl gap-10 px-4 py-12 md:grid-cols-[240px_1fr]">
        <form method="GET" class="space-y-5 text-sm">
            <h1 class="font-serif text-2xl font-semibold">Katalog</h1>
            <input name="q" value="{{ request('q') }}" placeholder="Cari judul…"
                class="w-full rounded-md border border-ink/15 bg-white px-3 py-2">
            <div><label class="mb-1 block text-xs uppercase tracking-widest text-gold">Kategori</label>
                <select name="category" class="w-full rounded-md border border-ink/15 bg-white px-3 py-2">
                    <option value="">Semua</option>
                    @foreach ($categories as $c)
                        <option value="{{ $c->slug }}" @selected(request('category') == $c->slug)>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div><label class="mb-1 block text-xs uppercase tracking-widest text-gold">Harga (Rp)</label>
                <div class="flex gap-2"><input name="min" value="{{ request('min') }}" placeholder="Min"
                        class="w-1/2 rounded-md border border-ink/15 bg-white px-3 py-2"><input name="max"
                        value="{{ request('max') }}" placeholder="Maks"
                        class="w-1/2 rounded-md border border-ink/15 bg-white px-3 py-2"></div>
            </div>
            <div><label class="mb-1 block text-xs uppercase tracking-widest text-gold">Jenis</label>
                <select name="type" class="w-full rounded-md border border-ink/15 bg-white px-3 py-2">
                    <option value="">Semua</option>
                    <option value="free" @selected(request('type') == 'free')>Gratis</option>
                    <option value="paid" @selected(request('type') == 'paid')>Berbayar</option>
                </select>
            </div>
            <div><label class="mb-1 block text-xs uppercase tracking-widest text-gold">Urutkan</label>
                <select name="sort" class="w-full rounded-md border border-ink/15 bg-white px-3 py-2">
                    <option value="">Terbaru</option>
                    <option value="terlaris" @selected(request('sort') == 'terlaris')>Terlaris</option>
                </select>
            </div>
            <button class="w-full rounded-full bg-ink py-2.5 text-paper hover:bg-forest">Terapkan</button>
        </form>
        <div>
            @if ($products->isEmpty())
                <p class="py-20 text-center text-ink/50">Tidak ada buku yang cocok.</p>
            @endif
            <div class="grid grid-cols-2 gap-6 lg:grid-cols-3">
                @foreach ($products as $p)
                    <x-product-card :product="$p" />
                @endforeach
            </div>
            <div class="mt-10">{{ $products->links() }}</div>
        </div>
    </div>
@endsection
