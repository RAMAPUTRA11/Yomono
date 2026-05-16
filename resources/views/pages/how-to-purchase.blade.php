@extends('layouts.app')

@section('content')
<div class="max-w-[800px] mx-auto px-6 py-24">
    <div class="mb-16 text-center">
        <h1 class="text-2xl font-light tracking-[0.4em] uppercase mb-4 text-gray-900">How to Purchase</h1>
        <div class="h-[1px] w-12 bg-black mx-auto mb-6"></div>
        <p class="text-[11px] text-gray-400 uppercase tracking-[0.2em]">Langkah mudah berbelanja di yomono.id</p>
    </div>

    <div class="space-y-16">
        {{-- Step 1 --}}
        <div class="flex gap-8 items-start">
            <span class="text-2xl font-extralight text-gray-300 italic">01</span>
            <div>
                <h3 class="text-[12px] font-bold uppercase tracking-[0.2em] mb-3">Pilih Produk</h3>
                <p class="text-[12px] text-gray-500 leading-relaxed uppercase tracking-wider">
                    Jelajahi koleksi kami di menu 'SHOP'. Klik pada produk yang kamu inginkan untuk melihat detail informasi, bahan, dan foto produk.
                </p>
            </div>
        </div>

        {{-- Step 2 --}}
        <div class="flex gap-8 items-start">
            <span class="text-2xl font-extralight text-gray-300 italic">02</span>
            <div>
                <h3 class="text-[12px] font-bold uppercase tracking-[0.2em] mb-3">Tambah ke Keranjang</h3>
                <p class="text-[12px] text-gray-500 leading-relaxed uppercase tracking-wider">
                    Pilih ukuran (Size) dan warna yang sesuai. Klik tombol 'ADD TO CART'. Kamu bisa lanjut belanja atau langsung menuju halaman 'CART' di pojok kanan atas.
                </p>
            </div>
        </div>

        {{-- Step 3 --}}
        <div class="flex gap-8 items-start">
            <span class="text-2xl font-extralight text-gray-300 italic">03</span>
            <div>
                <h3 class="text-[12px] font-bold uppercase tracking-[0.2em] mb-3">Checkout & Alamat</h3>
                <p class="text-[12px] text-gray-500 leading-relaxed uppercase tracking-wider">
                    Di halaman keranjang, klik 'CHECKOUT'. Masukkan detail pengiriman, pastikan nama, alamat lengkap, dan nomor handphone sudah benar untuk menghindari kendala kurir.
                </p>
            </div>
        </div>

        {{-- Step 4 --}}
        <div class="flex gap-8 items-start">
            <span class="text-2xl font-extralight text-gray-300 italic">04</span>
            <div>
                <h3 class="text-[12px] font-bold uppercase tracking-[0.2em] mb-3">Pembayaran</h3>
                <p class="text-[12px] text-gray-500 leading-relaxed uppercase tracking-wider">
                    Pilih metode pembayaran yang kamu inginkan (Bank Transfer atau E-Wallet). Lakukan pembayaran sesuai nominal yang tertera. Pesananmu akan otomatis terproses setelah verifikasi.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection