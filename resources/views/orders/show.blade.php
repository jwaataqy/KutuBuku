@extends('layout')
@section('title', $order->invoice)
@section('content')
    <div class="mx-auto max-w-2xl px-4 py-12">
        <div class="rounded-lg border border-ink/10 bg-white p-8">
            <p class="text-xs uppercase tracking-widest text-gold">{{ $order->invoice }}</p>
            <h1 class="mt-1 font-serif text-3xl font-semibold">
                {{ ['paid' => 'Pembayaran Berhasil', 'pending' => 'Menunggu Pembayaran', 'expired' => 'Kedaluwarsa', 'failed' => 'Pembayaran Gagal'][$order->status] ?? $order->status }}
            </h1>
            <div class="mt-6 divide-y divide-ink/10 text-sm">
                @foreach ($order->items as $i)
                    <div class="flex justify-between py-3"><span>{{ $i->product->title }}</span><span><x-price
                                :value="$i->price" /></span></div>
                @endforeach
                @if ($order->discount)
                    <div class="flex justify-between py-3 text-forest"><span>Voucher
                            {{ $order->voucher_code }}</span><span>-Rp
                            {{ number_format($order->discount, 0, ',', '.') }}</span></div>
                @endif
                <div class="flex justify-between py-3 font-serif text-lg font-semibold"><span>Total</span><span><x-price
                            :value="$order->total" /></span></div>
            </div>
            @if ($order->status == 'paid')<a href="{{ route('library') }}"
                    class="mt-6 block rounded-full bg-forest py-3 text-center font-semibold text-white">Buka Perpustakaan</a>
            @elseif($order->status == 'pending')
                <button onclick="pay()"
                    class="mt-6 w-full rounded-full bg-gold py-3 font-semibold text-ink hover:bg-ink hover:text-paper">Bayar
                    Sekarang (QRIS / VA / E-Wallet)</button>
                @if (app()->isLocal())
                    <form method="POST" action="{{ route('orders.simulate', $order) }}" class="mt-3">@csrf<button
                            class="w-full rounded-full border border-ink/20 py-2 text-sm">Simulasikan pembayaran berhasil
                            (lokal)</button></form>
                @endif
            @endif
        </div>
    </div>
@endsection
@if ($order->status == 'pending' && $order->snap_token)
    @push('scripts')
        <script
            src="{{ config('midtrans.production') ? 'https://app.midtrans.com' : 'https://app.sandbox.midtrans.com' }}/snap/snap.js"
            data-client-key="{{ config('midtrans.client_key') }}"></script>
        <script>
            function pay() {
                snap.pay('{{ $order->snap_token }}', {
                    onSuccess: () => location.reload(),
                    onPending: () => location.reload()
                })
            }
        </script>
    @endpush
@endif
