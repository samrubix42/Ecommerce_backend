<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name') }}</title>

    @vite(['resources/css/app.css','resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    @livewireStyles
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body>
    <div x-data="{ sidebarOpen:false } " x-cloak class="flex h-screen bg-gray-100">

        <livewire:admin.include.sidebar />

        <div class="flex flex-col flex-1">
            <livewire:admin.include.header />

            <main class="flex-1 p-6 overflow-y-auto">
                {{ $slot }}
            </main>
        </div>

    </div>
    @include('livewire.admin.include.toast')

    <script src="{{ asset('tinymce/tinymce.js') }}"></script>
    <script>
        document.addEventListener('livewire:navigate', function() {
            Livewire.hook('message.processed', function(message, component) {
                if (message.updateQueue.some(update => update.type === 'callMethod' && update.method === 'initializeEditor')) {
                    tinymce.remove(); // Remove any existing editors
                    tinymce.init({
                        selector: '.rich-text-editor',
                        height: 300,
                        menubar: false,
                        plugins: 'lists link image preview',
                        toolbar: 'undo redo | formatselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | preview',
                        content_style: 'body { font-family:Raleway, sans-serif; font-size:14px }'
                    });
                }
            });
        });
    </script>
    @livewireScripts
</body>

</html>