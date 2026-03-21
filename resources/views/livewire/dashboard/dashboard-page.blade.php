<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
            <p class="text-sm text-gray-500 mt-1">
                Workspace: <span class="font-medium text-indigo-600">{{ $tenantName }}</span>
                <span class="ml-2 text-gray-400">({{ $tenantSlug }})</span>
            </p>
        </div>

        <div class="flex items-center gap-2">
            @if ($isSubscribed)
            <span
                class="inline-flex items-center gap-1 rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                <span class="h-2 w-2 rounded-full bg-green-500"></span>
                Assinatura ativa
            </span>
            @else
            <span
                class="inline-flex items-center gap-1 rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                <span class="h-2 w-2 rounded-full bg-red-400"></span>
                Sem assinatura
            </span>
            @endif
        </div>
    </div>
    <div>
        <livewire:dashboard.logout />
    </div>
    {{-- Cards de métricas --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">

        <div class="rounded-xl bg-white p-5 shadow-sm border border-gray-100">
            <p class="text-xs font-medium uppercase tracking-wide text-gray-400">Membros ativos</p>
            <p class="mt-2 text-3xl font-bold text-gray-800">{{ $totalMembers }}</p>
        </div>

        <div class="rounded-xl bg-white p-5 shadow-sm border border-gray-100">
            <p class="text-xs font-medium uppercase tracking-wide text-gray-400">Workspaces</p>
            <p class="mt-2 text-3xl font-bold text-gray-800">{{ $totalTenants }}</p>
        </div>

        <div class="rounded-xl bg-white p-5 shadow-sm border border-gray-100">
            <p class="text-xs font-medium uppercase tracking-wide text-gray-400">Seu cargo</p>
            <p class="mt-2 text-xl font-bold text-gray-800 capitalize">{{ $memberRole }}</p>
        </div>

        <div class="rounded-xl bg-white p-5 shadow-sm border border-gray-100">
            <p class="text-xs font-medium uppercase tracking-wide text-gray-400">Seu status</p>
            <p class="mt-2 text-xl font-bold text-gray-800 capitalize">{{ $memberStatus }}</p>
        </div>

    </div>

    {{-- Alerta sem assinatura --}}
    @unless ($isSubscribed)
    <div class="rounded-xl border border-yellow-200 bg-yellow-50 p-4 flex items-start gap-3">
        <svg class="h-5 w-5 text-yellow-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
        </svg>
        <div>
            <p class="font-semibold text-yellow-800">Este workspace não possui assinatura ativa.</p>
            <p class="text-sm text-yellow-700 mt-1">
                <a href="#" wire:navigate class="underline hover:text-yellow-900">
                    Clique aqui para assinar um plano.
                </a>
            </p>
        </div>
    </div>
    @endunless

    {{-- Ações rápidas --}}
    <div>
        <h2 class="mb-3 text-sm font-semibold uppercase tracking-wide text-gray-400">Ações rápidas</h2>
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">

            <a href="{{ route('tenant.invites.index') }}" wire:navigate
                class="flex items-center gap-3 rounded-xl bg-white border border-gray-100 p-4 shadow-sm transition hover:shadow-md hover:border-indigo-300">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-700">Convidar membro</span>
            </a>

            <a href="{{ route('tenant.index') }}" wire:navigate
                class="flex items-center gap-3 rounded-xl bg-white border border-gray-100 p-4 shadow-sm transition hover:shadow-md hover:border-indigo-300">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-purple-50 text-purple-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-700">Gerenciar workspaces</span>
            </a>

            <a href="#" wire:navigate
                class="flex items-center gap-3 rounded-xl bg-white border border-gray-100 p-4 shadow-sm transition hover:shadow-md hover:border-indigo-300">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-50 text-green-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-700">Billing & planos</span>
            </a>

        </div>
    </div>

</div>