{{-- ═══════════════════════════════════════════════════════
     STEP 2 — PRICING & INVENTORY (Non-Variant Products)
═══════════════════════════════════════════════════════ --}}

<div class="space-y-8">

    {{-- Pricing Section --}}
    <div>
        <h3 class="text-sm font-semibold text-neutral-700 flex items-center gap-2 mb-4">
            <span class="w-6 h-6 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs">
                <i class="ri-money-dollar-circle-line"></i>
            </span>
            Pricing
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            {{-- Price --}}
            <div>
                <label for="product-price" class="block text-sm font-medium text-neutral-700 mb-1.5">
                    Price <span class="text-red-400">*</span>
                </label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-neutral-400 text-sm">₹</span>
                    <input id="product-price" type="number" step="0.01" wire:model="price"
                        placeholder="0.00"
                        class="w-full rounded-xl border border-neutral-200 bg-neutral-50/50 pl-8 pr-4 py-3 text-sm
                               transition-all duration-200
                               focus:bg-white focus:border-indigo-400 focus:ring-4 focus:ring-indigo-50 focus:outline-none">
                </div>
                @error('price')
                    <p class="mt-1 text-xs text-red-500"><i class="ri-error-warning-line"></i> {{ $message }}</p>
                @enderror
            </div>

            {{-- Sale Price --}}
            <div>
                <label for="product-sale-price" class="block text-sm font-medium text-neutral-700 mb-1.5">Sale Price</label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-neutral-400 text-sm">₹</span>
                    <input id="product-sale-price" type="number" step="0.01" wire:model="sale_price"
                        placeholder="0.00"
                        class="w-full rounded-xl border border-neutral-200 bg-neutral-50/50 pl-8 pr-4 py-3 text-sm
                               transition-all duration-200
                               focus:bg-white focus:border-emerald-400 focus:ring-4 focus:ring-emerald-50 focus:outline-none">
                </div>
            </div>

            {{-- Cost Price --}}
            <div>
                <label for="product-cost-price" class="block text-sm font-medium text-neutral-700 mb-1.5">Cost Price</label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-neutral-400 text-sm">₹</span>
                    <input id="product-cost-price" type="number" step="0.01" wire:model="cost_price"
                        placeholder="0.00"
                        class="w-full rounded-xl border border-neutral-200 bg-neutral-50/50 pl-8 pr-4 py-3 text-sm
                               transition-all duration-200
                               focus:bg-white focus:border-neutral-300 focus:ring-4 focus:ring-neutral-50 focus:outline-none">
                </div>
            </div>
        </div>

        {{-- Margin Hint --}}
        @if($price && $cost_price)
            @php $margin = round((($price - $cost_price) / $price) * 100, 1); @endphp
            <div class="mt-3 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium
                {{ $margin > 0 ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700' }}">
                <i class="{{ $margin > 0 ? 'ri-arrow-up-line' : 'ri-arrow-down-line' }}"></i>
                {{ $margin }}% margin
            </div>
        @endif
    </div>

    {{-- Inventory Section --}}
    <div>
        <h3 class="text-sm font-semibold text-neutral-700 flex items-center gap-2 mb-4">
            <span class="w-6 h-6 rounded-lg bg-violet-100 text-violet-600 flex items-center justify-center text-xs">
                <i class="ri-archive-line"></i>
            </span>
            Inventory
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
            {{-- SKU --}}
            <div>
                <label for="product-sku" class="block text-sm font-medium text-neutral-700 mb-1.5">SKU</label>
                <input id="product-sku" type="text" wire:model="sku"
                    placeholder="Auto-generated"
                    class="w-full rounded-xl border border-neutral-200 bg-neutral-50/50 px-4 py-3 text-sm
                           transition-all duration-200 focus:bg-white focus:border-indigo-400 focus:ring-4 focus:ring-indigo-50 focus:outline-none">
            </div>

            {{-- Barcode --}}
            <div>
                <label for="product-barcode" class="block text-sm font-medium text-neutral-700 mb-1.5">Barcode</label>
                <input id="product-barcode" type="text" wire:model="barcode"
                    placeholder="UPC / EAN"
                    class="w-full rounded-xl border border-neutral-200 bg-neutral-50/50 px-4 py-3 text-sm
                           transition-all duration-200 focus:bg-white focus:border-indigo-400 focus:ring-4 focus:ring-indigo-50 focus:outline-none">
            </div>

            {{-- Stock --}}
            <div>
                <label for="product-stock" class="block text-sm font-medium text-neutral-700 mb-1.5">
                    Stock <span class="text-red-400">*</span>
                </label>
                <input id="product-stock" type="number" wire:model="stock"
                    placeholder="0"
                    class="w-full rounded-xl border border-neutral-200 bg-neutral-50/50 px-4 py-3 text-sm
                           transition-all duration-200 focus:bg-white focus:border-indigo-400 focus:ring-4 focus:ring-indigo-50 focus:outline-none">
                @error('stock')
                    <p class="mt-1 text-xs text-red-500"><i class="ri-error-warning-line"></i> {{ $message }}</p>
                @enderror
            </div>

            {{-- Low Stock Alert --}}
            <div>
                <label for="product-low-stock" class="block text-sm font-medium text-neutral-700 mb-1.5">Low Stock Alert</label>
                <input id="product-low-stock" type="number" wire:model="low_stock_alert"
                    placeholder="5"
                    class="w-full rounded-xl border border-neutral-200 bg-neutral-50/50 px-4 py-3 text-sm
                           transition-all duration-200 focus:bg-white focus:border-indigo-400 focus:ring-4 focus:ring-indigo-50 focus:outline-none">
            </div>
        </div>
    </div>

    {{-- Shipping Section --}}
    <div>
        <h3 class="text-sm font-semibold text-neutral-700 flex items-center gap-2 mb-4">
            <span class="w-6 h-6 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center text-xs">
                <i class="ri-truck-line"></i>
            </span>
            Shipping
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label for="product-weight" class="block text-sm font-medium text-neutral-700 mb-1.5">Weight (kg)</label>
                <input id="product-weight" type="number" step="0.01" wire:model="weight"
                    placeholder="0.00"
                    class="w-full rounded-xl border border-neutral-200 bg-neutral-50/50 px-4 py-3 text-sm
                           transition-all duration-200 focus:bg-white focus:border-indigo-400 focus:ring-4 focus:ring-indigo-50 focus:outline-none">
            </div>
        </div>
    </div>
</div>