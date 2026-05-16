<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::with('variant.product')
            ->where('user_id', Auth::id())
            ->get();

        return view('user.cart', compact('cartItems'));
    }

    public function addToCart(Request $request)
    {
        // 1. Validasi Input Dasar
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'color'      => 'required',
            'size'       => 'required',
            'quantity'   => 'required|integer|min:1'
        ]);

        // 2. Cari Variant ID berdasarkan kombinasi Product, Color, dan Size
        $variant = ProductVariant::where('product_id', $request->product_id)
            ->where('color', $request->color)
            ->where('size', $request->size)
            ->first();

        if (!$variant) {
            return redirect()->back()->with('error', 'Kombinasi warna dan ukuran tidak tersedia.');
        }

        // 3. LOGIKA BUY IT NOW (Jika user belum login)
        if ($request->has('buy_now') && !Auth::check()) {
            // Simpan pilihan ke session agar setelah login/register bisa diproses
            session(['pending_buy_now' => [
                'product_variant_id' => $variant->id,
                'quantity' => $request->quantity
            ]]);
            return redirect()->route('register')->with('info', 'Silahkan buat akun untuk melanjutkan pembelian.');
        }

        // Jika klik Buy It Now dan sudah login, bisa langsung redirect ke checkout
        if ($request->has('buy_now') && Auth::check()) {
             // Kamu bisa sesuaikan ini ke route checkoutmu
             return redirect()->route('checkout', [
                 'variant_id' => $variant->id, 
                 'qty' => $request->quantity
             ]);
        }

        // 4. LOGIKA ADD TO CART (Wajib Login)
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silahkan login terlebih dahulu.');
        }

        Cart::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'product_variant_id' => $variant->id
            ],
            ['quantity' => $request->quantity]
        );

        return redirect()->back()->with('success', 'Berhasil ditambahkan ke keranjang!');
    }
}