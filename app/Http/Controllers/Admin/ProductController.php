<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Color;
use App\Models\Size;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * Halaman List Produk untuk Admin
     */
    public function index(Request $request)
    {
        // 1. Ambil Query Awal
        $query = Product::with(['category', 'variants']);

        // 2. Tambahkan Filter Koleksi (Jika ada di URL)
        if ($request->has('collection')) {
            $collection = $request->collection;
            // Mencari produk yang kolom 'collection' nya sesuai dengan parameter URL
            $query->where('collection_name', $collection);
        }

        // 3. Tambahkan Filter Search (Jika ada di URL)
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // 4. Ambil Hasilnya
        $products = $query->latest()->get();

        // 5. Variabel Awal untuk Dropdown Form Modal
        $categories = Category::all(); 
        $colors = Color::all(); 
        $sizes = Size::all();

        return view('admin.products', compact('products', 'categories', 'colors', 'sizes'));
    }

    /**
     * Halaman Shop untuk User (Katalog dengan Filter)
     */
    public function shop()
    {
        $categories = Category::all();
        $query = Product::with(['category', 'variants']);

        // Filter Collection (Koleksi Terbaru)
        if (request('collection') && request('collection') != 'all categories') {
            $query->where('collection_name', request('collection'));
        }

        // Filter Kategori
        if (request('category')) {
            $query->whereHas('category', function($q) {
                $q->where('slug', request('category'));
            });
        }

        // Filter Stok Availability
        if (request('availability') == 'in_stock') {
            $query->whereHas('variants', function($q) { 
                $q->where('stock', '>', 0); 
            });
        } elseif (request('availability') == 'out_of_stock') {
            $query->whereDoesntHave('variants', function($q) { 
                $q->where('stock', '>', 0); 
            });
        }

        // Sorting Katalog
        switch (request('sort')) {
            case 'price-asc': $query->orderBy('price', 'asc'); break;
            case 'price-desc': $query->orderBy('price', 'desc'); break;
            case 'alphabet-asc': $query->orderBy('name', 'asc'); break;
            case 'alphabet-desc': $query->orderBy('name', 'desc'); break;
            default: $query->latest(); break;
        }

        $products = $query->get();
        
        return view('shop', compact('products', 'categories'));
    }

    /**
     * Simpan Produk Baru dengan Grouping Warna
     */
    public function store(Request $request)
    {
        // 1. Validasi Form Input
        $request->validate([
            'name'                => 'required|string|max:255',
            'category_id'         => 'required|exists:categories,id',
            'price'               => 'required|numeric',
            'main_image'          => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'collection_name'     => 'nullable|string|max:255',
            'variants'            => 'required|array|min:1',
            'variants.*.color_id' => 'required|exists:colors,id',
            'variants.*.sizes'    => 'required|array',
            'variants.*.stocks'   => 'required|array',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $mainImagePath = $request->file('main_image')->store('products/main', 'public');

                // 2. Simpan ke tabel products
                $product = Product::create([
                    'category_id'     => $request->category_id,
                    'name'            => $request->name,
                    'slug'            => Str::slug($request->name),
                    'price'           => $request->price,
                    'description'     => $request->description ?? '-',
                    'image'           => $mainImagePath,
                    'collection_name' => $request->collection_name,
                    'is_featured'     => 0,
                ]);

                // 3. Simpan Relasi Varian Produk
                if ($request->has('variants')) {
                    foreach ($request->variants as $vData) {
                        $vImagePath = $mainImagePath;
                        if (isset($vData['image']) && $vData['image'] instanceof \Illuminate\Http\UploadedFile) {
                            $vImagePath = $vData['image']->store('products/variants', 'public');
                        }

                        foreach ($vData['sizes'] as $sKey => $sizeId) {
                            $product->variants()->create([
                                'color_id' => $vData['color_id'],
                                'size_id'  => $sizeId,
                                'stock'    => $vData['stocks'][$sKey] ?? 0,
                                'image'    => $vImagePath,
                            ]);
                        }
                    }
                }
            });

            return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan!');

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    /**
     * Halaman Detail Produk untuk User
     */
    public function show($slug)
    {
        $product = Product::with(['variants.color', 'variants.size', 'category'])
            ->where('slug', $slug)
            ->orWhere('id', $slug)
            ->firstOrFail();

        return view('user.product_detail', compact('product'));
    }

    /**
     * Edit Produk (Form)
     */
    public function edit($id)
    {
        $product = Product::with('variants')->findOrFail($id);
        $categories = Category::all();
        $colors = Color::all();
        $sizes = Size::all();
        return view('admin.products_edit', compact('product', 'categories', 'colors', 'sizes'));
    }

    /**
     * Update Data Produk & Regenerasi Varian
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'                => 'required|string|max:255',
            'category_id'         => 'required|exists:categories,id',
            'price'               => 'required|numeric',
            'main_image'          => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'variants'            => 'required|array|min:1',
            'variants.*.color_id' => 'required|exists:colors,id',
            'variants.*.sizes'    => 'required|array',
            'variants.*.stocks'   => 'required|array',
        ]);

        $product = Product::findOrFail($id);

        try {
            DB::transaction(function () use ($request, $product) {
                // 1. Susun Data Utama Produk
                $productData = [
                    'category_id' => $request->category_id,
                    'name'        => $request->name,
                    'slug'        => Str::slug($request->name),
                    'price'       => $request->price,
                    'description' => $request->description ?? '-',
                ];

                // Jika ada manajemen pergantian gambar utama baru
                if ($request->hasFile('main_image')) {
                    if ($product->image) {
                        Storage::disk('public')->delete($product->image);
                    }
                    $productData['image'] = $request->file('main_image')->store('products/main', 'public');
                }

                $product->update($productData);

                // 2. Refresh Varian (Hapus lama untuk menghindari anomali stock)
                $product->variants()->delete(); 

                foreach ($request->variants as $vData) {
                    $vImagePath = $product->image;

                    if (isset($vData['image']) && $vData['image'] instanceof \Illuminate\Http\UploadedFile) {
                        $vImagePath = $vData['image']->store('products/variants', 'public');
                    }

                    foreach ($vData['sizes'] as $sKey => $sizeId) {
                        $product->variants()->create([
                            'color_id' => $vData['color_id'],
                            'size_id'  => $sizeId,
                            'stock'    => $vData['stocks'][$sKey] ?? 0,
                            'image'    => $vImagePath,
                        ]);
                    }
                }
            });

            return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diupdate!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }

    /**
     * Hapus Produk Beserta Gambar dari File Storage
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        
        // Hapus file gambar utama dari storage
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        // Hapus gambar varian jika filenya berbeda dari gambar utama produk
        foreach($product->variants as $v) {
            if($v->image && $v->image != $product->image) {
                Storage::disk('public')->delete($v->image);
            }
        }
        
        $product->delete();
        return redirect()->back()->with('success', 'Produk berhasil dihapus.');
    }
}