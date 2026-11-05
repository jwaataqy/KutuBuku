@extends('layout')
@section('title', 'Perpustakaan Saya')
@section('content')
    <div class="mx-auto max-w-6xl px-4 py-12">
        <h1 class="font-serif text-3xl font-semibold">Perpustakaan Saya</h1>
        <p class="text-ink/60">Akses semua e-book yang sudah kamu miliki, kapan saja.</p>
        @if ($products->isEmpty())
            <p class="py-20 text-center text-ink/50">Belum ada buku. <a class="text-gold" href="{{ route('catalog') }}">Mulai
                    membaca →</a></p>
        @endif
        <div class="mt-8 grid grid-cols-2 gap-8 md:grid-cols-4">
            @foreach ($products as $p)
                <div><x-cover :product="$p" />
                    <p class="mt-3 font-serif">{{ $p->title }}</p><span
                        class="inline-block rounded bg-forest/10 px-2 py-0.5 text-xs text-forest">Akses aktif
                        selamanya</span>
                    <a href="{{ route('library.download', $p) }}"
                        class="mt-2 block rounded-full bg-ink py-2 text-center text-sm text-paper hover:bg-forest">Unduh
                        {{ $p->format }}</a>
                </div>
            @endforeach
        </div>
        <a href="{{ route('orders.index') }}" class="mt-10 inline-block text-sm text-gold">Lihat riwayat pembelian →</a>
    </div>
@endsection
