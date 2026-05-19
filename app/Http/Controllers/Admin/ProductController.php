<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Product, Category, Color, Size, ProductVariant};
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\{Storage, DB};

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
        $query->where('collection', $collection);
    }

    // 3. Tambahkan Filter Search (Jika ada di URL)
    if ($request->has('search')) {
        $query->where('name', 'like', '%' . $request->search . '%');
    }

    // 4. Ambil Hasilnya (Tetap menggunakan latest() seperti kodingan awalmu)
    $products = $query->latest()->get();

    // 5. Variabel Awal (Jangan diubah/dihapus agar admin tetap jalan)
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

    // --- FITUR BARU: Filter Collection (Koleksi Terbaru) ---
    if (request('collection') && request('collection') != 'all categories') {
        $query->where('collection_name', request('collection'));
    }

    // Filter Kategori (Tetap Aman)
    if (request('category')) {
        $query->whereHas('category', function($q) {
            $q->where('slug', request('category'));
        });
    }

    // Filter Stok (Tetap Aman)
    if (request('availability') == 'in_stock') {
        $query->whereHas('variants', function($q) { 
            $q->where('stock', '>', 0); 
        });
    } elseif (request('availability') == 'out_of_stock') {
        $query->whereDoesntHave('variants', function($q) { 
            $q->where('stock', '>', 0); 
        });
    }

    // Sorting (Tetap Aman)
    switch (request('sort')) {
        case 'price-asc': $query->orderBy('price', 'asc'); break;
        case 'price-desc': $query->orderBy('price', 'desc'); break;
        case 'alphabet-asc': $query->orderBy('name', 'asc'); break;
        case 'alphabet-desc': $query->orderBy('name', 'desc'); break;
        default: $query->latest(); break;
    }

    $products = $query->get();
    
    // Pastikan view-nya mengarah ke 'pages.shop' jika filenya ada di folder pages
    return view('shop', compact('products', 'categories'));
    }

    /**
     * Simpan Produk Baru dengan Grouping Warna
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'category_id'   => 'required|exists:categories,id',
            'price'         => 'required|numeric',
            'main_image'    => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'variants'      => 'required|array|min:1',
            'variants.*.color_id' => 'required|exists:colors,id',
            'variants.*.sizes'    => 'required|array',
            'variants.*.stocks'   => 'required|array',
        ]);

        try {
            // Kita bungkus dalam transaksi agar jika varian gagal, produk tidak terbuat (tidak setengah-setengah)
            DB::transaction(function () use ($request) {
                
                // 1. Simpan Gambar Utama
                $mainImagePath = $request->file('main_image')->store('products/main', 'public');

                // 2. Simpan Data Produk Utama
                $product = Product::create([
                    'category_id' => $request->category_id,
                    'name'        => $request->name,
                    'slug'        => Str::slug($request->name),
                    'price'       => $request->price,
                    'description' => $request->description ?? '-',
                    'image'       => $mainImagePath,
                ]);

                // 3. Simpan Varian per Warna
                if ($request->has('variants')) {
                    foreach ($request->variants as $vData) {
                        
                        // Cek apakah ada gambar khusus untuk warna ini, jika tidak pakai gambar utama
                        $vImagePath = $mainImagePath;
                        if (isset($vData['image']) && $vData['image'] instanceof \Illuminate\Http\UploadedFile) {
                            $vImagePath = $vData['image']->store('products/variants', 'public');
                        }

                        // Simpan setiap ukuran dan stok yang diinput untuk warna ini
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

            // Return diletakkan di luar closure transaction agar redirect berjalan lancar
            return redirect()->route('admin.products.index')->with('success', 'Produk dan varian berhasil ditambahkan!');

        } catch (\Exception $e) {
            // Jika gagal, kembalikan ke form dengan pesan error dan input yang lama
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan produk: ' . $e->getMessage());
        }
    }

    /**
     * Halaman Detail Produk untuk User
     */
    public function show($id)
    {
        $product = Product::with(['variants.color', 'variants.size', 'category'])->findOrFail($id);
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
     * Update Produk
     */
    public function update(Request $request, $id)
    {
    $request->validate([
        'name'        => 'required|string|max:255',
        'category_id' => 'required|exists:categories,id',
        'price'       => 'required|numeric',
        'main_image'  => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        'variants'    => 'required|array|min:1',
        'variants.*.color_id' => 'required|exists:colors,id',
        'variants.*.sizes'    => 'required|array',
        'variants.*.stocks'   => 'required|array',
    ]);

    $product = Product::findOrFail($id);

    try {
        DB::transaction(function () use ($request, $product) {
            // 1. Update Data Utama Produk
            $productData = [
                'category_id' => $request->category_id,
                'name'        => $request->name,
                'slug'        => \Illuminate\Support\Str::slug($request->name),
                'price'       => $request->price,
                'description' => $request->description ?? '-',
            ];

            // Jika ada gambar utama baru
            if ($request->hasFile('main_image')) {
                if ($product->image) {
                    Storage::disk('public')->delete($product->image);
                }
                $productData['image'] = $request->file('main_image')->store('products/main', 'public');
            }

            $product->update($productData);

            // 2. Kelola Varian (Hapus yang lama, simpan yang baru)
            // Catatan: Jika ingin lebih advance, Anda bisa mencocokkan ID agar tidak menghapus gambar
            $product->variants()->delete(); 

            foreach ($request->variants as $vData) {
                // Tentukan gambar varian (default ke gambar utama produk)
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
     * Hapus Produk
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        
        // Hapus file gambar utama dari storage
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        // Hapus gambar-gambar varian jika filenya berbeda dari gambar utama
        foreach($product->variants as $v) {
            if($v->image && $v->image != $product->image) {
                Storage::disk('public')->delete($v->image);
            }
        }
        
        $product->delete();
        return redirect()->back()->with('success', 'Produk berhasil dihapus.');
    }
}