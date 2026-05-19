<?php

namespace App\Http\Controllers;

use Midtrans\Config;
use Midtrans\Snap;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\Payment; // Tambahkan ini sesuai tabel payment kamu
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /**
     * Menampilkan halaman checkout (View Only)
     */
    public function index()
    {
        $cartItems = Cart::with(['variant.product', 'variant.color', 'variant.size'])
            ->where('user_id', Auth::id())
            ->get();

        if ($cartItems->isEmpty()) return redirect()->route('cart.index');

        return view('user.checkout', compact('cartItems'));
    }

    /**
     * Proses pembuatan Order (Aksi dari form checkout)
     */


    public function checkout(Request $request)
    {
    $cartItems = Cart::with('variant.product')->where('user_id', Auth::id())->get();
    if ($cartItems->isEmpty()) return back();

    return DB::transaction(function () use ($cartItems, $request) {
        $totalAmount = $cartItems->sum(fn($item) => $item->variant->product->price * $item->quantity);
        $orderNumber = 'YMN-' . strtoupper(Str::random(10));

        // 1. Buat Order
        $order = Order::create([
            'user_id' => Auth::id(),
            'order_number' => $orderNumber,
            'total_amount' => $totalAmount,
            'shipping_address' => $request->address,
            'status' => 'pending',
            'payment_status' => 'unpaid'
        ]);

        // 2. Buat OrderItems
        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_variant_id' => $item->product_variant_id,
                'quantity' => $item->quantity,
                'price' => $item->variant->product->price
            ]);
            $item->delete(); // Kosongkan keranjang
        }

        // 3. Konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id' => $orderNumber,
                'gross_amount' => $totalAmount,
            ],
            'customer_details' => [
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email,
            ],
            // Kamu bisa membatasi metode pembayaran di sini
            'enabled_payments' => ['qris', 'bank_transfer', 'gopay', 'shopeepay'],
        ];

        // 4. Dapatkan Snap Token dari Midtrans
        $snapToken = Snap::getSnapToken($params);

        // 5. Simpan ke Tabel Payment
        Payment::create([
            'order_id' => $order->id,
            'payment_method' => 'midtrans', // Otomatis meng-handle QRIS/VA
            'amount' => $totalAmount,
            'status' => 'pending',
            'snap_token' => $snapToken // Simpan token ini
        ]);

        return redirect()->route('order.show', $order->id);
    });
    }
    public function show($id)
    {
        $order = Order::with(['items.variant.product', 'payment'])->findOrFail($id);
        return view('user.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->update([
            'status' => $request->status,
            'tracking_number' => $request->tracking_number
        ]);

        return back()->with('success', 'Status updated!');
    }
}