@extends('layouts.app')

@section('title', 'Meu Perfil - CLIVUS')
@section('page-title', 'Meu Perfil')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Informações do Usuário -->
    <div class="rounded-xl p-6" style="background-color: rgb(var(--card)); border: 1px solid rgb(var(--border)); box-shadow: var(--shadow);">
        <h2 class="text-2xl font-bold mb-6">Informações Pessoais</h2>
        
        <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium mb-2">Nome *</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                        class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                        style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                </div>
                
                <div>
                    <label for="email" class="block text-sm font-medium mb-2">Email *</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                        class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                        style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                </div>
                
                <div>
                    <label for="phone" class="block text-sm font-medium mb-2">Telefone</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}"
                        class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                        style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                        placeholder="(00) 00000-0000">
                </div>
                
                <div>
                    <label for="cpf_cnpj" class="block text-sm font-medium mb-2">CPF/CNPJ</label>
                    <input type="text" id="cpf_cnpj" name="cpf_cnpj" value="{{ old('cpf_cnpj', $user->cpf_cnpj) }}"
                        class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                        style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                        placeholder="000.000.000-00">
                </div>
            </div>
            
            <div class="border-t pt-6" style="border-color: rgb(var(--border));">
                <h3 class="text-lg font-semibold mb-4">Alterar Senha</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="current_password" class="block text-sm font-medium mb-2">Senha Atual</label>
                        <input type="password" id="current_password" name="current_password"
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                    </div>
                    
                    <div>
                        <label for="password" class="block text-sm font-medium mb-2">Nova Senha</label>
                        <input type="password" id="password" name="password"
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                    </div>
                    
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium mb-2">Confirmar Senha</label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                    </div>
                </div>
            </div>
            
            <div class="flex justify-end pt-4">
                <button type="submit" class="px-6 py-3 rounded-lg font-medium text-white transition-all hover:scale-105" style="background: linear-gradient(135deg, rgb(var(--primary)), rgb(var(--primary-dark))); box-shadow: 0 4px 6px -1px rgba(var(--primary), 0.3);">
                    Salvar Alterações
                </button>
            </div>
        </form>
    </div>

    <!-- Assinatura Ativa -->
    @if($activeSubscription)
    <div class="rounded-xl p-6" style="background-color: rgb(var(--card)); border: 1px solid rgb(var(--border)); box-shadow: var(--shadow);">
        <h2 class="text-2xl font-bold mb-6">Assinatura Ativa</h2>
        
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold">{{ $activeSubscription->plan->name }}</h3>
                    <p class="text-sm" style="color: rgb(var(--text-secondary));">{{ $activeSubscription->plan->description }}</p>
                </div>
                <div class="text-right">
                    <p class="text-2xl font-bold" style="color: rgb(var(--primary));">R$ {{ number_format($activeSubscription->plan->price, 2, ',', '.') }}</p>
                    <p class="text-sm" style="color: rgb(var(--text-secondary));">/{{ $activeSubscription->plan->billing_cycle === 'monthly' ? 'mês' : 'ano' }}</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 border-t" style="border-color: rgb(var(--border));">
                <div>
                    <p class="text-sm" style="color: rgb(var(--text-secondary));">Status</p>
                    <p class="font-semibold capitalize">{{ $activeSubscription->status }}</p>
                </div>
                @if($activeSubscription->next_billing_date)
                <div>
                    <p class="text-sm" style="color: rgb(var(--text-secondary));">Próxima Cobrança</p>
                    <p class="font-semibold">{{ $activeSubscription->next_billing_date->format('d/m/Y') }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

