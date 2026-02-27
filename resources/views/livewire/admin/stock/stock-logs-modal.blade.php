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
                <div class="flex-1 overflow-y-auto px-6 py-8 bg-slate-50/50">

                    @if(count($historyLogs) > 0)
                    <div class="relative">
                        <!-- Vertical Chain Line -->
                        <div class="absolute left-6 top-0 bottom-0 w-px bg-slate-200 z-0"></div>

                        <div class="space-y-8 relative z-10">
                            @foreach($historyLogs as $log)
                            <div class="flex gap-6">
                                <!-- Chain Node -->
                                <div class="flex-shrink-0">
                                    <div class="h-12 w-12 rounded-full border-4 border-white shadow-sm flex items-center justify-center
                                        @if($log->type === 'stock_in') bg-emerald-500
                                        @elseif($log->type === 'stock_out') bg-rose-500
                                        @elseif($log->type === 'sale') bg-blue-500
                                        @elseif($log->type === 'return') bg-indigo-500
                                        @else bg-amber-500 @endif text-white">
                                        @if($log->type === 'stock_in') <i class="ri-arrow-left-down-line text-lg"></i>
                                        @elseif($log->type === 'stock_out') <i class="ri-arrow-right-up-line text-lg"></i>
                                        @elseif($log->type === 'sale') <i class="ri-shopping-bag-line text-lg"></i>
                                        @elseif($log->type === 'return') <i class="ri-refresh-line text-lg"></i>
                                        @else <i class="ri-settings-3-line text-lg"></i> @endif
                                    </div>
                                </div>

                                <!-- Content Card -->
                                <div class="flex-1 bg-white border border-slate-200 rounded-xl p-4 shadow-sm hover:shadow-md transition-all">
                                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                        <div class="space-y-1">
                                            <div class="flex items-center gap-2">
                                                <span class="text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded
                                                    @if($log->type === 'stock_in') text-emerald-700 bg-emerald-50
                                                    @elseif($log->type === 'stock_out') text-rose-700 bg-rose-50
                                                    @elseif($log->type === 'sale') text-blue-700 bg-blue-50
                                                    @elseif($log->type === 'return') text-indigo-700 bg-indigo-50
                                                    @else text-amber-700 bg-amber-50 @endif">
                                                    {{ str_replace('_', ' ', $log->type) }}
                                                </span>
                                                <span class="text-xs text-slate-400 font-medium">
                                                    {{ $log->created_at->format('M j, Y • h:i A') }}
                                                </span>
                                            </div>
                                            <p class="text-base font-bold text-slate-800">
                                                {{ in_array($log->type, ['stock_out', 'sale']) ? '-' : '+' }}{{ $log->quantity }} Units
                                                <span class="text-sm font-normal text-slate-400 ml-1">
                                                    ({{ in_array($log->type, ['stock_out', 'sale']) ? 'Reduced' : 'Added' }} to stock)
                                                </span>
                                            </p>
                                            @if($log->note)
                                            <p class="text-sm text-slate-500 bg-slate-50 p-2 rounded-lg border-l-2 border-slate-200 italic">
                                                "{{ $log->note }}"
                                            </p>
                                            @endif
                                        </div>

                                        <div class="flex flex-row sm:flex-col items-center sm:items-end justify-between sm:justify-center px-4 py-2 bg-slate-50 rounded-lg sm:bg-transparent">
                                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Stock Level</span>
                                            <div class="flex items-center gap-3">
                                                <span class="text-sm font-mono text-slate-400 line-through decoration-slate-300">{{ $log->before_quantity }}</span>
                                                <i class="ri-arrow-right-long-line text-slate-300"></i>
                                                <span class="text-lg font-mono font-black text-slate-900">{{ $log->after_quantity }}</span>
                                            </div>
                                        </div>
                                    </div>
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