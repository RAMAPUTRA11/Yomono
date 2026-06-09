@extends('layouts.app')

@section('content')
<div class="max-w-[1400px] mx-auto px-4 sm:px-6 md:px-16 py-12 md:py-20">
    
    {{-- Judul Halaman --}}
    <div class="border-b border-gray-100 pb-6 mb-10">
        <h1 class="text-xl md:text-2xl font-bold tracking-tight text-black uppercase">PESANAN SAYA (DEMO MODE)</h1>
        <p class="text-[11px] text-gray-400 tracking-wide mt-1">Pantau status pengiriman dan riwayat simulasi belanja Anda secara berkala.</p>
    </div>

    {{-- Filter Navigasi Status --}}
    <div class="flex flex-wrap gap-4 md:gap-6 mb-8 text-[11px] font-bold tracking-widest uppercase border-b border-gray-50 pb-2">
        {{-- CATATAN: Jika nama route list pesananmu bukan 'orders.pesanan', silakan ganti bagian route() di bawah ini --}}
        <a href="{{ route('orders.pesanan') }}" 
           class="pb-2 transition {{ !request('status') ? 'border-b-2 border-black text-black' : 'text-gray-400 hover:text-black' }}">
            SEMUA
        </a>
        <a href="{{ route('orders.pesanan', ['status' => 'pending']) }}" 
           class="pb-2 transition {{ request('status') === 'pending' ? 'border-b-2 border-black text-black' : 'text-gray-400 hover:text-black' }}">
            BELUM BAYAR
        </a>
        <a href="{{ route('orders.pesanan', ['status' => 'processing']) }}" 
           class="pb-2 transition {{ request('status') === 'processing' ? 'border-b-2 border-black text-black' : 'text-gray-400 hover:text-black' }}">
            DIPROSES
        </a>
        <a href="{{ route('orders.pesanan', ['status' => 'shipped']) }}" 
           class="pb-2 transition {{ request('status') === 'shipped' ? 'border-b-2 border-black text-black' : 'text-gray-400 hover:text-black' }}">
            DIKIRIM
        </a>
        <a href="{{ route('orders.pesanan', ['status' => 'completed']) }}" 
           class="pb-2 transition {{ request('status') === 'completed' ? 'border-b-2 border-black text-black' : 'text-gray-400 hover:text-black' }}">
            SELESAI
        </a>
    </div>

    {{-- Daftar Pesanan --}}
    <div class="space-y-6">
        
        @forelse($orders as $order)
            <div class="border border-gray-100 rounded-sm p-5 md:p-6 bg-[#fcfcfc] hover:shadow-sm transition">
                
                {{-- Info Atas Baris Pesanan --}}
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 border-b border-gray-100 pb-4 mb-4 text-[11px] tracking-wide">
                    <div class="text-gray-500">
                        <span class="font-bold text-black">#{{ $order->order_number }}</span> 
                        <span class="mx-2">|</span> {{ $order->created_at->format('d M Y, H:i') }} WIB
                    </div>
                    <div>
                        {{-- Badge Status Utama --}}
                        <span class="text-[9px] font-bold tracking-widest uppercase px-2.5 py-1 rounded-sm 
                            @if($order->status === 'pending') bg-orange-100 text-orange-800 
                            @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                            @elseif($order->status === 'shipped') bg-purple-100 text-purple-800
                            @elseif($order->status === 'completed') bg-green-100 text-green-800
                            @else bg-black text-white @endif">
                            @if($order->status === 'pending') BELUM BAYAR
                            @elseif($order->status === 'processing') DIPROSES
                            @elseif($order->status === 'shipped') DIKIRIM
                            @elseif($order->status === 'completed') SELESAI
                            @else {{ $order->status }} @endif
                        </span>

                        {{-- Status Pembayaran Lunas / Belum --}}
                        <span class="ml-2 text-[9px] font-bold tracking-widest uppercase px-2.5 py-1 rounded-sm border
                            {{ $order->payment_status === 'paid' ? 'border-green-200 text-green-700 bg-green-50' : 'border-red-200 text-red-700 bg-red-50' }}">
                            {{ $order->payment_status === 'paid' ? 'TERBAYAR' : 'BELUM BAYAR' }}
                        </span>
                    </div>
                </div>

                {{-- Konten Utama Pesanan --}}
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    
                    <div class="flex flex-col space-y-4 flex-1 w-full">
                        @foreach($order->orderItems as $item)
                            <div class="flex items-center space-x-4">
                                {{-- Gambar Produk --}}
                                <div class="w-16 h-20 bg-gray-50 rounded-sm overflow-hidden flex-shrink-0 flex items-center justify-center border border-gray-100">
                                    @if($item->variant && $item->variant->product && $item->variant->product->image)
                                        <img src="{{ asset('storage/' . $item->variant->product->image) }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-[9px] text-gray-400">No Image</span>
                                    @endif
                                </div>
                                
                                {{-- Nama & Detail Item --}}
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-[12px] font-bold uppercase tracking-widest text-black truncate">
                                        {{ $item->variant->product->name ?? 'Produk Terhapus' }}
                                    </h3>
                                    <p class="text-[10px] text-gray-400 uppercase tracking-wider mt-0.5">
                                        Warna: {{ $item->variant->color->name ?? 'N/A' }} 
                                        @if($item->variant->size) | Ukuran: {{ $item->variant->size->name }} @endif
                                        | Qty: {{ $item->quantity }}
                                    </p>
                                    <p class="text-[11px] text-gray-400 mt-1">
                                        Alamat Kirim: <span class="text-gray-600 truncate max-w-xs inline-block align-bottom">{{ $order->shipping_address }}</span>
                                    </p>
                                </div>
                            </div>
                        @endforeach

                        @if($order->tracking_number)
                            <div class="pt-2 border-t border-dashed border-gray-100">
                                <p class="text-[11px] text-gray-400">No. Resi Pengiriman: <span class="text-black font-mono font-bold tracking-wider ml-1">{{ $order->tracking_number }}</span></p>
                            </div>
                        @endif
                    </div>

                    {{-- Total Tagihan & Tombol Detail --}}
                    <div class="w-full md:w-auto flex md:flex-col justify-between md:items-end items-center border-t md:border-t-0 pt-4 md:pt-0 border-gray-100 flex-shrink-0">
                        <div class="text-left md:text-right mb-0 md:mb-3">
                            <p class="text-[10px] text-gray-400 uppercase tracking-widest">Total Tagihan</p>
                            <p class="text-[13px] font-bold text-black">IDR {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                        </div>
                        
                    <a href="{{ route('orders.show', $order->id) }}" class="border border-black text-black hover:bg-black hover:text-white transition text-[10px] font-bold tracking-widest uppercase px-4 py-2.5 rounded-sm">
                        LIHAT DETAIL
                    </a>
                    </div>
                </div>

            </div>
        @empty
            <div class="text-center py-24 border border-dashed border-gray-200 rounded-sm">
                <p class="text-[11px] text-gray-400 tracking-widest uppercase">Tidak ada pesanan dengan status ini.</p>
                <div class="mt-5">
                    <a href="{{ url('/shop') }}" class="bg-black text-white text-[10px] font-bold tracking-widest uppercase px-6 py-3 hover:bg-gray-800 transition rounded-sm">
                        Mulai Belanja
                    </a>
                </div>
            </div>
        @endforelse

    </div>
</div>
@endsection