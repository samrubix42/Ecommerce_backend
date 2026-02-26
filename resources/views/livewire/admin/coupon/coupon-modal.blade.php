<div
    x-data="{ modalOpen: false }"
    x-on:open-modal.window="modalOpen = true"
    x-on:close-modal.window="modalOpen = false"
    x-cloak>
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
                class="relative w-full max-w-3xl bg-white rounded-xl shadow-2xl max-h-[90vh] flex flex-col overflow-hidden">

                <!-- Header -->
                <div class="px-5 py-3 border-b border-slate-200 flex items-center justify-between bg-white">
                    <h3 class="text-base font-semibold text-slate-800">
                        {{ $couponId ? 'Edit Coupon' : 'Add Coupon' }}
                    </h3>

                    <button @click="modalOpen=false"
                        class="text-slate-400 hover:text-slate-600 transition">
                        <i class="ri-close-line text-lg"></i>
                    </button>
                </div>

                <!-- Body -->
                <div class="flex-1 overflow-y-auto px-5 py-4 space-y-6 text-sm">

                    <!-- Grid Layout -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                        <!-- Code -->
                        <div>
                            <label class="text-xs font-medium text-slate-600">Coupon Code</label>
                            <input wire:model="code"
                                placeholder="SUMMER2026"
                                class="mt-1 w-full rounded-md border border-slate-300 px-3 py-1.5
                                          focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none uppercase">
                            @error('code')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Type -->
                        <div>
                            <label class="text-xs font-medium text-slate-600">Type</label>
                            <select wire:model.live="type"
                                class="mt-1 w-full rounded-md border border-slate-300 px-3 py-1.5
                                           focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none">
                                <option value="fixed">Fixed Amount</option>
                                <option value="percentage">Percentage</option>
                            </select>
                            @error('type')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Value -->
                        <div>
                            <label class="text-xs font-medium text-slate-600">
                                Value ({{ $type === 'percentage' ? '%' : '$' }})
                            </label>
                            <input type="number" step="0.01" wire:model="value"
                                class="mt-1 w-full rounded-md border border-slate-300 px-3 py-1.5
                                          focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none">
                            @error('value')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Min Cart Amount -->
                        <div>
                            <label class="text-xs font-medium text-slate-600">Minimum Cart Amount</label>
                            <input type="number" step="0.01" wire:model="min_cart_amount"
                                class="mt-1 w-full rounded-md border border-slate-300 px-3 py-1.5
                                          focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none">
                            @error('min_cart_amount')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        @if($type === 'percentage')
                        <!-- Max Discount -->
                        <div>
                            <label class="text-xs font-medium text-slate-600">Maximum Discount Amount</label>
                            <input type="number" step="0.01" wire:model="max_discount"
                                class="mt-1 w-full rounded-md border border-slate-300 px-3 py-1.5
                                          focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none">
                            @error('max_discount')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        @endif

                        <!-- Usage Limit -->
                        <div>
                            <label class="text-xs font-medium text-slate-600">Total Usage Limit</label>
                            <input type="number" wire:model="usage_limit"
                                class="mt-1 w-full rounded-md border border-slate-300 px-3 py-1.5
                                          focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none">
                            @error('usage_limit')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Per User Limit -->
                        <div>
                            <label class="text-xs font-medium text-slate-600">Limit Per User</label>
                            <input type="number" wire:model="per_user_limit"
                                class="mt-1 w-full rounded-md border border-slate-300 px-3 py-1.5
                                          focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none">
                            @error('per_user_limit')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Starts At -->
                        <div>
                            <label class="text-xs font-medium text-slate-600">Starts At</label>
                            <input type="datetime-local" wire:model="starts_at"
                                class="mt-1 w-full rounded-md border border-slate-300 px-3 py-1.5
                                          focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none">
                            @error('starts_at')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Expires At -->
                        <div>
                            <label class="text-xs font-medium text-slate-600">Expires At</label>
                            <input type="datetime-local" wire:model="expires_at"
                                class="mt-1 w-full rounded-md border border-slate-300 px-3 py-1.5
                                          focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none">
                            @error('expires_at')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Active Toggle -->
                        <div class="flex items-center gap-6 pt-5">
                            <label class="flex items-center gap-2 text-xs text-slate-700 cursor-pointer">
                                <input type="checkbox"
                                    wire:model="is_active"
                                    class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                Active
                            </label>
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

                        <span wire:loading.remove wire:target="save">Save Coupon</span>
                        <span wire:loading wire:target="save">Saving...</span>

                    </button>

                </div>

            </div>

        </div>

    </template>
</div>