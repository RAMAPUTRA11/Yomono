@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-6 py-24">
    {{-- Header --}}
    <div class="mb-16 text-left">
        <h1 class="text-2xl font-light tracking-[0.4em] uppercase mb-4 text-gray-900">Returns & Shipping</h1>
        <p class="text-[11px] text-gray-400 uppercase tracking-widest">Customer Service / Policy</p>
        <div class="h-[1px] w-12 bg-black mt-4"></div>
    </div>

    {{-- Kebijakan Content Grid --}}
    <div class="space-y-16">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-12 border-b border-gray-100 pb-12">
            <h3 class="text-[12px] font-bold uppercase tracking-[0.2em] text-gray-900">Shipping Policy</h3>
            <div class="md:col-span-2 text-[12px] text-gray-500 leading-relaxed tracking-wide space-y-4">
                <p>Semua pesanan diproses dalam waktu 1-2 hari kerja. Kami tidak melakukan pengiriman pada hari Minggu dan hari libur nasional.</p>
                <p>Nomor resi pelacakan (Awb) otomatis akan dikirimkan ke email terdaftar atau nomor WhatsApp setelah paket diserahkan ke pihak ekspedisi.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-12 border-b border-gray-100 pb-12">
            <h3 class="text-[12px] font-bold uppercase tracking-[0.2em] text-gray-900">Returns & Exchange</h3>
            <div class="md:col-span-2 text-[12px] text-gray-500 leading-relaxed tracking-wide space-y-4">
                <p>Penukaran ukuran (Size Exchange) diperbolehkan maksimal 3 hari sejak status barang diterima menurut log kurir resmi.</p>
                <p>Produk wajib dikembalikan dalam kondisi original: hangtag terpasang utuh, belum pernah dicuci, tidak rusak, dan tidak berbau wewangian/parfum.</p>
                <p>Seluruh ongkos kirim bolak-balik sepenuhnya ditanggung oleh pihak pembeli, kecuali jika penukaran diakibatkan oleh salah kirim jenis barang atau cacat produksi (*defect*).</p>
            </div>
        </div>
    </div>

    {{-- Info Box / Contact Center --}}
    <div class="mt-20 border border-gray-100 p-12 text-center bg-gray-50/50">
        <h4 class="text-[12px] font-bold uppercase tracking-[0.3em] mb-6 text-gray-900">Need more details?</h4>
        <div class="flex flex-col sm:flex-row justify-center gap-6 sm:gap-16">
            <div class="text-[11px] uppercase tracking-widest text-gray-400">
                Email: <span class="text-black font-bold block mt-1 lowercase">support@yomono.id</span>
            </div>
            <div class="text-[11px] uppercase tracking-widest text-gray-400">
                WhatsApp Support: 
                <a href="https://wa.me/6285311111010" target="_blank" class="text-black font-bold block mt-1 hover:text-gray-600 transition underline decoration-1 underline-offset-4">
                    +62 853-1111-1010
                </a>
            </div>
        </div>
    </div>
</div>
@endsection