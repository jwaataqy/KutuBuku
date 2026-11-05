@extends('admin.layout')
@section('title', 'Pesanan')
@section('heading', 'Pesanan')
@section('content')
    @php $badge=['paid'=>'bg-forest/10 text-forest','pending'=>'bg-gold/15 text-gold','failed'=>'bg-red-100 text-red-700','expired'=>'bg-ink/10 text-ink/60']; @endphp
    <form class="mb-6 flex flex-wrap gap-2 text-sm">
        <input name="q" value="{{ request('q') }}" placeholder="Cari invoice / nama / email…"
            class="w-64 rounded-md border border-ink/15 bg-white px-3 py-2">
        <select name="status" class="rounded-md border border-ink/15 bg-white px-3 py-2">
            <option value="">Semua status</option>
            @foreach (['paid', 'pending', 'failed', 'expired'] as $s)
                <option @selected(request('status') == $s)>{{ $s }}</option>
            @endforeach
        </select>
        <button class="rounded-md bg-ink px-4 text-paper">Filter</button>
    </form>
    <div class="overflow-x-auto rounded-lg border border-ink/10 bg-white">
        <table class="w-full text-left text-sm">
            <thead class="border-b border-ink/10 text-xs uppercase tracking-widest text-ink/50">
                <tr>
                    <th class="p-4">Invoice</th>
                    <th>Pembeli</th>
                    <th>Item</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-ink/10">
                @forelse($orders as $o)
                    <tr>
                        <td class="p-4"><a href="{{ route('admin.orders.show', $o) }}"
                                class="font-medium hover:text-gold">{{ $o->invoice }}</a></td>
                        <td>{{ $o->user->name }}<p class="text-xs text-ink/50">{{ $o->user->email }}</p>
                        </td>
                        <td>{{ $o->items_count }}</td>
                        <td>Rp {{ number_format($o->total, 0, ',', '.') }}</td>
                        <td><span
                                class="rounded px-2 py-0.5 text-xs {{ $badge[$o->status] ?? '' }}">{{ $o->status }}</span>
                        </td>
                        <td class="text-ink/50">{{ $o->created_at->format('d M Y H:i') }}</td>
                    </tr>
                @empty<tr>
                        <td colspan="6" class="p-10 text-center text-ink/50">Tidak ada pesanan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $orders->links() }}</div>
@endsection
