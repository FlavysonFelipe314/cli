@extends('layouts.app')

@section('title', 'Dashboard - CLIVUS')
@section('page-title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Cards de Estatísticas -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="rounded-xl p-6" style="background: linear-gradient(135deg, rgb(var(--primary)), rgb(var(--primary-dark))); color: white; box-shadow: 0 4px 15px -3px rgba(var(--primary), 0.4);">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium opacity-90">Saldo Total</h3>
                <svg class="w-6 h-6 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <p class="text-3xl font-bold">R$ {{ number_format($totalBalance, 2, ',', '.') }}</p>
            <p class="text-xs opacity-75 mt-2">{{ $totalAccounts }} {{ $totalAccounts == 1 ? 'conta' : 'contas' }}</p>
        </div>

        <div class="rounded-xl p-6" style="background-color: rgb(var(--card)); border: 1px solid rgb(var(--border)); box-shadow: var(--shadow);">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium" style="color: rgb(var(--text-secondary));">Receitas do Mês</h3>
                <svg class="w-6 h-6" style="color: rgb(34, 197, 94);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                </svg>
            </div>
            <p class="text-3xl font-bold" style="color: rgb(34, 197, 94);">R$ {{ number_format($monthlyRevenue, 2, ',', '.') }}</p>
        </div>

        <div class="rounded-xl p-6" style="background-color: rgb(var(--card)); border: 1px solid rgb(var(--border)); box-shadow: var(--shadow);">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium" style="color: rgb(var(--text-secondary));">Despesas do Mês</h3>
                <svg class="w-6 h-6" style="color: rgb(239, 68, 68);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                </svg>
            </div>
            <p class="text-3xl font-bold" style="color: rgb(239, 68, 68);">R$ {{ number_format($monthlyExpenses, 2, ',', '.') }}</p>
        </div>

        <div class="rounded-xl p-6" style="background-color: rgb(var(--card)); border: 1px solid rgb(var(--border)); box-shadow: var(--shadow);">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium" style="color: rgb(var(--text-secondary));">Saldo do Mês</h3>
                <svg class="w-6 h-6" style="color: rgb(var(--primary));" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
            <p class="text-3xl font-bold" style="color: {{ $monthlyBalance >= 0 ? 'rgb(34, 197, 94)' : 'rgb(239, 68, 68)' }};">R$ {{ number_format($monthlyBalance, 2, ',', '.') }}</p>
        </div>
    </div>

    <!-- Contas a Pagar e Receber -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="rounded-xl p-6" style="background-color: rgb(var(--card)); border: 1px solid rgb(var(--border)); box-shadow: var(--shadow);">
            <h3 class="text-lg font-bold mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2" style="color: rgb(239, 68, 68);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Contas a Pagar
            </h3>
            <div class="space-y-2">
                <div class="flex justify-between items-center">
                    <span class="text-sm" style="color: rgb(var(--text-secondary));">Total Pendente</span>
                    <span class="text-lg font-bold">R$ {{ number_format($totalPayables, 2, ',', '.') }}</span>
                </div>
                @if($overduePayables > 0)
                <div class="flex justify-between items-center p-2 rounded-lg" style="background-color: rgba(239, 68, 68, 0.1);">
                    <span class="text-sm" style="color: rgb(239, 68, 68);">Vencidas</span>
                    <span class="text-sm font-bold" style="color: rgb(239, 68, 68);">R$ {{ number_format($overduePayables, 2, ',', '.') }}</span>
                </div>
                @endif
            </div>
        </div>

        <div class="rounded-xl p-6" style="background-color: rgb(var(--card)); border: 1px solid rgb(var(--border)); box-shadow: var(--shadow);">
            <h3 class="text-lg font-bold mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2" style="color: rgb(34, 197, 94);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Contas a Receber
            </h3>
            <div class="space-y-2">
                <div class="flex justify-between items-center">
                    <span class="text-sm" style="color: rgb(var(--text-secondary));">Total Pendente</span>
                    <span class="text-lg font-bold">R$ {{ number_format($totalReceivables, 2, ',', '.') }}</span>
                </div>
                @if($overdueReceivables > 0)
                <div class="flex justify-between items-center p-2 rounded-lg" style="background-color: rgba(239, 68, 68, 0.1);">
                    <span class="text-sm" style="color: rgb(239, 68, 68);">Vencidas</span>
                    <span class="text-sm font-bold" style="color: rgb(239, 68, 68);">R$ {{ number_format($overdueReceivables, 2, ',', '.') }}</span>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Metas Financeiras -->
    @if($totalGoals > 0)
    <div class="rounded-xl p-6" style="background-color: rgb(var(--card)); border: 1px solid rgb(var(--border)); box-shadow: var(--shadow);">
        <h3 class="text-lg font-bold mb-4">Metas Financeiras</h3>
        <div class="space-y-4">
            @foreach($goalsProgress as $goal)
            <div>
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-medium">{{ $goal['name'] }}</span>
                    <span class="text-sm" style="color: rgb(var(--text-secondary));">{{ number_format($goal['progress'], 1) }}%</span>
                </div>
                <div class="w-full h-2 rounded-full" style="background-color: rgb(var(--bg-secondary));">
                    <div class="h-2 rounded-full transition-all" style="background: linear-gradient(90deg, rgb(var(--primary)), rgb(var(--primary-dark))); width: {{ min(100, $goal['progress']) }}%;"></div>
                </div>
                <div class="flex justify-between text-xs mt-1" style="color: rgb(var(--text-secondary));">
                    <span>R$ {{ number_format($goal['current'], 2, ',', '.') }}</span>
                    <span>R$ {{ number_format($goal['target'], 2, ',', '.') }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Transações Recentes -->
    <div class="rounded-xl p-6" style="background-color: rgb(var(--card)); border: 1px solid rgb(var(--border)); box-shadow: var(--shadow);">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold">Transações Recentes</h3>
            <a href="{{ route('finance.transactions.index') }}" class="text-sm font-medium" style="color: rgb(var(--primary));">Ver todas</a>
        </div>
        @if($recentTransactions->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr style="border-bottom: 1px solid rgb(var(--border));">
                        <th class="text-left py-2 text-sm font-medium" style="color: rgb(var(--text-secondary));">Data</th>
                        <th class="text-left py-2 text-sm font-medium" style="color: rgb(var(--text-secondary));">Descrição</th>
                        <th class="text-left py-2 text-sm font-medium" style="color: rgb(var(--text-secondary));">Conta</th>
                        <th class="text-right py-2 text-sm font-medium" style="color: rgb(var(--text-secondary));">Valor</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentTransactions as $transaction)
                    <tr style="border-bottom: 1px solid rgb(var(--border));">
                        <td class="py-3 text-sm">{{ $transaction->date->format('d/m/Y') }}</td>
                        <td class="py-3 text-sm">{{ $transaction->description }}</td>
                        <td class="py-3 text-sm">{{ $transaction->account->name ?? '-' }}</td>
                        <td class="py-3 text-sm text-right font-medium" style="color: {{ $transaction->type === 'revenue' ? 'rgb(34, 197, 94)' : 'rgb(239, 68, 68)' }};">
                            {{ $transaction->type === 'revenue' ? '+' : '-' }} R$ {{ number_format($transaction->amount, 2, ',', '.') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <p class="text-sm text-center py-8" style="color: rgb(var(--text-secondary));">Nenhuma transação recente</p>
        @endif
    </div>
</div>
@endsection

