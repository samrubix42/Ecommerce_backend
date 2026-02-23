<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'title' => 'Electronics',
                'slug' => 'electronics',
                'description' => 'All electronic devices and gadgets',
            ],
            [
                'title' => 'Clothing',
                'slug' => 'clothing',
                'description' => 'Fashion and apparel products',
            ],
            [
                'title' => 'Books',
                'slug' => 'books',
                'description' => 'Physical and digital books',
            ],
            [
                'title' => 'Home & Garden',
                'slug' => 'home-garden',
                'description' => 'Home decor and gardening supplies',
            ],
            [
                'title' => 'Sports & Outdoors',
                'slug' => 'sports-outdoors',
                'description' => 'Sports equipment and outdoor gear',
            ],
            [
                'title' => 'Beauty & Personal Care',
                'slug' => 'beauty-personal-care',
                'description' => 'Cosmetics and personal care products',
            ],
            [
                'title' => 'Toys & Games',
                'slug' => 'toys-games',
                'description' => 'Children toys and games',
            ],
            [
                'title' => 'Food & Beverages',
                'slug' => 'food-beverages',
                'description' => 'Food items and drinks',
            ],
        ];

        foreach ($categories as $category) {
            ProductCategory::create($category);
        }
    }
}
