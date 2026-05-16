<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string'
        ]);

        // Cek apakah user benar-benar sudah beli dan statusnya completed
        $hasBought = Order::where('user_id', Auth::id())
            ->where('status', 'completed')
            ->whereHas('items.variant', function($q) use ($request) {
                $q->where('product_id', $request->product_id);
            })->exists();

        if (!$hasBought) {
            return back()->with('error', 'You can only review products you have received.');
        }

        Review::create([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
            'rating' => $request->rating,
            'comment' => $request->comment
        ]);

        return back()->with('success', 'Thank you for your review!');
    }
}
