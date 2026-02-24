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
                class="relative w-full max-w-4xl bg-white rounded-2xl shadow-2xl max-h-[90vh] overflow-hidden flex flex-col"
            >

                <!-- Header -->
                <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-slate-900">
                        {{ $categoryId ? 'Edit Category' : 'Add Category' }}
                    </h3>

                    <button @click="modalOpen=false"
                            class="text-slate-400 hover:text-slate-600 transition">
                        <i class="ri-close-line text-xl"></i>
                    </button>
                </div>

                <!-- Body -->
                <div class="flex-1 overflow-y-auto p-6 space-y-8">

                    <!-- ========== BASIC INFO ========== -->
                    <div class="space-y-6">

                        <h4 class="text-sm font-semibold text-slate-700 uppercase tracking-wide">
                            Basic Information
                        </h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <!-- Image -->
                            <div class="space-y-3">
                                <label class="text-xs font-medium text-slate-600">Category Image</label>

                                <div class="flex items-center gap-4">

                                    <div class="w-24 h-24 rounded-xl border border-slate-200 bg-slate-50 overflow-hidden flex items-center justify-center">

                                        @if($image)
                                            <img src="{{ $image->temporaryUrl() }}"
                                                 class="object-cover w-full h-full">
                                        @elseif($existingImage)
                                            <img src="{{ asset('storage/' . $existingImage) }}"
                                                 class="object-cover w-full h-full">
                                        @else
                                            <i class="ri-image-line text-3xl text-slate-300"></i>
                                        @endif

                                    </div>

                                    <div class="flex-1">
                                        <input type="file"
                                               wire:model="image"
                                               accept="image/*"
                                               class="text-sm border border-slate-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none w-full ">

                                        @error('image')
                                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                        @enderror

                                        <p class="text-xs text-slate-400 mt-2">
                                            JPG or PNG. Max 2MB.
                                        </p>

                                        <div wire:loading
                                             wire:target="image"
                                             class="text-xs text-blue-600 mt-2">
                                            Uploading...
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <!-- Name + Slug -->
                            <div class="space-y-4">

                                <div>
                                    <label class="block text-xs font-medium text-slate-600 mb-1">Name</label>
                                    <input wire:model.live="name"
                                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                                  focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none">
                                    @error('name')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-slate-600 mb-1">Slug</label>
                                    <input wire:model="slug"
                                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                                  focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none">
                                    @error('slug')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                            </div>

                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block text-xs font-medium text-slate-600 mb-1">Description</label>
                            <textarea wire:model="description"
                                      rows="3"
                                      class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                             focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none"></textarea>
                        </div>

                        <!-- Checkboxes -->
                        <div class="flex items-center gap-8 pt-2">
                            <label class="flex items-center gap-2 text-sm text-slate-700">
                                <input type="checkbox"
                                       wire:model.live="isSubcategory"
                                       class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                Is Subcategory
                            </label>

                            <label class="flex items-center gap-2 text-sm text-slate-700">
                                <input type="checkbox"
                                       wire:model="status"
                                       class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                Active
                            </label>
                        </div>

                        @if($isSubcategory)
                            <div>
                                <label class="block text-xs font-medium text-slate-600 mb-1">
                                    Parent Category
                                </label>
                                <select wire:model="parentId"
                                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                               focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none">
                                    <option value="">Select Parent Category</option>
                                    @foreach($parentCategories as $parent)
                                        <option value="{{ $parent->id }}">{{ $parent->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                    </div>

                    <!-- ========== SEO SECTION ========== -->
                    <div class="space-y-6 border-t border-slate-200 pt-6">

                        <h4 class="text-sm font-semibold text-slate-700 uppercase tracking-wide">
                            SEO Settings
                        </h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <div>
                                <label class="block text-xs font-medium text-slate-600 mb-1">Meta Title</label>
                                <input wire:model="meta_title"
                                       class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                              focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none">
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-slate-600 mb-1">Meta Keywords</label>
                                <input wire:model="meta_keywords"
                                       class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                              focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none">
                            </div>

                        </div>

                        <div>
                            <label class="block text-xs font-medium text-slate-600 mb-1">Meta Description</label>
                            <textarea wire:model="meta_description"
                                      rows="2"
                                      class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm
                                             focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none"></textarea>
                        </div>

                    </div>

                </div>

                <!-- Footer -->
                <div class="px-6 py-4 border-t border-slate-200 flex justify-end gap-3 bg-slate-50">

                    <button @click="modalOpen=false"
                            class="px-4 py-2 text-sm rounded-lg border border-slate-300 hover:bg-slate-100 transition">
                        Cancel
                    </button>

                    <button wire:click="save"
                            class="px-4 py-2 text-sm rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition shadow-sm">
                        Save Category
                    </button>

                </div>

            </div>

        </div>

    </template>
</div>