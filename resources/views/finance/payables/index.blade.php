@extends('layouts.app')

@section('title', 'Contas a Pagar - CLIVUS')
@section('page-title', 'Contas a Pagar')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold mb-1">Contas a Pagar</h2>
            <p class="text-sm" style="color: rgb(var(--text-secondary));">Gerencie suas contas a pagar</p>
        </div>
        <button type="button" onclick="event.preventDefault(); openPayableModal();" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-medium text-white transition-all hover:scale-105" style="background: linear-gradient(135deg, rgb(var(--primary)), rgb(var(--primary-dark))); box-shadow: 0 4px 6px -1px rgba(var(--primary), 0.3);">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span>Nova Conta a Pagar</span>
        </button>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="rounded-xl p-6" style="background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), rgba(220, 38, 38, 0.1)); border: 1px solid rgb(239, 68, 68);">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium" style="color: rgb(220, 38, 38);">Pendentes</span>
                <svg class="w-5 h-5" style="color: rgb(239, 68, 68);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <p class="text-2xl font-bold" style="color: rgb(220, 38, 38);">R$ {{ number_format($summary['pending'], 2, ',', '.') }}</p>
        </div>
        
        <div class="rounded-xl p-6" style="background: linear-gradient(135deg, rgba(34, 197, 94, 0.1), rgba(22, 163, 74, 0.1)); border: 1px solid rgb(34, 197, 94);">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium" style="color: rgb(22, 163, 74);">Pagas</span>
                <svg class="w-5 h-5" style="color: rgb(34, 197, 94);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <p class="text-2xl font-bold" style="color: rgb(22, 163, 74);">R$ {{ number_format($summary['paid'], 2, ',', '.') }}</p>
        </div>
        
        <div class="rounded-xl p-6" style="background: linear-gradient(135deg, rgba(251, 146, 60, 0.1), rgba(234, 88, 12, 0.1)); border: 1px solid rgb(251, 146, 60);">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium" style="color: rgb(234, 88, 12);">Vencidas</span>
                <svg class="w-5 h-5" style="color: rgb(251, 146, 60);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <p class="text-2xl font-bold" style="color: rgb(234, 88, 12);">R$ {{ number_format($summary['overdue'], 2, ',', '.') }}</p>
        </div>
    </div>

    <!-- Filters e Busca -->
    <div class="rounded-xl p-4" style="background-color: rgb(var(--card)); border: 1px solid rgb(var(--border)); box-shadow: var(--shadow);">
        <form method="GET" action="{{ route('finance.payables.index') }}" class="space-y-4">
            <div>
                <label for="search" class="block text-sm font-medium mb-2">Buscar</label>
                <div class="relative">
                    <input type="text" id="search" name="search" value="{{ request('search') }}"
                        class="w-full px-4 py-2 pl-10 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                        style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                        placeholder="Buscar por descrição ou observações...">
                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5" style="color: rgb(var(--text-secondary));" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label for="status" class="block text-sm font-medium mb-2">Status</label>
                    <select id="status" name="status"
                        class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                        style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                        <option value="">Todos os status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendente</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paga</option>
                        <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Vencida</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelada</option>
                    </select>
                </div>
                
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
                    <label for="category_id" class="block text-sm font-medium mb-2">Categoria</label>
                    <select id="category_id" name="category_id"
                        class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                        style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                        <option value="">Todas as categorias</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 rounded-lg font-medium text-white transition-all" style="background: linear-gradient(135deg, rgb(var(--primary)), rgb(var(--primary-dark)));">
                    Filtrar
                </button>
                @if(request()->hasAny(['search', 'status', 'account_id', 'category_id']))
                <a href="{{ route('finance.payables.index') }}" class="px-4 py-2 rounded-lg font-medium text-center transition-colors" style="background-color: rgba(var(--primary), 0.1); color: rgb(var(--primary));">
                    Limpar
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Payables List -->
    @if($payables->count() > 0)
    <div class="space-y-3">
        @foreach($payables as $payable)
        <div class="rounded-xl p-4 transition-all hover:scale-[1.01]" style="background-color: rgb(var(--card)); border: 1px solid rgb(var(--border)); box-shadow: var(--shadow);">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex-1">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0" style="background: linear-gradient(135deg, rgba(239, 68, 68, 0.2), rgba(220, 38, 38, 0.2));">
                            <svg class="w-5 h-5" style="color: rgb(239, 68, 68);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-semibold mb-1 truncate">{{ $payable->description }}</h3>
                            <div class="flex flex-wrap items-center gap-3 text-sm" style="color: rgb(var(--text-secondary));">
                                <span class="inline-flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    Vencimento: {{ $payable->due_date->format('d/m/Y') }}
                                </span>
                                @if($payable->account)
                                <span class="inline-flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                    </svg>
                                    {{ $payable->account->name }}
                                </span>
                                @endif
                                @if($payable->category)
                                <span class="px-2 py-1 rounded text-xs font-medium" style="background-color: rgba(var(--primary), 0.1); color: rgb(var(--primary));">
                                    {{ $payable->category->name }}
                                </span>
                                @endif
                                @if($payable->contact)
                                <span class="inline-flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    {{ $payable->contact->name }}
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-right">
                        <p class="text-2xl font-bold" style="color: rgb(239, 68, 68);">R$ {{ number_format($payable->amount, 2, ',', '.') }}</p>
                        <span class="px-2 py-1 rounded text-xs font-medium 
                            @if($payable->status === 'paid') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                            @elseif($payable->status === 'overdue') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                            @elseif($payable->status === 'cancelled') bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200
                            @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                            @endif">
                            @if($payable->status === 'paid') Paga
                            @elseif($payable->status === 'overdue') Vencida
                            @elseif($payable->status === 'cancelled') Cancelada
                            @else Pendente
                            @endif
                        </span>
                    </div>
                    <div class="flex gap-2">
                        <button type="button" onclick="event.stopPropagation(); openPayableModal({{ $payable->id }});" class="p-2 rounded-lg hover:bg-opacity-50 transition-colors" style="background-color: rgba(var(--primary), 0.1);">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </button>
                        <form action="{{ route('finance.payables.destroy', $payable) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja remover esta conta a pagar?');">
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
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <h3 class="text-lg font-semibold mb-2">Nenhuma conta a pagar cadastrada</h3>
        <p class="text-sm mb-4" style="color: rgb(var(--text-secondary));">Comece criando sua primeira conta a pagar</p>
        <button type="button" onclick="event.preventDefault(); openPayableModal();" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-medium text-white transition-all" style="background: linear-gradient(135deg, rgb(var(--primary)), rgb(var(--primary-dark)));">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span>Criar Conta a Pagar</span>
        </button>
    </div>
    @endif
</div>

<!-- Modal de Conta a Pagar -->
<div id="payableModal" class="fixed inset-0 z-50 hidden overflow-y-auto" style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity backdrop-blur-md" style="background: rgba(0, 0, 0, 0.4);" onclick="closePayableModal()"></div>

        <div class="relative inline-block w-full max-w-2xl p-6 my-8 text-left align-middle transition-all transform rounded-xl shadow-xl z-50" style="background-color: #ffffff; border: 1px solid rgb(var(--border)); box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold" id="payableModalTitle">Nova Conta a Pagar</h3>
                <button type="button" onclick="closePayableModal()" class="p-2 rounded-lg hover:bg-opacity-50 transition-colors" style="background-color: rgba(239, 68, 68, 0.1); color: rgb(239, 68, 68);">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form id="payableForm" method="POST" class="space-y-6">
                @csrf
                <div id="payableFormMethod"></div>
                
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
                        <label for="payable_description" class="block text-sm font-medium mb-2">Descrição</label>
                        <input type="text" id="payable_description" name="description" required
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                            placeholder="Descrição da conta a pagar">
                    </div>

                    <div>
                        <label for="payable_amount" class="block text-sm font-medium mb-2">Valor Total *</label>
                        <input type="number" id="payable_amount" name="amount" step="0.01" min="0.01" required
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                            placeholder="0,00">
                    </div>

                    <div>
                        <label for="payable_due_date" class="block text-sm font-medium mb-2">Data de Vencimento *</label>
                        <input type="date" id="payable_due_date" name="due_date" required
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                    </div>

                    <div>
                        <label for="payable_type" class="block text-sm font-medium mb-2">Tipo *</label>
                        <select id="payable_type" name="type" required
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                            <option value="Pessoa Física (PF)">Pessoa Física (PF)</option>
                            <option value="Pessoa Jurídica (PJ)">Pessoa Jurídica (PJ)</option>
                        </select>
                    </div>

                    <div>
                        <label for="payable_account_id" class="block text-sm font-medium mb-2">Conta</label>
                        <select id="payable_account_id" name="account_id"
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                            <option value="">Selecione uma conta</option>
                            @foreach($accounts as $account)
                            <option value="{{ $account->id }}">{{ $account->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="payable_category_id" class="block text-sm font-medium mb-2">Categoria</label>
                        <select id="payable_category_id" name="category_id"
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                            <option value="">Selecione uma categoria</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="payable_contact_id" class="block text-sm font-medium mb-2">Fornecedor/Contato</label>
                        <select id="payable_contact_id" name="contact_id"
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                            <option value="">Selecione um contato</option>
                            @foreach($contacts as $contact)
                            <option value="{{ $contact->id }}">{{ $contact->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div id="payable_status_field" style="display: none;">
                        <label for="payable_status" class="block text-sm font-medium mb-2">Status</label>
                        <select id="payable_status" name="status"
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                            <option value="pending">Pendente</option>
                            <option value="paid">Paga</option>
                            <option value="overdue">Vencida</option>
                            <option value="cancelled">Cancelada</option>
                        </select>
                    </div>

                    <div id="payable_paid_at_field" style="display: none;">
                        <label for="payable_paid_at" class="block text-sm font-medium mb-2">Data de Pagamento</label>
                        <input type="date" id="payable_paid_at" name="paid_at"
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                    </div>

                    <div>
                        <label for="payable_notes" class="block text-sm font-medium mb-2">Observações</label>
                        <textarea id="payable_notes" name="notes" rows="3"
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                            placeholder="Observações adicionais..."></textarea>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t" style="border-color: rgb(var(--border));">
                    <button type="submit" class="flex-1 px-6 py-3 rounded-lg font-medium text-white transition-all hover:scale-105" style="background: linear-gradient(135deg, rgb(var(--primary)), rgb(var(--primary-dark))); box-shadow: 0 4px 6px -1px rgba(var(--primary), 0.3);">
                        <span id="payableSubmitText">Criar Conta</span>
                    </button>
                    <button type="button" onclick="closePayableModal()" class="flex-1 px-6 py-3 rounded-lg font-medium text-center transition-colors" style="background-color: rgba(var(--primary), 0.1); color: rgb(var(--primary));">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const payablesData = @json($payables->count() > 0 ? $payables->keyBy('id') : []);
const accountsData = @json($accounts->keyBy('id'));
const categoriesData = @json($categories->keyBy('id'));
const contactsData = @json($contacts->keyBy('id'));

function openPayableModal(payableId = null) {
    const modal = document.getElementById('payableModal');
    if (!modal) return;
    
    const form = document.getElementById('payableForm');
    const formMethod = document.getElementById('payableFormMethod');
    const title = document.getElementById('payableModalTitle');
    const submitText = document.getElementById('payableSubmitText');
    const statusField = document.getElementById('payable_status_field');
    const paidAtField = document.getElementById('payable_paid_at_field');
    
    if (payableId && payablesData[payableId]) {
        const payable = payablesData[payableId];
        title.textContent = 'Editar Conta a Pagar';
        submitText.textContent = 'Salvar Alterações';
        form.action = '{{ route("finance.payables.update", ":id") }}'.replace(':id', payableId);
        formMethod.innerHTML = '@method("PUT")';
        statusField.style.display = 'block';
        paidAtField.style.display = 'block';
        
        document.getElementById('payable_description').value = payable.description || '';
        document.getElementById('payable_amount').value = payable.amount || '';
        document.getElementById('payable_due_date').value = payable.due_date || '';
        document.getElementById('payable_type').value = payable.type || 'Pessoa Física (PF)';
        document.getElementById('payable_account_id').value = payable.account_id || '';
        document.getElementById('payable_category_id').value = payable.category_id || '';
        document.getElementById('payable_contact_id').value = payable.contact_id || '';
        document.getElementById('payable_status').value = payable.status || 'pending';
        document.getElementById('payable_paid_at').value = payable.paid_at || '';
        document.getElementById('payable_notes').value = payable.notes || '';
    } else {
        title.textContent = 'Nova Conta a Pagar';
        submitText.textContent = 'Criar Conta';
        form.action = '{{ route("finance.payables.store") }}';
        formMethod.innerHTML = '';
        statusField.style.display = 'none';
        paidAtField.style.display = 'none';
        form.reset();
    }
    
    modal.style.display = 'block';
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closePayableModal() {
    const modal = document.getElementById('payableModal');
    if (modal) {
        modal.style.display = 'none';
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closePayableModal();
    }
});
</script>
@endsection

