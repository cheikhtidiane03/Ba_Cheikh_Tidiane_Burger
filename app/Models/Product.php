<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'image',
        'stock',
        'is_available',
        'is_archived',
    ];

    protected $casts = [
        'price'        => 'decimal:2',
        'stock'        => 'integer',
        'is_available' => 'boolean',
        'is_archived'  => 'boolean',
    ];

    // -----------------------------------------------
    // Boot : génère le slug automatiquement
    // -----------------------------------------------
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($product) {
            $product->slug = Str::slug($product->name);
        });

        static::updating(function ($product) {
            if ($product->isDirty('name')) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    // -----------------------------------------------
    // Accessors
    // -----------------------------------------------

    /**
     * URL complète de l'image (ou image par défaut)
     */
    public function getImageUrlAttribute(): string
    {
        if ($this->image && file_exists(storage_path('app/public/' . $this->image))) {
            return asset('storage/' . $this->image);
        }
        return asset('images/default-burger.png');
    }

    /**
     * Prix formaté en FCFA
     */
    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->price, 0, ',', ' ') . ' FCFA';
    }

    // -----------------------------------------------
    // Scopes (filtres réutilisables)
    // -----------------------------------------------

    /**
     * Produits disponibles dans le catalogue client
     */
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true)
                     ->where('is_archived', false)
                     ->where('stock', '>', 0);
    }

    /**
     * Produits non archivés (liste gestionnaire)
     */
    public function scopeNotArchived($query)
    {
        return $query->where('is_archived', false);
    }

    /**
     * Produits en rupture de stock
     */
    public function scopeOutOfStock($query)
    {
        return $query->where('stock', '<=', 0);
    }

    // -----------------------------------------------
    // Méthodes utilitaires
    // -----------------------------------------------

    /**
     * Est-ce que le produit peut être commandé ?
     */
    public function isOrderable(): bool
    {
        return $this->is_available && !$this->is_archived && $this->stock > 0;
    }

    // -----------------------------------------------
    // Relations
    // -----------------------------------------------

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}