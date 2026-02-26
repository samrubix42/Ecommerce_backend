{{-- ═══════════════════════════════════════════════════════
     STEP 5 — REVIEW & PUBLISH
═══════════════════════════════════════════════════════ --}}

<div class="space-y-6">

    {{-- Status Selector --}}
    <div>
        <h3 class="text-sm font-semibold text-neutral-700 flex items-center gap-2 mb-4">
            <span class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center text-xs">
                <i class="ri-toggle-line"></i>
            </span>
            Publish Status
        </h3>

        <div class="grid grid-cols-3 gap-3">
            @foreach(['draft' => ['label' => 'Draft', 'icon' => 'ri-edit-line', 'desc' => 'Hidden from store', 'color' => 'amber'], 'active' => ['label' => 'Active', 'icon' => 'ri-check-double-line', 'desc' => 'Live on store', 'color' => 'emerald'], 'inactive' => ['label' => 'Inactive', 'icon' => 'ri-pause-circle-line', 'desc' => 'Temporarily hidden', 'color' => 'neutral']] as $value => $opt)
                <label
                    wire:key="status-{{ $value }}"
                    for="status-{{ $value }}"
                    @class([
                        'relative flex flex-col items-center gap-2 p-4 rounded-xl border-2 cursor-pointer transition-all duration-200',
                        'border-blue-400 bg-blue-50/50 shadow-md shadow-blue-100/50' => $status === $value,
                        'border-neutral-200 bg-white hover:border-neutral-300' => $status !== $value,
                    ])>
                    <input type="radio" wire:model.live="status" id="status-{{ $value }}" value="{{ $value }}" class="sr-only">
                    <div @class([
                        'w-10 h-10 rounded-xl flex items-center justify-center transition-colors',
                        "bg-{$opt['color']}-100 text-{$opt['color']}-600" => true,
                    ])>
                        <i class="{{ $opt['icon'] }} text-xl"></i>
                    </div>
                    <span class="text-sm font-semibold text-neutral-700">{{ $opt['label'] }}</span>
                    <span class="text-xs text-neutral-400">{{ $opt['desc'] }}</span>
                    @if($status === $value)
                        <div class="absolute top-2 right-2 w-5 h-5 rounded-full bg-blue-600 text-white flex items-center justify-center scale-in">
                            <i class="ri-check-line text-xs"></i>
                        </div>
                    @endif
                </label>
            @endforeach
        </div>
    </div>

    {{-- Review Summary --}}
    <div>
        <h3 class="text-sm font-semibold text-neutral-700 flex items-center gap-2 mb-4">
            <span class="w-6 h-6 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center text-xs">
                <i class="ri-file-list-3-line"></i>
            </span>
            Product Summary
        </h3>

        <div class="bg-neutral-50/80 rounded-xl border border-neutral-100 divide-y divide-neutral-100">

            {{-- Basic Info --}}
            <div class="p-5">
                <div class="flex items-start justify-between">
                    <div>
                        <h4 class="font-semibold text-neutral-800 text-base">{{ $name ?: '—' }}</h4>
                        <p class="text-xs text-neutral-400 mt-0.5">/products/{{ $slug ?: '...' }}</p>
                    </div>
                    <div class="flex gap-1.5">
                        @if($is_featured)
                            <span class="px-2 py-0.5 rounded-md bg-amber-100 text-amber-700 text-[10px] font-bold uppercase">Featured</span>
                        @endif
                        @if($has_variants)
                            <span class="px-2 py-0.5 rounded-md bg-blue-100 text-blue-700 text-[10px] font-bold uppercase">Variants</span>
                        @else
                            <span class="px-2 py-0.5 rounded-md bg-sky-100 text-sky-700 text-[10px] font-bold uppercase">Simple</span>
                        @endif
                    </div>
                </div>
                @if($short_description)
                    <p class="text-sm text-neutral-500 mt-2">{{ Str::limit($short_description, 120) }}</p>
                @endif
            </div>

            {{-- Pricing / Variants --}}
            <div class="p-5">
                @if(!$has_variants)
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                        <div>
                            <span class="text-xs text-neutral-400 block mb-0.5">Price</span>
                            <span class="text-sm font-semibold text-neutral-800">₹{{ number_format((float)($price ?: 0), 2) }}</span>
                        </div>
                        @if($sale_price)
                            <div>
                                <span class="text-xs text-neutral-400 block mb-0.5">Sale Price</span>
                                <span class="text-sm font-semibold text-emerald-600">₹{{ number_format((float)$sale_price, 2) }}</span>
                            </div>
                        @endif
                        <div>
                            <span class="text-xs text-neutral-400 block mb-0.5">Cost</span>
                            <span class="text-sm font-semibold text-neutral-500">₹{{ number_format((float)($cost_price ?: 0), 2) }}</span>
                        </div>
                        <div>
                            <span class="text-xs text-neutral-400 block mb-0.5">Stock</span>
                            <span class="text-sm font-semibold text-neutral-800">{{ $stock ?: 0 }} units</span>
                        </div>
                        <div>
                            <span class="text-xs text-neutral-400 block mb-0.5">SKU</span>
                            <span class="text-sm font-mono text-neutral-600">{{ $sku ?: 'Auto' }}</span>
                        </div>
                    </div>
                    @if($price && $cost_price && (float)$price > 0)
                        <div class="mt-3 inline-flex items-center gap-1 px-2 py-0.5 rounded bg-blue-50 text-[10px] font-bold text-blue-700 uppercase">
                            Margin: {{ round((( (float)$price - (float)$cost_price) / (float)$price) * 100, 1) }}%
                        </div>
                    @endif
                @else
                    <div class="space-y-4">
                        <span class="text-xs text-neutral-400">{{ count($variants) }} Variant(s)</span>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach($variants as $idx => $v)
                                <div class="flex flex-col gap-2 p-3 rounded-lg bg-white border border-neutral-200 shadow-sm transition-all hover:shadow-md">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-semibold text-neutral-700">{{ $v['name'] }}</span>
                                        <button type="button" 
                                            wire:click="$set('variants.{{ $idx }}.status', {{ !($v['status'] ?? true) ? 'true' : 'false' }})"
                                            @class([
                                                'px-2 py-0.5 rounded text-[10px] font-bold uppercase transition-colors',
                                                'bg-emerald-50 text-emerald-600 hover:bg-emerald-100' => $v['status'] ?? true,
                                                'bg-neutral-50 text-neutral-400 hover:bg-neutral-100' => !($v['status'] ?? true),
                                            ])>
                                            {{ ($v['status'] ?? true) ? 'Active' : 'Inactive' }}
                                        </button>
                                    </div>

                                    {{-- Variant Images --}}
                                    <div class="flex flex-wrap gap-1.5 my-1">
                                        @if(isset($existingVariantImages[$v['id'] ?? null]))
                                            @foreach($existingVariantImages[$v['id']] as $vImg)
                                                <img src="{{ asset('storage/' . $vImg['image_path']) }}" class="w-8 h-8 rounded border border-neutral-100 object-cover shadow-sm">
                                            @endforeach
                                        @endif
                                        @if(isset($variantImages[$idx]) && is_array($variantImages[$idx]))
                                            @foreach($variantImages[$idx] as $uImg)
                                                <img src="{{ $uImg->temporaryUrl() }}" class="w-8 h-8 rounded border border-blue-100 object-cover shadow-sm">
                                            @endforeach
                                        @endif
                                    </div>

                                    <div class="flex items-center gap-3 pt-2 border-t border-neutral-50">
                                        <span class="text-xs font-bold text-blue-600">₹{{ number_format($v['price'] ?: 0, 2) }}</span>
                                        @if($v['cost_price'])
                                            <span class="text-[10px] text-neutral-400">Cost: ₹{{ number_format($v['cost_price'], 2) }}</span>
                                        @endif
                                        <span class="text-[10px] px-1.5 py-0.5 rounded bg-neutral-50 text-neutral-500 ml-auto">{{ $v['stock'] }} units</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- Images --}}
            <div class="p-5">
                <span class="text-xs text-neutral-400 block mb-2">Images</span>
                <div class="flex flex-wrap gap-2">
                    {{-- Existing Images --}}
                    @if(isset($existingImages))
                        @foreach(collect($existingImages)->take(6) as $img)
                            <div class="w-12 h-12 rounded-lg overflow-hidden border border-neutral-200">
                                <img src="{{ asset('storage/' . $img['image_path']) }}" class="w-full h-full object-cover" alt="">
                            </div>
                        @endforeach
                    @endif

                    {{-- New Images --}}
                    @if($productImages && count($productImages) > 0)
                        @foreach(collect($productImages)->take(6) as $image)
                            <div class="w-12 h-12 rounded-lg overflow-hidden border border-blue-200 ring-2 ring-blue-50">
                                <img src="{{ $image->temporaryUrl() }}" class="w-full h-full object-cover" alt="">
                            </div>
                        @endforeach
                    @endif

                    @php
                        $totalCount = ($productImages ? count($productImages) : 0) + (isset($existingImages) ? count($existingImages) : 0);
                    @endphp
                    @if($totalCount > 12)
                        <div class="w-12 h-12 rounded-lg bg-neutral-100 border border-neutral-200 flex items-center justify-center text-xs font-semibold text-neutral-500">
                            +{{ $totalCount - 12 }}
                        </div>
                    @endif

                    @if($totalCount === 0)
                        <span class="text-xs text-neutral-400 italic">No images</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Info Banner --}}
    <div class="flex items-start gap-3 p-4 rounded-xl bg-blue-50/80 border border-blue-100">
        <i class="ri-information-line text-blue-500 text-lg mt-0.5"></i>
        <div>
            <p class="text-sm font-medium text-blue-800">Ready to save?</p>
            <p class="text-xs text-blue-600/70 mt-0.5">Review the details above, then click "{{ isset($existingImages) ? 'Update' : 'Publish' }} Product" to save.</p>
        </div>
    </div>
</div>