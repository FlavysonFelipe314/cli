@extends('layouts.app')

@section('title', 'Contas Bancárias - CLIVUS')
@section('page-title', 'Contas Bancárias')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold mb-1">Contas Bancárias</h2>
            <p class="text-sm" style="color: rgb(var(--text-secondary));">Gerencie suas contas bancárias e saldos</p>
        </div>
        <button type="button" onclick="event.preventDefault(); openAccountModal();" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-medium text-white transition-all hover:scale-105" style="background: linear-gradient(135deg, rgb(var(--primary)), rgb(var(--primary-dark))); box-shadow: 0 4px 6px -1px rgba(var(--primary), 0.3);">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span>Nova Conta</span>
        </button>
    </div>

    <!-- Accounts Grid -->
    @if($accounts->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($accounts as $account)
        <div class="rounded-xl p-6 transition-all hover:scale-[1.02] cursor-pointer" style="background-color: rgb(var(--card)); border: 1px solid rgb(var(--border)); box-shadow: var(--shadow);" onclick="event.stopPropagation(); openAccountModal({{ $account->id }});">
            <div class="flex items-start justify-between mb-4">
                <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background: linear-gradient(135deg, rgba(var(--primary), 0.2), rgba(var(--primary-dark), 0.2));">
                    <svg class="w-6 h-6" style="color: rgb(var(--primary));" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                </div>
                <div class="flex gap-2" onclick="event.stopPropagation();">
                    <button type="button" onclick="event.stopPropagation(); openAccountModal({{ $account->id }});" class="p-2 rounded-lg hover:bg-opacity-50 transition-colors" style="background-color: rgba(var(--primary), 0.1);">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </button>
                    <form action="{{ route('finance.accounts.destroy', $account) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja remover esta conta?');">
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
            
            <h3 class="text-lg font-semibold mb-2">{{ $account->name }}</h3>
            
            <div class="space-y-2 mb-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm" style="color: rgb(var(--text-secondary));">Saldo</span>
                    <span class="text-xl font-bold" style="color: rgb(var(--primary));">R$ {{ number_format($account->balance, 2, ',', '.') }}</span>
                </div>
                @if($account->type)
                <div class="flex items-center justify-between text-sm">
                    <span style="color: rgb(var(--text-secondary));">Tipo</span>
                    <span>{{ $account->type }}</span>
                </div>
                @endif
                @if($account->bank)
                <div class="flex items-center justify-between text-sm">
                    <span style="color: rgb(var(--text-secondary));">Banco</span>
                    <span class="capitalize">{{ $account->bank }}</span>
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="text-center py-12 rounded-xl" style="background-color: rgb(var(--card)); border: 1px solid rgb(var(--border));">
        <svg class="w-16 h-16 mx-auto mb-4 opacity-50" style="color: rgb(var(--text-secondary));" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
        </svg>
        <h3 class="text-lg font-semibold mb-2">Nenhuma conta cadastrada</h3>
        <p class="text-sm mb-4" style="color: rgb(var(--text-secondary));">Comece criando sua primeira conta bancária</p>
        <button type="button" onclick="event.preventDefault(); openAccountModal();" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-medium text-white transition-all" style="background: linear-gradient(135deg, rgb(var(--primary)), rgb(var(--primary-dark)));">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span>Criar Conta</span>
        </button>
    </div>
    @endif
</div>

<!-- Modal de Conta -->
<div id="accountModal" class="fixed inset-0 z-50 hidden overflow-y-auto" style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <!-- Overlay com efeito blur dark glass -->
        <div class="fixed inset-0 transition-opacity backdrop-blur-md" style="background: rgba(0, 0, 0, 0.4);" onclick="closeAccountModal()"></div>

        <!-- Modal -->
        <div class="relative inline-block w-full max-w-2xl p-6 my-8 text-left align-middle transition-all transform rounded-xl shadow-xl z-50" style="background-color: #ffffff; border: 1px solid rgb(var(--border)); box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold" id="accountModalTitle">Nova Conta</h3>
                <button onclick="closeAccountModal()" class="p-2 rounded-lg hover:bg-opacity-50 transition-colors" style="background-color: rgba(var(--primary), 0.1);">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form id="accountForm" method="POST" class="space-y-6">
                @csrf
                <div id="accountFormMethod"></div>
                
                @if($errors->any())
                <div class="p-4 rounded-lg mb-4" style="background-color: rgba(239, 68, 68, 0.1); border: 1px solid rgb(239, 68, 68);">
                    <ul class="list-disc list-inside text-sm" style="color: rgb(220, 38, 38);">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-h-[60vh] overflow-y-auto pr-2">
                    <div class="md:col-span-2">
                        <label for="account_name" class="block text-sm font-medium mb-2">Nome da Conta *</label>
                        <input type="text" id="account_name" name="name" required
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                            placeholder="Ex: Conta Corrente Principal">
                    </div>
                    
                    <div>
                        <label for="account_type" class="block text-sm font-medium mb-2">Tipo</label>
                        <select id="account_type" name="type"
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                            <option value="Conta Corrente">Conta Corrente</option>
                            <option value="Poupança">Poupança</option>
                            <option value="Conta Empresarial">Conta Empresarial</option>
                            <option value="Investimento">Investimento</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="account_balance" class="block text-sm font-medium mb-2">Saldo Inicial</label>
                        <input type="number" id="account_balance" name="balance" step="0.01" min="0" value="0"
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                            placeholder="0.00">
                    </div>
                    
                    <div>
                        <label for="account_bank" class="block text-sm font-medium mb-2">Banco</label>
                        <input type="text" id="account_bank" name="bank"
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                            placeholder="Ex: Nubank, Itaú">
                    </div>
                    
                    <div>
                        <label for="account_agency" class="block text-sm font-medium mb-2">Agência</label>
                        <input type="text" id="account_agency" name="agency"
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                            placeholder="0000">
                    </div>
                    
                    <div>
                        <label for="account_account_number" class="block text-sm font-medium mb-2">Número da Conta</label>
                        <input type="text" id="account_account_number" name="account_number"
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                            placeholder="00000-0">
                    </div>
                    
                    <div>
                        <label for="account_holder" class="block text-sm font-medium mb-2">Titular</label>
                        <input type="text" id="account_holder" name="holder"
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                            placeholder="Nome do titular">
                    </div>
                    
                    <div>
                        <label for="account_cpf" class="block text-sm font-medium mb-2">CPF</label>
                        <input type="text" id="account_cpf" name="cpf"
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                            placeholder="000.000.000-00">
                    </div>
                    
                    <div class="md:col-span-2">
                        <label for="account_pix_key" class="block text-sm font-medium mb-2">Chave PIX</label>
                        <input type="text" id="account_pix_key" name="pix_key"
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                            placeholder="CPF, Email, Telefone ou Chave Aleatória">
                    </div>
                </div>
                
                <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t" style="border-color: rgb(var(--border));">
                    <button type="submit" class="flex-1 px-6 py-3 rounded-lg font-medium text-white transition-all hover:scale-105" style="background: linear-gradient(135deg, rgb(var(--primary)), rgb(var(--primary-dark))); box-shadow: 0 4px 6px -1px rgba(var(--primary), 0.3);">
                        <span id="accountSubmitText">Criar Conta</span>
                    </button>
                    <button type="button" onclick="closeAccountModal()" class="flex-1 px-6 py-3 rounded-lg font-medium text-center transition-colors" style="background-color: rgba(var(--primary), 0.1); color: rgb(var(--primary));">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const accountsData = @json($accounts->keyBy('id'));

// Abrir modal automaticamente se houver erros de validação
@if($errors->any())
document.addEventListener('DOMContentLoaded', function() {
    // Preencher campos com valores antigos se houver erros
    @if(old('name'))
    document.getElementById('account_name').value = @json(old('name'));
    @endif
    @if(old('type'))
    document.getElementById('account_type').value = @json(old('type'));
    @endif
    @if(old('balance'))
    document.getElementById('account_balance').value = @json(old('balance'));
    @endif
    @if(old('bank'))
    document.getElementById('account_bank').value = @json(old('bank'));
    @endif
    @if(old('agency'))
    document.getElementById('account_agency').value = @json(old('agency'));
    @endif
    @if(old('account_number'))
    document.getElementById('account_account_number').value = @json(old('account_number'));
    @endif
    @if(old('holder'))
    document.getElementById('account_holder').value = @json(old('holder'));
    @endif
    @if(old('cpf'))
    document.getElementById('account_cpf').value = @json(old('cpf'));
    @endif
    @if(old('pix_key'))
    document.getElementById('account_pix_key').value = @json(old('pix_key'));
    @endif
    
    // Verificar se é edição ou criação
    @php
        $accountId = request()->route('account') ? request()->route('account')->id : null;
    @endphp
    @if($accountId)
    openAccountModal({{ $accountId }});
    @else
    openAccountModal();
    @endif
});
@endif

function openAccountModal(accountId = null) {
    const modal = document.getElementById('accountModal');
    if (!modal) return;
    
    const form = document.getElementById('accountForm');
    const formMethod = document.getElementById('accountFormMethod');
    const title = document.getElementById('accountModalTitle');
    const submitText = document.getElementById('accountSubmitText');
    
    if (accountId && accountsData[accountId]) {
        const account = accountsData[accountId];
        title.textContent = 'Editar Conta';
        submitText.textContent = 'Salvar Alterações';
        form.action = '{{ route("finance.accounts.update", ":id") }}'.replace(':id', accountId);
        formMethod.innerHTML = '@method("PUT")';
        
        document.getElementById('account_name').value = account.name || '';
        document.getElementById('account_type').value = account.type || 'Conta Corrente';
        document.getElementById('account_balance').value = account.balance || 0;
        document.getElementById('account_bank').value = account.bank || '';
        document.getElementById('account_agency').value = account.agency || '';
        document.getElementById('account_account_number').value = account.account_number || '';
        document.getElementById('account_holder').value = account.holder || '';
        document.getElementById('account_cpf').value = account.cpf || '';
        document.getElementById('account_pix_key').value = account.pix_key || '';
    } else {
        title.textContent = 'Nova Conta';
        submitText.textContent = 'Criar Conta';
        form.action = '{{ route("finance.accounts.store") }}';
        formMethod.innerHTML = '';
        form.reset();
    }
    
    modal.style.display = 'block';
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeAccountModal() {
    const modal = document.getElementById('accountModal');
    if (modal) {
        modal.style.display = 'none';
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
}

// Fechar modal com ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeAccountModal();
    }
});
</script>
@endsection
