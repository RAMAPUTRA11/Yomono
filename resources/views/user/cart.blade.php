@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">SHOPPING CART</h1>

    @if($cartItems->isEmpty())
        <div class="text-center py-12">
            <p class="text-xl text-gray-600 mb-4">Your cart is currently empty.</p>
            <a href="{{ url('/shop') }}" class="bg-black text-white px-6 py-3 rounded uppercase hover:bg-gray-800">Continue Shopping</a>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b">
                            <th class="py-4">PRODUCT</th>
                            <th class="py-4 text-center">QUANTITY</th>
                            <th class="py-4 text-right">TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cartItems as $item)
                        {{-- Cek apakah produk dan varian ada untuk menghindari error --}}
                        @if($item->variant && $item->variant->product)
                        <tr class="border-b">
                            <td class="py-6 flex items-center">
                                {{-- Ambil gambar dari variant, jika kosong ambil dari product --}}
                                <img src="{{ asset('storage/' . ($item->variant->image ?? $item->variant->product->image)) }}" 
                                     alt="Product" class="w-20 h-20 object-cover mr-4">
                                
                                <div>
                                    <h3 class="font-bold uppercase">{{ $item->variant->product->name }}</h3>
                                    {{-- Perbaikan: Panggil Color dan Size --}}
                                    <p class="text-sm text-gray-500 uppercase">
                                        Variant: {{ $item->variant->color->name ?? 'N/A' }} / {{ $item->variant->size->name ?? 'N/A' }}
                                    </p>
                                    {{-- Perbaikan: Harga diambil dari Product --}}
                                    <p class="text-sm">Rp {{ number_format($item->variant->product->price, 0, ',', '.') }}</p>
                                </div>
                            </td>
                            <td class="py-6 text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <form action="{{ route('cart.update', $item->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="action" value="decrease">
                                        <button type="submit" class="px-2 py-1 border">-</button>
                                    </form>
                                    
                                    <span class="font-bold">{{ $item->quantity }}</span>
                                    
                                    <form action="{{ route('cart.update', $item->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="action" value="increase">
                                        <button type="submit" class="px-2 py-1 border">+</button>
                                    </form>
                                </div>
                                <form action="{{ route('cart.remove', $item->id) }}" method="POST" class="mt-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs text-red-500 underline uppercase">Remove</button>
                                </form>
                            </td>
                            <td class="py-6 text-right font-bold">
                                {{-- Perbaikan: Total dikali harga dari product --}}
                                Rp {{ number_format($item->variant->product->price * $item->quantity, 0, ',', '.') }}
                            </td>
                        </tr>
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- ORDER SUMMARY --}}
            <div class="bg-gray-50 p-6 h-fit rounded shadow-sm">
                <h2 class="text-xl font-bold mb-4">ORDER SUMMARY</h2>
                <div class="flex justify-between border-b py-2">
                    <span>Subtotal</span>
                    <span class="font-bold">
                        @php
                            $total = $cartItems->sum(function($i) {
                                return ($i->variant->product->price ?? 0) * $i->quantity;
                            });
                        @endphp
                        Rp {{ number_format($total, 0, ',', '.') }}
                    </span>
                </div>
                <div class="flex justify-between py-4 text-lg font-bold">
                    <span>Total</span>
                    <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>
                <a href="{{ route('checkout.index') }}" class="block text-center w-full bg-black text-white py-4 rounded uppercase font-bold hover:bg-gray-800 transition">
                    Proceed to Checkout
                </a>
            </div>
        </div>
    @endif
</div>
@endsection