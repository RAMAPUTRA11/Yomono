@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-[#fcfcfc]">
    @include('admin.sidebar')

    <div class="flex-1 p-10">
        <div class="mb-12">
            <h2 class="text-3xl font-light tracking-tight text-gray-900">Transactions</h2>
            <p class="text-xs text-gray-400 uppercase tracking-widest mt-2">Track and manage customer orders</p>
        </div>

        <div class="flex space-x-8 mb-8 text-[10px] uppercase tracking-widest font-bold">
            <a href="#" class="border-b-2 border-black pb-1">All Orders</a>
            <a href="#" class="text-gray-300 hover:text-black transition pb-1">Unpaid</a>
            <a href="#" class="text-gray-300 hover:text-black transition pb-1">Paid</a>
            <a href="#" class="text-gray-300 hover:text-black transition pb-1">Shipped</a>
        </div>

        <div class="bg-white border border-gray-100 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 text-[10px] uppercase tracking-widest text-gray-400 border-b border-gray-100">
                    <tr>
                        <th class="p-6 font-medium">Order ID</th>
                        <th class="p-6 font-medium">Date</th>
                        <th class="p-6 font-medium">Customer</th>
                        <th class="p-6 font-medium">Total Amount</th>
                        <th class="p-6 font-medium text-center">Payment Status</th>
                        <th class="p-6 font-medium text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="text-[11px] tracking-wide text-gray-600">
                    @forelse($orders as $order)
                    <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                        <td class="p-6 font-bold text-black uppercase">#{{ $order->id }}</td>
                        <td class="p-6 uppercase text-gray-400">{{ $order->created_at->format('d M Y') }}</td>
                        <td class="p-6">
                            <div class="flex flex-col">
                                <span class="font-medium text-black uppercase">{{ $order->user->name }}</span>
                                <span class="text-[9px] lowercase text-gray-400">{{ $order->user->email }}</span>
                            </div>
                        </td>
                        <td class="p-6 text-black">IDR {{ number_format($order->total_price, 0, ',', '.') }}</td>
                        <td class="p-6 text-center">
                            @if($order->payment_status == 'paid')
                                <span class="bg-green-50 text-green-600 px-3 py-1 rounded-full text-[9px] font-bold uppercase">Paid</span>
                            @else
                                <span class="bg-orange-50 text-orange-600 px-3 py-1 rounded-full text-[9px] font-bold uppercase">Pending</span>
                            @endif
                        </td>
                        <td class="p-6 text-right">
                            <a href="#" class="bg-black text-white px-4 py-2 hover:bg-gray-800 transition">MANAGE</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-12 text-center text-gray-400 italic uppercase tracking-widest">No transactions found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection