<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use app\Models\Order;
use app\Models\Cart;
use app\Models\Review;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role'];

    // Relasi ke Pesanan
    public function orders() {
        return $this->hasMany(Order::class);
    }

    // Relasi ke Keranjang
    public function carts() {
        return $this->hasMany(Cart::class);
    }

    // Relasi ke Ulasan
    public function reviews() {
        return $this->hasMany(Review::class);
    }
}
