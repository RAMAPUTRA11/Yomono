@extends('layouts.app')

@section('content')
<div class="max-w-[800px] mx-auto px-6 py-24">
    
    {{-- Notifikasi Sukses --}}
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 text-[12px] uppercase tracking-wider rounded-sm font-medium">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-12 border-b border-gray-100 pb-6 gap-4">
        <div>
            <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400 block mb-1">Invoice (Demo Mode)</span>
            <h1 class="text-xl font-light tracking-wider text-gray-900 uppercase">Order #{{ $order->order_number }}</h1>
            <p class="text-[11px] text-gray-400 mt-1">{{ $order->created_at->format('d M Y, H:i') }} WIB</p>
        </div>
        
        {{-- Status Badge --}}
        <div>
            @if($order->status === 'pending')
                <span class="bg-amber-50 text-amber-700 border border-amber-100 text-[10px] font-bold px-3 py-1 uppercase tracking-widest rounded-sm">Belum Bayar</span>
            @elseif($order->status === 'processing')
                <span class="bg-blue-50 text-blue-700 border border-blue-100 text-[10px] font-bold px-3 py-1 uppercase tracking-widest rounded-sm">Diproses</span>
            @elseif($order->status === 'shipped')
                <span class="bg-purple-50 text-purple-700 border border-purple-100 text-[10px] font-bold px-3 py-1 uppercase tracking-widest rounded-sm">Dikirim</span>
            @elseif($order->status === 'completed')
                <span class="bg-green-50 text-green-700 border border-green-100 text-[10px] font-bold px-3 py-1 uppercase tracking-widest rounded-sm">Selesai</span>
            @else
                <span class="bg-gray-50 text-gray-700 border border-gray-100 text-[10px] font-bold px-3 py-1 uppercase tracking-widest rounded-sm">{{ $order->status }}</span>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-10 mb-12">
        {{-- Info Pengiriman --}}
        <div class="md:col-span-2 space-y-2">
            <h3 class="text-[11px] font-bold uppercase tracking-wider text-gray-900 border-b border-gray-50 pb-1">Alamat Pengiriman</h3>
            <p class="text-[12px] text-gray-700 font-medium leading-relaxed">{{ $order->shipping_address }}</p>
        </div>
        {{-- Info Metode Pembayaran --}}
        <div class="space-y-2">
            <h3 class="text-[11px] font-bold uppercase tracking-wider text-gray-900 border-b border-gray-50 pb-1">Metode Pembayaran</h3>
            <p class="text-[11px] text-gray-600 uppercase tracking-wide font-medium">
                {{ $order->payment ? str_replace('_', ' ', $order->payment->payment_method) : 'BANK TRANSFER' }}
            </p>
        </div>
    </div>

    {{-- Detail Item Produk --}}
    <div class="border border-gray-100 bg-white p-6 mb-8">
        <h3 class="text-[11px] font-bold uppercase tracking-wider text-gray-900 mb-4">Item Terbeli</h3>
        <div class="divide-y divide-gray-100">
            @foreach($order->orderItems as $item)
                <div class="flex justify-between items-center py-4 first:pt-0 last:pb-0">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-16 bg-gray-50 border border-gray-100 flex-shrink-0">
                            @if($item->variant && $item->variant->product && $item->variant->product->image)
                                <img src="{{ asset('storage/' . $item->variant->product->image) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-[8px] text-gray-300">No Pic</div>
                            @endif
                        </div>
                        <div>
                            <h4 class="text-[12px] font-bold uppercase text-gray-900">{{ $item->variant->product->name ?? 'Produk Terhapus' }}</h4>
                            <p class="text-[10px] text-gray-400 mt-0.5 uppercase tracking-wider">
                                Ukuran: {{ $item->variant->size->name ?? 'N/A' }} / Warna: {{ $item->variant->color->name ?? 'N/A' }}
                                <span class="text-gray-900 font-bold ml-2">x{{ $item->quantity }}</span>
                            </p>
                        </div>
                    </div>
                    <span class="text-[12px] font-medium text-gray-900">
                        IDR {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                    </span>
                </div>
            @endforeach
        </div>

        {{-- Total Akhir --}}
        <div class="border-t border-gray-100 mt-6 pt-6 flex justify-between items-center">
            <span class="text-[12px] font-bold uppercase tracking-widest text-gray-900">Total Pembayaran</span>
            <span class="text-base font-bold text-gray-900 tracking-wide">IDR {{ number_format($order->total_amount, 0, ',', '.') }}</span>
        </div>
    </div>

    {{-- Aksi Interaktif --}}
    <div class="flex flex-col sm:flex-row gap-4 items-center justify-between mt-8">
        <a href="{{ route('orders.pesanan') }}" class="text-[11px] font-bold uppercase tracking-widest text-gray-400 hover:text-black transition">
            ← Kembali ke Pesanan Saya
        </a>

        {{-- Tombol dialihkan menggunakan link GET ke halaman simulator --}}
        @if($order->status === 'pending')
            <div class="w-full sm:w-auto">
                <a href="{{ route('order.payDemo', $order->id) }}" class="inline-block text-center w-full sm:w-auto bg-green-600 text-white px-8 py-3.5 text-[11px] tracking-[0.2em] font-bold uppercase hover:bg-green-700 transition active:scale-[0.99] rounded-sm">
                    Bayar Sekarang (Simulasi Demo)
                </a>
            </div>
        @endif
    </div>
</div>
@endsection