{{-- ═══════════════════════════════════════════════════════
     STEP 3 — VARIANT CONFIGURATION
═══════════════════════════════════════════════════════ --}}

<div class="space-y-8">

    {{-- Attribute Selector --}}
    <div>
        <h3 class="text-sm font-semibold text-neutral-700 flex items-center gap-2 mb-4">
            <span class="w-6 h-6 rounded-lg bg-violet-100 text-violet-600 flex items-center justify-center text-xs">
                <i class="ri-palette-line"></i>
            </span>
            Select Attributes
        </h3>
        <p class="text-xs text-neutral-400 mb-5">Click on attribute values to create variant combinations</p>

        <div class="space-y-5">
            @foreach($this->productAttributes as $attribute)
                <div class="bg-neutral-50/80 rounded-xl p-5 border border-neutral-100">
                    <h4 class="text-sm font-semibold text-neutral-700 mb-3">{{ $attribute->name }}</h4>
                    <div class="flex flex-wrap gap-2">
                        @foreach($attribute->values as $value)
                            @php $selected = $this->isValueSelected($attribute->id, $value->id); @endphp
                            <button type="button"
                                wire:click="toggleAttributeValue({{ $attribute->id }}, {{ $value->id }})"
                                @class([
                                    'px-4 py-2 rounded-full text-sm font-medium transition-all duration-200 border',
                                    'bg-indigo-600 text-white border-indigo-500 shadow-md shadow-indigo-200/50 scale-105' => $selected,
                                    'bg-white text-neutral-600 border-neutral-200 hover:border-indigo-300 hover:text-indigo-600 hover:bg-indigo-50' => !$selected,
                                ])>
                                @if($selected)
                                    <i class="ri-check-line mr-1 text-xs"></i>
                                @endif
                                {{ $value->value }}
                            </button>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Generated Variants --}}
    @if(count($variants) > 0)
        <div>
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-neutral-700 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center text-xs">
                        <i class="ri-stack-line"></i>
                    </span>
                    Generated Variants
                    <span class="ml-2 px-2 py-0.5 rounded-full bg-indigo-100 text-indigo-700 text-xs font-semibold">
                        {{ count($variants) }}
                    </span>
                </h3>
            </div>

            @error('variants')
                <p class="mb-3 text-xs text-red-500"><i class="ri-error-warning-line"></i> {{ $message }}</p>
            @enderror

            <div class="space-y-3">
                @foreach($variants as $index => $variant)
                    <div class="bg-white border border-neutral-200 rounded-xl p-5 hover:shadow-md transition-shadow duration-200"
                         wire:key="variant-{{ $index }}">

                        {{-- Variant Header --}}
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-2">
                                <span class="w-7 h-7 rounded-lg bg-gradient-to-br from-indigo-500 to-violet-600 text-white flex items-center justify-center text-xs font-bold">
                                    {{ $index + 1 }}
                                </span>
                                <span class="text-sm font-semibold text-neutral-800">{{ $variant['name'] }}</span>
                            </div>
                            <button type="button" wire:click="removeVariant({{ $index }})"
                                class="w-7 h-7 rounded-lg bg-red-50 text-red-400 hover:bg-red-100 hover:text-red-600 flex items-center justify-center transition-colors">
                                <i class="ri-delete-bin-line text-sm"></i>
                            </button>
                        </div>

                        {{-- Variant Fields --}}
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-neutral-500 mb-1">SKU</label>
                                <input type="text" wire:model="variants.{{ $index }}.sku"
                                    class="w-full rounded-lg border border-neutral-200 bg-neutral-50/50 px-3 py-2 text-sm
                                           focus:bg-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-50 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-neutral-500 mb-1">
                                    Price <span class="text-red-400">*</span>
                                </label>
                                <input type="number" step="0.01" wire:model="variants.{{ $index }}.price"
                                    placeholder="0.00"
                                    class="w-full rounded-lg border border-neutral-200 bg-neutral-50/50 px-3 py-2 text-sm
                                           focus:bg-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-50 focus:outline-none">
                                @error("variants.{$index}.price")
                                    <p class="mt-0.5 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-neutral-500 mb-1">Sale Price</label>
                                <input type="number" step="0.01" wire:model="variants.{{ $index }}.sale_price"
                                    placeholder="0.00"
                                    class="w-full rounded-lg border border-neutral-200 bg-neutral-50/50 px-3 py-2 text-sm
                                           focus:bg-white focus:border-emerald-400 focus:ring-2 focus:ring-emerald-50 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-neutral-500 mb-1">
                                    Stock <span class="text-red-400">*</span>
                                </label>
                                <input type="number" wire:model="variants.{{ $index }}.stock"
                                    placeholder="0"
                                    class="w-full rounded-lg border border-neutral-200 bg-neutral-50/50 px-3 py-2 text-sm
                                           focus:bg-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-50 focus:outline-none">
                                @error("variants.{$index}.stock")
                                    <p class="mt-0.5 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        {{-- Empty State --}}
        <div class="text-center py-12 bg-neutral-50/80 rounded-xl border-2 border-dashed border-neutral-200">
            <div class="w-14 h-14 rounded-2xl bg-neutral-100 flex items-center justify-center mx-auto mb-4">
                <i class="ri-apps-line text-2xl text-neutral-400"></i>
            </div>
            <h4 class="text-sm font-semibold text-neutral-600">No Variants Yet</h4>
            <p class="text-xs text-neutral-400 mt-1">Select attribute values above to auto-generate variant combinations</p>
        </div>
    @endif
</div>