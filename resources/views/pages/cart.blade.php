@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-6 py-24">
    <div class="mb-12">
        <h1 class="text-2xl font-light tracking-[0.3em] uppercase text-gray-900">Shopping Cart</h1>
        <div class="h-[1px] w-12 bg-black mt-4"></div>
    </div>

    @if($cartItems->isEmpty())
        <div class="text-center py-24 border border-dashed border-gray-200">
            <p class="text-[12px] text-gray-400 uppercase tracking-widest mb-6">Your cart is currently empty.</p>
            <a href="{{ url('/shop') }}" class="inline-block bg-black text-white px-8 py-4 text-[11px] uppercase tracking-[0.2em] font-bold hover:bg-gray-800 transition">
                Continue Shopping
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 items-start">
            {{-- DAFTAR PRODUK --}}
            <div class="lg:col-span-2 overflow-x-auto">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead>
                        <tr class="border-b border-gray-100 text-[10px] uppercase tracking-widest text-gray-400">
                            <th class="pb-4 font-bold">Product</th>
                            <th class="pb-4 text-center font-bold">Quantity</th>
                            <th class="pb-4 text-right font-bold">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-y-gray-50">
                        @foreach($cartItems as $item)
                        @if($item->variant && $item->variant->product)
                        <tr>
                            {{-- Detail Produk --}}
                            <td class="py-6 flex items-center whitespace-normal">
                                <img src="{{ asset('storage/' . ($item->variant->image ?? $item->variant->product->image)) }}" 
                                     alt="Product Image" class="w-20 h-24 object-cover mr-6 bg-gray-50">
                                <div>
                                    <h3 class="text-[12px] font-bold uppercase tracking-wider text-gray-900 mb-1">
                                        {{ $item->variant->product->name }}
                                    </h3>
                                    <p class="text-[10px] text-gray-400 uppercase tracking-widest mb-1">
                                        Size/Color: {{ $item->variant->size->name ?? 'N/A' }} — {{ $item->variant->color->name ?? 'N/A' }}
                                    </p>
                                    <p class="text-[11px] text-gray-600 tracking-wide">
                                        Rp {{ number_format($item->variant->product->price, 0, ',', '.') }}
                                    </p>
                                </div>
                            </td>
                            
                            {{-- Kontrol Kuantitas --}}
                            <td class="py-6 text-center">
                                <div class="inline-flex items-center border border-gray-200">
                                    <form action="{{ route('cart.update', $item->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="action" value="decrease">
                                        <button type="submit" class="w-8 h-8 text-sm font-light hover:bg-gray-50 transition">-</button>
                                    </form>
                                    
                                    <span class="w-10 text-center text-[12px] font-bold text-gray-900">{{ $item->quantity }}</span>
                                    
                                    <form action="{{ route('cart.update', $item->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="action" value="increase">
                                        <button type="submit" class="w-8 h-8 text-sm font-light hover:bg-gray-50 transition">+</button>
                                    </form>
                                </div>
                                <div class="mt-2">
                                    <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-[9px] text-gray-400 hover:text-red-500 underline uppercase tracking-widest transition">
                                            Remove
                                        </button>
                                    </form>
                                </div>
                            </td>
                            
                            {{-- Total Harga Item --}}
                            <td class="py-6 text-right text-[12px] font-bold text-gray-900 tracking-wide">
                                Rp {{ number_format($item->variant->product->price * $item->quantity, 0, ',', '.') }}
                            </td>
                        </tr>
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- ORDER SUMMARY --}}
            <div class="border border-gray-100 p-8 bg-white">
                <h2 class="text-[12px] font-bold uppercase tracking-[0.2em] text-gray-900 mb-6">Order Summary</h2>
                
                @php
                    $total = $cartItems->sum(function($i) {
                        return ($i->variant->product->price ?? 0) * $i->quantity;
                    });
                @endphp

                <div class="space-y-4 border-b border-gray-100 pb-6 text-[11px] uppercase tracking-widest text-gray-600">
                    <div class="flex justify-between">
                        <span>Subtotal</span>
                        <span class="font-bold text-gray-900">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Shipping</span>
                        <span class="text-gray-400">Calculated at next step</span>
                    </div>
                </div>
                
                <div class="flex justify-between items-baseline py-6 mb-2">
                    <span class="text-[11px] uppercase tracking-[0.2em] font-bold text-gray-900">Estimated Total</span>
                    <span class="text-lg font-bold text-gray-900 tracking-wide">Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>
                
                <a href="{{ route('checkout') }}" class="block text-center w-full bg-black text-white py-4 text-[11px] uppercase tracking-[0.2em] font-bold hover:bg-gray-800 transition active:scale-[0.99]">
                    Proceed to Checkout
                </a>
            </div>
        </div>
    @endif
</div>
@endsection