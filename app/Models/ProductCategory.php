<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductCategory extends Model
{
     protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'description',
    ];

    // parent category
    public function parent()
    {
        return $this->belongsTo(ProductCategory::class, 'parent_id');
    }

    // child categories
    public function children()
    {
        return $this->hasMany(ProductCategory::class, 'parent_id');
    }
}
