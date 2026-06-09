@extends('layouts.app')

@section('content')
<div class="flex flex-col md:flex-row min-h-screen bg-[#fcfcfc]">
    {{-- Sidebar Admin --}}
    @include('admin.sidebar')

    {{-- Konten Utama --}}
    <div class="flex-1 p-4 sm:p-6 md:p-10">
        {{-- Header Section --}}
        <div class="mb-8 md:mb-12">
            <h2 class="text-2xl md:text-3xl font-light tracking-tight text-gray-900 uppercase">Categories</h2>
            <p class="text-[10px] sm:text-xs text-gray-400 uppercase tracking-widest mt-1.5 sm:mt-2">Manage product categories for your store</p>
        </div>

        {{-- Grid Form & Tabel --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 md:gap-10">
            
            {{-- Form Tambah Kategori --}}
            <div class="bg-white p-6 sm:p-8 border border-gray-100 shadow-sm h-fit">
                <h3 class="text-[10px] font-bold uppercase tracking-[0.2em] mb-5 sm:mb-6 text-gray-700">Add New Category</h3>
                
                <form action="{{ route('admin.categories.store') }}" method="POST">
                    @csrf
                    <div class="mb-6">
                        <label class="text-[9px] uppercase tracking-widest text-gray-400 block mb-2">Category Name</label>
                        <input type="text" name="name" required 
                               class="w-full border-b border-gray-200 py-2.5 text-xs focus:border-black outline-none transition uppercase tracking-widest"
                               placeholder="e.g. OUTERWEAR">
                        @error('name')
                            <p class="text-[9px] text-red-500 mt-1 uppercase tracking-wide">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <button type="submit" class="w-full bg-black text-white py-3 text-[10px] uppercase tracking-[0.2em] hover:bg-gray-900 transition duration-300 active:scale-[0.99]">
                        Save Category
                    </button>
                </form>
            </div>

            {{-- Tabel List Kategori Responsif --}}
            <div class="lg:col-span-2 bg-white border border-gray-100 shadow-sm overflow-hidden rounded-sm">
                <div class="w-full overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[500px]">
                        <thead class="bg-gray-50 text-[10px] uppercase tracking-widest text-gray-400 border-b border-gray-100">
                            <tr>
                                <th class="p-4 sm:p-6 font-medium">Category Name</th>
                                <th class="p-4 sm:p-6 font-medium">Slug</th>
                                <th class="p-4 sm:p-6 font-medium text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-[11px] tracking-wide text-gray-600 divide-y divide-gray-50">
                            @forelse($categories as $cat)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="p-4 sm:p-6 font-bold text-black uppercase tracking-wider">{{ $cat->name }}</td>
                                <td class="p-4 sm:p-6 text-gray-400">{{ $cat->slug }}</td>
                                <td class="p-4 sm:p-6 text-right">
                                    <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Hapus kategori ini? Produk dengan kategori ini mungkin akan terpengaruh.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-400 font-bold border-b border-red-400 hover:text-red-600 hover:border-red-600 pb-0.5 transition-all uppercase text-[10px] tracking-wider">DELETE</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="p-12 text-center text-gray-400 italic uppercase tracking-widest normal-case">No categories found.</td>
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