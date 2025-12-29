@extends('layouts.app')

@section('title', 'Nova Transação - CLIVUS')
@section('page-title', 'Nova Transação')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="rounded-xl p-6 lg:p-8" style="background-color: rgb(var(--card)); border: 1px solid rgb(var(--border)); box-shadow: var(--shadow);">
        <h2 class="text-2xl font-bold mb-6">Criar Nova Transação</h2>
        
        <form action="{{ route('finance.transactions.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="space-y-6">
                <div>
                    <label for="account_id" class="block text-sm font-medium mb-2">Conta *</label>
                    <select id="account_id" name="account_id" required
                        class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                        style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                        <option value="">Selecione uma conta</option>
                        @foreach($accounts as $account)
                        <option value="{{ $account->id }}" {{ old('account_id') == $account->id ? 'selected' : '' }}>
                            {{ $account->name }} - R$ {{ number_format($account->balance, 2, ',', '.') }}
                        </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label for="description" class="block text-sm font-medium mb-2">Descrição *</label>
                    <input type="text" id="description" name="description" value="{{ old('description') }}" required
                        class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                        style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                        placeholder="Ex: Salário, Aluguel, Supermercado">
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="type" class="block text-sm font-medium mb-2">Tipo *</label>
                        <select id="type" name="type" required
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                            <option value="">Selecione o tipo</option>
                            <option value="receita" {{ old('type') == 'receita' ? 'selected' : '' }}>Receita</option>
                            <option value="despesa" {{ old('type') == 'despesa' ? 'selected' : '' }}>Despesa</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="amount" class="block text-sm font-medium mb-2">Valor *</label>
                        <input type="number" id="amount" name="amount" value="{{ old('amount') }}" step="0.01" min="0.01" required
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                            placeholder="0.00">
                    </div>
                </div>
                
                <div>
                    <label for="date" class="block text-sm font-medium mb-2">Data *</label>
                    <input type="date" id="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required
                        class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                        style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                </div>
                
                <div>
                    <label for="notes" class="block text-sm font-medium mb-2">Observações</label>
                    <textarea id="notes" name="notes" rows="3"
                        class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2 resize-none"
                        style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                        placeholder="Adicione observações sobre esta transação...">{{ old('notes') }}</textarea>
                </div>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-3 pt-4">
                <button type="submit" class="flex-1 px-6 py-3 rounded-lg font-medium text-white transition-all hover:scale-105" style="background: linear-gradient(135deg, rgb(var(--primary)), rgb(var(--primary-dark))); box-shadow: 0 4px 6px -1px rgba(var(--primary), 0.3);">
                    Criar Transação
                </button>
                <a href="{{ route('finance.transactions.index') }}" class="flex-1 px-6 py-3 rounded-lg font-medium text-center transition-colors" style="background-color: rgba(var(--primary), 0.1); color: rgb(var(--primary));">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

