@extends('admin.layout')
@section('title', $order->invoice)
@section('heading', 'Detail Pesanan')
@section('content')
    <div class="max-w-2xl rounded-lg border border-ink/10 bg-white p-8 text-sm">
        <p class="text-xs uppercase tracking-widest text-gold">{{ $order->invoice }}</p>
        <p class="mt-1">{{ $order->user->name }} · {{ $order->user->email }}</p>
        <p class="text-ink/50">Dibuat {{ $order->created_at->format('d M Y H:i') }}@if ($order->paid_at)
                · Dibayar {{ $order->paid_at->format('d M Y H:i') }}
                @endif @if ($order->payment_method)
                    · via {{ $order->payment_method }}
                @endif
        </p>
        <p class="mt-3">Status: <span class="font-semibold">{{ $order->status }}</span></p>
        <div class="mt-5 divide-y divide-ink/10">
            @foreach ($order->items as $i)
                <div class="flex justify-between py-3"><span>{{ $i->product->title }}</span><span>Rp
                        {{ number_format($i->price, 0, ',', '.') }}</span></div>
            @endforeach
            @if ($order->discount)
                <div class="flex justify-between py-3 text-forest"><span>Voucher {{ $order->voucher_code }}</span><span>-Rp
                        {{ number_format($order->discount, 0, ',', '.') }}</span></div>
            @endif
            <div class="flex justify-between py-3 font-serif text-lg font-semibold"><span>Total</span><span>Rp
                    {{ number_format($order->total, 0, ',', '.') }}</span></div>
        </div>
        @if ($order->status === 'pending')
            <div class="mt-4 flex gap-3">
                <form method="POST" action="{{ route('admin.orders.update', $order) }}"
                    onsubmit="return confirm('Tandai pesanan ini lunas?')">@csrf @method('PUT')<input type="hidden"
                        name="status" value="paid"><button
                        class="rounded-full bg-forest px-6 py-2.5 font-semibold text-white">Tandai Lunas</button></form>
                <form method="POST" action="{{ route('admin.orders.update', $order) }}"
                    onsubmit="return confirm('Batalkan pesanan ini?')">@csrf @method('PUT')<input type="hidden"
                        name="status" value="failed"><button
                        class="rounded-full border border-red-300 px-6 py-2.5 text-red-700">Batalkan</button></form>
            </div>
        @endif
        <a href="{{ route('admin.orders.index') }}" class="mt-6 inline-block text-gold">← Kembali</a>
    </div>
@endsection
