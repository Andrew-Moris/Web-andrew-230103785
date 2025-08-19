<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'code',
        'model',
        'price',
        'description',
        'image',
        'stock',
        'purchases'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
    ];

    /**
     * Get purchases for this product
     */
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    /**
     * Check if product is in stock
     */
    public function isInStock($quantity = 1)
    {
        return $this->stock >= $quantity;
    }

    /**
     * Reduce stock after purchase
     */
    public function reduceStock($quantity = 1)
    {
        if ($this->isInStock($quantity)) {
            $this->decrement('stock', $quantity);
            return true;
        }
        return false;
    }

    /**
     * Add stock
     */
    public function addStock($quantity)
    {
        $this->increment('stock', $quantity);
    }
}
