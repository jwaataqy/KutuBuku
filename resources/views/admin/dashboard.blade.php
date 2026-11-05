@extends('admin.layout')
@section('title', 'Dashboard')
@section('heading', 'Dashboard')
@section('content')
    @php
        $rp = fn($n) => 'Rp ' . number_format($n, 0, ',', '.');
        $badge = [
            'paid' => 'bg-forest/10 text-forest',
            'pending' => 'bg-gold/15 text-gold',
            'failed' => 'bg-red-100 text-red-700',
            'expired' => 'bg-ink/10 text-ink/60',
        ];
        $trend =
            $growth === null
                ? 'Belum ada pembanding'
                : ($growth >= 0 ? '▲ ' : '▼ ') . abs($growth) . '% dari bulan lalu';
        $cards = [
            ['Total Pendapatan', $rp($revenue), $paidCount . ' pesanan lunas'],
            ['Pendapatan Bulan Ini', $rp($thisMonth), $trend],
            ['Menunggu Pembayaran', $pending, 'pesanan pending'],
            ['Pengguna', $users, 'akun terdaftar'],
        ];
        $mini = [['Produk', $products], ['E-book Terjual', $sold], ['Ulasan', $reviews]];
    @endphp
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @foreach ($cards as [$l, $v, $s])
            <div class="rounded-lg border border-ink/10 bg-white p-5">
                <p class="text-xs uppercase tracking-widest text-gold">{{ $l }}</p>
                <p class="mt-2 font-serif text-2xl font-semibold">{{ $v }}</p>
                <p class="mt-1 text-xs text-ink/50">{{ $s }}</p>
            </div>
        @endforeach
    </div>
    <div class="mt-4 grid gap-4 sm:grid-cols-3">
        @foreach ($mini as [$l, $v])
            <div class="rounded-lg border border-ink/10 bg-white p-4 text-sm"><span
                    class="text-ink/60">{{ $l }}</span><span
                    class="float-right font-semibold">{{ number_format($v, 0, ',', '.') }}</span></div>
        @endforeach
    </div>
    <div class="mt-8 grid gap-6 lg:grid-cols-3">
        <div class="rounded-lg border border-ink/10 bg-white p-6 lg:col-span-2">
            <h2 class="font-serif text-lg font-semibold">Penjualan 7 Hari Terakhir</h2>
            <div class="mt-4 h-64"><canvas id="sales"></canvas></div>
        </div>
        <div class="rounded-lg border border-ink/10 bg-white p-6">
            <h2 class="font-serif text-lg font-semibold">Buku Terlaris</h2>
            <ol class="mt-4 space-y-3 text-sm">
                @foreach ($top as $i => $p)
                    <li class="flex gap-3"><span class="w-4 font-serif text-gold">{{ $i + 1 }}</span><span
                            class="flex-1">{{ $p->title }}</span><span class="text-ink/50">{{ $p->sold }}x</span>
                    </li>
                @endforeach
            </ol>
        </div>
    </div>
    <div class="mt-8 overflow-x-auto rounded-lg border border-ink/10 bg-white">
        <div class="flex items-center justify-between p-5">
            <h2 class="font-serif text-lg font-semibold">Pesanan Terbaru</h2><a href="{{ route('admin.orders.index') }}"
                class="text-sm text-gold">Lihat semua →</a>
        </div>
        <table class="w-full text-left text-sm">
            <tbody class="divide-y divide-ink/10">
                @forelse($recent as $o)
                    <tr>
                        <td class="px-5 py-3"><a class="font-medium hover:text-gold"
                                href="{{ route('admin.orders.show', $o) }}">{{ $o->invoice }}</a></td>
                        <td>{{ $o->user->name }}</td>
                        <td>{{ $rp($o->total) }}</td>
                        <td><span
                                class="rounded px-2 py-0.5 text-xs {{ $badge[$o->status] ?? '' }}">{{ $o->status }}</span>
                        </td>
                        <td class="pr-5 text-right text-ink/50">{{ $o->created_at->format('d M Y') }}</td>
                    </tr>
                @empty<tr>
                        <td class="p-8 text-center text-ink/50">Belum ada pesanan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
    <script>
        const d = @json($chart);
        new Chart(document.getElementById('sales'), {
            type: 'bar',
            data: {
                labels: d.labels,
                datasets: [{
                        type: 'bar',
                        label: 'Pendapatan (Rp)',
                        data: d.revenue,
                        backgroundColor: '#b08d57',
                        borderRadius: 4,
                        yAxisID: 'y'
                    },
                    {
                        type: 'line',
                        label: 'Pesanan',
                        data: d.orders,
                        borderColor: '#2f4a3c',
                        backgroundColor: '#2f4a3c',
                        tension: .3,
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    },
                    y1: {
                        beginAtZero: true,
                        position: 'right',
                        grid: {
                            drawOnChartArea: false
                        },
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    </script>
@endpush
