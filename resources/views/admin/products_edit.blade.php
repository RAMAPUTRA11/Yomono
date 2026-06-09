@extends('layouts.app')

@section('content')
<div class="flex flex-col md:flex-row min-h-screen bg-[#fcfcfc]">
    {{-- Sidebar Admin --}}
    @include('admin.sidebar')

    {{-- Konten Utama --}}
    <div class="flex-1 p-4 sm:p-6 md:p-10">
        <div class="max-w-5xl mx-auto">
            {{-- Header --}}
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-end gap-4 mb-10 md:mb-12 border-b sm:border-b-0 border-gray-100 pb-4 sm:pb-0">
                <div>
                    <h2 class="text-2xl md:text-3xl font-light tracking-tight text-gray-900 uppercase">Edit Product</h2>
                    <p class="text-[10px] sm:text-xs text-gray-400 uppercase tracking-widest mt-1.5">Modify details and inventory</p>
                </div>
                <a href="{{ route('admin.products.index') }}" class="text-[10px] uppercase tracking-widest text-gray-400 hover:text-black transition font-bold">
                    ← Back to List
                </a>
            </div>

            <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 md:gap-10">
                    {{-- Sisi Kiri: Main Info --}}
                    <div class="space-y-6 md:space-y-8">
                        <div class="bg-white border border-gray-100 p-6 sm:p-8 shadow-sm rounded-sm">
                            <h3 class="text-xs sm:text-sm font-bold uppercase tracking-widest border-b pb-4 mb-6 text-gray-800">General Information</h3>
                            
                            <div class="space-y-6">
                                <div>
                                    <label class="text-[10px] uppercase text-gray-400 block mb-2 font-bold tracking-wider">Product Name</label>
                                    <input type="text" name="name" required value="{{ old('name', $product->name) }}"
                                        class="w-full border-b py-2 text-xs focus:border-black outline-none transition uppercase tracking-wide">
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 sm:gap-4">
                                    <div>
                                        <label class="text-[10px] uppercase text-gray-400 block mb-2 font-bold tracking-wider">Category</label>
                                        <select name="category_id" required class="w-full border-b py-2 text-xs outline-none bg-transparent focus:border-black rounded-none">
                                            @foreach($categories as $cat)
                                                <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>
                                                    {{ $cat->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="text-[10px] uppercase text-gray-400 block mb-2 font-bold tracking-wider">Price (IDR)</label>
                                        <input type="number" name="price" required value="{{ old('price', $product->price) }}"
                                            class="w-full border-b py-2 text-xs outline-none focus:border-black">
                                    </div>
                                </div>

                                <div>
                                    <label class="text-[10px] uppercase text-gray-400 block mb-2 font-bold tracking-wider">Change Image (Optional)</label>
                                    <div class="flex flex-wrap items-center gap-4 mt-2 p-3 bg-gray-50 border border-dashed border-gray-200">
                                        <img src="{{ asset('storage/' . $product->image) }}" class="w-14 h-18 object-cover grayscale border bg-white shadow-sm">
                                        <input type="file" name="main_image" class="text-[10px] text-gray-400 flex-1 file:mr-4 file:py-1.5 file:px-3 file:border-0 file:text-[10px] file:uppercase file:tracking-wider file:font-bold file:bg-black file:text-white hover:file:bg-gray-800">
                                    </div>
                                </div>

                                <div>
                                    <label class="text-[10px] uppercase text-gray-400 block mb-2 font-bold tracking-wider">Description</label>
                                    <textarea name="description" rows="5" class="w-full border border-gray-200 p-3 text-xs outline-none focus:border-black transition-all rounded-none">{{ old('description', $product->description) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Sisi Kanan: Variants --}}
                    <div class="bg-gray-50 p-6 sm:p-8 border border-gray-100 rounded-sm shadow-sm h-fit">
                        <div class="flex justify-between items-center mb-6 border-b border-gray-200 pb-4">
                            <h4 class="text-[10px] font-bold uppercase tracking-widest text-gray-500">Inventory Variants</h4>
                            <button type="button" onclick="addColorGroup()" class="text-[9px] bg-black text-white px-3 py-1.5 uppercase tracking-widest hover:bg-gray-800 transition active:scale-95 font-medium rounded-sm">
                                + Add Color
                            </button>
                        </div>
                        
                        <div id="color-group-container" class="space-y-6">
                            @foreach($product->variants->groupBy('color_id') as $colorId => $variants)
                                @php $firstVariant = $variants->first(); @endphp
                                <div class="color-group bg-white p-4 border border-gray-200 relative shadow-sm rounded-sm">
                                    <button type="button" onclick="this.parentElement.remove(); reindexAll();" class="absolute top-2 right-2 text-gray-300 hover:text-red-500 text-xl font-light">×</button>
                                    
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4 border-b border-gray-50 pb-3">
                                        <div>
                                            <label class="text-[8px] uppercase font-bold text-gray-400 block mb-1 tracking-wider">Color</label>
                                            <select required class="color-select w-full text-[10px] uppercase border-none bg-gray-50 focus:ring-1 focus:ring-black py-1.5">
                                                @foreach($colors as $color)
                                                    <option value="{{ $color->id }}" {{ $colorId == $color->id ? 'selected' : '' }}>{{ $color->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="text-[8px] uppercase font-bold text-gray-400 block mb-1 tracking-wider">Photo</label>
                                            <input type="file" class="variant-image text-[9px] w-full text-gray-400">
                                            @if($firstVariant->image)
                                                <p class="text-[8px] text-green-600 font-bold tracking-wide mt-1 uppercase">✓ Image exists</p>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="size-stock-rows space-y-2">
                                        <label class="text-[8px] uppercase font-bold text-gray-400 block mb-1 tracking-wider">Sizes & Stock</label>
                                        @foreach($variants as $v)
                                        <div class="grid grid-cols-3 gap-2 size-row items-center">
                                            <select required class="size-select text-[10px] border-none bg-gray-50 focus:ring-1 focus:ring-black py-1.5">
                                                @foreach($sizes as $size)
                                                    <option value="{{ $size->id }}" {{ $v->size_id == $size->id ? 'selected' : '' }}>{{ $size->name }}</option>
                                                @endforeach
                                            </select>
                                            <input type="number" placeholder="Qty" required value="{{ $v->stock }}" class="stock-input text-[10px] border-none bg-gray-50 focus:ring-1 focus:ring-black py-1.5">
                                            <button type="button" onclick="this.parentElement.remove(); reindexAll();" class="text-md text-red-300 hover:text-red-500 font-light text-left pl-2">×</button>
                                        </div>
                                        @endforeach
                                        <button type="button" onclick="addSizeRow(this)" class="inline-block text-[9px] uppercase tracking-widest text-gray-400 hover:text-black font-bold pt-1">+ Add Size</button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="mt-8 md:mt-12">
                    <button type="submit" class="w-full bg-black text-white py-4 text-[11px] uppercase tracking-[0.3em] font-bold hover:bg-gray-900 transition duration-300 active:scale-[0.99] rounded-sm">
                        Update Product
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function addColorGroup() {
        const container = document.getElementById('color-group-container');
        const div = document.createElement('div');
        div.className = "color-group bg-white p-4 border border-gray-200 relative shadow-sm rounded-sm";
        div.innerHTML = `
            <button type="button" onclick="this.parentElement.remove(); reindexAll();" class="absolute top-2 right-2 text-gray-300 hover:text-red-500 text-xl font-light">×</button>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4 border-b border-gray-50 pb-3">
                <div>
                    <label class="text-[8px] uppercase font-bold text-gray-400 block mb-1 tracking-wider">Color</label>
                    <select required class="color-select w-full text-[10px] uppercase border-none bg-gray-50 focus:ring-1 focus:ring-black py-1.5">
                        <option value="" disabled selected>Select Color</option>
                        @foreach($colors as $color)
                            <option value="{{ $color->id }}">{{ $color->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-[8px] uppercase font-bold text-gray-400 block mb-1 tracking-wider">Photo</label>
                    <input type="file" class="variant-image text-[9px] w-full text-gray-400">
                </div>
            </div>
            <div class="size-stock-rows space-y-2">
                <label class="text-[8px] uppercase font-bold text-gray-400 block mb-1 tracking-wider">Sizes & Stock</label>
                <div class="grid grid-cols-3 gap-2 size-row items-center">
                    <select required class="size-select text-[10px] border-none bg-gray-50 focus:ring-1 focus:ring-black py-1.5">
                        @foreach($sizes as $size)
                            <option value="{{ $size->id }}">{{ $size->name }}</option>
                        @endforeach
                    </select>
                    <input type="number" placeholder="Qty" required class="stock-input text-[10px] border-none bg-gray-50 focus:ring-1 focus:ring-black py-1.5">
                    <button type="button" onclick="this.parentElement.remove(); reindexAll();" class="text-md text-red-300 hover:text-red-500 font-light text-left pl-2">×</button>
                </div>
                <button type="button" onclick="addSizeRow(this)" class="inline-block text-[9px] uppercase tracking-widest text-gray-400 hover:text-black font-bold pt-1">+ Add Size</button>
            </div>
        `;
        container.appendChild(div);
        reindexAll();
    }

    function addSizeRow(btn) {
        const rowsContainer = btn.closest('.size-stock-rows');
        const row = document.createElement('div');
        row.className = "grid grid-cols-3 gap-2 size-row items-center";
        row.innerHTML = `
            <select required class="size-select text-[10px] border-none bg-gray-50 focus:ring-1 focus:ring-black py-1.5">
                @foreach($sizes as $size)
                    <option value="{{ $size->id }}">{{ $size->name }}</option>
                @endforeach
            </select>
            <input type="number" placeholder="Qty" required class="stock-input text-[10px] border-none bg-gray-50 focus:ring-1 focus:ring-black py-1.5">
            <button type="button" onclick="this.parentElement.remove(); reindexAll();" class="text-md text-red-300 hover:text-red-500 font-light text-left pl-2">×</button>
        `;
        rowsContainer.insertBefore(row, btn);
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

    window.onload = function() {
        reindexAll();
    };
</script>
@endsection