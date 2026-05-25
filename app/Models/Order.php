<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    /**
     * fillable: Mendaftarkan kolom yang diizinkan untuk diisi secara massal.
     * Pastikan nama kolom di sini SAMA PERSIS dengan yang ada di HeidiSQL kamu.
     */
    protected $fillable = [
        'user_id', 
        'order_number', 
        'total_amount',      // Sesuai screenshot HeidiSQL terakhir
        'status', 
        'payment_status',    // Sesuai screenshot HeidiSQL terakhir
        'shipping_address',  // Sesuai screenshot HeidiSQL terakhir
        'tracking_number',
        'phone'
    ];

    /**
     * Relasi ke User (Pembeli)
     */
    public function user() 
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke OrderItems (Detail barang yang dibeli)
     */
    public function items() 
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Relasi ke Payment (Jika kamu punya tabel pembayaran terpisah)
     */
    public function payment() 
    {
        return $this->hasOne(Payment::class);
    }
}