<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

    <!-- Page Heading -->
    <div class="mb-6">
        <h1 class="text-xl font-semibold text-slate-900">
            Category List
        </h1>
        <p class="mt-1 text-sm text-slate-500">
            Manage blog categories, status and visibility.
        </p>
    </div>

    <!-- Main content -->
    <div class="space-y-4">

        <!-- Top bar: search + create button -->
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="w-full sm:max-w-xs">
                <label class="block text-xs font-medium text-slate-500 mb-1">
                    Search Categories
                </label>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 text-sm">
                        <i class="ri-search-line"></i>
                    </span>
                    <input
                        type="text"
                        wire:model.live="search"
                        placeholder="Search by title or slug..."
                        class="block w-full rounded-md border border-slate-300 bg-white pl-9 pr-3 py-2 text-sm
                               text-slate-700 placeholder:text-slate-400
                               focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40 outline-none">
                </div>
            </div>

            <div class="flex sm:justify-end">
                <button
                    @click="$dispatch('open-modal');$wire.resetForm()"
                    class="inline-flex items-center gap-2 rounded-md bg-blue-600
                           px-4 py-2 text-sm font-medium text-white shadow-sm
                           hover:bg-blue-500 focus:outline-none focus:ring-2
                           focus:ring-blue-500/60 focus:ring-offset-1">
                    <i class="ri-add-line text-base"></i>
                    <span>Add Category</span>
                </button>
            </div>
        </div>

        <!-- Table card (desktop & tablet) -->
        <div class="hidden sm:block overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50">
                        <tr class="text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            <th class="px-4 py-3 w-12">#</th>
                            <th class="px-4 py-3">Title</th>
                            <th class="px-4 py-3">Slug</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3 text-right w-40">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse($categories as $category)
                            <tr wire:key="category-{{ $category->id }}" class="hover:bg-slate-50/80">
                                <td class="px-4 py-3 text-slate-500">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="px-4 py-3 font-medium text-slate-800">
                                    {{ $category->title }}
                                </td>
                                <td class="px-4 py-3 text-slate-600">
                                    {{ $category->slug }}
                                </td>
                                <td class="px-4 py-3">
                                    @if($category->status)
                                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 rounded-full bg-rose-100 px-2.5 py-1 text-xs font-semibold text-rose-700">
                                            <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span>
                                            Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-end gap-2">
                                        <button
                                            @click="
                                                $dispatch('open-modal');
                                                $wire.openEditModal({{ $category->id }})
                                            "
                                            class="inline-flex items-center gap-1 rounded-md border border-slate-200 px-2.5 py-1.5
                                                   text-xs font-medium text-slate-700 hover:bg-slate-100">
                                            <i class="ri-edit-line text-sm"></i>
                                            Edit
                                        </button>

                                        <button
                                            @click="
                                                $dispatch('open-delete-modal');
                                                $wire.confirmDelete({{ $category->id }})
                                            "
                                            wire:confirm="Are you sure?"
                                            class="inline-flex items-center gap-1 rounded-md border border-rose-200 px-2.5 py-1.5
                                                   text-xs font-medium text-rose-600 hover:bg-rose-50">
                                            <i class="ri-delete-bin-6-line text-sm"></i>
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-sm text-slate-500">
                                    No categories found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Card list (mobile) -->
        <div class="space-y-3 sm:hidden">
            @forelse($categories as $category)
                <div wire:key="category-card-{{ $category->id }}" class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-sm font-semibold text-slate-900">
                                {{ $category->title }}
                            </p>
                            <p class="mt-0.5 text-[11px] font-mono text-slate-500 break-all">
                                {{ $category->slug }}
                            </p>
                        </div>

                        <div class="text-right text-xs">
                            @if($category->status)
                                <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-2 py-0.5 text-[11px] font-semibold text-emerald-700">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                    Active
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 rounded-full bg-rose-100 px-2 py-0.5 text-[11px] font-semibold text-rose-700">
                                    <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span>
                                    Inactive
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="mt-4 flex items-center justify-end gap-2">
                        <button
                            @click="
                                $dispatch('open-modal');
                                $wire.openEditModal({{ $category->id }})
                            "
                            class="inline-flex items-center gap-1 rounded-md border border-slate-200 px-2.5 py-1.5
                                   text-xs font-medium text-slate-700 hover:bg-slate-100">
                            <i class="ri-edit-line text-sm"></i>
                            Edit
                        </button>

                        <button
                            @click="
                                $dispatch('open-delete-modal');
                                $wire.confirmDelete({{ $category->id }})
                            "
                            wire:confirm="Are you sure?"
                            class="inline-flex items-center gap-1 rounded-md border border-rose-200 px-2.5 py-1.5
                                   text-xs font-medium text-rose-600 hover:bg-rose-50">
                            <i class="ri-delete-bin-6-line text-sm"></i>
                            Delete
                        </button>
                    </div>
                </div>
            @empty
                <div class="rounded-xl border border-dashed border-slate-200 bg-slate-50 px-4 py-6 text-center text-sm text-slate-500">
                    No categories found.
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-3 flex justify-end">
            {{ $categories->links() }}
        </div>

        <!-- Alpine Modal for Add/Edit Category -->
        @include('livewire.category.category-modal')
        <!-- Alpine Modal for Delete Confirmation -->
        @include('livewire.category.delete')

    </div>
</div>