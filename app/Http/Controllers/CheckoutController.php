<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    /**
     * Menampilkan halaman checkout dengan ringkasan belanja.
     */
    public function index()
    {
        $userId = Auth::id();
        $cartItems = Cart::where('user_id', $userId)->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja Anda kosong.');
        }

        // Hitung total belanja dari cart
        $total = $cartItems->sum(function($i) {
            return ($i->variant->product->price ?? 0) * $i->quantity;
        });

        return view('user.checkout', compact('cartItems', 'total'));
    }

    /**
     * Memproses checkout: Pindah data dari Cart ke Order (Demo Mode).
     */
    public function process(Request $request)
    {
        $userId = Auth::id();
        $cartItems = Cart::where('user_id', $userId)->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja kosong.');
        }

        // Hitung total harga untuk disimpan ke total_amount
        $totalPrice = $cartItems->sum(function($item) {
            return $item->variant->product->price * $item->quantity;
        });

        try {
            DB::beginTransaction();

            // 1. Buat data di tabel Orders 
            // Nama kolom disesuaikan dengan screenshot HeidiSQL kamu (db_yomonoid)
// Di CheckoutController.php, ubah bagian Order::create menjadi:
            $order = Order::create([
                'user_id'          => $userId,
                'order_number'     => 'INV-' . strtoupper(uniqid()),
                'total_amount'     => $totalPrice, 
                'status'           => 'pending',
                'payment_status'   => 'unpaid',
                'shipping_address' => $request->address ?? 'Alamat Demo',
                // 'phone' => $request->phone, <-- HAPUS ATAU KOMENTARI BARIS INI
            ]);

            // 2. Pindahkan setiap item dari Cart ke OrderItems
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id'           => $order->id,
                    'product_variant_id' => $item->product_variant_id,
                    'quantity'           => $item->quantity,
                    'price'              => $item->variant->product->price,
                ]);

                // Opsional: Kurangi stok produk saat demo
                if ($item->variant) {
                    $item->variant->decrement('stock', $item->quantity);
                }
            }

            // 3. Hapus isi keranjang setelah order berhasil dibuat
            Cart::where('user_id', $userId)->delete();

            DB::commit();

            // 4. Arahkan ke halaman sukses demo
            return redirect()->route('checkout.success', $order->id)->with('success', 'Order berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollback();
            // Jika error, tampilkan pesan errornya untuk debugging
            return redirect()->back()->with('error', 'Gagal memproses checkout: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan halaman sukses setelah Generate Payment Info.
     */
    public function success($id)
    {
        // Ambil data order untuk ditampilkan di halaman sukses
        $order = Order::findOrFail($id);
        return view('user.checkout-success', compact('order'));
    }
}