@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-20 px-6 text-center">
    <div class="mb-8">
        <i class="fas fa-check-circle text-5xl text-black"></i>
    </div>
    <h1 class="text-2xl font-bold tracking-tighter uppercase mb-2">Order Confirmed</h1>
    <p class="text-gray-500 text-[12px] tracking-widest mb-10">ORDER #{{ $order->order_number }}</p>

    <div class="bg-gray-50 p-8 border border-gray-100 text-left mb-10">
        <h4 class="font-bold text-[11px] tracking-widest uppercase mb-4 text-center">Simulated Payment</h4>
        <div class="flex justify-between border-b py-3 text-[11px]">
            <span>Total Amount:</span>
            <span class="font-bold">IDR {{ number_format($order->total_price) }}</span>
        </div>
        <div class="py-6 text-center">
            <p class="text-[10px] text-gray-400 uppercase tracking-widest mb-4">Scan QRIS to Pay (Demo Only)</p>
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=YOMONO-DEMO" class="mx-auto border p-2 bg-white">
        </div>
    </div>

    <a href="/" class="inline-block border border-black px-10 py-3 text-[10px] font-bold tracking-widest uppercase hover:bg-black hover:text-white transition">
        Back to Home
    </a>
</div>
@endsection