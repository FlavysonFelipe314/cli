@extends('layouts.app')

@section('title', 'Conciliação Bancária - CLIVUS')
@section('page-title', 'Conciliação Bancária')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold mb-1">Conciliação Bancária</h2>
            <p class="text-sm" style="color: rgb(var(--text-secondary));">Reconcilie suas transações com os extratos bancários</p>
        </div>
        <button type="button" onclick="event.preventDefault(); openImportModal();" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-medium text-white transition-all hover:scale-105" style="background: linear-gradient(135deg, rgb(var(--primary)), rgb(var(--primary-dark))); box-shadow: 0 4px 6px -1px rgba(var(--primary), 0.3);">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
            </svg>
            <span>Importar Extrato</span>
        </button>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="rounded-xl p-6" style="background: linear-gradient(135deg, rgba(251, 146, 60, 0.1), rgba(234, 88, 12, 0.1)); border: 1px solid rgb(251, 146, 60);">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium" style="color: rgb(234, 88, 12);">Pendentes</span>
                <svg class="w-5 h-5" style="color: rgb(251, 146, 60);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <p class="text-2xl font-bold" style="color: rgb(234, 88, 12);">{{ $summary['pending'] }}</p>
        </div>
        
        <div class="rounded-xl p-6" style="background: linear-gradient(135deg, rgba(34, 197, 94, 0.1), rgba(22, 163, 74, 0.1)); border: 1px solid rgb(34, 197, 94);">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium" style="color: rgb(22, 163, 74);">Conciliadas</span>
                <svg class="w-5 h-5" style="color: rgb(34, 197, 94);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <p class="text-2xl font-bold" style="color: rgb(22, 163, 74);">{{ $summary['reconciled'] }}</p>
        </div>
        
        <div class="rounded-xl p-6" style="background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), rgba(220, 38, 38, 0.1)); border: 1px solid rgb(239, 68, 68);">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium" style="color: rgb(220, 38, 38);">Ignoradas</span>
                <svg class="w-5 h-5" style="color: rgb(239, 68, 68);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </div>
            <p class="text-2xl font-bold" style="color: rgb(220, 38, 38);">{{ $summary['ignored'] }}</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="rounded-xl p-4" style="background-color: rgb(var(--card)); border: 1px solid rgb(var(--border)); box-shadow: var(--shadow);">
        <form method="GET" action="{{ route('finance.reconciliations.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <div>
                    <label for="account_id" class="block text-sm font-medium mb-2">Conta</label>
                    <select id="account_id" name="account_id"
                        class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                        style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                        <option value="">Todas as contas</option>
                        @foreach($accounts as $account)
                        <option value="{{ $account->id }}" {{ request('account_id') == $account->id ? 'selected' : '' }}>
                            {{ $account->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label for="start_date" class="block text-sm font-medium mb-2">Data Início</label>
                    <input type="date" id="start_date" name="start_date" value="{{ request('start_date') }}"
                        class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                        style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                </div>
                
                <div>
                    <label for="end_date" class="block text-sm font-medium mb-2">Data Fim</label>
                    <input type="date" id="end_date" name="end_date" value="{{ request('end_date') }}"
                        class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                        style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                </div>
                
                <div class="flex items-end">
                    <button type="submit" class="w-full px-4 py-2 rounded-lg font-medium text-white transition-all" style="background: linear-gradient(135deg, rgb(var(--primary)), rgb(var(--primary-dark)));">
                        Filtrar
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Reconciliations List -->
    @if($reconciliations->count() > 0)
    <div class="space-y-3">
        @foreach($reconciliations as $reconciliation)
        <div class="rounded-xl p-4 transition-all hover:scale-[1.01]" style="background-color: rgb(var(--card)); border: 1px solid rgb(var(--border)); box-shadow: var(--shadow);">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex-1">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0" style="background: linear-gradient(135deg, rgba(var(--primary), 0.2), rgba(var(--primary-dark), 0.2));">
                            <svg class="w-5 h-5" style="color: rgb(var(--primary));" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-semibold mb-1 truncate">{{ $reconciliation->statement_description ?: 'Sem descrição' }}</h3>
                            <div class="flex flex-wrap items-center gap-3 text-sm" style="color: rgb(var(--text-secondary));">
                                <span class="inline-flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                    </svg>
                                    {{ $reconciliation->account->name }}
                                </span>
                                <span class="inline-flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    {{ $reconciliation->statement_date->format('d/m/Y') }}
                                </span>
                                <span class="font-semibold" style="color: rgb(var(--text));">R$ {{ number_format($reconciliation->statement_amount, 2, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <span class="px-2 py-1 rounded text-xs font-medium 
                        @if($reconciliation->status === 'reconciled') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                        @elseif($reconciliation->status === 'ignored') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                        @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                        @endif">
                        @if($reconciliation->status === 'reconciled') Conciliada
                        @elseif($reconciliation->status === 'ignored') Ignorada
                        @else Pendente
                        @endif
                    </span>
                    <div class="flex gap-2">
                        <button type="button" onclick="event.stopPropagation(); reconcileItem({{ $reconciliation->id }});" class="p-2 rounded-lg hover:bg-opacity-50 transition-colors" style="background-color: rgba(34, 197, 94, 0.1); color: rgb(34, 197, 94);">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </button>
                        <form action="{{ route('finance.reconciliations.destroy', $reconciliation) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja remover esta conciliação?');">
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
    <div class="text-center py-12 rounded-xl" style="background-color: rgb(var(--card)); border: 1px solid rgb(var(--border));">
        <svg class="w-16 h-16 mx-auto mb-4 opacity-50" style="color: rgb(var(--text-secondary));" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
        <h3 class="text-lg font-semibold mb-2">Nenhuma transação para conciliar</h3>
        <p class="text-sm mb-4" style="color: rgb(var(--text-secondary));">Importe um extrato bancário para começar a conciliação</p>
        <button type="button" onclick="event.preventDefault(); openImportModal();" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-medium text-white transition-all" style="background: linear-gradient(135deg, rgb(var(--primary)), rgb(var(--primary-dark)));">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
            </svg>
            <span>Importar Extrato</span>
        </button>
    </div>
    @endif
</div>

<!-- Modal de Importar Extrato -->
<div id="importModal" class="fixed inset-0 z-50 hidden overflow-y-auto" style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity backdrop-blur-md" style="background: rgba(0, 0, 0, 0.4);" onclick="closeImportModal()"></div>

        <div class="relative inline-block w-full max-w-2xl p-6 my-8 text-left align-middle transition-all transform rounded-xl shadow-xl z-50" style="background-color: #ffffff; border: 1px solid rgb(var(--border)); box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold">Importar Extrato</h3>
                <button type="button" onclick="closeImportModal()" class="p-2 rounded-lg hover:bg-opacity-50 transition-colors" style="background-color: rgba(239, 68, 68, 0.1); color: rgb(239, 68, 68);">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form id="importForm" method="POST" action="{{ route('finance.reconciliations.store') }}" class="space-y-6">
                @csrf
                
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
                        <label for="import_account_id" class="block text-sm font-medium mb-2">Conta *</label>
                        <select id="import_account_id" name="account_id" required
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                            <option value="">Selecione uma conta</option>
                            @foreach($accounts as $account)
                            <option value="{{ $account->id }}">{{ $account->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="import_statement_date" class="block text-sm font-medium mb-2">Data do Extrato *</label>
                        <input type="date" id="import_statement_date" name="statement_date" required
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                    </div>

                    <div>
                        <label for="import_statement_amount" class="block text-sm font-medium mb-2">Valor *</label>
                        <input type="number" id="import_statement_amount" name="statement_amount" step="0.01" required
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                            placeholder="0,00">
                    </div>

                    <div>
                        <label for="import_statement_description" class="block text-sm font-medium mb-2">Descrição</label>
                        <input type="text" id="import_statement_description" name="statement_description"
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                            placeholder="Descrição da transação">
                    </div>

                    <div>
                        <label for="import_notes" class="block text-sm font-medium mb-2">Observações</label>
                        <textarea id="import_notes" name="notes" rows="3"
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                            placeholder="Observações adicionais..."></textarea>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t" style="border-color: rgb(var(--border));">
                    <button type="submit" class="flex-1 px-6 py-3 rounded-lg font-medium text-white transition-all hover:scale-105" style="background: linear-gradient(135deg, rgb(var(--primary)), rgb(var(--primary-dark))); box-shadow: 0 4px 6px -1px rgba(var(--primary), 0.3);">
                        Importar
                    </button>
                    <button type="button" onclick="closeImportModal()" class="flex-1 px-6 py-3 rounded-lg font-medium text-center transition-colors" style="background-color: rgba(var(--primary), 0.1); color: rgb(var(--primary));">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openImportModal() {
    const modal = document.getElementById('importModal');
    if (modal) {
        modal.style.display = 'block';
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
}

function closeImportModal() {
    const modal = document.getElementById('importModal');
    if (modal) {
        modal.style.display = 'none';
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
}

function reconcileItem(id) {
    if (confirm('Deseja marcar esta transação como conciliada?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `{{ route('finance.reconciliations.update', ':id') }}`.replace(':id', id);
        
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'PUT';
        form.appendChild(methodInput);
        
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = document.querySelector('meta[name="csrf-token"]').content;
        form.appendChild(csrfInput);
        
        const statusInput = document.createElement('input');
        statusInput.type = 'hidden';
        statusInput.name = 'status';
        statusInput.value = 'reconciled';
        form.appendChild(statusInput);
        
        document.body.appendChild(form);
        form.submit();
    }
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeImportModal();
    }
});
</script>
@endsection

