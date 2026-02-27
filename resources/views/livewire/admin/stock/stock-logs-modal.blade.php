<div
    x-data="{ historyOpen: false }"
    x-on:open-history-modal.window="historyOpen = true"
    x-on:close-history-modal.window="historyOpen = false"
    x-cloak>
    <template x-teleport="body">

        <!-- Overlay -->
        <div x-show="historyOpen"
            class="fixed inset-0 z-[99] flex items-center justify-center p-4">

            <!-- Background -->
            <div @click="historyOpen=false"
                class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>

            <!-- Modal -->
            <div
                x-show="historyOpen"
                x-transition
                x-trap.inert.noscroll="historyOpen"
                class="relative w-full max-w-3xl bg-white rounded-xl shadow-2xl max-h-[90vh] flex flex-col overflow-hidden">

                <!-- Header -->
                <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between bg-white sticky top-0 z-10">
                    <div class="flex flex-col">
                        <h3 class="text-lg font-bold text-slate-800 tracking-tight">
                            Inventory History
                        </h3>
                        @if($selectedInventory)
                        <div class="flex items-center gap-2 mt-0.5">
                            <span class="text-xs bg-slate-100 text-slate-600 px-2 py-0.5 rounded-full font-medium">SKU: {{ $selectedInventory->variant->sku }}</span>
                            <span class="text-xs text-slate-500">
                                {{ $selectedInventory->variant->product->name }} ({{ $selectedInventory->variant->name }})
                            </span>
                        </div>
                        @endif
                    </div>

                    <button @click="historyOpen=false"
                        class="h-8 w-8 flex items-center justify-center rounded-full text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition">
                        <i class="ri-close-line text-xl"></i>
                    </button>
                </div>

                <!-- Body -->
                <div class="flex-1 overflow-y-auto px-8 py-10 bg-white">

                    @if(count($historyLogs) > 0)
                    <div class="relative">
                        <!-- Thinner Timeline Line -->
                        <div class="absolute left-[19px] top-0 bottom-0 w-0.5 bg-slate-100 z-0"></div>

                        <div class="space-y-10 relative z-10">
                            @foreach($historyLogs as $log)
                            <div class="flex gap-6 group">
                                <!-- Cleaner Chain Node -->
                                <div class="flex-shrink-0 relative">
                                    <div class="h-10 w-10 rounded-full border-2 border-white shadow-sm flex items-center justify-center transition-colors
                                        @if($log->type === 'stock_in') bg-emerald-50 text-emerald-600
                                        @elseif($log->type === 'stock_out') bg-rose-50 text-rose-600
                                        @elseif($log->type === 'sale') bg-blue-50 text-blue-600
                                        @elseif($log->type === 'return') bg-indigo-50 text-indigo-600
                                        @else bg-amber-50 text-amber-600 @endif">
                                        @if($log->type === 'stock_in') <i class="ri-add-line text-lg"></i>
                                        @elseif($log->type === 'stock_out') <i class="ri-subtract-line text-lg"></i>
                                        @elseif($log->type === 'sale') <i class="ri-shopping-cart-2-line text-base"></i>
                                        @elseif($log->type === 'return') <i class="ri-restart-line text-base"></i>
                                        @else <i class="ri-equalizer-line text-base"></i> @endif
                                    </div>
                                </div>

                                <!-- Lighter Content -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                                        <div class="space-y-1.5">
                                            <div class="flex items-center gap-3">
                                                <span class="text-[10px] font-bold uppercase tracking-widest
                                                    @if($log->type === 'stock_in') text-emerald-600
                                                    @elseif($log->type === 'stock_out') text-rose-600
                                                    @elseif($log->type === 'sale') text-blue-600
                                                    @elseif($log->type === 'return') text-indigo-600
                                                    @else text-amber-600 @endif">
                                                    {{ str_replace('_', ' ', $log->type) }}
                                                </span>
                                                <span class="text-[11px] text-slate-400 font-medium">
                                                    {{ $log->created_at->format('M j, h:i A') }}
                                                </span>
                                            </div>

                                            <div class="flex items-baseline gap-2">
                                                <h4 class="text-sm font-semibold text-slate-800">
                                                    {{ in_array($log->type, ['stock_out', 'sale']) ? '-' : '+' }}{{ $log->quantity }} Units
                                                </h4>
                                                <span class="text-[11px] text-slate-400 font-medium">
                                                    ({{ in_array($log->type, ['stock_out', 'sale']) ? 'Reduced from' : 'Added to' }} inventory)
                                                </span>
                                            </div>

                                            @if($log->note)
                                            <p class="text-[13px] text-slate-500 leading-relaxed max-w-md">
                                                {{ $log->note }}
                                            </p>
                                            @endif
                                        </div>

                                        <!-- Ultra-Clean Stock Level -->
                                        <div class="flex flex-col items-start sm:items-end gap-1">
                                            <span class="text-[10px] font-bold text-slate-300 uppercase tracking-widest">Balance</span>
                                            <div class="flex items-center gap-2 text-sm font-mono tracking-tight">
                                                <span class="text-slate-300">{{ $log->before_quantity }}</span>
                                                <i class="ri-arrow-right-s-line text-slate-200"></i>
                                                <span class="font-bold text-slate-900 bg-slate-50 px-2 py-0.5 rounded">{{ $log->after_quantity }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Separator for better pacing -->
                                    <div class="mt-8 border-b border-slate-50"></div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @else
                    <div class="flex flex-col items-center justify-center py-20 text-center">
                        <div class="h-20 w-20 bg-slate-100 rounded-full flex items-center justify-center mb-4 text-slate-300">
                            <i class="ri-history-line text-4xl"></i>
                        </div>
                        <h4 class="text-slate-900 font-semibold italic">No History Found</h4>
                        <p class="text-sm text-slate-400 mt-1 max-w-xs">There are no inventory logs recorded for this variant yet.</p>
                    </div>
                    @endif
                </div>

                <!-- Footer -->
                <div class="px-5 py-3 border-t border-slate-200 flex justify-end gap-3 bg-slate-50">
                    <button
                        @click="historyOpen=false"
                        class="inline-flex items-center gap-2 px-5 py-2 text-sm font-medium
                               rounded-lg bg-white border border-slate-300
                               text-slate-700 shadow-sm hover:bg-slate-50
                               transition-all duration-200">
                        Close
                    </button>
                </div>

            </div>

        </div>

    </template>
</div>