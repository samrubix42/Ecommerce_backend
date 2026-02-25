<div x-data="{
    attributeModal:false,
    valueModal:false,
    deleteModal:false,
    deleteId:null
}"
     x-init="
        // Listen for Livewire events
        Livewire.on('close-attribute-modal', () =&gt; attributeModal = false);
        Livewire.on('open-attribute-modal', () =&gt; attributeModal = true);
        Livewire.on('open-attribute-delete-modal', () =&gt; deleteModal = true);
        Livewire.on('close-attribute-delete-modal', () =&gt; deleteModal = false);
        Livewire.on('open-attribute-value-modal', (name) =&gt; { valueModal = true; valueAttributeName = name });

        // Also listen for DOM custom events (dispatchBrowserEvent)
        window.addEventListener('close-attribute-modal', () =&gt; attributeModal = false);
        window.addEventListener('open-attribute-modal', () =&gt; attributeModal = true);
        window.addEventListener('open-attribute-delete-modal', () =&gt; deleteModal = true);
        window.addEventListener('close-attribute-delete-modal', () =&gt; deleteModal = false);
        window.addEventListener('open-attribute-value-modal', (e) =&gt; { valueModal = true; valueAttributeName = e.detail?.name ?? valueAttributeName; });
    "
    class="p-6 space-y-6">

    <!-- HEADER -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Attributes</h1>
            <p class="text-sm text-gray-500">Manage product attributes</p>
        </div>

        <div class="flex gap-3">
            <input wire:model.live.debounce.400ms="search"
                class="px-4 py-2 border rounded-md text-sm focus:ring focus:ring-blue-200"
                placeholder="Search...">

            <button @click="attributeModal=true; $wire.resetForm()"
                class="px-5 py-2 text-sm font-medium text-white bg-blue-600 rounded-md shadow-sm hover:bg-blue-700 transition">
                + New
            </button>
        </div>
    </div>


    <!-- TABLE -->
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                <tr>
                 
                    <th class="px-6 py-4 text-left">Attribute</th>
                    <th class="px-6 py-4 text-left">Status</th>
                    <th class="px-6 py-4 text-right w-40">Actions</th>
                </tr>
            </thead>
            <tbody>

                @foreach($attributeList as $attribute)
                <tr wire:key="attribute-{{ $attribute->id }}" class="hover:bg-slate-50 transition">

                  

                    <td class="px-6 py-5">
                        <div class="flex items-start gap-2">
                            <div>
                                <p class="font-medium text-slate-900">{{ $attribute->name }}</p>
                            </div>
                        </div>
                    </td>

                    <td class="px-6 py-5">
                        @if($attribute->status)
                        <span class="text-emerald-600 text-xs font-medium">Active</span>
                        @else
                        <span class="text-rose-600 text-xs font-medium">Inactive</span>
                        @endif
                    </td>

                    <td class="px-6 py-5 text-right">
                        <div class="flex justify-end gap-2">
                            <button @click="valueModal=true; $wire.openValueModal({{ $attribute->id }})" class="bg-blue-50 text-blue-600 px-3 py-1.5 rounded-md text-xs">Values</button>

                            <button @click="attributeModal=true; $wire.openEdit({{ $attribute->id }})" class="bg-blue-50 text-blue-600 px-3 py-1.5 rounded-md text-xs">Edit</button>

                            <button @click="deleteModal=true; deleteId={{ $attribute->id }}" class="bg-rose-50 text-rose-600 px-3 py-1.5 rounded-md text-xs">Delete</button>
                        </div>
                    </td>

                </tr>
                @endforeach

            </tbody>
        </table>

        <div class="p-4 flex items-center justify-end">
            {{ $attributeList->links() }}
        </div>
    </div>

    <!-- ATTRIBUTE MODAL -->
    <div x-cloak x-data="{}">
        <template x-teleport="body">
            <div x-show="attributeModal" class="fixed inset-0 z-[99] flex items-center justify-center p-4">

                <!-- Background -->
                <div @click="attributeModal=false" class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>

                <!-- Modal -->
                <div x-show="attributeModal" x-transition x-trap.inert.noscroll="attributeModal" class="relative w-full max-w-lg bg-white rounded-md shadow-2xl max-h-[85vh] overflow-auto">

                    <!-- Header -->
                    <div class="px-5 py-3 border-b border-slate-200 flex items-center justify-between bg-white">
                        <h3 class="text-base font-semibold text-slate-800">{{ $attributeId ? 'Edit Attribute' : 'Create Attribute' }}</h3>
                        <button @click="attributeModal=false" class="text-slate-400 hover:text-slate-600">
                            <i class="ri-close-line text-lg"></i>
                        </button>
                    </div>

                    <!-- Body -->
                    <div class="px-5 py-4 space-y-4 text-sm">
                        <div>
                            <label class="text-xs font-medium text-slate-600">Name</label>
                            <input wire:model.defer="name" class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 focus:ring-2 focus:ring-blue-500/30" placeholder="e.g. Color">
                            @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="flex items-center gap-3">
                            <label class="flex items-center gap-2 text-sm text-slate-700">
                                <input type="checkbox" wire:model="status" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                Active
                            </label>
                        </div>

                        {{-- Values are managed in the separate Values modal --}}
                    </div>

                    <!-- Footer -->
                    <div class="px-5 py-3 border-t border-slate-200 flex justify-end gap-3 bg-slate-50">
                        <button @click="attributeModal=false" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-md border border-slate-300 text-slate-600 hover:bg-slate-100">Cancel</button>

                        <button wire:click="save" wire:loading.attr="disabled" class="inline-flex items-center gap-2 px-5 py-2 text-sm font-medium rounded-md bg-gradient-to-r from-blue-600 to-blue-700 text-white shadow-md hover:shadow-lg disabled:opacity-60">
                            <svg wire:loading wire:target="save" class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                            </svg>
                            <i wire:loading.remove wire:target="save" class="ri-save-line text-base"></i>
                            <span wire:loading.remove wire:target="save">Save</span>
                            <span wire:loading wire:target="save">Saving...</span>
                        </button>
                    </div>

                </div>
            </div>
        </template>
    </div>

    <!-- VALUE MODAL -->
    <div x-cloak x-data="{}">
        <template x-teleport="body">
            <div x-show="valueModal" class="fixed inset-0 z-[99] flex items-center justify-center p-4">
                <div @click="valueModal=false" class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>

                <div x-show="valueModal" x-transition x-trap.inert.noscroll="valueModal" class="relative w-full max-w-xl bg-white rounded-md shadow-2xl max-h-[85vh] overflow-auto">

                    <div class="px-5 py-3 border-b border-slate-200 flex items-center justify-between bg-white">
                        <h3 class="text-base font-semibold text-slate-800">Manage Values — {{ $valueAttributeName }}</h3>
                        <button @click="valueModal=false" class="text-slate-400 hover:text-slate-600"><i class="ri-close-line text-lg"></i></button>
                    </div>

                    <div class="px-5 py-4 space-y-4 text-sm">
                        <div class="space-y-3 max-h-64 overflow-y-auto">
                            @foreach($currentValues as $value)
                            <div class="flex justify-between items-center bg-slate-50 p-3 rounded-md">
                                @if($editingValueId == $value['id'])
                                <div class="flex gap-2 w-full">
                                    <input wire:model="editingValueText" class="flex-1 px-3 py-1 border rounded-md text-sm">
                                    <button wire:click="updateValue" class="px-3 py-1 bg-blue-600 text-white text-xs rounded-md">Save</button>
                                </div>
                                @else
                                <span class="text-sm">{{ $value['value'] }}</span>
                                <div class="flex gap-3 items-center">
                                    <button wire:click="startEditValue({{ $value['id'] }})" class="text-blue-600 text-xs">Edit</button>

                                    @if($confirmValueId == $value['id'])
                                    <span class="text-xs text-slate-500">Confirm?</span>
                                    <button wire:click="deleteValue({{ $value['id'] }})" class="text-rose-600 text-xs">Yes</button>
                                    <button wire:click="cancelConfirmValue" class="text-slate-600 text-xs">No</button>
                                    @else
                                    <button wire:click="confirmValueDelete({{ $value['id'] }})" class="text-rose-600 text-xs">Delete</button>
                                    @endif
                                </div>
                                @endif
                            </div>
                            @endforeach
                        </div>

                        <div class="mt-4 flex gap-3">
                            <input wire:model="newValue" class="flex-1 px-4 py-2 border rounded-md text-sm" placeholder="Add new value">
                            <button wire:click="addNewValue" class="px-5 py-2 bg-blue-600 text-white rounded-md text-sm hover:bg-blue-700">Add</button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <!-- DELETE MODAL -->
    <div x-cloak x-data="{}">
        <template x-teleport="body">
            <div x-show="deleteModal" class="fixed inset-0 z-[99] flex items-center justify-center p-4">
                <div @click="deleteModal=false" class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>
                <div x-show="deleteModal" x-transition x-trap.inert.noscroll="deleteModal" class="relative w-full max-w-md rounded-md bg-white p-6 shadow-xl">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">Delete Attribute</h3>
                    <p class="mb-6 text-slate-700">Are you sure you want to delete this attribute?</p>
                    <div class="flex justify-end gap-3">
                        <button @click="deleteModal=false" class="rounded-md border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Cancel</button>
                        <button @click="$wire.deleteConfirmed(deleteId); deleteModal=false" class="inline-flex items-center gap-1 rounded-md bg-rose-600 px-4 py-2 text-sm font-medium text-white hover:bg-rose-500">Delete</button>
                    </div>
                </div>
            </div>
        </template>
    </div>

</div>