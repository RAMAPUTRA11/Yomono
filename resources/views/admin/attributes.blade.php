@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-[#fcfcfc]">
    @include('admin.sidebar')

    <div class="flex-1 p-10">
        <div class="mb-10">
            <h2 class="text-2xl font-light tracking-tight text-gray-900 uppercase">Manage Attributes</h2>
            <p class="text-[10px] text-gray-400 uppercase tracking-[0.3em] mt-2">Manage colors and sizes for your products</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
            <div class="space-y-6">
                <div class="bg-white p-8 border border-gray-100 shadow-sm">
                    <h3 class="text-[10px] font-bold uppercase tracking-[0.2em] mb-6">Add Color</h3>
                    <form action="{{ route('admin.colors.store') }}" method="POST">
                        @csrf
                        <input type="text" name="name" required class="w-full border-b border-gray-200 py-2 text-xs focus:border-black outline-none transition uppercase mb-4" placeholder="e.g. EBONY BLACK">
                        <button class="w-full bg-black text-white py-3 text-[10px] uppercase tracking-[0.2em]">Save Color</button>
                    </form>
                </div>
                
                <div class="bg-white border border-gray-100 shadow-sm">
                    <table class="w-full text-left text-[11px] uppercase tracking-widest">
                        <tr class="bg-gray-50 text-gray-400 border-b border-gray-100">
                            <th class="p-4 font-medium">Available Colors</th>
                        </tr>
                        @foreach($colors as $color)
                        <tr class="border-b border-gray-50">
                            <td class="p-4 font-bold">{{ $color->name }}</td>
                        </tr>
                        @endforeach
                    </table>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-white p-8 border border-gray-100 shadow-sm">
                    <h3 class="text-[10px] font-bold uppercase tracking-[0.2em] mb-6">Add Size</h3>
                    <form action="{{ route('admin.sizes.store') }}" method="POST">
                        @csrf
                        <input type="text" name="name" required class="w-full border-b border-gray-200 py-2 text-xs focus:border-black outline-none transition uppercase mb-4" placeholder="e.g. LARGE (L)">
                        <button class="w-full bg-black text-white py-3 text-[10px] uppercase tracking-[0.2em]">Save Size</button>
                    </form>
                </div>

                <div class="bg-white border border-gray-100 shadow-sm">
                    <table class="w-full text-left text-[11px] uppercase tracking-widest">
                        <tr class="bg-gray-50 text-gray-400 border-b border-gray-100">
                            <th class="p-4 font-medium">Available Sizes</th>
                        </tr>
                        @foreach($sizes as $size)
                        <tr class="border-b border-gray-50">
                            <td class="p-4 font-bold">{{ $size->name }}</td>
                        </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection