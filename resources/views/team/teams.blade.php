@extends('layouts.app')

@section('title', 'Minhas Equipes - CLIVUS')
@section('page-title', 'Minhas Equipes')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h2 class="text-2xl font-bold mb-1">Minhas Equipes</h2>
        <p class="text-sm" style="color: rgb(var(--text-secondary));">Gerencie as equipes das quais você faz parte</p>
    </div>

    @if(!Auth::user()->hasActiveSubscription())
    <!-- Aviso sobre plano -->
    <div class="rounded-xl p-6" style="background: linear-gradient(135deg, rgba(251, 191, 36, 0.1), rgba(234, 179, 8, 0.1)); border: 1px solid rgb(251, 191, 36);">
        <div class="flex items-start gap-4">
            <svg class="w-6 h-6 flex-shrink-0" style="color: rgb(234, 179, 8);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            <div class="flex-1">
                <h3 class="font-semibold mb-2" style="color: rgb(234, 179, 8);">Você não possui um plano ativo</h3>
                <p class="text-sm mb-4" style="color: rgb(var(--text-secondary));">
                    Você pode visualizar as equipes das quais faz parte, mas para criar e gerenciar suas próprias equipes, é necessário ter um plano ativo.
                </p>
                <a href="{{ route('subscriptions.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-medium text-white transition-all" style="background: linear-gradient(135deg, rgb(var(--primary)), rgb(var(--primary-dark)));">
                    <span>Ver Planos Disponíveis</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </div>
    </div>
    @endif

    <!-- Convites Pendentes -->
    @if($pendingInvitations->count() > 0)
    <div class="rounded-xl p-6" style="background-color: rgb(var(--card)); border: 1px solid rgb(var(--border)); box-shadow: var(--shadow);">
        <h3 class="font-semibold mb-4 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
            </svg>
            Convites Pendentes
        </h3>
        <div class="space-y-3">
            @foreach($pendingInvitations as $invitation)
            <div class="rounded-lg p-4" style="background-color: rgba(var(--primary), 0.05); border: 1px solid rgb(var(--border));">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex-1">
                        <h4 class="font-semibold mb-1">Convite de {{ $invitation->owner->name }}</h4>
                        @if($invitation->teamMember)
                        <p class="text-sm mb-2" style="color: rgb(var(--text-secondary));">
                            <strong>Cargo:</strong> {{ $invitation->teamMember->position ?? 'Não informado' }} | 
                            <strong>Tipo:</strong> {{ $invitation->teamMember->employment_type }}
                        </p>
                        @endif
                        <p class="text-xs" style="color: rgb(var(--text-secondary));">
                            Expira em: {{ $invitation->expires_at->diffForHumans() }}
                        </p>
                    </div>
                    <div class="flex gap-2">
                        <form action="{{ route('team.invitation.accept', $invitation->token) }}" method="GET" class="inline">
                            <button type="submit" class="px-4 py-2 rounded-lg font-medium text-white transition-all" style="background: linear-gradient(135deg, rgb(var(--primary)), rgb(var(--primary-dark)));">
                                Aceitar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Minhas Equipes -->
    <div class="rounded-xl p-6" style="background-color: rgb(var(--card)); border: 1px solid rgb(var(--border)); box-shadow: var(--shadow);">
        <h3 class="font-semibold mb-4 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            Equipes das quais você faz parte
        </h3>
        
        @if($myTeams->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($myTeams as $team)
            <a href="{{ route('team.tasks', $team->id) }}" class="rounded-lg p-4 cursor-pointer transition-all hover:scale-[1.02] hover:shadow-lg block" 
                style="background-color: rgba(var(--primary), 0.05); border: 1px solid rgb(var(--border));">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex-1">
                        <h4 class="font-semibold mb-1">{{ $team->name ?? 'Equipe' }}</h4>
                        <p class="text-sm" style="color: rgb(var(--text-secondary));">
                            Proprietário: {{ $team->owner->name }}
                        </p>
                        @if($team->position)
                        <p class="text-sm mt-1" style="color: rgb(var(--text-secondary));">
                            Seu cargo: {{ $team->position }}
                        </p>
                        @endif
                    </div>
                    @if($team->pivot)
                    <span class="px-2 py-1 rounded text-xs font-medium" style="background-color: rgba(var(--primary), 0.1); color: rgb(var(--primary));">
                        {{ ucfirst($team->pivot->role ?? 'member') }}
                    </span>
                    @endif
                </div>
                <div class="flex items-center gap-2 text-xs mb-3" style="color: rgb(var(--text-secondary));">
                    <span class="px-2 py-1 rounded" style="background-color: rgba(var(--primary), 0.1); color: rgb(var(--primary));">
                        {{ $team->employment_type }}
                    </span>
                    @if($team->status === 'active')
                    <span class="px-2 py-1 rounded bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                        Ativo
                    </span>
                    @endif
                </div>
                <div class="flex items-center gap-2 text-sm font-medium" style="color: rgb(var(--primary));">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <span>Ver Tarefas →</span>
                </div>
                @if($team->user_id === Auth::id())
                <div class="mt-3 pt-3 border-t" style="border-color: rgb(var(--border));">
                    <a href="{{ route('management.team.index') }}" class="text-sm font-medium" style="color: rgb(var(--primary));" onclick="event.preventDefault(); event.stopPropagation(); window.location.href='{{ route('management.team.index') }}';">
                        Gerenciar Equipe →
                    </a>
                </div>
                @endif
            </a>
            @endforeach
        </div>
        @else
        <div class="text-center py-12">
            <svg class="w-16 h-16 mx-auto mb-4 opacity-50" style="color: rgb(var(--text-secondary));" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            <h3 class="text-lg font-semibold mb-2">Você ainda não faz parte de nenhuma equipe</h3>
            <p class="text-sm mb-4" style="color: rgb(var(--text-secondary));">
                Quando você receber um convite, ele aparecerá aqui.
            </p>
        </div>
        @endif
    </div>
</div>
@endsection

