<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Category;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /**
         * Share data categories ke semua view (*).
         * Ini gunanya biar header di app.blade.php bisa nampilin daftar kategori 
         * secara dinamis dari database tanpa bikin error 'variable undefined'.
         */
        View::composer('*', function ($view) {
            // Kita ambil kategori dan diurutkan (optional, bisa lu hapus ->orderBy jika gak perlu)
            $categories = Category::orderBy('name', 'asc')->get();
            
            $view->with('categories', $categories);
        });
    }
}