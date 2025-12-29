@extends('layouts.app')

@section('title', 'Planejamento Financeiro - CLIVUS')
@section('page-title', 'Planejamento Financeiro')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold mb-1">Planejamento Financeiro</h2>
            <p class="text-sm" style="color: rgb(var(--text-secondary));">Gerencie suas metas e orçamentos</p>
        </div>
        <button type="button" onclick="event.preventDefault(); openGoalModal();" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-medium text-white transition-all hover:scale-105" style="background: linear-gradient(135deg, rgb(var(--primary)), rgb(var(--primary-dark))); box-shadow: 0 4px 6px -1px rgba(var(--primary), 0.3);">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span>Nova Meta</span>
        </button>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="rounded-xl p-6" style="background-color: rgb(var(--card)); border: 1px solid rgb(var(--border)); box-shadow: var(--shadow);">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium" style="color: rgb(var(--text-secondary));">Metas Ativas</span>
                <svg class="w-5 h-5" style="color: rgb(var(--primary));" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
            <p class="text-2xl font-bold">{{ $activeGoals->count() }}</p>
            <p class="text-sm mt-1" style="color: rgb(var(--text-secondary));">{{ number_format($totalRevenue, 2, ',', '.') }} de receita, {{ number_format($totalProfit, 2, ',', '.') }} de lucro</p>
        </div>
        
        <div class="rounded-xl p-6" style="background-color: rgb(var(--card)); border: 1px solid rgb(var(--border)); box-shadow: var(--shadow);">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium" style="color: rgb(var(--text-secondary));">Progresso Médio</span>
                <svg class="w-5 h-5" style="color: rgb(var(--primary));" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
            <p class="text-2xl font-bold">{{ number_format($averageProgress, 1) }}%</p>
            <p class="text-sm mt-1" style="color: rgb(var(--text-secondary));">Das metas ativas</p>
        </div>
        
        <div class="rounded-xl p-6" style="background-color: rgb(var(--card)); border: 1px solid rgb(var(--border)); box-shadow: var(--shadow);">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium" style="color: rgb(var(--text-secondary));">Orçamentos</span>
                <svg class="w-5 h-5" style="color: rgb(var(--primary));" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <p class="text-2xl font-bold">{{ $budgetsCount }}</p>
            <p class="text-sm mt-1" style="color: rgb(var(--text-secondary));">Cadastrados</p>
        </div>
    </div>

    <!-- Tabs -->
    <div class="flex gap-2 border-b" style="border-color: rgb(var(--border));">
        <button id="goalsTab" onclick="switchTab('goals')" class="px-4 py-2 rounded-t-lg font-medium transition-colors" style="background-color: rgb(var(--primary)); color: white;">
            Metas
        </button>
        <button id="budgetsTab" onclick="switchTab('budgets')" class="px-4 py-2 rounded-t-lg font-medium transition-colors" style="background-color: transparent; color: rgb(var(--text-secondary));">
            Orçamentos
        </button>
    </div>

    <!-- Goals Section -->
    <div id="goalsSection">
        <h3 class="text-xl font-bold mb-4">Metas Financeiras</h3>
        @if($goals->count() > 0)
        <div class="space-y-3">
            @foreach($goals as $goal)
            <div class="rounded-xl p-4 transition-all hover:scale-[1.01]" style="background-color: rgb(var(--card)); border: 1px solid rgb(var(--border)); box-shadow: var(--shadow);">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex-1">
                        <h4 class="font-semibold mb-2">{{ $goal->name }}</h4>
                        <div class="flex flex-wrap items-center gap-3 text-sm" style="color: rgb(var(--text-secondary));">
                            <span class="px-2 py-1 rounded text-xs font-medium" style="background-color: rgba(var(--primary), 0.1); color: rgb(var(--primary));">
                                {{ $goal->type }}
                            </span>
                            <span class="px-2 py-1 rounded text-xs font-medium" style="background-color: rgba(var(--primary), 0.1); color: rgb(var(--primary));">
                                {{ $goal->scope }}
                            </span>
                            <span>Meta: R$ {{ number_format($goal->target_value, 2, ',', '.') }}</span>
                            <span>Atual: R$ {{ number_format($goal->current_value, 2, ',', '.') }}</span>
                            <span>{{ $goal->start_date->format('d/m/Y') }} - {{ $goal->end_date->format('d/m/Y') }}</span>
                        </div>
                        <div class="mt-3">
                            <div class="w-full bg-gray-200 rounded-full h-2.5" style="background-color: rgba(var(--primary), 0.1);">
                                <div class="h-2.5 rounded-full transition-all" style="background: linear-gradient(135deg, rgb(var(--primary)), rgb(var(--primary-dark))); width: {{ min(100, $goal->progress) }}%;"></div>
                            </div>
                            <p class="text-xs mt-1" style="color: rgb(var(--text-secondary));">{{ number_format($goal->progress, 1) }}% concluído</p>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button type="button" onclick="event.stopPropagation(); openGoalModal({{ $goal->id }});" class="p-2 rounded-lg hover:bg-opacity-50 transition-colors" style="background-color: rgba(var(--primary), 0.1);">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </button>
                        <form action="{{ route('finance.planning.goals.destroy', $goal) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja remover esta meta?');">
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
            @endforeach
        </div>
        @else
        <div class="text-center py-12 rounded-xl" style="background-color: rgb(var(--card)); border: 1px solid rgb(var(--border));">
            <svg class="w-16 h-16 mx-auto mb-4 opacity-50" style="color: rgb(var(--text-secondary));" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            <h3 class="text-lg font-semibold mb-2">Nenhuma meta cadastrada</h3>
            <p class="text-sm mb-4" style="color: rgb(var(--text-secondary));">Comece criando sua primeira meta financeira</p>
        </div>
        @endif
    </div>

    <!-- Budgets Section (hidden by default) -->
    <div id="budgetsSection" style="display: none;">
        <h3 class="text-xl font-bold mb-4">Orçamentos</h3>
        <div class="text-center py-12 rounded-xl" style="background-color: rgb(var(--card)); border: 1px solid rgb(var(--border));">
            <p class="text-sm" style="color: rgb(var(--text-secondary));">Funcionalidade de orçamentos em desenvolvimento</p>
        </div>
    </div>
</div>

<!-- Modal de Meta Financeira -->
<div id="goalModal" class="fixed inset-0 z-50 hidden overflow-y-auto" style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity backdrop-blur-md" style="background: rgba(0, 0, 0, 0.4);" onclick="closeGoalModal()"></div>

        <div class="relative inline-block w-full max-w-2xl p-6 my-8 text-left align-middle transition-all transform rounded-xl shadow-xl z-50" style="background-color: #ffffff; border: 1px solid rgb(var(--border)); box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold" id="goalModalTitle">Nova Meta Financeira</h3>
                <button type="button" onclick="closeGoalModal()" class="p-2 rounded-lg hover:bg-opacity-50 transition-colors" style="background-color: rgba(239, 68, 68, 0.1); color: rgb(239, 68, 68);">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form id="goalForm" method="POST" class="space-y-6">
                @csrf
                <div id="goalFormMethod"></div>
                
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
                        <label for="goal_name" class="block text-sm font-medium mb-2">Nome da Meta *</label>
                        <input type="text" id="goal_name" name="name" required
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                            placeholder="Ex: Faturamento Anual">
                    </div>

                    <div>
                        <label for="goal_type" class="block text-sm font-medium mb-2">Tipo de Meta *</label>
                        <select id="goal_type" name="type" required
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                            <option value="Receita">Receita</option>
                            <option value="Lucro">Lucro</option>
                            <option value="Despesa">Despesa</option>
                        </select>
                    </div>

                    <div>
                        <label for="goal_scope" class="block text-sm font-medium mb-2">Escopo *</label>
                        <select id="goal_scope" name="scope" required
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                            <option value="PF">PF</option>
                            <option value="PJ">PJ</option>
                        </select>
                    </div>

                    <div>
                        <label for="goal_target_value" class="block text-sm font-medium mb-2">Valor Meta *</label>
                        <input type="number" id="goal_target_value" name="target_value" step="0.01" min="0.01" required
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                            placeholder="0,00">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="goal_start_date" class="block text-sm font-medium mb-2">Data Início *</label>
                            <input type="date" id="goal_start_date" name="start_date" required
                                class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                                style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                        </div>

                        <div>
                            <label for="goal_end_date" class="block text-sm font-medium mb-2">Data Fim *</label>
                            <input type="date" id="goal_end_date" name="end_date" required
                                class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                                style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                        </div>
                    </div>

                    <div>
                        <label for="goal_description" class="block text-sm font-medium mb-2">Descrição</label>
                        <textarea id="goal_description" name="description" rows="3"
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                            placeholder="Opcional"></textarea>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t" style="border-color: rgb(var(--border));">
                    <button type="submit" class="flex-1 px-6 py-3 rounded-lg font-medium text-white transition-all hover:scale-105" style="background: linear-gradient(135deg, rgb(var(--primary)), rgb(var(--primary-dark))); box-shadow: 0 4px 6px -1px rgba(var(--primary), 0.3);">
                        <span id="goalSubmitText">Criar Meta</span>
                    </button>
                    <button type="button" onclick="closeGoalModal()" class="flex-1 px-6 py-3 rounded-lg font-medium text-center transition-colors" style="background-color: rgba(var(--primary), 0.1); color: rgb(var(--primary));">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const goalsData = @json($goals->count() > 0 ? $goals->keyBy('id') : []);

function switchTab(tab) {
    if (tab === 'goals') {
        document.getElementById('goalsSection').style.display = 'block';
        document.getElementById('budgetsSection').style.display = 'none';
        document.getElementById('goalsTab').style.backgroundColor = 'rgb(var(--primary))';
        document.getElementById('goalsTab').style.color = 'white';
        document.getElementById('budgetsTab').style.backgroundColor = 'transparent';
        document.getElementById('budgetsTab').style.color = 'rgb(var(--text-secondary))';
    } else {
        document.getElementById('goalsSection').style.display = 'none';
        document.getElementById('budgetsSection').style.display = 'block';
        document.getElementById('budgetsTab').style.backgroundColor = 'rgb(var(--primary))';
        document.getElementById('budgetsTab').style.color = 'white';
        document.getElementById('goalsTab').style.backgroundColor = 'transparent';
        document.getElementById('goalsTab').style.color = 'rgb(var(--text-secondary))';
    }
}

function openGoalModal(goalId = null) {
    const modal = document.getElementById('goalModal');
    if (!modal) return;
    
    const form = document.getElementById('goalForm');
    const formMethod = document.getElementById('goalFormMethod');
    const title = document.getElementById('goalModalTitle');
    const submitText = document.getElementById('goalSubmitText');
    
    if (goalId && goalsData[goalId]) {
        const goal = goalsData[goalId];
        title.textContent = 'Editar Meta Financeira';
        submitText.textContent = 'Salvar Alterações';
        form.action = '{{ route("finance.planning.goals.update", ":id") }}'.replace(':id', goalId);
        formMethod.innerHTML = '@method("PUT")';
        
        document.getElementById('goal_name').value = goal.name || '';
        document.getElementById('goal_type').value = goal.type || 'Receita';
        document.getElementById('goal_scope').value = goal.scope || 'PF';
        document.getElementById('goal_target_value').value = goal.target_value || '';
        document.getElementById('goal_start_date').value = goal.start_date || '';
        document.getElementById('goal_end_date').value = goal.end_date || '';
        document.getElementById('goal_description').value = goal.description || '';
    } else {
        title.textContent = 'Nova Meta Financeira';
        submitText.textContent = 'Criar Meta';
        form.action = '{{ route("finance.planning.goals.store") }}';
        formMethod.innerHTML = '';
        form.reset();
    }
    
    modal.style.display = 'block';
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeGoalModal() {
    const modal = document.getElementById('goalModal');
    if (modal) {
        modal.style.display = 'none';
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeGoalModal();
    }
});
</script>
@endsection

