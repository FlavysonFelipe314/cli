@extends('layouts.app')

@section('title', 'Transações - CLIVUS')
@section('page-title', 'Transações')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold mb-1">Transações</h2>
            <p class="text-sm" style="color: rgb(var(--text-secondary));">Gerencie suas receitas e despesas</p>
        </div>
        <button type="button" onclick="event.preventDefault(); openTransactionModal();" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-medium text-white transition-all hover:scale-105" style="background: linear-gradient(135deg, rgb(var(--primary)), rgb(var(--primary-dark))); box-shadow: 0 4px 6px -1px rgba(var(--primary), 0.3);">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span>Nova Transação</span>
        </button>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="rounded-xl p-6" style="background: linear-gradient(135deg, rgba(34, 197, 94, 0.1), rgba(22, 163, 74, 0.1)); border: 1px solid rgb(34, 197, 94);">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium" style="color: rgb(22, 163, 74);">Receitas</span>
                <svg class="w-5 h-5" style="color: rgb(34, 197, 94);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                </svg>
            </div>
            <p class="text-2xl font-bold" style="color: rgb(22, 163, 74);">R$ {{ number_format($summary['receita'], 2, ',', '.') }}</p>
        </div>
        
        <div class="rounded-xl p-6" style="background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), rgba(220, 38, 38, 0.1)); border: 1px solid rgb(239, 68, 68);">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium" style="color: rgb(220, 38, 38);">Despesas</span>
                <svg class="w-5 h-5" style="color: rgb(239, 68, 68);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                </svg>
            </div>
            <p class="text-2xl font-bold" style="color: rgb(220, 38, 38);">R$ {{ number_format($summary['despesa'], 2, ',', '.') }}</p>
        </div>
        
        <div class="rounded-xl p-6" style="background: linear-gradient(135deg, rgba(var(--primary), 0.1), rgba(var(--primary-dark), 0.1)); border: 1px solid rgb(var(--primary));">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium" style="color: rgb(var(--primary));">Saldo</span>
                <svg class="w-5 h-5" style="color: rgb(var(--primary));" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
            <p class="text-2xl font-bold" style="color: rgb(var(--primary));">R$ {{ number_format($summary['saldo'], 2, ',', '.') }}</p>
        </div>
    </div>

    <!-- Filters e Busca -->
    <div class="rounded-xl p-4" style="background-color: rgb(var(--card)); border: 1px solid rgb(var(--border)); box-shadow: var(--shadow);">
        <form method="GET" action="{{ route('finance.transactions.index') }}" class="space-y-4">
            <!-- Busca -->
            <div>
                <label for="search" class="block text-sm font-medium mb-2">Buscar</label>
                <div class="relative">
                    <input type="text" id="search" name="search" value="{{ request('search') }}"
                        class="w-full px-4 py-2 pl-10 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                        style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                        placeholder="Buscar por descrição, observações, método de pagamento ou conta...">
                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5" style="color: rgb(var(--text-secondary));" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>
            
            <!-- Filtros -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
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
                    <label for="type" class="block text-sm font-medium mb-2">Tipo</label>
                    <select id="type" name="type"
                        class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                        style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                        <option value="">Todos os tipos</option>
                        <option value="receita" {{ request('type') == 'receita' ? 'selected' : '' }}>Receita</option>
                        <option value="despesa" {{ request('type') == 'despesa' ? 'selected' : '' }}>Despesa</option>
                    </select>
                </div>
            </div>
            
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 rounded-lg font-medium text-white transition-all" style="background: linear-gradient(135deg, rgb(var(--primary)), rgb(var(--primary-dark)));">
                    Filtrar
                </button>
                @if(request()->hasAny(['search', 'account_id', 'type']))
                <a href="{{ route('finance.transactions.index') }}" class="px-4 py-2 rounded-lg font-medium text-center transition-colors" style="background-color: rgba(var(--primary), 0.1); color: rgb(var(--primary));">
                    Limpar
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Transactions List -->
    @if($transactions->count() > 0)
    <div class="space-y-3">
        @foreach($transactions as $transaction)
        <div class="rounded-xl p-4 transition-all hover:scale-[1.01]" style="background-color: rgb(var(--card)); border: 1px solid rgb(var(--border)); box-shadow: var(--shadow);">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex-1">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0" style="background: {{ $transaction->type === 'receita' ? 'linear-gradient(135deg, rgba(34, 197, 94, 0.2), rgba(22, 163, 74, 0.2))' : 'linear-gradient(135deg, rgba(239, 68, 68, 0.2), rgba(220, 38, 38, 0.2))' }};">
                            @if($transaction->type === 'receita')
                            <svg class="w-5 h-5" style="color: rgb(34, 197, 94);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                            @else
                            <svg class="w-5 h-5" style="color: rgb(239, 68, 68);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                            </svg>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-semibold mb-1 truncate">{{ $transaction->description }}</h3>
                            <div class="flex flex-wrap items-center gap-3 text-sm" style="color: rgb(var(--text-secondary));">
                                <span class="inline-flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                    </svg>
                                    {{ $transaction->account->name }}
                                </span>
                                <span class="inline-flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    {{ $transaction->date->format('d/m/Y') }}
                                </span>
                                <span class="px-2 py-1 rounded text-xs font-medium {{ $transaction->type === 'receita' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                    {{ ucfirst($transaction->type) }}
                                </span>
                                @if($transaction->payment_method)
                                <span class="px-2 py-1 rounded text-xs font-medium" style="background-color: rgba(var(--primary), 0.1); color: rgb(var(--primary));">
                                    @switch($transaction->payment_method)
                                        @case('pix')
                                            PIX
                                            @break
                                        @case('cartao_credito')
                                            Cartão Crédito
                                            @break
                                        @case('cartao_debito')
                                            Cartão Débito
                                            @break
                                        @case('dinheiro')
                                            Dinheiro
                                            @break
                                        @case('transferencia')
                                            Transferência
                                            @break
                                        @case('boleto')
                                            Boleto
                                            @break
                                        @default
                                            {{ ucfirst($transaction->payment_method) }}
                                    @endswitch
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center gap-3 sm:flex-shrink-0">
                    <div class="text-right">
                        <p class="text-xl font-bold {{ $transaction->type === 'receita' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                            {{ $transaction->type === 'receita' ? '+' : '-' }} R$ {{ number_format($transaction->amount, 2, ',', '.') }}
                        </p>
                    </div>
                    <div class="flex gap-2">
                        <button type="button" onclick="event.stopPropagation(); openTransactionModal({{ $transaction->id }});" class="p-2 rounded-lg hover:bg-opacity-50 transition-colors" style="background-color: rgba(var(--primary), 0.1);">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </button>
                        <form action="{{ route('finance.transactions.destroy', $transaction) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja remover esta transação?');">
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

    <!-- Pagination -->
    <div class="mt-6">
        {{ $transactions->links() }}
    </div>
    @else
    <div class="text-center py-12 rounded-xl" style="background-color: rgb(var(--card)); border: 1px solid rgb(var(--border));">
        <svg class="w-16 h-16 mx-auto mb-4 opacity-50" style="color: rgb(var(--text-secondary));" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
        </svg>
        <h3 class="text-lg font-semibold mb-2">Nenhuma transação encontrada</h3>
        <p class="text-sm mb-4" style="color: rgb(var(--text-secondary));">Comece registrando sua primeira transação</p>
        <button type="button" onclick="event.preventDefault(); openTransactionModal();" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-medium text-white transition-all" style="background: linear-gradient(135deg, rgb(var(--primary)), rgb(var(--primary-dark)));">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span>Criar Transação</span>
        </button>
    </div>
    @endif
</div>

<!-- Modal de Transação -->
<div id="transactionModal" class="fixed inset-0 z-50 hidden overflow-y-auto" style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <!-- Overlay com efeito blur dark glass -->
        <div class="fixed inset-0 transition-opacity backdrop-blur-md" style="background: rgba(0, 0, 0, 0.4);" onclick="closeTransactionModal()"></div>

        <!-- Modal -->
        <div class="relative inline-block w-full max-w-2xl p-6 my-8 text-left align-middle transition-all transform rounded-xl shadow-xl z-50" style="background-color: #ffffff; border: 1px solid rgb(var(--border)); box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold" id="transactionModalTitle">Nova Transação</h3>
                <button onclick="closeTransactionModal()" class="p-2 rounded-lg hover:bg-opacity-50 transition-colors" style="background-color: rgba(var(--primary), 0.1);">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form id="transactionForm" method="POST" class="space-y-6">
                @csrf
                <div id="transactionFormMethod"></div>
                
                @if($errors->any())
                <div class="p-4 rounded-lg mb-4" style="background-color: rgba(239, 68, 68, 0.1); border: 1px solid rgb(239, 68, 68);">
                    <ul class="list-disc list-inside text-sm" style="color: rgb(220, 38, 38);">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                
                <div class="space-y-6 max-h-[60vh] overflow-y-auto pr-2">
                    <div>
                        <label for="transaction_account_id" class="block text-sm font-medium mb-2">Conta *</label>
                        <select id="transaction_account_id" name="account_id" required
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                            <option value="">Selecione uma conta</option>
                            @foreach($accounts as $account)
                            <option value="{{ $account->id }}">
                                {{ $account->name }} - R$ {{ number_format($account->balance, 2, ',', '.') }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label for="transaction_description" class="block text-sm font-medium mb-2">Descrição *</label>
                        <input type="text" id="transaction_description" name="description" required
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                            placeholder="Ex: Salário, Aluguel, Supermercado">
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="transaction_type" class="block text-sm font-medium mb-2">Tipo *</label>
                            <select id="transaction_type" name="type" required
                                class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                                style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                                <option value="">Selecione o tipo</option>
                                <option value="receita">Receita</option>
                                <option value="despesa">Despesa</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="transaction_payment_method" class="block text-sm font-medium mb-2">Método de Pagamento</label>
                            <select id="transaction_payment_method" name="payment_method"
                                class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                                style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                                <option value="">Selecione o método</option>
                                <option value="pix">PIX</option>
                                <option value="cartao_credito">Cartão de Crédito</option>
                                <option value="cartao_debito">Cartão de Débito</option>
                                <option value="dinheiro">Dinheiro</option>
                                <option value="transferencia">Transferência Bancária</option>
                                <option value="boleto">Boleto</option>
                                <option value="outro">Outro</option>
                            </select>
                        </div>
                    </div>
                    
                    <div>
                        <label for="transaction_amount" class="block text-sm font-medium mb-2">Valor *</label>
                        <input type="number" id="transaction_amount" name="amount" step="0.01" min="0.01" required
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                            placeholder="0.00">
                    </div>
                    
                    <div>
                        <label for="transaction_date" class="block text-sm font-medium mb-2">Data *</label>
                        <input type="date" id="transaction_date" name="date" required
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                    </div>
                    
                    <div>
                        <label for="transaction_notes" class="block text-sm font-medium mb-2">Observações</label>
                        <textarea id="transaction_notes" name="notes" rows="3"
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2 resize-none"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                            placeholder="Adicione observações sobre esta transação..."></textarea>
                    </div>
                </div>
                
                <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t" style="border-color: rgb(var(--border));">
                    <button type="submit" class="flex-1 px-6 py-3 rounded-lg font-medium text-white transition-all hover:scale-105" style="background: linear-gradient(135deg, rgb(var(--primary)), rgb(var(--primary-dark))); box-shadow: 0 4px 6px -1px rgba(var(--primary), 0.3);">
                        <span id="transactionSubmitText">Criar Transação</span>
                    </button>
                    <button type="button" onclick="closeTransactionModal()" class="flex-1 px-6 py-3 rounded-lg font-medium text-center transition-colors" style="background-color: rgba(var(--primary), 0.1); color: rgb(var(--primary));">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Abrir modal automaticamente se houver erros de validação
@if($errors->any())
document.addEventListener('DOMContentLoaded', function() {
    // Preencher campos com valores antigos se houver erros
    @if(old('account_id'))
    document.getElementById('transaction_account_id').value = @json(old('account_id'));
    @endif
    @if(old('description'))
    document.getElementById('transaction_description').value = @json(old('description'));
    @endif
    @if(old('type'))
    document.getElementById('transaction_type').value = @json(old('type'));
    @endif
    @if(old('payment_method'))
    document.getElementById('transaction_payment_method').value = @json(old('payment_method'));
    @endif
    @if(old('amount'))
    document.getElementById('transaction_amount').value = @json(old('amount'));
    @endif
    @if(old('date'))
    document.getElementById('transaction_date').value = @json(old('date'));
    @endif
    @if(old('notes'))
    document.getElementById('transaction_notes').value = @json(old('notes'));
    @endif
    
    // Verificar se é edição ou criação
    @php
        $transactionId = request()->route('transaction') ? request()->route('transaction')->id : null;
    @endphp
    @if($transactionId)
    openTransactionModal({{ $transactionId }});
    @else
    openTransactionModal();
    @endif
});
@endif

@php
$transactionsArray = [];
foreach($transactions as $t) {
    $transactionsArray[$t->id] = [
        'id' => $t->id,
        'account_id' => $t->account_id,
        'description' => $t->description,
        'type' => $t->type,
        'payment_method' => $t->payment_method ?? '',
        'amount' => $t->amount,
        'date' => $t->date->format('Y-m-d'),
        'notes' => $t->notes ?? '',
    ];
}
@endphp
const transactionsData = @json($transactionsArray);

function openTransactionModal(transactionId = null) {
    const modal = document.getElementById('transactionModal');
    if (!modal) return;
    
    const form = document.getElementById('transactionForm');
    const formMethod = document.getElementById('transactionFormMethod');
    const title = document.getElementById('transactionModalTitle');
    const submitText = document.getElementById('transactionSubmitText');
    
    if (transactionId && transactionsData[transactionId]) {
        const transaction = transactionsData[transactionId];
        title.textContent = 'Editar Transação';
        submitText.textContent = 'Salvar Alterações';
        form.action = '{{ route("finance.transactions.update", ":id") }}'.replace(':id', transactionId);
        formMethod.innerHTML = '@method("PUT")';
        
        document.getElementById('transaction_account_id').value = transaction.account_id || '';
        document.getElementById('transaction_description').value = transaction.description || '';
        document.getElementById('transaction_type').value = transaction.type || '';
        document.getElementById('transaction_payment_method').value = transaction.payment_method || '';
        document.getElementById('transaction_amount').value = transaction.amount || '';
        document.getElementById('transaction_date').value = transaction.date || '';
        document.getElementById('transaction_notes').value = transaction.notes || '';
    } else {
        title.textContent = 'Nova Transação';
        submitText.textContent = 'Criar Transação';
        form.action = '{{ route("finance.transactions.store") }}';
        formMethod.innerHTML = '';
        form.reset();
        document.getElementById('transaction_date').value = new Date().toISOString().split('T')[0];
    }
    
    modal.style.display = 'block';
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeTransactionModal() {
    const modal = document.getElementById('transactionModal');
    if (modal) {
        modal.style.display = 'none';
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
}

// Fechar modal com ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeTransactionModal();
    }
});
</script>
@endsection
