<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /**
     * Menampilkan halaman formulir pembayaran (Upload Bukti)
     */
    public function showUploadForm($orderId)
    {
        $order = Order::where('id', $orderId)
                      ->where('user_id', Auth::id())
                      ->firstOrFail();

        return view('user.payment-upload', compact('order'));
    }

    /**
     * Memproses upload bukti transfer dari User
     */
    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'payment_method' => 'required',
            'proof_of_payment' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Simpan file bukti transfer ke folder storage/app/public/payments
        $path = $request->file('proof_of_payment')->store('payments', 'public');

        $order = Order::findOrFail($request->order_id);

        // Buat atau Update data di tabel Payment
        Payment::updateOrCreate(
            ['order_id' => $order->id],
            [
                'payment_method' => $request->payment_method,
                'amount' => $order->total_amount,
                'payment_status' => 'pending', // Menunggu verifikasi admin
                'proof_of_payment' => $path,
            ]
        );

        return redirect()->route('orders.index')->with('success', 'Bukti pembayaran berhasil diunggah. Mohon tunggu verifikasi admin.');
    }
}