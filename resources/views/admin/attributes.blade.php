@extends('layouts.app')

@section('content')
<div class="flex flex-col md:flex-row min-h-screen bg-[#fcfcfc]">
    {{-- Sidebar Admin --}}
    @include('admin.sidebar')

    {{-- Konten Utama --}}
    <div class="flex-1 p-4 sm:p-6 md:p-10 transition-all duration-300">
        {{-- Header Section --}}
        <div class="mb-8 md:mb-10">
            <h2 class="text-xl sm:text-2xl font-light tracking-tight text-gray-900 uppercase">Manage Attributes</h2>
            <p class="text-[9px] sm:text-[10px] text-gray-400 uppercase tracking-[0.2em] sm:tracking-[0.3em] mt-1.5 sm:mt-2">Manage colors and sizes for your products</p>
        </div>

        {{-- Grid Form & Table --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 md:gap-10">
            
            {{-- SEKSI ATRIBUT WARNA --}}
            <div class="space-y-6">
                <div class="bg-white p-6 sm:p-8 border border-gray-100 shadow-sm">
                    <h3 class="text-[10px] font-bold uppercase tracking-[0.2em] mb-5 sm:mb-6 text-gray-700">Add Color</h3>
                    <form action="{{ route('admin.colors.store') }}" method="POST">
                        @csrf
                        <input type="text" name="name" required class="w-full border-b border-gray-200 py-2.5 text-xs focus:border-black outline-none transition uppercase mb-5" placeholder="e.g. EBONY BLACK">
                        <button class="w-full bg-black text-white py-3 text-[10px] uppercase tracking-[0.2em] hover:bg-gray-900 transition duration-300 active:scale-[0.99]">Save Color</button>
                    </form>
                </div>
                
                <div class="bg-white border border-gray-100 shadow-sm overflow-hidden rounded-sm">
                    <table class="w-full text-left text-[11px] uppercase tracking-widest">
                        <thead>
                            <tr class="bg-gray-50 text-gray-400 border-b border-gray-100">
                                <th class="p-4 font-medium">Available Colors</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($colors as $color)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="p-4 font-bold text-gray-800">{{ $color->name }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td class="p-4 text-gray-400 text-center normal-case tracking-normal">No colors added yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- SEKSI ATRIBUT UKURAN --}}
            <div class="space-y-6">
                <div class="bg-white p-6 sm:p-8 border border-gray-100 shadow-sm">
                    <h3 class="text-[10px] font-bold uppercase tracking-[0.2em] mb-5 sm:mb-6 text-gray-700">Add Size</h3>
                    <form action="{{ route('admin.sizes.store') }}" method="POST">
                        @csrf
                        <input type="text" name="name" required class="w-full border-b border-gray-200 py-2.5 text-xs focus:border-black outline-none transition uppercase mb-5" placeholder="e.g. LARGE (L)">
                        <button class="w-full bg-black text-white py-3 text-[10px] uppercase tracking-[0.2em] hover:bg-gray-900 transition duration-300 active:scale-[0.99]">Save Size</button>
                    </form>
                </div>

                <div class="bg-white border border-gray-100 shadow-sm overflow-hidden rounded-sm">
                    <table class="w-full text-left text-[11px] uppercase tracking-widest">
                        <thead>
                            <tr class="bg-gray-50 text-gray-400 border-b border-gray-100">
                                <th class="p-4 font-medium">Available Sizes</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($sizes as $size)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="p-4 font-bold text-gray-800">{{ $size->name }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td class="p-4 text-gray-400 text-center normal-case tracking-normal">No sizes added yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
        </div>
    </div>
</div>
@endsection