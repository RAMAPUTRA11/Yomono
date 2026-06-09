@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

<div class="bg-white overflow-hidden">
    {{-- 1. HERO SECTION --}}
    <section class="relative h-[60vh] sm:h-[75vh] lg:h-[85vh] w-full overflow-hidden">
        <img src="/storage/aset/aset1.png" 
             class="absolute inset-0 w-full h-full object-cover object-top" alt="Hero Image">
        <div class="absolute inset-0 bg-black bg-opacity-20 flex items-center justify-center">
            <div class="text-center text-white px-4">
                <h2 class="text-[10px] sm:text-xs uppercase tracking-[0.4em] sm:tracking-[0.6em] mb-3 drop-shadow-md">New Arrivals</h2>
                <h1 class="text-2xl sm:text-4xl md:text-6xl font-light tracking-tight mb-6 md:mb-8 drop-shadow-lg">COMFORT IN STYLE</h1>
            </div>
        </div>
    </section>

    {{-- 2. CURATED FOR YOU SECTION (SLIDER PRODUK) --}}
    <section class="py-12 sm:py-16 lg:py-20 px-4 max-w-[1440px] mx-auto relative">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-end mb-8 md:mb-10 px-2 sm:px-4 gap-4">
            <div>
                <h3 class="text-lg sm:text-xl font-light tracking-[0.2em] uppercase">Pilihan Buat Kamu</h3>
                <p class="text-[9px] sm:text-[10px] text-gray-400 uppercase tracking-widest mt-1 sm:mt-2">Our latest essential pieces</p>
            </div>
            <div class="flex justify-between sm:justify-end gap-6 items-center border-t sm:border-t-0 border-gray-100 pt-3 sm:pt-0">
                <a href="/shop" class="text-[11px] sm:text-xs uppercase border-b border-black pb-1 hover:text-gray-500 hover:border-gray-500 transition tracking-widest">View All</a>
                {{-- Navigasi Slider Kustom --}}
                <div class="flex gap-2">
                    <div class="swiper-button-prev-custom cursor-pointer hover:opacity-50 transition p-1">
                        <svg width="20" height="20" sm:width="24" sm:height="24" viewBox="0 0 24 24" fill="none" stroke="black" stroke-width="1"><path d="M15 18l-6-6 6-6"/></svg>
                    </div>
                    <div class="swiper-button-next-custom cursor-pointer hover:opacity-50 transition p-1">
                        <svg width="20" height="20" sm:width="24" sm:height="24" viewBox="0 0 24 24" fill="none" stroke="black" stroke-width="1"><path d="M9 18l6-6-6-6"/></svg>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="swiper productSwiper px-2 sm:px-4">
            <div class="swiper-wrapper">
                @foreach($featuredProducts as $product)
                <div class="swiper-slide group">
                    <a href="{{ route('product.show', $product->slug ?? $product->id) }}">
                        <div class="relative aspect-[3/4] overflow-hidden bg-[#f9f9f9] mb-3 md:mb-4">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" 
                                     class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" alt="{{ $product->name }}">
                            @type
                                <div class="w-full h-full flex items-center justify-center text-[9px] text-gray-400 uppercase">No Image</div>
                            @endif

                            @if($product->variants->sum('stock') <= 0)
                                <div class="absolute top-3 left-3 sm:top-4 sm:left-4 bg-black text-white text-[8px] sm:text-[9px] px-2 sm:px-3 py-1 tracking-widest">SOLD OUT</div>
                            @else
                                <div class="absolute top-3 left-3 sm:top-4 sm:left-4 bg-white text-black text-[8px] sm:text-[9px] px-2 sm:px-3 py-1 tracking-widest shadow-sm">NEW IN</div>
                            @endif
                        </div>
                        <div class="text-left space-y-0.5 sm:space-y-1">
                            <h4 class="text-[10px] sm:text-[11px] uppercase tracking-[0.12em] sm:tracking-[0.15em] text-gray-800 font-medium group-hover:text-gray-500 transition truncate">{{ $product->name }}</h4>
                            <p class="text-[10px] sm:text-[11px] text-gray-500 tracking-wider">IDR {{ number_format($product->price, 0, ',', '.') }}</p>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- 3. AUTO BANNER SWIPER --}}
    <section class="w-full relative mt-4 md:mt-12">
        <div class="swiper autoBannerSwiper">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <img src="/storage/aset/slide1.png" class="w-full h-auto block" alt="Banner 1"> 
                </div>
                <div class="swiper-slide">
                    <img src="/storage/aset/slide2.png" class="w-full h-auto block" alt="Banner 2">
                </div>
            </div>
        </div>
    </section>

    {{-- 4. CATEGORY GRID --}}
    <section class="max-w-7xl mx-auto px-4 py-14 sm:py-20">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
            <a href="/shop?category=tops" class="relative aspect-[4/5] group overflow-hidden">
                <img src="/storage/aset/frame1.jpg" class="w-full h-full object-cover group-hover:scale-110 transition duration-1000" alt="Kategori Tops">
                <div class="absolute inset-0 bg-black/10 flex items-center justify-center">
                    <span class="bg-white text-black px-6 sm:px-8 py-2.5 sm:py-3 text-[11px] sm:text-xs uppercase tracking-[0.2em] sm:tracking-[0.3em] font-medium group-hover:bg-black group-hover:text-white transition">Tops</span>
                </div>
            </a>
            <a href="/shop?category=pants" class="relative aspect-[4/5] group overflow-hidden">
                <img src="/storage/aset/frame2.jpeg" class="w-full h-full object-cover group-hover:scale-110 transition duration-1000" alt="Kategori Pants">
                <div class="absolute inset-0 bg-black/10 flex items-center justify-center">
                    <span class="bg-white text-black px-6 sm:px-8 py-2.5 sm:py-3 text-[11px] sm:text-xs uppercase tracking-[0.2em] sm:tracking-[0.3em] font-medium group-hover:bg-black group-hover:text-white transition">Pants</span>
                </div>
            </a>
            <a href="/shop?category=outerwear" class="relative aspect-[4/5] sm:col-span-2 lg:col-span-1 group overflow-hidden">
                <img src="/storage/aset/frame3.png" class="w-full h-full object-cover group-hover:scale-110 transition duration-1000" alt="Kategori Outerwear">
                <div class="absolute inset-0 bg-black/10 flex items-center justify-center">
                    <span class="bg-white text-black px-6 sm:px-8 py-2.5 sm:py-3 text-[11px] sm:text-xs uppercase tracking-[0.2em] sm:tracking-[0.3em] font-medium group-hover:bg-black group-hover:text-white transition">Outerwear</span>
                </div>
            </a>
        </div>
    </section>

    {{-- 5. RETURN POLICY IMAGE --}}
    <section class="w-full bg-gray-50 border-t border-gray-100">
        <div class="max-w-5xl mx-auto py-8 sm:py-12 px-4">
            <img src="https://down-aka-id.img.susercontent.com/id-11134210-7rasi-m3nv87bo4t25fe.webp" class="w-full h-auto object-contain" alt="Return Policy">
        </div>
    </section>
</div>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    // Slider Produk Responsif Berdasarkan Breakpoint Layar
    new Swiper(".productSwiper", {
        slidesPerView: 1.3,
        spaceBetween: 12,
        navigation: {
            nextEl: ".swiper-button-next-custom",
            prevEl: ".swiper-button-prev-custom",
        },
        breakpoints: {
            480: { slidesPerView: 2.2, spaceBetween: 16 },
            768: { slidesPerView: 3.2, spaceBetween: 20 },
            1024: { slidesPerView: 4, spaceBetween: 30 }
        }
    });

    // Banner Auto fading
    new Swiper(".autoBannerSwiper", {
        loop: true,
        effect: "fade",
        autoplay: {
            delay: 4000,
            disableOnInteraction: false,
        },
    });
</script>

<style>
    .swiper-button-disabled {
        opacity: 0.2;
        cursor: auto;
    }
</style>
@endsection