<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Admin\ProductController as AdminProduct;
use App\Http\Controllers\Admin\TransactionController as AdminOrder;
use App\Http\Controllers\Admin\CategoryController;
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


/*
|--------------------------------------------------------------------------
| Auth Routes (Pindahkan ke satu tempat agar tidak bentrok)
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Google Auth
Route::get('auth/google', [AuthController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('google.callback');

/*
|--------------------------------------------------------------------------
| Informational Pages (Grup rute statis)
|--------------------------------------------------------------------------
*/
Route::prefix('pages')->name('pages.')->group(function () {
    // INFO: Hapus login/register/shop dari sini karena sudah ada di Public Routes di atas
    
    Route::get('/faq', fn() => view('pages.faq'))->name('faq');
    Route::get('/returns-shipping', fn() => view('pages.returns-shipping'))->name('returns-shipping');
    Route::get('/how-to-purchase', fn() => view('pages.how-to-purchase'))->name('how-to-purchase');
    Route::get('/sizing-guide', fn() => view('pages.sizing-guide'))->name('sizing-guide');
    Route::get('/stores', fn() => view('pages.stores'))->name('stores');
    Route::get('/career', fn() => view('pages.career'))->name('career');
    Route::get('/journal', fn() => view('pages.journal'))->name('journal');
});

/*
|--------------------------------------------------------------------------
| API / AJAX Routes
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
    // --- CART SYSTEM ---
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
    Route::get('/profile', [AuthController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
    // Perbaikan: Tambahkan route Update dan Remove (Wajib ada untuk fitur di halaman cart)
    Route::patch('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

    // --- CHECKOUT & ORDERS ---
    // Perbaikan: Checkout biasanya GET untuk menampilkan form, dan POST untuk proses pesanan
    Route::get('/checkout', [OrderController::class, 'index'])->name('checkout'); 
    Route::post('/checkout/process', [OrderController::class, 'processCheckout'])->name('checkout.process');
    
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('order.show');

    // --- PAYMENT ---
    Route::get('/payment/upload/{orderId}', [PaymentController::class, 'showUploadForm'])->name('payment.upload');
    Route::post('/payment/store', [PaymentController::class, 'store'])->name('payment.store');

    // --- REVIEWS ---
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