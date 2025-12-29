<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Transaction::where('user_id', Auth::id())
            ->with('account')
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc');

        if ($request->has('account_id') && $request->account_id) {
            $query->where('account_id', $request->account_id);
        }

        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('notes', 'like', "%{$search}%")
                  ->orWhere('payment_method', 'like', "%{$search}%")
                  ->orWhereHas('account', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $transactions = $query->paginate(20);
        $accounts = Account::where('user_id', Auth::id())
            ->where('active', true)
            ->orderBy('name')
            ->get();

        $summary = [
            'receita' => Transaction::where('user_id', Auth::id())
                ->where('type', 'receita')
                ->when($request->account_id, fn($q) => $q->where('account_id', $request->account_id))
                ->sum('amount'),
            'despesa' => Transaction::where('user_id', Auth::id())
                ->where('type', 'despesa')
                ->when($request->account_id, fn($q) => $q->where('account_id', $request->account_id))
                ->sum('amount'),
        ];

        $summary['saldo'] = $summary['receita'] - $summary['despesa'];

        return view('finance.transactions.index', compact('transactions', 'accounts', 'summary'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->route('finance.transactions.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'description' => 'required|string|max:255',
            'type' => 'required|in:receita,despesa',
            'payment_method' => 'nullable|in:pix,cartao_credito,cartao_debito,dinheiro,transferencia,boleto,outro',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $account = Account::findOrFail($validated['account_id']);
        Gate::authorize('update', $account);

        DB::transaction(function () use ($validated, $account) {
            $validated['user_id'] = Auth::id();
            $transaction = Transaction::create($validated);

            // Atualizar saldo da conta
            if ($validated['type'] === 'receita') {
                $account->increment('balance', $validated['amount']);
            } else {
                $account->decrement('balance', $validated['amount']);
            }
        });

        return redirect()->route('finance.transactions.index')
            ->with('success', 'Transação criada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        Gate::authorize('view', $transaction);
        return view('finance.transactions.show', compact('transaction'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        Gate::authorize('update', $transaction);
        return redirect()->route('finance.transactions.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        Gate::authorize('update', $transaction);

        $validated = $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'description' => 'required|string|max:255',
            'type' => 'required|in:receita,despesa',
            'payment_method' => 'nullable|in:pix,cartao_credito,cartao_debito,dinheiro,transferencia,boleto,outro',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $oldAccount = $transaction->account;
        $newAccount = Account::findOrFail($validated['account_id']);

        DB::transaction(function () use ($validated, $transaction, $oldAccount, $newAccount) {
            $oldAmount = $transaction->amount;
            $oldType = $transaction->type;

            // Reverter saldo da conta antiga
            if ($oldType === 'receita') {
                $oldAccount->decrement('balance', $oldAmount);
            } else {
                $oldAccount->increment('balance', $oldAmount);
            }

            // Atualizar transação
            $transaction->update($validated);

            // Aplicar novo saldo
            if ($validated['type'] === 'receita') {
                $newAccount->increment('balance', $validated['amount']);
            } else {
                $newAccount->decrement('balance', $validated['amount']);
            }
        });

        return redirect()->route('finance.transactions.index')
            ->with('success', 'Transação atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        Gate::authorize('delete', $transaction);

        DB::transaction(function () use ($transaction) {
            $account = $transaction->account;

            // Reverter saldo
            if ($transaction->type === 'receita') {
                $account->decrement('balance', $transaction->amount);
            } else {
                $account->increment('balance', $transaction->amount);
            }

            $transaction->delete();
        });

        return redirect()->route('finance.transactions.index')
            ->with('success', 'Transação removida com sucesso!');
    }
}
