<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>yomono.id</title>
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body { font-family: 'Inter', sans-serif; scroll-behavior: smooth; }
        [x-cloak] { display: none !important; }

        .nav-link { position: relative; display: inline-block; }
        .nav-link::after {
            content: ''; position: absolute; width: 0; height: 1.5px;
            bottom: -2px; left: 0; background-color: black; transition: width 0.3s ease;
        }
        .nav-link:hover::after, .active-link::after { width: 100%; }

        .mega-menu-scroll::-webkit-scrollbar { width: 4px; }
        .mega-menu-scroll::-webkit-scrollbar-track { background: #f1f1f1; }
        .mega-menu-scroll::-webkit-scrollbar-thumb { background: #888; }
        
        /* Input styling for newsletter */
        .footer-input {
            background: transparent;
            border-bottom: 1px solid #ccc;
            outline: none;
            transition: border-color 0.3s;
        }
        .footer-input:focus { border-color: black; }
    </style>
</head>
<body class="bg-white flex flex-col min-h-screen">

    <div class="bg-black text-white text-[10px] py-2.5 text-center tracking-[0.2em] uppercase z-[70] relative">
        Free shipping up to 50,000 with a minimum purchase of 199,000
    </div>

    <header x-data="{ 
        searchOpen: false, 
        shopMenu: false, 
        searchQuery: '', 
        suggestions: [],
        fetchSuggestions() {
            if (this.searchQuery.length < 2) { this.suggestions = []; return; }
            fetch(`/api/search-suggestions?q=${this.searchQuery}`)
                .then(res => res.json())
                .then(data => { this.suggestions = data; })
        }
    }" class="sticky top-0 z-50 bg-white border-b border-gray-100">
        
        <div class="max-w-[1400px] mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex-shrink-0">
                <a href="/" class="text-2xl font-bold tracking-tighter text-black">YOMONO</a>
            </div>

            <nav class="hidden lg:flex space-x-10 text-[11px] font-medium uppercase tracking-[0.15em] text-gray-700 items-center">
                <a href="{{ url('/shop?collection=new Artikel') }}" class="nav-link hover:text-black transition">New Artikel</a>
                <a href="{{ url('/shop?collection=Best in 2026') }}" class="nav-link hover:text-black transition">Best in 2026</a>
                <a href="{{ url('/shop?collection=all categories') }}" class="nav-link hover:text-black transition">All Categories</a>

                <div class="relative h-20 flex items-center" @mouseenter="shopMenu = true" @mouseleave="shopMenu = false">
                    <a href="{{ url('/shop') }}" class="nav-link hover:text-black transition py-2" :class="{ 'active-link': shopMenu }">Shop</a>

                    <div x-show="shopMenu" x-cloak
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 -translate-y-4"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="fixed left-0 top-20 w-full bg-white border-b border-gray-100 shadow-xl z-40 overflow-y-auto max-h-[70vh] mega-menu-scroll">
                        <div class="max-w-[1400px] mx-auto grid grid-cols-4 gap-10 py-14 px-20">
                            <div>
                                <h4 class="text-black font-bold mb-6 tracking-[0.2em] text-[12px] uppercase">COLLECTIONS</h4>
                                <ul class="space-y-3 text-gray-500 font-normal tracking-wide text-[11px]">
                                    @php
                                        // Gunakan groupBy sebagai pengganti distinct agar tetap bisa order by created_at
                                        $collections = \App\Models\Product::select('collection_name', \DB::raw('MAX(created_at) as last_created'))
                                                        ->whereNotNull('collection_name')
                                                        ->groupBy('collection_name')
                                                        ->orderBy('last_created', 'desc')
                                                        ->take(4)
                                                        ->get();
                                    @endphp

                                    @foreach($collections as $col)
                                        <li>
                                            <a href="{{ route('shop', ['collection' => $col->collection_name]) }}" 
                                            class="hover:text-black transition uppercase">
                                                {{ $col->collection_name }}
                                            </a>
                                        </li>
                                    @endforeach

                                    {{-- Fallback jika database kosong --}}
                                    @if($collections->isEmpty())
                                        <li><a href="{{ route('shop') }}" class="hover:text-black transition uppercase">New Arrivals</a></li>
                                    @endif
                                </ul>
                            </div>
                            <div>
                                <h4 class="text-black font-bold mb-6 tracking-[0.2em] text-[12px] uppercase">HIGHLIGHT</h4>
                                <ul class="space-y-3 text-gray-500 font-normal tracking-wide text-[11px]">
                                    <li><a href="{{ url('/shop?highlight=best-seller') }}" class="hover:text-black transition font-bold text-black italic">Best Seller</a></li>
                                    <li><a href="{{ url('/shop?highlight=womenswear') }}" class="hover:text-black transition">Womenswear</a></li>
                                    <li><a href="{{ route('pages.faq') }}" class="hover:text-black transition border-t border-gray-100 pt-3 block mt-3">About Us</a></li>
                                </ul>
                            </div>
                            <div>
                                <h4 class="text-black font-bold mb-6 tracking-[0.2em] text-[12px] uppercase">CATEGORY</h4>
                                <ul class="space-y-3 text-gray-500 font-normal tracking-wide text-[11px]">
                                    @if(isset($categories) && $categories->count() > 0)
                                        @foreach($categories as $category)
                                            <li><a href="{{ url('/shop?category=' . $category->slug) }}" class="hover:text-black transition">{{ $category->name }}</a></li>
                                        @endforeach
                                    @else
                                        <li><a href="#" class="hover:text-black transition">Tops</a></li>
                                        <li><a href="#" class="hover:text-black transition">Bottoms</a></li>
                                    @endif
                                </ul>
                            </div>
                            <div class="flex items-center justify-center bg-gray-50 rounded-sm p-4">
                                <p class="text-[10px] text-gray-400 tracking-[0.2em] uppercase italic text-center">New Arrivals<br>Available Now</p>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <div class="flex items-center space-x-6 text-gray-800">
                @auth
                    <a href="{{ Auth::user()->role === 'admin' ? route('admin.dashboard') : route('home') }}" 
                    class="hover:text-gray-600 transition uppercase font-bold">
                        {{ Auth::user()->name }}
                    </a>
                @else
                    <a href="{{ route('login') }}" 
                    class="hover:text-gray-600 transition uppercase">
                        ACCOUNT
                    </a>
                @endauth
                <button @click="searchOpen = true" class="hover:text-black transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </button>
                <a href="{{ route('cart.index') }}" class="relative hover:text-black transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    <span class="absolute -top-1.5 -right-1.5 bg-black text-white text-[8px] w-4 h-4 rounded-full flex items-center justify-center font-bold">0</span>
                </a>
            </div>
        </div>

        <div x-show="searchOpen" x-cloak class="fixed inset-0 bg-white z-[100] flex flex-col p-10" @keydown.escape.window="searchOpen = false">
            <div class="flex justify-end">
                <button @click="searchOpen = false" class="text-[11px] uppercase tracking-[0.2em] font-bold">Close [X]</button>
            </div>
            <div class="mt-20 flex flex-col items-center">
                <form action="{{ url('/shop') }}" method="GET" class="w-full max-w-3xl text-center">
                    <input type="text" name="search" placeholder="SEARCH PRODUCTS..." x-model="searchQuery" @input.debounce.300ms="fetchSuggestions()"
                           class="w-full text-3xl md:text-5xl font-light uppercase tracking-tighter border-b border-gray-100 py-6 outline-none focus:border-black transition text-center">
                    <p class="mt-4 text-[10px] text-gray-400 tracking-[0.2em]">PRESS ENTER TO SEARCH OR ESCAPE TO CLOSE</p>
                </form>
                <div class="w-full max-w-2xl mt-10 grid grid-cols-1 md:grid-cols-2 gap-4 overflow-y-auto max-h-[50vh]">
                    <template x-for="item in suggestions" :key="item.id">
                        <a :href="'/shop/' + item.slug" class="flex items-center space-x-4 p-3 hover:bg-gray-50 transition border-b border-gray-50">
                            <img :src="item.image_url" class="w-12 h-12 object-cover bg-gray-100">
                            <div class="text-left">
                                <p class="text-[11px] font-bold uppercase tracking-widest text-black" x-text="item.name"></p>
                                <p class="text-[10px] text-gray-400" x-text="'IDR ' + item.price"></p>
                            </div>
                        </a>
                    </template>
                </div>
            </div>
        </div>
    </header>

    <main class="flex-grow">
        @yield('content')
    </main>

    <footer class="bg-[#f2f2f2] pt-20 pb-10 border-t border-gray-200 mt-20">
        <div class="max-w-[1400px] mx-auto px-10">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-20">
                
                <div class="space-y-6">
                    <h4 class="text-[11px] font-bold tracking-[0.2em] uppercase">dress well, keep it simple</h4>
                    <p class="text-[11px] text-gray-500 leading-relaxed font-light">
                        since 2012, we’re committed to always give you a better daily-wear with timeless and minimalist design as our guide, either casual, semi-formal, or formal look, that you’ll love to wear anywhere and anytime.
                    </p>
                    <div class="pt-4">
                        <p class="text-[9px] text-gray-400 leading-relaxed">
                            Layanan Pengaduan Konsumen Direktorat Jenderal Perlindungan Konsumen dan Tertib Niaga Kementerian Perdagangan RI<br>
                            Nomor Whatsapp Ditjen PKTN 0853-1111-1010
                        </p>
                    </div>
                    <div class="flex space-x-5 text-gray-600">
                        <a href="#" class="hover:text-black transition"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="hover:text-black transition"><i class="fab fa-tiktok"></i></a>
                        <a href="#" class="hover:text-black transition"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>

                <div>
                    <h4 class="text-[11px] font-bold tracking-[0.2em] uppercase mb-8">NEED HELP</h4>
                    <ul class="space-y-4 text-[11px] text-gray-500 font-light">
                        <li><a href="{{ route('login') }}" class="hover:text-black transition">my account</a></li>
                        <li><a href="{{ route('pages.faq') }}" class="hover:text-black transition">FAQ</a></li>
                        <li><a href="{{ route('pages.returns-shipping') }}" class="hover:text-black transition">returns and shipping</a></li>
                        <li><a href="{{ route('pages.how-to-purchase') }}" class="hover:text-black transition">how to purchase</a></li>
                        <li><a href="{{ route('pages.sizing-guide') }}" class="hover:text-black transition">sizing guide</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-[11px] font-bold tracking-[0.2em] uppercase mb-8">ABOUT US</h4>
                    <ul class="space-y-4 text-[11px] text-gray-500 font-light">
                        <li><a href="{{ route('pages.stores') }}" class="hover:text-black transition">visit our stores</a></li>
                        <li><a href="{{ route('pages.career') }}" class="hover:text-black transition">join #yomonoteam</a></li>
                        <li><a href="{{ route('pages.journal') }}" class="hover:text-black transition">journal #YOMONO</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-[11px] font-bold tracking-[0.2em] uppercase mb-8">newsletter</h4>
                    <p class="text-[11px] text-gray-500 mb-6 font-light">sign up to our newsletter and keep up to date with the latest arrivals</p>
                    <form class="relative">
                        <input type="email" placeholder="enter email" class="w-full py-3 footer-input text-[11px]">
                        <button type="submit" class="absolute right-0 top-3 text-gray-400 hover:text-black transition">
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </form>
                </div>
            </div>

            <div class="border-t border-gray-200 pt-10 flex flex-col md:flex-row justify-between items-center space-y-6 md:space-y-0">
                <div class="text-[10px] text-gray-400 tracking-widest uppercase font-light">
                    © 2026 YOMONO.ID
                </div>
                
                <div class="flex items-center space-x-4 grayscale opacity-50">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/5/5c/Bank_Central_Asia.svg" class="h-3" alt="BCA">
                    <img src="https://upload.wikimedia.org/wikipedia/id/f/fa/Bank_Mandiri_logo.svg" class="h-3" alt="Mandiri">
                    <img src="https://upload.wikimedia.org/wikipedia/id/5/55/BNI_logo.svg" class="h-3" alt="BNI">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/b/b7/MasterCard_Logo.svg" class="h-4" alt="Mastercard">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/5/5e/Visa_Inc._logo.svg" class="h-3" alt="Visa">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/8/85/Gopay_logo.svg" class="h-3" alt="Gopay">
                </div>
            </div>
        </div>
    </footer>

</body>
</html>