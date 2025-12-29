@extends('layouts.app')

@section('title', 'Calculadora de Custo de Funcionário (CLT) - CLIVUS')
@section('page-title', 'Calculadora de Custo de Funcionário (CLT)')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-end">
        <button type="button" onclick="saveProfile()" class="px-4 py-2 rounded-lg font-medium text-white transition-all hover:scale-105" style="background: linear-gradient(135deg, rgb(var(--primary)), rgb(var(--primary-dark))); box-shadow: 0 4px 6px -1px rgba(var(--primary), 0.3);">
            Salvar como Perfil
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Formulário -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Dados do Funcionário -->
            <div class="rounded-xl p-6" style="background-color: rgb(var(--card)); border: 1px solid rgb(var(--border)); box-shadow: var(--shadow);">
                <h3 class="text-lg font-semibold mb-4">Dados do Funcionário</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="employee_name" class="block text-sm font-medium mb-2">Nome do Funcionário (opcional)</label>
                        <input type="text" id="employee_name" placeholder="Ex: João Silva"
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                    </div>
                    <div>
                        <label for="position" class="block text-sm font-medium mb-2">Cargo</label>
                        <input type="text" id="position" placeholder="Ex: Técnico Nível 1"
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                    </div>
                    <div>
                        <label for="cost_center" class="block text-sm font-medium mb-2">Centro de Custo (opcional)</label>
                        <input type="text" id="cost_center" placeholder="Ex: Produção"
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                    </div>
                </div>
            </div>

            <!-- Remuneração Fixa -->
            <div class="rounded-xl p-6" style="background-color: rgb(var(--card)); border: 1px solid rgb(var(--border)); box-shadow: var(--shadow);">
                <h3 class="text-lg font-semibold mb-4">Remuneração Fixa</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="gross_salary" class="block text-sm font-medium mb-2">Salário Bruto (R$)</label>
                        <input type="number" id="gross_salary" step="0.01" min="0" value="0" onchange="calculateEmployeeCost()"
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                    </div>
                    <div>
                        <label for="monthly_hours" class="block text-sm font-medium mb-2">Horas Mensais Consideradas</label>
                        <input type="number" id="monthly_hours" min="1" value="220" onchange="calculateEmployeeCost()"
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                    </div>
                </div>
            </div>

            <!-- Benefícios Mensais -->
            <div class="rounded-xl p-6" style="background-color: rgb(var(--card)); border: 1px solid rgb(var(--border)); box-shadow: var(--shadow);">
                <h3 class="text-lg font-semibold mb-4">Benefícios Mensais</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="transport_allowance" class="block text-sm font-medium mb-2">Vale Transporte (R$)</label>
                        <input type="number" id="transport_allowance" step="0.01" min="0" value="0" onchange="calculateEmployeeCost()"
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                    </div>
                    <div>
                        <label for="meal_allowance" class="block text-sm font-medium mb-2">Vale Refeição/Alimentação (R$)</label>
                        <input type="number" id="meal_allowance" step="0.01" min="0" value="0" onchange="calculateEmployeeCost()"
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                    </div>
                    <div>
                        <label for="health_insurance" class="block text-sm font-medium mb-2">Plano de Saúde (cota empresa) (R$)</label>
                        <input type="number" id="health_insurance" step="0.01" min="0" value="0" onchange="calculateEmployeeCost()"
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                    </div>
                    <div>
                        <label for="other_benefits" class="block text-sm font-medium mb-2">Outros Benefícios Fixos (R$)</label>
                        <input type="number" id="other_benefits" step="0.01" min="0" value="0" onchange="calculateEmployeeCost()"
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                    </div>
                </div>
            </div>

            <!-- Encargos e Provisões -->
            <div class="rounded-xl p-6" style="background-color: rgb(var(--card)); border: 1px solid rgb(var(--border)); box-shadow: var(--shadow);">
                <h3 class="text-lg font-semibold mb-4">Encargos e Provisões (%)</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="inss_rate" class="block text-sm font-medium mb-2">INSS Patronal (%)</label>
                        <input type="number" id="inss_rate" step="0.01" min="0" max="100" value="20" onchange="calculateEmployeeCost()"
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                    </div>
                    <div>
                        <label for="fgts_rate" class="block text-sm font-medium mb-2">FGTS (%)</label>
                        <input type="number" id="fgts_rate" step="0.01" min="0" max="100" value="8" onchange="calculateEmployeeCost()"
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                    </div>
                    <div>
                        <label for="thirteenth_provision" class="block text-sm font-medium mb-2">Provisão 13º (%)</label>
                        <input type="number" id="thirteenth_provision" step="0.01" min="0" max="100" value="8.33" onchange="calculateEmployeeCost()"
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                    </div>
                    <div>
                        <label for="vacation_provision" class="block text-sm font-medium mb-2">Provisão Férias + 1/3 (%)</label>
                        <input type="number" id="vacation_provision" step="0.01" min="0" max="100" value="11.11" onchange="calculateEmployeeCost()"
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                    </div>
                    <div>
                        <label for="severance_provision" class="block text-sm font-medium mb-2">Provisão Rescisão (%)</label>
                        <input type="number" id="severance_provision" step="0.01" min="0" max="100" value="4" onchange="calculateEmployeeCost()"
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                    </div>
                    <div>
                        <label for="other_charges" class="block text-sm font-medium mb-2">Outros Encargos (%)</label>
                        <input type="number" id="other_charges" step="0.01" min="0" max="100" value="0" onchange="calculateEmployeeCost()"
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                    </div>
                </div>
            </div>

            <!-- Outros Custos Relacionados -->
            <div class="rounded-xl p-6" style="background-color: rgb(var(--card)); border: 1px solid rgb(var(--border)); box-shadow: var(--shadow);">
                <h3 class="text-lg font-semibold mb-4">Outros Custos Relacionados (mensal)</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="equipment_tools" class="block text-sm font-medium mb-2">Equipamentos/Ferramentas (R$)</label>
                        <input type="number" id="equipment_tools" step="0.01" min="0" value="0" onchange="calculateEmployeeCost()"
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                    </div>
                    <div>
                        <label for="training" class="block text-sm font-medium mb-2">Treinamentos (R$)</label>
                        <input type="number" id="training" step="0.01" min="0" value="0" onchange="calculateEmployeeCost()"
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                    </div>
                    <div>
                        <label for="epi" class="block text-sm font-medium mb-2">EPIs etc. (R$)</label>
                        <input type="number" id="epi" step="0.01" min="0" value="0" onchange="calculateEmployeeCost()"
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                    </div>
                </div>
            </div>
        </div>

        <!-- Resultado -->
        <div class="space-y-6">
            <div class="rounded-xl p-6" style="background-color: rgb(var(--card)); border: 1px solid rgb(var(--border)); box-shadow: var(--shadow);">
                <h3 class="text-lg font-semibold mb-4">Resultado do Cálculo</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span style="color: rgb(var(--text-secondary));">Custo Salarial:</span>
                        <span id="display_salary_cost" class="font-medium">R$ 0,00</span>
                    </div>
                    <div class="flex justify-between">
                        <span style="color: rgb(var(--text-secondary));">Benefícios:</span>
                        <span id="display_benefits" class="font-medium">R$ 0,00</span>
                    </div>
                    <div class="flex justify-between">
                        <span style="color: rgb(var(--text-secondary));">Encargos Totais:</span>
                        <span id="display_charges" class="font-medium">R$ 0,00</span>
                        <span class="text-xs" style="color: rgb(var(--text-secondary));" id="display_charges_rate">(51.44%)</span>
                    </div>
                    <div class="flex justify-between">
                        <span style="color: rgb(var(--text-secondary));">Outros Custos:</span>
                        <span id="display_other_costs" class="font-medium">R$ 0,00</span>
                    </div>
                    <div class="pt-4 border-t space-y-3" style="border-color: rgb(var(--border));">
                        <div>
                            <p class="text-sm mb-1" style="color: rgb(var(--text-secondary));">Custo Total Mensal:</p>
                            <p id="display_total_monthly" class="text-2xl font-bold" style="color: rgb(239, 68, 68);">R$ 0,00</p>
                        </div>
                        <div>
                            <p class="text-sm mb-1" style="color: rgb(var(--text-secondary));">Custo por Hora:</p>
                            <p id="display_cost_per_hour" class="text-2xl font-bold" style="color: rgb(var(--primary));">R$ 0,00</p>
                        </div>
                        <div>
                            <p class="text-sm mb-1" style="color: rgb(var(--text-secondary));">Custo por Dia (8h):</p>
                            <p id="display_cost_per_day" class="text-2xl font-bold">R$ 0,00</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Perfis Salvos -->
            <div class="rounded-xl p-6" style="background-color: rgb(var(--card)); border: 1px solid rgb(var(--border)); box-shadow: var(--shadow);">
                <h3 class="text-lg font-semibold mb-4">Perfis Salvos</h3>
                @if($profiles->count() > 0)
                <div class="space-y-2">
                    @foreach($profiles as $profile)
                    <div class="p-3 rounded-lg flex items-center justify-between" style="background-color: rgba(var(--primary), 0.05);">
                        <div>
                            <p class="font-medium">{{ $profile->name }}</p>
                            <p class="text-sm" style="color: rgb(var(--text-secondary));">{{ $profile->position ?: 'Sem cargo' }}</p>
                        </div>
                        <button type="button" onclick="loadProfile({{ $profile->id }})" class="px-3 py-1 rounded text-sm font-medium transition-colors" style="background-color: rgba(var(--primary), 0.1); color: rgb(var(--primary));">
                            Carregar
                        </button>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-sm text-center py-4" style="color: rgb(var(--text-secondary));">
                    Nenhum perfil salvo ainda. Preencha os dados e clique em "Salvar como Perfil".
                </p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal Salvar Perfil -->
<div id="profileModal" class="fixed inset-0 z-50 hidden overflow-y-auto" style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity backdrop-blur-md" style="background: rgba(0, 0, 0, 0.4);" onclick="closeProfileModal()"></div>
        <div class="relative inline-block w-full max-w-md p-6 my-8 text-left align-middle transition-all transform rounded-xl shadow-xl z-50" style="background-color: #ffffff; border: 1px solid rgb(var(--border)); box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);">
            <h3 class="text-xl font-bold mb-4">Salvar Perfil</h3>
            <form id="profileForm" method="POST" action="{{ route('tools.employee-cost.store') }}">
                @csrf
                <div>
                    <label for="profile_name" class="block text-sm font-medium mb-2">Nome do Perfil *</label>
                    <input type="text" id="profile_name" name="name" required
                        class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                        style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                        placeholder="Ex: Técnico Nível 1">
                </div>
                <input type="hidden" name="employee_name" id="form_employee_name">
                <input type="hidden" name="position" id="form_position">
                <input type="hidden" name="cost_center" id="form_cost_center">
                <input type="hidden" name="gross_salary" id="form_gross_salary">
                <input type="hidden" name="monthly_hours" id="form_monthly_hours">
                <input type="hidden" name="transport_allowance" id="form_transport_allowance">
                <input type="hidden" name="meal_allowance" id="form_meal_allowance">
                <input type="hidden" name="health_insurance" id="form_health_insurance">
                <input type="hidden" name="other_benefits" id="form_other_benefits">
                <input type="hidden" name="inss_rate" id="form_inss_rate">
                <input type="hidden" name="fgts_rate" id="form_fgts_rate">
                <input type="hidden" name="thirteenth_provision" id="form_thirteenth_provision">
                <input type="hidden" name="vacation_provision" id="form_vacation_provision">
                <input type="hidden" name="severance_provision" id="form_severance_provision">
                <input type="hidden" name="other_charges" id="form_other_charges">
                <input type="hidden" name="equipment_tools" id="form_equipment_tools">
                <input type="hidden" name="training" id="form_training">
                <input type="hidden" name="epi" id="form_epi">
                <div class="flex gap-3 mt-6">
                    <button type="submit" class="flex-1 px-6 py-3 rounded-lg font-medium text-white transition-all" style="background: linear-gradient(135deg, rgb(var(--primary)), rgb(var(--primary-dark)));">
                        Salvar
                    </button>
                    <button type="button" onclick="closeProfileModal()" class="flex-1 px-6 py-3 rounded-lg font-medium text-center transition-colors" style="background-color: rgba(var(--primary), 0.1); color: rgb(var(--primary));">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const profilesData = @json($profiles->keyBy('id'));

function calculateEmployeeCost() {
    const salary = parseFloat(document.getElementById('gross_salary').value) || 0;
    const monthlyHours = parseFloat(document.getElementById('monthly_hours').value) || 220;
    const benefits = (parseFloat(document.getElementById('transport_allowance').value) || 0)
        + (parseFloat(document.getElementById('meal_allowance').value) || 0)
        + (parseFloat(document.getElementById('health_insurance').value) || 0)
        + (parseFloat(document.getElementById('other_benefits').value) || 0);
    
    const chargesRate = (parseFloat(document.getElementById('inss_rate').value) || 20)
        + (parseFloat(document.getElementById('fgts_rate').value) || 8)
        + (parseFloat(document.getElementById('thirteenth_provision').value) || 8.33)
        + (parseFloat(document.getElementById('vacation_provision').value) || 11.11)
        + (parseFloat(document.getElementById('severance_provision').value) || 4)
        + (parseFloat(document.getElementById('other_charges').value) || 0);
    
    const charges = (salary * chargesRate) / 100;
    const otherCosts = (parseFloat(document.getElementById('equipment_tools').value) || 0)
        + (parseFloat(document.getElementById('training').value) || 0)
        + (parseFloat(document.getElementById('epi').value) || 0);
    
    const totalMonthly = salary + benefits + charges + otherCosts;
    const costPerHour = totalMonthly / monthlyHours;
    const costPerDay = costPerHour * 8;

    document.getElementById('display_salary_cost').textContent = formatCurrency(salary);
    document.getElementById('display_benefits').textContent = formatCurrency(benefits);
    document.getElementById('display_charges').textContent = formatCurrency(charges);
    document.getElementById('display_charges_rate').textContent = `(${chargesRate.toFixed(2)}%)`;
    document.getElementById('display_other_costs').textContent = formatCurrency(otherCosts);
    document.getElementById('display_total_monthly').textContent = formatCurrency(totalMonthly);
    document.getElementById('display_cost_per_hour').textContent = formatCurrency(costPerHour);
    document.getElementById('display_cost_per_day').textContent = formatCurrency(costPerDay);
}

function saveProfile() {
    document.getElementById('form_employee_name').value = document.getElementById('employee_name').value;
    document.getElementById('form_position').value = document.getElementById('position').value;
    document.getElementById('form_cost_center').value = document.getElementById('cost_center').value;
    document.getElementById('form_gross_salary').value = document.getElementById('gross_salary').value;
    document.getElementById('form_monthly_hours').value = document.getElementById('monthly_hours').value;
    document.getElementById('form_transport_allowance').value = document.getElementById('transport_allowance').value;
    document.getElementById('form_meal_allowance').value = document.getElementById('meal_allowance').value;
    document.getElementById('form_health_insurance').value = document.getElementById('health_insurance').value;
    document.getElementById('form_other_benefits').value = document.getElementById('other_benefits').value;
    document.getElementById('form_inss_rate').value = document.getElementById('inss_rate').value;
    document.getElementById('form_fgts_rate').value = document.getElementById('fgts_rate').value;
    document.getElementById('form_thirteenth_provision').value = document.getElementById('thirteenth_provision').value;
    document.getElementById('form_vacation_provision').value = document.getElementById('vacation_provision').value;
    document.getElementById('form_severance_provision').value = document.getElementById('severance_provision').value;
    document.getElementById('form_other_charges').value = document.getElementById('other_charges').value;
    document.getElementById('form_equipment_tools').value = document.getElementById('equipment_tools').value;
    document.getElementById('form_training').value = document.getElementById('training').value;
    document.getElementById('form_epi').value = document.getElementById('epi').value;
    
    document.getElementById('profileModal').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function closeProfileModal() {
    document.getElementById('profileModal').style.display = 'none';
    document.body.style.overflow = 'auto';
}

function loadProfile(id) {
    const profile = profilesData[id];
    if (!profile) return;
    
    document.getElementById('employee_name').value = profile.employee_name || '';
    document.getElementById('position').value = profile.position || '';
    document.getElementById('cost_center').value = profile.cost_center || '';
    document.getElementById('gross_salary').value = profile.gross_salary || 0;
    document.getElementById('monthly_hours').value = profile.monthly_hours || 220;
    document.getElementById('transport_allowance').value = profile.transport_allowance || 0;
    document.getElementById('meal_allowance').value = profile.meal_allowance || 0;
    document.getElementById('health_insurance').value = profile.health_insurance || 0;
    document.getElementById('other_benefits').value = profile.other_benefits || 0;
    document.getElementById('inss_rate').value = profile.inss_rate || 20;
    document.getElementById('fgts_rate').value = profile.fgts_rate || 8;
    document.getElementById('thirteenth_provision').value = profile.thirteenth_provision || 8.33;
    document.getElementById('vacation_provision').value = profile.vacation_provision || 11.11;
    document.getElementById('severance_provision').value = profile.severance_provision || 4;
    document.getElementById('other_charges').value = profile.other_charges || 0;
    document.getElementById('equipment_tools').value = profile.equipment_tools || 0;
    document.getElementById('training').value = profile.training || 0;
    document.getElementById('epi').value = profile.epi || 0;
    
    calculateEmployeeCost();
}

function formatCurrency(value) {
    return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(value);
}

document.addEventListener('DOMContentLoaded', calculateEmployeeCost);
</script>
@endsection

