<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\UserAddress; 
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /**
     * Menampilkan halaman checkout dengan ringkasan belanja & opsi buku alamat.
     */
    public function index()
    {
        $userId = Auth::id();

        // 1. Ambil data keranjang lengkap beserta relasi variant, warna, dan ukuran
        $cartItems = Cart::with(['variant.product', 'variant.color', 'variant.size'])
            ->where('user_id', $userId)
            ->get();

        // Jika keranjang belanja kosong, kembalikan ke halaman keranjang
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja Anda kosong.');
        }

        // 2. Menggunakan model UserAddress untuk daftar alamat
        $addresses = UserAddress::where('user_id', $userId)->get();

        // 3. Kirim data keranjang belanja dan koleksi alamat ke view checkout
        return view('user.checkout', compact('cartItems', 'addresses'));
    }

    /**
     * Menampilkan Halaman Riwayat "Pesanan Saya" dengan Filter Status Dinamis
     */
    public function pesananSaya(Request $request)
    {
        $userId = Auth::id();
        $statusFilter = $request->query('status');

        // Menggunakan relasi 'orderItems' yang sah
        $query = Order::with('orderItems.variant.product', 'orderItems.variant.color', 'orderItems.variant.size')
                      ->where('user_id', $userId)
                      ->latest();

        if ($statusFilter) {
            $query->where('status', $statusFilter);
        }

        $orders = $query->paginate(10);

        return view('customer.orders', compact('orders'));
    }

    /**
     * Proses pembuatan Order - VERSI DEMO (Tanpa Midtrans)
     */
    public function checkout(Request $request)
    {
        $userId = Auth::id();

        // Validasi input form yang masuk
        $request->validate([
            'address' => 'required|string',
            'payment_method' => 'required|string',
        ]);

        // Ambil data keranjang belanja
        $cartItems = Cart::with('variant.product')->where('user_id', $userId)->get();
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong, tidak dapat memproses checkout.');
        }

        return DB::transaction(function () use ($cartItems, $request, $userId) {
            
            // Hitung total belanja dari keranjang
            $totalAmount = $cartItems->sum(fn($item) => $item->variant->product->price * $item->quantity);
            $orderNumber = 'YMN-' . strtoupper(Str::random(10));

            // 1. Buat Header Order Baru
            $order = Order::create([
                'user_id'          => $userId,
                'order_number'     => $orderNumber,
                'total_amount'     => $totalAmount,
                'shipping_address' => $request->address, 
                'status'           => 'pending',
                'payment_status'   => 'unpaid'
            ]);

            // 2. Pindahkan data dari Cart ke OrderItems & kurangi stok produk
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id'           => $order->id,
                    'product_variant_id' => $item->product_variant_id,
                    'quantity'           => $item->quantity,
                    'price'              => $item->variant->product->price
                ]);

                // Kurangi stok varian secara otomatis
                if ($item->variant) {
                    $item->variant->decrement('stock', $item->quantity);
                }

                // Hapus item dari keranjang belanja user
                $item->delete(); 
            }

            // 3. Buat Data Payment Demo (Langsung bypass tanpa API Midtrans)
            Payment::create([
                'order_id'       => $order->id,
                'payment_method' => $request->payment_method, 
                'amount'         => $totalAmount,
                'status'         => 'pending',
                'snap_token'     => 'DEMO-TOKEN-' . Str::random(20)
            ]);

            // SINKRONISASI: Diarahkan ke rute orders.show (memakai s)
            return redirect()->route('orders.show', $order->id)->with('success', 'Order Demo Berhasil Dibuat!');
        });
    }

    /**
     * Menampilkan halaman detail nota pesanan (Invoice)
     */
    public function show($id)
    {
        $order = Order::with(['orderItems.variant.product', 'orderItems.variant.color', 'orderItems.variant.size', 'payment'])->findOrFail($id);
        return view('customer.show', compact('order'));
    }

    /**
     * Fitur Demo: Menampilkan Halaman Simulator Gerbang Pembayaran (Pay Demo)
     */
    public function payDemoPage($id)
    {
        $order = Order::with('payment')->findOrFail($id);
        
        if ($order->status !== 'pending') {
            return redirect()->route('orders.show', $order->id)->with('error', 'Pesanan ini sudah diproses atau telah dibayar.');
        }

        return view('customer.paydemo', compact('order'));
    }

    /**
     * Fitur Demo: Memproses Pembayaran dari Halaman Simulator ke Database
     */
    public function processPayDemo(Request $request, $id)
    {
        $order = Order::with('payment')->findOrFail($id);
        
        $method = $request->input('payment_method', 'BANK_TRANSFER');

        // Update status order menjadi diproses dan lunas
        $order->update([
            'status' => 'processing',
            'payment_status' => 'paid'
        ]);

        if ($order->payment) {
            $order->payment->update([
                'payment_method' => $method,
                'status' => 'success'
            ]);
        }

        // SINKRONISASI: Diarahkan ke rute orders.show (memakai s)
        return redirect()->route('orders.show', $order->id)->with('success', 'Simulasi pembayaran berhasil! Status pesanan Anda kini diperbarui.');
    }

    /**
     * Fitur Logistik: Memperbarui status pengiriman barang & nomor resi pelacakan
     */
    public function updateStatus(Request $request, $id)
    {
        // Validasi payload data yang dikirimkan
        $request->validate([
            'status'           => 'required|string|in:pending,processing,shipped,completed,cancelled',
            'tracking_number'  => 'nullable|string|max:100'
        ]);

        $order = Order::findOrFail($id);
        
        // Eksekusi pembaruan milestone logistik
        $order->update([
            'status'          => $request->status,
            'tracking_number' => $request->tracking_number
        ]);

        return back()->with('success', 'Status milestone dan resi pesanan berhasil diperbarui!');
    }
}