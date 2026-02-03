<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
     use SoftDeletes;

    protected $fillable = [
        'name','slug','description','status','has_variants'
    ];

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

     public function scopeApplyFilters($query, array $filters)
    {
        foreach ($filters as $key => $value) {

            // skip empty filters
            if ($value === null || $value === '') {
                continue;
            }

            // price filters (variant level)
            if ($key === 'price_min') {
                $query->whereHas('variants', fn ($q) =>
                    $q->where('price', '>=', $value)
                );
                continue;
            }

            if ($key === 'price_max') {
                $query->whereHas('variants', fn ($q) =>
                    $q->where('price', '<=', $value)
                );
                continue;
            }

            // attribute filters
            $attribute = Attribute::where('name', ucfirst($key))->first();

            if (!$attribute) {
                continue;
            }

            $query->whereHas('variants.attributes', function ($q) use ($attribute, $value) {

                $operator = is_array($value) && isset($value['op'])
                    ? $value['op']
                    : '=';

                $filterValue = is_array($value)
                    ? $value['value']
                    : $value;

                $q->where('attribute_id', $attribute->id)
                  ->filterByType($attribute, $operator, $filterValue);
            });
        }

        return $query;
    }
}
