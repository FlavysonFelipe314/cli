<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\FinancialGoal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class FinancialGoalController extends Controller
{
    public function index()
    {
        $goals = FinancialGoal::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        $activeGoals = $goals->where('status', 'active');
        $totalRevenue = $activeGoals->where('type', 'Receita')->sum('current_value');
        $totalProfit = $activeGoals->where('type', 'Lucro')->sum('current_value');
        $averageProgress = $activeGoals->count() > 0 
            ? $activeGoals->map(fn($g) => $g->progress)->avg() 
            : 0;
        $budgetsCount = 0; // Pode ser expandido no futuro

        return view('finance.planning.index', compact('goals', 'activeGoals', 'totalRevenue', 'totalProfit', 'averageProgress', 'budgetsCount'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:Receita,Lucro,Despesa',
            'scope' => 'required|in:PF,PJ',
            'target_value' => 'required|numeric|min:0.01',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'description' => 'nullable|string|max:1000',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['current_value'] = 0;
        $validated['status'] = 'active';

        FinancialGoal::create($validated);

        return redirect()->route('finance.planning.index')
            ->with('success', 'Meta financeira criada com sucesso!');
    }

    public function update(Request $request, FinancialGoal $financialGoal)
    {
        Gate::authorize('update', $financialGoal);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:Receita,Lucro,Despesa',
            'scope' => 'required|in:PF,PJ',
            'target_value' => 'required|numeric|min:0.01',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'description' => 'nullable|string|max:1000',
        ]);

        $financialGoal->update($validated);

        return redirect()->route('finance.planning.index')
            ->with('success', 'Meta financeira atualizada com sucesso!');
    }

    public function destroy(FinancialGoal $financialGoal)
    {
        Gate::authorize('delete', $financialGoal);
        $financialGoal->delete();
        
        return redirect()->route('finance.planning.index')
            ->with('success', 'Meta financeira removida com sucesso!');
    }
}
