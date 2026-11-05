<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Kutubuku') — Toko E-Book Digital</title>
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
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
</head>

<body class="bg-paper text-ink font-sans antialiased">
    <header class="sticky top-0 z-40 border-b border-ink/10 bg-paper/90 backdrop-blur" x-data="{ menu: false }">
        <div class="mx-auto flex max-w-6xl items-center gap-6 px-4 py-4">
            <a href="{{ route('home') }}" class="font-serif text-2xl font-bold tracking-tight">Kutu<span
                    class="text-gold">buku</span></a>
            <nav class="hidden gap-6 text-sm md:flex"><a href="{{ route('catalog') }}"
                    class="hover:text-gold">Katalog</a><a href="{{ route('library') }}"
                    class="hover:text-gold">Perpustakaan Saya</a></nav>
            <form action="{{ route('catalog') }}" class="ml-auto hidden md:block"><input name="q"
                    value="{{ request('q') }}" placeholder="Cari judul buku…"
                    class="w-64 rounded-full border border-ink/15 bg-white px-4 py-2 text-sm focus:border-gold focus:outline-none">
            </form>
            <a href="{{ route('cart') }}" class="relative text-sm font-medium hover:text-gold">Keranjang @if (count(session('cart', [])))
                    <span
                        class="ml-1 rounded-full bg-gold px-2 py-0.5 text-xs text-white">{{ count(session('cart')) }}</span>
                @endif
            </a>
            @auth
                <div class="relative" x-data="{ open: false }" @click.outside="open = false">

                    {{-- Tombol Avatar --}}
                    <button type="button" @click="open = !open" class="flex items-center gap-2 text-sm focus:outline-none">
                        <span
                            class="grid h-8 w-8 place-items-center overflow-hidden rounded-full bg-forest text-xs text-white">
                            @if (auth()->user()->avatar)
                                <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="Avatar"
                                    class="h-full w-full object-cover">
                            @else
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            @endif
                        </span>
                    </button>

                    {{-- Dropdown Menu --}}
                    <div x-show="open" x-cloak x-transition
                        class="absolute right-0 z-50 mt-2 w-48 rounded-lg border border-ink/10 bg-white py-2 text-sm shadow-xl">

                        {{-- Dashboard Admin --}}
                        @if (auth()->user()->is_admin)
                            <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 hover:bg-paper">
                                Dashboard Admin
                            </a>

                            <div class="my-1 border-t border-ink/10"></div>
                        @endif

                        {{-- Profil --}}
                        <a href="{{ route('profile') }}" class="block px-4 py-2 hover:bg-paper">
                            Profil
                        </a>

                        {{-- Riwayat Transaksi --}}
                        <a href="{{ route('orders.index') }}" class="block px-4 py-2 hover:bg-paper">
                            Riwayat Transaksi
                        </a>

                        {{-- Logout --}}
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <button type="submit" class="w-full px-4 py-2 text-left hover:bg-paper">
                                Keluar
                            </button>
                        </form>

                    </div>
                </div>
            @else
                <a href="/masuk"
                class="rounded-full bg-ink px-4 py-2 text-sm text-paper hover:bg-forest">Masuk</a> @endauth
        </div>
    </header>
    @if (session('ok'))
        <div class="bg-forest py-2 text-center text-sm text-white">{{ session('ok') }}</div>
    @endif
    <main>@yield('content')</main>
    <footer class="mt-24 border-t border-ink/10 py-10 text-center text-sm text-ink/60">
        <p class="font-serif text-lg text-ink">Kutubuku</p>
        <p>Baca lebih banyak, di mana saja. © {{ date('Y') }}</p>
    </footer>
    @stack('scripts')
</body>

</html>
