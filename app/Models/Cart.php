<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = ['user_id', 'product_variant_id', 'quantity'];

    public function variant()
    {
        // Menentukan secara manual bahwa foreign key-nya adalah product_variant_id
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }
}