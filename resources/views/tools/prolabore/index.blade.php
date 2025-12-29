@extends('layouts.app')

@section('title', 'Calculadora de Pró-Labore - CLIVUS')
@section('page-title', 'Calculadora de Pró-Labore')

@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Formulário -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Visão Geral da Empresa -->
            <div class="rounded-xl p-6" style="background-color: rgb(var(--card)); border: 1px solid rgb(var(--border)); box-shadow: var(--shadow);">
                <h3 class="text-lg font-semibold mb-4">Visão Geral da Empresa</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="monthly_revenue" class="block text-sm font-medium mb-2">Faturamento Mensal Médio (R$)</label>
                        <input type="number" id="monthly_revenue" step="0.01" min="0" value="0"
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                            onchange="calculateProlabore()">
                    </div>
                    <div>
                        <label for="fixed_costs" class="block text-sm font-medium mb-2">Custos Fixos Mensais (R$)</label>
                        <input type="number" id="fixed_costs" step="0.01" min="0" value="0"
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                            onchange="calculateProlabore()">
                    </div>
                    <div>
                        <label for="variable_costs_percentage" class="block text-sm font-medium mb-2">Custos Variáveis (%)</label>
                        <input type="number" id="variable_costs_percentage" step="0.01" min="0" max="100" value="0"
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                            onchange="calculateProlabore()">
                        <p class="text-xs mt-1" style="color: rgb(var(--text-secondary));">Percentual sobre o faturamento</p>
                    </div>
                </div>
            </div>

            <!-- Configuração de Pró-labore -->
            <div class="rounded-xl p-6" style="background-color: rgb(var(--card)); border: 1px solid rgb(var(--border)); box-shadow: var(--shadow);">
                <h3 class="text-lg font-semibold mb-4">Configuração de Pró-labore</h3>
                <div class="mb-4">
                    <label for="current_prolabore" class="block text-sm font-medium mb-2">Pró-labore Atual (R$) - Opcional</label>
                    <input type="number" id="current_prolabore" step="0.01" min="0" value="0"
                        class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                        style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                    <p class="text-xs mt-1" style="color: rgb(var(--text-secondary));">Informe o valor atual para comparação</p>
                </div>

                <!-- Sócios e Distribuição -->
                <div class="mb-4">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="font-medium">Sócios e Distribuição</h4>
                        <button type="button" onclick="addPartner()" class="px-3 py-1 rounded-lg text-sm font-medium text-white transition-all" style="background: linear-gradient(135deg, rgb(var(--primary)), rgb(var(--primary-dark)));">
                            + Adicionar Sócio
                        </button>
                    </div>
                    <div id="partners-container" class="space-y-2">
                        <div class="partner-row grid grid-cols-12 gap-2">
                            <div class="col-span-8">
                                <input type="text" name="partner_name[]" placeholder="Nome" value="Sócio 1"
                                    class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                                    style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                            </div>
                            <div class="col-span-3">
                                <input type="number" name="partner_percentage[]" step="0.01" min="0" max="100" placeholder="% Dist." value="100"
                                    class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                                    style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                                    onchange="calculateProlabore()">
                            </div>
                            <div class="col-span-1">
                                <button type="button" onclick="removePartner(this)" class="w-full p-2 rounded-lg hover:bg-opacity-50 transition-colors" style="background-color: rgba(239, 68, 68, 0.1); color: rgb(239, 68, 68);">
                                    <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Metas e Reservas -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="profit_margin_percentage" class="block text-sm font-medium mb-2">Margem de Lucro Desejada (%)</label>
                        <input type="number" id="profit_margin_percentage" step="0.01" min="0" max="100" value="20"
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                            onchange="calculateProlabore()">
                    </div>
                    <div>
                        <label for="reinvestment_percentage" class="block text-sm font-medium mb-2">% do Lucro para Reinvestimento</label>
                        <input type="number" id="reinvestment_percentage" step="0.01" min="0" max="100" value="30"
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                            onchange="calculateProlabore()">
                    </div>
                    <div>
                        <label for="reserve_percentage" class="block text-sm font-medium mb-2">% do Lucro para Reserva de Segurança</label>
                        <input type="number" id="reserve_percentage" step="0.01" min="0" max="100" value="20"
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                            onchange="calculateProlabore()">
                    </div>
                </div>
            </div>
        </div>

        <!-- Resultado -->
        <div class="space-y-6">
            <div class="rounded-xl p-6" style="background-color: rgb(var(--card)); border: 1px solid rgb(var(--border)); box-shadow: var(--shadow);">
                <h3 class="text-lg font-semibold mb-4">Análise Financeira</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span style="color: rgb(var(--text-secondary));">Faturamento Mensal:</span>
                        <span id="display_revenue" class="font-medium">R$ 0,00</span>
                    </div>
                    <div class="flex justify-between">
                        <span style="color: rgb(var(--text-secondary));">Custos Fixos:</span>
                        <span id="display_fixed_costs" class="font-medium">R$ 0,00</span>
                    </div>
                    <div class="flex justify-between">
                        <span style="color: rgb(var(--text-secondary));">Custos Variáveis:</span>
                        <span id="display_variable_costs" class="font-medium">R$ 0,00</span>
                    </div>
                    <div class="flex justify-between pt-3 border-t" style="border-color: rgb(var(--border));">
                        <span class="font-semibold">Lucro Líquido Projetado:</span>
                        <span id="display_net_profit" class="font-bold text-lg">R$ 0,00</span>
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t" style="border-color: rgb(var(--border));">
                    <h4 class="font-medium mb-3">Distribuição do Lucro:</h4>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span style="color: rgb(var(--text-secondary));">Reinvestimento (30%):</span>
                            <span id="display_reinvestment" class="font-medium">R$ 0,00</span>
                        </div>
                        <div class="flex justify-between">
                            <span style="color: rgb(var(--text-secondary));">Reserva (20%):</span>
                            <span id="display_reserve" class="font-medium">R$ 0,00</span>
                        </div>
                        <div class="flex justify-between pt-2 border-t" style="border-color: rgb(var(--border));">
                            <span class="font-medium">Disponível p/ Pró-labore:</span>
                            <span id="display_available_prolabore" class="font-semibold">R$ 0,00</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="rounded-xl p-6" style="background: linear-gradient(135deg, rgba(var(--primary), 0.1), rgba(var(--primary-dark), 0.1)); border: 1px solid rgb(var(--primary));">
                <h3 class="text-lg font-semibold mb-4">Pró-labore Recomendado</h3>
                <div class="text-center">
                    <p class="text-3xl font-bold mb-2" style="color: rgb(var(--primary));" id="display_recommended_prolabore">R$ 0,00</p>
                    <p class="text-sm" style="color: rgb(var(--text-secondary));" id="display_prolabore_percentage">0.00% do faturamento</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let partnerCount = 1;

function addPartner() {
    partnerCount++;
    const container = document.getElementById('partners-container');
    const row = document.createElement('div');
    row.className = 'partner-row grid grid-cols-12 gap-2';
    row.innerHTML = `
        <div class="col-span-8">
            <input type="text" name="partner_name[]" placeholder="Nome" value="Sócio ${partnerCount}"
                class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
        </div>
        <div class="col-span-3">
            <input type="number" name="partner_percentage[]" step="0.01" min="0" max="100" placeholder="% Dist." value="0"
                class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                onchange="calculateProlabore()">
        </div>
        <div class="col-span-1">
            <button type="button" onclick="removePartner(this)" class="w-full p-2 rounded-lg hover:bg-opacity-50 transition-colors" style="background-color: rgba(239, 68, 68, 0.1); color: rgb(239, 68, 68);">
                <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
            </button>
        </div>
    `;
    container.appendChild(row);
}

function removePartner(btn) {
    const rows = document.querySelectorAll('.partner-row');
    if (rows.length > 1) {
        btn.closest('.partner-row').remove();
        calculateProlabore();
    }
}

function calculateProlabore() {
    const revenue = parseFloat(document.getElementById('monthly_revenue').value) || 0;
    const fixedCosts = parseFloat(document.getElementById('fixed_costs').value) || 0;
    const variableCostsPercentage = parseFloat(document.getElementById('variable_costs_percentage').value) || 0;
    const profitMargin = parseFloat(document.getElementById('profit_margin_percentage').value) || 20;
    const reinvestment = parseFloat(document.getElementById('reinvestment_percentage').value) || 30;
    const reserve = parseFloat(document.getElementById('reserve_percentage').value) || 20;

    const variableCosts = (revenue * variableCostsPercentage) / 100;
    const netProfit = revenue - fixedCosts - variableCosts;
    const reinvestmentAmount = (netProfit * reinvestment) / 100;
    const reserveAmount = (netProfit * reserve) / 100;
    const availableForProlabore = netProfit - reinvestmentAmount - reserveAmount;

    // Atualizar displays
    document.getElementById('display_revenue').textContent = formatCurrency(revenue);
    document.getElementById('display_fixed_costs').textContent = formatCurrency(fixedCosts);
    document.getElementById('display_variable_costs').textContent = formatCurrency(variableCosts);
    document.getElementById('display_net_profit').textContent = formatCurrency(netProfit);
    document.getElementById('display_reinvestment').textContent = formatCurrency(reinvestmentAmount);
    document.getElementById('display_reserve').textContent = formatCurrency(reserveAmount);
    document.getElementById('display_available_prolabore').textContent = formatCurrency(availableForProlabore);
    document.getElementById('display_recommended_prolabore').textContent = formatCurrency(availableForProlabore);
    
    const percentage = revenue > 0 ? ((availableForProlabore / revenue) * 100).toFixed(2) : '0.00';
    document.getElementById('display_prolabore_percentage').textContent = `${percentage}% do faturamento`;
}

function formatCurrency(value) {
    return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(value);
}

// Calcular ao carregar
document.addEventListener('DOMContentLoaded', calculateProlabore);
</script>
@endsection

