<div>
<div>
    @php
    $user = auth()->user() ?? (object)[
        'name' => 'Admin User',
        'role' => 'Administrator',
        'avatar' => asset('tabler/theme/img/avatar.jpg') ?? asset('assets/theme/img/avatar.jpg'),
    ];
@endphp

<header class="navbar navbar-expand-md navbar-light d-print-none">
    <div class="container-xl">

 
        {{-- Page title --}}
        <h1 class="navbar-brand navbar-brand-autodark d-none d-md-block pe-0">
            {{ $title ?? 'Dashboard' }}
        </h1>

        {{-- Right side actions --}}
        <div class="navbar-nav flex-row-end ">

            <!-- {{-- Dark mode toggle --}}
            <div class="nav-item d-none d-md-flex me-3">
                <a href="javascript:;" class="nav-link px-0" id="darkModeToggle" title="Toggle dark mode">
                    <i class="ti ti-moon hide-theme-dark"></i>
                    <i class="ti ti-sun hide-theme-light"></i>
                </a>
            </div> -->

            {{-- User dropdown --}}
            <div class="nav-item dropdown">
                <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="avatar avatar-sm rounded" style="background-image: url('{{ $user->avatar }}')"></span>
                    <div class="d-none d-xl-block ps-2">
                        <div class="fw-semibold">{{ $user->name }}</div>
                        <div class="mt-1 small text-muted">{{ $user->role }}</div>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end">
                    <a class="dropdown-item" href="#">
                        <i class="ti ti-user me-2"></i> Profile
                    </a>
                    <div class="dropdown-divider"></div>
                    <form  action="{{route('logout')}}">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            <i class="ti ti-logout me-2"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>                                                                                                             
</header>

</div></div>