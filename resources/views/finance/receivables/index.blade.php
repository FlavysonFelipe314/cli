@extends('layouts.app')

@section('title', 'Contas a Receber - CLIVUS')
@section('page-title', 'Contas a Receber')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold mb-1">Contas a Receber</h2>
            <p class="text-sm" style="color: rgb(var(--text-secondary));">Gerencie suas contas a receber</p>
        </div>
        <button type="button" onclick="event.preventDefault(); openReceivableModal();" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-medium text-white transition-all hover:scale-105" style="background: linear-gradient(135deg, rgb(var(--primary)), rgb(var(--primary-dark))); box-shadow: 0 4px 6px -1px rgba(var(--primary), 0.3);">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span>Nova Conta a Receber</span>
        </button>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="rounded-xl p-6" style="background: linear-gradient(135deg, rgba(34, 197, 94, 0.1), rgba(22, 163, 74, 0.1)); border: 1px solid rgb(34, 197, 94);">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium" style="color: rgb(22, 163, 74);">Pendentes</span>
                <svg class="w-5 h-5" style="color: rgb(34, 197, 94);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <p class="text-2xl font-bold" style="color: rgb(22, 163, 74);">R$ {{ number_format($summary['pending'], 2, ',', '.') }}</p>
        </div>
        
        <div class="rounded-xl p-6" style="background: linear-gradient(135deg, rgba(34, 197, 94, 0.1), rgba(22, 163, 74, 0.1)); border: 1px solid rgb(34, 197, 94);">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium" style="color: rgb(22, 163, 74);">Recebidas</span>
                <svg class="w-5 h-5" style="color: rgb(34, 197, 94);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <p class="text-2xl font-bold" style="color: rgb(22, 163, 74);">R$ {{ number_format($summary['received'], 2, ',', '.') }}</p>
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
        <form method="GET" action="{{ route('finance.receivables.index') }}" class="space-y-4">
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
                        <option value="received" {{ request('status') == 'received' ? 'selected' : '' }}>Recebida</option>
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
                <a href="{{ route('finance.receivables.index') }}" class="px-4 py-2 rounded-lg font-medium text-center transition-colors" style="background-color: rgba(var(--primary), 0.1); color: rgb(var(--primary));">
                    Limpar
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Receivables List -->
    @if($receivables->count() > 0)
    <div class="space-y-3">
        @foreach($receivables as $receivable)
        <div class="rounded-xl p-4 transition-all hover:scale-[1.01]" style="background-color: rgb(var(--card)); border: 1px solid rgb(var(--border)); box-shadow: var(--shadow);">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex-1">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0" style="background: linear-gradient(135deg, rgba(34, 197, 94, 0.2), rgba(22, 163, 74, 0.2));">
                            <svg class="w-5 h-5" style="color: rgb(34, 197, 94);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-semibold mb-1 truncate">{{ $receivable->description }}</h3>
                            <div class="flex flex-wrap items-center gap-3 text-sm" style="color: rgb(var(--text-secondary));">
                                <span class="inline-flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    Vencimento: {{ $receivable->due_date->format('d/m/Y') }}
                                </span>
                                @if($receivable->account)
                                <span class="inline-flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                    </svg>
                                    {{ $receivable->account->name }}
                                </span>
                                @endif
                                @if($receivable->category)
                                <span class="px-2 py-1 rounded text-xs font-medium" style="background-color: rgba(var(--primary), 0.1); color: rgb(var(--primary));">
                                    {{ $receivable->category->name }}
                                </span>
                                @endif
                                @if($receivable->contact)
                                <span class="inline-flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    {{ $receivable->contact->name }}
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-right">
                        <p class="text-2xl font-bold" style="color: rgb(34, 197, 94);">R$ {{ number_format($receivable->amount, 2, ',', '.') }}</p>
                        <span class="px-2 py-1 rounded text-xs font-medium 
                            @if($receivable->status === 'received') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                            @elseif($receivable->status === 'overdue') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                            @elseif($receivable->status === 'cancelled') bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200
                            @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                            @endif">
                            @if($receivable->status === 'received') Recebida
                            @elseif($receivable->status === 'overdue') Vencida
                            @elseif($receivable->status === 'cancelled') Cancelada
                            @else Pendente
                            @endif
                        </span>
                    </div>
                    <div class="flex gap-2">
                        <button type="button" onclick="event.stopPropagation(); openReceivableModal({{ $receivable->id }});" class="p-2 rounded-lg hover:bg-opacity-50 transition-colors" style="background-color: rgba(var(--primary), 0.1);">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </button>
                        <form action="{{ route('finance.receivables.destroy', $receivable) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja remover esta conta a receber?');">
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
        <h3 class="text-lg font-semibold mb-2">Nenhuma conta a receber cadastrada</h3>
        <p class="text-sm mb-4" style="color: rgb(var(--text-secondary));">Comece criando sua primeira conta a receber</p>
        <button type="button" onclick="event.preventDefault(); openReceivableModal();" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-medium text-white transition-all" style="background: linear-gradient(135deg, rgb(var(--primary)), rgb(var(--primary-dark)));">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span>Criar Conta a Receber</span>
        </button>
    </div>
    @endif
</div>

<!-- Modal de Conta a Receber -->
<div id="receivableModal" class="fixed inset-0 z-50 hidden overflow-y-auto" style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity backdrop-blur-md" style="background: rgba(0, 0, 0, 0.4);" onclick="closeReceivableModal()"></div>

        <div class="relative inline-block w-full max-w-2xl p-6 my-8 text-left align-middle transition-all transform rounded-xl shadow-xl z-50" style="background-color: #ffffff; border: 1px solid rgb(var(--border)); box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold" id="receivableModalTitle">Nova Conta a Receber</h3>
                <button type="button" onclick="closeReceivableModal()" class="p-2 rounded-lg hover:bg-opacity-50 transition-colors" style="background-color: rgba(239, 68, 68, 0.1); color: rgb(239, 68, 68);">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form id="receivableForm" method="POST" class="space-y-6">
                @csrf
                <div id="receivableFormMethod"></div>
                
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
                        <label for="receivable_description" class="block text-sm font-medium mb-2">Descrição</label>
                        <input type="text" id="receivable_description" name="description" required
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                            placeholder="Descrição da conta a receber">
                    </div>

                    <div>
                        <label for="receivable_amount" class="block text-sm font-medium mb-2">Valor Total *</label>
                        <input type="number" id="receivable_amount" name="amount" step="0.01" min="0.01" required
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                            placeholder="0,00">
                    </div>

                    <div>
                        <label for="receivable_due_date" class="block text-sm font-medium mb-2">Data de Vencimento *</label>
                        <input type="date" id="receivable_due_date" name="due_date" required
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                    </div>

                    <div>
                        <label for="receivable_type" class="block text-sm font-medium mb-2">Tipo *</label>
                        <select id="receivable_type" name="type" required
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                            <option value="Pessoa Física (PF)">Pessoa Física (PF)</option>
                            <option value="Pessoa Jurídica (PJ)">Pessoa Jurídica (PJ)</option>
                        </select>
                    </div>

                    <div>
                        <label for="receivable_account_id" class="block text-sm font-medium mb-2">Conta</label>
                        <select id="receivable_account_id" name="account_id"
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                            <option value="">Selecione uma conta</option>
                            @foreach($accounts as $account)
                            <option value="{{ $account->id }}">{{ $account->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="receivable_category_id" class="block text-sm font-medium mb-2">Categoria</label>
                        <select id="receivable_category_id" name="category_id"
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                            <option value="">Selecione uma categoria</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="receivable_contact_id" class="block text-sm font-medium mb-2">Fornecedor/Contato</label>
                        <select id="receivable_contact_id" name="contact_id"
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                            <option value="">Selecione um contato</option>
                            @foreach($contacts as $contact)
                            <option value="{{ $contact->id }}">{{ $contact->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div id="receivable_status_field" style="display: none;">
                        <label for="receivable_status" class="block text-sm font-medium mb-2">Status</label>
                        <select id="receivable_status" name="status"
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                            <option value="pending">Pendente</option>
                            <option value="received">Recebida</option>
                            <option value="overdue">Vencida</option>
                            <option value="cancelled">Cancelada</option>
                        </select>
                    </div>

                    <div id="receivable_received_at_field" style="display: none;">
                        <label for="receivable_received_at" class="block text-sm font-medium mb-2">Data de Recebimento</label>
                        <input type="date" id="receivable_received_at" name="received_at"
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                    </div>

                    <div>
                        <label for="receivable_notes" class="block text-sm font-medium mb-2">Observações</label>
                        <textarea id="receivable_notes" name="notes" rows="3"
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                            placeholder="Observações adicionais..."></textarea>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t" style="border-color: rgb(var(--border));">
                    <button type="submit" class="flex-1 px-6 py-3 rounded-lg font-medium text-white transition-all hover:scale-105" style="background: linear-gradient(135deg, rgb(var(--primary)), rgb(var(--primary-dark))); box-shadow: 0 4px 6px -1px rgba(var(--primary), 0.3);">
                        <span id="receivableSubmitText">Criar Conta</span>
                    </button>
                    <button type="button" onclick="closeReceivableModal()" class="flex-1 px-6 py-3 rounded-lg font-medium text-center transition-colors" style="background-color: rgba(var(--primary), 0.1); color: rgb(var(--primary));">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const receivablesData = @json($receivables->count() > 0 ? $receivables->keyBy('id') : []);
const accountsData = @json($accounts->keyBy('id'));
const categoriesData = @json($categories->keyBy('id'));
const contactsData = @json($contacts->keyBy('id'));

function openReceivableModal(receivableId = null) {
    const modal = document.getElementById('receivableModal');
    if (!modal) return;
    
    const form = document.getElementById('receivableForm');
    const formMethod = document.getElementById('receivableFormMethod');
    const title = document.getElementById('receivableModalTitle');
    const submitText = document.getElementById('receivableSubmitText');
    const statusField = document.getElementById('receivable_status_field');
    const receivedAtField = document.getElementById('receivable_received_at_field');
    
    if (receivableId && receivablesData[receivableId]) {
        const receivable = receivablesData[receivableId];
        title.textContent = 'Editar Conta a Receber';
        submitText.textContent = 'Salvar Alterações';
        form.action = '{{ route("finance.receivables.update", ":id") }}'.replace(':id', receivableId);
        formMethod.innerHTML = '@method("PUT")';
        statusField.style.display = 'block';
        receivedAtField.style.display = 'block';
        
        document.getElementById('receivable_description').value = receivable.description || '';
        document.getElementById('receivable_amount').value = receivable.amount || '';
        document.getElementById('receivable_due_date').value = receivable.due_date || '';
        document.getElementById('receivable_type').value = receivable.type || 'Pessoa Física (PF)';
        document.getElementById('receivable_account_id').value = receivable.account_id || '';
        document.getElementById('receivable_category_id').value = receivable.category_id || '';
        document.getElementById('receivable_contact_id').value = receivable.contact_id || '';
        document.getElementById('receivable_status').value = receivable.status || 'pending';
        document.getElementById('receivable_received_at').value = receivable.received_at || '';
        document.getElementById('receivable_notes').value = receivable.notes || '';
    } else {
        title.textContent = 'Nova Conta a Receber';
        submitText.textContent = 'Criar Conta';
        form.action = '{{ route("finance.receivables.store") }}';
        formMethod.innerHTML = '';
        statusField.style.display = 'none';
        receivedAtField.style.display = 'none';
        form.reset();
    }
    
    modal.style.display = 'block';
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeReceivableModal() {
    const modal = document.getElementById('receivableModal');
    if (modal) {
        modal.style.display = 'none';
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeReceivableModal();
    }
});
</script>
@endsection

