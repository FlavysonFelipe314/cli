<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $accounts = Account::where('user_id', Auth::id())
            ->where('active', true)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('finance.accounts.index', compact('accounts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->route('finance.accounts.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'nullable|string|max:255',
            'bank' => 'nullable|string|max:255',
            'agency' => 'nullable|string|max:50',
            'account_number' => 'nullable|string|max:50',
            'holder' => 'nullable|string|max:255',
            'cpf' => 'nullable|string|max:20',
            'pix_key' => 'nullable|string|max:255',
            'balance' => 'nullable|numeric|min:0',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['balance'] = $validated['balance'] ?? 0;

        Account::create($validated);

        return redirect()->route('finance.accounts.index')
            ->with('success', 'Conta criada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Account $account)
    {
        Gate::authorize('view', $account);
        return view('finance.accounts.show', compact('account'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Account $account)
    {
        Gate::authorize('update', $account);
        return redirect()->route('finance.accounts.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Account $account)
    {
        Gate::authorize('update', $account);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'nullable|string|max:255',
            'bank' => 'nullable|string|max:255',
            'agency' => 'nullable|string|max:50',
            'account_number' => 'nullable|string|max:50',
            'holder' => 'nullable|string|max:255',
            'cpf' => 'nullable|string|max:20',
            'pix_key' => 'nullable|string|max:255',
            'balance' => 'nullable|numeric|min:0',
        ]);

        $account->update($validated);

        return redirect()->route('finance.accounts.index')
            ->with('success', 'Conta atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Account $account)
    {
        Gate::authorize('delete', $account);
        $account->update(['active' => false]);
        
        return redirect()->route('finance.accounts.index')
            ->with('success', 'Conta removida com sucesso!');
    }
}
