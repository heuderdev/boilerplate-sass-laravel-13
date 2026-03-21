<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="bg-gray-100 text-gray-900">

    <nav class="bg-white shadow px-6 py-4 flex items-center justify-between">
        <span class="font-bold text-lg">{{ config('app.name') }}</span>
        <div class="flex gap-4 text-sm">
            {{-- <a href="{{ route('dashboard') }}" wire:navigate>Dashboard</a>
            <a href="{{ route('tenant.index') }}" wire:navigate>Tenants</a>
            <a href="{{ route('billing.index') }}" wire:navigate>Billing</a> --}}
            {{-- <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-red-500">Sair</button>
            </form> --}}
        </div>
    </nav>

    <main class="mx-auto max-w-6xl p-6">
        {{ $slot }}
    </main>

    @livewireScripts
</body>

</html>