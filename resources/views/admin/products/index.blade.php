@extends('admin.layout')
@section('title', 'Produk')
@section('heading', 'Produk')
@section('content')
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <form class="flex gap-2"><input name="q" value="{{ request('q') }}" placeholder="Cari judul…"
                class="rounded-md border border-ink/15 bg-white px-3 py-2 text-sm"><button
                class="rounded-md bg-ink px-4 text-sm text-paper">Cari</button></form>
        <a href="{{ route('admin.products.create') }}"
            class="rounded-full bg-gold px-5 py-2 text-sm font-semibold text-ink hover:bg-ink hover:text-paper">+ Tambah
            Produk</a>
    </div>
    <div class="overflow-x-auto rounded-lg border border-ink/10 bg-white">
        <table class="w-full text-left text-sm">
            <thead class="border-b border-ink/10 text-xs uppercase tracking-widest text-ink/50">
                <tr>
                    <th class="p-4">Judul</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Format</th>
                    <th>Terjual</th>
                    <th></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-ink/10">
                @forelse($products as $p)
                    <tr>
                        <td class="p-4">
                            <p class="font-medium">{{ $p->title }} @if ($p->featured)
                                    <span class="text-gold">★</span>
                                @endif
                            </p>
                            <p class="text-xs text-ink/50">{{ $p->author }}</p>
                        </td>
                        <td>{{ $p->category->name }}</td>
                        <td>
                            @if ($p->final_price == 0)
                                Gratis
                            @else
                                Rp {{ number_format($p->final_price, 0, ',', '.') }}
                                @endif @if ($p->on_sale)
                                    <span
                                        class="text-xs text-ink/40 line-through">{{ number_format($p->price, 0, ',', '.') }}</span>
                                @endif
                        </td>
                        <td>{{ $p->format }}</td>
                        <td>{{ $p->sold }}</td>
                        <td class="space-x-3 whitespace-nowrap pr-4 text-right">
                            <a href="{{ route('admin.products.edit', $p) }}" class="text-gold">Edit</a>
                            <form method="POST" action="{{ route('admin.products.destroy', $p) }}" class="inline"
                                onsubmit="return confirm('Hapus produk ini?')">@csrf @method('DELETE')<button
                                    class="text-red-600">Hapus</button></form>
                        </td>
                    </tr>
                @empty<tr>
                        <td colspan="6" class="p-10 text-center text-ink/50">Belum ada produk.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $products->links() }}</div>
@endsection
