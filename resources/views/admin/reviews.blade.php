@extends('admin.layout')
@section('title', 'Ulasan')
@section('heading', 'Ulasan Pembeli')
@section('content')
    <div class="divide-y divide-ink/10 rounded-lg border border-ink/10 bg-white text-sm">
        @forelse($reviews as $r)
            <div class="flex gap-4 p-5">
                <div class="flex-1">
                    <p class="font-medium">{{ $r->user->name }} <span
                            class="text-gold">{{ str_repeat('★', $r->rating) }}</span></p>
                    <p class="text-xs text-ink/50">{{ $r->product->title }} · {{ $r->created_at->format('d M Y') }}</p>
                    <p class="mt-2 text-ink/80">{{ $r->body }}</p>
                </div>
                <form method="POST" action="{{ route('admin.reviews.destroy', $r) }}"
                    onsubmit="return confirm('Hapus ulasan ini?')">@csrf @method('DELETE')<button
                        class="text-red-600">Hapus</button></form>
            </div>
        @empty<p class="p-10 text-center text-ink/50">Belum ada ulasan.</p>
        @endforelse
    </div>
    <div class="mt-6">{{ $reviews->links() }}</div>
@endsection
