@extends('layouts.app')

@section('content')
<div class="flex flex-col md:flex-row min-h-screen bg-[#fcfcfc]">
    {{-- Sidebar Admin Kontainer --}}
    <div class="w-full md:w-64 bg-white border-b md:border-b-0 md:border-r border-gray-100">
        <div class="p-6 md:p-8 flex flex-row md:flex-col justify-between items-center md:items-start gap-1">
            <div>
                <h1 class="text-lg font-bold tracking-tighter">YOMONO</h1>
                <p class="text-[9px] text-gray-400 uppercase tracking-[0.3em]">Administrator</p>
            </div>
        </div>
        <nav class="px-4 md:px-6 pb-6 md:py-4 flex flex-row md:flex-col flex-wrap gap-2 md:space-y-2 text-[10px] md:text-[11px] uppercase tracking-[0.2em] font-medium text-gray-500 w-full">
            <a href="{{ route('admin.dashboard') }}" class="inline-block md:block py-2.5 md:py-3 px-4 hover:bg-gray-50 transition rounded-sm border border-transparent">Dashboard</a>
            <a href="{{ route('admin.products.index') }}" class="inline-block md:block py-2.5 md:py-3 px-4 hover:bg-gray-50 transition rounded-sm border border-transparent">Manage Products</a>
            <a href="{{ route('admin.orders.index') }}" class="inline-block md:block py-2.5 md:py-3 px-4 bg-black text-white rounded-sm">Transactions</a>
            
            <form action="{{ route('logout') }}" method="POST" class="inline-block md:block w-full">
                @csrf
                <button class="w-full text-left py-2.5 md:py-3 px-4 text-red-400 hover:text-red-600 transition uppercase font-medium">Logout</button>
            </form>
        </nav>
    </div>

    {{-- Konten Utama Dinamis --}}
    <div class="flex-1 p-4 sm:p-6 md:p-10">
        
        @if(Route::currentRouteName() === 'admin.orders.show')
            {{-- ============================================================================== --}}
            {{-- TAMPILAN: ORDER DETAILS & PRATINJAU NOTA RESI                                 --}}
            {{-- ============================================================================== --}}
            <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <a href="{{ route('admin.orders.index') }}" class="text-[10px] uppercase tracking-widest text-neutral-400 hover:text-black transition">
                        &larr; Back to Transactions
                    </a>
                    <h2 class="text-2xl font-light tracking-tight text-gray-900 mt-2">Order Details #{{ $order->id }}</h2>
                    <p class="text-[10px] text-gray-400 uppercase tracking-widest mt-0.5">Manage single transaction item</p>
                </div>
                <button onclick="printReceipt()" class="bg-black text-white px-5 py-2.5 text-[10px] uppercase tracking-widest font-bold hover:bg-neutral-800 transition rounded-none self-start sm:self-center shadow-sm">
                    🖨️ Print Invoice
                </button>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                {{-- Sisi Kiri: Detail Data Informasi Pelanggan (5 Kolom) --}}
                <div class="space-y-6 lg:col-span-5">
                    <div class="bg-white p-6 border border-gray-100 shadow-sm rounded-none">
                        <h4 class="text-[10px] font-bold uppercase tracking-widest text-neutral-400 mb-4 pb-2 border-b border-neutral-100">Customer & Shipping Information</h4>
                        <div class="text-[11px] space-y-3 text-neutral-700 uppercase tracking-wider">
                            <p><strong class="text-neutral-900 inline-block w-24">Name:</strong> <span class="normal-case text-neutral-600">{{ $order->user->name }}</span></p>
                            <p><strong class="text-neutral-900 inline-block w-24">Email:</strong> <span class="normal-case text-neutral-600">{{ $order->user->email }}</span></p>
                            <p><strong class="text-neutral-900 inline-block w-24">Phone:</strong> <span class="normal-case text-neutral-600">{{ $order->phone ?? '-' }}</span></p>
                            <p><strong class="text-neutral-900 block mb-1">Shipping Address:</strong> <span class="normal-case text-neutral-500 leading-relaxed block bg-neutral-50 p-3 border border-neutral-100 font-mono text-[11px]">{{ $order->shipping_address ?? 'No Address Provided' }}</span></p>
                        </div>
                    </div>

                    <div class="bg-white p-6 border border-gray-100 shadow-sm rounded-none">
                        <h4 class="text-[10px] font-bold uppercase tracking-widest text-neutral-400 mb-4 pb-2 border-b border-neutral-100">Order Status & Delivery</h4>
                        <div class="space-y-4 text-[11px]">
                            <div class="flex justify-between items-center uppercase tracking-wider">
                                <span class="text-neutral-400">Payment Status:</span>
                                <span class="px-2.5 py-0.5 text-[9px] font-bold tracking-widest uppercase {{ $order->payment_status == 'paid' ? 'bg-green-50 text-green-600' : 'bg-orange-50 text-orange-600' }}">
                                    {{ $order->payment_status }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center uppercase tracking-wider">
                                <span class="text-neutral-400">Total Amount:</span>
                                <span class="font-bold text-neutral-900">IDR {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                            </div>
                            
                            {{-- FITUR BARU: Menampilkan Status Logistik Saat Ini --}}
                            <div class="flex justify-between items-center uppercase tracking-wider pt-1">
                                <span class="text-neutral-400">Delivery Status:</span>
                                <span class="font-bold text-neutral-900 tracking-widest text-[10px]">
                                    @if(($order->status ?? 'pending') == 'pending')
                                        ⏳ DIPROSES
                                    @elseif($order->status == 'shipped')
                                        📦 DIKIRIM
                                    @elseif($order->status == 'completed')
                                        ✅ SELESAI
                                    @else
                                        {{ strtoupper($order->status) }}
                                    @endif
                                </span>
                            </div>

                            <div class="pt-2 space-y-2">
                                {{-- Tombol Baru Pengatur Status Mengambang (Modal) --}}
                                <button type="button" onclick="toggleTrackingModal(true)" class="w-full border border-black text-black hover:bg-neutral-50 py-2.5 text-[10px] uppercase tracking-widest font-bold transition text-center block">
                                    🚚 Track Status
                                </button>

                                @if($order->payment_status !== 'paid')
                                    <form action="{{ route('admin.orders.confirm', $order->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white py-2.5 text-[10px] uppercase tracking-widest font-bold transition rounded-none">
                                            Approve Payment
                                        </button>
                                    </form>
                                @else
                                    <button disabled class="w-full bg-neutral-50 text-neutral-400 py-2.5 text-[10px] uppercase tracking-widest font-bold rounded-none cursor-not-allowed border border-neutral-200 text-center block">
                                        Paid & Confirmed
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Sisi Kanan: Kertas Preview Resi Nota Belanja Minimalis Yomono (7 Kolom) --}}
                <div class="lg:col-span-7">
                    <div id="receipt-print-area" class="bg-white p-8 sm:p-10 border border-neutral-200 shadow-sm rounded-none text-black font-mono mx-auto max-w-xl">
                        {{-- Header Toko --}}
                        <div class="text-center border-b border-dashed border-neutral-400 pb-4 mb-6">
                            <h3 class="text-lg font-bold tracking-widest">YOMONO STORE</h3>
                            <p class="text-[9px] uppercase tracking-widest text-neutral-400 mt-0.5">Minimalist Ready-To-Wear</p>
                            <p class="text-[9px] text-neutral-400 mt-2">Jakarta, Indonesia</p>
                        </div>

                        {{-- Metadata Informasi Invoice --}}
                        <div class="space-y-1 text-[11px] mb-6 pb-4 border-b border-neutral-100 uppercase tracking-wider">
                            <div class="flex justify-between">
                                <span class="text-neutral-400">Invoice No:</span>
                                <span class="font-bold">#INV/{{ $order->created_at->format('Ymd') }}/{{ $order->id }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-neutral-400">Date/Time:</span>
                                <span>{{ $order->created_at->format('d MY, H:i') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-neutral-400">Customer:</span>
                                <span class="font-bold">{{ $order->user->name }}</span>
                            </div>
                        </div>

                        {{-- Tabel Daftar Item Pembelian --}}
                        <div class="mb-6">
                            <div class="text-[9px] uppercase tracking-widest font-bold text-neutral-400 border-b border-neutral-900 pb-1 mb-2 flex justify-between">
                                <span class="w-3/5">Item Description</span>
                                <span class="w-1/5 text-center">Qty</span>
                                <span class="w-1/5 text-right">Price</span>
                            </div>
                            <div class="text-[11px] space-y-3 divide-y divide-neutral-100">
                                @if($order->orderItems && $order->orderItems->count() > 0)
                                    @foreach($order->orderItems as $item)
                                        <div class="flex justify-between pt-2.5 items-start">
                                            <div class="w-3/5 uppercase font-medium text-neutral-800 tracking-wide leading-tight">
                                                <div>{{ $item->variant->product->name ?? 'Product Item' }}</div>
                                                @if(isset($item->variant->color) || isset($item->variant->size))
                                                    <div class="text-[9px] text-neutral-400 lowercase tracking-normal mt-0.5">
                                                        ({{ $item->variant->color->name ?? '' }} / {{ $item->variant->size->name ?? '' }})
                                                    </div>
                                                @endif
                                            </div>
                                            <span class="w-1/5 text-center text-neutral-500 font-bold">{{ $item->quantity }}</span>
                                            <span class="w-1/5 text-right text-neutral-900">IDR {{ number_format($item->price, 0, ',', '.') }}</span>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="flex justify-between pt-2">
                                        <span class="w-3/5 uppercase font-medium text-neutral-800">YOMONO APPAREL GOODS</span>
                                        <span class="w-1/5 text-center text-neutral-500">1</span>
                                        <span class="w-1/5 text-right text-neutral-900">IDR {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Ringkasan Total Biaya Belanja --}}
                        <div class="border-t border-dashed border-neutral-400 pt-4 space-y-1.5 text-[11px] uppercase tracking-wider">
                            <div class="flex justify-between text-neutral-500">
                                <span>Subtotal</span>
                                <span>IDR {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-neutral-500">
                                <span>Shipping Fees</span>
                                <span class="text-[9px] tracking-widest font-bold text-neutral-900">FREE SHIPPING</span>
                            </div>
                            <div class="flex justify-between font-bold text-sm text-neutral-900 pt-2 border-t border-neutral-200">
                                <span>Grand Total</span>
                                <span>IDR {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        {{-- Catatan Kaki Nota --}}
                        <div class="text-center mt-8 pt-4 border-t border-neutral-100 text-[9px] uppercase tracking-[0.15em] text-neutral-400">
                            <p>Thank you for shopping with us.</p>
                            <p class="mt-0.5 font-bold text-neutral-800">www.yomono.id</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- MODAL MENGAMBANG: TRACKING & KONFIRMASI STATUS LOGISTIK --}}
            <div id="trackingModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
                {{-- Backdrop Transparan Gelap --}}
                <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="toggleTrackingModal(false)"></div>
                
                {{-- Card Konten Utama Modal --}}
                <div class="bg-white border border-neutral-200 shadow-2xl max-w-md w-full relative z-10 p-6 sm:p-8 rounded-none transition-transform duration-300">
                    <div class="flex justify-between items-start border-b border-neutral-100 pb-3 mb-6">
                        <div>
                            <h3 class="text-sm font-bold uppercase tracking-widest text-neutral-900">Update Order Tracking</h3>
                            <p class="text-[10px] text-gray-400 uppercase mt-0.5 font-mono">Order Reference: #{{ $order->id }}</p>
                        </div>
                        <button type="button" onclick="toggleTrackingModal(false)" class="text-neutral-400 hover:text-black text-sm uppercase font-bold tracking-wider">&times; Close</button>
                    </div>

                    {{-- Alur Timeline Status Visual Statis --}}
                    <div class="flex items-center justify-between px-2 mb-8 text-center font-mono">
                        <div class="flex-1">
                            <div class="w-7 h-7 mx-auto rounded-full flex items-center justify-center text-xs font-bold {{ in_array($order->status ?? 'pending', ['pending','shipped','completed']) ? 'bg-black text-white' : 'bg-gray-100 text-gray-400' }}">1</div>
                            <p class="text-[9px] uppercase tracking-wider font-bold mt-2 {{ ($order->status ?? 'pending') == 'pending' ? 'text-black font-extrabold' : 'text-neutral-400' }}">Diproses</p>
                        </div>
                        <div class="h-[2px] bg-neutral-200 flex-1 -mt-4"></div>
                        <div class="flex-1">
                            <div class="w-7 h-7 mx-auto rounded-full flex items-center justify-center text-xs font-bold {{ in_array($order->status ?? 'pending', ['shipped','completed']) ? 'bg-black text-white' : 'bg-gray-100 text-gray-400' }}">2</div>
                            <p class="text-[9px] uppercase tracking-wider font-bold mt-2 {{ ($order->status ?? 'pending') == 'shipped' ? 'text-black font-extrabold' : 'text-neutral-400' }}">Dikirim</p>
                        </div>
                        <div class="h-[2px] bg-neutral-200 flex-1 -mt-4"></div>
                        <div class="flex-1">
                            <div class="w-7 h-7 mx-auto rounded-full flex items-center justify-center text-xs font-bold {{ ($order->status ?? 'pending') == 'completed' ? 'bg-black text-white' : 'bg-gray-100 text-gray-400' }}">3</div>
                            <p class="text-[9px] uppercase tracking-wider font-bold mt-2 {{ ($order->status ?? 'pending') == 'completed' ? 'text-black font-extrabold' : 'text-neutral-400' }}">Selesai</p>
                        </div>
                    </div>

                    {{-- Form Submit ke Backend --}}
                    <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PATCH')
                        <div>
                            <label class="block text-[9px] font-bold uppercase tracking-widest text-neutral-400 mb-2">Select Delivery Milestone</label>
                            <select name="status" class="w-full border border-neutral-200 bg-white p-3 text-[11px] font-mono tracking-wide uppercase focus:outline-none focus:border-black rounded-none">
                                <option value="pending" {{ ($order->status ?? 'pending') == 'pending' ? 'selected' : '' }}>⏳ DIPROSES (Dalam Antrean / Dikemas Toko)</option>
                                <option value="shipped" {{ ($order->status ?? 'pending') == 'shipped' ? 'selected' : '' }}>📦 DIKIRIM (Sedang Dalam Perjalanan Kurir)</option>
                                <option value="completed" {{ ($order->status ?? 'pending') == 'completed' ? 'selected' : '' }}>✅ SELESAI (Paket Sudah Diterima Customer)</option>
                            </select>
                        </div>

                        <div class="pt-4 flex gap-2">
                            <button type="button" onclick="toggleTrackingModal(false)" class="w-1/3 border border-neutral-200 text-neutral-500 hover:text-black py-2.5 text-[10px] uppercase tracking-widest font-bold transition rounded-none">
                                Cancel
                            </button>
                            <button type="submit" class="w-2/3 bg-black hover:bg-neutral-800 text-white py-2.5 text-[10px] uppercase tracking-widest font-bold transition rounded-none shadow-sm">
                                Save Milestone
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Script Cetak & Modal Terisolasi --}}
            <script>
                function printReceipt() {
                    var printContents = document.getElementById('receipt-print-area').innerHTML;
                    var originalContents = document.body.innerHTML;

                    document.body.innerHTML = "<html><head><title>Print_Invoice_#{{ $order->id }}</title></head><body style='background:#white; padding:30px;'>" + printContents + "</body></html>";
                    window.print();
                    document.body.innerHTML = originalContents;
                    window.location.reload();
                }

                // Fungsi Buka-Tutup Modal Mengambang
                function toggleTrackingModal(show) {
                    const modal = document.getElementById('trackingModal');
                    if (show) {
                        modal.classList.remove('hidden');
                    } else {
                        modal.classList.add('hidden');
                    }
                }
            </script>

        @else
            {{-- ============================================================================== --}}
            {{-- TAMPILAN: DAFTAR SELURUH TRANSAKSI UTAMA                                      --}}
            {{-- ============================================================================== --}}
            <div class="mb-10">
                <h2 class="text-2xl md:text-3xl font-light tracking-tight text-gray-900">Transactions</h2>
                <p class="text-[10px] sm:text-xs text-gray-400 uppercase tracking-widest mt-1">Track and manage customer orders</p>
            </div>

            <div class="bg-white border border-gray-100 shadow-sm rounded-none overflow-hidden">
                <div class="w-full overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[700px]">
                        <thead class="bg-gray-50 text-[10px] uppercase tracking-widest text-gray-400 border-b border-gray-100">
                            <tr>
                                <th class="p-4 font-medium">Order ID</th>
                                <th class="p-4 font-medium">Date</th>
                                <th class="p-4 font-medium">Customer</th>
                                <th class="p-4 font-medium">Total Amount</th>
                                <th class="p-4 font-medium">Payment Status</th>
                                <th class="p-4 font-medium text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-[11px] tracking-wide text-gray-600 divide-y divide-gray-50">
                            @forelse($orders as $o)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="p-4 font-medium text-black">#{{ $o->id }}</td>
                                <td class="p-4">{{ $o->created_at->format('d M Y') }}</td>
                                <td class="p-4">
                                    <div class="font-bold text-neutral-800 uppercase text-[10px]">{{ $o->user->name }}</div>
                                    <div class="text-[9px] text-neutral-400 normal-case">{{ $o->user->email }}</div>
                                </td>
                                <td class="p-4 font-medium text-neutral-900">IDR {{ number_format($o->total_amount, 0, ',', '.') }}</td>
                                <td class="p-4">
                                    <span class="px-2.5 py-1 rounded-full text-[9px] font-bold tracking-wider inline-block {{ $o->payment_status == 'paid' ? 'bg-green-50 text-green-600' : 'bg-orange-50 text-orange-600' }}">
                                        {{ strtoupper($o->payment_status) }}
                                    </span>
                                </td>
                                <td class="p-4 text-right">
                                    <a href="{{ route('admin.orders.show', $o->id) }}" class="bg-black text-white px-3 py-1.5 text-[9px] font-bold tracking-widest hover:bg-neutral-800 transition">
                                        MANAGE
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="p-12 text-center text-gray-400 italic">No customer orders available.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

    </div>
</div>
@endsection