@extends('layouts.app')

@section('title', 'Loja de Módulos - CLIVUS')
@section('page-title', 'Loja de Módulos')

@section('content')
<div class="space-y-6">
    <div>
        <h2 class="text-2xl font-bold mb-2">Loja de Módulos</h2>
        <p class="text-sm" style="color: rgb(var(--text-secondary));">Adicione módulos adicionais ao seu plano</p>
    </div>

    @if($userModules->count() > 0)
    <div class="mb-6">
        <h3 class="text-lg font-semibold mb-4">Módulos Adquiridos</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($userModules as $userModule)
            <div class="rounded-xl p-6" style="background-color: rgb(var(--card)); border: 1px solid rgb(var(--border)); box-shadow: var(--shadow);">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h4 class="text-lg font-bold">{{ $userModule->module->name }}</h4>
                        <p class="text-sm" style="color: rgb(var(--text-secondary));">{{ $userModule->module->description ?? 'Sem descrição' }}</p>
                    </div>
                    <span class="px-2 py-1 rounded text-xs" style="background-color: rgba(34, 197, 94, 0.1); color: rgb(22, 163, 74);">Ativo</span>
                </div>
                <div class="text-sm" style="color: rgb(var(--text-secondary));">
                    Adquirido em: {{ $userModule->purchased_at->format('d/m/Y') }}
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    @if($availableModules->count() > 0)
    <div>
        <h3 class="text-lg font-semibold mb-4">Módulos Disponíveis</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($availableModules as $module)
            <div class="rounded-xl p-6" style="background-color: rgb(var(--card)); border: 1px solid rgb(var(--border)); box-shadow: var(--shadow);">
                <div class="mb-4">
                    <h4 class="text-lg font-bold mb-2">{{ $module->name }}</h4>
                    <p class="text-sm mb-4" style="color: rgb(var(--text-secondary));">{{ $module->description ?? 'Sem descrição' }}</p>
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-xs px-2 py-1 rounded" style="background-color: rgba(var(--primary), 0.1); color: rgb(var(--primary));">
                                {{ ucfirst($module->category) }}
                            </span>
                        </div>
                        <div class="text-right">
                            <p class="text-2xl font-bold" style="color: rgb(var(--primary));">R$ {{ number_format($module->price, 2, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
                
                <form action="{{ route('modules.purchase', $module) }}" method="POST" id="purchase-form-{{ $module->id }}">
                    @csrf
                    <div class="mb-3">
                        <label class="block text-sm font-medium mb-2">Forma de Pagamento</label>
                        <select name="billing_type" required class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2" style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                            <option value="PIX">PIX</option>
                            <option value="CREDIT_CARD">Cartão de Crédito</option>
                        </select>
                    </div>
                    <button type="submit" class="w-full px-4 py-2 rounded-lg font-medium text-white transition-all hover:scale-105" style="background: linear-gradient(135deg, rgb(var(--primary)), rgb(var(--primary-dark)));">
                        Adicionar ao Plano
                    </button>
                </form>
            </div>
            @endforeach
        </div>
    </div>
    @else
    <div class="rounded-xl p-12 text-center" style="background-color: rgb(var(--card)); border: 1px solid rgb(var(--border)); box-shadow: var(--shadow);">
        <svg class="w-16 h-16 mx-auto mb-4" style="color: rgb(var(--text-secondary));" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
        </svg>
        <p class="text-lg font-medium mb-2">Nenhum módulo disponível</p>
        <p class="text-sm" style="color: rgb(var(--text-secondary));">Todos os módulos já foram adicionados ao seu plano ou não há módulos cadastrados.</p>
    </div>
    @endif
</div>
@endsection

