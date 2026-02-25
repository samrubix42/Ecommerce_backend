<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Attribute;
use App\Models\AttributeValue;

class AttributeSeeder extends Seeder
{
    /**
     * Seed the application's database with attributes and their values.
     *
     * Attributes table columns: id, name, slug, status, timestamps, soft_deletes
     * Attribute values columns: id, attribute_id, value, slug, timestamps, soft_deletes
     */
    public function run(): void
    {
        $attributes = [

            // ── Apparel ──
            'Color' => [
                'Red', 'Blue', 'Green', 'Black', 'White',
                'Yellow', 'Navy', 'Pink', 'Grey', 'Maroon',
                'Orange', 'Purple', 'Beige', 'Olive', 'Teal',
            ],

            'Size' => [
                'XS', 'S', 'M', 'L', 'XL', 'XXL', '3XL',
            ],

            'Material' => [
                'Cotton', 'Polyester', 'Leather', 'Silk',
                'Linen', 'Wool', 'Denim', 'Nylon', 'Satin',
            ],

            // ── Footwear ──
            'Shoe Size' => [
                'UK 5', 'UK 6', 'UK 7', 'UK 8', 'UK 9',
                'UK 10', 'UK 11', 'UK 12',
            ],

            // ── Electronics / General ──
            'Storage' => [
                '32 GB', '64 GB', '128 GB', '256 GB', '512 GB', '1 TB',
            ],

            'RAM' => [
                '4 GB', '6 GB', '8 GB', '12 GB', '16 GB', '32 GB',
            ],

            // ── Weights / Quantities ──
            'Weight' => [
                '250 g', '500 g', '1 kg', '2 kg', '5 kg',
            ],

            // ── Style ──
            'Pattern' => [
                'Solid', 'Striped', 'Checked', 'Printed', 'Floral', 'Polka Dot',
            ],

        ];

        foreach ($attributes as $name => $values) {

            $attribute = Attribute::create([
                'name'   => $name,
                'slug'   => Str::slug($name),
                'status' => true,
            ]);

            foreach ($values as $value) {
                AttributeValue::create([
                    'attribute_id' => $attribute->id,
                    'value'        => $value,
                    'slug'         => Str::slug($value),
                ]);
            }
        }
    }
}
