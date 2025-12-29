@extends('layouts.app')

@section('title', 'Editar Conta - CLIVUS')
@section('page-title', 'Editar Conta')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="rounded-xl p-6 lg:p-8" style="background-color: rgb(var(--card)); border: 1px solid rgb(var(--border)); box-shadow: var(--shadow);">
        <h2 class="text-2xl font-bold mb-6">Editar Conta</h2>
        
        <form action="{{ route('finance.accounts.update', $account) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label for="name" class="block text-sm font-medium mb-2">Nome da Conta *</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $account->name) }}" required
                        class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                        style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                        placeholder="Ex: Conta Corrente Principal">
                </div>
                
                <div>
                    <label for="type" class="block text-sm font-medium mb-2">Tipo</label>
                    <select id="type" name="type"
                        class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                        style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                        <option value="Conta Corrente" {{ old('type', $account->type) == 'Conta Corrente' ? 'selected' : '' }}>Conta Corrente</option>
                        <option value="Poupança" {{ old('type', $account->type) == 'Poupança' ? 'selected' : '' }}>Poupança</option>
                        <option value="Conta Empresarial" {{ old('type', $account->type) == 'Conta Empresarial' ? 'selected' : '' }}>Conta Empresarial</option>
                        <option value="Investimento" {{ old('type', $account->type) == 'Investimento' ? 'selected' : '' }}>Investimento</option>
                    </select>
                </div>
                
                <div>
                    <label for="balance" class="block text-sm font-medium mb-2">Saldo</label>
                    <input type="number" id="balance" name="balance" value="{{ old('balance', $account->balance) }}" step="0.01" min="0"
                        class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                        style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                        placeholder="0.00">
                </div>
                
                <div>
                    <label for="bank" class="block text-sm font-medium mb-2">Banco</label>
                    <input type="text" id="bank" name="bank" value="{{ old('bank', $account->bank) }}"
                        class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                        style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                        placeholder="Ex: Nubank, Itaú">
                </div>
                
                <div>
                    <label for="agency" class="block text-sm font-medium mb-2">Agência</label>
                    <input type="text" id="agency" name="agency" value="{{ old('agency', $account->agency) }}"
                        class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                        style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                        placeholder="0000">
                </div>
                
                <div>
                    <label for="account_number" class="block text-sm font-medium mb-2">Número da Conta</label>
                    <input type="text" id="account_number" name="account_number" value="{{ old('account_number', $account->account_number) }}"
                        class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                        style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                        placeholder="00000-0">
                </div>
                
                <div>
                    <label for="holder" class="block text-sm font-medium mb-2">Titular</label>
                    <input type="text" id="holder" name="holder" value="{{ old('holder', $account->holder) }}"
                        class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                        style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                        placeholder="Nome do titular">
                </div>
                
                <div>
                    <label for="cpf" class="block text-sm font-medium mb-2">CPF</label>
                    <input type="text" id="cpf" name="cpf" value="{{ old('cpf', $account->cpf) }}"
                        class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                        style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                        placeholder="000.000.000-00">
                </div>
                
                <div class="md:col-span-2">
                    <label for="pix_key" class="block text-sm font-medium mb-2">Chave PIX</label>
                    <input type="text" id="pix_key" name="pix_key" value="{{ old('pix_key', $account->pix_key) }}"
                        class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                        style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                        placeholder="CPF, Email, Telefone ou Chave Aleatória">
                </div>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-3 pt-4">
                <button type="submit" class="flex-1 px-6 py-3 rounded-lg font-medium text-white transition-all hover:scale-105" style="background: linear-gradient(135deg, rgb(var(--primary)), rgb(var(--primary-dark))); box-shadow: 0 4px 6px -1px rgba(var(--primary), 0.3);">
                    Salvar Alterações
                </button>
                <a href="{{ route('finance.accounts.index') }}" class="flex-1 px-6 py-3 rounded-lg font-medium text-center transition-colors" style="background-color: rgba(var(--primary), 0.1); color: rgb(var(--primary));">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

