@extends('layouts.app')

@section('content')
<div class="max-w-[1000px] mx-auto px-6 py-20">
    {{-- Header --}}
    <div class="mb-12 text-left border-b border-gray-900 pb-8">
        <h1 class="text-3xl font-light tracking-[0.4em] uppercase mb-4">Returns & Shipping</h1>
        <p class="text-[11px] text-gray-400 uppercase tracking-widest font-medium">Customer Service / Policy</p>
    </div>

    {{-- Banner Image --}}
    <div class="mb-16 w-full overflow-hidden rounded-sm bg-gray-100 shadow-sm">
        <img src="https://cf.shopee.co.id/file/id-11134210-7rasi-m3nv87bo4t25fe" 
             alt="Shipping Banner" 
             class="w-full h-auto object-cover grayscale hover:grayscale-0 transition duration-700">
    </div>
    {{-- Info Box --}}
    <div class="mt-20 border border-gray-100 p-12 text-center bg-gray-50/50">
        <h4 class="text-[13px] font-bold uppercase tracking-[0.3em] mb-6">Need more info?</h4>
        <div class="flex flex-col md:flex-row justify-center gap-8 md:gap-16">
            <div class="text-[11px] uppercase tracking-widest text-gray-500">
                Email: <span class="text-black font-medium">support@yomono.id</span>
            </div>
            <div class="text-[11px] uppercase tracking-widest text-gray-500">
                WhatsApp: 
                <a href="https://wa.me/6285311111010" target="_blank" class="text-black font-medium hover:underline">
                    +62 853-1111-1010
                </a>
            </div>
        </div>
    </div>
</div>
@endsection