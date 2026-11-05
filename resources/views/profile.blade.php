@extends('layout')
@section('title', 'Profil')
@section('content')
    <div class="mx-auto max-w-4xl px-4 py-12" x-data="{ t: 'profil' }">
        <h1 class="font-serif text-3xl font-semibold">Profil Saya</h1>
        <div class="mt-6 flex gap-8 border-b border-ink/10 text-sm">
            @foreach (['profil' => 'Profil', 'password' => 'Password', 'wishlist' => 'Wishlist'] as $k => $l)
                <button @click="t='{{ $k }}'"
                    :class="t == '{{ $k }}' ? 'border-gold' : 'border-transparent text-ink/50'"
                    class="border-b-2 pb-3">{{ $l }}</button>
            @endforeach
            <a href="{{ route('orders.index') }}" class="pb-3 text-ink/50">
                Riwayat Transaksi →</a>
        </div>
        <form x-show="t=='profil'" method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data"
            class="mt-8 max-w-md space-y-4 text-sm">@csrf @method('PUT')
            <div class="flex items-center gap-4"><span
                    class="grid h-16 w-16 place-items-center overflow-hidden rounded-full bg-forest text-xl text-white">
                    @if ($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}"
                            class="h-full w-full object-cover">@else{{ strtoupper(substr($user->name, 0, 1)) }}
                    @endif
                </span>
                <input type="file" name="avatar" accept="image/*">
            </div>
            <input name="name" value="{{ old('name', $user->name) }}"
                class="w-full rounded border border-ink/15 bg-white px-3 py-2"><input name="email"
                value="{{ old('email', $user->email) }}" class="w-full rounded border border-ink/15 bg-white px-3 py-2">
            @foreach (['name', 'email', 'avatar'] as $f)
                @error($f)
                    <p class="text-red-700">{{ $message }}</p>
                @enderror
            @endforeach
            <button class="rounded-full bg-ink px-6 py-2.5 text-paper hover:bg-forest">Simpan</button>
        </form>
        <form x-show="t=='password'" x-cloak method="POST" action="{{ route('profile.password') }}"
            class="mt-8 max-w-md space-y-4 text-sm">@csrf @method('PUT')
            <input type="password" name="current" placeholder="Password lama"
                class="w-full rounded border border-ink/15 bg-white px-3 py-2"><input type="password" name="password"
                placeholder="Password baru" class="w-full rounded border border-ink/15 bg-white px-3 py-2"><input
                type="password" name="password_confirmation" placeholder="Ulangi password baru"
                class="w-full rounded border border-ink/15 bg-white px-3 py-2">
            @foreach (['current', 'password'] as $f)
                @error($f)
                    <p class="text-red-700">{{ $message }}</p>
                @enderror
            @endforeach
            <button class="rounded-full bg-ink px-6 py-2.5 text-paper hover:bg-forest">Ubah Password</button>
        </form>
        <div x-show="t=='wishlist'" x-cloak class="mt-8 grid grid-cols-2 gap-6 md:grid-cols-4">
            @forelse($wishlist as $p)
            <x-product-card :product="$p" />@empty<p class="col-span-4 text-ink/50">Belum ada buku favorit.</p>
            @endforelse
        </div>
    </div>
@endsection
