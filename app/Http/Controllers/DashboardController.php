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
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Se for super admin, redirecionar para dashboard admin
        if ($user->isSuperAdmin()) {
            return redirect()->route('admin.dashboard');
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
}
