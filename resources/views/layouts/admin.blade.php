<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title> {{ config('settings.brand_name', 'Panel') }} | {{ $title ?? (trim($__env->yieldContent('title')) ?: 'Page Title') }} </title>
      

    <!-- Tabler CSS -->  
    <link href="{{ asset('tabler/theme/css/tabler.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('tabler/theme/css/tabler-flags.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('tabler/theme/css/tabler-payments.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('tabler/theme/css/tabler-vendors.min.css') }}" rel="stylesheet" />

    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css" />

    <!-- Custom Styles -->
    <link rel="stylesheet" href="{{ asset('tabler/theme/style.css') }}">
    <link rel="stylesheet" href="{{ asset('tabler/theme/loaders.css') }}">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('tabler/theme/img/') }}" type="image/x-icon">

    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    @livewireStyles
    @stack('styles')
    <style>
        /* Tabler Styled SweetAlert2 Toast */
        .swal2-container .swal2-toast {
            border-radius: 10px !important;
            padding: 12px 16px !important;
            font-size: 15px !important;
            font-weight: 500;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1) !important;
            border-left: 5px solid transparent !important;
        }

        /* Success */
        .swal2-icon-success {
            border-color: #2f9e44 !important;
            color: #2f9e44 !important;
        }

        .swal2-toast.swal2-success {
            border-left-color: #2f9e44 !important;
        }

        /* Error */
        .swal2-icon-error {
            border-color: #e03131 !important;
            color: #e03131 !important;
        }

        .swal2-toast.swal2-error {
            border-left-color: #e03131 !important;
        }

        /* Warning */
        .swal2-icon-warning {
            border-color: #f08c00 !important;
            color: #f08c00 !important;
        }

        .swal2-toast.swal2-warning {
            border-left-color: #f08c00 !important;
        }

        /* Info */
        .swal2-icon-info {
            border-color: #1c7ed6 !important;
            color: #1c7ed6 !important;
        }

        .swal2-toast.swal2-info {
            border-left-color: #1c7ed6 !important;
        }

        /* Title */
        .swal2-title {
            font-weight: 600 !important;
            font-size: 15px !important;
        }
    </style>

</head>

<body class="layout-fluid">

    <!-- Sidebar -->
    <x-admin.sidebar :currentRoute="Route::currentRouteName()" />

    <div class="page">
        <!-- Header -->
        @php
        $resolvedTitle = $title ?? (trim($__env->yieldContent('title')) ?: 'Dashboard');
        @endphp
        <x-admin.header :title="$resolvedTitle" />

        <!-- Page Content -->
        <div class="page-wrapper">
            <div class="page-body">
                <div class="container-xl">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('tabler/theme/js/tabler.min.js') }}"></script>
    <script src="{{ asset('tabler/theme/js/demo-theme.min.js') }}"></script>

    <!-- Tom Select -->
    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Toastr -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    @livewireScripts

    <script>
        function initTabler() {
            document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
                new bootstrap.Tooltip(el);
            });

            document.querySelectorAll('[data-bs-toggle="dropdown"]').forEach(el => {
                new bootstrap.Dropdown(el);
            });
        }

        document.addEventListener("DOMContentLoaded", initTabler);
        document.addEventListener("livewire:navigated", initTabler);

        // ============================
        // DARK MODE HANDLER
        // ============================
        document.addEventListener("DOMContentLoaded", () => {
            const html = document.documentElement;
            const toggle = document.getElementById("darkModeToggle");

            if (localStorage.getItem("theme") === "dark") {
                html.setAttribute("data-theme", "dark");
            }

            if (toggle) {
                toggle.addEventListener("click", () => {
                    if (html.getAttribute("data-theme") === "dark") {
                        html.removeAttribute("data-theme");
                        localStorage.setItem("theme", "light");
                    } else {
                        html.setAttribute("data-theme", "dark");
                        localStorage.setItem("theme", "dark");
                    }
                });
            }
        });

        // ============================
        // MODAL CONTROL EVENTS
        // ============================
        document.addEventListener("open-faq-modal", () => {
            const modalEl = document.getElementById("faqModal");
            bootstrap.Modal.getOrCreateInstance(modalEl).show();
        });

        document.addEventListener("close-faq-modal", () => {
            const modalEl = document.getElementById("faqModal");
            bootstrap.Modal.getOrCreateInstance(modalEl).hide();
        });
    </script>
    <script>
        document.addEventListener("livewire:init", () => {
            Livewire.on('toast', (data) => {
                Swal.fire({
                    toast: true,
                    icon: data.type ?? 'success',
                    title: data.message ?? '',
                    position: 'top-end',
                    timer: 2500,
                    showConfirmButton: false,
                    timerProgressBar: true,
                });
            });
        });
    </script>
    


    @stack('scripts')
</body>

</html>