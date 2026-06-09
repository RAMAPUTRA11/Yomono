<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = ['order_id', 'product_variant_id', 'quantity', 'price'];

    /**
     * Relasi ke Varian Produk
     */
    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    /**
     * SOLUSI FINISH ERROR: Relasi tidak langsung ke Product via ProductVariant
     * Menjembatani pencarian 'product' dari Controller agar tidak menghasilkan 'undefined relation'
     */
    public function product(): BelongsTo
    {
        // Karena OrderItem punya variant, dan Variant pasti punya Product, 
        // kita bisa mendefinisikan relasinya lewat class ProductVariant
        return $this->belongsTo(Product::class, 'product_variant_id')->withDefault();
    }
}