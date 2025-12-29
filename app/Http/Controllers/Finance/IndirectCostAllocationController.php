<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\IndirectCost;
use App\Models\IndirectCostAllocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IndirectCostAllocationController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'reference_month' => 'required|integer|min:1|max:12',
            'reference_year' => 'required|integer|min:2000|max:2100',
            'allocation_mode' => 'required|in:Simples,Avançado',
            'allocation_base' => 'required|in:percent_revenue,cost_per_unit,cost_per_hour',
        ]);

        DB::beginTransaction();
        try {
            $totalCosts = IndirectCost::where('user_id', Auth::id())
                ->where('reference_month', $validated['reference_month'])
                ->where('reference_year', $validated['reference_year'])
                ->where('include_in_allocation', true)
                ->sum('monthly_value');

            // Calcular receita total do período (pode ser expandido)
            $totalRevenue = 0; // Implementar cálculo de receita

            $allocation = IndirectCostAllocation::updateOrCreate(
                [
                    'user_id' => Auth::id(),
                    'reference_month' => $validated['reference_month'],
                    'reference_year' => $validated['reference_year'],
                ],
                [
                    'allocation_mode' => $validated['allocation_mode'],
                    'allocation_base' => $validated['allocation_base'],
                    'total_indirect_costs' => $totalCosts,
                    'total_revenue' => $totalRevenue,
                    'allocation_percentage' => $totalRevenue > 0 ? ($totalCosts / $totalRevenue) * 100 : 0,
                ]
            );

            DB::commit();

            return redirect()->route('finance.indirect-costs.index', [
                'month' => $validated['reference_month'],
                'year' => $validated['reference_year']
            ])->with('success', 'Configuração de rateio salva com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erro ao salvar configuração: ' . $e->getMessage());
        }
    }
}
