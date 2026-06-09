<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderCustomerController extends Controller
{
    /**
     * Menampilkan daftar pesanan milik customer yang sedang login.
     */
    public function index()
    {
        // Mengambil data order berdasarkan id user yang login saat ini
        $orders = Order::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        // Mengirimkan variabel $orders ke view customer/orders.blade.php
        return view('customer.orders', compact('orders'));
    }
}