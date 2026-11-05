@extends('layout')
@section('title', 'Keranjang')
@section('content')
    <div class="mx-auto max-w-5xl px-4 py-12">
        <h1 class="font-serif text-3xl font-semibold">Keranjang</h1>
        @if ($items->isEmpty())
            <p class="py-20 text-center text-ink/50">Keranjangmu masih kosong. <a class="text-gold"
                    href="{{ route('catalog') }}">Cari buku →</a></p>
        @else<div class="mt-8 grid gap-10 md:grid-cols-[1fr_320px]">
                <div class="divide-y divide-ink/10">
                    @foreach ($items as $p)
                        <div class="flex items-center gap-4 py-4"><x-cover :product="$p"
                                class="w-16 !p-2 [&_h3]:text-[8px] [&_p]:hidden" />
                            <div class="flex-1"><a href="{{ route('product.show', $p) }}"
                                    class="font-serif text-lg">{{ $p->title }}</a>
                                <p class="text-sm text-ink/60">{{ $p->author }} · {{ $p->format }}</p>
                            </div>
                            <span class="font-semibold"><x-price :value="$p->final_price" /></span>
                            <form method="POST" action="{{ route('cart.remove', $p) }}">@csrf @method('DELETE')<button
                                    class="text-sm text-ink/40 hover:text-red-700">Hapus</button></form>
                        </div>
                    @endforeach
                </div>
                <aside class="h-fit space-y-4 rounded-lg border border-ink/10 bg-white p-6 text-sm">
                    <form method="POST" action="{{ route('cart.voucher') }}" class="flex gap-2">@csrf<input name="code"
                            placeholder="Kode voucher"
                            class="w-full rounded border border-ink/15 px-3 py-2 uppercase"><button
                            class="rounded bg-ink px-4 text-paper">Pakai</button></form>
                    @error('code')
                        <p class="text-red-700">{{ $message }}</p>
                    @enderror
                    <div class="flex justify-between"><span>Subtotal</span><span>Rp
                            {{ number_format($subtotal, 0, ',', '.') }}</span></div>
                    @if ($voucher)
                        <div class="flex justify-between text-forest"><span>Voucher {{ $voucher->code }}</span><span>-Rp
                                {{ number_format($discount, 0, ',', '.') }}</span></div>
                    @endif
                    <div class="flex justify-between border-t border-ink/10 pt-4 font-serif text-xl font-semibold">
                        <span>Total</span><span><x-price :value="$total" /></span></div>
                    @auth<form method="POST" action="{{ route('checkout') }}">@csrf<button
                                class="w-full rounded-full bg-gold py-3 font-semibold text-ink hover:bg-ink hover:text-paper">Checkout</button>
                        </form>
                    @else<a href="{{ route('login') }}"
                        class="block rounded-full bg-gold py-3 text-center font-semibold">Masuk untuk Checkout</a>@endauth
                </aside>
            </div>
        @endif
    </div>
@endsection
