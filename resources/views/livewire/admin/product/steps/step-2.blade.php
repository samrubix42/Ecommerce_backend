{{-- ═══════════════════════════════════════════════════════
     STEP 2 — PRICING & INVENTORY (Non-Variant Products)
═══════════════════════════════════════════════════════ --}}

<div class="space-y-8">

    {{-- Pricing Section --}}
    <div>
        <h3 class="text-sm font-semibold text-neutral-700 flex items-center gap-2 mb-4">
            <span class="w-6 h-6 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center text-xs">
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
                               focus:bg-white focus:border-blue-400 focus:ring-4 focus:ring-blue-50 focus:outline-none">
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
            <span class="w-6 h-6 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center text-xs">
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
                           transition-all duration-200 focus:bg-white focus:border-blue-400 focus:ring-4 focus:ring-blue-50 focus:outline-none">
            </div>

            {{-- Barcode --}}
            <div>
                <label for="product-barcode" class="block text-sm font-medium text-neutral-700 mb-1.5">Barcode</label>
                <input id="product-barcode" type="text" wire:model="barcode"
                    placeholder="UPC / EAN"
                    class="w-full rounded-xl border border-neutral-200 bg-neutral-50/50 px-4 py-3 text-sm
                           transition-all duration-200 focus:bg-white focus:border-blue-400 focus:ring-4 focus:ring-blue-50 focus:outline-none">
            </div>

            {{-- Stock --}}
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label for="product-stock" class="block text-sm font-medium text-neutral-700">
                        Stock <span class="text-red-400">*</span>
                    </label>
                    @if($stock !== '')
                    @php
                    $status = 'In Stock';
                    $statusColor = 'bg-emerald-50 text-emerald-600 border-emerald-100';
                    if(!$track_inventory) {
                    $status = 'Tracking Off';
                    $statusColor = 'bg-neutral-50 text-neutral-500 border-neutral-100';
                    } elseif ((int)$stock <= 0) {
                        $status='Out of Stock' ;
                        $statusColor='bg-red-50 text-red-600 border-red-100' ;
                        } elseif ((int)$stock <=(int)$low_stock_alert) {
                        $status='Low Stock' ;
                        $statusColor='bg-amber-50 text-amber-600 border-amber-100' ;
                        }
                        @endphp
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider border {{ $statusColor }}">
                        {{ $status }}
                        </span>
                        @endif
                </div>
                <input id="product-stock" type="number" wire:model="stock"
                    placeholder="0"
                    class="w-full rounded-xl border border-neutral-200 bg-neutral-50/50 px-4 py-3 text-sm
                           transition-all duration-200 focus:bg-white focus:border-blue-400 focus:ring-4 focus:ring-blue-50 focus:outline-none">
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
                           transition-all duration-200 focus:bg-white focus:border-blue-400 focus:ring-4 focus:ring-blue-50 focus:outline-none">
            </div>

            {{-- Track Inventory Toggle --}}
            <div class="flex items-center gap-3 bg-neutral-50/80 rounded-xl px-4 py-3 border border-neutral-100">
                <div class="flex-1">
                    <h4 class="text-sm font-semibold text-neutral-700">Track Inventory</h4>
                    <p class="text-[10px] text-neutral-400 mt-0.5 whitespace-nowrap">Enable stock management</p>
                </div>
                <button type="button"
                    wire:click="$toggle('track_inventory')"
                    @class([ 'relative inline-flex h-6 w-11 items-center rounded-full transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2' , 'bg-blue-600'=> $track_inventory,
                    'bg-neutral-200' => !$track_inventory,
                    ])>
                    <span @class([ 'inline-block h-4 w-4 transform rounded-full bg-white transition duration-200 ease-in-out' , 'translate-x-6'=> $track_inventory,
                        'translate-x-1' => !$track_inventory,
                        ])></span>
                </button>
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
                           transition-all duration-200 focus:bg-white focus:border-blue-400 focus:ring-4 focus:ring-blue-50 focus:outline-none">
            </div>
        </div>
    </div>
</div>