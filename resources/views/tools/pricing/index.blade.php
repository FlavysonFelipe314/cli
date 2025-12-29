@extends('layouts.app')

@section('title', 'Calculadora de Precificação - CLIVUS')
@section('page-title', 'Calculadora de Precificação')

@section('content')
<div class="space-y-6">
    <!-- Tipo de Operação -->
    <div class="rounded-xl p-6" style="background-color: rgb(var(--card)); border: 1px solid rgb(var(--border)); box-shadow: var(--shadow);">
        <h3 class="text-lg font-semibold mb-4">Tipo de Operação</h3>
        <div class="flex gap-4">
            <button type="button" id="btn-commerce" onclick="switchOperationType('commerce')" class="flex-1 px-6 py-4 rounded-lg font-medium transition-all hover:scale-105" style="background: linear-gradient(135deg, rgb(var(--primary)), rgb(var(--primary-dark))); color: white; box-shadow: 0 4px 6px -1px rgba(var(--primary), 0.3);">
                Comércio
            </button>
            <button type="button" id="btn-service" onclick="switchOperationType('service')" class="flex-1 px-6 py-4 rounded-lg font-medium transition-all hover:scale-105" style="background-color: rgba(var(--primary), 0.1); color: rgb(var(--primary));">
                Prestação de Serviços
            </button>
        </div>
    </div>

    @if(!$allocation)
    <div class="rounded-xl p-4" style="background-color: rgba(251, 191, 36, 0.1); border: 1px solid rgb(251, 191, 36);">
        <div class="flex items-start gap-3">
            <svg class="w-5 h-5 flex-shrink-0 mt-0.5" style="color: rgb(251, 191, 36);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            <div>
                <p class="text-sm" style="color: rgb(184, 132, 0);">
                    Configure o <a href="{{ route('finance.indirect-costs.index') }}" class="underline font-medium">Rateio de Custos Indiretos</a> para cálculos mais precisos.
                </p>
            </div>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Formulário -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Dados do Produto/Serviço -->
            <div id="commerce-section" class="rounded-xl p-6" style="background-color: rgb(var(--card)); border: 1px solid rgb(var(--border)); box-shadow: var(--shadow);">
                <h3 class="text-lg font-semibold mb-4">Dados do Produto</h3>
                <div class="space-y-4">
                    <div>
                        <label for="product_name" class="block text-sm font-medium mb-2">Nome do Produto</label>
                        <input type="text" id="product_name" placeholder="Ex: Camisa Polo"
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="purchase_price" class="block text-sm font-medium mb-2">Preço de Compra (R$)</label>
                            <input type="number" id="purchase_price" step="0.01" min="0" value="0" onchange="calculatePricing()"
                                class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                                style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                        </div>
                        <div>
                            <label for="freight" class="block text-sm font-medium mb-2">Frete na Compra (R$)</label>
                            <input type="number" id="freight" step="0.01" min="0" value="0" onchange="calculatePricing()"
                                class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                                style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                        </div>
                        <div>
                            <label for="input_taxes" class="block text-sm font-medium mb-2">Impostos de Entrada (R$)</label>
                            <input type="number" id="input_taxes" step="0.01" min="0" value="0" onchange="calculatePricing()"
                                class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                                style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                        </div>
                        <div>
                            <label for="packaging" class="block text-sm font-medium mb-2">Embalagem Específica (R$)</label>
                            <input type="number" id="packaging" step="0.01" min="0" value="0" onchange="calculatePricing()"
                                class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                                style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                        </div>
                        <div>
                            <label for="other_direct_costs" class="block text-sm font-medium mb-2">Outros Custos Diretos (R$)</label>
                            <input type="number" id="other_direct_costs" step="0.01" min="0" value="0" onchange="calculatePricing()"
                                class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                                style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dados do Serviço -->
            <div id="service-section" class="rounded-xl p-6 hidden" style="background-color: rgb(var(--card)); border: 1px solid rgb(var(--border)); box-shadow: var(--shadow);">
                <h3 class="text-lg font-semibold mb-4">Dados do Serviço</h3>
                <div class="space-y-4">
                    <div>
                        <label for="service_name" class="block text-sm font-medium mb-2">Nome do Serviço</label>
                        <input type="text" id="service_name" placeholder="Ex: Consultoria Financeira"
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                    </div>

                    <!-- Horas por Função -->
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="font-medium">Horas por Função</h4>
                            <button type="button" onclick="addServiceHour()" class="px-3 py-1 rounded-lg text-sm font-medium text-white transition-all" style="background: linear-gradient(135deg, rgb(var(--primary)), rgb(var(--primary-dark)));">
                                + Adicionar
                            </button>
                        </div>
                        <div id="service-hours-container" class="space-y-2">
                            <p class="text-sm" style="color: rgb(var(--text-secondary));">Clique em "Adicionar" para incluir horas de trabalho</p>
                        </div>
                    </div>

                    <!-- Insumos -->
                    <div>
                        <h4 class="font-medium mb-3">Insumos</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="transport" class="block text-sm font-medium mb-2">Deslocamento (R$)</label>
                                <input type="number" id="transport" step="0.01" min="0" value="0" onchange="calculatePricing()"
                                    class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                                    style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                            </div>
                            <div>
                                <label for="accommodation" class="block text-sm font-medium mb-2">Hospedagem (R$)</label>
                                <input type="number" id="accommodation" step="0.01" min="0" value="0" onchange="calculatePricing()"
                                    class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                                    style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                            </div>
                            <div>
                                <label for="specific_materials" class="block text-sm font-medium mb-2">Materiais Específicos (R$)</label>
                                <input type="number" id="specific_materials" step="0.01" min="0" value="0" onchange="calculatePricing()"
                                    class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                                    style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                            </div>
                            <div>
                                <label for="other_inputs" class="block text-sm font-medium mb-2">Outros (R$)</label>
                                <input type="number" id="other_inputs" step="0.01" min="0" value="0" onchange="calculatePricing()"
                                    class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                                    style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Impostos e Margem -->
            <div class="rounded-xl p-6" style="background-color: rgb(var(--card)); border: 1px solid rgb(var(--border)); box-shadow: var(--shadow);">
                <h3 class="text-lg font-semibold mb-4">Impostos e Margem</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="tax_rate" class="block text-sm font-medium mb-2">Alíquota Efetiva de Impostos (%)</label>
                        <input type="number" id="tax_rate" step="0.01" min="0" max="100" value="15" onchange="calculatePricing()"
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                    </div>
                    <div>
                        <label for="desired_margin" class="block text-sm font-medium mb-2">Margem Desejada (%)</label>
                        <input type="number" id="desired_margin" step="0.01" min="0" max="100" value="30" onchange="calculatePricing()"
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                    </div>
                </div>
            </div>
        </div>

        <!-- Resultado -->
        <div class="space-y-6">
            <div class="rounded-xl p-6" style="background-color: rgb(var(--card)); border: 1px solid rgb(var(--border)); box-shadow: var(--shadow);">
                <h3 class="text-lg font-semibold mb-4">Resultado da Precificação</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span style="color: rgb(var(--text-secondary));">Custos Diretos:</span>
                        <span id="display_direct_costs" class="font-medium">R$ 0,00</span>
                    </div>
                    <div class="flex justify-between">
                        <span style="color: rgb(var(--text-secondary));">Custos Indiretos (rateio):</span>
                        <span id="display_indirect_costs" class="font-medium">R$ 0,00</span>
                    </div>
                    <div class="flex justify-between">
                        <span style="color: rgb(var(--text-secondary));">Impostos sobre Venda:</span>
                        <span id="display_taxes" class="font-medium">R$ 0,00</span>
                    </div>
                    <div class="flex justify-between pt-3 border-t" style="border-color: rgb(var(--border));">
                        <span class="font-semibold">Custo Total:</span>
                        <span id="display_total_cost" class="font-bold">R$ 0,00</span>
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t space-y-4" style="border-color: rgb(var(--border));">
                    <div>
                        <p class="text-sm mb-1" style="color: rgb(var(--text-secondary));">Preço Mínimo (sem margem):</p>
                        <p id="display_min_price" class="text-xl font-bold">R$ 0,00</p>
                    </div>
                    <div>
                        <p class="text-sm mb-1" style="color: rgb(var(--text-secondary));">Preço Ideal Sugerido:</p>
                        <p id="display_ideal_price" class="text-3xl font-bold" style="color: rgb(34, 197, 94);">R$ 0,00</p>
                    </div>
                    <div class="pt-4 border-t" style="border-color: rgb(var(--border));">
                        <div class="flex justify-between mb-2">
                            <span style="color: rgb(var(--text-secondary));">Lucro:</span>
                            <span id="display_profit" class="font-medium">R$ 0,00</span>
                        </div>
                        <div class="flex justify-between">
                            <span style="color: rgb(var(--text-secondary));">Margem Real:</span>
                            <span id="display_real_margin" class="font-medium">0%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let currentOperationType = 'commerce';
let serviceHourCount = 0;

function switchOperationType(type) {
    currentOperationType = type;
    const commerceSection = document.getElementById('commerce-section');
    const serviceSection = document.getElementById('service-section');
    const btnCommerce = document.getElementById('btn-commerce');
    const btnService = document.getElementById('btn-service');

    if (type === 'commerce') {
        commerceSection.classList.remove('hidden');
        serviceSection.classList.add('hidden');
        btnCommerce.style.background = 'linear-gradient(135deg, rgb(var(--primary)), rgb(var(--primary-dark)))';
        btnCommerce.style.color = 'white';
        btnService.style.background = 'rgba(var(--primary), 0.1)';
        btnService.style.color = 'rgb(var(--primary))';
    } else {
        commerceSection.classList.add('hidden');
        serviceSection.classList.remove('hidden');
        btnService.style.background = 'linear-gradient(135deg, rgb(var(--primary)), rgb(var(--primary-dark)))';
        btnService.style.color = 'white';
        btnCommerce.style.background = 'rgba(var(--primary), 0.1)';
        btnCommerce.style.color = 'rgb(var(--primary))';
    }
    calculatePricing();
}

function addServiceHour() {
    serviceHourCount++;
    const container = document.getElementById('service-hours-container');
    if (container.querySelector('p')) {
        container.innerHTML = '';
    }
    const row = document.createElement('div');
    row.className = 'service-hour-row grid grid-cols-12 gap-2 items-end';
    row.innerHTML = `
        <div class="col-span-4">
            <input type="text" name="function_name[]" placeholder="Função" value=""
                class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
        </div>
        <div class="col-span-4">
            <input type="number" name="hourly_rate[]" step="0.01" min="0" placeholder="R$/Hora" value="0" onchange="calculatePricing()"
                class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
        </div>
        <div class="col-span-3">
            <input type="number" name="hours[]" step="0.01" min="0" placeholder="Horas" value="0" onchange="calculatePricing()"
                class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
        </div>
        <div class="col-span-1">
            <button type="button" onclick="removeServiceHour(this)" class="w-full p-2 rounded-lg hover:bg-opacity-50 transition-colors" style="background-color: rgba(239, 68, 68, 0.1); color: rgb(239, 68, 68);">
                <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
            </button>
        </div>
    `;
    container.appendChild(row);
}

function removeServiceHour(btn) {
    btn.closest('.service-hour-row').remove();
    calculatePricing();
}

function calculatePricing() {
    let directCosts = 0;
    
    if (currentOperationType === 'commerce') {
        directCosts = (parseFloat(document.getElementById('purchase_price').value) || 0)
            + (parseFloat(document.getElementById('freight').value) || 0)
            + (parseFloat(document.getElementById('input_taxes').value) || 0)
            + (parseFloat(document.getElementById('packaging').value) || 0)
            + (parseFloat(document.getElementById('other_direct_costs').value) || 0);
    } else {
        directCosts = (parseFloat(document.getElementById('transport').value) || 0)
            + (parseFloat(document.getElementById('accommodation').value) || 0)
            + (parseFloat(document.getElementById('specific_materials').value) || 0)
            + (parseFloat(document.getElementById('other_inputs').value) || 0);
        
        // Somar horas de serviço
        const hourRows = document.querySelectorAll('.service-hour-row');
        hourRows.forEach(row => {
            const rate = parseFloat(row.querySelector('input[name="hourly_rate[]"]').value) || 0;
            const hours = parseFloat(row.querySelector('input[name="hours[]"]').value) || 0;
            directCosts += (rate * hours);
        });
    }

    const indirectCosts = 0; // Será calculado com base no rateio
    const taxRate = parseFloat(document.getElementById('tax_rate').value) || 15;
    const desiredMargin = parseFloat(document.getElementById('desired_margin').value) || 30;

    // Calcular preço mínimo
    const totalCost = directCosts + indirectCosts;
    const minPrice = totalCost / (1 - (taxRate / 100));

    // Calcular preço ideal
    const idealPrice = minPrice / (1 - (desiredMargin / 100));

    const taxes = (idealPrice * taxRate) / 100;
    const profit = idealPrice - totalCost - taxes;
    const realMargin = idealPrice > 0 ? ((profit / idealPrice) * 100).toFixed(2) : 0;

    // Atualizar displays
    document.getElementById('display_direct_costs').textContent = formatCurrency(directCosts);
    document.getElementById('display_indirect_costs').textContent = formatCurrency(indirectCosts);
    document.getElementById('display_taxes').textContent = formatCurrency(taxes);
    document.getElementById('display_total_cost').textContent = formatCurrency(totalCost);
    document.getElementById('display_min_price').textContent = formatCurrency(minPrice);
    document.getElementById('display_ideal_price').textContent = formatCurrency(idealPrice);
    document.getElementById('display_profit').textContent = formatCurrency(profit);
    document.getElementById('display_real_margin').textContent = realMargin + '%';
}

function formatCurrency(value) {
    return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(value);
}

document.addEventListener('DOMContentLoaded', calculatePricing);
</script>
@endsection

