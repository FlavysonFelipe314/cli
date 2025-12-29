@extends('layouts.app')

@section('title', 'Agenda / Calendário - CLIVUS')
@section('page-title', 'Agenda / Calendário')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h2 class="text-2xl font-bold mb-1">Agenda / Calendário</h2>
        <p class="text-sm" style="color: rgb(var(--text-secondary));">Visão unificada de vencimentos e eventos</p>
    </div>

    <!-- Filtros -->
    <div class="rounded-xl p-6" style="background-color: rgb(var(--card)); border: 1px solid rgb(var(--border)); box-shadow: var(--shadow);">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                </svg>
                Filtros
            </h3>
        </div>
        <form method="GET" action="{{ route('management.calendar.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="start_date" class="block text-sm font-medium mb-2">Data Início</label>
                    <input type="date" id="start_date" name="start_date" value="{{ $startDate }}"
                        class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                        style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                </div>
                <div>
                    <label for="end_date" class="block text-sm font-medium mb-2">Data Fim</label>
                    <input type="date" id="end_date" name="end_date" value="{{ $endDate }}"
                        class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                        style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium mb-2">Tipos de Evento</label>
                <div class="flex flex-wrap gap-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="event_types[]" value="conta_pagar" {{ in_array('conta_pagar', $eventTypes) ? 'checked' : '' }}
                            class="w-4 h-4 rounded border transition-colors"
                            style="border-color: rgb(var(--border));">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span class="text-sm">Conta a Pagar</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="event_types[]" value="conta_receber" {{ in_array('conta_receber', $eventTypes) ? 'checked' : '' }}
                            class="w-4 h-4 rounded border transition-colors"
                            style="border-color: rgb(var(--border));">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-sm">Conta a Receber</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="event_types[]" value="obrigacao_fiscal" {{ in_array('obrigacao_fiscal', $eventTypes) ? 'checked' : '' }}
                            class="w-4 h-4 rounded border transition-colors"
                            style="border-color: rgb(var(--border));">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <span class="text-sm">Obrigação Fiscal</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="event_types[]" value="tarefa" {{ in_array('tarefa', $eventTypes) ? 'checked' : '' }}
                            class="w-4 h-4 rounded border transition-colors"
                            style="border-color: rgb(var(--border));">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <span class="text-sm">Tarefa</span>
                    </label>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium mb-2">Escopo</label>
                <div class="flex gap-2">
                    <button type="submit" name="scope" value="Todos" class="px-4 py-2 rounded-lg font-medium transition-all {{ $scope === 'Todos' ? 'text-white' : '' }}" 
                        style="{{ $scope === 'Todos' ? 'background: linear-gradient(135deg, rgb(var(--primary)), rgb(var(--primary-dark)));' : 'background-color: rgba(var(--primary), 0.1); color: rgb(var(--primary));' }}">
                        Todos
                    </button>
                    <button type="submit" name="scope" value="PF" class="px-4 py-2 rounded-lg font-medium transition-all {{ $scope === 'PF' ? 'text-white' : '' }}" 
                        style="{{ $scope === 'PF' ? 'background: linear-gradient(135deg, rgb(var(--primary)), rgb(var(--primary-dark)));' : 'background-color: rgba(var(--primary), 0.1); color: rgb(var(--primary));' }}">
                        PF
                    </button>
                    <button type="submit" name="scope" value="PJ" class="px-4 py-2 rounded-lg font-medium transition-all {{ $scope === 'PJ' ? 'text-white' : '' }}" 
                        style="{{ $scope === 'PJ' ? 'background: linear-gradient(135deg, rgb(var(--primary)), rgb(var(--primary-dark)));' : 'background-color: rgba(var(--primary), 0.1); color: rgb(var(--primary));' }}">
                        PJ
                    </button>
                </div>
            </div>
            <button type="submit" class="w-full sm:w-auto px-6 py-2 rounded-lg font-medium text-white transition-all" style="background: linear-gradient(135deg, rgb(var(--primary)), rgb(var(--primary-dark)));">
                Aplicar Filtros
            </button>
        </form>
    </div>

    <!-- Eventos -->
    <div class="rounded-xl p-6" style="background-color: rgb(var(--card)); border: 1px solid rgb(var(--border)); box-shadow: var(--shadow);">
        <h3 class="font-semibold mb-4">Eventos</h3>
        <p class="text-sm mb-4" style="color: rgb(var(--text-secondary));">{{ count($events) }} eventos encontrados</p>
        
        @if(count($events) > 0)
        <div class="space-y-3">
            @foreach($events as $event)
            <div class="rounded-lg p-4 flex items-start gap-4" style="background-color: rgba(var(--primary), 0.05); border-left: 4px solid {{ $event['color'] ?? 'rgb(var(--primary))' }};">
                <div class="flex-1">
                    <h4 class="font-semibold mb-1">{{ $event['title'] }}</h4>
                    <p class="text-sm mb-2" style="color: rgb(var(--text-secondary));">{{ $event['description'] }}</p>
                    <div class="flex items-center gap-3 text-xs" style="color: rgb(var(--text-secondary));">
                        <span class="inline-flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            {{ is_string($event['event_date']) ? \Carbon\Carbon::parse($event['event_date'])->format('d/m/Y') : $event['event_date']->format('d/m/Y') }}
                        </span>
                        <span class="px-2 py-1 rounded text-xs font-medium" style="background-color: rgba(var(--primary), 0.1); color: rgb(var(--primary));">
                            {{ ucfirst(str_replace('_', ' ', $event['event_type'])) }}
                        </span>
                        @if($event['scope'] !== 'Todos')
                        <span class="px-2 py-1 rounded text-xs font-medium" style="background-color: rgba(var(--primary), 0.1); color: rgb(var(--primary));">
                            {{ $event['scope'] }}
                        </span>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-12">
            <svg class="w-16 h-16 mx-auto mb-4 opacity-50" style="color: rgb(var(--text-secondary));" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <h3 class="text-lg font-semibold mb-2">Nenhum evento encontrado no período selecionado</h3>
            <p class="text-sm" style="color: rgb(var(--text-secondary));">Ajuste os filtros para ver mais eventos</p>
        </div>
        @endif
    </div>
</div>
@endsection

