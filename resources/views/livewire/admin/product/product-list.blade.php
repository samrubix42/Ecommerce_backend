<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900 tracking-tight">
                Product Management
            </h1>
            <p class="text-sm text-slate-500 mt-1">
                Manage your product catalog inventory.
            </p>
        </div>

        <a href="{{ route('admin.add-product') }}" wire:navigate
            class="inline-flex items-center justify-center gap-2 rounded-md bg-blue-600
                   px-5 py-2.5 text-sm font-medium text-white shadow-sm
                   hover:bg-blue-500 transition">
            <i class="ri-add-line text-base"></i>
            Add Product
        </a>
    </div>

    {{-- Filters Bar --}}
    <div class="flex flex-col sm:flex-row gap-3">

        {{-- Search --}}
        <div class="relative w-full sm:w-80">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                <i class="ri-search-line"></i>
            </span>
            <input
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="Search products..."
                class="w-full rounded-md border border-slate-300 pl-9 pr-4 py-2.5 text-sm
                       focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none transition">
        </div>

        {{-- Status Filter --}}
        <select wire:model.live="statusFilter"
            class="rounded-md border border-slate-300 px-3 py-2.5 text-sm text-slate-700
                   focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none transition">
            <option value="">All Status</option>
            <option value="active">Active</option>
            <option value="draft">Draft</option>
            <option value="inactive">Inactive</option>
        </select>

        {{-- Category Filter --}}
        <select wire:model.live="categoryFilter"
            class="rounded-md border border-slate-300 px-3 py-2.5 text-sm text-slate-700
                   focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none transition">
            <option value="">All Categories</option>
            @foreach($this->categories as $cat)
            <option value="{{ $cat->id }}">{{ $cat->title }}</option>
            @endforeach
        </select>
    </div>

    {{-- ═══════════════════════════════════════
         DESKTOP TABLE
    ═══════════════════════════════════════ --}}
    <div class="hidden sm:block bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

        <table class="min-w-full text-sm">

            <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                <tr>
                    <th class="px-6 py-4 text-left">Product</th>
                    <th class="px-6 py-4 text-left">Category</th>
                    <th class="px-6 py-4 text-left">Price</th>
                    <th class="px-6 py-4 text-left">Stock</th>
                    <th class="px-6 py-4 text-left">Type</th>
                    <th class="px-6 py-4 text-left">Status</th>
                    <th class="px-6 py-4 text-right w-40">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-100">

                @forelse($products as $product)

                <tr wire:key="product-{{ $product->id }}"
                    class="hover:bg-slate-50 transition">

                    {{-- Product (Image + Name + SKU) --}}
                    <td class="px-6 py-5">
                        <div class="flex items-center gap-3">
                            @if($product->primaryImage)
                            <img src="{{ asset('storage/' . $product->primaryImage->image_path) }}"
                                class="h-10 w-10 rounded-lg object-cover border border-slate-200"
                                alt="{{ $product->name }}">
                            @else
                            <div class="h-10 w-10 rounded-lg bg-slate-100 flex items-center justify-center text-slate-300">
                                <i class="ri-image-line text-lg"></i>
                            </div>
                            @endif

                            <div>
                                <p class="font-medium text-slate-900 line-clamp-1">{{ $product->name }}</p>
                                @if($product->defaultVariant)
                                <p class="text-xs text-slate-400 font-mono mt-0.5">{{ $product->defaultVariant->sku }}</p>
                                @endif
                            </div>
                        </div>
                    </td>

                    {{-- Category --}}
                    <td class="px-6 py-5">
                        @if($product->category)
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-slate-100 text-xs font-medium text-slate-600">
                            <i class="ri-folder-3-line text-xs"></i>
                            {{ $product->category->title }}
                        </span>
                        @else
                        <span class="text-xs text-slate-300">—</span>
                        @endif
                    </td>

                    {{-- Price --}}
                    <td class="px-6 py-5">
                        @if($product->defaultVariant)
                        <p class="font-medium text-slate-900">₹{{ number_format($product->defaultVariant->price, 2) }}</p>
                        @if($product->defaultVariant->sale_price)
                        <p class="text-xs text-emerald-600 mt-0.5">
                            <i class="ri-arrow-down-line text-[10px]"></i>
                            ₹{{ number_format($product->defaultVariant->sale_price, 2) }}
                        </p>
                        @endif
                        @elseif($product->has_variants && $product->variants->count() > 0)
                        @php
                        $minPrice = $product->variants->min('price');
                        $maxPrice = $product->variants->max('price');
                        @endphp
                        <p class="font-medium text-slate-900">
                            ₹{{ number_format($minPrice, 2) }}
                            @if($minPrice != $maxPrice)
                            – ₹{{ number_format($maxPrice, 2) }}
                            @endif
                        </p>
                        @else
                        <span class="text-xs text-slate-300">—</span>
                        @endif
                    </td>

                    {{-- Stock --}}
                    <td class="px-6 py-5">
                        @php
                        $totalStock = $product->variants->sum(fn($v) => $v->inventory->quantity ?? 0);
                        $lowAlert = $product->defaultVariant->inventory->low_stock_threshold ?? 5;
                        @endphp
                        <div class="flex items-center gap-1.5">
                            @if($totalStock <= 0)
                                <span class="w-2 h-2 rounded-full bg-red-500"></span>
                                <span class="text-xs font-medium text-red-600">Out of stock</span>
                                @elseif($totalStock <= $lowAlert)
                                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                    <span class="text-xs font-medium text-amber-600">{{ $totalStock }} left</span>
                                    @else
                                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                    <span class="text-xs font-medium text-slate-700">{{ $totalStock }}</span>
                                    @endif
                        </div>
                    </td>

                    {{-- Type --}}
                    <td class="px-6 py-5">
                        @if($product->has_variants)
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-violet-50 text-violet-700 text-xs font-medium">
                            <i class="ri-stack-line text-[10px]"></i>
                            {{ $product->variants->count() }} variants
                        </span>
                        @else
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-sky-50 text-sky-700 text-xs font-medium">
                            <i class="ri-checkbox-blank-circle-line text-[10px]"></i>
                            Simple
                        </span>
                        @endif
                    </td>

                    {{-- Status --}}
                    <td class="px-6 py-5">
                        @if($product->status === 'active')
                        <span class="inline-flex items-center gap-1 text-emerald-600 text-xs font-medium">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            Active
                        </span>
                        @elseif($product->status === 'draft')
                        <span class="inline-flex items-center gap-1 text-amber-600 text-xs font-medium">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                            Draft
                        </span>
                        @else
                        <span class="inline-flex items-center gap-1 text-slate-400 text-xs font-medium">
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                            Inactive
                        </span>
                        @endif
                    </td>

                    {{-- Actions --}}
                    <td class="px-6 py-5 text-right">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.update-product', $product->id) }}" wire:navigate
                                class="bg-indigo-50 text-indigo-600 px-3 py-1.5 rounded-md text-xs hover:bg-indigo-100 transition"
                                title="Edit Product">
                                <i class="ri-edit-line"></i>
                            </a>

                            <button wire:click="toggleStatus({{ $product->id }})"
                                class="bg-slate-50 text-slate-600 px-3 py-1.5 rounded-md text-xs hover:bg-slate-100 transition"
                                title="Toggle Status">
                                <i class="ri-toggle-line"></i>
                            </button>

                            <button
                                @click="$dispatch('open-delete-modal'); $wire.confirmDelete({{ $product->id }})"
                                class="bg-rose-50 text-rose-600 px-3 py-1.5 rounded-md text-xs hover:bg-rose-100 transition">
                                Delete
                            </button>
                        </div>
                    </td>

                </tr>

                @empty

                <tr>
                    <td colspan="7" class="px-6 py-16 text-center">
                        <div class="flex flex-col items-center">
                            <div class="w-14 h-14 rounded-2xl bg-slate-100 flex items-center justify-center mb-4">
                                <i class="ri-shopping-bag-line text-2xl text-slate-300"></i>
                            </div>
                            <p class="text-sm font-medium text-slate-500">No products found</p>
                            <p class="text-xs text-slate-400 mt-1">Try adjusting your search or filters</p>
                        </div>
                    </td>
                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    {{-- ═══════════════════════════════════════
         MOBILE CARDS
    ═══════════════════════════════════════ --}}
    <div class="sm:hidden space-y-4">

        @forelse($products as $product)

        <div wire:key="mobile-product-{{ $product->id }}"
            class="bg-white border border-slate-200 rounded-md p-4 shadow-sm space-y-3">

            <div class="flex items-start gap-3">
                {{-- Image --}}
                @if($product->primaryImage)
                <img src="{{ asset('storage/' . $product->primaryImage->image_path) }}"
                    class="h-12 w-12 rounded-lg object-cover border border-slate-200"
                    alt="{{ $product->name }}">
                @else
                <div class="h-12 w-12 rounded-lg bg-slate-100 flex items-center justify-center text-slate-300">
                    <i class="ri-image-line text-lg"></i>
                </div>
                @endif

                <div class="flex-1 min-w-0">
                    <p class="font-medium text-slate-900 truncate">{{ $product->name }}</p>

                    @if($product->category)
                    <p class="text-xs text-slate-400 mt-0.5">{{ $product->category->title }}</p>
                    @endif

                    {{-- Price --}}
                    <div class="mt-1">
                        @if($product->defaultVariant)
                        <span class="text-sm font-semibold text-slate-900">₹{{ number_format($product->defaultVariant->price, 2) }}</span>
                        @elseif($product->has_variants && $product->variants->count() > 0)
                        @php
                        $min = $product->variants->min('price');
                        $max = $product->variants->max('price');
                        @endphp
                        <span class="text-sm font-semibold text-slate-900">
                            ₹{{ number_format($min, 2) }}@if($min != $max) – ₹{{ number_format($max, 2) }}@endif
                        </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    {{-- Status --}}
                    @if($product->status === 'active')
                    <span class="text-xs font-medium text-emerald-600">Active</span>
                    @elseif($product->status === 'draft')
                    <span class="text-xs font-medium text-amber-600">Draft</span>
                    @else
                    <span class="text-xs font-medium text-slate-400">Inactive</span>
                    @endif

                    {{-- Type --}}
                    @if($product->has_variants)
                    <span class="text-xs text-violet-600">{{ $product->variants->count() }} variants</span>
                    @else
                    <span class="text-xs text-sky-600">Simple</span>
                    @endif

                    {{-- Stock --}}
                    @php $totalStock = $product->variants->sum(fn($v) => $v->inventory->quantity ?? 0); @endphp
                    @if($totalStock <= 0)
                        <span class="text-xs text-red-600">Out of stock</span>
                        @else
                        <span class="text-xs text-slate-500">{{ $totalStock }} in stock</span>
                        @endif
                </div>

                <div class="flex gap-2">
                    <a href="{{ route('admin.update-product', $product->id) }}" wire:navigate
                        class="bg-indigo-50 text-indigo-600 px-3 py-1.5 rounded-md text-xs font-medium hover:bg-indigo-100">
                        Edit
                    </a>

                    <button wire:click="toggleStatus({{ $product->id }})"
                        class="bg-slate-50 text-slate-600 px-3 py-1.5 rounded-md text-xs font-medium hover:bg-slate-100">
                        <i class="ri-toggle-line"></i>
                    </button>

                    <button
                        @click="$dispatch('open-delete-modal'); $wire.confirmDelete({{ $product->id }})"
                        class="bg-rose-50 text-rose-600 px-3 py-1.5 rounded-md text-xs font-medium">
                        Delete
                    </button>
                </div>
            </div>

        </div>

        @empty

        <div class="rounded-md border border-dashed border-slate-200 bg-slate-50 py-10 text-center text-slate-400">
            No products found.
        </div>

        @endforelse

    </div>

    {{-- ═══════════════════════════════════════
         PAGINATION
    ═══════════════════════════════════════ --}}
    @if($this->products->hasPages())
    <div class="pt-2">
        {{ $this->products->links() }}
    </div>
    @endif

    {{-- ═══════════════════════════════════════
         DELETE CONFIRMATION MODAL
    ═══════════════════════════════════════ --}}
    <div x-data="{ deleteOpen: false }"
        x-on:open-delete-modal.window="deleteOpen = true"
        x-on:close-delete-modal.window="deleteOpen = false"
        x-cloak>
        <template x-teleport="body">
            <div x-show="deleteOpen" class="fixed inset-0 z-[99] flex items-center justify-center px-4">
                <div @click="deleteOpen=false" class="absolute inset-0 bg-black/40"></div>
                <div x-show="deleteOpen" x-transition x-trap.inert.noscroll="deleteOpen"
                    class="relative w-full max-w-sm rounded-xl bg-white p-6 shadow-xl">

                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-full bg-rose-100 flex items-center justify-center">
                            <i class="ri-delete-bin-line text-rose-600"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-900">Delete Product</h3>
                    </div>

                    <p class="mb-6 text-sm text-slate-600">
                        Are you sure you want to delete this product? All variants, images, and related data will be permanently removed.
                    </p>

                    <div class="flex justify-end gap-3">
                        <button @click="deleteOpen=false"
                            class="rounded-md border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 transition">
                            Cancel
                        </button>
                        <button wire:click="delete"
                            class="inline-flex items-center gap-1 rounded-md bg-rose-600 px-4 py-2 text-sm font-medium text-white hover:bg-rose-500 transition">
                            <i class="ri-delete-bin-line text-xs"></i>
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </div>

</div>