<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'category',
        'image',
        'is_available',
        'is_popular',
        'stock'
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'is_popular' => 'boolean',
        'price' => 'decimal:2',
        'category' => 'string'
    ];

    protected static function booted()
    {
        static::saving(function ($menu) {
            // Otomatis set is_available berdasarkan stok
            $menu->is_available = $menu->stock > 0;
        });
    }
public function getImageUrlAttribute()
{
    if ($this->image) {
        // If image starts with '/', treat as public asset
        if (str_starts_with($this->image, '/')) {
            return asset($this->image);
        }
        // Otherwise, treat as storage path
        return asset('storage/' . $this->image);
    }
    return null;
}

}