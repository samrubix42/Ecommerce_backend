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
                'meta_description' => 'All electronic devices and gadgets',
            ],
            [
                'title' => 'Clothing',
                'slug' => 'clothing',
                'meta_description' => 'Fashion and apparel products',
            ],
            [
                'title' => 'Books',
                'slug' => 'books',
                'meta_description' => 'Physical and digital books',
            ],
            [
                'title' => 'Home & Garden',
                'slug' => 'home-garden',
                'meta_description' => 'Home decor and gardening supplies',
            ],
            [
                'title' => 'Sports & Outdoors',
                'slug' => 'sports-outdoors',
                'meta_description' => 'Sports equipment and outdoor gear',
            ],
            [
                'title' => 'Beauty & Personal Care',
                'slug' => 'beauty-personal-care',
                'meta_description' => 'Cosmetics and personal care products',
            ],
            [
                'title' => 'Toys & Games',
                'slug' => 'toys-games',
                'meta_description' => 'Children toys and games',
            ],
            [
                'title' => 'Food & Beverages',
                'slug' => 'food-beverages',
                'meta_description' => 'Food items and drinks',
            ],
        ];

        foreach ($categories as $category) {
            ProductCategory::create($category);
        }
    }
}
