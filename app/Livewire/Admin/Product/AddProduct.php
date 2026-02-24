<?php

namespace App\Livewire\Admin\Product;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductImage;


class AddProduct extends Component
{
      use WithFileUploads;

    public int $step = 1;

    // Basic
    public string $name = '';
    public string $slug = '';
    public ?int $category_id = null;
    public string $description = '';
    public bool $has_variants = false;

    // Simple Product
    public $simple_price;
    public $simple_stock;
    public array $product_images = [];

    // Variant Mode
    public array $variants = [];

    public function updatedName()
    {
        $this->slug = Str::slug($this->name);
    }

    public function addVariant()
    {
        $this->variants[] = [
            'sku' => '',
            'price' => '',
            'stock' => '',
            'images' => [],
        ];
    }

    public function removeVariant($index)
    {
        unset($this->variants[$index]);
        $this->variants = array_values($this->variants);
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|min:3',
            'slug' => 'required|string',
            'product_images.*' => 'nullable|image|max:5120',
        ]);

        $product = Product::create([
            'category_id' => $this->category_id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'has_variants' => $this->has_variants,
            'status' => 'active'
        ]);

        // store product images
        foreach ($this->product_images ?? [] as $idx => $file) {
            if (!$file) continue;
            $path = $file->store('products', 'public');
            ProductImage::create([
                'imageable_id' => $product->id,
                'imageable_type' => Product::class,
                'image_path' => $path,
                'is_primary' => $idx === 0,
                'sort_order' => $idx,
            ]);
        }
        if (!$this->has_variants) {

            $product->variants()->create([
                'sku' => 'DEFAULT-' . strtoupper(Str::random(5)),
                'price' => $this->simple_price,
                'stock' => $this->simple_stock,
                'is_default' => true
            ]);

        } else {

            foreach ($this->variants as $variant) {
                $created = $product->variants()->create([
                    'sku' => $variant['sku'] ?? ('VAR-' . strtoupper(Str::random(5))),
                    'price' => $variant['price'] ?? 0,
                    'stock' => $variant['stock'] ?? 0,
                    'is_default' => false,
                ]);

                // variant images
                if (!empty($variant['images'])) {
                    foreach ($variant['images'] as $vidx => $vfile) {
                        if (!$vfile) continue;
                        $vpath = $vfile->store('variants', 'public');
                        ProductImage::create([
                            'imageable_id' => $created->id,
                            'imageable_type' => ProductVariant::class,
                            'image_path' => $vpath,
                            'is_primary' => $vidx === 0,
                            'sort_order' => $vidx,
                        ]);
                    }
                }
            }
        }

        session()->flash('success', 'Product Created Successfully!');
        return redirect()->route('products.index');
    }
    #[Layout('layouts.admin')]
    public function render()
    {
        return view('livewire.admin.product.add-product');
    }
}
