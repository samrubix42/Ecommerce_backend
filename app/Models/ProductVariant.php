<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariant extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'product_id',
        'sku',
        'barcode',
        'price',
        'sale_price',
        'cost_price',
        'stock',
        'low_stock_alert',
        'weight',
        'dimensions',
        'is_default',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'dimensions' => 'array',
        'is_default' => 'boolean',
        'status' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variantAttributes()
    {
        return $this->hasMany(VariantAttribute::class, 'product_variant_id');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function inventory()
    {
        return $this->hasOne(Inventory::class);
    }

    public function getNameAttribute()
    {
        if ($this->variantAttributes->isEmpty()) {
            return 'Default Variant';
        }

        return $this->variantAttributes->map(function ($attr) {
            return ($attr->attribute ? $attr->attribute->name . ': ' : '') . ($attr->value ? $attr->value->value : '');
        })->implode(' / ');
    }
}
