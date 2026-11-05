@extends('admin.layout')
@section('title', 'Pengguna')
@section('heading', 'Pengguna')
@section('content')
    <form class="mb-6 flex gap-2 text-sm">
        <input name="q" value="{{ request('q') }}" placeholder="Cari nama / email…"
            class="w-64 rounded-md border border-ink/15 bg-white px-3 py-2"><button
            class="rounded-md bg-ink px-4 text-paper">Cari</button>
    </form>
    <div class="overflow-x-auto rounded-lg border border-ink/10 bg-white">
        <table class="w-full text-left text-sm">
            <thead class="border-b border-ink/10 text-xs uppercase tracking-widest text-ink/50">
                <tr>
                    <th class="p-4">Nama</th>
                    <th>Bergabung</th>
                    <th>Pesanan</th>
                    <th>Total Belanja</th>
                    <th>Peran</th>
                    <th></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-ink/10">
                @foreach ($users as $u)
                    <tr>
                        <td class="p-4">
                            <p class="font-medium">{{ $u->name }}</p>
                            <p class="text-xs text-ink/50">{{ $u->email }}</p>
                        </td>
                        <td class="text-ink/50">{{ $u->created_at?->format('d M Y') ?? '-' }}</td>
                        <td>{{ $u->orders_count }}</td>
                        <td>Rp {{ number_format($u->spent ?? 0, 0, ',', '.') }}</td>
                        <td><span
                                class="rounded px-2 py-0.5 text-xs {{ $u->is_admin ? 'bg-gold/15 text-gold' : 'bg-ink/10 text-ink/60' }}">{{ $u->is_admin ? 'Admin' : 'Pembeli' }}</span>
                        </td>
                        <td class="pr-4 text-right">
                            @if ($u->id !== auth()->id())
                                <form method="POST" action="{{ route('admin.users.admin', $u) }}"
                                    onsubmit="return confirm('Ubah akses pengguna ini?')">@csrf @method('PUT')<button
                                        class="text-gold">{{ $u->is_admin ? 'Cabut admin' : 'Jadikan admin' }}</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $users->links() }}</div>
@endsection
