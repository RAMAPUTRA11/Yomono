@extends('layouts.app')

@section('content')
<div class="max-w-[1000px] mx-auto px-6 py-24">
    <div class="mb-20">
        <h1 class="text-3xl font-light tracking-[0.4em] uppercase mb-4 text-gray-900">Visit Our Stores</h1>
        <div class="h-[1px] w-12 bg-black mb-6"></div>
        <p class="text-[11px] text-gray-400 uppercase tracking-[0.2em]">Experience our collection in person</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-16">
        {{-- Store 1 --}}
        <div class="space-y-4">
            <h3 class="text-[14px] font-bold uppercase tracking-[0.2em] text-gray-900">YOMONO Flagship Store - Jakarta</h3>
            <div class="text-[12px] text-gray-500 leading-relaxed uppercase tracking-wider space-y-2">
                <p>Jl. Senopati No. 123, Kebayoran Baru<br>Jakarta Selatan, 12190</p>
                <p>Monday - Sunday / 10:00 - 21:00</p>
                <a href="https://maps.google.com" target="_blank" class="inline-block border-b border-black pb-1 mt-2 text-black hover:text-gray-400 transition">Get Directions</a>
            </div>
        </div>

        {{-- Store 2 --}}
        <div class="space-y-4">
            <h3 class="text-[14px] font-bold uppercase tracking-[0.2em] text-gray-900">YOMONO Studio - Bandung</h3>
            <div class="text-[12px] text-gray-500 leading-relaxed uppercase tracking-wider space-y-2">
                <p>Jl. LLRE Martadinata No. 45<br>Bandung, 40115</p>
                <p>Monday - Sunday / 09:00 - 20:00</p>
                <a href="https://maps.google.com" target="_blank" class="inline-block border-b border-black pb-1 mt-2 text-black hover:text-gray-400 transition">Get Directions</a>
            </div>
        </div>
    </div>
</div>
@endsection