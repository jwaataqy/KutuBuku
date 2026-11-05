@extends('admin.layout')
@section('title', 'Voucher')
@section('heading', 'Voucher Diskon')
@section('content')
    <form method="POST" action="{{ route('admin.vouchers.store') }}" class="mb-6 flex max-w-lg gap-2">@csrf
        <input name="code" placeholder="KODE" required
            class="flex-1 rounded-md border border-ink/15 bg-white px-3 py-2 text-sm uppercase">
        <input type="number" name="percent" min="1" max="100" placeholder="Diskon %" required
            class="w-28 rounded-md border border-ink/15 bg-white px-3 py-2 text-sm">
        <button class="rounded-md bg-ink px-5 text-sm text-paper">Buat</button>
    </form>
    <div class="divide-y divide-ink/10 rounded-lg border border-ink/10 bg-white text-sm">
        @forelse($vouchers as $v)
            <div class="flex items-center gap-4 p-4">
                <span class="rounded border border-gold px-2 py-1 font-semibold text-gold">{{ $v->code }}</span>
                <span class="flex-1">Diskon {{ $v->percent }}%</span>
                <form method="POST" action="{{ route('admin.vouchers.update', $v) }}">@csrf @method('PUT')<button
                        class="rounded-full px-3 py-1 text-xs {{ $v->active ? 'bg-forest/10 text-forest' : 'bg-ink/10 text-ink/60' }}">{{ $v->active ? 'Aktif' : 'Nonaktif' }}</button>
                </form>
                <form method="POST" action="{{ route('admin.vouchers.destroy', $v) }}"
                    onsubmit="return confirm('Hapus voucher?')">@csrf @method('DELETE')<button
                        class="text-red-600">Hapus</button></form>
            </div>
        @empty<p class="p-10 text-center text-ink/50">Belum ada voucher.</p>
        @endforelse
    </div>
@endsection
