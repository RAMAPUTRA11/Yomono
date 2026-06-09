@extends('layouts.app')

@section('content')
<div class="max-w-[1200px] mx-auto px-6 py-24">
    <h1 class="text-2xl font-light tracking-[0.4em] uppercase mb-16 text-center text-gray-900">Checkout</h1>

    <form action="{{ route('order.checkout') }}" method="POST" id="checkout-form">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            
            {{-- SISI KIRI: SHIPPING & PAYMENT METHOD --}}
            <div class="lg:col-span-2 space-y-14">
                
                {{-- Shipping Information --}}
                <section>
                    <div class="flex justify-between items-center mb-6 border-b border-gray-100 pb-2">
                        <h2 class="text-[12px] font-bold uppercase tracking-[0.2em] text-gray-900">Shipping Information</h2>
                        
                        {{-- Tombol Akses Cepat Tambah Alamat Baru --}}
                        @if(isset($addresses) && !$addresses->isEmpty())
                            <a href="{{ route('profile.edit') }}" class="text-[10px] font-bold uppercase tracking-wider text-gray-400 hover:text-black border-b border-transparent hover:border-black transition pb-0.5">
                                + Tambah Alamat Baru
                            </a>
                        @endif
                    </div>
                    
                    {{-- Kondisi Jika User Belum Memiliki Alamat Tersimpan --}}
                    @if(!isset($addresses) || $addresses->isEmpty())
                        <div class="border border-dashed border-gray-200 p-8 text-center bg-white">
                            <p class="text-[11px] text-gray-400 uppercase tracking-widest mb-4">Anda belum mendaftarkan alamat pengiriman.</p>
                            <a href="{{ route('profile.edit') }}" class="inline-block border border-black px-5 py-3 text-[10px] uppercase tracking-widest font-bold hover:bg-black hover:text-white transition">
                                + Tambah Alamat di Pengaturan
                            </a>
                        </div>
                    @else
                        {{-- FIX: Mengubah label menjadi div untuk menghindari double-click bug pada radio button --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            @foreach($addresses as $addr)
                                <div id="card-{{ $addr->id }}" class="border border-gray-200 p-5 cursor-pointer relative bg-white transition hover:bg-gray-50/50 flex flex-col justify-between min-h-[160px] address-card group">
                                    
                                    {{-- Radio Button --}}
                                    <div class="absolute top-5 right-5">
                                        <input type="radio" name="address_id" value="{{ $addr->id }}" 
                                               class="accent-black address-radio w-4 h-4 cursor-pointer"
                                               data-name="{{ $addr->receiver_name }}" 
                                               data-phone="{{ $addr->phone_number }}" 
                                               data-address="{{ $addr->full_address }}"
                                               {{ $addr->is_default ? 'checked' : '' }} required>
                                    </div>

                                    <div>
                                        <div class="flex items-center gap-2 mb-2">
                                            <span class="text-[10px] font-bold uppercase tracking-widest text-gray-900">[{{ $addr->label }}]</span>
                                            @if($addr->is_default)
                                                <span class="bg-gray-100 text-gray-600 text-[8px] font-bold px-1.5 py-0.5 uppercase tracking-wider">Utama</span>
                                            @endif
                                        </div>
                                        <p class="text-[12px] font-bold text-gray-800 mb-0.5 uppercase tracking-wide">{{ $addr->receiver_name }}</p>
                                        <p class="text-[11px] text-gray-400 tracking-wide mb-3">{{ $addr->phone_number }}</p>
                                    </div>

                                    <p class="text-[11px] text-gray-500 leading-relaxed border-t border-gray-50 pt-3">
                                        {{ $addr->full_address }}
                                    </p>
                                </div>
                            @endforeach
                        </div>

                        {{-- Input Hidden Global: Payload Utama yang Dikirim ke Controller --}}
                        <input type="hidden" id="shipping_address" name="address">
                    @endif
                </section>

                {{-- Payment Method --}}
                <section x-data="{ selectedMethod: 'qris' }">
                    <h2 class="text-[12px] font-bold uppercase tracking-[0.2em] mb-6 border-b border-gray-100 pb-2 text-gray-900">Payment Method</h2>
                    <div class="space-y-4">
                        
                        {{-- QRIS Option --}}
                        <label class="flex items-center justify-between p-5 border cursor-pointer hover:bg-gray-50/50 transition"
                               :class="selectedMethod === 'qris' ? 'border-black bg-white' : 'border-gray-100 bg-white'">
                            <div class="flex items-center gap-4">
                                <input type="radio" name="payment_method" value="qris" x-model="selectedMethod" class="accent-black" required>
                                <div>
                                    <span class="block text-[12px] font-bold uppercase tracking-wider text-gray-900">QRIS (Otomatis)</span>
                                    <span class="text-[10px] text-gray-400 tracking-wide block mt-0.5">Dana, OVO, GoPay, ShopeePay, or Mobile Banking Scan</span>
                                </div>
                            </div>
                            <img src="https://upload.wikimedia.org/wikipedia/commons/a/a2/Logo_QRIS.svg" alt="QRIS" class="h-4 grayscale opacity-80">
                        </label>

                        {{-- Virtual Account Options Container --}}
                        <div class="border border-gray-100 p-5 bg-white">
                            <span class="block text-[10px] font-bold uppercase tracking-[0.2em] mb-4 text-gray-400">Virtual Account Bank</span>
                            <div class="space-y-4">
                                
                                <label class="flex items-center justify-between cursor-pointer group" @click="selectedMethod = 'va_bca'">
                                    <div class="flex items-center gap-4">
                                        <input type="radio" name="payment_method" value="va_bca" x-model="selectedMethod" class="accent-black">
                                        <span class="text-[12px] uppercase tracking-wide text-gray-700 group-hover:text-black transition-colors" :class="selectedMethod === 'va_bca' ? 'font-bold text-black' : ''">BCA Virtual Account</span>
                                    </div>
                                    <span class="text-[9px] border border-gray-200 px-2 py-0.5 font-bold uppercase text-gray-400 tracking-widest">BCA</span>
                                </label>
                                
                                <label class="flex items-center justify-between cursor-pointer group" @click="selectedMethod = 'va_mandiri'">
                                    <div class="flex items-center gap-4">
                                        <input type="radio" name="payment_method" value="va_mandiri" x-model="selectedMethod" class="accent-black">
                                        <span class="text-[12px] uppercase tracking-wide text-gray-700 group-hover:text-black transition-colors" :class="selectedMethod === 'va_mandiri' ? 'font-bold text-black' : ''">Mandiri Virtual Account</span>
                                    </div>
                                    <span class="text-[9px] border border-gray-200 px-2 py-0.5 font-bold uppercase text-gray-400 tracking-widest">Mandiri</span>
                                </label>

                                <label class="flex items-center justify-between cursor-pointer group" @click="selectedMethod = 'va_bni'">
                                    <div class="flex items-center gap-4">
                                        <input type="radio" name="payment_method" value="va_bni" x-model="selectedMethod" class="accent-black">
                                        <span class="text-[12px] uppercase tracking-wide text-gray-700 group-hover:text-black transition-colors" :class="selectedMethod === 'va_bni' ? 'font-bold text-black' : ''">BNI Virtual Account</span>
                                    </div>
                                    <span class="text-[9px] border border-gray-200 px-2 py-0.5 font-bold uppercase text-gray-400 tracking-widest">BNI</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            {{-- SISI KANAN: ORDER SUMMARY --}}
            <div class="bg-white border border-gray-100 p-8 h-fit sticky top-28 shadow-sm">
                <h2 class="text-[11px] font-bold uppercase mb-6 tracking-[0.2em] text-gray-900 border-b border-gray-50 pb-2">Your Order</h2>
                
                {{-- List Item di Keranjang Belanja --}}
                <div class="space-y-4 mb-6 max-h-[260px] overflow-y-auto pr-2 divide-y divide-gray-50">
                    @foreach($cartItems as $item)
                    @if($item->variant && $item->variant->product)
                    <div class="flex justify-between items-start gap-4 pt-3 first:pt-0">
                        <div class="flex gap-4">
                            <div class="w-12 h-16 bg-gray-50 flex-shrink-0 border border-gray-100">
                                <img src="{{ asset('storage/' . ($item->variant->image ?? $item->variant->product->image)) }}" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <h4 class="text-[11px] font-bold uppercase tracking-wide text-gray-900 leading-tight">{{ $item->variant->product->name }}</h4>
                                <p class="text-[10px] text-gray-400 uppercase mt-1 tracking-widest">
                                    Combo: {{ $item->variant->size->name ?? 'N/A' }} / {{ $item->variant->color->name ?? 'N/A' }} <span class="text-gray-900 font-bold ml-1">x{{ $item->quantity }}</span>
                                </p>
                            </div>
                        </div>
                        <span class="text-[11px] font-medium text-gray-900 tracking-wide whitespace-nowrap">
                            IDR {{ number_format($item->variant->product->price * $item->quantity, 0, ',', '.') }}
                        </span>
                    </div>
                    @endif
                    @endforeach
                </div>

                {{-- Kalkulasi Biaya Belanja --}}
                <div class="border-t border-gray-100 pt-6 space-y-4">
                    <div class="flex justify-between text-[11px] text-gray-500 uppercase tracking-widest">
                        <span>Subtotal</span>
                        @php
                            $subtotal = $cartItems->sum(fn($i) => ($i->variant->product->price ?? 0) * $i->quantity);
                        @endphp
                        <span class="text-gray-900">IDR {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-[11px] text-gray-500 uppercase tracking-widest">
                        <span>Shipping</span>
                        <span class="text-black font-bold tracking-wide text-[10px]">Free Shipping</span>
                    </div>
                    <div class="flex justify-between border-t border-gray-100 pt-5 text-[13px] font-bold text-gray-900">
                        <span class="uppercase tracking-[0.2em]">Total</span>
                        <span class="tracking-wide">IDR {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                </div>

                {{-- Button Aksi Checkout --}}
                <div class="mt-8">
                    <button type="submit" class="w-full bg-black text-white py-4 text-[11px] tracking-[0.2em] font-bold uppercase hover:bg-gray-800 transition active:scale-[0.99]">
                        PROCEED TO PAYMENT
                    </button>
                </div>
                
                <p class="text-[9px] text-gray-400 mt-4 leading-relaxed text-center italic tracking-wide">
                    QRIS/VA secure code will be generated securely specifically for this transaction amount via Midtrans.
                </p>
            </div>
        </div>
    </form>
</div>

{{-- JAVASCRIPT: REVISI STABIL DAN AMAN UNTUK CARD SELECTION --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const cards = document.querySelectorAll('.address-card');
        const hiddenAddressInput = document.getElementById('shipping_address');

        function updateSelectedAddressStyle() {
            let selectedRadio = null;

            cards.forEach(card => {
                const radio = card.querySelector('.address-radio');
                if (radio) {
                    if (radio.checked) {
                        card.classList.add('border-black', 'shadow-sm');
                        card.classList.remove('border-gray-200');
                        selectedRadio = radio;
                    } else {
                        card.classList.remove('border-black', 'shadow-sm');
                        card.classList.add('border-gray-200');
                    }
                }
            });

            // Sinkronkan value string alamat ke input hidden untuk dikirim ke Controller
            if (selectedRadio) {
                const name = selectedRadio.getAttribute('data-name');
                const phone = selectedRadio.getAttribute('data-phone');
                const address = selectedRadio.getAttribute('data-address');
                hiddenAddressInput.value = `[${name} | ${phone}] ${address}`;
            }
        }

        // Jalankan event click langsung pada container card demi fleksibilitas UX
        cards.forEach(card => {
            card.addEventListener('click', function (e) {
                const radio = this.querySelector('.address-radio');
                // Jika user mengklik area card di luar radio button itu sendiri, paksa radio jadi checked
                if (e.target !== radio) {
                    radio.checked = true;
                }
                updateSelectedAddressStyle();
            });
        });

        // Inisialisasi awal untuk membaca alamat berstatus default / checked pertama kali
        updateSelectedAddressStyle();
    });
</script>
@endsection