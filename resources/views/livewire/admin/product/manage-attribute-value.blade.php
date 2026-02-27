<div x-data="{
    attributeModal: false,
    valueModal: false,
    deleteModal: false,
    deleteId: null
}"
    x-init="
    window.addEventListener('close-attribute-modal', () => attributeModal = false);
    window.addEventListener('open-attribute-modal', () => attributeModal = true);
    window.addEventListener('open-attribute-delete-modal', () => deleteModal = true);
    window.addEventListener('close-attribute-delete-modal', () => deleteModal = false);
"
    class="p-6 lg:p-10 space-y-8  bg-gray-50/50 min-h-screen">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-gray-200 pb-8">
        <div>
            <h1 class="text-xl font-semibold text-gray-900 tracking-tight">Product Attributes</h1>
            <p class="text-sm text-gray-500 mt-1">Define and manage product specifications and variants.</p>
        </div>

        <button @click="attributeModal=true; $wire.resetForm()"
            class="inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 active:ring-4 active:ring-blue-100 transition-all shadow-sm">
            <i class="ri-add-line"></i>
            Add Attribute
        </button>
    </div>

    <!-- Toolbar -->
    <div class="flex flex-col md:flex-row items-center gap-4">
        <div class="relative w-full md:max-w-sm">
            <i class="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
            <input wire:model.live.debounce.400ms="search"
                class="w-full pl-9 pr-4 py-2 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all"
                placeholder="Search by name...">
        </div>
    </div>

    <!-- Content Section -->
    <div class="space-y-4">
        <!-- Desktop Table (Visible on md and up) -->
        <div class="hidden md:block bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Name</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Slug</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Values</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($attributeList as $attribute)
                    <tr wire:key="desktop-attribute-{{ $attribute->id }}" class="hover:bg-gray-50/50 transition-colors group">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="font-medium text-gray-900">{{ $attribute->name }}</span>
                        </td>
                        <td class="px-6 py-4 tracking-tight whitespace-nowrap">
                            <code class="text-[11px] px-2 py-0.5 bg-gray-100 text-gray-600 rounded">{{ $attribute->slug }}</code>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-500">
                                {{ $attribute->values_count }} variations
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($attribute->status)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">
                                Active
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200">
                                Disabled
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            <div class="inline-flex items-center gap-1">
                                <button @click="valueModal=true; $wire.openValueModal({{ $attribute->id }})"
                                    class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-600 hover:text-white transition-all shadow-sm" title="Manage Values">
                                    <i class="ri-list-settings-line"></i>
                                </button>
                                <button @click="attributeModal=true; $wire.openEdit({{ $attribute->id }})"
                                    class="p-2 bg-slate-50 text-slate-600 rounded-lg hover:bg-slate-800 hover:text-white transition-all shadow-sm" title="Edit">
                                    <i class="ri-edit-line"></i>
                                </button>
                                <button @click="deleteModal=true; deleteId={{ $attribute->id }}"
                                    class="p-2 bg-rose-50 text-rose-600 rounded-lg hover:bg-rose-600 hover:text-white transition-all shadow-sm" title="Delete">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            No attributes found items match your current filter.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Grid (Visible below md) -->
        <div class="grid grid-cols-1 gap-4 md:hidden">
            @forelse($attributeList as $attribute)
            <div wire:key="mobile-attribute-{{ $attribute->id }}" class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm space-y-4">
                <div class="flex items-start justify-between">
                    <div>
                        <h3 class="font-semibold text-gray-900">{{ $attribute->name }}</h3>
                        <div class="flex items-center gap-2 mt-1">
                            <code class="text-[10px] px-1.5 py-0.5 bg-gray-100 text-gray-500 rounded">{{ $attribute->slug }}</code>
                            <span class="text-[10px] text-gray-400 font-medium">•</span>
                            <span class="text-[10px] text-gray-500 font-medium">{{ $attribute->values_count }} Variations</span>
                        </div>
                    </div>
                    @if($attribute->status)
                    <span class="h-2 w-2 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.4)]"></span>
                    @else
                    <span class="h-2 w-2 rounded-full bg-gray-300"></span>
                    @endif
                </div>

                <div class="pt-4 border-t border-gray-50 flex items-center justify-between">
                    <button @click="valueModal=true; $wire.openValueModal({{ $attribute->id }})"
                        class="flex-1 flex items-center justify-center gap-2 py-2 text-xs font-bold text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                        <i class="ri-list-settings-line"></i>
                        Values
                    </button>
                    <div class="h-4 w-px bg-gray-100"></div>
                    <button @click="attributeModal=true; $wire.openEdit({{ $attribute->id }})"
                        class="flex-1 flex items-center justify-center gap-2 py-2 text-xs font-bold text-gray-600 hover:bg-gray-50 rounded-lg transition-colors">
                        <i class="ri-edit-line"></i>
                        Edit
                    </button>
                    <div class="h-4 w-px bg-gray-100"></div>
                    <button @click="deleteModal=true; deleteId={{ $attribute->id }}"
                        class="flex-1 flex items-center justify-center gap-2 py-2 text-xs font-bold text-rose-600 hover:bg-rose-50 rounded-lg transition-colors">
                        <i class="ri-delete-bin-line"></i>
                        Delete
                    </button>
                </div>
            </div>
            @empty
            <div class="py-10 text-center text-gray-500 bg-white border border-gray-200 rounded-xl">
                No attributes found.
            </div>
            @endforelse
        </div>

        @if($attributeList->hasPages())
        <div class="px-6 py-4 bg-gray-50/50 border border-gray-200 rounded-xl">
            {{ $attributeList->links() }}
        </div>
        @endif
    </div>

    <!-- Attribute Modal -->
    <template x-teleport="body">
        <div x-show="attributeModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
            <div x-show="attributeModal" x-transition.opacity @click="attributeModal=false" class="absolute inset-0 bg-gray-900/40 backdrop-blur-sm"></div>

            <div x-show="attributeModal" x-transition.scale.95
                class="relative w-full max-w-md bg-white rounded-xl shadow-xl overflow-hidden border border-gray-200">

                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-base font-semibold text-gray-900">{{ $attributeId ? 'Edit Attribute' : 'New Attribute' }}</h3>
                    <button @click="attributeModal=false" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="ri-close-line"></i>
                    </button>
                </div>

                <div class="p-6 space-y-5">
                    <div class="space-y-1.5">
                        <label class="text-xs font-medium text-gray-700">Display Name</label>
                        <input wire:model.defer="name"
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all"
                            placeholder="e.g. Size, Color">
                        @error('name') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-100">
                        <div class="text-xs font-medium text-gray-700">Display this attribute in store</div>
                        <label class="relative inline-flex items-center cursor-pointer scale-90">
                            <input type="checkbox" wire:model="status" class="sr-only peer">
                            <div class="w-10 h-5.5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4.5 after:w-4.5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end gap-2">
                    <button @click="attributeModal=false" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900">Cancel</button>
                    <button wire:click="save" wire:loading.attr="disabled"
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50">
                        <span wire:loading.remove wire:target="save">{{ $attributeId ? 'Save Changes' : 'Create' }}</span>
                        <span wire:loading wire:target="save">Processing...</span>
                    </button>
                </div>
            </div>
        </div>
    </template>

    <!-- Values Modal -->
    <template x-teleport="body">
        <div x-show="valueModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
            <div x-show="valueModal" x-transition.opacity @click="valueModal=false" class="absolute inset-0 bg-gray-900/40 backdrop-blur-sm"></div>

            <div x-show="valueModal" x-transition.scale.95
                class="relative w-full max-w-lg bg-white rounded-xl shadow-xl overflow-hidden flex flex-col max-h-[85vh]">

                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between shrink-0">
                    <div>
                        <h3 class="text-base font-semibold text-gray-900">Manage Values</h3>
                        <p class="text-[11px] text-gray-500 mt-0.5">Configuring options for <span class="text-blue-600 font-bold tracking-tight">{{ $valueAttributeName }}</span></p>
                    </div>
                    <button @click="valueModal=false" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="ri-close-line"></i>
                    </button>
                </div>

                <div class="p-6 overflow-y-auto flex-1">
                    <div class="divide-y divide-gray-100">
                        @forelse($currentValues as $value)
                        <div class="py-3 flex items-center justify-between gap-4 group">
                            @if($editingValueId == $value['id'])
                            <div class="flex-1 flex gap-2">
                                <input wire:model="editingValueText"
                                    class="flex-1 px-3 py-1.5 border border-blue-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-blue-100"
                                    autofocus>
                                <button wire:click="updateValue" class="px-3 py-1.5 bg-blue-600 text-white text-xs font-semibold rounded-lg">Save</button>
                                <button wire:click="$set('editingValueId', null)" class="text-xs text-gray-500 hover:underline">Cancel</button>
                            </div>
                            @else
                            <span class="text-sm text-gray-700 font-medium">{{ $value['value'] }}</span>
                            <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button wire:click="startEditValue({{ $value['id'] }})" class="h-8 w-8 flex items-center justify-center bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-600 hover:text-white transition-all"><i class="ri-edit-2-line text-sm"></i></button>

                                @if($confirmValueId == $value['id'])
                                <div class="flex items-center gap-2 bg-rose-50 px-2 py-1 rounded-md border border-rose-100 shadow-sm">
                                    <span class="text-[10px] text-rose-600 font-black uppercase tracking-tight">Sure?</span>
                                    <button wire:click="deleteValue({{ $value['id'] }})" class="text-[10px] font-black text-rose-700 hover:underline">YES</button>
                                    <button wire:click="cancelConfirmValue" class="text-[10px] font-black text-gray-400">NO</button>
                                </div>
                                @else
                                <button wire:click="confirmValueDelete({{ $value['id'] }})" class="h-8 w-8 flex items-center justify-center bg-rose-50 text-rose-600 rounded-lg hover:bg-rose-600 hover:text-white transition-all"><i class="ri-delete-bin-line text-sm"></i></button>
                                @endif
                            </div>
                            @endif
                        </div>
                        @empty
                        <div class="py-10 text-center text-gray-400 text-sm italic">
                            No variants established yet.
                        </div>
                        @endforelse
                    </div>
                </div>

                <div class="px-6 py-5 bg-gray-50 border-t border-gray-100 shrink-0">
                    <div class="flex gap-2">
                        <input wire:model="newValue"
                            wire:keydown.enter="addNewValue"
                            class="flex-1 px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm outline-none focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500"
                            placeholder="Add a new option...">
                        <button wire:click="addNewValue"
                            class="px-5 py-2.5 bg-blue-600 text-white rounded-xl text-[11px] font-black uppercase tracking-widest shadow-lg shadow-blue-500/20 hover:bg-blue-700 transition-all active:scale-95">
                            Add Option
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <!-- Delete Alert -->
    <template x-teleport="body">
        <div x-show="deleteModal" class="fixed inset-0 z-[110] flex items-center justify-center p-4">
            <div x-show="deleteModal" x-transition.opacity @click="deleteModal=false" class="absolute inset-0 bg-gray-900/40 backdrop-blur-sm"></div>
            <div x-show="deleteModal" x-transition.scale.95 class="relative w-full max-w-sm bg-white rounded-xl p-6 shadow-xl border border-gray-200">
                <h3 class="text-base font-semibold text-gray-900">Delete Attribute?</h3>
                <p class="text-gray-500 text-sm mt-2">All associated variants will be removed. This action cannot be undone.</p>
                <div class="mt-6 flex justify-end gap-3">
                    <button @click="deleteModal=false" class="px-4 py-2 text-sm text-gray-600 font-medium">Cancel</button>
                    <button @click="$wire.deleteConfirmed(deleteId); deleteModal=false" class="px-4 py-2 text-sm bg-rose-600 text-white rounded-lg font-medium hover:bg-rose-700 transition-colors">Confirm Delete</button>
                </div>
            </div>
        </div>
    </template>

</div>