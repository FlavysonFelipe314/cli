@extends('layouts.app')

@section('title', 'Dashboard Admin - CLIVUS')
@section('page-title', 'Dashboard Admin')

@section('content')
<div class="space-y-6">
    <!-- Estatísticas -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="rounded-xl p-6" style="background-color: rgb(var(--card)); border: 1px solid rgb(var(--border)); box-shadow: var(--shadow);">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm" style="color: rgb(var(--text-secondary));">Total de Usuários</span>
                <svg class="w-8 h-8" style="color: rgb(var(--primary));" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </div>
            <p class="text-3xl font-bold">{{ $stats['total_users'] }}</p>
        </div>
        
        <div class="rounded-xl p-6" style="background-color: rgb(var(--card)); border: 1px solid rgb(var(--border)); box-shadow: var(--shadow);">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm" style="color: rgb(var(--text-secondary));">Assinaturas Ativas</span>
                <svg class="w-8 h-8" style="color: rgb(34, 197, 94);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
            </div>
            <p class="text-3xl font-bold" style="color: rgb(34, 197, 94);">{{ $stats['active_subscriptions'] }}</p>
        </div>
        
        <div class="rounded-xl p-6" style="background-color: rgb(var(--card)); border: 1px solid rgb(var(--border)); box-shadow: var(--shadow);">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm" style="color: rgb(var(--text-secondary));">Total de Planos</span>
                <svg class="w-8 h-8" style="color: rgb(var(--primary));" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
            <p class="text-3xl font-bold">{{ $stats['total_plans'] }}</p>
        </div>
        
        <div class="rounded-xl p-6" style="background-color: rgb(var(--card)); border: 1px solid rgb(var(--border)); box-shadow: var(--shadow);">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm" style="color: rgb(var(--text-secondary));">Receita Mensal</span>
                <svg class="w-8 h-8" style="color: rgb(34, 197, 94);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <p class="text-3xl font-bold" style="color: rgb(34, 197, 94);">R$ {{ number_format($stats['revenue'], 2, ',', '.') }}</p>
        </div>
    </div>

    <!-- Links Rápidos -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="{{ route('admin.plans.index') }}" class="rounded-xl p-6 transition-all hover:scale-105" style="background-color: rgb(var(--card)); border: 1px solid rgb(var(--border)); box-shadow: var(--shadow);">
            <h3 class="text-lg font-semibold mb-2">Gerenciar Planos</h3>
            <p class="text-sm" style="color: rgb(var(--text-secondary));">Criar e editar planos de assinatura</p>
        </a>
        
        <a href="{{ route('admin.modules.index') }}" class="rounded-xl p-6 transition-all hover:scale-105" style="background-color: rgb(var(--card)); border: 1px solid rgb(var(--border)); box-shadow: var(--shadow);">
            <h3 class="text-lg font-semibold mb-2">Gerenciar Módulos</h3>
            <p class="text-sm" style="color: rgb(var(--text-secondary));">Criar e configurar módulos adicionais</p>
        </a>
        
        <a href="{{ route('admin.users.index') }}" class="rounded-xl p-6 transition-all hover:scale-105" style="background-color: rgb(var(--card)); border: 1px solid rgb(var(--border)); box-shadow: var(--shadow);">
            <h3 class="text-lg font-semibold mb-2">Gerenciar Usuários</h3>
            <p class="text-sm" style="color: rgb(var(--text-secondary));">Ver e gerenciar todos os usuários</p>
        </a>
    </div>

    <!-- Usuários Recentes -->
    <div class="rounded-xl p-6" style="background-color: rgb(var(--card)); border: 1px solid rgb(var(--border)); box-shadow: var(--shadow);">
        <h2 class="text-xl font-bold mb-4">Usuários Recentes</h2>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b" style="border-color: rgb(var(--border));">
                        <th class="text-left py-3 px-4 text-sm font-semibold">Nome</th>
                        <th class="text-left py-3 px-4 text-sm font-semibold">Email</th>
                        <th class="text-left py-3 px-4 text-sm font-semibold">Plano</th>
                        <th class="text-left py-3 px-4 text-sm font-semibold">Criado em</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentUsers as $user)
                    <tr class="border-b" style="border-color: rgb(var(--border));">
                        <td class="py-3 px-4">{{ $user->name }}</td>
                        <td class="py-3 px-4">{{ $user->email }}</td>
                        <td class="py-3 px-4">
                            @php
                                $activeSub = $user->subscriptions->where('status', 'active')->where(function($s) {
                                    return !$s->ends_at || $s->ends_at->isFuture();
                                })->first();
                            @endphp
                            @if($activeSub && $activeSub->plan)
                            <span class="px-2 py-1 rounded text-xs" style="background-color: rgba(var(--primary), 0.1); color: rgb(var(--primary));">
                                {{ $activeSub->plan->name }}
                            </span>
                            @else
                            <span class="text-sm" style="color: rgb(var(--text-secondary));">Sem plano</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-sm" style="color: rgb(var(--text-secondary));">{{ $user->created_at->format('d/m/Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

