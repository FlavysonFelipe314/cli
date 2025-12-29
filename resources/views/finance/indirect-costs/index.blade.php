@extends('layouts.app')

@section('title', 'Custos Indiretos - CLIVUS')
@section('page-title', 'Custos Indiretos')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold mb-1">Rateio de Custos Indiretos</h2>
            <p class="text-sm" style="color: rgb(var(--text-secondary));">Configure como os custos indiretos serão rateados na precificação</p>
        </div>
    </div>

    <!-- Periodo de Referência -->
    <div class="rounded-xl p-6" style="background-color: rgb(var(--card)); border: 1px solid rgb(var(--border)); box-shadow: var(--shadow);">
        <h3 class="text-lg font-semibold mb-4">Período de Referência</h3>
        <form method="GET" action="{{ route('finance.indirect-costs.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="month" class="block text-sm font-medium mb-2">Mês</label>
                <select id="month" name="month"
                    class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                    style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                    @for($i = 1; $i <= 12; $i++)
                    <option value="{{ $i }}" {{ $month == $i ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create(null, $i, 1)->locale('pt_BR')->translatedFormat('F') }}
                    </option>
                    @endfor
                </select>
            </div>
            <div>
                <label for="year" class="block text-sm font-medium mb-2">Ano</label>
                <input type="number" id="year" name="year" value="{{ $year }}" min="2000" max="2100"
                    class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                    style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full px-4 py-2 rounded-lg font-medium text-white transition-all" style="background: linear-gradient(135deg, rgb(var(--primary)), rgb(var(--primary-dark)));">
                    Filtrar
                </button>
            </div>
        </form>
    </div>

    <!-- Configuração de Rateio -->
    <form method="POST" action="{{ route('finance.indirect-costs.allocation.store') }}" class="rounded-xl p-6" style="background-color: rgb(var(--card)); border: 1px solid rgb(var(--border)); box-shadow: var(--shadow);">
        @csrf
        <input type="hidden" name="reference_month" value="{{ $month }}">
        <input type="hidden" name="reference_year" value="{{ $year }}">
        
        <div class="mb-6">
            <label for="allocation_mode" class="block text-sm font-medium mb-2">Modo de Rateio</label>
            <select id="allocation_mode" name="allocation_mode" required
                class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                <option value="Simples" {{ ($allocation->allocation_mode ?? 'Simples') == 'Simples' ? 'selected' : '' }}>Simples</option>
                <option value="Avançado" {{ ($allocation->allocation_mode ?? '') == 'Avançado' ? 'selected' : '' }}>Avançado</option>
            </select>
        </div>

        <!-- Custos Indiretos -->
        <div class="mb-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold">Custos Indiretos</h3>
                <button type="button" onclick="event.preventDefault(); openCostModal();" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-medium text-white transition-all hover:scale-105" style="background: linear-gradient(135deg, rgb(var(--primary)), rgb(var(--primary-dark))); box-shadow: 0 4px 6px -1px rgba(var(--primary), 0.3);">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>Adicionar Custo</span>
                </button>
            </div>

            @if($costs->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b" style="border-color: rgb(var(--border));">
                            <th class="text-left py-3 px-4 text-sm font-medium">Descrição</th>
                            <th class="text-left py-3 px-4 text-sm font-medium">Categoria</th>
                            <th class="text-left py-3 px-4 text-sm font-medium">Tipo</th>
                            <th class="text-left py-3 px-4 text-sm font-medium">Valor Mensal</th>
                            <th class="text-left py-3 px-4 text-sm font-medium">Centro de Custo</th>
                            <th class="text-left py-3 px-4 text-sm font-medium">Incluir?</th>
                            <th class="text-left py-3 px-4 text-sm font-medium">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($costs as $cost)
                        <tr class="border-b" style="border-color: rgb(var(--border));">
                            <td class="py-3 px-4">{{ $cost->description }}</td>
                            <td class="py-3 px-4">{{ $cost->category ?: '-' }}</td>
                            <td class="py-3 px-4">{{ $cost->type }}</td>
                            <td class="py-3 px-4">R$ {{ number_format($cost->monthly_value, 2, ',', '.') }}</td>
                            <td class="py-3 px-4">{{ $cost->cost_center ?: '-' }}</td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-1 rounded text-xs font-medium {{ $cost->include_in_allocation ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $cost->include_in_allocation ? 'Sim' : 'Não' }}
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex gap-2">
                                    <button type="button" onclick="openCostModal({{ $cost->id }})" class="p-1 rounded hover:bg-opacity-50 transition-colors" style="background-color: rgba(var(--primary), 0.1);">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                    <form action="{{ route('finance.indirect-costs.destroy', $cost) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja remover este custo?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1 rounded hover:bg-opacity-50 transition-colors" style="background-color: rgba(239, 68, 68, 0.1); color: rgb(239, 68, 68);">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4 p-4 rounded-lg" style="background-color: rgba(var(--primary), 0.1);">
                <p class="text-sm font-medium">
                    Total de custos indiretos incluídos no rateio: <span class="font-bold">R$ {{ number_format($totalIncluded, 2, ',', '.') }}</span>
                </p>
            </div>
            @else
            <div class="text-center py-8 rounded-lg" style="background-color: rgba(var(--primary), 0.05);">
                <p class="text-sm" style="color: rgb(var(--text-secondary));">Nenhum custo indireto cadastrado para este período</p>
            </div>
            @endif
        </div>

        <!-- Base de Rateio -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-4">Base de Rateio</h3>
            <div class="space-y-3">
                <label class="flex items-start gap-3 p-4 rounded-lg cursor-pointer transition-colors hover:bg-opacity-50" style="background-color: {{ ($allocation->allocation_base ?? 'percent_revenue') == 'percent_revenue' ? 'rgba(var(--primary), 0.1)' : 'transparent' }};">
                    <input type="radio" name="allocation_base" value="percent_revenue" {{ ($allocation->allocation_base ?? 'percent_revenue') == 'percent_revenue' ? 'checked' : '' }} class="mt-1">
                    <div>
                        <p class="font-medium">% sobre faturamento</p>
                        <p class="text-sm mt-1" style="color: rgb(var(--text-secondary));">Os custos indiretos serão calculados como um percentual sobre o faturamento do período.</p>
                    </div>
                </label>
                <label class="flex items-start gap-3 p-4 rounded-lg cursor-pointer transition-colors hover:bg-opacity-50" style="background-color: {{ ($allocation->allocation_base ?? '') == 'cost_per_unit' ? 'rgba(var(--primary), 0.1)' : 'transparent' }};">
                    <input type="radio" name="allocation_base" value="cost_per_unit" {{ ($allocation->allocation_base ?? '') == 'cost_per_unit' ? 'checked' : '' }} class="mt-1">
                    <div>
                        <p class="font-medium">Custo por unidade (para comércio)</p>
                    </div>
                </label>
                <label class="flex items-start gap-3 p-4 rounded-lg cursor-pointer transition-colors hover:bg-opacity-50" style="background-color: {{ ($allocation->allocation_base ?? '') == 'cost_per_hour' ? 'rgba(var(--primary), 0.1)' : 'transparent' }};">
                    <input type="radio" name="allocation_base" value="cost_per_hour" {{ ($allocation->allocation_base ?? '') == 'cost_per_hour' ? 'checked' : '' }} class="mt-1">
                    <div>
                        <p class="font-medium">Custo por hora (para serviços)</p>
                    </div>
                </label>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="px-6 py-3 rounded-lg font-medium text-white transition-all hover:scale-105" style="background: linear-gradient(135deg, rgb(var(--primary)), rgb(var(--primary-dark))); box-shadow: 0 4px 6px -1px rgba(var(--primary), 0.3);">
                Salvar Configuração
            </button>
        </div>
    </form>
</div>

<!-- Modal de Custo Indireto -->
<div id="costModal" class="fixed inset-0 z-50 hidden overflow-y-auto" style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity backdrop-blur-md" style="background: rgba(0, 0, 0, 0.4);" onclick="closeCostModal()"></div>

        <div class="relative inline-block w-full max-w-2xl p-6 my-8 text-left align-middle transition-all transform rounded-xl shadow-xl z-50" style="background-color: #ffffff; border: 1px solid rgb(var(--border)); box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold" id="costModalTitle">Adicionar Custo</h3>
                <button type="button" onclick="closeCostModal()" class="p-2 rounded-lg hover:bg-opacity-50 transition-colors" style="background-color: rgba(239, 68, 68, 0.1); color: rgb(239, 68, 68);">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form id="costForm" method="POST" class="space-y-6">
                @csrf
                <div id="costFormMethod"></div>
                <input type="hidden" name="reference_month" value="{{ $month }}">
                <input type="hidden" name="reference_year" value="{{ $year }}">
                
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
                        <label for="cost_description" class="block text-sm font-medium mb-2">Descrição *</label>
                        <input type="text" id="cost_description" name="description" required
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                            placeholder="Ex: Aluguel do escritório">
                    </div>

                    <div>
                        <label for="cost_category" class="block text-sm font-medium mb-2">Categoria</label>
                        <select id="cost_category" name="category"
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                            <option value="">Selecione uma categoria</option>
                            <option value="Administração">Administração</option>
                            <option value="Marketing">Marketing</option>
                            <option value="Operacional">Operacional</option>
                            <option value="Financeiro">Financeiro</option>
                            <option value="Outros">Outros</option>
                        </select>
                    </div>

                    <div>
                        <label for="cost_type" class="block text-sm font-medium mb-2">Tipo *</label>
                        <select id="cost_type" name="type" required
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                            <option value="Fixo">Fixo</option>
                            <option value="Variável">Variável</option>
                        </select>
                    </div>

                    <div>
                        <label for="cost_monthly_value" class="block text-sm font-medium mb-2">Valor Mensal *</label>
                        <input type="number" id="cost_monthly_value" name="monthly_value" step="0.01" min="0" required
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                            placeholder="0">
                    </div>

                    <div>
                        <label for="cost_cost_center" class="block text-sm font-medium mb-2">Centro de Custo</label>
                        <input type="text" id="cost_cost_center" name="cost_center"
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                            placeholder="Opcional">
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" id="cost_include_in_allocation" name="include_in_allocation" value="1" checked
                            class="w-4 h-4 rounded border transition-colors"
                            style="border-color: rgb(var(--border));">
                        <label for="cost_include_in_allocation" class="text-sm font-medium">Incluir?</label>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t" style="border-color: rgb(var(--border));">
                    <button type="submit" class="flex-1 px-6 py-3 rounded-lg font-medium text-white transition-all hover:scale-105" style="background: linear-gradient(135deg, rgb(var(--primary)), rgb(var(--primary-dark))); box-shadow: 0 4px 6px -1px rgba(var(--primary), 0.3);">
                        <span id="costSubmitText">Adicionar Custo</span>
                    </button>
                    <button type="button" onclick="closeCostModal()" class="flex-1 px-6 py-3 rounded-lg font-medium text-center transition-colors" style="background-color: rgba(var(--primary), 0.1); color: rgb(var(--primary));">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const costsData = @json($costs->count() > 0 ? $costs->keyBy('id') : []);

function openCostModal(costId = null) {
    const modal = document.getElementById('costModal');
    if (!modal) return;
    
    const form = document.getElementById('costForm');
    const formMethod = document.getElementById('costFormMethod');
    const title = document.getElementById('costModalTitle');
    const submitText = document.getElementById('costSubmitText');
    
    if (costId && costsData[costId]) {
        const cost = costsData[costId];
        title.textContent = 'Editar Custo Indireto';
        submitText.textContent = 'Salvar Alterações';
        form.action = '{{ route("finance.indirect-costs.update", ":id") }}'.replace(':id', costId);
        formMethod.innerHTML = '@method("PUT")';
        
        document.getElementById('cost_description').value = cost.description || '';
        document.getElementById('cost_category').value = cost.category || '';
        document.getElementById('cost_type').value = cost.type || 'Fixo';
        document.getElementById('cost_monthly_value').value = cost.monthly_value || '';
        document.getElementById('cost_cost_center').value = cost.cost_center || '';
        document.getElementById('cost_include_in_allocation').checked = cost.include_in_allocation || false;
    } else {
        title.textContent = 'Adicionar Custo';
        submitText.textContent = 'Adicionar Custo';
        form.action = '{{ route("finance.indirect-costs.store") }}';
        formMethod.innerHTML = '';
        form.reset();
        document.getElementById('cost_include_in_allocation').checked = true;
    }
    
    modal.style.display = 'block';
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeCostModal() {
    const modal = document.getElementById('costModal');
    if (modal) {
        modal.style.display = 'none';
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeCostModal();
    }
});
</script>
@endsection

