<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\BankReconciliation;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class BankReconciliationController extends Controller
{
    public function index(Request $request)
    {
        $query = BankReconciliation::where('user_id', Auth::id())
            ->with(['account', 'transaction'])
            ->orderBy('statement_date', 'desc');

        if ($request->has('account_id') && $request->account_id) {
            $query->where('account_id', $request->account_id);
        }

        if ($request->has('start_date') && $request->start_date) {
            $query->where('statement_date', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date) {
            $query->where('statement_date', '<=', $request->end_date);
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $reconciliations = $query->paginate(20);
        $accounts = Account::where('user_id', Auth::id())
            ->where('active', true)
            ->orderBy('name')
            ->get();

        $summary = [
            'pending' => BankReconciliation::where('user_id', Auth::id())
                ->where('status', 'pending')
                ->count(),
            'reconciled' => BankReconciliation::where('user_id', Auth::id())
                ->where('status', 'reconciled')
                ->count(),
            'ignored' => BankReconciliation::where('user_id', Auth::id())
                ->where('status', 'ignored')
                ->count(),
        ];

        return view('finance.reconciliations.index', compact('reconciliations', 'accounts', 'summary'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'statement_date' => 'required|date',
            'statement_amount' => 'required|numeric',
            'statement_description' => 'nullable|string|max:255',
            'transaction_id' => 'nullable|exists:transactions,id',
            'notes' => 'nullable|string|max:1000',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['status'] = 'pending';

        BankReconciliation::create($validated);

        return redirect()->route('finance.reconciliations.index')
            ->with('success', 'Extrato importado com sucesso!');
    }

    public function update(Request $request, BankReconciliation $reconciliation)
    {
        Gate::authorize('update', $reconciliation);

        $validated = $request->validate([
            'status' => 'required|in:pending,reconciled,ignored',
            'transaction_id' => 'nullable|exists:transactions,id',
            'reconciled_at' => 'nullable|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validated['status'] === 'reconciled' && !$validated['reconciled_at']) {
            $validated['reconciled_at'] = now();
        }

        $reconciliation->update($validated);

        return redirect()->route('finance.reconciliations.index')
            ->with('success', 'Conciliação atualizada com sucesso!');
    }

    public function destroy(BankReconciliation $reconciliation)
    {
        Gate::authorize('delete', $reconciliation);
        $reconciliation->delete();
        
        return redirect()->route('finance.reconciliations.index')
            ->with('success', 'Conciliação removida com sucesso!');
    }
}
