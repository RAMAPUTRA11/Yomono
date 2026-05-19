@extends('layouts.app')

@section('content')
<div class="bg-white min-h-screen pb-20" x-data="{ 
    mainImage: '{{ asset('storage/' . $product->image) }}',
    selectedColor: null,
    selectedSize: null,
    {{-- Ambil data unik per warna untuk mapping gambar --}}
    colorImages: {
        @foreach($product->variants->unique('color_id') as $v)
            '{{ $v->color_id }}': '{{ asset('storage/' . $v->image) }}',
        @endforeach
    },
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
                    <div class="aspect-[3/4] overflow-hidden bg-gray-100 rounded-sm shadow-sm border border-gray-50">
                        {{-- Tambahkan :key agar Alpine merender ulang saat image berubah --}}
                        <img :src="mainImage" :key="mainImage" class="w-full h-full object-cover transition-opacity duration-500">
                    </div>
                    
                    {{-- Thumbnails --}}
                    <div class="flex gap-4 mt-4 overflow-x-auto pb-2 scrollbar-hide">
                        <img src="{{ asset('storage/' . $product->image) }}" 
                             @click="mainImage = $el.src; selectedColor = null"
                             :class="mainImage === $el.src ? 'border-black' : 'border-transparent'"
                             class="w-20 h-24 object-cover cursor-pointer border hover:border-black transition">
                        
                        @foreach($product->variants->unique('color_id') as $variant)
                            @if($variant->image)
                            <img src="{{ asset('storage/' . $variant->image) }}" 
                                 @click="mainImage = $el.src; selectedColor = {{ $variant->color_id }}"
                                 :class="mainImage === $el.src ? 'border-black' : 'border-transparent'"
                                 class="w-20 h-24 object-cover cursor-pointer border hover:border-black transition">
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
                    
                    {{-- Color Selection --}}
                    <div class="mb-8">
                        <label class="text-[11px] font-bold uppercase tracking-[0.2em] block mb-4 text-gray-900">
                            Color: <span x-text="variants.find(v => v.color_id === selectedColor)?.color_name || 'Select Color'" class="font-light text-gray-500"></span>
                        </label>
                        <div class="flex flex-wrap gap-2">
                            @foreach($product->variants->unique('color_id') as $variant)
                            <label class="relative cursor-pointer">
                                {{-- Saat radio diklik, ganti mainImage berdasarkan colorImages map --}}
                                <input type="radio" name="color_id" value="{{ $variant->color_id }}" class="peer sr-only" required
                                       @click="selectedColor = {{ $variant->color_id }}; mainImage = colorImages[{{ $variant->color_id }}]; selectedSize = null">
                                <span class="px-4 py-2 border border-gray-200 text-[11px] uppercase tracking-widest peer-checked:bg-black peer-checked:text-white transition-all block hover:border-black">
                                    {{ $variant->color->name }}
                                </span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Size Selection --}}
                    <div class="mb-8" x-show="selectedColor" x-transition>
                        <label class="text-[11px] font-bold uppercase tracking-[0.2em] block mb-4 text-gray-900">Size</label>
                        <div class="flex flex-wrap gap-2">
                            <template x-for="v in variants.filter(v => v.color_id === selectedColor)" :key="v.id">
                                <label class="relative cursor-pointer">
                                    <input type="radio" name="variant_id" :value="v.id" class="peer sr-only" required
                                           @click="selectedSize = v.id" :disabled="v.stock <= 0">
                                    <span class="w-12 h-10 border border-gray-200 text-[11px] flex items-center justify-center peer-checked:bg-black peer-checked:text-white transition-all block uppercase"
                                          :class="v.stock <= 0 ? 'opacity-30 cursor-not-allowed bg-gray-100' : 'hover:border-black'"
                                          x-text="v.size_name">
                                    </span>
                                </label>
                            </template>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex flex-col gap-4 mt-10">
                        <div class="flex border border-gray-300 w-32 items-center">
                            <button type="button" @click="if($refs.qty.value > 1) $refs.qty.value--" class="w-10 h-10 flex items-center justify-center hover:bg-gray-50">-</button>
                            <input type="number" x-ref="qty" name="quantity" value="1" min="1" class="w-12 text-center border-none focus:ring-0 text-sm" readonly>
                            <button type="button" @click="$refs.qty.value++" class="w-10 h-10 flex items-center justify-center hover:bg-gray-50">+</button>
                        </div>

                        <div class="flex flex-col gap-3">
                            <button type="submit" :disabled="!selectedSize" 
                                    class="w-full bg-white border border-black text-black py-4 text-[11px] uppercase tracking-[0.3em] font-bold hover:bg-black hover:text-white transition-all duration-300 disabled:opacity-30 disabled:cursor-not-allowed">
                                Add to Cart
                            </button>
                        </div>
                    </div>
                </form>

                <div class="mt-12 pt-12 border-t border-gray-100">
                    <h3 class="text-[11px] font-bold uppercase tracking-[0.2em] mb-4 text-black">Product Description</h3>
                    <div class="text-[12px] text-gray-500 leading-relaxed space-y-4 font-light tracking-wide">
                        {!! nl2br(e($product->description)) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection