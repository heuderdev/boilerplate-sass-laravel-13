<!DOCTYPE html>
<html lang="pt-BR" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        <style>

        /* Scrollbar 2px ultra discreta */
        main::-webkit-scrollbar {
            width: 1px !important;
        }

        main::-webkit-scrollbar-track {
            background: transparent;
        }

        main::-webkit-scrollbar-thumb {
            background: rgba(148, 163, 184, 0.4);
            border-radius: 1px;
            border: none;
        }

        main::-webkit-scrollbar-thumb:hover {
            background: rgba(148, 163, 184, 0.6);
        }

        /* Firefox 2px equivalente */
        main {
            scrollbar-width: thin;
            scrollbar-color: rgba(148, 163, 184, 0.4) transparent;
        }
    </style>
    </style>
</head>

<body class="h-screen w-screen overflow-hidden bg-slate-100">
    @include('sweetalert2::index')

    <main class="mx-auto max-w-[85%] h-full overflow-hidden md:max-w-6xl lg:max-w-7xl overflow-y-auto">
        {{ $slot }}
    </main>

    @livewireScripts
</body>

</html>