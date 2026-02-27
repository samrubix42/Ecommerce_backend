<aside
    @php
    use App\View\Builders\AdminSidebar;
    $sidebarItems=AdminSidebar::menu(auth()->user())->get();
    @endphp
    x-cloak
    class="fixed inset-y-0 left-0 z-30 w-64 bg-slate-900 text-slate-300 shadow-2xl
    transform transition-transform duration-300 -translate-x-full lg:translate-x-0 lg:static"
    :class="sidebarOpen ? 'translate-x-0' : ''">

    <!-- Logo Section -->
    <div class="flex items-center h-20 px-6 bg-slate-900 border-b border-white/5">
        <div class="flex items-center gap-3 group cursor-pointer">
            <!-- App Icon/Logo -->
            <div class="h-10 w-10 flex items-center justify-center rounded-xl bg-gradient-to-br from-blue-600 via-blue-500 to-indigo-600 shadow-lg shadow-blue-500/20 group-hover:scale-105 transition-transform duration-300">
                <i class="ri-hexagon-fill text-white text-2xl"></i>
            </div>

            <!-- Brand Name -->
            <div class="flex flex-col">
                <span class="text-xl font-black tracking-tight text-white leading-none">
                    TECHON<span class="text-blue-500">IKA</span>
                </span>
                <span class="text-[10px] font-bold text-slate-500 uppercase tracking-[0.2em] mt-1">
                    Management
                </span>
            </div>
        </div>

        <!-- Mobile close button -->
        <button
            x-cloak
            @click="sidebarOpen = false"
            class="ml-auto inline-flex items-center justify-center rounded-lg p-2 text-slate-300 hover:bg-slate-800 hover:text-white lg:hidden"
            aria-label="Close sidebar"
            type="button">
            <i class="ri-close-line text-xl"></i>
        </button>
    </div>

    <!-- Navigation -->
    <nav class="px-3 py-6 space-y-1" x-cloak x-data="{ openMenu: null }">

        @foreach ($sidebarItems as $item)

        {{-- SINGLE ITEM --}}
        @if (! $item->hasSubmenu)
        <a
            href="{{ $item->url }}"
            wire:navigate
            class="flex items-center gap-3 px-4 py-3 rounded-lg transition
                   {{ request()->url() === $item->url
                       ? 'bg-slate-800 text-white font-semibold shadow-inner'
                       : 'hover:bg-slate-800 hover:text-white' }}">
            <i class="{{ $item->icon }}
                      {{ request()->url() === $item->url
                          ? 'text-blue-400'
                          : 'text-slate-400' }} text-lg"></i>
            <span class="flex-1">{{ $item->title }}</span>
        </a>

        {{-- DROPDOWN --}}
        @else
        <div>
            <button
                x-cloak
                @click="openMenu === '{{ $item->title }}'
                            ? openMenu = null
                            : openMenu = '{{ $item->title }}'"
                class="w-full flex items-center justify-between px-4 py-3 rounded-lg
                       hover:bg-slate-800 transition group">

                <div class="flex items-center gap-3">
                    <i class="{{ $item->icon }} text-slate-400 text-lg group-hover:text-white"></i>
                    <span class="flex-1 group-hover:text-white">{{ $item->title }}</span>
                </div>

                <i class="ri-arrow-down-s-line text-slate-400 text-lg transition-transform duration-200"
                    :class="openMenu === '{{ $item->title }}' ? 'rotate-180 text-blue-400' : ''">
                </i>
            </button>

            <div
                x-show="openMenu === '{{ $item->title }}'"
                x-collapse
                x-cloak
                class="ml-6 mt-1 space-y-1">

                @foreach ($item->submenu as $child)
                <a
                    href="{{ $child->url }}"
                    @if (! in_array($child->title, ['Privacy Policy', 'Terms & Conditions','About Page'])) wire:navigate @endif
                    class="flex items-center px-3 py-2 text-sm rounded-lg transition
                    {{ request()->url() === $child->url
                               ? 'bg-slate-800 text-white font-medium'
                               : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="{{ $child->icon }} mr-2 text-xs text-slate-400 group-hover:text-white"></i>
                    {{ $child->title }}
                </a>
                @endforeach
            </div>
        </div>
        @endif

        @endforeach

    </nav>
</aside>