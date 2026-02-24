<div
    x-data="{ modalOpen: false }"
    x-on:open-modal.window="modalOpen = true"
    x-on:close-modal.window="modalOpen = false"
    x-cloak>
    <template x-teleport="body">
        <div x-show="modalOpen" class="fixed inset-0 z-[99] flex items-center justify-center px-4">

            <div @click="modalOpen=false" class="absolute inset-0 bg-black/40"></div>

            <div
                x-show="modalOpen"
                x-transition
                x-trap.inert.noscroll="modalOpen"
                class="relative w-full max-w-xl rounded-xl bg-white p-6 shadow-xl">

                <h3 class="text-lg font-semibold text-slate-900 mb-4">
                    {{ $categoryId ? 'Edit Category' : 'Add Category' }}
                </h3>

                <div class="space-y-3">

                    {{-- Name --}}
                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">Name</label>
                        <input
                            wire:model.live="name"
                            placeholder="Category name"
                            class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm
                                   focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40 outline-none">
                        @error('name')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Slug --}}
                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">Slug</label>
                        <input
                            wire:model="slug"
                            class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm
                                   focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40 outline-none">
                        @error('slug')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">Description</label>
                        <textarea
                            wire:model="description"
                            rows="3"
                            class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm
                                   focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40 outline-none"></textarea>
                    </div>

                    {{-- Checkboxes --}}
                    <div class="flex items-center gap-6">

                        {{-- Subcategory --}}
                        <label class="inline-flex items-center gap-2 text-sm text-slate-700">
                            <input
                                type="checkbox"
                                wire:model.live="isSubcategory"
                                class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                            <span>Is Subcategory?</span>
                        </label>

                        {{-- Status --}}
                        <label class="inline-flex items-center gap-2 text-sm text-slate-700">
                            <input
                                type="checkbox"
                                wire:model="status"
                                class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                            <span>Active</span>
                        </label>

                    </div>

                    {{-- Parent Dropdown --}}
                    @if($isSubcategory)
                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">
                            Parent Category
                        </label>

                        <select
                            wire:model="parentId"
                            class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm
                   focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40 outline-none">

                            <option value="">-- Select Parent Category --</option>

                            @foreach($parentCategories as $parent)
                            <option value="{{ $parent->id }}">
                                {{ $parent->title }}
                            </option>
                            @endforeach

                        </select>

                        @error('parentId')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                    @endif

                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button
                        @click="modalOpen=false"
                        class="rounded-md border px-4 py-2 text-sm">
                        Cancel
                    </button>

                    <button
                        wire:click="save"
                        class="rounded-md bg-blue-600 px-4 py-2 text-sm text-white">
                        Save
                    </button>
                </div>

            </div>
        </div>
    </template>
</div>