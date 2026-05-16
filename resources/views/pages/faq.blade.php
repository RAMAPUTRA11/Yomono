@extends('layouts.app')

@section('content')
{{-- Tambahin script Alpine.js via CDN di sini --}}
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<div class="max-w-[900px] mx-auto px-6 py-24">
    {{-- Header ala Thenblank --}}
    <div class="mb-16">
        <h1 class="text-2xl font-light tracking-[0.3em] uppercase mb-2 text-gray-900">FAQ</h1>
        <div class="h-[1px] w-12 bg-black mb-6"></div>
        <p class="text-[11px] text-gray-400 uppercase tracking-[0.2em]">Frequently Asked Questions</p>
    </div>

    {{-- Container Accordion --}}
    <div class="space-y-0 border-t border-gray-200" x-data="{ selected: null }">
        
        {{-- Item 1: Pemesanan --}}
        <div class="border-b border-gray-200">
            <button class="w-full py-6 flex justify-between items-center text-left" 
                    @click="selected !== 1 ? selected = 1 : selected = null">
                <span class="text-[12px] font-medium uppercase tracking-[0.15em] text-gray-800">Bagaimana cara memesan di yomono.id?</span>
                <span class="text-xl font-light" x-text="selected === 1 ? '−' : '+' "></span>
            </button>
            <div class="overflow-hidden transition-all duration-300 max-h-0" 
                 x-ref="container1" 
                 x-bind:style="selected === 1 ? 'max-height: ' + $refs.container1.scrollHeight + 'px' : ''">
                <div class="pb-8 text-[12px] text-gray-500 leading-relaxed uppercase tracking-wider">
                    Pemesanan dapat dilakukan melalui website resmi kami. Pilih produk, tentukan ukuran, masukkan ke keranjang, dan ikuti langkah instruksi pembayaran yang tersedia.
                </div>
            </div>
        </div>

        {{-- Item 2: Pembayaran --}}
        <div class="border-b border-gray-200">
            <button class="w-full py-6 flex justify-between items-center text-left" 
                    @click="selected !== 2 ? selected = 2 : selected = null">
                <span class="text-[12px] font-medium uppercase tracking-[0.15em] text-gray-800">Metode pembayaran apa saja yang tersedia?</span>
                <span class="text-xl font-light" x-text="selected === 2 ? '−' : '+' "></span>
            </button>
            <div class="overflow-hidden transition-all duration-300 max-h-0" 
                 x-ref="container2" 
                 x-bind:style="selected === 2 ? 'max-height: ' + $refs.container2.scrollHeight + 'px' : ''">
                <div class="pb-8 text-[12px] text-gray-500 leading-relaxed uppercase tracking-wider">
                    Kami menerima pembayaran melalui Transfer Bank (BCA, Mandiri, BNI) serta metode pembayaran instan seperti GoPay dan QRIS.
                </div>
            </div>
        </div>

        {{-- Item 3: Pengiriman --}}
        <div class="border-b border-gray-200">
            <button class="w-full py-6 flex justify-between items-center text-left" 
                    @click="selected !== 3 ? selected = 3 : selected = null">
                <span class="text-[12px] font-medium uppercase tracking-[0.15em] text-gray-800">Kapan pesanan saya dikirim?</span>
                <span class="text-xl font-light" x-text="selected === 3 ? '−' : '+' "></span>
            </button>
            <div class="overflow-hidden transition-all duration-300 max-h-0" 
                 x-ref="container3" 
                 x-bind:style="selected === 3 ? 'max-height: ' + $refs.container3.scrollHeight + 'px' : ''">
                <div class="pb-8 text-[12px] text-gray-500 leading-relaxed uppercase tracking-wider">
                    Pesanan akan diproses 1-2 hari kerja setelah konfirmasi pembayaran diterima. Pengiriman dilakukan setiap hari Senin - Sabtu.
                </div>
            </div>
        </div>

        {{-- Item 4: Pengembalian --}}
        <div class="border-b border-gray-200">
            <button class="w-full py-6 flex justify-between items-center text-left" 
                    @click="selected !== 4 ? selected = 4 : selected = null">
                <span class="text-[12px] font-medium uppercase tracking-[0.15em] text-gray-800">Apakah saya bisa menukar ukuran produk?</span>
                <span class="text-xl font-light" x-text="selected === 4 ? '−' : '+' "></span>
            </button>
            <div class="overflow-hidden transition-all duration-300 max-h-0" 
                 x-ref="container4" 
                 x-bind:style="selected === 4 ? 'max-height: ' + $refs.container4.scrollHeight + 'px' : ''">
                <div class="pb-8 text-[12px] text-gray-500 leading-relaxed uppercase tracking-wider">
                    Bisa, maksimal 3 hari setelah barang diterima. Pastikan hangtag masih terpasang dan barang belum dicuci. Ongkos kirim ditanggung oleh pembeli.
                </div>
            </div>
        </div>

    </div>

    {{-- Customer Service Link --}}
    <div class="mt-24 text-center">
        <p class="text-[10px] text-gray-400 uppercase tracking-[0.3em] mb-4">Masih butuh bantuan?</p>
        <a href="https://wa.me/6285311111010" class="text-[11px] font-bold border-b border-black pb-1 uppercase tracking-widest hover:text-gray-400 transition">Hubungi WhatsApp Kami</a>
    </div>
</div>
@endsection