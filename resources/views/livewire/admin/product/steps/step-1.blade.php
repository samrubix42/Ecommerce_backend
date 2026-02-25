{{-- ═══════════════════════════════════════════════════════
     STEP 1 — BASIC INFORMATION
═══════════════════════════════════════════════════════ --}}

<div class="space-y-6">

    {{-- Product Name & Slug --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

        {{-- Name --}}
        <div>
            <label for="product-name" class="block text-sm font-medium text-neutral-700 mb-1.5">
                Product Name <span class="text-red-400">*</span>
            </label>
            <input id="product-name" type="text" wire:model.live.debounce.300ms="name"
                placeholder="e.g. Premium Cotton T-Shirt"
                class="w-full rounded-xl border border-neutral-200 bg-neutral-50/50 px-4 py-3 text-sm text-neutral-800
                       placeholder:text-neutral-400 transition-all duration-200
                       focus:bg-white focus:border-blue-400 focus:ring-4 focus:ring-blue-50 focus:outline-none">
            @error('name')
                <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                    <i class="ri-error-warning-line"></i> {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Slug --}}
        <div>
            <label for="product-slug" class="block text-sm font-medium text-neutral-700 mb-1.5">
                URL Slug <span class="text-red-400">*</span>
            </label>
            <div class="flex rounded-xl border border-neutral-200 bg-neutral-50/50 overflow-hidden transition-all duration-200
                        focus-within:bg-white focus-within:border-blue-400 focus-within:ring-4 focus-within:ring-blue-50">
                <span class="inline-flex items-center px-3 text-xs text-neutral-400 bg-neutral-100 border-r border-neutral-200">
                    /products/
                </span>
                <input id="product-slug" type="text" wire:model="slug"
                    class="flex-1 px-3 py-3 text-sm text-neutral-800 bg-transparent border-0 focus:outline-none focus:ring-0">
            </div>
            @error('slug')
                <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                    <i class="ri-error-warning-line"></i> {{ $message }}
                </p>
            @enderror
        </div>
    </div>

    {{-- Category --}}
    <div>
        <label for="product-category" class="block text-sm font-medium text-neutral-700 mb-1.5">Category</label>
        <select id="product-category" wire:model="category_id"
            class="w-full rounded-xl border border-neutral-200 bg-neutral-50/50 px-4 py-3 text-sm text-neutral-800
                   transition-all duration-200 focus:bg-white focus:border-blue-400 focus:ring-4 focus:ring-blue-50 focus:outline-none
                   appearance-none bg-no-repeat bg-right pr-10"
            style="background-image: url('data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 20 20%22 fill=%22%239ca3af%22><path fill-rule=%22evenodd%22 d=%22M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z%22/></svg>'); background-position: right 0.75rem center; background-size: 1.25em;"
            >
            <option value="">Select a category</option>
            @foreach($this->categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->title }}</option>
            @endforeach
        </select>
    </div>

    {{-- Short Description --}}
    <div>
        <label for="product-short-desc" class="block text-sm font-medium text-neutral-700 mb-1.5">Short Description</label>
        <input id="product-short-desc" type="text" wire:model="short_description"
            placeholder="A brief one-liner about the product"
            class="w-full rounded-xl border border-neutral-200 bg-neutral-50/50 px-4 py-3 text-sm text-neutral-800
                   placeholder:text-neutral-400 transition-all duration-200
                   focus:bg-white focus:border-blue-400 focus:ring-4 focus:ring-blue-50 focus:outline-none">
    </div>

    {{-- Full Description --}}
    <div>
        <label for="product-desc" class="block text-sm font-medium text-neutral-700 mb-1.5">Full Description</label>
        <textarea id="product-desc" wire:model="description" rows="5"
            placeholder="Detailed product description…"
            class="w-full rounded-xl border border-neutral-200 bg-neutral-50/50 px-4 py-3 text-sm text-neutral-800
                   placeholder:text-neutral-400 transition-all duration-200 resize-none
                   focus:bg-white focus:border-blue-400 focus:ring-4 focus:ring-blue-50 focus:outline-none"></textarea>
    </div>

    {{-- Toggles Row --}}
    <div class="flex flex-wrap gap-6 pt-2">

        {{-- Has Variants Toggle (Pines UI Style) --}}
        <div x-data class="flex items-center gap-3">
            <button type="button" wire:click="$toggle('has_variants')" role="switch"
                :class="$wire.has_variants ? 'bg-blue-600' : 'bg-neutral-300'"
                class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full transition-colors duration-200 ease-in-out focus:outline-none focus:ring-4 focus:ring-blue-100">
                <span :class="$wire.has_variants ? 'translate-x-5' : 'translate-x-0.5'"
                    class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow-lg ring-0 transition-transform duration-200 ease-in-out mt-0.5"></span>
            </button>
            <div>
                <span class="text-sm font-medium text-neutral-700">Has Variants</span>
                <p class="text-xs text-neutral-400">Enable sizes, colors, etc.</p>
            </div>
        </div>

        {{-- Featured Toggle (Pines UI Style) --}}
        <div x-data class="flex items-center gap-3">
            <button type="button" wire:click="$toggle('is_featured')" role="switch"
                :class="$wire.is_featured ? 'bg-amber-500' : 'bg-neutral-300'"
                class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full transition-colors duration-200 ease-in-out focus:outline-none focus:ring-4 focus:ring-amber-100">
                <span :class="$wire.is_featured ? 'translate-x-5' : 'translate-x-0.5'"
                    class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow-lg ring-0 transition-transform duration-200 ease-in-out mt-0.5"></span>
            </button>
            <div>
                <span class="text-sm font-medium text-neutral-700">Featured</span>
                <p class="text-xs text-neutral-400">Show on homepage</p>
            </div>
        </div>

    </div>
</div>