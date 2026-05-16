@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-[#fcfcfc]">
    @include('admin.sidebar')

    <div class="flex-1 p-10">
        <div class="mb-12">
            <h2 class="text-3xl font-light tracking-tight text-gray-900">Categories</h2>
            <p class="text-xs text-gray-400 uppercase tracking-widest mt-2">Manage product categories for your store</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
            <div class="bg-white p-8 border border-gray-100 shadow-sm h-fit">
                <h3 class="text-[10px] font-bold uppercase tracking-[0.2em] mb-6">Add New Category</h3>
                
                <form action="{{ route('admin.categories.store') }}" method="POST">
                    @csrf
                    <div class="mb-6">
                        <label class="text-[9px] uppercase tracking-widest text-gray-400 block mb-2">Category Name</label>
                        <input type="text" name="name" required 
                               class="w-full border-b border-gray-200 py-2 text-xs focus:border-black outline-none transition uppercase tracking-widest"
                               placeholder="e.g. OUTERWEAR">
                        @error('name')
                            <p class="text-[9px] text-red-500 mt-1 uppercase">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <button type="submit" class="w-full bg-black text-white py-3 text-[10px] uppercase tracking-[0.2em] hover:bg-gray-800 transition">
                        Save Category
                    </button>
                </form>
            </div>

            <div class="md:col-span-2 bg-white border border-gray-100 shadow-sm overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 text-[10px] uppercase tracking-widest text-gray-400 border-b border-gray-100">
                        <tr>
                            <th class="p-6 font-medium">Category Name</th>
                            <th class="p-6 font-medium">Slug</th>
                            <th class="p-6 font-medium text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="text-[11px] tracking-wide text-gray-600">
                        @forelse($categories as $cat)
                        <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                            <td class="p-6 font-bold text-black uppercase tracking-wider">{{ $cat->name }}</td>
                            <td class="p-6 text-gray-400">{{ $cat->slug }}</td>
                            <td class="p-6 text-right">
                                <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Hapus kategori ini? Produk dengan kategori ini mungkin akan terpengaruh.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-400 font-bold border-b border-red-400 hover:pb-1 transition-all">DELETE</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="p-12 text-center text-gray-400 italic uppercase tracking-widest">No categories found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection