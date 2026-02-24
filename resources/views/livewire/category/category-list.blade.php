<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900 tracking-tight">
                Category Management
            </h1>
            <p class="text-sm text-slate-500 mt-1">
                Manage main categories and subcategories.
            </p>
        </div>

        <button
            @click="$dispatch('open-modal'); $wire.resetForm()"
            class="inline-flex items-center justify-center gap-2 rounded-md bg-blue-600
                   px-5 py-2.5 text-sm font-medium text-white shadow-sm
                   hover:bg-blue-500 transition">
            <i class="ri-add-line text-base"></i>
            Add Category
        </button>
    </div>

    <!-- Search -->
    <div class="relative w-full sm:w-80">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
            <i class="ri-search-line"></i>
        </span>
        <input
            type="text"
            wire:model.live="search"
            placeholder="Search categories..."
            class="w-full rounded-md border border-slate-300 pl-9 pr-4 py-2.5 text-sm
                   focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none transition">
    </div>

    <!-- Desktop Table -->
    <div class="hidden sm:block bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

        <table class="min-w-full text-sm">

            <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                <tr>
                    <th class="px-6 py-4 text-left">Category</th>
                    <th class="px-6 py-4 text-left">Slug</th>
                    <th class="px-6 py-4 text-left">Status</th>
                    <th class="px-6 py-4 text-right w-40">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-100">

                @forelse($categories as $category)

                    <tr wire:key="category-{{ $category->id }}"
                        class="hover:bg-slate-50 transition">

                        <!-- Category -->
                        <td class="px-6 py-5">
                            <div class="flex items-start gap-4">

                                <!-- Icon -->
                                <div class="h-9 w-9 flex items-center justify-center rounded-md
                                    {{ $category->parent ? 'bg-indigo-50 text-indigo-600' : 'bg-blue-50 text-blue-600' }}">
                                    <i class="ri-folder-3-line text-base"></i>
                                </div>

                                <div>
                                    <p class="font-medium text-slate-900">
                                        {{ $category->title }}
                                    </p>

                                    @if($category->parent)
                                        <p class="text-xs text-slate-400 mt-1">
                                            Subcategory of
                                            <span class="font-medium text-slate-600">
                                                {{ $category->parent->title }}
                                            </span>
                                        </p>
                                    @endif
                                </div>

                            </div>
                        </td>

                        <!-- Slug -->
                        <td class="px-6 py-5 text-slate-500 font-mono text-xs">
                            {{ $category->slug }}
                        </td>

                        <!-- Status -->
                        <td class="px-6 py-5">
                            @if($category->status)
                                <span class="inline-flex items-center gap-2 text-xs font-medium text-emerald-600">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                    Active
                                </span>
                            @else
                                <span class="inline-flex items-center gap-2 text-xs font-medium text-rose-600">
                                    <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                                    Inactive
                                </span>
                            @endif
                        </td>

                        <!-- Actions -->
                        <td class="px-6 py-5 text-right">
                            <div class="flex justify-end gap-2">

                                <button
                                    @click="$dispatch('open-modal'); $wire.openEditModal({{ $category->id }})"
                                    class="inline-flex items-center gap-1 rounded-md
                                           bg-blue-50 text-blue-600 px-3 py-1.5 text-xs font-medium
                                           hover:bg-blue-100 transition">
                                    <i class="ri-edit-line text-sm"></i>
                                    Edit
                                </button>

                                <button
                                    @click="$dispatch('open-delete-modal'); $wire.confirmDelete({{ $category->id }})"
                                    class="inline-flex items-center gap-1 rounded-md
                                           bg-rose-50 text-rose-600 px-3 py-1.5 text-xs font-medium
                                           hover:bg-rose-100 transition">
                                    <i class="ri-delete-bin-6-line text-sm"></i>
                                    Delete
                                </button>

                            </div>
                        </td>

                    </tr>

                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-slate-400">
                            No categories found.
                        </td>
                    </tr>
                @endforelse

            </tbody>

        </table>

    </div>

    <!-- Mobile Cards -->
    <div class="sm:hidden space-y-4">

        @forelse($categories as $category)

            <div wire:key="mobile-category-{{ $category->id }}"
                 class="bg-white border border-slate-200 rounded-md p-4 shadow-sm space-y-3">

                <div class="flex items-start gap-3">

                    <div class="h-9 w-9 flex items-center justify-center rounded-md
                        {{ $category->parent ? 'bg-indigo-50 text-indigo-600' : 'bg-blue-50 text-blue-600' }}">
                        <i class="ri-folder-3-line text-base"></i>
                    </div>

                    <div class="flex-1">
                        <p class="font-medium text-slate-900">
                            {{ $category->title }}
                        </p>

                        @if($category->parent)
                            <p class="text-xs text-slate-400 mt-1">
                                Subcategory of
                                <span class="font-medium text-slate-600">
                                    {{ $category->parent->title }}
                                </span>
                            </p>
                        @endif

                        <p class="text-xs text-slate-400 font-mono mt-2 break-all">
                            {{ $category->slug }}
                        </p>
                    </div>

                </div>

                <div class="flex items-center justify-between">

                    @if($category->status)
                        <span class="text-xs font-medium text-emerald-600">Active</span>
                    @else
                        <span class="text-xs font-medium text-rose-600">Inactive</span>
                    @endif

                    <div class="flex gap-2">

                        <button
                            @click="$dispatch('open-modal'); $wire.openEditModal({{ $category->id }})"
                            class="bg-blue-50 text-blue-600 px-3 py-1.5 rounded-md text-xs font-medium">
                            Edit
                        </button>

                        <button
                            @click="$dispatch('open-delete-modal'); $wire.confirmDelete({{ $category->id }})"
                            class="bg-rose-50 text-rose-600 px-3 py-1.5 rounded-md text-xs font-medium">
                            Delete
                        </button>

                    </div>

                </div>

            </div>

        @empty

            <div class="rounded-md border border-dashed border-slate-200 bg-slate-50 py-10 text-center text-slate-400">
                No categories found.
            </div>

        @endforelse

    </div>

    <!-- Pagination -->
    <div class="flex justify-end">
        {{ $categories->links() }}
    </div>

    @include('livewire.category.category-modal')
    @include('livewire.category.delete')

</div>