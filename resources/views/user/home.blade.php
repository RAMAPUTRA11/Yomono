@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

<div class="bg-white overflow-hidden">
    {{-- 1. HERO SECTION (ASLI KAMU) --}}
    <section class="relative h-[85vh] w-full overflow-hidden">
        <img src="/storage/aset/aset1.png" 
             class="absolute inset-0 w-full h-full object-cover object-top" alt="Hero Image">
        <div class="absolute inset-0 bg-black bg-opacity-20 flex items-center justify-center">
            <div class="text-center text-white">
                <h2 class="text-xs uppercase tracking-[0.6em] mb-4 drop-shadow-md">New Arrivals</h2>
                <h1 class="text-4xl md:text-6xl font-light tracking-tight mb-8 drop-shadow-lg">COMFORT IN STYLE</h1>
            </div>
        </div>
    </section>

    {{-- 2. CURATED FOR YOU SECTION (SLIDER ALA THENBLANK) --}}
    <section class="py-20 px-4 max-w-[1440px] mx-auto relative">
        <div class="flex justify-between items-end mb-10 px-4">
            <div>
                <h3 class="text-xl font-light tracking-[0.2em] uppercase">Curated For You</h3>
                <p class="text-[10px] text-gray-400 uppercase tracking-widest mt-2">Our latest essential pieces</p>
            </div>
            <div class="flex gap-6 items-center">
                <a href="/shop" class="text-xs uppercase border-b border-black pb-1 hover:text-gray-500 hover:border-gray-500 transition tracking-widest">View All</a>
                {{-- Navigasi Manual --}}
                <div class="flex gap-2">
                    <div class="swiper-button-prev-custom cursor-pointer hover:opacity-50 transition">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="black" stroke-width="1"><path d="M15 18l-6-6 6-6"/></svg>
                    </div>
                    <div class="swiper-button-next-custom cursor-pointer hover:opacity-50 transition">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="black" stroke-width="1"><path d="M9 18l6-6-6-6"/></svg>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="swiper productSwiper px-4">
            <div class="swiper-wrapper">
                @foreach($featuredProducts as $product)
                <div class="swiper-slide group">
                    <a href="{{ route('product.show', $product->slug ?? $product->id) }}">
                        <div class="relative aspect-[3/4] overflow-hidden bg-[#f9f9f9] mb-4">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" 
                                     class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" alt="{{ $product->name }}">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-[10px] text-gray-400 uppercase">No Image</div>
                            @endif

                            @if($product->variants->sum('stock') <= 0)
                                <div class="absolute top-4 left-4 bg-black text-white text-[9px] px-3 py-1 tracking-widest">SOLD OUT</div>
                            @else
                                <div class="absolute top-4 left-4 bg-white text-black text-[9px] px-3 py-1 tracking-widest shadow-sm">NEW IN</div>
                            @endif
                        </div>
                        <div class="text-left space-y-1">
                            <h4 class="text-[11px] uppercase tracking-[0.15em] text-gray-800 font-medium group-hover:text-gray-500 transition">{{ $product->name }}</h4>
                            <p class="text-[11px] text-gray-500 tracking-wider">IDR {{ number_format($product->price, 0, ',', '.') }}</p>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- 3. AUTO BANNER SWIPER (ASLI KAMU) --}}
    <section class="w-full relative mt-12">
        <div class="swiper autoBannerSwiper">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <img src="/storage/aset/slide1.png" class="w-full h-auto block"> 
                </div>
                <div class="swiper-slide">
                    <img src="/storage/aset/slide2.png" class="w-full h-auto block">
                </div>
            </div>
        </div>
    </section>

    {{-- 4. CATEGORY GRID (ASLI KAMU) --}}
    <section class="max-w-7xl mx-auto px-4 py-20">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <a href="/shop?category=tops" class="relative aspect-[4/5] group overflow-hidden">
                <img src="/storage/aset/frame1.jpg" class="w-full h-full object-cover group-hover:scale-110 transition duration-1000">
                <div class="absolute inset-0 bg-black/10 flex items-center justify-center">
                    <span class="bg-white text-black px-8 py-3 text-xs uppercase tracking-[0.3em] font-medium group-hover:bg-black group-hover:text-white transition">Tops</span>
                </div>
            </a>
            <a href="/shop?category=pants" class="relative aspect-[4/5] group overflow-hidden">
                <img src="/storage/aset/frame2.jpeg" class="w-full h-full object-cover group-hover:scale-110 transition duration-1000">
                <div class="absolute inset-0 bg-black/10 flex items-center justify-center">
                    <span class="bg-white text-black px-8 py-3 text-xs uppercase tracking-[0.3em] font-medium group-hover:bg-black group-hover:text-white transition">Pants</span>
                </div>
            </a>
            <a href="/shop?category=outerwear" class="relative aspect-[4/5] group overflow-hidden">
                <img src="/storage/aset/frame3.png" class="w-full h-full object-cover group-hover:scale-110 transition duration-1000">
                <div class="absolute inset-0 bg-black/10 flex items-center justify-center">
                    <span class="bg-white text-black px-8 py-3 text-xs uppercase tracking-[0.3em] font-medium group-hover:bg-black group-hover:text-white transition">Outerwear</span>
                </div>
            </a>
        </div>
    </section>

    {{-- 5. RETURN POLICY IMAGE (ASLI KAMU) --}}
    <section class="w-full bg-gray-50 border-t border-gray-100">
        <div class="max-w-5xl mx-auto py-12 px-4">
            <img src="https://down-aka-id.img.susercontent.com/id-11134210-7rasi-m3nv87bo4t25fe.webp" class="w-full h-auto" alt="Return Policy">
        </div>
    </section>
</div>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    // Slider Produk (Navigasi Kiri-Kanan)
    new Swiper(".productSwiper", {
        slidesPerView: 1.2,
        spaceBetween: 20,
        navigation: {
            nextEl: ".swiper-button-next-custom",
            prevEl: ".swiper-button-prev-custom",
        },
        breakpoints: {
            640: { slidesPerView: 2.2, spaceBetween: 20 },
            1024: { slidesPerView: 4, spaceBetween: 30 }
        }
    });

    // Banner Auto (Asli Kamu)
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
    /* Styling khusus agar navigasi tidak menutupi produk */
    .swiper-button-disabled {
        opacity: 0.2;
        cursor: auto;
    }
</style>
@endsection