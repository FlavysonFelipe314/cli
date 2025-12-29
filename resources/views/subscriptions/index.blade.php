@extends('layouts.app')

@section('title', 'Assinaturas - CLIVUS')
@section('page-title', 'Assinaturas')

@section('content')
<div class="space-y-6">
    @if($activeSubscription)
    <!-- Assinatura Ativa -->
    <div class="rounded-xl p-6" style="background: linear-gradient(135deg, rgba(var(--primary), 0.1), rgba(var(--primary-dark), 0.1)); border: 1px solid rgb(var(--primary));">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-2xl font-bold mb-2">Assinatura Ativa</h2>
                <p class="text-sm" style="color: rgb(var(--text-secondary));">{{ $activeSubscription->plan->description }}</p>
            </div>
            <span class="px-4 py-2 rounded-lg font-medium capitalize" style="background-color: rgba(34, 197, 94, 0.1); color: rgb(22, 163, 74);">
                {{ $activeSubscription->status }}
            </span>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <div>
                <p class="text-sm" style="color: rgb(var(--text-secondary));">Plano</p>
                <p class="text-lg font-semibold">{{ $activeSubscription->plan->name }}</p>
            </div>
            <div>
                <p class="text-sm" style="color: rgb(var(--text-secondary));">Valor</p>
                <p class="text-lg font-semibold">R$ {{ number_format($activeSubscription->plan->price, 2, ',', '.') }} /{{ $activeSubscription->plan->billing_cycle === 'monthly' ? 'mês' : 'ano' }}</p>
            </div>
            @if($activeSubscription->next_billing_date)
            <div>
                <p class="text-sm" style="color: rgb(var(--text-secondary));">Próxima Cobrança</p>
                <p class="text-lg font-semibold">{{ $activeSubscription->next_billing_date->format('d/m/Y') }}</p>
            </div>
            @endif
        </div>
        
        <form action="{{ route('subscriptions.cancel', $activeSubscription) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja cancelar sua assinatura?');">
            @csrf
            <button type="submit" class="px-4 py-2 rounded-lg font-medium transition-colors" style="background-color: rgba(239, 68, 68, 0.1); color: rgb(239, 68, 68);">
                Cancelar Assinatura
            </button>
        </form>
    </div>
    @endif

    <!-- Planos Disponíveis -->
    <div>
        <h2 class="text-2xl font-bold mb-6">{{ $activeSubscription ? 'Alterar Plano' : 'Escolha seu Plano' }}</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($plans as $plan)
            <div class="rounded-xl p-6 transition-all hover:scale-105" style="background-color: rgb(var(--card)); border: 1px solid rgb(var(--border)); box-shadow: var(--shadow);">
                <div class="text-center mb-6">
                    <h3 class="text-xl font-bold mb-2">{{ $plan->name }}</h3>
                    <p class="text-sm mb-4" style="color: rgb(var(--text-secondary));">{{ $plan->description }}</p>
                    <div class="mb-4">
                        <span class="text-3xl font-bold" style="color: rgb(var(--primary));">R$ {{ number_format($plan->price, 2, ',', '.') }}</span>
                        <span class="text-sm" style="color: rgb(var(--text-secondary));">/{{ $plan->billing_cycle === 'monthly' ? 'mês' : 'ano' }}</span>
                    </div>
                </div>
                
                @if($plan->features)
                <ul class="space-y-2 mb-6">
                    @foreach($plan->features as $feature)
                    <li class="flex items-center gap-2 text-sm">
                        <svg class="w-5 h-5" style="color: rgb(34, 197, 94);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        {{ $feature }}
                    </li>
                    @endforeach
                </ul>
                @endif
                
                @if(!$activeSubscription || $activeSubscription->plan_id !== $plan->id)
                <button onclick="openSubscribeModal({{ $plan->id }})" class="w-full px-6 py-3 rounded-lg font-medium text-white transition-all hover:scale-105" style="background: linear-gradient(135deg, rgb(var(--primary)), rgb(var(--primary-dark))); box-shadow: 0 4px 6px -1px rgba(var(--primary), 0.3);">
                    {{ $activeSubscription ? 'Alterar para este plano' : 'Assinar' }}
                </button>
                @else
                <div class="w-full px-6 py-3 rounded-lg font-medium text-center" style="background-color: rgba(var(--primary), 0.1); color: rgb(var(--primary));">
                    Plano Atual
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Modal de Assinatura -->
<div id="subscribeModal" class="fixed inset-0 z-50 hidden overflow-y-auto" style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity backdrop-blur-md" style="background: rgba(0, 0, 0, 0.4);" onclick="closeSubscribeModal()"></div>
        
        <div class="relative inline-block w-full max-w-md p-6 my-8 text-left align-middle transition-all transform rounded-xl shadow-xl z-50" style="background-color: #ffffff; border: 1px solid rgb(var(--border)); box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold" id="subscribeModalTitle">Assinar Plano</h3>
                <button onclick="closeSubscribeModal()" class="p-2 rounded-lg hover:bg-opacity-50 transition-colors" style="background-color: rgba(var(--primary), 0.1);">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form id="subscribeForm" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" id="plan_id" name="plan_id">
                
                <div>
                    <label for="billing_type" class="block text-sm font-medium mb-2">Forma de Pagamento *</label>
                    <select id="billing_type" name="billing_type" required
                        class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                        style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                        <option value="">Selecione</option>
                        <option value="CREDIT_CARD">Cartão de Crédito</option>
                        <option value="BOLETO">Boleto</option>
                        <option value="PIX">PIX</option>
                    </select>
                </div>
                
                <div>
                    <label for="cpf_cnpj" class="block text-sm font-medium mb-2">CPF/CNPJ *</label>
                    <input type="text" id="cpf_cnpj" name="cpf_cnpj" value="{{ old('cpf_cnpj', Auth::user()->cpf_cnpj) }}" required
                        class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                        style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                        placeholder="000.000.000-00">
                </div>
                
                <div>
                    <label for="phone" class="block text-sm font-medium mb-2">Telefone *</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone', Auth::user()->phone) }}" required
                        class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                        style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                        placeholder="(00) 00000-0000">
                </div>
                
                <div class="flex gap-3 pt-4 border-t" style="border-color: rgb(var(--border));">
                    <button type="submit" class="flex-1 px-6 py-3 rounded-lg font-medium text-white transition-all hover:scale-105" style="background: linear-gradient(135deg, rgb(var(--primary)), rgb(var(--primary-dark))); box-shadow: 0 4px 6px -1px rgba(var(--primary), 0.3);">
                        Assinar
                    </button>
                    <button type="button" onclick="closeSubscribeModal()" class="flex-1 px-6 py-3 rounded-lg font-medium text-center transition-colors" style="background-color: rgba(var(--primary), 0.1); color: rgb(var(--primary));">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const plansData = @json($plans->keyBy('id'));

function openSubscribeModal(planId) {
    const modal = document.getElementById('subscribeModal');
    const form = document.getElementById('subscribeForm');
    const plan = plansData[planId];
    
    if (!plan || !modal) return;
    
    document.getElementById('plan_id').value = planId;
    document.getElementById('subscribeModalTitle').textContent = `Assinar ${plan.name}`;
    form.action = '{{ route("subscriptions.subscribe", ":id") }}'.replace(':id', planId);
    
    modal.style.display = 'block';
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeSubscribeModal() {
    const modal = document.getElementById('subscribeModal');
    if (modal) {
        modal.style.display = 'none';
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeSubscribeModal();
    }
});
</script>
@endsection

