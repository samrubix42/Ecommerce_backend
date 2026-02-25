<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
   protected $fillable = [
    'product_id',
    'product_variant_id',
    'image_path',
    'is_primary',
    'sort_order'
];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

      public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }


    
}
