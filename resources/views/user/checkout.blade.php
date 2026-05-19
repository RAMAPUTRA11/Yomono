@extends('layouts.app')

@section('content')
<div class="max-w-[1200px] mx-auto px-6 py-24">
    <h1 class="text-2xl font-bold tracking-[0.2em] uppercase mb-10 text-center">Checkout</h1>

    <form action="{{ route('checkout.process') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            
            <div class="lg:col-span-2 space-y-10">
                
                {{-- Shipping Information --}}
                <section>
                    <h2 class="text-[12px] font-bold uppercase tracking-widest mb-6 border-b pb-2">Shipping Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <input type="text" name="name" value="{{ Auth::user()->name }}" placeholder="Full Name" class="border p-3 text-[13px] outline-none focus:border-black" required>
                        <input type="text" name="phone" placeholder="Phone Number" class="border p-3 text-[13px] outline-none focus:border-black" required>
                        <textarea name="address" placeholder="Full Address" class="border p-3 text-[13px] outline-none focus:border-black md:col-span-2" rows="3" required></textarea>
                    </div>
                </section>

                {{-- Payment Method --}}
                <section>
                    <h2 class="text-[12px] font-bold uppercase tracking-widest mb-6 border-b pb-2">Payment Method</h2>
                    <div class="space-y-4">
                        {{-- QRIS Option --}}
                        <label class="flex items-center justify-between p-4 border cursor-pointer hover:bg-gray-50 transition border-black">
                            <div class="flex items-center gap-3">
                                <input type="radio" name="payment_method" value="qris" class="accent-black" required checked>
                                <div>
                                    <span class="block text-[13px] font-bold uppercase tracking-wider">QRIS</span>
                                    <span class="text-[10px] text-gray-500">Scan via Dana, OVO, GoPay, or Mobile Banking</span>
                                </div>
                            </div>
                            <img src="https://upload.wikimedia.org/wikipedia/commons/a/a2/Logo_QRIS.svg" alt="QRIS" class="h-4">
                        </label>

                        {{-- Virtual Account Options --}}
                        <div class="border p-4">
                            <span class="block text-[11px] font-bold uppercase tracking-widest mb-4 text-gray-400">Virtual Account</span>
                            <div class="space-y-3">
                                <label class="flex items-center justify-between cursor-pointer group">
                                    <div class="flex items-center gap-3">
                                        <input type="radio" name="payment_method" value="va_bca" class="accent-black">
                                        <span class="text-[12px] uppercase group-hover:font-bold">BCA Virtual Account</span>
                                    </div>
                                    <span class="text-[10px] bg-gray-100 px-2 py-1 uppercase">BCA</span>
                                </label>
                                
                                <label class="flex items-center justify-between cursor-pointer group">
                                    <div class="flex items-center gap-3">
                                        <input type="radio" name="payment_method" value="va_mandiri" class="accent-black">
                                        <span class="text-[12px] uppercase group-hover:font-bold">Mandiri Virtual Account</span>
                                    </div>
                                    <span class="text-[10px] bg-gray-100 px-2 py-1 uppercase">Mandiri</span>
                                </label>

                                <label class="flex items-center justify-between cursor-pointer group">
                                    <div class="flex items-center gap-3">
                                        <input type="radio" name="payment_method" value="va_bni" class="accent-black">
                                        <span class="text-[12px] uppercase group-hover:font-bold">BNI Virtual Account</span>
                                    </div>
                                    <span class="text-[10px] bg-gray-100 px-2 py-1 uppercase">BNI</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            {{-- Order Summary --}}
            <div class="bg-gray-50 p-8 h-fit sticky top-24">
                <h2 class="text-[12px] font-bold uppercase mb-6 tracking-widest">Your Order</h2>
                
                <div class="space-y-4 mb-6 max-h-[300px] overflow-y-auto pr-2">
                    @foreach($cartItems as $item)
                    @if($item->variant && $item->variant->product)
                    <div class="flex justify-between items-start gap-4">
                        <div class="flex gap-3">
                            <div class="w-12 h-16 bg-gray-200 flex-shrink-0">
                                <img src="{{ asset('storage/' . ($item->variant->image ?? $item->variant->product->image)) }}" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <h4 class="text-[11px] font-bold uppercase leading-tight">{{ $item->variant->product->name }}</h4>
                                <p class="text-[10px] text-gray-500 uppercase mt-1">
                                    {{ $item->variant->color->name ?? 'N/A' }} / {{ $item->variant->size->name ?? 'N/A' }} (x{{ $item->quantity }})
                                </p>
                            </div>
                        </div>
                        <span class="text-[11px] font-medium">
                            IDR {{ number_format($item->variant->product->price * $item->quantity, 0, ',', '.') }}
                        </span>
                    </div>
                    @endif
                    @endforeach
                </div>

                <div class="border-t border-gray-200 pt-6 space-y-3">
                    <div class="flex justify-between text-[11px] text-gray-500 uppercase">
                        <span>Subtotal</span>
                        @php
                            $subtotal = $cartItems->sum(fn($i) => ($i->variant->product->price ?? 0) * $i->quantity);
                        @endphp
                        <span>IDR {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-[11px] text-gray-500 uppercase">
                        <span>Shipping</span>
                        <span class="text-green-600 font-bold italic">Free Shipping</span>
                    </div>
                    <div class="flex justify-between border-t border-gray-200 pt-4 text-[14px] font-bold">
                        <span class="uppercase tracking-widest">Total</span>
                        <span>IDR {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                </div>

                <button type="submit" class="block w-full bg-black text-white text-center py-4 mt-8 text-[11px] font-bold uppercase tracking-widest hover:bg-gray-800 transition shadow-lg">
                    Generate Payment Info
                </button>
                
                <p class="text-[9px] text-gray-400 mt-4 leading-relaxed text-center italic">
                    QRIS/VA Code will be generated specifically for this transaction amount.
                </p>
            </div>
        </div>
    </form>
</div>
@endsection