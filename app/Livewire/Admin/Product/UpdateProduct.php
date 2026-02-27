<?php

namespace App\Livewire\Admin\Product;

use App\Models\Attribute;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\VariantAttribute;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class UpdateProduct extends Component
{
    use WithFileUploads;

    public Product $product;
    public int $step = 1;
    public int $totalSteps = 5;

    // ── Step 1: Basic Information ──
    public string $name = '';
    public string $slug = '';
    public ?string $category_id = null;
    public string $short_description = '';
    public string $description = '';
    public bool $has_variants = false;
    public bool $is_featured = false;

    // ── Step 2: Pricing & Inventory (Non-Variant) ──
    public string $sku = '';
    public string $barcode = '';
    public $price = '';
    public $sale_price = '';
    public $cost_price = '';
    public $stock = '';
    public $low_stock_alert = '5';
    public $track_inventory = true;
    public $weight = '';

    // ── Step 3: Variants ──
    public array $selectedAttributes = [];
    public array $variants = [];
    public $variantImages = []; // Stores temporary uploads for variants
    public $existingVariantImages = []; // Stores paths of currently saved images

    // ── Step 4: Media ──
    public $productImages = []; // New uploads
    public $existingImages = []; // Current images from DB

    // ── Step 5: Status ──
    public string $status = 'draft';

    public function mount(Product $product)
    {
        $this->product = $product->load(['variants.variantAttributes', 'variants.inventory', 'images', 'category']);

        // Load Basic Info
        $this->name = $this->product->name;
        $this->slug = $this->product->slug;
        $this->category_id = $this->product->category_id;
        $this->short_description = $this->product->short_description ?? '';
        $this->description = $this->product->description ?? '';
        $this->has_variants = (bool) $this->product->has_variants;
        $this->is_featured = (bool) $this->product->is_featured;
        $this->status = $this->product->status;

        // Load Simple Product Info (from default variant)
        if (!$this->has_variants) {
            $defaultVariant = $this->product->variants->where('is_default', true)->first();
            if ($defaultVariant) {
                $this->sku = $defaultVariant->sku;
                $this->barcode = $defaultVariant->barcode ?? '';
                $this->price = $defaultVariant->price;
                $this->sale_price = $defaultVariant->sale_price;
                $this->cost_price = $defaultVariant->cost_price;
                $this->stock = $defaultVariant->inventory ? $defaultVariant->inventory->quantity : $defaultVariant->stock;
                $this->low_stock_alert = $defaultVariant->inventory ? $defaultVariant->inventory->low_stock_threshold : ($defaultVariant->low_stock_alert ?? 5);
                $this->track_inventory = $defaultVariant->inventory ? $defaultVariant->inventory->track_inventory : true;
                $this->weight = $defaultVariant->weight;
            }
        } else {
            // Load Variants and selected attributes
            foreach ($this->product->variants as $variant) {
                $combo = [];
                foreach ($variant->variantAttributes as $va) {
                    $combo[$va->attribute_id] = $va->attribute_value_id;

                    // Populate selectedAttributes for UI
                    if (!isset($this->selectedAttributes[(string)$va->attribute_id])) {
                        $this->selectedAttributes[(string)$va->attribute_id] = [];
                    }
                    if (!in_array($va->attribute_value_id, $this->selectedAttributes[(string)$va->attribute_id])) {
                        $this->selectedAttributes[(string)$va->attribute_id][] = $va->attribute_value_id;
                    }
                }

                $this->variants[] = [
                    'id' => $variant->id,
                    'name' => $this->getVariantNameFromCombo($combo),
                    'sku' => $variant->sku,
                    'price' => $variant->price,
                    'sale_price' => $variant->sale_price,
                    'cost_price' => $variant->cost_price,
                    'stock' => $variant->inventory ? $variant->inventory->quantity : $variant->stock,
                    'status' => $variant->status,
                    'attributes' => $combo,
                    'exists' => true,
                ];

                // Load variant images
                foreach ($variant->images as $vImg) {
                    if (!isset($this->existingVariantImages[$variant->id])) {
                        $this->existingVariantImages[$variant->id] = [];
                    }
                    $this->existingVariantImages[$variant->id][] = [
                        'id' => $vImg->id,
                        'image_path' => $vImg->image_path,
                    ];
                }
            }
        }

        // Load Existing Images
        $this->existingImages = $this->product->images->toArray();
    }

    private function getVariantNameFromCombo(array $combo)
    {
        $attrs = $this->productAttributes;
        $parts = [];
        foreach ($combo as $aId => $vId) {
            $attr = $attrs->firstWhere('id', (int) $aId);
            $val = $attr?->values->firstWhere('id', (int) $vId);
            if ($val) $parts[] = ($attr ? $attr->name . ': ' : '') . $val->value;
        }
        return implode(' / ', $parts);
    }

    /*
    |--------------------------------------------------------------------------
    | Computed Properties
    |--------------------------------------------------------------------------
    */

    #[Computed]
    public function categories()
    {
        return ProductCategory::where('status', true)->orderBy('title')->get();
    }

    #[Computed]
    public function productAttributes()
    {
        return Attribute::with('values')->where('status', true)->get();
    }

    #[Computed]
    public function progress()
    {
        return round(($this->step / $this->totalSteps) * 100);
    }

    #[Computed]
    public function stepInfo()
    {
        return [
            1 => ['title' => 'Basic Info', 'icon' => 'ri-information-line'],
            2 => ['title' => 'Pricing', 'icon' => 'ri-price-tag-3-line'],
            3 => ['title' => 'Variants', 'icon' => 'ri-stack-line'],
            4 => ['title' => 'Media', 'icon' => 'ri-image-line'],
            5 => ['title' => 'Update', 'icon' => 'ri-save-line'],
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Lifecycle Hooks
    |--------------------------------------------------------------------------
    */

    public function updatedName()
    {
        $this->slug = Str::slug($this->name);
    }

    public function updatedHasVariants()
    {
        // Warn: toggling variants on an existing product might be destructive or complex.
        // For now, let's just clear if switching away from variants.
        if (!$this->has_variants) {
            $this->selectedAttributes = [];
            $this->variants = [];
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Step Navigation
    |--------------------------------------------------------------------------
    */

    protected function stepRules(): array
    {
        return match ($this->step) {
            1 => [
                'name' => 'required|string|max:255',
                'slug' => 'required|string|max:255|unique:products,slug,' . $this->product->id,
            ],
            2 => [
                'price' => 'required|numeric|min:0',
                'stock' => 'required|integer|min:0',
            ],
            3 => [
                'variants' => 'required|array|min:1',
                'variants.*.price' => 'required|numeric|min:0',
                'variants.*.cost_price' => 'nullable|numeric|min:0',
                'variants.*.stock' => 'required|integer|min:0',
            ],
            default => [],
        };
    }

    public function next()
    {
        $rules = $this->stepRules();
        if (!empty($rules)) {
            $this->validate($rules);
        }

        if ($this->step === 1 && $this->has_variants) {
            $this->step = 3;
            return;
        }

        if ($this->step === 2) {
            $this->step = 4;
            return;
        }

        if ($this->step < $this->totalSteps) {
            $this->step++;
        }
    }

    public function back()
    {
        if ($this->step === 4 && !$this->has_variants) {
            $this->step = 2;
            return;
        }

        if ($this->step === 4 && $this->has_variants) {
            $this->step = 3;
            return;
        }

        if ($this->step === 3) {
            $this->step = 1;
            return;
        }

        if ($this->step > 1) {
            $this->step--;
        }
    }

    public function goToStep(int $target)
    {
        if ($target >= $this->step || $target < 1) return;
        if ($target === 2 && $this->has_variants) return;
        if ($target === 3 && !$this->has_variants) return;
        $this->step = $target;
    }

    public function isStepSkipped(int $stepNum): bool
    {
        return ($stepNum === 2 && $this->has_variants) || ($stepNum === 3 && !$this->has_variants);
    }

    /*
    |--------------------------------------------------------------------------
    | Variant Management
    |--------------------------------------------------------------------------
    */

    public function toggleAttributeValue(int $attributeId, int $valueId)
    {
        $key = (string) $attributeId;

        if (!isset($this->selectedAttributes[$key])) {
            $this->selectedAttributes[$key] = [];
        }

        $index = array_search($valueId, $this->selectedAttributes[$key]);

        if ($index !== false) {
            array_splice($this->selectedAttributes[$key], $index, 1);
        } else {
            $this->selectedAttributes[$key][] = $valueId;
        }

        $this->selectedAttributes = array_filter($this->selectedAttributes, fn($v) => !empty($v));
        $this->generateVariants();
    }

    public function isValueSelected(int $attributeId, int $valueId): bool
    {
        return in_array($valueId, $this->selectedAttributes[(string) $attributeId] ?? []);
    }

    public function generateVariants()
    {
        $existingVariants = $this->variants;
        $this->variants = [];

        $active = array_filter($this->selectedAttributes, fn($v) => !empty($v));
        if (empty($active)) return;

        $combinations = [[]];
        foreach ($active as $attrId => $valueIds) {
            $tmp = [];
            foreach ($combinations as $combo) {
                foreach ($valueIds as $valId) {
                    $tmp[] = $combo + [$attrId => $valId];
                }
            }
            $combinations = $tmp;
        }

        foreach ($combinations as $i => $combo) {
            // Check if this combination already exists in our local list
            $found = null;
            foreach ($existingVariants as $ev) {
                if ($ev['attributes'] == $combo) {
                    $found = $ev;
                    break;
                }
            }

            if ($found) {
                $this->variants[] = $found;
            } else {
                $this->variants[] = [
                    'name' => $this->getVariantNameFromCombo($combo),
                    'sku' => strtoupper(Str::slug($this->name ?: 'PROD')) . '-V' . ($i + 1) . '-' . rand(100, 999),
                    'price' => '',
                    'sale_price' => '',
                    'cost_price' => '',
                    'stock' => '0',
                    'status' => true,
                    'attributes' => $combo,
                    'exists' => false,
                ];
            }
        }
    }

    public function removeVariant(int $index)
    {
        array_splice($this->variants, $index, 1);
    }

    /*
    |--------------------------------------------------------------------------
    | Image Management
    |--------------------------------------------------------------------------
    */

    public function removeExistingImage(int $imageId)
    {
        $image = ProductImage::find($imageId);
        if ($image) {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();

            // Re-index parent product images
            $this->existingImages = array_values(array_filter($this->existingImages, fn($img) => (int)$img['id'] !== $imageId));

            // Re-index variant-specific images
            $newVariantImages = [];
            foreach ($this->existingVariantImages as $vId => $imgs) {
                $newVariantImages[$vId] = array_values(array_filter($imgs, fn($img) => (int)$img['id'] !== $imageId));
            }
            $this->existingVariantImages = $newVariantImages;
        }
    }

    public function setPrimaryImage(int $imageId)
    {
        // 1. Reset all images for this product to NOT primary
        ProductImage::where('product_id', $this->product->id)->update(['is_primary' => false]);

        // 2. Set the selected one as primary
        $image = ProductImage::find($imageId);
        if ($image) {
            $image->update(['is_primary' => true]);

            // 3. Sync local state
            foreach ($this->existingImages as &$img) {
                $img['is_primary'] = ($img['id'] === $imageId);
            }
        }
    }

    public function removeVariantImage(int $variantIndex, int $imageIndex)
    {
        if (isset($this->variantImages[$variantIndex][$imageIndex])) {
            $currentImages = $this->variantImages[$variantIndex];
            array_splice($currentImages, $imageIndex, 1);
            $this->variantImages[$variantIndex] = $currentImages;
        }
    }

    public function removeImage(int $index)
    {
        if (isset($this->productImages[$index])) {
            array_splice($this->productImages, $index, 1);
            $this->productImages = array_values($this->productImages);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Save Update
    |--------------------------------------------------------------------------
    */

    public function save()
    {
        $this->product->update([
            'name' => $this->name,
            'slug' => $this->slug,
            'category_id' => $this->category_id ?: null,
            'short_description' => $this->short_description,
            'description' => $this->description,
            'has_variants' => $this->has_variants,
            'is_featured' => $this->is_featured,
            'status' => $this->status,
        ]);

        if (!$this->has_variants) {
            // Delete old variants if they existed (except default if simple)
            $this->product->variants()->where('is_default', false)->delete();

            $defaultVariant = $this->product->variants()->where('is_default', true)->first();
            if ($defaultVariant) {
                $oldStock = $defaultVariant->inventory ? $defaultVariant->inventory->quantity : $defaultVariant->stock;
                $defaultVariant->update([
                    'sku' => $this->sku,
                    'barcode' => $this->barcode ?: null,
                    'price' => $this->price,
                    'sale_price' => $this->sale_price ?: null,
                    'cost_price' => $this->cost_price ?: null,
                    'stock' => $this->stock,
                    'low_stock_alert' => $this->low_stock_alert ?: 5,
                    'weight' => $this->weight ?: null,
                ]);

                // Update/Create Inventory
                $inventory = $defaultVariant->inventory()->updateOrCreate(
                    ['product_variant_id' => $defaultVariant->id],
                    [
                        'quantity' => $this->stock,
                        'low_stock_threshold' => $this->low_stock_alert ?: 5,
                        'track_inventory' => $this->track_inventory
                    ]
                );

                // Log if quantity changed
                if ($oldStock != $this->stock) {
                    \App\Models\InventoryLog::create([
                        'inventory_id' => $inventory->id,
                        'type' => 'adjustment',
                        'quantity' => abs($this->stock - $oldStock),
                        'before_quantity' => $oldStock,
                        'after_quantity' => $this->stock,
                        'note' => 'Manual adjustment via product edit',
                    ]);
                }
            } else {
                $variant = $this->product->variants()->create([
                    'sku' => $this->sku ?: 'SKU-' . strtoupper(Str::random(8)),
                    'barcode' => $this->barcode ?: null,
                    'price' => $this->price,
                    'sale_price' => $this->sale_price ?: null,
                    'cost_price' => $this->cost_price ?: null,
                    'stock' => $this->stock,
                    'low_stock_alert' => $this->low_stock_alert ?: 5,
                    'weight' => $this->weight ?: null,
                    'is_default' => true,
                    'status' => true,
                ]);

                // Create Inventory
                $inventory = $variant->inventory()->create([
                    'quantity' => $this->stock,
                    'low_stock_threshold' => $this->low_stock_alert ?: 5,
                    'track_inventory' => $this->track_inventory
                ]);

                // Create Initial Log if stock > 0
                if ($this->stock > 0) {
                    \App\Models\InventoryLog::create([
                        'inventory_id' => $inventory->id,
                        'type' => 'stock_in',
                        'quantity' => $this->stock,
                        'before_quantity' => 0,
                        'after_quantity' => $this->stock,
                        'note' => 'Initial stock on product variant creation',
                    ]);
                }
            }
        } else {
            // For Variant Products
            // 1. Determine which variants were removed
            $currentVariantIds = collect($this->variants)->pluck('id')->filter()->toArray();
            $this->product->variants()->where('is_default', false)->whereNotIn('id', $currentVariantIds)->delete();
            $this->product->variants()->where('is_default', true)->whereNotIn('id', $currentVariantIds)->update(['is_default' => false]);

            // 2. Update/Create variants
            foreach ($this->variants as $i => $v) {
                if (isset($v['id'])) {
                    $variant = ProductVariant::find($v['id']);
                    $oldStock = $variant->inventory ? $variant->inventory->quantity : $variant->stock;
                    $variant->update([
                        'sku' => $v['sku'],
                        'price' => $v['price'],
                        'sale_price' => $v['sale_price'] ?: null,
                        'cost_price' => $v['cost_price'] ?: null,
                        'stock' => $v['stock'],
                        'is_default' => $i === 0,
                        'status' => $v['status'] ?? true,
                    ]);

                    // Update/Create Inventory
                    $inventory = $variant->inventory()->updateOrCreate(
                        ['product_variant_id' => $variant->id],
                        [
                            'quantity' => $v['stock'] ?: 0,
                            'low_stock_threshold' => 5, // Default
                            'track_inventory' => $this->track_inventory
                        ]
                    );

                    // Log if quantity changed
                    if ($oldStock != $v['stock']) {
                        \App\Models\InventoryLog::create([
                            'inventory_id' => $inventory->id,
                            'type' => 'adjustment',
                            'quantity' => abs($v['stock'] - $oldStock),
                            'before_quantity' => $oldStock,
                            'after_quantity' => $v['stock'],
                            'note' => 'Manual adjustment via product edit',
                        ]);
                    }

                    // Handle variant images update
                    if (isset($this->variantImages[$i]) && is_array($this->variantImages[$i])) {
                        foreach ($this->variantImages[$i] as $img) {
                            $path = $img->store('products/variants', 'public');
                            ProductImage::create([
                                'product_id' => $this->product->id,
                                'product_variant_id' => $variant->id,
                                'image_path' => $path,
                                'is_primary' => false,
                                'sort_order' => 0,
                            ]);
                        }
                    }
                } else {
                    $variant = $this->product->variants()->create([
                        'sku' => $v['sku'] ?: 'SKU-' . strtoupper(Str::random(8)),
                        'price' => $v['price'],
                        'sale_price' => $v['sale_price'] ?: null,
                        'cost_price' => $v['cost_price'] ?: null,
                        'stock' => $v['stock'] ?: 0,
                        'is_default' => $i === 0 && $this->product->variants()->where('is_default', true)->count() == 0,
                        'status' => $v['status'] ?? true,
                    ]);

                    // Create Inventory
                    $inventory = $variant->inventory()->create([
                        'quantity' => $v['stock'] ?: 0,
                        'low_stock_threshold' => 5, // Default
                        'track_inventory' => $this->track_inventory
                    ]);

                    // Create Initial Log if stock > 0
                    if (($v['stock'] ?? 0) > 0) {
                        \App\Models\InventoryLog::create([
                            'inventory_id' => $inventory->id,
                            'type' => 'stock_in',
                            'quantity' => $v['stock'],
                            'before_quantity' => 0,
                            'after_quantity' => $v['stock'],
                            'note' => 'Initial stock on variant creation',
                        ]);
                    }

                    // Save Variant Images if uploaded
                    if (isset($this->variantImages[$i]) && is_array($this->variantImages[$i])) {
                        foreach ($this->variantImages[$i] as $img) {
                            $path = $img->store('products/variants', 'public');
                            ProductImage::create([
                                'product_id' => $this->product->id,
                                'product_variant_id' => $variant->id,
                                'image_path' => $path,
                                'is_primary' => false,
                                'sort_order' => 0,
                            ]);
                        }
                    }

                    foreach ($v['attributes'] as $attrId => $valId) {
                        VariantAttribute::create([
                            'product_variant_id' => $variant->id,
                            'attribute_id' => $attrId,
                            'attribute_value_id' => $valId,
                        ]);
                    }
                }
            }
        }

        // Add New Images
        if ($this->productImages) {
            $lastSortOrder = $this->product->images()->max('sort_order') ?? -1;
            foreach ($this->productImages as $i => $image) {
                $path = $image->store('products', 'public');
                ProductImage::create([
                    'product_id' => $this->product->id,
                    'image_path' => $path,
                    'is_primary' => ($lastSortOrder == -1 && $i == 0) ? true : false,
                    'sort_order' => $lastSortOrder + $i + 1,
                ]);
            }
        }

        session()->flash('success', 'Product updated successfully!');
        return $this->redirect(route('admin.products.index'), navigate: true);
    }

    #[Layout('layouts.admin')]
    public function render()
    {
        return view('livewire.admin.product.update-product');
    }
}
