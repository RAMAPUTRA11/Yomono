@extends('layouts.app')

@section('content')
<div class="max-w-[1000px] mx-auto px-6 py-24">
    <div class="text-left mb-16">
        <h1 class="text-2xl font-light tracking-[0.4em] uppercase mb-2">Your Cart</h1>
        <p class="text-[11px] text-gray-400 uppercase tracking-widest">(0 Items)</p>
    </div>

    {{-- State: Empty Cart --}}
    <div class="py-20 text-center border-t border-b border-gray-100">
        <p class="text-[12px] text-gray-500 uppercase tracking-[0.2em] mb-10">Your cart is currently empty.</p>
        <a href="{{ url('/shop') }}" class="inline-block bg-black text-white px-12 py-4 text-[10px] uppercase tracking-[0.3em] hover:bg-gray-800 transition">Continue Shopping</a>
    </div>
</div>
@endsection