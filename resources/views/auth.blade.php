@extends('layout')
@section('title', $mode == 'login' ? 'Masuk' : 'Daftar')
@section('content')
    <div class="mx-auto max-w-sm px-4 py-20">
        <h1 class="font-serif text-3xl font-semibold">{{ $mode == 'login' ? 'Selamat datang kembali' : 'Buat akun Kutubuku' }}</h1>
        <form method="POST" action="{{ $mode == 'login' ? url('/masuk') : url('/daftar') }}" class="mt-8 space-y-4 text-sm">@csrf
            @if ($mode == 'register')
                <input name="name" value="{{ old('name') }}" placeholder="Nama lengkap" required
                    class="w-full rounded border border-ink/15 bg-white px-3 py-2.5">
            @endif
            <input type="email" name="email" value="{{ old('email') }}" placeholder="Email" required
                class="w-full rounded border border-ink/15 bg-white px-3 py-2.5">
            <input type="password" name="password" placeholder="Password" required
                class="w-full rounded border border-ink/15 bg-white px-3 py-2.5">
            @if ($mode == 'register')
                <input type="password" name="password_confirmation" placeholder="Ulangi password" required
                    class="w-full rounded border border-ink/15 bg-white px-3 py-2.5">
            @endif
            @foreach ($errors->all() as $e)
                <p class="text-red-700">{{ $e }}</p>
            @endforeach
            <button
                class="w-full rounded-full bg-ink py-3 font-semibold text-paper hover:bg-forest">{{ $mode == 'login' ? 'Masuk' : 'Daftar' }}</button>
        </form>
        <p class="mt-6 text-center text-sm text-ink/60">{!! $mode == 'login'
            ? 'Belum punya akun? <a class="text-gold" href="' . route('register') . '">Daftar</a>'
            : 'Sudah punya akun? <a class="text-gold" href="' . route('login') . '">Masuk</a>' !!}</p>
    </div>
@endsection
