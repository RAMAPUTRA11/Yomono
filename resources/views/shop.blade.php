@extends('layouts.app')

@section('content')
<div class="bg-white min-h-screen font-sans">
    
    <div class="pt-16 pb-8">
        <h1 class="text-3xl font-light tracking-[0.3em] uppercase text-center text-gray-900">Products</h1>
    </div>

    {{-- Filter Section --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-12">
        <div class="flex flex-wrap justify-center items-center gap-4 border-t border-b border-gray-100 py-6">
            
            {{-- Category Filter --}}
            <div class="flex items-center gap-2">
                <span class="text-[11px] font-bold uppercase tracking-widest text-gray-900">Product type:</span>
                <select onchange="applyFilter('category', this.value)" class="border border-gray-300 text-[11px] uppercase tracking-widest px-4 py-2 focus:ring-0 focus:border-black outline-none min-w-[140px]">
                    <option value="">All</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->slug }}" {{ request('category') == $cat->slug ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Availability Filter --}}
            <div class="flex items-center gap-2">
                <span class="text-[11px] font-bold uppercase tracking-widest text-gray-900">Availability:</span>
                <select onchange="applyFilter('availability', this.value)" class="border border-gray-300 text-[11px] uppercase tracking-widest px-4 py-2 focus:ring-0 focus:border-black outline-none min-w-[140px]">
                    <option value="">All</option>
                    <option value="in_stock" {{ request('availability') == 'in_stock' ? 'selected' : '' }}>In Stock</option>
                    <option value="out_of_stock" {{ request('availability') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                </select>
            </div>

            {{-- Sort Filter --}}
            <div class="flex items-center gap-2">
                <span class="text-[11px] font-bold uppercase tracking-widest text-gray-900">Sort by:</span>
                <select onchange="applyFilter('sort', this.value)" class="border border-gray-300 text-[11px] uppercase tracking-widest px-4 py-2 focus:ring-0 focus:border-black outline-none min-w-[160px]">
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Date, new to old</option>
                    <option value="alphabet-asc" {{ request('sort') == 'alphabet-asc' ? 'selected' : '' }}>Alphabetically, A-Z</option>
                    <option value="alphabet-desc" {{ request('sort') == 'alphabet-desc' ? 'selected' : '' }}>Alphabetically, Z-A</option>
                    <option value="price-asc" {{ request('sort') == 'price-asc' ? 'selected' : '' }}>Price, low to high</option>
                    <option value="price-desc" {{ request('sort') == 'price-desc' ? 'selected' : '' }}>Price, high to low</option>
                </select>
            </div>
        </div>
    </div>

    {{-- Product Grid --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-x-4 gap-y-12">
            @forelse($products as $product)
            <div class="group">
                {{-- Link ke Detail menggunakan ID --}}
                <a href="{{ route('product.show', $product->id) }}" class="relative block aspect-[3/4] overflow-hidden bg-[#f5f5f5]">
                    <img src="{{ asset('storage/' . $product->image) }}" 
                         alt="{{ $product->name }}" 
                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                    
                    @php $totalStock = $product->variants->sum('stock'); @endphp
                    
                    @if($totalStock <= 0)
                    <div class="absolute top-4 left-4 bg-black/40 px-3 py-1">
                        <span class="text-[9px] text-white uppercase tracking-[0.2em]">Sold Out</span>
                    </div>
                    @endif
                </a>

                <div class="mt-6 text-center">
                    <h2 class="text-[10px] uppercase tracking-[0.2em] text-gray-900 font-medium leading-relaxed">
                        <a href="{{ route('product.show', $product->id) }}">{{ $product->name }}</a>
                    </h2>
                    <p class="mt-2 text-[10px] tracking-widest text-gray-500">
                        IDR {{ number_format($product->price, 0, ',', '.') }}
                    </p>
                </div>
            </div>
            @empty
            <div class="col-span-full py-20 text-center">
                <p class="text-[10px] uppercase tracking-[0.3em] text-gray-400">No products available.</p>
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
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 0.5rem center;
        background-repeat: no-repeat;
        background-size: 1.5em 1.5em;
        padding-right: 2.5rem;
        -webkit-appearance: none; appearance: none;
    }
</style>
@endsection