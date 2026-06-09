    @extends('layouts.app')

    @section('content')
    <div class="flex flex-col md:flex-row min-h-screen bg-[#fcfcfc]">
        {{-- Sidebar Admin --}}
        @include('admin.sidebar')

        {{-- Konten Utama --}}
        <div class="flex-1 p-4 sm:p-6 md:p-10">
            {{-- Header --}}
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-end gap-4 mb-10 md:mb-12 border-b sm:border-b-0 border-gray-100 pb-4 sm:pb-0">
                <div>
                    <h2 class="text-2xl md:text-3xl font-light tracking-tight text-gray-900 uppercase">Products</h2>
                    <p class="text-[10px] sm:text-xs text-gray-400 uppercase tracking-widest mt-1.5">Manage inventory and variants</p>
                </div>
                <button onclick="toggleModal('modal-add-product')" 
                    class="bg-black text-white px-6 sm:px-8 py-3 text-[10px] uppercase tracking-[0.2em] hover:bg-gray-800 transition active:scale-95 text-center font-bold rounded-sm">
                    + Add New Product
                </button>
            </div>

            {{-- Notifikasi Sukses --}}
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 text-[10px] uppercase tracking-widest font-bold">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Notifikasi Error & Validasi --}}
            @if(session('error') || $errors->any())
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 text-[10px] uppercase tracking-widest">
                    @if(session('error'))
                        <p class="font-bold">{{ session('error') }}</p>
                    @endif

                    @if($errors->any())
                        <p class="font-bold mb-1">Validation Error:</p>
                        <ul class="list-disc list-inside space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endif

            {{-- Tabel List Produk --}}
            <div class="bg-white border border-gray-100 overflow-hidden shadow-sm rounded-sm">
                <div class="w-full overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[800px]">
                        <thead class="bg-gray-50 text-[10px] uppercase tracking-widest text-gray-400 border-b border-gray-100">
                            <tr>
                                <th class="p-4 sm:p-6 w-24">Image</th>
                                <th class="p-4 sm:p-6">Product Name</th>
                                <th class="p-4 sm:p-6">Category</th>
                                <th class="p-4 sm:p-6">Collection</th>
                                <th class="p-4 sm:p-6">Price</th>
                                <th class="p-4 sm:p-6 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-[11px] tracking-wide text-gray-600 divide-y divide-gray-50">
                            @forelse($products as $product)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="p-4 sm:p-6">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" class="w-12 h-16 object-cover grayscale hover:grayscale-0 border border-gray-100 bg-white transition duration-300 rounded-sm">
                                    @else
                                        <div class="w-12 h-16 bg-gray-50 border border-gray-100 flex items-center justify-center text-[8px] text-gray-400 uppercase tracking-wider rounded-sm text-center px-1">No Img</div>
                                    @endif
                                </td>
                                <td class="p-4 sm:p-6 font-bold text-black uppercase tracking-wide">{{ $product->name }}</td>
                                <td class="p-4 sm:p-6 text-gray-400 uppercase tracking-wide">{{ $product->category->name ?? '-' }}</td>
                                <td class="p-4 sm:p-6 text-gray-400 uppercase italic tracking-wide">{{ $product->collection_name ?? '-' }}</td>
                                <td class="p-4 sm:p-6 text-black font-medium">IDR {{ number_format($product->price, 0, ',', '.') }}</td>
                                <td class="p-4 sm:p-6 text-right">
                                    <div class="flex justify-end items-center gap-6">
                                        <a href="{{ route('admin.products.edit', $product->id) }}" 
                                        class="text-gray-400 hover:text-black font-bold uppercase tracking-widest transition text-[10px]">
                                            EDIT
                                        </a>

                                        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-400 hover:text-red-600 font-bold uppercase tracking-widest transition text-[10px]" 
                                                    onclick="return confirm('Delete product?')">
                                                DELETE
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="p-12 text-center text-gray-400 italic uppercase tracking-widest normal-case">No products available.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Add Product Modal --}}
    <div id="modal-add-product" class="fixed inset-0 z-[60] hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4 sm:p-6">
            <div class="fixed inset-0 bg-black/60 backdrop-blur-xs transition-opacity" onclick="toggleModal('modal-add-product')"></div>
            
            <div class="relative bg-white shadow-2xl sm:max-w-5xl sm:w-full overflow-hidden rounded-sm w-full animate-modal-up">
                <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="p-6 sm:p-10 max-h-[90vh] overflow-y-auto">
                    @csrf
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-10">
                        
                        {{-- Sisi Kiri: Main Info --}}
                        <div class="space-y-6">
                            <h3 class="text-sm font-bold uppercase tracking-widest border-b pb-4 text-gray-800">General Information</h3>
                            
                            <div>
                                <label class="text-[10px] uppercase text-gray-400 block mb-2 font-bold tracking-wider">Product Name</label>
                                <input type="text" name="name" required value="{{ old('name') }}"
                                    class="w-full border-b py-2 text-xs focus:border-black outline-none transition uppercase tracking-wide">
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 sm:gap-4">
                                <div>
                                    <label class="text-[10px] uppercase text-gray-400 block mb-2 font-bold tracking-wider">Category</label>
                                            <select name="category_id" required class="w-full border-b py-2 text-xs outline-none bg-transparent focus:border-black rounded-none">
                                        <option value="" disabled selected>Select Category</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="text-[10px] uppercase text-gray-400 block mb-2 font-bold tracking-wider">Collection Name</label>
                                    <input type="text" name="collection_name" value="{{ old('collection_name') }}"
                                        placeholder="e.g. Summer 2026"
                                        class="w-full border-b py-2 text-xs focus:border-black outline-none transition uppercase tracking-wide">
                                </div>
                            </div>

                            <div>
                                <label class="text-[10px] uppercase text-gray-400 block mb-2 font-bold tracking-wider">Price (IDR)</label>
                                <input type="number" name="price" required value="{{ old('price') }}"
                                    class="w-full border-b py-2 text-xs outline-none focus:border-black">
                            </div>

                            <div>
                                <label class="text-[10px] uppercase text-gray-400 block mb-2 font-bold tracking-wider">Main Image (Thumbnail)</label>
                                <input type="file" name="main_image" required class="text-[10px] w-full text-gray-400 file:mr-4 file:py-1.5 file:px-3 file:border-0 file:text-[10px] file:uppercase file:tracking-wider file:font-bold file:bg-black file:text-white hover:file:bg-gray-800">
                            </div>

                            <div>
                                <label class="text-[10px] uppercase text-gray-400 block mb-2 font-bold tracking-wider">Description</label>
                                <textarea name="description" rows="4" class="w-full border border-gray-200 p-3 text-xs outline-none focus:border-black transition-all rounded-none">{{ old('description') }}</textarea>
                            </div>
                        </div>

                        {{-- Sisi Kanan: Variants --}}
                        <div class="bg-gray-50 p-4 sm:p-6 border border-gray-100 rounded-sm">
                            <div class="flex justify-between items-center mb-6 border-b border-gray-200 pb-4">
                                <h4 class="text-[10px] font-bold uppercase tracking-widest text-gray-500">Inventory Variants</h4>
                                <button type="button" onclick="addColorGroup()" class="text-[9px] bg-black text-white px-3 py-1.5 uppercase tracking-widest hover:bg-gray-800 transition active:scale-95 font-medium rounded-sm">
                                    + Add Color Group
                                </button>
                            </div>
                            
                            <div id="color-group-container" class="space-y-6 max-h-[420px] overflow-y-auto pr-1">
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 sm:mt-12 flex flex-col sm:flex-row gap-4">
                        <button type="button" onclick="toggleModal('modal-add-product')" class="w-full sm:w-1/3 border border-gray-200 text-gray-400 py-4 text-[11px] uppercase tracking-[0.3em] font-bold hover:text-black hover:border-black transition rounded-sm order-2 sm:order-1">
                            Cancel
                        </button>
                        <button type="submit" class="w-full sm:w-2/3 bg-black text-white py-4 text-[11px] uppercase tracking-[0.3em] font-bold hover:bg-gray-900 transition duration-300 active:scale-[0.99] rounded-sm order-1 sm:order-2">
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
            div.className = "color-group bg-white p-4 border border-gray-200 relative animate-fade-in mb-4 shadow-sm rounded-sm";
            
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
                        <label class="text-[8px] uppercase font-bold text-gray-400 block mb-1 tracking-wider">Photo (Optional)</label>
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

        document.addEventListener('change', (e) => {
            if(e.target.closest('.color-group')) reindexAll();
        });
    </script>

    <style>
        .animate-fade-in { animation: fadeIn 0.25s ease-out forwards; }
        .animate-modal-up { animation: modalUp 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        
        @keyframes fadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes modalUp { from { opacity: 0; transform: scale(0.98) translateY(15px); } to { opacity: 1; transform: scale(1) translateY(0); } }
        
        #color-group-container::-webkit-scrollbar { width: 4px; }
        #color-group-container::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 10px; }
        #color-group-container::-webkit-scrollbar-thumb:hover { background: #000; }
    </style>
    @endsection