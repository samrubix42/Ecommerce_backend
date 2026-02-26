<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900 tracking-tight">
                Coupon Management
            </h1>
            <p class="text-sm text-slate-500 mt-1">
                Manage your store coupons and discounts.
            </p>
        </div>

        <button
            @click="$dispatch('open-modal'); $wire.resetForm()"
            class="inline-flex items-center justify-center gap-2 rounded-md bg-blue-600
                   px-5 py-2.5 text-sm font-medium text-white shadow-sm
                   hover:bg-blue-500 transition">
            <i class="ri-add-line text-base"></i>
            Add Coupon
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
            placeholder="Search coupons..."
            class="w-full rounded-md border border-slate-300 pl-9 pr-4 py-2.5 text-sm
                   focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none transition">
    </div>

    <!-- Desktop Table -->
    <div class="hidden sm:block bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                <tr>
                    <th class="px-6 py-4 text-left">Code</th>
                    <th class="px-6 py-4 text-left">Type</th>
                    <th class="px-6 py-4 text-left">Value</th>
                    <th class="px-6 py-4 text-left">Usage</th>
                    <th class="px-6 py-4 text-left">Status</th>
                    <th class="px-6 py-4 text-right w-40">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($coupons as $coupon)
                <tr wire:key="coupon-{{ $coupon->id }}" class="hover:bg-slate-50 transition">
                    <td class="px-6 py-5">
                        <span class="font-mono font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded">
                            {{ $coupon->code }}
                        </span>
                    </td>
                    <td class="px-6 py-5 capitalize">
                        {{ $coupon->type }}
                    </td>
                    <td class="px-6 py-5">
                        @if($coupon->type === 'percentage')
                        {{ $coupon->value }}%
                        @else
                        ${{ number_format($coupon->value, 2) }}
                        @endif
                    </td>
                    <td class="px-6 py-5">
                        <span class="text-slate-500">
                            {{ $coupon->usage_limit ? $coupon->usage_limit : '∞' }}
                        </span>
                    </td>
                    <td class="px-6 py-5">
                        @if($coupon->is_active)
                        <span class="text-emerald-600 text-xs font-medium bg-emerald-50 px-2 py-1 rounded-full">Active</span>
                        @else
                        <span class="text-rose-600 text-xs font-medium bg-rose-50 px-2 py-1 rounded-full">Inactive</span>
                        @endif
                    </td>
                    <td class="px-6 py-5 text-right">
                        <div class="flex justify-end gap-2">
                            <button
                                @click="$dispatch('open-modal'); $wire.openEditModal({{ $coupon->id }})"
                                class="bg-blue-50 text-blue-600 px-3 py-1.5 rounded-md text-xs">
                                Edit
                            </button>
                            <button
                                @click="$dispatch('open-delete-modal'); $wire.confirmDelete({{ $coupon->id }})"
                                class="bg-rose-50 text-rose-600 px-3 py-1.5 rounded-md text-xs">
                                Delete
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $coupons->links() }}
        </div>
    </div>

    <!-- Mobile Cards -->
    <div class="sm:hidden space-y-4">
        @forelse($coupons as $coupon)
        <div wire:key="mobile-coupon-{{ $coupon->id }}"
            class="bg-white border border-slate-200 rounded-md p-4 shadow-sm space-y-3">
            <div class="flex items-start justify-between">
                <div>
                    <span class="font-mono font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded">
                        {{ $coupon->code }}
                    </span>
                    <p class="text-xs text-slate-500 mt-2 capitalize">
                        {{ $coupon->type }} • @if($coupon->type === 'percentage') {{ $coupon->value }}% @else ${{ number_format($coupon->value, 2) }} @endif
                    </p>
                </div>
                @if($coupon->is_active)
                <span class="text-[10px] font-medium text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">Active</span>
                @else
                <span class="text-[10px] font-medium text-rose-600 bg-rose-50 px-2 py-0.5 rounded-full">Inactive</span>
                @endif
            </div>
            <div class="flex items-center justify-between pt-2 border-t border-slate-100">
                <span class="text-xs text-slate-400">Limit: {{ $coupon->usage_limit ?: '∞' }}</span>
                <div class="flex gap-2">
                    <button
                        @click="$dispatch('open-modal'); $wire.openEditModal({{ $coupon->id }})"
                        class="bg-blue-50 text-blue-600 px-3 py-1.5 rounded-md text-xs font-medium">
                        Edit
                    </button>
                    <button
                        @click="$dispatch('open-delete-modal'); $wire.confirmDelete({{ $coupon->id }})"
                        class="bg-rose-50 text-rose-600 px-3 py-1.5 rounded-md text-xs font-medium">
                        Delete
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="rounded-md border border-dashed border-slate-200 bg-slate-50 py-10 text-center text-slate-400">
            No coupons found.
        </div>
        @endforelse
        <div class="mt-4">
            {{ $coupons->links() }}
        </div>
    </div>

    @include('livewire.admin.coupon.coupon-modal')
    @include('livewire.admin.coupon.delete-coupon')

</div>