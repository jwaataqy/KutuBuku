@extends('admin.layout')
@section('title', 'Kategori')
@section('heading', 'Kategori')
@section('content')
    <form method="POST" action="{{ route('admin.categories.store') }}" class="mb-6 flex max-w-md gap-2">@csrf
        <input name="name" placeholder="Nama kategori baru" required
            class="flex-1 rounded-md border border-ink/15 bg-white px-3 py-2 text-sm"><button
            class="rounded-md bg-ink px-5 text-sm text-paper">Tambah</button>
    </form>
    <div class="divide-y divide-ink/10 rounded-lg border border-ink/10 bg-white">
        @foreach ($categories as $c)
            <div class="flex flex-wrap items-center gap-3 p-4 text-sm">
                <form method="POST" action="{{ route('admin.categories.update', $c) }}" class="flex flex-1 gap-2">@csrf
                    @method('PUT')
                    <input name="name" value="{{ $c->name }}"
                        class="min-w-0 flex-1 rounded border border-ink/15 px-3 py-2"><button
                        class="text-gold">Simpan</button>
                </form>
                <span class="text-ink/50">{{ $c->products_count }} produk</span>
                <form method="POST" action="{{ route('admin.categories.destroy', $c) }}"
                    onsubmit="return confirm('Hapus kategori ini?')">@csrf @method('DELETE')<button
                        class="text-red-600">Hapus</button></form>
            </div>
        @endforeach
    </div>
@endsection
