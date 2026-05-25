<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View; // Tambahkan ini
use Illuminate\Support\Facades\Auth; // Tambahkan ini
use App\Models\Cart; // Tambahkan ini

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Berbagi data jumlah keranjang ke semua file .blade.php
        View::composer('*', function ($view) {
            if (Auth::check()) {
                // Menghitung jumlah item unik di keranjang user
                $cartCount = Cart::where('user_id', Auth::id())->count();
                $view->with('cartCount', $cartCount);
            } else {
                $view->with('cartCount', 0);
            }
        });
    }
}