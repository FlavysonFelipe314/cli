@extends('layouts.app')

@section('title', 'Compliance Fiscal - CLIVUS')
@section('page-title', 'Compliance Fiscal')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold mb-1">Compliance Fiscal</h2>
            <p class="text-sm" style="color: rgb(var(--text-secondary));">Gerencie suas obrigações tributárias</p>
        </div>
        <div class="flex gap-3">
            <select id="filterStatus" onchange="filterObligations()"
                class="px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                <option value="">Todos</option>
                <option value="pending">Pendentes</option>
                <option value="completed">Concluídas</option>
                <option value="overdue">Atrasadas</option>
            </select>
            <button type="button" onclick="event.preventDefault(); openObligationModal();" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-medium text-white transition-all hover:scale-105" style="background: linear-gradient(135deg, rgb(var(--primary)), rgb(var(--primary-dark))); box-shadow: 0 4px 6px -1px rgba(var(--primary), 0.3);">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>Nova Obrigação</span>
            </button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-5 gap-4">
        <div class="rounded-xl p-6" style="background-color: rgb(var(--card)); border: 1px solid rgb(var(--border)); box-shadow: var(--shadow);">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium" style="color: rgb(var(--text-secondary));">Total</span>
                <svg class="w-5 h-5" style="color: rgb(var(--primary));" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            <p class="text-2xl font-bold">{{ $summary['total'] }}</p>
            <p class="text-xs mt-1" style="color: rgb(var(--text-secondary));">Agendamentos</p>
        </div>
        
        <div class="rounded-xl p-6" style="background: linear-gradient(135deg, rgba(251, 146, 60, 0.1), rgba(234, 88, 12, 0.1)); border: 1px solid rgb(251, 146, 60);">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium" style="color: rgb(234, 88, 12);">Próximos 7 dias</span>
                <svg class="w-5 h-5" style="color: rgb(251, 146, 60);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <p class="text-2xl font-bold" style="color: rgb(234, 88, 12);">{{ $summary['next_7_days'] }}</p>
            <p class="text-xs mt-1" style="color: rgb(var(--text-secondary));">Vencendo</p>
        </div>
        
        <div class="rounded-xl p-6" style="background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(37, 99, 235, 0.1)); border: 1px solid rgb(59, 130, 246);">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium" style="color: rgb(37, 99, 235);">Pendentes</span>
                <svg class="w-5 h-5" style="color: rgb(59, 130, 246);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <p class="text-2xl font-bold" style="color: rgb(37, 99, 235);">{{ $summary['pending'] }}</p>
            <p class="text-xs mt-1" style="color: rgb(var(--text-secondary));">Para fazer</p>
        </div>
        
        <div class="rounded-xl p-6" style="background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), rgba(220, 38, 38, 0.1)); border: 1px solid rgb(239, 68, 68);">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium" style="color: rgb(220, 38, 38);">Atrasadas</span>
                <svg class="w-5 h-5" style="color: rgb(239, 68, 68);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <p class="text-2xl font-bold" style="color: rgb(220, 38, 38);">{{ $summary['overdue'] }}</p>
            <p class="text-xs mt-1" style="color: rgb(var(--text-secondary));">Atenção</p>
        </div>
        
        <div class="rounded-xl p-6" style="background: linear-gradient(135deg, rgba(34, 197, 94, 0.1), rgba(22, 163, 74, 0.1)); border: 1px solid rgb(34, 197, 94);">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium" style="color: rgb(22, 163, 74);">Concluídas</span>
                <svg class="w-5 h-5" style="color: rgb(34, 197, 94);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <p class="text-2xl font-bold" style="color: rgb(22, 163, 74);">{{ $summary['completed'] }}</p>
            <p class="text-xs mt-1" style="color: rgb(var(--text-secondary));">Entregues</p>
        </div>
    </div>

    <!-- Obrigações Fiscais -->
    <div class="rounded-xl p-6" style="background-color: rgb(var(--card)); border: 1px solid rgb(var(--border)); box-shadow: var(--shadow);">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold">Obrigações Fiscais</h3>
        </div>

        @if($obligations->count() > 0)
        <div class="space-y-3" id="obligations-list">
            @foreach($obligations as $obligation)
            <div class="obligation-item rounded-lg p-4 transition-all hover:scale-[1.01]" 
                data-status="{{ $obligation->status }}"
                style="background-color: rgba(var(--primary), 0.05); border: 1px solid rgb(var(--border));">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex-1">
                        <h4 class="font-semibold mb-2">{{ $obligation->name }}</h4>
                        <div class="flex flex-wrap items-center gap-3 text-sm" style="color: rgb(var(--text-secondary));">
                            <span class="px-2 py-1 rounded text-xs font-medium" style="background-color: rgba(var(--primary), 0.1); color: rgb(var(--primary));">
                                {{ ucfirst($obligation->periodicity) }}
                            </span>
                            <span class="px-2 py-1 rounded text-xs font-medium" style="background-color: rgba(var(--primary), 0.1); color: rgb(var(--primary));">
                                {{ $obligation->scope }}
                            </span>
                            <span class="inline-flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Vence dia {{ $obligation->due_day }}
                            </span>
                            @if($obligation->next_due_date)
                            <span class="inline-flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Próximo: {{ $obligation->next_due_date->format('d/m/Y') }}
                            </span>
                            @endif
                            @if($obligation->responsible)
                            <span>{{ $obligation->responsible }}</span>
                            @endif
                        </div>
                        @if($obligation->description)
                        <p class="text-sm mt-2" style="color: rgb(var(--text-secondary));">{{ $obligation->description }}</p>
                        @endif
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="px-3 py-1 rounded text-sm font-medium 
                            @if($obligation->status === 'completed') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                            @elseif($obligation->status === 'overdue') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                            @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                            @endif">
                            @if($obligation->status === 'completed') Concluída
                            @elseif($obligation->status === 'overdue') Atrasada
                            @else Pendente
                            @endif
                        </span>
                        <div class="flex gap-2">
                            <button type="button" onclick="openObligationModal({{ $obligation->id }})" class="p-2 rounded-lg hover:bg-opacity-50 transition-colors" style="background-color: rgba(var(--primary), 0.1);">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </button>
                            <form action="{{ route('tools.compliance.destroy', $obligation) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja remover esta obrigação?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 rounded-lg hover:bg-opacity-50 transition-colors" style="background-color: rgba(239, 68, 68, 0.1); color: rgb(239, 68, 68);">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
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
            <h3 class="text-lg font-semibold mb-2">Nenhuma obrigação cadastrada</h3>
            <p class="text-sm mb-4" style="color: rgb(var(--text-secondary));">Comece criando sua primeira obrigação fiscal</p>
            <button type="button" onclick="event.preventDefault(); openObligationModal();" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-medium text-white transition-all" style="background: linear-gradient(135deg, rgb(var(--primary)), rgb(var(--primary-dark)));">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>Criar Primeira Obrigação</span>
            </button>
        </div>
        @endif
    </div>
</div>

<!-- Modal de Obrigação Fiscal -->
<div id="obligationModal" class="fixed inset-0 z-50 hidden overflow-y-auto" style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity backdrop-blur-md" style="background: rgba(0, 0, 0, 0.4);" onclick="closeObligationModal()"></div>

        <div class="relative inline-block w-full max-w-2xl p-6 my-8 text-left align-middle transition-all transform rounded-xl shadow-xl z-50" style="background-color: #ffffff; border: 1px solid rgb(var(--border)); box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold" id="obligationModalTitle">Nova Obrigação Fiscal</h3>
                <button type="button" onclick="closeObligationModal()" class="p-2 rounded-lg hover:bg-opacity-50 transition-colors" style="background-color: rgba(239, 68, 68, 0.1); color: rgb(239, 68, 68);">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form id="obligationForm" method="POST" class="space-y-6">
                @csrf
                <div id="obligationFormMethod"></div>
                
                @if($errors->any())
                <div class="p-4 rounded-lg mb-4" style="background-color: rgba(239, 68, 68, 0.1); border: 1px solid rgb(239, 68, 68);">
                    <ul class="list-disc list-inside text-sm" style="color: rgb(220, 38, 38);">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                
                <div class="space-y-6">
                    <div>
                        <label for="obligation_name" class="block text-sm font-medium mb-2">Nome da Obrigação *</label>
                        <input type="text" id="obligation_name" name="name" required
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                            placeholder="Ex: DARF Mensal">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="obligation_periodicity" class="block text-sm font-medium mb-2">Periodicidade *</label>
                            <select id="obligation_periodicity" name="periodicity" required
                                class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                                style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                                <option value="daily">Diária</option>
                                <option value="weekly">Semanal</option>
                                <option value="monthly" selected>Mensal</option>
                                <option value="quarterly">Trimestral</option>
                                <option value="yearly">Anual</option>
                            </select>
                        </div>

                        <div>
                            <label for="obligation_scope" class="block text-sm font-medium mb-2">Escopo *</label>
                            <select id="obligation_scope" name="scope" required
                                class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                                style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                                <option value="PF" selected>PF</option>
                                <option value="PJ">PJ</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="obligation_due_day" class="block text-sm font-medium mb-2">Dia do Vencimento *</label>
                        <input type="number" id="obligation_due_day" name="due_day" min="1" max="31" value="15" required
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                        <p class="text-xs mt-1" style="color: rgb(var(--text-secondary));">Dia do mês em que a obrigação vence (1-31)</p>
                    </div>

                    <div>
                        <label for="obligation_responsible" class="block text-sm font-medium mb-2">Responsável</label>
                        <input type="text" id="obligation_responsible" name="responsible"
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                            placeholder="Nome do responsável (opcional)">
                    </div>

                    <div>
                        <label for="obligation_description" class="block text-sm font-medium mb-2">Descrição</label>
                        <textarea id="obligation_description" name="description" rows="3"
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                            placeholder="Detalhes adicionais (opcional)"></textarea>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t" style="border-color: rgb(var(--border));">
                    <button type="submit" class="flex-1 px-6 py-3 rounded-lg font-medium text-white transition-all hover:scale-105" style="background: linear-gradient(135deg, rgb(var(--primary)), rgb(var(--primary-dark))); box-shadow: 0 4px 6px -1px rgba(var(--primary), 0.3);">
                        <span id="obligationSubmitText">Criar Obrigação</span>
                    </button>
                    <button type="button" onclick="closeObligationModal()" class="flex-1 px-6 py-3 rounded-lg font-medium text-center transition-colors" style="background-color: rgba(var(--primary), 0.1); color: rgb(var(--primary));">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const obligationsData = @json($obligations->count() > 0 ? $obligations->keyBy('id') : []);

function filterObligations() {
    const status = document.getElementById('filterStatus').value;
    const items = document.querySelectorAll('.obligation-item');
    
    items.forEach(item => {
        const itemStatus = item.getAttribute('data-status');
        if (!status || itemStatus === status) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
}

function openObligationModal(obligationId = null) {
    const modal = document.getElementById('obligationModal');
    if (!modal) return;
    
    const form = document.getElementById('obligationForm');
    const formMethod = document.getElementById('obligationFormMethod');
    const title = document.getElementById('obligationModalTitle');
    const submitText = document.getElementById('obligationSubmitText');
    
    if (obligationId && obligationsData[obligationId]) {
        const obligation = obligationsData[obligationId];
        title.textContent = 'Editar Obrigação Fiscal';
        submitText.textContent = 'Salvar Alterações';
        form.action = '{{ route("tools.compliance.update", ":id") }}'.replace(':id', obligationId);
        formMethod.innerHTML = '@method("PUT")';
        
        document.getElementById('obligation_name').value = obligation.name || '';
        document.getElementById('obligation_periodicity').value = obligation.periodicity || 'monthly';
        document.getElementById('obligation_scope').value = obligation.scope || 'PF';
        document.getElementById('obligation_due_day').value = obligation.due_day || 15;
        document.getElementById('obligation_responsible').value = obligation.responsible || '';
        document.getElementById('obligation_description').value = obligation.description || '';
    } else {
        title.textContent = 'Nova Obrigação Fiscal';
        submitText.textContent = 'Criar Obrigação';
        form.action = '{{ route("tools.compliance.store") }}';
        formMethod.innerHTML = '';
        form.reset();
        document.getElementById('obligation_periodicity').value = 'monthly';
        document.getElementById('obligation_scope').value = 'PF';
        document.getElementById('obligation_due_day').value = 15;
    }
    
    modal.style.display = 'block';
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeObligationModal() {
    const modal = document.getElementById('obligationModal');
    if (modal) {
        modal.style.display = 'none';
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeObligationModal();
    }
});
</script>
@endsection

