     <div x-data="{ deleteOpen: false }" x-on:open-delete-modal.window="deleteOpen = true" x-on:close-delete-modal.window="deleteOpen = false" x-cloak>
            <template x-teleport="body">
                <div x-show="deleteOpen" class="fixed inset-0 z-[99] flex items-center justify-center px-4">
                    <div @click="deleteOpen=false" class="absolute inset-0 bg-black/40"></div>
                    <div x-show="deleteOpen" x-transition x-trap.inert.noscroll="deleteOpen" class="relative w-full max-w-sm rounded-xl bg-white p-6 shadow-xl">
                        <h3 class="text-lg font-semibold text-slate-900 mb-4">Delete Category</h3>
                        <p class="mb-6 text-slate-700">Are you sure you want to delete this category? This action cannot be undone.</p>
                        <div class="flex justify-end gap-3">
                            <button @click="deleteOpen=false" class="rounded-md border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Cancel</button>
                            <button wire:click="delete({{ $deleteId }})" class="inline-flex items-center gap-1 rounded-md bg-rose-600 px-4 py-2 text-sm font-medium text-white hover:bg-rose-500">Delete</button>
                        </div>
                    </div>
                </div>
            </template>
        </div>