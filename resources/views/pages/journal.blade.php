@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-6 py-24">
    <div class="mb-20 border-b border-gray-100 pb-10">
        <h1 class="text-2xl font-light tracking-[0.4em] uppercase mb-4 text-gray-900">Journal #RE-YOMONO</h1>
        <p class="text-[11px] text-gray-400 uppercase tracking-[0.2em]">Stories, Inspirations, and Behind the Scenes</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-20">
        {{-- Journal 1 --}}
        <article class="group cursor-pointer">
            <div class="aspect-[4/3] bg-gray-50 overflow-hidden mb-6">
                {{-- Placeholder visual abu-abu minimalis, ganti dengan tag <img> asli jika aset siap --}}
                <div class="w-full h-full bg-gray-100 group-hover:scale-[1.03] transition duration-700"></div>
            </div>
            <p class="text-[10px] text-gray-400 uppercase tracking-widest mb-3">May 14, 2026</p>
            <h2 class="text-[14px] font-bold uppercase tracking-[0.15em] text-gray-900 mb-3 group-hover:text-gray-500 transition">
                The Art of Minimalist Dressing
            </h2>
            <p class="text-[12px] text-gray-500 leading-relaxed tracking-wide line-clamp-2">
                Discover how we define essential pieces for your daily wardrobe. Balancing simplicity, silhouettes, and versatile tones for the modern lifestyle.
            </p>
        </article>

        {{-- Journal 2 --}}
        <article class="group cursor-pointer">
            <div class="aspect-[4/3] bg-gray-50 overflow-hidden mb-6">
                <div class="w-full h-full bg-gray-100 group-hover:scale-[1.03] transition duration-700"></div>
            </div>
            <p class="text-[10px] text-gray-400 uppercase tracking-widest mb-3">April 20, 2026</p>
            <h2 class="text-[14px] font-bold uppercase tracking-[0.15em] text-gray-900 mb-3 group-hover:text-gray-500 transition">
                Inside Our Production: Overlock 2.0
            </h2>
            <p class="text-[12px] text-gray-500 leading-relaxed tracking-wide line-clamp-2">
                Take a closer look at the high-standard craftsmanship and durable stitching methods behind our latest ready-to-wear drops.
            </p>
        </article>
    </div>
</div>
@endsection