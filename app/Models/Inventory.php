<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $fillable = [
        'product_variant_id',
        'quantity',
        'reserved_quantity',
        'low_stock_threshold',
        'track_inventory',
    ];

    protected $casts = [
        'track_inventory' => 'boolean',
        'quantity' => 'integer',
        'reserved_quantity' => 'integer',
        'low_stock_threshold' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public function logs()
    {
        return $this->hasMany(InventoryLog::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Helper
    |--------------------------------------------------------------------------
    */

    public function getAvailableQuantityAttribute()
    {
        // Now that quantity represents sellable stock, we return it directly. 
        // Total physical stock would be (quantity + reserved_quantity).
        return $this->quantity;
    }
}
