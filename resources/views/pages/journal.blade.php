@extends('layouts.app')

@section('content')
<div class="max-w-[1200px] mx-auto px-6 py-24">
    <div class="mb-16 border-b border-gray-100 pb-10">
        <h1 class="text-3xl font-light tracking-[0.4em] uppercase mb-4 text-gray-900">Journal #RE-YOMONO</h1>
        <p class="text-[11px] text-gray-400 uppercase tracking-[0.2em]">Stories, Inspirations, and Behind the Scenes</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-16">
        {{-- Journal 1 --}}
        <article class="group cursor-pointer">
            <div class="aspect-video bg-gray-100 overflow-hidden mb-6">
                {{-- Ganti src dengan image journal kamu --}}
                <div class="w-full h-full bg-gray-200 group-hover:scale-105 transition duration-700"></div>
            </div>
            <p class="text-[10px] text-gray-400 uppercase tracking-widest mb-2">May 14, 2026</p>
            <h2 class="text-lg font-medium uppercase tracking-wider mb-4 group-hover:text-gray-500 transition">The Art of Minimalist Dressing</h2>
            <p class="text-[12px] text-gray-500 leading-relaxed uppercase tracking-wider line-clamp-2">
                Discover how we define essential pieces for your daily wardrobe...
            </p>
        </article>

        {{-- Journal 2 --}}
        <article class="group cursor-pointer">
            <div class="aspect-video bg-gray-100 overflow-hidden mb-6">
                <div class="w-full h-full bg-gray-200 group-hover:scale-105 transition duration-700"></div>
            </div>
            <p class="text-[10px] text-gray-400 uppercase tracking-widest mb-2">April 20, 2026</p>
            <h2 class="text-lg font-medium uppercase tracking-wider mb-4 group-hover:text-gray-500 transition">Inside Our Production: Overlock 2.0</h2>
            <p class="text-[12px] text-gray-500 leading-relaxed uppercase tracking-wider line-clamp-2">
                Take a closer look at the craftsmanship behind our latest collection...
            </p>
        </article>
    </div>
</div>
@endsection