<div>
<div x-data="{ step: @entangle('step') }" class="max-w-4xl mx-auto p-6 space-y-6">

    <!-- Step Navigation -->
    <div class="flex justify-between">
        <button @click="step = 1"
            :class="step === 1 ? 'bg-blue-600 text-white' : 'bg-gray-200'"
            class="px-4 py-2 rounded-md text-sm">Basic Info</button>

        <button @click="step = 2"
            :class="step === 2 ? 'bg-blue-600 text-white' : 'bg-gray-200'"
            class="px-4 py-2 rounded-md text-sm">Variants</button>

        <button @click="step = 3"
            :class="step === 3 ? 'bg-blue-600 text-white' : 'bg-gray-200'"
            class="px-4 py-2 rounded-md text-sm">Review</button>
    </div>

    <!-- STEP 1 -->
    <div x-show="step === 1" class="space-y-4">

        <div>
            <label class="block text-sm font-medium">Product Name</label>
            <input type="text" wire:model="name"
                class="w-full border rounded-md px-3 py-2">
        </div>

        <div>
            <label class="block text-sm font-medium">Slug</label>
            <input type="text" wire:model="slug"
                class="w-full border rounded-md px-3 py-2">
        </div>

        <div>
            <label class="block text-sm font-medium">Description</label>
            <textarea wire:model="description"
                class="w-full border rounded-md px-3 py-2"></textarea>
        </div>

        <div>
            <label class="block text-sm font-medium">Product Images</label>
            <input type="file" wire:model="product_images" multiple class="mt-2">
            <div class="mt-2 flex gap-2">
                @if(isset($product_images) && count($product_images))
                    @foreach($product_images as $img)
                        <div class="h-20 w-20 overflow-hidden rounded-md">
                            <img src="{{ $img->temporaryUrl() }}" class="object-cover w-full h-full">
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        <div class="flex items-center gap-3">
            <input type="checkbox" wire:model="has_variants">
            <label>Has Variants?</label>
        </div>

        <button @click="step = 2"
            class="bg-blue-600 text-white px-4 py-2 rounded-md">
            Next
        </button>
    </div>

    <!-- STEP 2 -->
    <div x-show="step === 2" class="space-y-4">

        <!-- SIMPLE PRODUCT -->
        <template x-if="!@entangle('has_variants')">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label>Price</label>
                    <input type="number" wire:model="simple_price"
                        class="w-full border rounded-md px-3 py-2">
                </div>
                <div>
                    <label>Stock</label>
                    <input type="number" wire:model="simple_stock"
                        class="w-full border rounded-md px-3 py-2">
                </div>
            </div>
        </template>

        <!-- VARIANT PRODUCT -->
        <template x-if="@entangle('has_variants')">
            <div class="space-y-4">

                @foreach($variants as $index => $variant)
                <div class="border p-4 rounded-md grid grid-cols-3 gap-4">

                    <input type="text" wire:model="variants.{{ $index }}.sku"
                        placeholder="SKU"
                        class="border rounded-md px-3 py-2">

                    <input type="number" wire:model="variants.{{ $index }}.price"
                        placeholder="Price"
                        class="border rounded-md px-3 py-2">

                    <div class="flex gap-2">
                        <input type="number" wire:model="variants.{{ $index }}.stock"
                            placeholder="Stock"
                            class="border rounded-md px-3 py-2 w-full">

                        <div class="w-full">
                            <label class="block text-xs">Variant Images</label>
                            <input type="file" wire:model="variants.{{ $index }}.images" multiple class="mt-1">
                            <div class="mt-2 flex gap-2">
                                @if(isset($variants[$index]['images']) && is_array($variants[$index]['images']))
                                    @foreach($variants[$index]['images'] as $vimg)
                                        @if($vimg)
                                            <div class="h-16 w-16 overflow-hidden rounded-md">
                                                <img src="{{ $vimg->temporaryUrl() }}" class="object-cover w-full h-full">
                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                        </div>

                        <button type="button"
                            wire:click="removeVariant({{ $index }})"
                            class="bg-red-500 text-white px-3 rounded">
                            ✕
                        </button>
                    </div>

                </div>
                @endforeach

                <button type="button"
                    wire:click="addVariant"
                    class="bg-gray-800 text-white px-4 py-2 rounded-md">
                    Add Variant
                </button>

            </div>
        </template>

        <div class="flex justify-between">
            <button @click="step = 1"
                class="bg-gray-300 px-4 py-2 rounded-md">
                Back
            </button>

            <button @click="step = 3"
                class="bg-blue-600 text-white px-4 py-2 rounded-md">
                Next
            </button>
        </div>

    </div>

    <!-- STEP 3 -->
    <div x-show="step === 3" class="space-y-4">

        <h2 class="text-lg font-semibold">Review & Submit</h2>

        <button wire:click="save"
            class="bg-green-600 text-white px-6 py-3 rounded-md">
            Save Product
        </button>

        <button @click="step = 2"
            class="bg-gray-300 px-4 py-2 rounded-md">
            Back
        </button>

    </div>

</div></div>
