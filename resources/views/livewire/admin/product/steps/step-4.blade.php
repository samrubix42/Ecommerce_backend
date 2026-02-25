{{-- ═══════════════════════════════════════════════════════
     STEP 4 — MEDIA UPLOAD
═══════════════════════════════════════════════════════ --}}

<div class="space-y-6">

    {{-- Upload Zone --}}
    <div x-data="{ dragging: false }"
         x-on:dragover.prevent="dragging = true"
         x-on:dragleave.prevent="dragging = false"
         x-on:drop.prevent="dragging = false"
         :class="dragging ? 'border-blue-400 bg-blue-50/50 scale-[1.01]' : 'border-neutral-200 bg-neutral-50/30 hover:border-blue-300 hover:bg-blue-50/30'"
         class="relative border-2 border-dashed rounded-2xl p-10 text-center transition-all duration-300 cursor-pointer group">

        <input type="file" wire:model="productImages" multiple accept="image/*"
            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" id="media-upload">

        <div class="flex flex-col items-center">
            <div :class="dragging ? 'bg-blue-200 text-blue-700 scale-110' : 'bg-neutral-100 text-neutral-400 group-hover:bg-blue-100 group-hover:text-blue-500'"
                 class="w-16 h-16 rounded-2xl flex items-center justify-center mb-4 transition-all duration-300">
                <i class="ri-image-add-line text-3xl"></i>
            </div>

            <h4 class="text-sm font-semibold text-neutral-700 mb-1">
                <span x-show="!dragging">Drop images here or click to browse</span>
                <span x-show="dragging" class="text-blue-600">Release to upload</span>
            </h4>
            <p class="text-xs text-neutral-400">PNG, JPG, WEBP up to 2MB each</p>
        </div>

        {{-- Loading Indicator --}}
        <div wire:loading wire:target="productImages" class="absolute inset-0 bg-white/80 rounded-2xl flex items-center justify-center backdrop-blur-sm">
            <div class="flex flex-col items-center gap-2">
                <i class="ri-loader-4-line animate-spin text-2xl text-blue-600"></i>
                <span class="text-sm font-medium text-blue-600">Uploading...</span>
            </div>
        </div>
    </div>

    @error('productImages.*')
        <p class="text-xs text-red-500 flex items-center gap-1"><i class="ri-error-warning-line"></i> {{ $message }}</p>
    @enderror

    {{-- Existing Images Previews --}}
    @if(isset($existingImages) && count($existingImages) > 0)
        <div>
            <div class="flex items-center justify-between mb-3">
                <h4 class="text-sm font-semibold text-neutral-700">
                    Existing Images
                    <span class="ml-1 text-xs font-normal text-neutral-400">({{ count($existingImages) }} files)</span>
                </h4>
            </div>

            <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-3">
                @foreach($existingImages as $index => $img)
                    <div class="group relative rounded-xl overflow-hidden shadow-sm border border-neutral-200 aspect-square bg-neutral-100"
                         wire:key="existing-img-{{ $img['id'] }}">

                        <img src="{{ asset('storage/' . $img['image_path']) }}"
                             class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                             alt="Existing image">

                        {{-- Primary Badge --}}
                        @if($img['is_primary'])
                            <span class="absolute top-1.5 left-1.5 px-2 py-0.5 rounded-md bg-blue-600 text-white text-[10px] font-bold uppercase tracking-wider shadow">
                                Primary
                            </span>
                        @endif

                        {{-- Remove Button --}}
                        <button type="button" wire:click="removeExistingImage({{ $img['id'] }})"
                            class="absolute top-1.5 right-1.5 w-6 h-6 rounded-md bg-black/50 text-white flex items-center justify-center
                                   opacity-0 group-hover:opacity-100 transition-opacity duration-200 hover:bg-red-600">
                            <i class="ri-close-line text-sm"></i>
                        </button>

                        {{-- Overlay --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-200"></div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- New Upload Previews --}}
    @if($productImages && count($productImages) > 0)
        <div>
            <div class="flex items-center justify-between mb-3">
                <h4 class="text-sm font-semibold text-neutral-700">
                    New Uploads
                    <span class="ml-1 text-xs font-normal text-neutral-400">({{ count($productImages) }} files)</span>
                </h4>
                @if(!isset($existingImages) || count($existingImages) === 0)
                    <span class="text-xs text-neutral-400">First image = Primary</span>
                @endif
            </div>

            <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-3">
                @foreach($productImages as $index => $image)
                    <div class="group relative rounded-xl overflow-hidden shadow-sm border border-neutral-200 aspect-square bg-neutral-100"
                         wire:key="img-preview-{{ $index }}">

                        <img src="{{ $image->temporaryUrl() }}"
                             class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                             alt="Product image {{ $index + 1 }}">

                        {{-- Primary Badge --}}
                        @if($index === 0)
                            <span class="absolute top-1.5 left-1.5 px-2 py-0.5 rounded-md bg-blue-600 text-white text-[10px] font-bold uppercase tracking-wider shadow">
                                Primary
                            </span>
                        @endif

                        {{-- Remove Button --}}
                        <button type="button" wire:click="removeImage({{ $index }})"
                            class="absolute top-1.5 right-1.5 w-6 h-6 rounded-md bg-black/50 text-white flex items-center justify-center
                                   opacity-0 group-hover:opacity-100 transition-opacity duration-200 hover:bg-red-600">
                            <i class="ri-close-line text-sm"></i>
                        </button>

                        {{-- Overlay --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-200"></div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>