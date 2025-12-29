<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use App\Models\Payable;
use App\Models\Receivable;
use App\Models\Contact;
use App\Models\FinancialGoal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Se for super admin, redirecionar para dashboard admin
        if ($user->isSuperAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        // Verificar automaticamente pagamentos pendentes (módulos e assinaturas)
        $this->checkPendingPayments($user);

        // Verificar se veio de um pagamento bem-sucedido
        if ($request->has('payment') && $request->payment === 'success') {
            // Verificar se é pagamento de módulo
            if ($request->has('module')) {
                $moduleId = $request->query('module');
                $userModule = \App\Models\UserModule::where('user_id', $user->id)
                    ->where('module_id', $moduleId)
                    ->where('status', 'active')
                    ->first();
                
                if ($userModule) {
                    session()->flash('success', 'Módulo ativado com sucesso!');
                } else {
                    // Tentar verificar se o pagamento foi confirmado mas o módulo ainda não foi ativado
                    $userModule = \App\Models\UserModule::where('user_id', $user->id)
                        ->where('module_id', $moduleId)
                        ->where('status', 'inactive')
                        ->first();
                    
                    if ($userModule && $userModule->asaas_payment_id) {
                        try {
                            $payment = app(\App\Services\AsaasService::class)->getPayment($userModule->asaas_payment_id);
                            if ($payment && isset($payment['status']) && $payment['status'] === 'CONFIRMED') {
                                $userModule->update(['status' => 'active']);
                                session()->flash('success', 'Módulo ativado com sucesso!');
                            } else {
                                session()->flash('info', 'Aguardando confirmação do pagamento. O módulo será ativado automaticamente quando o pagamento for confirmado.');
                            }
                        } catch (\Exception $e) {
                            session()->flash('info', 'Aguardando confirmação do pagamento. O módulo será ativado automaticamente quando o pagamento for confirmado.');
                        }
                    }
                }
            } else {
                // Verificar se a assinatura foi ativada
                $subscription = $user->activeSubscription();
                if ($subscription && $subscription->status === 'active') {
                    session()->flash('success', 'Assinatura ativada com sucesso! Bem-vindo ao CLIVUS!');
                } else {
                    // Tentar verificar se o pagamento foi confirmado mas a assinatura ainda não foi ativada
                    $pendingSubscription = $user->subscriptions()
                        ->where('status', 'pending')
                        ->latest()
                        ->first();
                    
                    if ($pendingSubscription) {
                        try {
                            $payment = app(\App\Services\AsaasService::class)->getSubscriptionPayments($pendingSubscription->asaas_subscription_id);
                            if ($payment && isset($payment['status']) && $payment['status'] === 'CONFIRMED') {
                                $pendingSubscription->update(['status' => 'active']);
                                session()->flash('success', 'Assinatura ativada com sucesso! Bem-vindo ao CLIVUS!');
                            } else {
                                session()->flash('info', 'Aguardando confirmação do pagamento. Sua assinatura será ativada automaticamente quando o pagamento for confirmado.');
                            }
                        } catch (\Exception $e) {
                            session()->flash('info', 'Aguardando confirmação do pagamento. Sua assinatura será ativada automaticamente quando o pagamento for confirmado.');
                        }
                    }
                }
            }
        }

        // Estatísticas financeiras
        $accounts = Account::where('user_id', $user->id)->get();
        $totalAccounts = $accounts->count();
        $totalBalance = $accounts->sum('balance');

        // Transações do mês atual
        $currentMonth = now()->startOfMonth();
        $transactions = Transaction::where('user_id', $user->id)
            ->where('date', '>=', $currentMonth)
            ->get();
        
        $monthlyRevenue = $transactions->where('type', 'revenue')->sum('amount');
        $monthlyExpenses = $transactions->where('type', 'expense')->sum('amount');
        $monthlyBalance = $monthlyRevenue - $monthlyExpenses;

        // Contas a pagar e receber
        $payables = Payable::where('user_id', $user->id)
            ->where('status', 'pending')
            ->get();
        $totalPayables = $payables->sum('total_value');
        $overduePayables = $payables->where('due_date', '<', now())->sum('total_value');

        $receivables = Receivable::where('user_id', $user->id)
            ->where('status', 'pending')
            ->get();
        $totalReceivables = $receivables->sum('total_value');
        $overdueReceivables = $receivables->where('due_date', '<', now())->sum('total_value');

        // Contatos
        $totalContacts = Contact::where('user_id', $user->id)->count();

        // Metas financeiras
        $goals = FinancialGoal::where('user_id', $user->id)
            ->where('status', 'active')
            ->get();
        $totalGoals = $goals->count();
        $goalsProgress = $goals->map(function($goal) {
            $progress = $goal->target_value > 0 
                ? ($goal->current_value / $goal->target_value) * 100 
                : 0;
            return [
                'name' => $goal->name,
                'progress' => min(100, max(0, $progress)),
                'current' => $goal->current_value,
                'target' => $goal->target_value,
            ];
        });

        // Transações recentes
        $recentTransactions = Transaction::where('user_id', $user->id)
            ->with('account', 'category')
            ->latest('date')
            ->take(10)
            ->get();

        // Gráfico de receitas vs despesas (últimos 6 meses)
        $monthlyData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthStart = $month->copy()->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();
            
            $revenue = Transaction::where('user_id', $user->id)
                ->where('type', 'revenue')
                ->whereBetween('date', [$monthStart, $monthEnd])
                ->sum('amount');
            
            $expense = Transaction::where('user_id', $user->id)
                ->where('type', 'expense')
                ->whereBetween('date', [$monthStart, $monthEnd])
                ->sum('amount');
            
            $monthlyData[] = [
                'month' => $month->format('M/Y'),
                'revenue' => $revenue,
                'expense' => $expense,
            ];
        }

        // Assinatura
        $subscription = $user->activeSubscription();
        $plan = $subscription?->plan;

        return view('dashboard.index', compact(
            'totalAccounts',
            'totalBalance',
            'monthlyRevenue',
            'monthlyExpenses',
            'monthlyBalance',
            'totalPayables',
            'overduePayables',
            'totalReceivables',
            'overdueReceivables',
            'totalContacts',
            'totalGoals',
            'goalsProgress',
            'recentTransactions',
            'monthlyData',
            'subscription',
            'plan'
        ));
    }

    /**
     * Verificar e ativar pagamentos pendentes automaticamente
     */
    private function checkPendingPayments($user)
    {
        // Verificar módulos pendentes
        $pendingModules = \App\Models\UserModule::where('user_id', $user->id)
            ->where('status', 'inactive')
            ->whereNotNull('asaas_payment_id')
            ->get();

        foreach ($pendingModules as $userModule) {
            try {
                $payment = app(\App\Services\AsaasService::class)->getPayment($userModule->asaas_payment_id);
                if ($payment && isset($payment['status']) && $payment['status'] === 'CONFIRMED') {
                    $userModule->update(['status' => 'active']);
                    Log::info('Módulo ativado automaticamente no dashboard', [
                        'user_module_id' => $userModule->id,
                        'module_id' => $userModule->module_id,
                        'payment_id' => $userModule->asaas_payment_id
                    ]);
                }
            } catch (\Exception $e) {
                    Log::warning('Erro ao verificar pagamento de módulo no dashboard', [
                    'user_module_id' => $userModule->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        // Verificar assinaturas pendentes
        $pendingSubscriptions = \App\Models\Subscription::where('user_id', $user->id)
            ->where('status', 'pending')
            ->whereNotNull('asaas_subscription_id')
            ->get();

        foreach ($pendingSubscriptions as $subscription) {
            try {
                $subscriptionData = app(\App\Services\AsaasService::class)->getSubscription($subscription->asaas_subscription_id);
                if ($subscriptionData && isset($subscriptionData['status']) && $subscriptionData['status'] === 'ACTIVE') {
                    $subscription->update(['status' => 'active']);
                    Log::info('Assinatura ativada automaticamente no dashboard', [
                        'subscription_id' => $subscription->id,
                        'asaas_subscription_id' => $subscription->asaas_subscription_id
                    ]);
                } else {
                    // Tentar verificar pelos pagamentos da assinatura
                    $payment = app(\App\Services\AsaasService::class)->getSubscriptionPayments($subscription->asaas_subscription_id);
                    if ($payment && isset($payment['status']) && $payment['status'] === 'CONFIRMED') {
                        $subscription->update(['status' => 'active']);
                        Log::info('Assinatura ativada automaticamente via pagamento no dashboard', [
                            'subscription_id' => $subscription->id,
                            'asaas_subscription_id' => $subscription->asaas_subscription_id
                        ]);
                    }
                }
            } catch (\Exception $e) {
                Log::warning('Erro ao verificar assinatura no dashboard', [
                    'subscription_id' => $subscription->id,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }
}
