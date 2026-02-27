<header class="sticky top-0 z-20 bg-white/80 backdrop-blur shadow-sm">
    <div class="flex items-center justify-between h-16 px-6">

        <!-- Left -->
        <div class="flex items-center gap-4">

            <!-- Sidebar toggle -->
            <button
                @click="sidebarOpen = !sidebarOpen"
                class="lg:hidden p-2 rounded-lg hover:bg-slate-100">
                <i class="ri-menu-2-line text-xl text-slate-700"></i>
            </button>

            <h1 class="text-[15px] font-bold text-slate-800 tracking-tight flex items-center gap-2">
                <span class="text-blue-600 bg-blue-50 px-2 py-0.5 rounded text-[10px] uppercase font-black">Admin</span>
                Control Center
            </h1>
        </div>



        <!-- Right -->
        <div class="flex items-center gap-4">

            <!-- Notifications -->
            <button class="relative p-2 rounded-lg hover:bg-slate-100">
                <i class="ri-notification-3-line text-xl text-slate-700"></i>
                <span class="absolute top-2 right-2 h-2 w-2 bg-red-500 rounded-full"></span>
            </button>

            <!-- User -->
            <div x-data="{ open: false }" class="relative">
                <button
                    x-cloak
                    @click="open = !open"
                    class="flex items-center gap-2 px-2 py-1 rounded-full hover:bg-slate-100 transition">

                    <img
                        src="https://ui-avatars.com/api/?name=Admin&background=e2e8f0&color=334155"
                        class="h-8 w-8 rounded-full">

                    <span class="hidden md:block text-sm font-medium text-slate-700">
                        Admin
                    </span>

                    <i class="ri-arrow-down-s-line text-slate-500 text-sm transition-transform duration-200"
                        :class="open ? 'rotate-180 text-slate-700' : ''"></i>
                </button>

                <!-- Dropdown -->
                <div
                    x-show="open"
                    x-cloak
                    @click.outside="open = false"
                    x-transition
                    class="absolute right-0 mt-2 w-56 bg-white/95 backdrop-blur-xl
                           rounded-xl shadow-xl border border-slate-100/80 overflow-hidden">

                    <!-- User summary -->
                    <div class="flex items-center gap-3 px-4 py-3 bg-slate-50/80">
                        <div class="h-9 w-9 rounded-full overflow-hidden border border-slate-200">
                            <img
                                src="https://ui-avatars.com/api/?name=Admin&background=e2e8f0&color=334155"
                                class="h-full w-full object-cover">
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-slate-800 truncate">Admin</p>
                            <p class="text-[11px] text-slate-500 truncate">Administrator</p>
                        </div>
                    </div>

                    <div class="py-1 text-sm">
                        <a class="flex items-center gap-2 px-4 py-2 text-slate-700 hover:bg-slate-50">
                            <i class="ri-user-line text-slate-500 text-base"></i>
                            <span>Profile</span>
                        </a>

                        <a class="flex items-center gap-2 px-4 py-2 text-slate-700 hover:bg-slate-50">
                            <i class="ri-settings-3-line text-slate-500 text-base"></i>
                            <span>Settings</span>
                        </a>
                    </div>

                    <div class="border-t border-slate-100"></div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="flex w-full items-center gap-2 px-4 py-2 text-sm text-rose-600 hover:bg-rose-50">
                            <i class="ri-logout-box-line text-base"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</header>