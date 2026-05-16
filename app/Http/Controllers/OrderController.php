<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function checkout(Request $request)
    {
        $cartItems = Cart::where('user_id', Auth::id())->get();
        if ($cartItems->isEmpty()) return back();

        $totalAmount = $cartItems->sum(function($item) {
            return $item->variant->product->price * $item->quantity;
        });

        $order = Order::create([
            'user_id' => Auth::id(),
            'order_number' => 'YMN-' . strtoupper(Str::random(10)),
            'total_amount' => $totalAmount,
            'shipping_address' => $request->address,
            'status' => 'pending',
            'payment_status' => 'unpaid'
        ]);

        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_variant_id' => $item->product_variant_id,
                'quantity' => $item->quantity,
                'price' => $item->variant->product->price
            ]);
            $item->delete();
        }

        return redirect()->route('order.show', $order->id);
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