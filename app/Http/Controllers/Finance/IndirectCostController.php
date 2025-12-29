<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\IndirectCost;
use App\Models\IndirectCostAllocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class IndirectCostController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);

        $costs = IndirectCost::where('user_id', Auth::id())
            ->where('reference_month', $month)
            ->where('reference_year', $year)
            ->orderBy('created_at', 'desc')
            ->get();

        $allocation = IndirectCostAllocation::where('user_id', Auth::id())
            ->where('reference_month', $month)
            ->where('reference_year', $year)
            ->first();

        $totalIncluded = $costs->where('include_in_allocation', true)->sum('monthly_value');

        return view('finance.indirect-costs.index', compact('costs', 'allocation', 'month', 'year', 'totalIncluded'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'type' => 'required|in:Fixo,Variável',
            'monthly_value' => 'required|numeric|min:0',
            'cost_center' => 'nullable|string|max:255',
            'include_in_allocation' => 'boolean',
            'reference_month' => 'required|integer|min:1|max:12',
            'reference_year' => 'required|integer|min:2000|max:2100',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['include_in_allocation'] = $request->has('include_in_allocation');

        IndirectCost::create($validated);

        return redirect()->route('finance.indirect-costs.index', [
            'month' => $validated['reference_month'],
            'year' => $validated['reference_year']
        ])->with('success', 'Custo indireto adicionado com sucesso!');
    }

    public function update(Request $request, IndirectCost $indirectCost)
    {
        Gate::authorize('update', $indirectCost);

        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'type' => 'required|in:Fixo,Variável',
            'monthly_value' => 'required|numeric|min:0',
            'cost_center' => 'nullable|string|max:255',
            'include_in_allocation' => 'boolean',
        ]);

        $validated['include_in_allocation'] = $request->has('include_in_allocation');

        $indirectCost->update($validated);

        return redirect()->route('finance.indirect-costs.index', [
            'month' => $indirectCost->reference_month,
            'year' => $indirectCost->reference_year
        ])->with('success', 'Custo indireto atualizado com sucesso!');
    }

    public function destroy(IndirectCost $indirectCost)
    {
        Gate::authorize('delete', $indirectCost);
        $month = $indirectCost->reference_month;
        $year = $indirectCost->reference_year;
        $indirectCost->delete();
        
        return redirect()->route('finance.indirect-costs.index', compact('month', 'year'))
            ->with('success', 'Custo indireto removido com sucesso!');
    }
}
