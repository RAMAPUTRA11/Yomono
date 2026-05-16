<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Admin\ProductController;

// Admin Controllers
use App\Http\Controllers\Admin\ProductController as AdminProduct;
use App\Http\Controllers\Admin\TransactionController as AdminOrder;
use App\Http\Controllers\Admin\CategoryController;

// Import untuk Search API
use App\Models\Product;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/shop', [AdminProduct::class, 'shop'])->name('shop');
Route::get('/product/{id}', [AdminProduct::class, 'show'])->name('product.show');

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::prefix('pages')->group(function () {
    // Auth Pages
    Route::get('/login', function () { return view('auth.login'); })->name('login');
    Route::get('/register', function () { return view('auth.register'); })->name('register');
    Route::get('/shop', [ProductController::class, 'index'])->name('shop');
    
    // Cart Page
    Route::get('/cart', function () { return view('pages.cart'); })->name('cart');
    
    // 1. NEED HELP
    Route::get('/faq', function () {
        return view('pages.faq');
    })->name('pages.faq');

    Route::get('/returns-shipping', function () {
        return view('pages.returns-shipping');
    })->name('pages.returns-shipping');

    Route::get('/how-to-purchase', function () {
        return view('pages.how-to-purchase');
    })->name('pages.how-to-purchase');

    Route::get('/sizing-guide', function () {
        return view('pages.sizing-guide');
    })->name('pages.sizing-guide');

    // 2. ABOUT US
    Route::get('/stores', function () {
        return view('pages.stores');
    })->name('pages.stores');

    Route::get('/career', function () {
        return view('pages.career'); // Untuk join #yomonoteam
    })->name('pages.career');

    Route::get('/journal', function () {
        return view('pages.journal');
    })->name('pages.journal');
});

/*
|--------------------------------------------------------------------------
| API / AJAX Routes (Untuk Search Suggestion)
|--------------------------------------------------------------------------
*/
Route::get('/api/search-suggestions', function (Request $request) {
    $query = $request->get('q');
    
    $products = Product::where('name', 'LIKE', "%{$query}%")
                ->limit(6)
                ->get()
                ->map(function($product) {
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'slug' => $product->id, // Sesuaikan jika lu pakai ID atau Slug untuk link
                        'price' => number_format($product->price, 0, ',', '.'),
                        'image_url' => asset('storage/' . $product->image) 
                    ];
                });

    return response()->json($products);
});

/*
|--------------------------------------------------------------------------
| Customer Routes (Wajib Login)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
    
    Route::post('/checkout', [OrderController::class, 'checkout'])->name('checkout');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('order.show');

    Route::get('/payment/upload/{orderId}', [PaymentController::class, 'showUploadForm'])->name('payment.upload');
    Route::post('/payment/store', [PaymentController::class, 'store'])->name('payment.store');

    Route::post('/review/store', [ReviewController::class, 'store'])->name('review.store');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminOrder::class, 'index'])->name('dashboard');

    // Manajemen Produk
    Route::get('/products', [AdminProduct::class, 'index'])->name('products.index');
    Route::post('/products', [AdminProduct::class, 'store'])->name('products.store');
    Route::get('/products/{id}/edit', [AdminProduct::class, 'edit'])->name('products.edit');
    Route::put('/products/{id}', [AdminProduct::class, 'update'])->name('products.update');
    Route::delete('/products/{id}', [AdminProduct::class, 'destroy'])->name('products.destroy');

    Route::resource('categories', CategoryController::class);

    // Atribut & Transaksi
    Route::get('/attributes', [AdminOrder::class, 'attributes'])->name('attributes.index');
    Route::post('/colors', [AdminOrder::class, 'storeColor'])->name('colors.store');
    Route::post('/sizes', [AdminOrder::class, 'storeSize'])->name('sizes.store');

    Route::get('/orders', [AdminOrder::class, 'allOrders'])->name('orders.index');
    Route::get('/orders/{id}', [AdminOrder::class, 'show'])->name('orders.show');
    Route::post('/orders/{id}/confirm', [AdminOrder::class, 'confirmPayment'])->name('orders.confirm');
    Route::post('/orders/{id}/reject', [AdminOrder::class, 'rejectOrder'])->name('orders.reject');
});