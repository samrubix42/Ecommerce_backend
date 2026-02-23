<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductCategory extends Model
{
    protected $fillable = [
        'parent_id',
        'title',
        'slug',
        'description',
        'status',
    ];

    /**
     * Get the parent category.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo($this, 'parent_id');
    }

    /**
     * Get the child categories.
     */
    public function children(): HasMany
    {
        return $this->hasMany($this, 'parent_id');
    }

    /**
     * Get all descendants recursively.
     */
    public function allDescendants()
    {
        return $this->children()->with('allDescendants');
    }
}
