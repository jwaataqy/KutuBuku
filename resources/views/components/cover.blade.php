@props(['product'])
@php $g=[['#2f4a3c','#1c1a17'],['#8a6d3b','#2a2118'],['#4a2f3c','#1c1a17'],['#3b4a5c','#171c22'],['#5c4a2f','#1c1a17'],['#2f3f4a','#1a1d1f']][$product->id%6]; @endphp
<div {{ $attributes->merge(['class' => 'relative flex aspect-[3/4] flex-col justify-between overflow-hidden rounded-md p-5 text-paper shadow-lg']) }}
    style="background:linear-gradient(145deg,{{ $g[0] }},{{ $g[1] }})">
    <div class="absolute inset-3 rounded border border-gold/50"></div>
    <p class="relative text-[10px] uppercase tracking-[.3em] text-gold">{{ $product->category->name }}</p>
    <h3 class="relative font-serif text-xl font-bold leading-tight">{{ $product->title }}</h3>
    <p class="relative text-xs text-paper/70">{{ $product->author }}<br><span
            class="text-gold">{{ $product->format }}</span></p>
</div>
