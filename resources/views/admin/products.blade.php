@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-[#fcfcfc]">
    @include('admin.sidebar')

    <div class="flex-1 p-10">
        {{-- Header --}}
        <div class="flex justify-between items-end mb-12">
            <div>
                <h2 class="text-3xl font-light tracking-tight text-gray-900">Products</h2>
                <p class="text-xs text-gray-400 uppercase tracking-widest mt-2">Manage inventory and variants</p>
            </div>
            <button onclick="toggleModal('modal-add-product')" 
                class="bg-black text-white px-8 py-3 text-[10px] uppercase tracking-[0.2em] hover:bg-gray-800 transition">
                + Add New Product
            </button>
        </div>

        {{-- Success/Error Alerts --}}
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 text-[10px] uppercase tracking-widest">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 text-[10px] uppercase tracking-widest">
                <p class="font-bold mb-1">Validation Error:</p>
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Table --}}
        <div class="bg-white border border-gray-100 overflow-hidden shadow-sm">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 text-[10px] uppercase tracking-widest text-gray-400 border-b border-gray-100">
                    <tr>
                        <th class="p-6">Image</th>
                        <th class="p-6">Product Name</th>
                        <th class="p-6">Category</th>
                        <th class="p-6">Price</th>
                        <th class="p-6 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="text-[11px] tracking-wide text-gray-600">
                    @forelse($products as $product)
                    <tr class="border-b border-gray-50 hover:bg-gray-50">
                        <td class="p-6 w-20">
                            {{-- TAMPILAN GAMBAR: Menggunakan helper asset untuk akses storage --}}
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" class="w-12 h-16 object-cover grayscale hover:grayscale-0 transition duration-300">
                            @else
                                <div class="w-12 h-16 bg-gray-100 flex items-center justify-center text-[8px] text-gray-400 uppercase">No Img</div>
                            @endif
                        </td>
                        <td class="p-6 font-bold text-black uppercase">{{ $product->name }}</td>
                        <td class="p-6 text-gray-400 uppercase">{{ $product->category->name ?? '-' }}</td>
                        <td class="p-6">IDR {{ number_format($product->price, 0, ',', '.') }}</td>
                        <td class="p-6 text-right">
                            <div class="flex justify-end items-center gap-6">
                                {{-- TOMBOL EDIT --}}
                                <a href="{{ route('admin.products.edit', $product->id) }}" 
                                   class="text-gray-400 hover:text-black font-bold uppercase tracking-widest transition">
                                    EDIT
                                </a>

                                {{-- TOMBOL DELETE --}}
                                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-400 hover:text-red-600 font-bold uppercase tracking-widest transition" 
                                            onclick="return confirm('Delete product?')">
                                        DELETE
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="p-12 text-center text-gray-400 italic">No products available.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Add Product Modal (Keep as is) --}}
<div id="modal-add-product" class="fixed inset-0 z-[60] hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="fixed inset-0 bg-black/50 transition-opacity" onclick="toggleModal('modal-add-product')"></div>
        
        <div class="relative bg-white shadow-xl sm:max-w-5xl sm:w-full overflow-hidden">
            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="p-10">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    
                    {{-- Left Side: Main Info --}}
                    <div class="space-y-6">
                        <h3 class="text-xl font-light uppercase tracking-widest border-b pb-4">General Information</h3>
                        
                        <div>
                            <label class="text-[10px] uppercase text-gray-400 block mb-2 font-bold">Product Name</label>
                            <input type="text" name="name" required value="{{ old('name') }}"
                                class="w-full border-b py-2 text-xs focus:border-black outline-none transition uppercase">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-[10px] uppercase text-gray-400 block mb-2 font-bold">Category</label>
                                <select name="category_id" required class="w-full border-b py-2 text-xs outline-none bg-transparent">
                                    <option value="" disabled selected>Select Category</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="text-[10px] uppercase text-gray-400 block mb-2 font-bold">Price (IDR)</label>
                                <input type="number" name="price" required value="{{ old('price') }}"
                                    class="w-full border-b py-2 text-xs outline-none">
                            </div>
                        </div>

                        <div>
                            <label class="text-[10px] uppercase text-gray-400 block mb-2 font-bold">Main Image (Thumbnail)</label>
                            <input type="file" name="main_image" required class="text-[10px] w-full">
                        </div>

                        <div>
                            <label class="text-[10px] uppercase text-gray-400 block mb-2 font-bold">Description</label>
                            <textarea name="description" rows="3" class="w-full border p-3 text-xs outline-none focus:border-black">{{ old('description') }}</textarea>
                        </div>
                    </div>

                    {{-- Right Side: Variants --}}
                    <div class="bg-gray-50 p-6">
                        <div class="flex justify-between items-center mb-6 border-b border-gray-200 pb-4">
                            <h4 class="text-[10px] font-bold uppercase tracking-widest text-gray-500">Inventory Variants</h4>
                            <button type="button" onclick="addColorGroup()" class="text-[9px] bg-black text-white px-3 py-1 uppercase tracking-widest hover:bg-gray-800 transition">
                                + Add Color Group
                            </button>
                        </div>
                        
                        <div id="color-group-container" class="space-y-6 max-h-[450px] overflow-y-auto pr-2">
                        </div>
                    </div>
                </div>

                <div class="mt-12">
                    <button type="submit" class="w-full bg-black text-white py-4 text-[11px] uppercase tracking-[0.3em] font-bold hover:bg-gray-800 transition">
                        Save to Catalog
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function toggleModal(id) {
        const modal = document.getElementById(id);
        modal.classList.toggle('hidden');
        if (!modal.classList.contains('hidden') && document.querySelectorAll('.color-group').length === 0) {
            addColorGroup();
        }
    }

    function addColorGroup() {
        const container = document.getElementById('color-group-container');
        const div = document.createElement('div');
        div.className = "color-group bg-white p-4 border border-gray-200 relative animate-fade-in mb-4 shadow-sm";
        
        div.innerHTML = `
            <button type="button" onclick="this.parentElement.remove(); reindexAll();" class="absolute top-2 right-2 text-gray-300 hover:text-red-500 text-lg">×</button>
            
            <div class="grid grid-cols-2 gap-4 mb-4 border-b border-gray-50 pb-3">
                <div>
                    <label class="text-[8px] uppercase font-bold text-gray-400 block mb-1">Color</label>
                    <select required class="color-select w-full text-[10px] uppercase border-none bg-gray-50 focus:ring-0">
                        <option value="" disabled selected>Select Color</option>
                        @foreach($colors as $color)
                            <option value="{{ $color->id }}">{{ $color->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-[8px] uppercase font-bold text-gray-400 block mb-1">Photo (Optional)</label>
                    <input type="file" class="variant-image text-[9px] w-full text-gray-400">
                </div>
            </div>

            <div class="size-stock-rows space-y-2">
                <div class="grid grid-cols-3 gap-2 size-row items-center">
                    <select required class="size-select text-[10px] border-none bg-gray-50 focus:ring-0">
                        @foreach($sizes as $size)
                            <option value="{{ $size->id }}">{{ $size->name }}</option>
                        @endforeach
                    </select>
                    <input type="number" placeholder="Qty" required class="stock-input text-[10px] border-none bg-gray-50 focus:ring-0">
                    <button type="button" onclick="addSizeRow(this)" class="text-[14px] text-gray-300 hover:text-black font-bold">+</button>
                </div>
            </div>
        `;
        container.appendChild(div);
        reindexAll();
    }

    function addSizeRow(btn) {
        const rowsContainer = btn.closest('.size-stock-rows');
        const row = document.createElement('div');
        row.className = "grid grid-cols-3 gap-2 size-row items-center mt-2";
        row.innerHTML = `
            <select required class="size-select text-[10px] border-none bg-gray-50 focus:ring-0">
                @foreach($sizes as $size)
                    <option value="{{ $size->id }}">{{ $size->name }}</option>
                @endforeach
            </select>
            <input type="number" placeholder="Qty" required class="stock-input text-[10px] border-none bg-gray-50 focus:ring-0">
            <button type="button" onclick="this.parentElement.remove(); reindexAll();" class="text-[14px] text-red-300 hover:text-red-500 font-bold">×</button>
        `;
        rowsContainer.appendChild(row);
        reindexAll();
    }

    function reindexAll() {
        document.querySelectorAll('.color-group').forEach((group, colorIdx) => {
            group.querySelector('.color-select').name = `variants[${colorIdx}][color_id]`;
            group.querySelector('.variant-image').name = `variants[${colorIdx}][image]`;
            
            group.querySelectorAll('.size-row').forEach(row => {
                row.querySelector('.size-select').name = `variants[${colorIdx}][sizes][]`;
                row.querySelector('.stock-input').name = `variants[${colorIdx}][stocks][]`;
            });
        });
    }

    document.addEventListener('change', (e) => {
        if(e.target.closest('.color-group')) reindexAll();
    });
</script>

<style>
    .animate-fade-in { animation: fadeIn 0.3s ease-in-out; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    #color-group-container::-webkit-scrollbar { width: 4px; }
    #color-group-container::-webkit-scrollbar-thumb { background: #000; border-radius: 10px; }
</style>
@endsection