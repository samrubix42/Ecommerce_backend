<div
    x-data="{ modalOpen: false }"
    x-on:open-modal.window="modalOpen = true"
    x-on:close-modal.window="modalOpen = false"
    x-cloak
>
    <template x-teleport="body">

        <!-- Overlay -->
        <div x-show="modalOpen"
             class="fixed inset-0 z-[99] flex items-center justify-center p-4">

            <!-- Background -->
            <div @click="modalOpen=false"
                 class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>

            <!-- Modal -->
            <div
                x-show="modalOpen"
                x-transition
                x-trap.inert.noscroll="modalOpen"
                class="relative w-full max-w-3xl bg-white rounded-xl shadow-2xl max-h-[85vh] flex flex-col overflow-hidden"
            >

                <!-- Header -->
                <div class="px-5 py-3 border-b border-slate-200 flex items-center justify-between bg-white">
                    <h3 class="text-base font-semibold text-slate-800">
                        {{ $categoryId ? 'Edit Category' : 'Add Category' }}
                    </h3>

                    <button @click="modalOpen=false"
                            class="text-slate-400 hover:text-slate-600 transition">
                        <i class="ri-close-line text-lg"></i>
                    </button>
                </div>

                <!-- Body -->
                <div class="flex-1 overflow-y-auto px-5 py-4 space-y-6 text-sm">

                    <!-- Grid Layout -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                        <!-- Image -->
                        <div class="space-y-2">
                            <label class="text-xs font-medium text-slate-600">Image</label>

                            <div class="w-full h-28 rounded-lg border border-slate-200 bg-slate-50 flex items-center justify-center overflow-hidden">
                                @if($image)
                                    <img src="{{ $image->temporaryUrl() }}" class="object-cover w-full h-full">
                                @elseif($existingImage)
                                    <img src="{{ asset('storage/' . $existingImage) }}" class="object-cover w-full h-full">
                                @else
                                    <i class="ri-image-line text-2xl text-slate-300"></i>
                                @endif
                            </div>

                            <input type="file"
                                   wire:model="image"
                                   accept="image/*"
                                   class="w-full text-xs border border-slate-300 rounded-md px-2 py-1.5 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none">

                            @error('image')
                                <p class="text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Fields -->
                        <div class="md:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-4">

                            <div>
                                <label class="text-xs font-medium text-slate-600">Name</label>
                                <input wire:model.live="name"
                                       class="mt-1 w-full rounded-md border border-slate-300 px-3 py-1.5
                                              focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none">
                                @error('name')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="text-xs font-medium text-slate-600">Slug</label>
                                <input wire:model="slug"
                                       class="mt-1 w-full rounded-md border border-slate-300 px-3 py-1.5
                                              focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none">
                                @error('slug')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="sm:col-span-2">
                                <label class="text-xs font-medium text-slate-600">Description</label>
                                <textarea wire:model="description"
                                          rows="2"
                                          class="mt-1 w-full rounded-md border border-slate-300 px-3 py-1.5
                                                 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none"></textarea>
                            </div>

                            <!-- Toggles -->
                            <div class="sm:col-span-2 flex items-center gap-6 pt-1">
                                <label class="flex items-center gap-2 text-xs text-slate-700">
                                    <input type="checkbox"
                                           wire:model.live="isSubcategory"
                                           class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                    Subcategory
                                </label>

                                <label class="flex items-center gap-2 text-xs text-slate-700">
                                    <input type="checkbox"
                                           wire:model="status"
                                           class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                    Active
                                </label>
                            </div>

                            @if($isSubcategory)
                                <div class="sm:col-span-2">
                                    <label class="text-xs font-medium text-slate-600">Parent Category</label>
                                    <select wire:model="parentId"
                                            class="mt-1 w-full rounded-md border border-slate-300 px-3 py-1.5
                                                   focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none">
                                        <option value="">Select Parent</option>
                                        @foreach($parentCategories as $parent)
                                            <option value="{{ $parent->id }}">{{ $parent->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                        </div>
                    </div>

                    <!-- SEO -->
                    <div class="border-t border-slate-200 pt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">

                        <div>
                            <label class="text-xs font-medium text-slate-600">Meta Title</label>
                            <input wire:model="meta_title"
                                   class="mt-1 w-full rounded-md border border-slate-300 px-3 py-1.5
                                          focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none">
                        </div>

                        <div>
                            <label class="text-xs font-medium text-slate-600">Meta Keywords</label>
                            <input wire:model="meta_keywords"
                                   class="mt-1 w-full rounded-md border border-slate-300 px-3 py-1.5
                                          focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none">
                        </div>

                        <div class="sm:col-span-2">
                            <label class="text-xs font-medium text-slate-600">Meta Description</label>
                            <textarea wire:model="meta_description"
                                      rows="2"
                                      class="mt-1 w-full rounded-md border border-slate-300 px-3 py-1.5
                                             focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none"></textarea>
                        </div>

                    </div>

                </div>

                <!-- Footer -->
                <div class="px-5 py-3 border-t border-slate-200 flex justify-end gap-3 bg-slate-50">

                    <!-- Cancel -->
                    <button
                        @click="modalOpen=false"
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium
                               rounded-lg border border-slate-300 text-slate-600
                               hover:bg-slate-100 transition">

                        <i class="ri-close-line text-base"></i>
                        Cancel
                    </button>

                    <!-- Save -->
                    <button
                        wire:click="save"
                        wire:loading.attr="disabled"
                        class="inline-flex items-center gap-2 px-5 py-2 text-sm font-medium
                               rounded-lg bg-gradient-to-r from-blue-600 to-blue-700
                               text-white shadow-md hover:shadow-lg
                               hover:from-blue-700 hover:to-blue-800
                               transition-all duration-200
                               disabled:opacity-60 disabled:cursor-not-allowed">

                        <!-- Spinner -->
                        <svg wire:loading wire:target="save"
                             class="animate-spin h-4 w-4 text-white"
                             xmlns="http://www.w3.org/2000/svg"
                             fill="none"
                             viewBox="0 0 24 24">
                            <circle class="opacity-25"
                                    cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75"
                                  fill="currentColor"
                                  d="M4 12a8 8 0 018-8v8H4z"></path>
                        </svg>

                        <i wire:loading.remove wire:target="save"
                           class="ri-save-line text-base"></i>

                        <span wire:loading.remove wire:target="save">Save Category</span>
                        <span wire:loading wire:target="save">Saving...</span>

                    </button>

                </div>

            </div>

        </div>

    </template>
</div>