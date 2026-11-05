@extends('layout')
@section('title','Riwayat Transaksi')
@section('content')
<div class="mx-auto max-w-4xl px-4 py-12">
    <h1 class="font-serif text-3xl font-semibold">Riwayat Transaksi</h1>
    <div class="mt-8 divide-y divide-ink/10 rounded-lg border border-ink/10 bg-white">@forelse($orders as $o)
        <a href="{{ route('orders.show',$o) }}" class="flex items-center justify-between p-5 hover:bg-paper">
            <div>
                <p class="font-semibold">{{ $o->invoice }}</p>
                <p class="text-sm text-ink/60">{{ $o->created_at->translatedFormat('d M Y') }} · {{ $o->items_count }} item</p>
            </div>
            <div class="text-right">
                <p><x-price :value="$o->total" /></p><span class="text-xs uppercase tracking-widest {{ $o->status=='paid'?'text-forest':'text-gold' }}">{{ $o->status }}</span>
            </div>
        </a>
        @empty<p class="p-10 text-center text-ink/50">Belum ada transaksi.</p>@endforelse
    </div>
</div>
@endsection