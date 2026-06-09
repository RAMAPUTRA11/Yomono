@extends('layouts.app')

@section('content')
<div class="max-w-[750px] mx-auto px-6 py-24">
    <div class="mb-20 text-center">
        <h1 class="text-2xl font-light tracking-[0.4em] uppercase mb-4 text-gray-900">How to Purchase</h1>
        <div class="h-[1px] w-12 bg-black mx-auto mb-6"></div>
        <p class="text-[11px] text-gray-400 uppercase tracking-[0.2em]">Langkah mudah berbelanja di yomono.id</p>
    </div>

    <div class="space-y-16">
        {{-- Step 1 --}}
        <div class="flex gap-8 items-start">
            <span class="text-3xl font-extralight text-gray-200 tracking-tighter select-none">01</span>
            <div>
                <h3 class="text-[12px] font-bold uppercase tracking-[0.2em] mb-3 text-gray-900">Pilih Produk</h3>
                <p class="text-[12px] text-gray-500 leading-relaxed tracking-wide">
                    Jelajahi koleksi kami di menu 'SHOP'. Klik pada produk yang kamu inginkan untuk melihat detail informasi, komposisi bahan, panduan ukuran, dan foto detail produk.
                </p>
            </div>
        </div>

        {{-- Step 2 --}}
        <div class="flex gap-8 items-start">
            <span class="text-3xl font-extralight text-gray-200 tracking-tighter select-none">02</span>
            <div>
                <h3 class="text-[12px] font-bold uppercase tracking-[0.2em] mb-3 text-gray-900">Tambah ke Keranjang</h3>
                <p class="text-[12px] text-gray-500 leading-relaxed tracking-wide">
                    Pilih opsi ukuran (Size) dan warna yang sesuai dengan preferensimu. Klik tombol 'ADD TO CART'. Kamu bisa melanjutkan eksplorasi produk lain atau langsung menuju halaman 'CART' di pojok kanan atas untuk memproses pesanan.
                </p>
            </div>
        </div>

        {{-- Step 3 --}}
        <div class="flex gap-8 items-start">
            <span class="text-3xl font-extralight text-gray-200 tracking-tighter select-none">03</span>
            <div>
                <h3 class="text-[12px] font-bold uppercase tracking-[0.2em] mb-3 text-gray-900">Checkout & Alamat Pengiriman</h3>
                <p class="text-[12px] text-gray-500 leading-relaxed tracking-wide">
                    Di halaman keranjang belanja, periksa kembali daftar produk lalu klik 'CHECKOUT'. Masukkan detail alamat pengiriman secara lengkap (Nama, Alamat Rumah, Kecamatan, Kota, Kode Pos, dan Nomor HP aktif) untuk menghindari kendala kurir.
                </p>
            </div>
        </div>

        {{-- Step 4 --}}
        <div class="flex gap-8 items-start">
            <span class="text-3xl font-extralight text-gray-200 tracking-tighter select-none">04</span>
            <div>
                <h3 class="text-[12px] font-bold uppercase tracking-[0.2em] mb-3 text-gray-900">Pembayaran Resmi</h3>
                <p class="text-[12px] text-gray-500 leading-relaxed tracking-wide">
                    Pilih metode pembayaran aman yang tersedia (Virtual Account Bank Transfer atau QRIS / E-Wallet). Lakukan transfer sesuai nominal total hingga digit terakhir. Pesanan kamu akan otomatis diverifikasi oleh sistem.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection