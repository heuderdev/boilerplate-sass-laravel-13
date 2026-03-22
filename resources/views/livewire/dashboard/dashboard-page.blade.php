<div class="min-h-screen bg-slate-100 py-10">
    <div class="max-w-5xl mx-auto px-4">
        {{-- Cabeçalho --}}
        <header class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <p class="text-sm font-medium text-slate-500 uppercase tracking-wide">
                        Painel da Organização
                    </p>
                    <h1 class="mt-1 text-2xl font-semibold text-slate-900">
                        {{ $tenantName }}
                    </h1>
                    <p class="mt-1 text-sm text-slate-500">
                        {{ $tenantSlug }}
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <span @class([ 'inline-flex items-center rounded-full px-3 py-1 text-xs font-medium' , $isSubscribed
                        ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-100'
                        : 'bg-rose-50 text-rose-700 ring-1 ring-rose-100' , ])>
                        <span class="inline-flex h-1.5 w-1.5 rounded-full mr-2" @class([ $isSubscribed
                            ? 'bg-emerald-500' : 'bg-rose-500' , ])>
                        </span>
                        {{ $isSubscribed ? 'Assinatura ativa' : 'Assinatura inativa' }}
                    </span>
                </div>
            </div>
        </header>

        {{-- Linha de cards principais --}}
        {{-- Substitua o card "Membros ativos" pelos 4 cards abaixo --}}
        <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">

            {{-- Total geral --}}
            <article class="bg-white rounded-xl shadow-sm border border-slate-100 p-5 flex flex-col">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-sm font-medium text-slate-500">Total membros</h2>
                    <span
                        class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-slate-50 text-slate-500 text-xs">TM</span>
                </div>
                <p class="text-3xl font-semibold text-slate-900">{{ $totalMembers }}</p>
            </article>

            {{-- Ativos --}}
            <article class="bg-white rounded-xl shadow-sm border border-slate-100 p-5 flex flex-col">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-sm font-medium text-slate-500">Ativos</h2>
                    <span
                        class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-emerald-50 text-emerald-600 text-xs">✓</span>
                </div>
                <p class="text-3xl font-semibold text-slate-900">{{ $activeMembers }}</p>
            </article>

            {{-- Pendentes --}}
            <article class="bg-white rounded-xl shadow-sm border border-slate-100 p-5 flex flex-col">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-sm font-medium text-slate-500">Pendentes</h2>
                    <span
                        class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-amber-50 text-amber-600 text-xs">⏳</span>
                </div>
                <p class="text-3xl font-semibold text-amber-600">{{ $pendingMembers }}</p>
            </article>

            {{-- Inativos --}}
            <article class="bg-white rounded-xl shadow-sm border border-slate-100 p-5 flex flex-col">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-sm font-medium text-slate-500">Inativos</h2>
                    <span
                        class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-rose-50 text-rose-600 text-xs">✗</span>
                </div>
                <p class="text-3xl font-semibold text-rose-600">{{ $inactiveMembers }}</p>
            </article>

            {{-- Demais cards permanecem iguais --}}
        </section>

        {{-- Seção secundária / placeholder para futuras métricas --}}
        <section class="bg-white rounded-xl shadow-sm border border-slate-100 p-5">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-sm font-semibold text-slate-900">
                    Visão geral
                </h2>
                <p class="text-xs text-slate-500">
                    Espaço reservado para métricas adicionais ou atalhos rápidos.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-slate-500">
                <div class="border border-dashed border-slate-200 rounded-lg p-4">
                    Adicione aqui um gráfico simples (ex: evolução de membros).
                </div>
                <div class="border border-dashed border-slate-200 rounded-lg p-4">
                    Atalhos para ações comuns (ex: convidar membro).
                </div>
                <div class="border border-dashed border-slate-200 rounded-lg p-4">
                    Alertas ou mensagens importantes do tenant.
                </div>
            </div>
        </section>

        {{-- TABLE --}}

        {{-- SEÇÃO COMPLETA DA TABELA - Cole após os cards, substitua a anterior --}}
        <section class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 mb-8 mt-4">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-slate-900">
                    Todos os membros ({{ $totalMembers }})
                </h2>

                <div class="flex items-center gap-3">
                    {{-- Filtro por status --}}
                    <select wire:model.live="filterStatus"
                        class="text-sm rounded-lg border border-slate-200 focus:ring-2 focus:ring-slate-300 focus:border-transparent px-3 py-1.5 bg-white">
                        <option value="">Todos os status</option>
                        <option value="ativo">Apenas ativos</option>
                        <option value="pendente">Apenas pendentes</option>
                        <option value="inativo">Apenas inativos</option>
                    </select>

                    {{-- Exportar --}}
                    <button
                        class="text-sm text-slate-500 hover:text-slate-900 font-medium px-3 py-1.5 hover:bg-slate-50 rounded-lg transition-all duration-150">
                        Exportar CSV
                    </button>
                </div>
            </div>
        </section>

        {{-- Tabela --}}
        <div class="overflow-x-auto">
            {{-- TABELA COMPLETA ATUALIZADA COM STATUS TENANT STRIPE --}}
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th
                                class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Membro
                            </th>
                            <th
                                class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Email</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Papel</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th
                                class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Assinatura
                                Tenant</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Criado
                            </th>
                            <th class="w-12 text-right"></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-100">
                        @forelse($members as $member)
                        <tr class="hover:bg-slate-50/50 transition-colors duration-150">
                            {{-- Membro --}}
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 flex-shrink-0 rounded-full text-white text-sm font-semibold flex items-center justify-center shadow-sm
                            @if($member->status === 'ativo') bg-gradient-to-r from-emerald-500 to-emerald-600
                            @elseif($member->status === 'pendente') bg-gradient-to-r from-amber-500 to-amber-600
                            @elseif($member->status === 'inativo') bg-gradient-to-r from-rose-500 to-rose-600
                            @else bg-gradient-to-r from-slate-400 to-slate-500 @endif">
                                        {{ Str::upper(Str::substr($member->name ?? ($member->user?->name ?? '—'), 0, 2))
                                        }}
                                    </div>
                                    <div class="ml-3 min-w-0 flex-1">
                                        <div class="text-sm font-medium text-slate-900 truncate"
                                            title="{{ $member->name ?? ($member->user?->name ?? '—') }}">
                                            {{ $member->name ?? ($member->user?->name ?? '—') }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Email --}}
                            <td class="px-4 py-4 text-sm text-slate-700 max-w-56 truncate"
                                title="{{ $member->user?->email ?? '—' }}">
                                {{ $member->user?->email ?? '—' }}
                            </td>

                            {{-- Papel --}}
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span
                                    class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-700">
                                    {{ ucfirst($member->type ?? '—') }}
                                </span>
                            </td>

                            {{-- Status Perfil --}}
                            <td class="px-4 py-4 whitespace-nowrap">
                                @if($member->status === 'ativo')
                                <span
                                    class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-medium text-emerald-800">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>Ativo
                                </span>
                                @elseif($member->status === 'pendente')
                                <span
                                    class="inline-flex items-center rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-medium text-amber-800">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5"></span>Pendente
                                </span>
                                @elseif($member->status === 'inativo')
                                <span
                                    class="inline-flex items-center rounded-full bg-rose-100 px-2.5 py-0.5 text-xs font-medium text-rose-800">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-1.5"></span>Inativo
                                </span>
                                @else
                                <span
                                    class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-500">—</span>
                                @endif
                            </td>

                            {{-- ASSINATURA TENANT (IGUAL PARA TODOS) --}}
                            <td class="px-4 py-4 whitespace-nowrap">
                                @php
                                $subs = $tenant->subscriptions;
                                $hasActive = $tenant->subscribed() || $tenant->onTrial();
                                @endphp

                                @if($hasActive)
                                {{-- Assinatura ativa --}}
                                <span
                                    class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-medium text-emerald-800">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
                                    Ativa{{ $tenant->onTrial() ? ' (Trial)' : '' }}
                                </span>
                                @elseif($subs->count() > 0)
                                {{-- Inativa mas existe --}}
                                <span
                                    class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-700">
                                    {{ ucfirst($subs->first()?->stripe_status ?? 'Inativa') }}
                                </span>
                                @else
                                {{-- SEM ASSINATURA → BOTÃO --}}
                                <button wire:click="startSubscription"
                                    class="inline-flex items-center rounded-full bg-gradient-to-r from-emerald-500 to-emerald-600 px-3 py-1.5 text-xs font-semibold text-white shadow-sm hover:shadow-md hover:from-emerald-600 hover:to-emerald-700 transition-all duration-200 transform hover:-translate-y-0.5"
                                    title="Iniciar assinatura Stripe">
                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M5 12h14M12 5l7 7-7 7" />
                                    </svg>
                                    Assinar agora
                                </button>
                                @endif
                            </td>

                            {{-- Criado --}}
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-slate-500">
                                {{ $member->created_at?->format('d/m/Y') ?? '—' }}
                            </td>

                            {{-- Ações --}}
                            <td class="px-4 py-4 whitespace-nowrap text-right text-sm">
                                <button
                                    class="text-slate-400 hover:text-slate-900 p-1.5 -m-1.5 rounded-lg hover:bg-slate-100 transition-all duration-150 group">
                                    <span class="sr-only">Ações</span>
                                    <svg class="w-4 h-4 group-hover:rotate-90 transition-transform" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-12 py-16 text-center">
                                <div class="flex flex-col items-center space-y-3">
                                    <div
                                        class="w-20 h-20 bg-gradient-to-br from-slate-100 to-slate-200 rounded-3xl flex items-center justify-center">
                                        <svg class="w-12 h-12 text-slate-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-slate-900 mb-1">Nenhum membro encontrado
                                        </h3>
                                        <p class="text-sm text-slate-500">{{ $filterStatus ? 'Nenhum membro com status
                                            "' .
                                            ucfirst($filterStatus) . '"' : 'Convide os primeiros membros.' }}</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- TABLE --}}

    </div>
</div>