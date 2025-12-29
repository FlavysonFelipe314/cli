<?php

namespace Database\Seeders;

use App\Models\Module;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modules = [
            [
                'name' => 'Pró-labore',
                'slug' => 'prolabore',
                'description' => 'Calculadora de pró-labore para sócios e distribuição de lucros',
                'route_name' => 'tools.prolabore.index',
                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>',
                'price' => 29.90,
                'category' => 'tools',
                'active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Precificação',
                'slug' => 'pricing',
                'description' => 'Calculadora de precificação para produtos e serviços',
                'route_name' => 'tools.pricing.index',
                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
                'price' => 29.90,
                'category' => 'tools',
                'active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Custo de Funcionário (CLT)',
                'slug' => 'employee-cost',
                'description' => 'Calculadora de custo total de funcionários CLT',
                'route_name' => 'tools.employee-cost.index',
                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>',
                'price' => 29.90,
                'category' => 'tools',
                'active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Compliance Fiscal',
                'slug' => 'compliance',
                'description' => 'Gestão de obrigações fiscais e compliance',
                'route_name' => 'tools.compliance.index',
                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>',
                'price' => 39.90,
                'category' => 'tools',
                'active' => true,
                'sort_order' => 4,
            ],
            // Módulos Financeiros Individuais
            [
                'name' => 'Contas',
                'slug' => 'finance-accounts',
                'description' => 'Gestão de contas bancárias e financeiras',
                'route_name' => 'finance.accounts.index',
                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>',
                'price' => 19.90,
                'category' => 'finance',
                'active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Transações',
                'slug' => 'finance-transactions',
                'description' => 'Registro e controle de receitas e despesas',
                'route_name' => 'finance.transactions.index',
                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>',
                'price' => 19.90,
                'category' => 'finance',
                'active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Contatos',
                'slug' => 'finance-contacts',
                'description' => 'Cadastro de clientes, fornecedores e contatos',
                'route_name' => 'finance.contacts.index',
                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>',
                'price' => 19.90,
                'category' => 'finance',
                'active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Contas a Pagar',
                'slug' => 'finance-payables',
                'description' => 'Controle de contas a pagar e fornecedores',
                'route_name' => 'finance.payables.index',
                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
                'price' => 24.90,
                'category' => 'finance',
                'active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Contas a Receber',
                'slug' => 'finance-receivables',
                'description' => 'Controle de contas a receber e clientes',
                'route_name' => 'finance.receivables.index',
                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
                'price' => 24.90,
                'category' => 'finance',
                'active' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'Planejamento Financeiro',
                'slug' => 'finance-planning',
                'description' => 'Metas financeiras e planejamento',
                'route_name' => 'finance.planning.index',
                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>',
                'price' => 29.90,
                'category' => 'finance',
                'active' => true,
                'sort_order' => 6,
            ],
            [
                'name' => 'Conciliação Bancária',
                'slug' => 'finance-reconciliation',
                'description' => 'Conciliação de extratos bancários',
                'route_name' => 'finance.reconciliations.index',
                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>',
                'price' => 29.90,
                'category' => 'finance',
                'active' => true,
                'sort_order' => 7,
            ],
            [
                'name' => 'Custos Indiretos',
                'slug' => 'finance-indirect-costs',
                'description' => 'Rateio de custos indiretos',
                'route_name' => 'finance.indirect-costs.index',
                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>',
                'price' => 29.90,
                'category' => 'finance',
                'active' => true,
                'sort_order' => 8,
            ],
            [
                'name' => 'Categorias',
                'slug' => 'finance-categories',
                'description' => 'Gestão de categorias financeiras',
                'route_name' => 'finance.categories.index',
                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>',
                'price' => 9.90,
                'category' => 'finance',
                'active' => true,
                'sort_order' => 9,
            ],
            // Módulos de Gestão
            [
                'name' => 'Gestão de Equipe',
                'slug' => 'team-management',
                'description' => 'Gerenciamento completo de equipes e membros',
                'route_name' => 'management.team.index',
                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>',
                'price' => 29.90,
                'category' => 'management',
                'active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Gestão de Tarefas / Kanban',
                'slug' => 'task-management',
                'description' => 'Sistema completo de gestão de tarefas com Kanban',
                'route_name' => 'management.tasks.index',
                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>',
                'price' => 29.90,
                'category' => 'management',
                'active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Agenda / Calendário',
                'slug' => 'calendar',
                'description' => 'Agenda e calendário integrado com eventos financeiros',
                'route_name' => 'management.calendar.index',
                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>',
                'price' => 19.90,
                'category' => 'management',
                'active' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($modules as $module) {
            Module::updateOrCreate(
                ['slug' => $module['slug']],
                $module
            );
        }
    }
}
