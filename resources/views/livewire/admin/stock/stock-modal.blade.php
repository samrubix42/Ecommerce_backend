<div
    x-data="{ modalOpen: false }"
    x-on:open-adjustment-modal.window="modalOpen = true"
    x-on:close-adjustment-modal.window="modalOpen = false"
    x-cloak>
    <template x-teleport="body">

        <!-- Overlay -->
        <div x-show="modalOpen"
            class="fixed inset-0 z-[99] flex items-center justify-center p-4 shadow-2xl">

            <!-- Background -->
            <div @click="modalOpen=false"
                class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>

            <!-- Modal -->
            <div
                x-show="modalOpen"
                x-transition
                x-trap.inert.noscroll="modalOpen"
                class="relative w-full max-w-xl bg-white rounded-xl shadow-2xl max-h-[90vh] flex flex-col overflow-hidden">

                <!-- Header -->
                <div class="px-5 py-3 border-b border-slate-200 flex items-center justify-between bg-white">
                    <h3 class="text-base font-semibold text-slate-800">
                        Adjust Stock
                    </h3>

                    <button @click="modalOpen=false"
                        class="text-slate-400 hover:text-slate-600 transition">
                        <i class="ri-close-line text-lg"></i>
                    </button>
                </div>

                <!-- Body -->
                <div class="flex-1 overflow-y-auto px-5 py-6 space-y-6 text-sm">

                    <div class="space-y-4">
                        <!-- Adjustment Type -->
                        <div>
                            <label class="text-xs font-medium text-slate-600">Adjustment Type</label>
                            <div class="grid grid-cols-3 sm:grid-cols-5 gap-2 mt-1.5">
                                <label class="cursor-pointer">
                                    <input type="radio" wire:model.live="adjustmentType" value="stock_in" class="peer hidden">
                                    <div class="px-2 py-2 text-center rounded-lg border border-slate-200 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 peer-checked:text-emerald-600 transition text-[10px] font-bold uppercase"> In</div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" wire:model.live="adjustmentType" value="stock_out" class="peer hidden">
                                    <div class="px-2 py-2 text-center rounded-lg border border-slate-200 peer-checked:border-rose-500 peer-checked:bg-rose-50 peer-checked:text-rose-600 transition text-[10px] font-bold uppercase"> Out</div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" wire:model.live="adjustmentType" value="sale" class="peer hidden">
                                    <div class="px-2 py-2 text-center rounded-lg border border-slate-200 peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-600 transition text-[10px] font-bold uppercase"> Sale</div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" wire:model.live="adjustmentType" value="return" class="peer hidden">
                                    <div class="px-2 py-2 text-center rounded-lg border border-slate-200 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 peer-checked:text-indigo-600 transition text-[10px] font-bold uppercase"> Return</div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" wire:model.live="adjustmentType" value="adjustment" class="peer hidden">
                                    <div class="px-2 py-2 text-center rounded-lg border border-slate-200 peer-checked:border-amber-500 peer-checked:bg-amber-50 peer-checked:text-amber-600 transition text-[10px] font-bold uppercase"> Adjust</div>
                                </label>
                            </div>
                            @error('adjustmentType')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Quantity -->
                        <div>
                            <label class="text-xs font-medium text-slate-600">Quantity Change</label>
                            <input type="number" wire:model="adjustmentQuantity" min="1"
                                placeholder="10"
                                class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2
                                          focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none">
                            @error('adjustmentQuantity')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Note -->
                        <div>
                            <label class="text-xs font-medium text-slate-600">Note / Reference (Optional)</label>
                            <textarea wire:model="adjustmentNote" rows="3"
                                placeholder="e.g. Added stock from supplier X"
                                class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2
                                             focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none"></textarea>
                            @error('adjustmentNote')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
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
                        Cancel
                    </button>

                    <!-- Save -->
                    <button
                        wire:click="applyAdjustment"
                        wire:loading.attr="disabled"
                        class="inline-flex items-center gap-2 px-5 py-2 text-sm font-medium
                               rounded-lg bg-blue-600
                               text-white shadow-md hover:shadow-lg
                               hover:bg-blue-700
                               transition-all duration-200
                               disabled:opacity-60 disabled:cursor-not-allowed">

                        <i wire:loading.remove wire:target="applyAdjustment" class="ri-check-line text-base"></i>
                        <span wire:loading.remove wire:target="applyAdjustment">Update Stock</span>
                        <span wire:loading wire:target="applyAdjustment">Updating...</span>
                    </button>

                </div>

            </div>

        </div>

    </template>
</div>