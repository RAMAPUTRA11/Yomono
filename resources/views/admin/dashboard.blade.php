@extends('layouts.app')

@section('content')
<div class="flex flex-col md:flex-row min-h-screen bg-[#fcfcfc]">
    {{-- Sidebar Admin Kontainer (Desktop & Mobile-Safe Link) --}}
    <div class="w-full md:w-64 bg-white border-b md:border-b-0 md:border-r border-gray-100">
        <div class="p-6 md:p-8 flex flex-row md:flex-col justify-between items-center md:items-start gap-1">
            <div>
                <h1 class="text-lg font-bold tracking-tighter">YOMONO</h1>
                <p class="text-[9px] text-gray-400 uppercase tracking-[0.3em]">Administrator</p>
            </div>
            {{-- Tombol bantuan navigasi cepat untuk mobile view --}}
            <span class="text-[9px] md:hidden bg-gray-50 text-gray-500 px-2 py-1 uppercase tracking-widest border border-gray-200">Panel</span>
        </div>
        <nav class="px-4 md:px-6 pb-6 md:py-4 flex flex-row md:flex-col flex-wrap gap-2 md:space-y-2 text-[10px] md:text-[11px] uppercase tracking-[0.15em] md:tracking-[0.2em] font-medium text-gray-500 overflow-x-auto no-scrollbar whitespace-nowrap">
            <a href="{{ route('admin.dashboard') }}" class="inline-block md:block py-2.5 md:py-3 px-4 bg-black text-white rounded-sm">Dashboard</a>
            <a href="{{ route('admin.products.index') }}" class="inline-block md:block py-2.5 md:py-3 px-4 hover:bg-gray-50 transition rounded-sm border border-transparent hover:border-gray-100">Manage Products</a>
            
            {{-- FITUR NOTIFIKASI: Menampilkan badge jumlah pesanan unpaid (baru masuk) di samping menu --}}
            <a href="{{ route('admin.orders.index') }}" class="inline-block md:flex items-center justify-between py-2.5 md:py-3 px-4 hover:bg-gray-50 transition rounded-sm border border-transparent hover:border-gray-100">
                <span>Transactions</span>
                @php
                    $unreadOrders = $orders->where('payment_status', 'unpaid')->count();
                @endphp
                @if($unreadOrders > 0)
                    <span class="ml-2 bg-red-500 text-white text-[8px] font-bold px-1.5 py-0.5 rounded-full animate-pulse">
                        {{ $unreadOrders }} NEW
                    </span>
                @endif
            </a>
            
            <form action="{{ route('logout') }}" method="POST" class="inline-block md:block">
                @csrf
                <button class="w-full text-left py-2.5 md:py-3 px-4 text-red-400 hover:text-red-600 transition uppercase font-medium">Logout</button>
            </form>
        </nav>
    </div>

    {{-- Konten Utama Panel --}}
    <div class="flex-1 p-4 sm:p-6 md:p-10">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-end gap-4 mb-10 md:mb-12 border-b sm:border-b-0 border-gray-100 pb-4 sm:pb-0">
            <div>
                <h2 class="text-2xl md:text-3xl font-light tracking-tight text-gray-900">Dashboard</h2>
                <p class="text-[10px] sm:text-xs text-gray-400 uppercase tracking-widest mt-1 sm:mt-2">Overview of your store activity</p>
            </div>
            <div class="text-left sm:text-right bg-gray-50 sm:bg-transparent p-3 sm:p-0 rounded-sm">
                <p class="text-[9px] sm:text-[10px] text-gray-400 uppercase tracking-widest">Server Time</p>
                <p class="text-xs sm:text-sm font-medium text-gray-800">{{ date('d M Y, H:i') }}</p>
            </div>
        </div>

        {{-- Metrik Widget Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 md:gap-8 mb-10 md:mb-12">
            <div class="bg-white p-6 sm:p-8 border border-gray-100 shadow-sm rounded-sm">
                <p class="text-[9px] sm:text-[10px] uppercase tracking-[0.2em] sm:tracking-[0.3em] text-gray-400 mb-2">Total Sales</p>
                <h3 class="text-xl sm:text-2xl font-light text-gray-900">IDR {{ number_format($stats['total_sales'] ?? 0, 0, ',', '.') }}</h3>
            </div>
            <div class="bg-white p-6 sm:p-8 border border-gray-100 shadow-sm rounded-sm">
                <p class="text-[9px] sm:text-[10px] uppercase tracking-[0.2em] sm:tracking-[0.3em] text-gray-400 mb-2">Active Orders</p>
                <h3 class="text-xl sm:text-2xl font-light text-gray-900">{{ $stats['total_orders'] ?? $orders->count() }}</h3>
            </div>
            <div class="bg-white p-6 sm:p-8 border border-gray-100 shadow-sm rounded-sm sm:col-span-2 lg:col-span-1">
                <p class="text-[9px] sm:text-[10px] uppercase tracking-[0.2em] sm:tracking-[0.3em] text-gray-400 mb-2">Total Products</p>
                <h3 class="text-xl sm:text-2xl font-light text-gray-900">{{ $stats['total_products'] ?? \App\Models\Product::count() }}</h3>
            </div>
        </div>

        {{-- Tabel Transaksi Terbaru --}}
        <div class="bg-white border border-gray-100 shadow-sm rounded-sm overflow-hidden">
            <div class="p-4 sm:p-6 border-b border-gray-50 flex justify-between items-center">
                <h4 class="text-[10px] sm:text-[11px] font-bold uppercase tracking-widest text-gray-800">Recent Transactions</h4>
                <a href="{{ route('admin.orders.index') }}" class="text-[9px] sm:text-[10px] uppercase tracking-widest border-b border-black pb-0.5 hover:text-gray-500 hover:border-gray-400 transition">View All</a>
            </div>
            <div class="w-full overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[600px]">
                    <thead class="bg-gray-50 text-[10px] uppercase tracking-widest text-gray-400 border-b border-gray-100">
                        <tr>
                            <th class="p-4 sm:p-6 font-medium">Order ID</th>
                            <th class="p-4 sm:p-6 font-medium">Customer</th>
                            <th class="p-4 sm:p-6 font-medium">Date</th>
                            <th class="p-4 sm:p-6 font-medium">Status</th>
                            <th class="p-4 sm:p-6 font-medium text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="text-[11px] tracking-wide text-gray-600 divide-y divide-gray-50">
                        @forelse($orders as $order)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="p-4 sm:p-6 font-medium text-black">#{{ $order->id }}</td>
                            <td class="p-4 sm:p-6">{{ $order->user->name }}</td>
                            <td class="p-4 sm:p-6">{{ $order->created_at->format('d/m/Y') }}</td>
                            <td class="p-4 sm:p-6">
                                <span class="px-2.5 py-1 rounded-full text-[8px] sm:text-[9px] font-bold tracking-wider inline-block {{ $order->payment_status == 'paid' ? 'bg-green-50 text-green-600' : 'bg-orange-50 text-orange-600' }}">
                                    {{ strtoupper($order->payment_status) }}
                                </span>
                            </td>
                            <td class="p-4 sm:p-6 text-right">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="text-black font-bold border-b border-black text-[10px] tracking-wider hover:text-gray-500 hover:border-gray-400 transition">
                                    DETAILS
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="p-12 text-center text-gray-400 italic tracking-wider normal-case">No transactions found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    /* Utility CSS tambahan untuk menyembunyikan scrollbar menu navigasi mobile */
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

    /* Penyelarasan tema kustom SweetAlert2 dengan gaya minimalis YOMONO */
    .yomono-swal-popup {
        font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
        border-radius: 0px !important; /* Kotak siku tegas polos */
    }
    .yomono-swal-toast {
        font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
        border-radius: 0px !important;
    }
</style>

{{-- Memuat Library Instan SweetAlert2 dari CDN Resmi --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    window.addEventListener('DOMContentLoaded', function() {
        
        // 1. MODAL NOTIFIKASI GENERIK JIKA TRANSAKSI SUKSES (TOAST)
        @if(session('success'))
            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 3500,
                timerProgressBar: true,
                customClass: { popup: 'yomono-swal-toast' },
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                }
            });
            Toast.fire({
                icon: "success",
                title: "<span class='text-[11px] uppercase tracking-wider font-semibold text-neutral-800'>{{ session('success') }}</span>"
            });
        @endif

        // 2. MODAL NOTIFIKASI ERROR ATAU CANCELLED
        @if(session('error'))
            Swal.fire({
                icon: "error",
                title: "<span class='text-xs uppercase tracking-widest font-bold text-red-600'>Transaksi Dibatalkan</span>",
                html: "<p class='text-[11px] uppercase tracking-wide text-neutral-500'>{{ session('error') }}</p>",
                confirmButtonText: "TUTUP",
                confirmButtonColor: "#000000",
                customClass: { popup: 'yomono-swal-popup' }
            });
        @endif

        // 3. FITUR POP-UP WINDOWS CETAK RESI OTOMATIS BERGAYA PRESTISIUS
        @if(session('print_receipt_id'))
            var printUrl = "{{ route('admin.orders.print', session('print_receipt_id')) }}"; 
            var printWindow = window.open(printUrl, '_blank');
            
            if (printWindow) {
                printWindow.onload = function() {
                    printWindow.print();
                };
            } else {
                // Tampilan peringatan kegagalan pop-up interaktif menggantikan alert browser jadul
                Swal.fire({
                    icon: "warning",
                    title: "<span class='text-xs uppercase tracking-widest font-bold text-amber-600'>Pop-up Blocker Aktif</span>",
                    html: "<div class='text-left text-[11px] text-neutral-500 space-y-2 uppercase tracking-wide leading-relaxed'>" +
                            "<p>Sistem gagal membuka dokumen resi cetak secara otomatis karena diblokir browser.</p>" +
                            "<p class='font-semibold text-neutral-800 text-[10px] bg-neutral-50 p-2 border border-neutral-200 mt-2 rounded-none'>" +
                                "Solusi: Izinkan hak akses 'Pop-ups and redirects' pada setelan browser Anda, kemudian muat ulang halaman ini." +
                            "</p>" +
                          "</div>",
                    confirmButtonText: "SAYA PAHAM",
                    confirmButtonColor: "#000000",
                    customClass: { popup: 'yomono-swal-popup' }
                });
            }
        @endif
    });
</script>
@endsection