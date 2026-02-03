<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VariantAttribute extends Model
{
    protected $fillable = [
        'product_variant_id',
        'attribute_id',
        'attribute_value_id',
        'value'
    ];

    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }

   public function scopeFilterByType(
    $query,
    Attribute $attribute,
    string $operator,
    $value
) {
    return match ($attribute->type) {

        'string' =>
            $query->where('value', $value),

        'boolean' =>
            $query->where('value', $value ? '1' : '0'),

        'number' =>
            $query->whereRaw(
                'CAST(value AS DECIMAL(10,2)) ' . $operator . ' ?',
                [$value]
            ),

        'date' =>
            $query->whereRaw(
                'CAST(value AS DATE) ' . $operator . ' ?',
                [$value]
            ),
    };
}

}
