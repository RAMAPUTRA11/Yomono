<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Color;
use App\Models\Size;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TransactionController extends Controller
{
    /**
     * Dashboard Summary
     */
public function index(): View
{
    $orders = Order::with('user')->latest()->take(5)->get();
    $stats = [
        // SESUAIKAN DENGAN NAMA KOLOM DI DB KAMU (total_price)
        'total_sales' => Order::where('payment_status', 'paid')->sum('total_amount') ?? 0, 
        'total_orders' => Order::count(),
        'pending_orders' => Order::where('payment_status', 'pending')->count(),
        'total_products' => Product::count(),
    ];

    return view('admin.dashboard', compact('orders', 'stats'));
}

    /**
     * HALAMAN MANAGE ATTRIBUTES (Warna & Ukuran)
     */
    public function attributes(): View
    {
        $colors = Color::all();
        $sizes = Size::all();
        return view('admin.attributes', compact('colors', 'sizes'));
    }

    /**
     * Simpan Warna Baru
     */
    public function storeColor(Request $request): RedirectResponse
    {
        $request->validate(['name' => 'required|unique:colors,name']);
        Color::create(['name' => strtoupper($request->name)]);
        return redirect()->back()->with('success', 'Warna berhasil ditambahkan!');
    }

    /**
     * Simpan Ukuran Baru
     */
    public function storeSize(Request $request): RedirectResponse
    {
        $request->validate(['name' => 'required|unique:sizes,name']);
        Size::create(['name' => strtoupper($request->name)]);
        return redirect()->back()->with('success', 'Ukuran berhasil ditambahkan!');
    }

    /**
     * Manajemen Transaksi
     */
    public function allOrders(): View
    {
        $orders = Order::with(['user', 'orderItems.product'])->latest()->get();
        return view('admin.orders', compact('orders'));
    }

    public function show(int $id): View
    {
        $order = Order::with(['user', 'orderItems.product', 'payment'])->findOrFail($id);
        return view('admin.order_detail', compact('order'));
    }

    public function confirmPayment(int $id): RedirectResponse
    {
        $order = Order::findOrFail($id);
        $order->update(['payment_status' => 'paid', 'status' => 'processing']);
        return redirect()->back()->with('success', "Transaksi Berhasil!");
    }

    public function rejectOrder(int $id): RedirectResponse
    {
        $order = Order::findOrFail($id);
        $order->update(['payment_status' => 'failed', 'status' => 'cancelled']);
        return redirect()->back()->with('error', "Transaksi Dibatalkan.");
    }
}