@extends('layouts.app')

@section('content')
{{-- Memuat Alpine.js via CDN --}}
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<div class="max-w-[800px] mx-auto px-6 py-24">
    {{-- Header --}}
    <div class="mb-16">
        <h1 class="text-2xl font-light tracking-[0.3em] uppercase mb-2 text-gray-900">FAQ</h1>
        <div class="h-[1px] w-12 bg-black mb-6"></div>
        <p class="text-[11px] text-gray-400 uppercase tracking-[0.2em]">Frequently Asked Questions</p>
    </div>

    {{-- Container Accordion --}}
    <div class="space-y-0 border-t border-gray-200" x-data="{ selected: null }">
        
        {{-- Item 1: Pemesanan --}}
        <div class="border-b border-gray-200">
            <button class="w-full py-6 flex justify-between items-center text-left outline-none group" 
                    @click="selected !== 1 ? selected = 1 : selected = null">
                <span class="text-[12px] font-bold uppercase tracking-[0.15em] text-gray-800 group-hover:text-black transition-colors">Bagaimana cara memesan di yomono.id?</span>
                <span class="text-lg font-light text-gray-400 group-hover:text-black" x-text="selected === 1 ? '−' : '+' "></span>
            </button>
            <div class="overflow-hidden transition-all duration-300 max-h-0" 
                 x-ref="container1" 
                 x-bind:style="selected === 1 ? 'max-height: ' + $refs.container1.scrollHeight + 'px' : ''">
                <div class="pb-8 text-[12px] text-gray-500 leading-relaxed tracking-wide">
                    Pemesanan dapat dilakukan melalui website resmi kami. Pilih produk, tentukan ukuran, masukkan ke keranjang belanja, dan ikuti langkah instruksi pembayaran yang tersedia pada halaman checkout.
                </div>
            </div>
        </div>

        {{-- Item 2: Pembayaran --}}
        <div class="border-b border-gray-200">
            <button class="w-full py-6 flex justify-between items-center text-left outline-none group" 
                    @click="selected !== 2 ? selected = 2 : selected = null">
                <span class="text-[12px] font-bold uppercase tracking-[0.15em] text-gray-800 group-hover:text-black transition-colors">Metode pembayaran apa saja yang tersedia?</span>
                <span class="text-lg font-light text-gray-400 group-hover:text-black" x-text="selected === 2 ? '−' : '+' "></span>
            </button>
            <div class="overflow-hidden transition-all duration-300 max-h-0" 
                 x-ref="container2" 
                 x-bind:style="selected === 2 ? 'max-height: ' + $refs.container2.scrollHeight + 'px' : ''">
                <div class="pb-8 text-[12px] text-gray-500 leading-relaxed tracking-wide">
                    Kami menerima pembayaran melalui Transfer Bank resmi (BCA, Mandiri, BNI) serta berbagai opsi metode pembayaran instan seperti GoPay, ShopeePay, dan QRIS.
                </div>
            </div>
        </div>

        {{-- Item 3: Pengiriman --}}
        <div class="border-b border-gray-200">
            <button class="w-full py-6 flex justify-between items-center text-left outline-none group" 
                    @click="selected !== 3 ? selected = 3 : selected = null">
                <span class="text-[12px] font-bold uppercase tracking-[0.15em] text-gray-800 group-hover:text-black transition-colors">Kapan pesanan saya dikirim?</span>
                <span class="text-lg font-light text-gray-400 group-hover:text-black" x-text="selected === 3 ? '−' : '+' "></span>
            </button>
            <div class="overflow-hidden transition-all duration-300 max-h-0" 
                 x-ref="container3" 
                 x-bind:style="selected === 3 ? 'max-height: ' + $refs.container3.scrollHeight + 'px' : ''">
                <div class="pb-8 text-[12px] text-gray-500 leading-relaxed tracking-wide">
                    Pesanan akan diproses dalam waktu 1-2 hari kerja setelah verifikasi pembayaran sukses dilakukan. Operasional pengiriman paket berjalan setiap hari Senin hingga Sabtu (tidak termasuk hari libur nasional).
                </div>
            </div>
        </div>

        {{-- Item 4: Pengembalian --}}
        <div class="border-b border-gray-200">
            <button class="w-full py-6 flex justify-between items-center text-left outline-none group" 
                    @click="selected !== 4 ? selected = 4 : selected = null">
                <span class="text-[12px] font-bold uppercase tracking-[0.15em] text-gray-800 group-hover:text-black transition-colors">Apakah saya bisa menukar ukuran produk?</span>
                <span class="text-lg font-light text-gray-400 group-hover:text-black" x-text="selected === 4 ? '−' : '+' "></span>
            </button>
            <div class="overflow-hidden transition-all duration-300 max-h-0" 
                 x-ref="container4" 
                 x-bind:style="selected === 4 ? 'max-height: ' + $refs.container4.scrollHeight + 'px' : ''">
                <div class="pb-8 text-[12px] text-gray-500 leading-relaxed tracking-wide">
                    Penukaran ukuran diperbolehkan maksimal 3 hari sejak produk diterima. Syarat utamanya adalah hangtag baju masih terpasang utuh, produk belum pernah dicuci, dan tidak berbau. Seluruh ongkos kirim penukaran ditanggung oleh pembeli.
                </div>
            </div>
        </div>

    </div>

    {{-- Customer Service Link --}}
    <div class="mt-24 text-center">
        <p class="text-[10px] text-gray-400 uppercase tracking-[0.3em] mb-4">Masih butuh bantuan?</p>
        <a href="https://wa.me/6285311111010" class="text-[11px] font-bold border-b border-black pb-1 uppercase tracking-[0.2em] text-gray-900 hover:text-gray-500 hover:border-gray-50 transition">
            Hubungi WhatsApp Kami
        </a>
    </div>
</div>
@endsection