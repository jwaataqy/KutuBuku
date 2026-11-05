@extends('layout')
@section('content')
    <section class="bg-ink text-paper">
        <div class="mx-auto grid max-w-6xl items-center gap-10 px-4 py-20 md:grid-cols-2">
            <div>
                <p class="text-xs uppercase tracking-[.3em] text-gold">Promo Akhir Tahun</p>
                <h1 class="mt-4 font-serif text-5xl font-bold leading-tight">Setiap halaman,<br>sebuah perjalanan.</h1>
                <p class="mt-5 max-w-md text-paper/70">Ratusan e-book pilihan dalam format PDF dan EPUB. Bayar dengan QRIS,
                    Virtual Account, atau e-wallet — langsung baca.</p>
                <p class="mt-4 text-sm">Pakai kode <span
                        class="rounded border border-gold px-2 py-1 font-semibold text-gold">HEMAT10</span> untuk diskon 10%.
                </p>
                <a href="{{ route('catalog') }}"
                    class="mt-8 inline-block rounded-full bg-gold px-7 py-3 text-sm font-semibold text-ink hover:bg-paper">Jelajahi
                    Katalog</a>
            </div>
            <div class="hidden justify-center gap-4 md:flex">
                @foreach ($latest->take(3) as $i => $p)
                    <x-cover :product="$p" class="w-40 {{ $i == 1 ? '-translate-y-6' : 'translate-y-4' }}" />
                @endforeach
            </div>
        </div>
    </section>
    <section class="mx-auto max-w-6xl px-4 pt-14">
        <h2 class="font-serif text-2xl font-semibold">Kategori</h2>
        <div class="mt-4 flex flex-wrap gap-3">
            @foreach ($categories as $c)
                <a href="{{ route('catalog', ['category' => $c->slug]) }}"
                    class="rounded-full border border-ink/15 bg-white px-5 py-2 text-sm hover:border-gold hover:text-gold">{{ $c->name }}
                    <span class="text-ink/40">{{ $c->products_count }}</span></a>
            @endforeach
        </div>
    </section>
    @foreach ([['Produk Terbaru', $latest], ['Terlaris', $bestsellers], ['Promo & Diskon', $promo], ['Rekomendasi untuk Kamu', $recommended]] as [$title, $list])
        <section class="mx-auto max-w-6xl px-4 pt-16">
            <div class="flex items-end justify-between">
                <h2 class="font-serif text-2xl font-semibold">{{ $title }}</h2><a href="{{ route('catalog') }}"
                    class="text-sm text-gold">Lihat semua →</a>
            </div>
            <div class="mt-6 grid grid-cols-2 gap-6 md:grid-cols-4">
                @foreach ($list as $p)
                    <x-product-card :product="$p" />
                @endforeach
            </div>
        </section>
    @endforeach
@endsection
