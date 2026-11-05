@props(['product'])
<a href="{{ route('product.show', $product) }}" class="group block">
    <x-cover :product="$product" class="transition duration-300 group-hover:-translate-y-1 group-hover:shadow-2xl" />
    <div class="mt-4">
        <p class="text-[11px] uppercase tracking-widest text-gold">{{ $product->category->name }}</p>
        <h3 class="font-serif text-lg leading-snug">{{ $product->title }}</h3>
        <p class="text-sm text-ink/60">{{ $product->author }}</p>
        <div class="mt-1 flex items-center gap-2 text-sm"><span class="font-semibold"><x-price :value="$product->final_price" /></span>
            @if ($product->on_sale)
                <span class="text-xs text-ink/40 line-through">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
            @endif
            @if ($product->reviews_avg_rating)
                <span class="ml-auto text-xs text-gold">★ {{ number_format($product->reviews_avg_rating, 1) }}</span>
            @endif
        </div>
    </div>
</a>
