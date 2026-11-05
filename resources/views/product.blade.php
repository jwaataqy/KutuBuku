@extends('layout')
@section('title', $product->title)
@section('content')
    <div class="mx-auto max-w-6xl px-4 py-12">
        <div class="grid gap-12 md:grid-cols-[320px_1fr]">
            <x-cover :product="$product" class="mx-auto w-full max-w-xs" />
            <div>
                <p class="text-xs uppercase tracking-widest text-gold">{{ $product->category->name }}</p>
                <h1 class="mt-2 font-serif text-4xl font-bold">{{ $product->title }}</h1>
                <p class="mt-1 text-ink/60">oleh {{ $product->author }} · <span class="text-gold">★
                        {{ number_format($product->reviews_avg_rating ?? 0, 1) }}</span> ({{ $product->reviews->count() }}
                    ulasan) · {{ $product->sold }} terjual</p>
                <div class="mt-6 flex items-baseline gap-3"><span class="font-serif text-3xl font-semibold"><x-price
                            :value="$product->final_price" /></span>
                    @if ($product->on_sale)
                        <span class="text-ink/40 line-through">Rp {{ number_format($product->price, 0, ',', '.') }}</span><span
                            class="rounded bg-gold/15 px-2 py-0.5 text-xs font-semibold text-gold">-{{ round(100 - ($product->discount_price / $product->price) * 100) }}%</span>
                    @endif
                </div>
                <p class="mt-2 text-sm text-ink/60">Format file: <span
                        class="font-medium text-ink">{{ $product->format }}</span> · Akses selamanya di perpustakaanmu</p>
                <div class="mt-8 flex flex-wrap gap-3">
                    @if ($owned)
                        <a href="{{ route('library') }}"
                            class="rounded-full bg-forest px-7 py-3 text-sm font-semibold text-white">Buka di
                            Perpustakaan</a>
                    @else
                        <form method="POST" action="{{ route('cart.add', $product) }}">@csrf<input type="hidden"
                                name="buy" value="1"><button
                                class="rounded-full bg-ink px-7 py-3 text-sm font-semibold text-paper hover:bg-forest">Beli
                                Sekarang</button></form>
                        <form method="POST" action="{{ route('cart.add', $product) }}">@csrf<button
                                class="rounded-full border border-ink px-7 py-3 text-sm font-semibold hover:bg-ink hover:text-paper">+
                                Keranjang</button></form>
                    @endif
                    @auth<form method="POST" action="{{ route('wishlist', $product) }}">@csrf<button
                                class="rounded-full border border-ink/20 px-5 py-3 text-sm hover:border-gold">{{ $wished ? '♥ Favorit' : '♡ Favorit' }}</button>
                    </form>@endauth
                </div>
            </div>
        </div>
        <div class="mt-16" x-data="{ t: 'desc' }">
            <div class="flex gap-8 border-b border-ink/10 text-sm">
                @foreach (['desc' => 'Deskripsi', 'prev' => 'Preview Isi', 'rev' => 'Ulasan'] as $k => $l)
                    <button @click="t='{{ $k }}'"
                        :class="t == '{{ $k }}' ? 'border-gold text-ink' : 'border-transparent text-ink/50'"
                        class="border-b-2 pb-3">{{ $l }}</button>
                @endforeach
            </div>
            <div class="max-w-3xl py-8">
                <p x-show="t=='desc'" class="leading-relaxed text-ink/80">{{ $product->description }}</p>
                <div x-show="t=='prev'" x-cloak
                    class="whitespace-pre-line rounded-md border border-ink/10 bg-white p-6 font-serif leading-relaxed">
                    {{ $product->preview }}</div>
                <div x-show="t=='rev'" x-cloak class="space-y-6">
                    @forelse($product->reviews as $rv)
                        <div>
                            <p class="text-sm font-semibold">{{ $rv->user->name }} <span
                                    class="ml-2 text-gold">{{ str_repeat('★', $rv->rating) }}</span></p>
                            <p class="text-ink/70">{{ $rv->body }}</p>
                    </div>@empty<p class="text-ink/50">Belum ada ulasan.</p>
                    @endforelse
                    @if ($owned)
                        <form method="POST" action="{{ route('review', $product) }}"
                            class="space-y-3 rounded-md border border-ink/10 bg-white p-5">@csrf
                            <select name="rating" class="rounded border border-ink/15 px-3 py-2 text-sm">
                                @for ($i = 5; $i >= 1; $i--)
                                    <option value="{{ $i }}">{{ str_repeat('★', $i) }}</option>
                                @endfor
                            </select>
                            <textarea name="body" rows="3" required placeholder="Tulis ulasanmu…"
                                class="w-full rounded border border-ink/15 px-3 py-2 text-sm"></textarea>
                            <button class="rounded-full bg-ink px-5 py-2 text-sm text-paper">Kirim Ulasan</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
        @if ($related->count())
            <h2 class="mt-8 font-serif text-2xl font-semibold">Buku Serupa</h2>
            <div class="mt-6 grid grid-cols-2 gap-6 md:grid-cols-4">
                @foreach ($related as $p)
                    <x-product-card :product="$p" />
                @endforeach
            </div>
        @endif
    </div>
@endsection
