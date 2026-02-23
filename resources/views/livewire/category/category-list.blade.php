@section('title', 'Product Categories')

<div>

    <!-- SEARCH & ACTIONS -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex align-items-center gap-3">

                <!-- SEARCH -->
                <div class="flex-fill">
                    <div class="input-icon">
                        <span class="input-icon-addon">
                            <i class="ti ti-search"></i>
                        </span>
                        <input
                            type="text"
                            wire:model.live.debounce.300ms="search"
                            class="form-control"
                            placeholder="Search categories..."
                        >
                    </div>
                </div>

                <!-- ADD BUTTON -->
                <div class="col-auto">
                    <button wire:click="openModal" class="btn btn-primary">
                        <i class="ti ti-plus me-1"></i>
                        <span class="d-none d-sm-inline">Add Category</span>
                    </button>
                </div>

            </div>
        </div>
    </div>

    <!-- TABLE CARD -->
    <div class="card">

        <div class="table-responsive">
            <table class="table table-vcenter card-table">
                <thead>
                    <tr>
                        <th wire:click="sort('name')" class="cursor-pointer">
                            Name
                            @if($sortField === 'name')
                                <span class="text-muted small">({{ $sortDirection }})</span>
                            @endif
                        </th>
                        <th>Slug</th>
                        <th>Description</th>
                        <th wire:click="sort('created_at')" class="cursor-pointer">
                            Created
                            @if($sortField === 'created_at')
                                <span class="text-muted small">({{ $sortDirection }})</span>
                            @endif
                        </th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="avatar bg-primary text-white">
                                        {{ strtoupper(substr($category->name, 0, 1)) }}
                                    </span>
                                    <div>
                                        <div class="fw-semibold">{{ $category->name }}</div>
                                        <div class="text-muted small">ID: {{ $category->id }}</div>
                                        @if($category->parent)
                                            <div class="text-muted small">
                                                Parent: <span class="badge bg-info">{{ $category->parent->name }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <td>
                                <span class="badge bg-blue-lt">{{ $category->slug }}</span>
                            </td>

                            <td class="text-muted" style="max-width:360px">
                                {{ Str::limit($category->description ?? '—', 80) }}
                            </td>

                            <td>
                                <span class="text-muted small">
                                    {{ $category->created_at->format('M d, Y') }}
                                </span>
                            </td>

                            <td class="text-end">
                                <button
                                    wire:click="openModal({{ $category->id }})"
                                    class="btn btn-sm btn-outline-primary"
                                >
                                    Edit
                                </button>

                                <button
                                    wire:click="delete({{ $category->id }})"
                                    class="btn btn-sm btn-outline-danger"
                                >
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                No categories found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- FOOTER -->
        <div class="card-footer d-flex justify-content-between align-items-center">
            <div class="text-muted">
                Showing {{ $categories->firstItem() ?? 0 }}
                –
                {{ $categories->lastItem() ?? 0 }}
                of {{ $categories->total() }}
            </div>

            <div>
                {{ $categories->links() }}
            </div>
        </div>
    </div>

    <!-- MODAL -->
    @if($showModal)
        <div
            class="modal modal-blur fade show"
            style="display:block"
            tabindex="-1"
            wire:ignore.self
        >
            <div class="modal-dialog modal-dialog-centered modal-md">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">
                            {{ $isEdit ? 'Edit Category' : 'Add Category' }}
                        </h5>
                        <button type="button" class="btn-close" wire:click="closeModal"></button>
                    </div>

                    <form wire:submit.prevent="save">
                        <div class="modal-body">

                            <div class="mb-3">
                                <label class="form-label">Parent Category</label>
                                <select wire:model.defer="parentId" class="form-select">
                                    <option value="">-- No Parent (Root Category) --</option>
                                    @foreach($parentCategories as $parent)
                                        @if(!$isEdit || $parent->id !== $categoryId)
                                            <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('parentId') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" wire:model.defer="name" class="form-control">
                                @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Slug</label>
                                <input type="text" wire:model.defer="slug" class="form-control">
                                @error('slug') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div>
                                <label class="form-label">Description</label>
                                <textarea wire:model.defer="description" class="form-control" rows="3"></textarea>
                                @error('description') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" wire:click="closeModal">
                                Cancel
                            </button>
                            <button type="submit" class="btn btn-primary">
                                {{ $isEdit ? 'Update' : 'Create' }}
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>

        <!-- MODAL BACKDROP -->
        <div class="modal-backdrop fade show"></div>
    @endif



</div>
