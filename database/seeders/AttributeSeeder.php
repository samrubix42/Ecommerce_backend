<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attribute;
use App\Models\AttributeValue;

class AttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Colors
        $color = Attribute::create(["name" => "Color", "type" => "select"]);
        $colors = ['Red','Blue','Green','Black','White','Yellow'];
        foreach ($colors as $c) {
            AttributeValue::create(['attribute_id' => $color->id, 'value' => $c]);
        }

        // Sizes
        $size = Attribute::create(["name" => "Size", "type" => "select"]);
        $sizes = ['XS','S','M','L','XL','XXL'];
        foreach ($sizes as $s) {
            AttributeValue::create(['attribute_id' => $size->id, 'value' => $s]);
        }

        // Material
        $material = Attribute::create(["name" => "Material", "type" => "text"]);
        $materials = ['Cotton','Polyester','Leather','Silk'];
        foreach ($materials as $m) {
            AttributeValue::create(['attribute_id' => $material->id, 'value' => $m]);
        }
    }
}
