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

    @livewireStyles
 

</head>

<body>

    <!-- Sidebar -->

    <div class="page">
        <!-- Header -->
        @php
        $resolvedTitle = $title ?? (trim($__env->yieldContent('title')) ?: 'Dashboard');
        @endphp

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

   

    @livewireScripts



</body>

</html>