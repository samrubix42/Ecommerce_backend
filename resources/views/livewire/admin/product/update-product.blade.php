<div x-data="{ animating: false }" class="max-w-5xl mx-auto">

    {{-- ═══════════════════════════════════════════════════════
         PAGE HEADER
    ═══════════════════════════════════════════════════════ --}}
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-neutral-800 tracking-tight">Update Product</h1>
            <p class="text-sm text-neutral-500 mt-1">Editing: <span class="text-blue-600 font-semibold">{{ $product->name }}</span></p>
        </div>
        <a href="{{ route('admin.products.index') }}" wire:navigate
           class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-neutral-600 border border-neutral-200 rounded-xl hover:bg-neutral-50 transition-all">
            <i class="ri-arrow-left-s-line"></i>
            Back to List
        </a>
    </div>

    {{-- ═══════════════════════════════════════════════════════
         STEPPER NAVIGATION
    ═══════════════════════════════════════════════════════ --}}
    @php
        $steps = $this->stepInfo;
    @endphp

    <div class="bg-white rounded-2xl shadow-sm border border-neutral-200/60 px-8 py-6 mb-6">
        <div class="flex items-center justify-between">
            @foreach($steps as $num => $info)
                @php
                    $skipped   = $this->isStepSkipped($num);
                    $completed = $num < $step;
                    $current   = $num === $step;
                @endphp

                {{-- Step Circle + Label --}}
                <div class="flex flex-col items-center relative z-10">
                    <button
                        wire:click="goToStep({{ $num }})"
                        @class([
                            'w-11 h-11 rounded-full flex items-center justify-center text-lg transition-all duration-300 border-2',
                            'bg-gradient-to-br from-blue-500 to-blue-600 text-white border-blue-400 shadow-lg shadow-blue-200/50 scale-110' => $current,
                            'bg-blue-600 text-white border-blue-500 shadow-sm' => $completed && !$skipped,
                            'bg-neutral-100 text-neutral-300 border-neutral-200 opacity-50 cursor-not-allowed' => $skipped,
                            'bg-white text-neutral-400 border-neutral-200 hover:border-neutral-300' => !$current && !$completed && !$skipped,
                        ])
                        @if($skipped) disabled @endif
                    >
                        @if($completed && !$skipped)
                            <i class="ri-check-line"></i>
                        @else
                            <i class="{{ $info['icon'] }}"></i>
                        @endif
                    </button>

                    <span @class([
                        'text-xs font-medium mt-2 transition-colors duration-300',
                        'text-blue-600 font-semibold' => $current,
                        'text-blue-500' => $completed && !$skipped,
                        'text-neutral-300 line-through' => $skipped,
                        'text-neutral-400' => !$current && !$completed && !$skipped,
                    ])>
                        {{ $info['title'] }}
                    </span>
                </div>

                {{-- Connector Line --}}
                @if(!$loop->last)
                    <div class="flex-1 mx-3 mt-[-20px]">
                        <div @class([
                            'h-0.5 rounded-full transition-all duration-500',
                            'bg-blue-600' => $completed,
                            'bg-neutral-200' => !$completed,
                        ])></div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════
         STEP CONTENT
    ═══════════════════════════════════════════════════════ --}}
    <div class="bg-white rounded-2xl shadow-sm border border-neutral-200/60 p-8 min-h-[400px]">

        {{-- Step Title --}}
        <div class="mb-8 pb-5 border-b border-neutral-100">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white shadow-sm">
                    <i class="{{ $steps[$step]['icon'] ?? 'ri-file-line' }}"></i>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-neutral-800">{{ $steps[$step]['title'] ?? '' }}</h2>
                    <p class="text-xs text-neutral-400">Step {{ $step }} of {{ $totalSteps }}</p>
                </div>
            </div>
        </div>

        {{-- Step Body --}}
        <div wire:key="step-content-{{ $step }}">
            <style>
                @keyframes pinesFadeSlideIn {
                    from { opacity: 0; transform: translateY(12px); }
                    to   { opacity: 1; transform: translateY(0); }
                }
                .pines-animate-in { animation: pinesFadeSlideIn .35s ease-out; }
            </style>

            <div class="pines-animate-in">
                @switch($step)
                    @case(1) @include('livewire.admin.product.steps.step-1') @break
                    @case(2) @include('livewire.admin.product.steps.step-2') @break
                    @case(3) @include('livewire.admin.product.steps.step-3') @break
                    @case(4) @include('livewire.admin.product.steps.step-4') @break
                    @case(5) @include('livewire.admin.product.steps.step-5') @break
                @endswitch
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════
         NAVIGATION BUTTONS
    ═══════════════════════════════════════════════════════ --}}
    <div class="flex items-center justify-between mt-6">
        <div>
            @if($step > 1)
                <button wire:click="back"
                    class="group inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-white border border-neutral-200 text-neutral-600
                           hover:bg-neutral-50 hover:border-neutral-300 transition-all duration-200 text-sm font-medium shadow-sm">
                    <i class="ri-arrow-left-line transition-transform group-hover:-translate-x-0.5"></i>
                    Back
                </button>
            @endif
        </div>

        <div>
            @if($step < $totalSteps)
                <button wire:click="next"
                    class="group inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-gradient-to-r from-blue-600 to-blue-700
                           text-white hover:from-blue-700 hover:to-blue-800 transition-all duration-200 text-sm font-medium
                           shadow-lg shadow-blue-200/50 hover:shadow-blue-300/50">
                    Continue
                    <i class="ri-arrow-right-line transition-transform group-hover:translate-x-0.5"></i>
                </button>
            @else
                <button wire:click="save"
                    wire:loading.attr="disabled"
                    class="group inline-flex items-center gap-2 px-7 py-2.5 rounded-xl bg-gradient-to-r from-blue-600 to-blue-700
                           text-white hover:from-blue-700 hover:to-blue-800 transition-all duration-200 text-sm font-medium
                           shadow-lg shadow-blue-200/50 hover:shadow-blue-300/50 disabled:opacity-60">
                    <span wire:loading.remove wire:target="save">
                        <i class="ri-save-line"></i> Update Product
                    </span>
                    <span wire:loading wire:target="save">
                        <i class="ri-loader-4-line animate-spin"></i> Updating…
                    </span>
                </button>
            @endif
        </div>
    </div>
</div>
