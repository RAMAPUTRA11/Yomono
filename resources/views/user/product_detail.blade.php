@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

{{-- Pastikan variable $product dikirim dari ProductController @show method --}}
<div class="bg-white min-h-screen pb-20" x-data="{ 
    mainImage: '{{ asset('storage/' . $product->image) }}',
    selectedColor: null,
    selectedSize: null,
    variants: {{ $product->variants->map(function($v) {
        return [
            'id' => $v->id,
            'color_id' => $v->color_id,
            'color_name' => $v->color->name,
            'size_id' => $v->size_id,
            'size_name' => $v->size->name,
            'stock' => $v->stock,
            'image' => asset('storage/' . $v->image)
        ];
    })->toJson() }}
}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-10">
        <div class="flex flex-col lg:flex-row gap-12">
            
            {{-- Media Section --}}
            <div class="w-full lg:w-3/5">
                <div class="sticky top-24">
                    <div class="aspect-[3/4] overflow-hidden bg-gray-100 rounded-sm shadow-sm">
                        <img :src="mainImage" class="w-full h-full object-cover transition-all duration-500 ease-in-out">
                    </div>
                    {{-- Thumbnail Gallery --}}
                    <div class="flex gap-4 mt-4 overflow-x-auto pb-2">
                        <img src="{{ asset('storage/' . $product->image) }}" 
                             @click="mainImage = $el.src"
                             class="w-20 h-24 object-cover cursor-pointer border border-transparent hover:border-black transition">
                        
                        @foreach($product->variants->unique('color_id') as $variant)
                            @if($variant->image)
                            <img src="{{ asset('storage/' . $variant->image) }}" 
                                 @click="mainImage = $el.src; selectedColor = {{ $variant->color_id }}"
                                 class="w-20 h-24 object-cover cursor-pointer border border-transparent hover:border-black transition">
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Info Section --}}
            <div class="w-full lg:w-2/5 font-sans">
                <nav class="text-[10px] uppercase tracking-widest text-gray-400 mb-4">
                    <a href="/">Home</a> / <a href="/shop">Collections</a> / {{ $product->category->name ?? 'Uncategorized' }}
                </nav>

                <h1 class="text-2xl font-medium tracking-wider text-gray-900 uppercase">{{ $product->name }}</h1>
                <p class="text-lg mt-2 text-gray-600 font-light">IDR {{ number_format($product->price, 0, ',', '.') }}</p>

                <form action="{{ route('cart.add') }}" method="POST" class="mt-10">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    {{-- Color Selection --}}
                    <div class="mb-8">
                        <label class="text-[11px] font-bold uppercase tracking-[0.2em] block mb-4 text-gray-900">
                            Color: <span x-text="variants.find(v => v.color_id === selectedColor)?.color_name || 'Select Color'" class="font-light text-gray-500"></span>
                        </label>
                        <div class="flex flex-wrap gap-2">
                            @foreach($product->variants->unique('color_id') as $variant)
                            <label class="relative cursor-pointer">
                                <input type="radio" name="color_id" value="{{ $variant->color_id }}" class="peer sr-only" required
                                       @click="selectedColor = {{ $variant->color_id }}; mainImage = '{{ asset('storage/' . $variant->image) }}'; selectedSize = null">
                                <span class="px-4 py-2 border border-gray-200 text-[11px] uppercase tracking-widest peer-checked:bg-black peer-checked:text-white transition-all block">
                                    {{ $variant->color->name }}
                                </span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Size Selection (Hanya muncul jika warna sudah dipilih) --}}
                    <div class="mb-8" x-show="selectedColor">
                        <label class="text-[11px] font-bold uppercase tracking-[0.2em] block mb-4 text-gray-900">Size</label>
                        <div class="flex flex-wrap gap-2">
                            <template x-for="v in variants.filter(v => v.color_id === selectedColor)">
                                <label class="relative cursor-pointer">
                                    <input type="radio" name="variant_id" :value="v.id" class="peer sr-only" required
                                           @click="selectedSize = v.size_id" :disabled="v.stock <= 0">
                                    <span class="w-12 h-10 border border-gray-200 text-[11px] flex items-center justify-center peer-checked:bg-black peer-checked:text-white transition-all block uppercase"
                                          :class="v.stock <= 0 ? 'opacity-30 cursor-not-allowed bg-gray-100' : ''"
                                          x-text="v.size_name">
                                    </span>
                                </label>
                            </template>
                        </div>
                    </div>

                    {{-- Stock Info --}}
                    <div class="mb-4" x-show="selectedColor && selectedSize">
                        <p class="text-[10px] uppercase tracking-widest text-gray-500">
                            Stock: <span x-text="variants.find(v => v.color_id === selectedColor && v.size_id === selectedSize)?.stock"></span> items left
                        </p>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex flex-col gap-4 mt-10">
                        <div class="flex border border-gray-300 w-32 items-center">
                            <button type="button" @click="$refs.qty.value > 1 ? $refs.qty.value-- : ''" class="w-10 h-10 flex items-center justify-center">-</button>
                            <input type="number" x-ref="qty" name="quantity" value="1" min="1" class="w-12 text-center border-none focus:ring-0 text-sm">
                            <button type="button" @click="$refs.qty.value++" class="w-10 h-10 flex items-center justify-center">+</button>
                        </div>

                        <button type="submit" :disabled="!selectedSize" 
                                class="w-full bg-white border border-black text-black py-4 text-[11px] uppercase tracking-[0.3em] font-bold hover:bg-black hover:text-white transition-all duration-300 disabled:opacity-50">
                            Add to Cart
                        </button>
                        <button type="submit" name="buy_now" value="1" :disabled="!selectedSize"
                                class="w-full bg-black text-white py-4 text-[11px] uppercase tracking-[0.3em] font-bold hover:bg-gray-800 transition-all duration-300 disabled:opacity-50">
                            Buy It Now
                        </button>
                    </div>
                </form>

                <div class="mt-12 pt-12 border-t border-gray-100">
                    <h3 class="text-[11px] font-bold uppercase tracking-[0.2em] mb-4">Product Description</h3>
                    <div class="text-xs text-gray-600 leading-relaxed space-y-4 font-light tracking-wide">
                        {!! nl2br(e($product->description)) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection