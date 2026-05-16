<?php

namespace App\Http\Controllers;

use App\Models\{Product, Category};
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::with(['category', 'variants'])
            ->latest()
            ->take(8)
            ->get();

        return view('user.home', compact('featuredProducts')); 
    }

    public function shop(Request $request)
    {
        $categories = Category::all();
        $products = Product::with(['category', 'variants'])
            ->when($request->category, function ($query, $slug) {
                return $query->whereHas('category', function ($q) use ($slug) {
                    $q->where('slug', $slug);
                });
            })
            ->when($request->availability, function ($query, $status) {
                if ($status == 'in_stock') {
                    return $query->whereHas('variants', function ($q) { $q->where('stock', '>', 0); });
                } 
                return $query->whereHas('variants', function ($q) { $q->where('stock', '<=', 0); });
            })
            ->latest()
            ->get();

       return view('user.product_detail', compact('products', 'categories'));
    }

    public function show($slug)
    {
        $product = Product::with(['category', 'variants'])->where('slug', $slug)->firstOrFail();

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->limit(4)
            ->get();

        return view('user.product_detail', compact('product', 'relatedProducts'));
    }
}