@extends('layouts.app')

@section('content')
<div class="bg-white min-h-screen font-sans">
    
    {{-- Header Judul Halaman --}}
    <div class="pt-12 pb-6 md:pt-16 md:pb-8">
        <h1 class="text-2xl md:text-3xl font-light tracking-[0.3em] uppercase text-center text-gray-900">Products</h1>
    </div>

    {{-- Filter Section Responsif --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-10 md:mb-12">
        <div class="border-t border-b border-gray-100 py-4 md:py-6">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 max-w-3xl mx-auto">
                
                {{-- Category Filter --}}
                <div class="flex flex-col gap-1.5">
                    <span class="text-[9px] sm:text-[10px] font-bold uppercase tracking-widest text-gray-400">Product type</span>
                    <select onchange="applyFilter('category', this.value)" class="w-full border border-gray-200 text-[11px] uppercase tracking-widest px-3 py-2.5 bg-white focus:border-black outline-none transition cursor-pointer">
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->slug }}" {{ request('category') == $cat->slug ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Availability Filter --}}
                <div class="flex flex-col gap-1.5">
                    <span class="text-[9px] sm:text-[10px] font-bold uppercase tracking-widest text-gray-400">Availability</span>
                    <select onchange="applyFilter('availability', this.value)" class="w-full border border-gray-200 text-[11px] uppercase tracking-widest px-3 py-2.5 bg-white focus:border-black outline-none transition cursor-pointer">
                        <option value="">All Stock</option>
                        <option value="in_stock" {{ request('availability') == 'in_stock' ? 'selected' : '' }}>In Stock</option>
                        <option value="out_of_stock" {{ request('availability') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                    </select>
                </div>

                {{-- Sort Filter --}}
                <div class="flex flex-col gap-1.5">
                    <span class="text-[9px] sm:text-[10px] font-bold uppercase tracking-widest text-gray-400">Sort by</span>
                    <select onchange="applyFilter('sort', this.value)" class="w-full border border-gray-200 text-[11px] uppercase tracking-widest px-3 py-2.5 bg-white focus:border-black outline-none transition cursor-pointer">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Date, new to old</option>
                        <option value="alphabet-asc" {{ request('sort') == 'alphabet-asc' ? 'selected' : '' }}>Alphabetically, A-Z</option>
                        <option value="alphabet-desc" {{ request('sort') == 'alphabet-desc' ? 'selected' : '' }}>Alphabetically, Z-A</option>
                        <option value="price-asc" {{ request('sort') == 'price-asc' ? 'selected' : '' }}>Price, low to high</option>
                        <option value="price-desc" {{ request('sort') == 'price-desc' ? 'selected' : '' }}>Price, high to low</option>
                    </select>
                </div>
                
            </div>
        </div>
    </div>

    {{-- Product Grid Responsif --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-x-4 gap-y-10 md:gap-y-12">
            @forelse($products as $product)
            <div class="group">
                {{-- Image Container --}}
                <a href="{{ route('product.show', $product->id) }}" class="relative block aspect-[3/4] overflow-hidden bg-[#f9f9f9]">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" 
                             alt="{{ $product->name }}" 
                             class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-[10px] text-gray-400 uppercase tracking-wider">No Image</div>
                    @endif
                    
                    @php $totalStock = $product->variants->sum('stock'); @endphp
                    
                    @if($totalStock <= 0)
                    <div class="absolute top-3 left-3 bg-black/70 px-2.5 py-1 backdrop-blur-[2px]">
                        <span class="text-[8px] sm:text-[9px] text-white uppercase tracking-[0.2em] font-medium">Sold Out</span>
                    </div>
                    @endif
                </a>

                {{-- Product Info --}}
                <div class="mt-4 sm:mt-5 text-center px-1">
                    <h2 class="text-[10px] sm:text-[11px] uppercase tracking-[0.15em] text-gray-900 font-medium leading-relaxed group-hover:text-gray-500 transition truncate">
                        <a href="{{ route('product.show', $product->id) }}">{{ $product->name }}</a>
                    </h2>
                    <p class="mt-1.5 text-[10px] sm:text-[11px] tracking-wider text-gray-500">
                        IDR {{ number_format($product->price, 0, ',', '.') }}
                    </p>
                </div>
            </div>
            @empty
            <div class="col-span-full py-24 text-center">
                <p class="text-[11px] uppercase tracking-[0.3em] text-gray-400">No products available.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<script>
    function applyFilter(key, value) {
        let url = new URL(window.location.href);
        if (value) {
            url.searchParams.set(key, value);
        } else {
            url.searchParams.delete(key);
        }
        window.location.href = url.href;
    }
</script>

<style>
    body { background-color: #ffffff; color: #1a1a1a; }
    select {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%239ca3af' stroke-linecap='round' stroke-linejoin='round' stroke-width='1' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 0.75rem center;
        background-repeat: no-repeat;
        background-size: 1.2em 1.2em;
        padding-right: 2rem;
        -webkit-appearance: none; appearance: none;
    }
</style>
@endsection