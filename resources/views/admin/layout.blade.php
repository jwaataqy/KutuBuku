<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Admin Kutubuku</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:wght@500;600;700&display=swap"
        rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        serif: ['"Playfair Display"', 'serif'],
                        sans: ['Inter', 'sans-serif']
                    },
                    colors: {
                        ink: '#1c1a17',
                        paper: '#faf7f2',
                        gold: '#b08d57',
                        forest: '#2f4a3c'
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-paper font-sans text-ink antialiased">
    @php
        $nav = [
            ['admin.dashboard', 'Dashboard', 'admin.dashboard'],
            ['admin.products.index', 'Produk', 'admin.products.*'],
            ['admin.categories.index', 'Kategori', 'admin.categories.*'],
            ['admin.orders.index', 'Pesanan', 'admin.orders.*'],
            ['admin.vouchers.index', 'Voucher', 'admin.vouchers.*'],
            ['admin.reviews.index', 'Ulasan', 'admin.reviews.*'],
            ['admin.users.index', 'Pengguna', 'admin.users.*'],
        ];
    @endphp
    <div class="md:flex">
        <aside class="sticky top-0 hidden h-screen w-60 shrink-0 flex-col bg-ink p-6 text-paper md:flex">
            <a href="{{ route('admin.dashboard') }}" class="font-serif text-2xl font-bold">Kutu<span
                    class="text-gold">buku</span></a>
            <p class="mb-8 text-[10px] uppercase tracking-[.3em] text-gold">Admin Panel</p>
            <nav class="space-y-1 text-sm">
                @foreach ($nav as [$route, $label, $match])
                    <a href="{{ route($route) }}"
                        class="block rounded-md px-3 py-2 {{ request()->routeIs($match) ? 'bg-gold font-semibold text-ink' : 'text-paper/70 hover:bg-white/10' }}">{{ $label }}</a>
                @endforeach
            </nav>
            <div class="mt-auto space-y-2 text-sm">
                <a href="{{ route('home') }}" class="block text-paper/60 hover:text-gold">← Lihat Website</a>
                <form method="POST" action="{{ route('logout') }}">@csrf<button
                        class="text-paper/60 hover:text-gold">Keluar</button></form>
            </div>
        </aside>
        <div class="min-w-0 flex-1">
            <nav class="flex gap-5 overflow-x-auto bg-ink px-4 py-3 text-sm text-paper md:hidden">
                @foreach ($nav as [$route, $label, $match])
                    <a href="{{ route($route) }}"
                        class="whitespace-nowrap {{ request()->routeIs($match) ? 'text-gold' : '' }}">{{ $label }}</a>
                @endforeach
            </nav>
            <main class="mx-auto max-w-6xl p-6 md:p-10">
                <h1 class="mb-8 font-serif text-3xl font-semibold">@yield('heading')</h1>
                @if (session('ok'))
                    <div class="mb-6 rounded-md bg-forest px-4 py-3 text-sm text-white">{{ session('ok') }}</div>
                @endif
                @if ($errors->any())
                    <div class="mb-6 rounded-md bg-red-50 px-4 py-3 text-sm text-red-700">
                        @foreach ($errors->all() as $e)
                            <p>{{ $e }}</p>
                        @endforeach
                    </div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>
    @stack('scripts')
</body>

</html>
