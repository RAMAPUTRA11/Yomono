<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    /**
     * Atribut yang dapat diisi (Mass Assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category_id', 
        'name', 
        'slug', 
        'collection_name', // Harus ada
        'description', 
        'price', 
        'image', 
        'is_featured'
    ];

    /**
     * Casting tipe data otomatis.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_featured' => 'boolean',
        'price' => 'decimal:2',
    ];

    // --- RELATIONS ---

    /**
     * Relasi ke Kategori Produk.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relasi ke Varian Produk (Warna, Size, dll).
     */
    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    /**
     * Relasi ke Galeri Gambar Tambahan.
     */
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    /**
     * Relasi ke Review/Ulasan Pelanggan.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    // --- LOGIC / HELPERS ---

    /**
     * Menghitung rata-rata rating produk.
     * * @return float
     */
    public function averageRating(): float
    {
        return (float) ($this->reviews()->avg('rating') ?? 0);
    }

    /**
     * Menghitung total stok dari semua varian.
     * * @return int
     */
    public function totalStock(): int
    {
        return (int) $this->variants()->sum('stock');
    }
}