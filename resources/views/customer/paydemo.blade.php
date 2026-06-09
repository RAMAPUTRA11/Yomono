@extends('layouts.app')

@section('content')
<div class="max-w-[500px] mx-auto px-6 py-24">
    
    {{-- Header Simulator --}}
    <div class="text-center mb-10">
        <div class="inline-block bg-amber-50 border border-amber-200 text-amber-800 text-[10px] font-bold px-3 py-1 uppercase tracking-widest rounded-sm mb-3">
            ⚠️ BANK GATEWAY SIMULATOR (DEMO MODE)
        </div>
        <h1 class="text-xl font-light tracking-wider text-gray-900 uppercase">Simulasi Gerbang Pembayaran</h1>
        <p class="text-[11px] text-gray-400 mt-1">Selesaikan pembayaran untuk Order #{{ $order->order_number }}</p>
    </div>

    {{-- Detail Singkat Tagihan --}}
    <div class="border border-gray-100 bg-gray-50/50 p-6 mb-8 text-center rounded-sm">
        <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400 block mb-1">Total Tagihan Anda</span>
        <h2 class="text-2xl font-bold text-gray-900 tracking-wide">IDR {{ number_format($order->total_amount, 0, ',', '.') }}</h2>
    </div>

    {{-- Form Proses Simulator --}}
    <form action="{{ route('order.payDemo.process', $order->id) }}" method="POST" class="space-y-6">
        @csrf
        
        <div class="space-y-3">
            <label class="text-[11px] font-bold uppercase tracking-wider text-gray-900 block border-b border-gray-100 pb-2">
                Pilih Simulasi Metode Pembayaran
            </label>
            
            {{-- Opsi Bank 1 --}}
            <label class="flex items-center justify-between p-4 border border-gray-100 rounded-sm hover:bg-gray-50 cursor-pointer transition">
                <div class="flex items-center gap-3">
                    <input type="radio" name="payment_method" value="BCA_VIRTUAL_ACCOUNT" checked class="text-black focus:ring-black">
                    <span class="text-[12px] font-medium text-gray-800 uppercase tracking-wide">BCA Virtual Account</span>
                </div>
                <span class="text-[10px] bg-gray-100 text-gray-600 px-2 py-0.5 font-bold rounded-sm">BCA</span>
            </label>

            {{-- Opsi Bank 2 --}}
            <label class="flex items-center justify-between p-4 border border-gray-100 rounded-sm hover:bg-gray-50 cursor-pointer transition">
                <div class="flex items-center gap-3">
                    <input type="radio" name="payment_method" value="MANDIRI_VIRTUAL_ACCOUNT" class="text-black focus:ring-black">
                    <span class="text-[12px] font-medium text-gray-800 uppercase tracking-wide">Mandiri Virtual Account</span>
                </div>
                <span class="text-[10px] bg-gray-100 text-gray-600 px-2 py-0.5 font-bold rounded-sm">MANDIRI</span>
            </label>

            {{-- Opsi Bank 3 --}}
            <label class="flex items-center justify-between p-4 border border-gray-100 rounded-sm hover:bg-gray-50 cursor-pointer transition">
                <div class="flex items-center gap-3">
                    <input type="radio" name="payment_method" value="QRIS" class="text-black focus:ring-black">
                    <span class="text-[12px] font-medium text-gray-800 uppercase tracking-wide">QRIS (Otomatis)</span>
                </div>
                <span class="text-[10px] bg-gray-100 text-gray-600 px-2 py-0.5 font-bold rounded-sm">QRIS</span>
            </label>
        </div>

        {{-- Tombol Aksi --}}
        <div class="pt-4 space-y-3">
            <button type="submit" class="w-full bg-black text-white py-4 text-[11px] tracking-[0.2em] font-bold uppercase hover:bg-gray-900 transition active:scale-[0.99] rounded-sm shadow-sm">
                Selesaikan Pembayaran (Bypass Sukses)
            </button>
            
            <a href="{{ route('order.show', $order->id) }}" class="block text-center w-full bg-white text-gray-500 border border-gray-200 py-3.5 text-[11px] tracking-[0.15em] font-bold uppercase hover:bg-gray-50 hover:text-black transition rounded-sm">
                Batal / Kembali ke Invoice
            </a>
        </div>
    </form>

    <div class="mt-12 text-center">
        <p class="text-[11px] text-gray-400 italic">Halaman ini hanya simulator lokal proyek tugas akhir/demo aplikasi dan tidak memotong saldo asli apa pun.</p>
    </div>

</div>
@endsection