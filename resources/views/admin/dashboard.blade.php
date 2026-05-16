@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-[#fcfcfc]">
    <div class="w-64 bg-white border-r border-gray-100 hidden md:block">
        <div class="p-8">
            <h1 class="text-lg font-bold tracking-tighter">YOMONO</h1>
            <p class="text-[9px] text-gray-400 uppercase tracking-[0.3em]">Administrator</p>
        </div>
        <nav class="mt-4 px-6 space-y-2 text-[11px] uppercase tracking-[0.2em] font-medium text-gray-500">
            <a href="{{ route('admin.dashboard') }}" class="block py-3 px-4 bg-black text-white rounded-sm">Dashboard</a>
            <a href="{{ route('admin.products.index') }}" class="block py-3 px-4 hover:bg-gray-50 transition">Manage Products</a>
            <a href="{{ route('admin.orders.index') }}" class="block py-3 px-4 hover:bg-gray-50 transition">Transactions</a>
            <hr class="my-6 border-gray-100">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="w-full text-left py-3 px-4 text-red-400 hover:text-red-600 transition">Logout</button>
            </form>
        </nav>
    </div>

    <div class="flex-1 p-10">
        <div class="flex justify-between items-end mb-12">
            <div>
                <h2 class="text-3xl font-light tracking-tight text-gray-900">Dashboard</h2>
                <p class="text-xs text-gray-400 uppercase tracking-widest mt-2">Overview of your store activity</p>
            </div>
            <div class="text-right">
                <p class="text-[10px] text-gray-400 uppercase tracking-widest">Server Time</p>
                <p class="text-sm font-medium">{{ date('d M Y, H:i') }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
            <div class="bg-white p-8 border border-gray-100">
                <p class="text-[10px] uppercase tracking-[0.3em] text-gray-400 mb-2">Total Sales</p>
                <h3 class="text-2xl font-light">IDR 0</h3>
            </div>
            <div class="bg-white p-8 border border-gray-100">
                <p class="text-[10px] uppercase tracking-[0.3em] text-gray-400 mb-2">Active Orders</p>
                <h3 class="text-2xl font-light">{{ $orders->count() }}</h3>
            </div>
            <div class="bg-white p-8 border border-gray-100">
                <p class="text-[10px] uppercase tracking-[0.3em] text-gray-400 mb-2">Total Products</p>
                <h3 class="text-2xl font-light">{{ \App\Models\Product::count() }}</h3>
            </div>
        </div>

        <div class="bg-white border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-50 flex justify-between items-center">
                <h4 class="text-[11px] font-bold uppercase tracking-widest">Recent Transactions</h4>
                <a href="{{ route('admin.orders.index') }}" class="text-[10px] uppercase tracking-widest border-b border-black pb-1">View All</a>
            </div>
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 text-[10px] uppercase tracking-widest text-gray-400">
                    <tr>
                        <th class="p-6 font-medium">Order ID</th>
                        <th class="p-6 font-medium">Customer</th>
                        <th class="p-6 font-medium">Date</th>
                        <th class="p-6 font-medium">Status</th>
                        <th class="p-6 font-medium text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="text-[11px] tracking-wide text-gray-600">
                    @forelse($orders as $order)
                    <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                        <td class="p-6 font-medium text-black">#{{ $order->id }}</td>
                        <td class="p-6">{{ $order->user->name }}</td>
                        <td class="p-6">{{ $order->created_at->format('d/m/Y') }}</td>
                        <td class="p-6">
                            <span class="px-3 py-1 rounded-full text-[9px] font-bold {{ $order->payment_status == 'paid' ? 'bg-green-50 text-green-600' : 'bg-orange-50 text-orange-600' }}">
                                {{ strtoupper($order->payment_status) }}
                            </span>
                        </td>
                        <td class="p-6 text-right">
                            <a href="#" class="text-black font-bold border-b border-black">DETAILS</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-12 text-center text-gray-400 italic">No transactions found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection