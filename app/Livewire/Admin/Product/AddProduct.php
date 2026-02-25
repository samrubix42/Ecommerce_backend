<?php

namespace App\Livewire\Admin\Product;

use App\Models\Attribute;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductImage;
use App\Models\VariantAttribute;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

class AddProduct extends Component
{
    use WithFileUploads;

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
    public $weight = '';

    // ── Step 3: Variants ──
    public array $selectedAttributes = [];
    public array $variants = [];
    public $variantImages = []; // Stores temporary uploads for variants

    // ── Step 4: Media ──
    public $productImages = [];

    // ── Step 5: Status ──
    public string $status = 'draft';

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
            5 => ['title' => 'Publish', 'icon' => 'ri-rocket-line'],
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
                'slug' => 'required|string|max:255|unique:products,slug',
            ],
            2 => [
                'price' => 'required|numeric|min:0',
                'stock' => 'required|integer|min:0',
                'sale_price' => 'nullable|numeric|min:0',
                'cost_price' => 'nullable|numeric|min:0',
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

        // Skip pricing step for variant products
        if ($this->step === 1 && $this->has_variants) {
            $this->step = 3;
            return;
        }

        // Skip variants step for non-variant products
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

        $attrs = $this->productAttributes;

        foreach ($combinations as $i => $combo) {
            $parts = [];
            foreach ($combo as $aId => $vId) {
                $attr = $attrs->firstWhere('id', (int) $aId);
                $val = $attr?->values->firstWhere('id', (int) $vId);
                if ($val) $parts[] = ($attr ? $attr->name . ': ' : '') . $val->value;
            }

            $this->variants[] = [
                'name' => implode(' / ', $parts),
                'sku' => strtoupper(Str::slug($this->name ?: 'PROD')) . '-V' . ($i + 1),
                'price' => '',
                'sale_price' => '',
                'cost_price' => '',
                'stock' => '0',
                'status' => true,
                'attributes' => $combo,
            ];
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

    public function removeImage(int $index)
    {
        if (isset($this->productImages[$index])) {
            array_splice($this->productImages, $index, 1);
        }
    }

    public function removeVariantImage(int $variantIndex, int $imageIndex)
    {
        if (isset($this->variantImages[$variantIndex][$imageIndex])) {
            unset($this->variantImages[$variantIndex][$imageIndex]);
            $this->variantImages[$variantIndex] = array_values($this->variantImages[$variantIndex]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Save Product
    |--------------------------------------------------------------------------
    */

    public function save()
    {
        $product = Product::create([
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
            $product->variants()->create([
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
        } else {
            foreach ($this->variants as $i => $v) {
                $variant = $product->variants()->create([
                    'sku' => $v['sku'] ?: 'SKU-' . strtoupper(Str::random(8)),
                    'price' => $v['price'],
                    'cost_price' => $v['cost_price'] ?: null,
                    'stock' => $v['stock'] ?: 0,
                    'is_default' => $i === 0,
                    'status' => $v['status'] ?? true,
                ]);

                // Save Variant Images if uploaded
                if (isset($this->variantImages[$i]) && is_array($this->variantImages[$i])) {
                    foreach ($this->variantImages[$i] as $img) {
                        $path = $img->store('products/variants', 'public');
                        ProductImage::create([
                            'product_id' => $product->id,
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

        if ($this->productImages) {
            foreach ($this->productImages as $i => $image) {
                $path = $image->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'is_primary' => $i === 0,
                    'sort_order' => $i,
                ]);
            }
        }

        session()->flash('success', 'Product created successfully!');
        return $this->redirect(route('admin.products.index'), navigate: true);
    }

    #[Layout('layouts.admin')]
    public function render()
    {
        return view('livewire.admin.product.add-product');
    }
}
