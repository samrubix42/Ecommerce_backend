<aside class="navbar navbar-vertical navbar-expand-lg navbar-dark" data-bs-theme="dark" x-data="{ openMenu: null, mobileMenu: false }">
    <div class="container-fluid">

        {{-- Mobile toggle button --}}
        <button class="navbar-toggler" type="button" @click="mobileMenu = !mobileMenu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <a href="{{ route('admin.dashboard') }}"
            class="d-flex align-items-center justify-content-center gap-2 text-decoration-none">
            <a href="{{ route('admin.dashboard') }}"
                class="d-flex align-items-center justify-content-center gap-2 text-decoration-none">

                <span class="fw-bold fs-4 text-white mt-6" style="font-weight: bold; font-size: 1.25rem;">
                    Technonika Admin
                </span>
            </a>

        </a>

        {{-- Sidebar menu --}}
        <div class="collapse navbar-collapse" :class="{ 'show': mobileMenu }">
            <ul class="navbar-nav pt-lg-2">

                @foreach (\App\View\Builders\AdminSidebar::menu(user: Auth::user())->get() as $menu)
                <li class="nav-item mt-1" x-data="{ open: {{ request()->is($menu->url . '*') ? 'true' : 'false' }} }">

                    @if($menu->hasSubmenu)
                    {{-- Parent Menu --}}
                    <a href="javascript:void(0)"
                        class="nav-link dropdown-toggle {{ request()->is($menu->url . '*') ? 'active' : '' }}"
                        @click="open = !open">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <i class="{{ $menu->icon ?? '' }} h2"></i>
                        </span>
                        <span class="nav-link-title">{{ $menu->title }}</span>
                    </a>

                    {{-- Submenu --}}
                    <ul class="nav-dropdown-items pl-3"
                        x-show="open" x-transition
                        style="display: none;">
                        @foreach($menu->submenu as $submenu)
                        <li class="nav-item">
                            <a href="{{ $submenu->url }}"

                                class="nav-link {{ request()->is($submenu->url . '*') ? 'active' : '' }}">
                                <span class="nav-link-title">{{ $submenu->title }}</span>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                    @else
                    {{-- Single Menu Item --}}
                    <a  href="{{ $menu->url }}" class="nav-link {{ request()->is($menu->url . '*') ? 'active' : '' }}" >
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <i class="{{ $menu->icon ?? '' }} h2"></i>
                        </span>
                        <span class="nav-link-title">{{ $menu->title }}</span>
                    </a>
                    @endif
                </li>
                @endforeach

            </ul>
        </div>
    </div>
</aside>