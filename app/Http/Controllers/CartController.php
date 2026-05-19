<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Menampilkan halaman keranjang belanja
     */
    public function index()
    {
        // Tetap menggunakan Auth::id() agar editor bersih dari garis merah
        $userId = Auth::id();

        // Eager loading sangat penting agar harga & varian muncul di Blade
        $cartItems = Cart::with(['variant.product', 'variant.color', 'variant.size'])
        ->where('user_id', Auth::id())
        ->get();

    return view('user.cart', compact('cartItems'));
    }

    /**
     * Menambah item ke keranjang
     */
    public function addToCart(Request $request)
    {
        $request->validate([
            'variant_id' => 'required|exists:product_variants,id',
            'quantity'   => 'required|integer|min:1'
        ]);

        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to continue.');
        }

        // Cek stok yang tersedia sebelum menambahkan
        $variant = ProductVariant::findOrFail($request->variant_id);
        if ($variant->stock < $request->quantity) {
            return redirect()->back()->with('error', 'Sorry, insufficient stock available.');
        }

        // Cari item yang sudah ada di keranjang
        $cart = Cart::where('user_id', Auth::id())
                    ->where('product_variant_id', $request->variant_id)
                    ->first();

        if ($cart) {
            // Cek jika penambahan melebihi stok
            if (($cart->quantity + $request->quantity) > $variant->stock) {
                return redirect()->back()->with('error', 'Cannot add more. Total in cart exceeds available stock.');
            }
            $cart->increment('quantity', $request->quantity);
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'product_variant_id' => $request->variant_id,
                'quantity' => $request->quantity
            ]);
        }

        if ($request->has('buy_now')) {
            return redirect()->route('cart.index');
        }

        return redirect()->back()->with('success', 'Product added to cart!');
    }

    /**
     * Update jumlah quantity (Plus/Minus)
     */
    public function update(Request $request, $id)
    {
        // Pastikan item milik user yang sedang login
        $cartItem = Cart::with('variant')->where('user_id', Auth::id())->findOrFail($id);
        
        if ($request->action == 'increase') {
            // Validasi stok saat tombol + diklik
            if ($cartItem->quantity < $cartItem->variant->stock) {
                $cartItem->increment('quantity');
            } else {
                return redirect()->back()->with('error', 'Maximum stock reached.');
            }
        } elseif ($request->action == 'decrease' && $cartItem->quantity > 1) {
            $cartItem->decrement('quantity');
        }

        return redirect()->back();
    }

    /**
     * Menghapus item dari keranjang
     */
    public function remove($id)
    {
        // Gunakan delete() pada hasil query yang sudah difilter user_id
        Cart::where('user_id', Auth::id())->where('id', $id)->delete();
        
        return redirect()->back()->with('success', 'Item removed from cart.');
    }
}