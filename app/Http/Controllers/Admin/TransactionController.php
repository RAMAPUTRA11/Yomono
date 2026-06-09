<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Color;
use App\Models\Size;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Menampilkan Dashboard Utama Admin / Statistik Ringkas
     */
    public function index()
    {
        $orders = Order::with('user')->latest()->take(5)->get();
        return view('admin.dashboard', compact('orders'));
    }

    /**
     * Menampilkan Semua Daftar Transaksi Pelanggan
     */
    public function allOrders()
    {
        $orders = Order::with('user')->latest()->get();
        return view('admin.orders', compact('orders'));
    }

    /**
     * FIX ERROR: Menampilkan Detail Single Transaksi
     * Mengubah nama view dari 'admin.orders_show' menjadi 'admin.orders_detail'
     */
    public function show($id)
    {
        $order = Order::with([
            'user', 
            'orderItems.variant.product', 
            'orderItems.variant.color', 
            'orderItems.variant.size'
        ])->findOrFail($id);
        
        // Disesuaikan dengan nama file fisik Anda: orders_detail.blade.php
        return view('admin.orders_detail', compact('order')); 
    }

    /**
     * Menyetujui Pembayaran Masuk (Confirm Payment)
     */
    public function confirmPayment($id)
    {
        $order = Order::findOrFail($id);
        $order->update([
            'payment_status' => 'paid',
            'status' => 'processing'
        ]);

        return redirect()->back()->with('success', 'Status pembayaran berhasil dikonfirmasi.');
    }

    /**
     * Menolak Pesanan / Pembayaran (Reject Order)
     */
    public function rejectOrder($id)
    {
        $order = Order::findOrFail($id);
        $order->update([
            'payment_status' => 'failed',
            'status' => 'cancelled'
        ]);

        return redirect()->back()->with('success', 'Pesanan telah ditolak.');
    }

    /**
     * Menampilkan Manajemen Atribut (Warna & Ukuran)
     */
    public function attributes()
    {
        $colors = Color::all();
        $sizes = Size::all();
        return view('admin.attributes', compact('colors', 'sizes'));
    }

    /**
     * Menyimpan Atribut Warna Baru
     */
    public function storeColor(Request $request)
    {
        $request->validate(['name' => 'required|string|unique:colors,name']);
        Color::create(['name' => $request->name]);
        return redirect()->back()->with('success', 'Atribut warna berhasil ditambahkan.');
    }

    /**
     * Menyimpan Atribut Ukuran Baru
     */
    public function storeSize(Request $request)
    {
        $request->validate(['name' => 'required|string|unique:sizes,name']);
        Size::create(['name' => $request->name]);
        return redirect()->back()->with('success', 'Atribut ukuran berhasil ditambahkan.');
    }

    /**
     * FIX ERROR: Menangani Aksi Pengiriman Milestone Status dari Modal Update Tracking
     */
    public function updateStatus(Request $request, $id)
    {
        // Validasi input status agar sinkron dengan struktur database dan modal pilihan Anda
        $request->validate([
            'status' => 'required|string|in:pending,processing,shipped,completed,cancelled',
            'tracking_number' => 'nullable|string|max:100'
        ]);

        $order = Order::findOrFail($id);
        
        // Update data tracking logistik
        $order->update([
            'status' => $request->status,
            'tracking_number' => $request->tracking_number ?? $order->tracking_number
        ]);

        return redirect()->back()->with('success', 'Milestone pengiriman pesanan berhasil diperbarui!');
    }
}