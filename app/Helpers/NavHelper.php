<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class NavHelper
{
    /**
     * Mapeamento de rotas/categorias para slugs de módulos
     */
    private static array $moduleMap = [
        'dashboard' => null, // Sem módulo específico
        'finance' => [
            'finance.accounts.index' => 'finance-accounts',
            'finance.transactions.index' => 'finance-transactions',
            'finance.contacts.index' => 'finance-contacts',
            'finance.payables.index' => 'finance-payables',
            'finance.receivables.index' => 'finance-receivables',
            'finance.planning.index' => 'finance-planning',
            'finance.reconciliations.index' => 'finance-reconciliation',
            'finance.indirect-costs.index' => 'finance-indirect-costs',
            'finance.categories.index' => 'finance-categories',
        ],
        'tools' => [
            'tools.prolabore.index' => 'prolabore',
            'tools.pricing.index' => 'pricing',
            'tools.employee-cost.index' => 'employee-cost',
            'tools.compliance.index' => 'compliance',
        ],
        'management' => [
            'management.team.index' => 'team-management',
            'management.tasks.index' => 'task-management',
            'management.calendar.index' => 'calendar',
        ],
    ];

    /**
     * Verifica se um módulo/categoria está bloqueado para o usuário atual
     */
    public static function isLocked(string $category): bool
    {
        $user = Auth::user();

        if (!$user) {
            return true; // Não logado = bloqueado
        }

        // Super admin sempre tem acesso
        if ($user->isSuperAdmin()) {
            return false;
        }

        // Se não tem assinatura ativa, está bloqueado (exceto para equipes)
        if (!$user->hasActiveSubscription()) {
            // Equipes são acessíveis mesmo sem plano (apenas visualização de equipes que o usuário faz parte)
            if ($category === 'team') {
                return false;
            }
            return true;
        }

        // Verificar acesso específico a módulos por categoria
        $subscription = $user->activeSubscription();
        if (!$subscription || !$subscription->plan) {
            return true; // Sem plano = bloqueado
        }

        $allowedModules = $subscription->plan->allowed_modules ?? [];
        
        // Mapeamento de categorias para slugs de módulos
        $categoryModules = [
            'tools' => ['prolabore', 'pricing', 'employee-cost', 'compliance'],
            'finance' => ['finance-accounts', 'finance-transactions', 'finance-contacts', 'finance-payables', 'finance-receivables', 'finance-planning', 'finance-reconciliation', 'finance-indirect-costs', 'finance-categories'],
            'management' => ['team-management', 'task-management', 'calendar'],
        ];

        if (isset($categoryModules[$category])) {
            $requiredModules = $categoryModules[$category];
            
            // Verificar se o plano inclui algum módulo da categoria
            $hasModuleInPlan = !empty(array_intersect($allowedModules, $requiredModules));
            
            // Verificar se o usuário comprou algum módulo da categoria
            $userHasModule = $user->userModules()
                ->whereHas('module', function($q) use ($requiredModules) {
                    $q->whereIn('slug', $requiredModules)
                      ->where('active', true);
                })
                ->where('status', 'active')
                ->exists();

            if (!$hasModuleInPlan && !$userHasModule) {
                return true; // Bloqueado se não tem nenhum módulo da categoria
            }
        }

        return false; // Não está bloqueado
    }

    /**
     * Verifica se uma rota específica está bloqueada
     */
    public static function isRouteLocked(string $routeName): bool
    {
        $user = Auth::user();

        if (!$user) {
            return true;
        }

        if ($user->isSuperAdmin()) {
            return false;
        }

        // Verificar se a rota requer um módulo específico
        foreach (self::$moduleMap as $category => $routes) {
            if (is_array($routes) && isset($routes[$routeName])) {
                $moduleSlug = $routes[$routeName];
                return !$user->hasModuleAccess($moduleSlug);
            }
        }

        // Se não requer módulo específico, verificar assinatura
        if (!$user->hasActiveSubscription()) {
            return true;
        }

        return false;
    }
}
