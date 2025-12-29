<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use App\Models\IndirectCostAllocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PricingController extends Controller
{
    public function index()
    {
        // Buscar rateio de custos indiretos do mês atual
        $allocation = IndirectCostAllocation::where('user_id', Auth::id())
            ->where('reference_month', now()->month)
            ->where('reference_year', now()->year)
            ->first();

        return view('tools.pricing.index', compact('allocation'));
    }

    public function calculate(Request $request)
    {
        $validated = $request->validate([
            'operation_type' => 'required|in:commerce,service',
            'name' => 'required|string|max:255',
            'purchase_price' => 'nullable|numeric|min:0',
            'freight' => 'nullable|numeric|min:0',
            'input_taxes' => 'nullable|numeric|min:0',
            'packaging' => 'nullable|numeric|min:0',
            'other_direct_costs' => 'nullable|numeric|min:0',
            'transport' => 'nullable|numeric|min:0',
            'accommodation' => 'nullable|numeric|min:0',
            'specific_materials' => 'nullable|numeric|min:0',
            'other_inputs' => 'nullable|numeric|min:0',
            'service_hours' => 'nullable|array',
            'service_hours.*.function_name' => 'required_with:service_hours|string|max:255',
            'service_hours.*.hourly_rate' => 'required_with:service_hours|numeric|min:0',
            'service_hours.*.hours' => 'required_with:service_hours|numeric|min:0',
            'tax_rate' => 'required|numeric|min:0|max:100',
            'desired_margin' => 'required|numeric|min:0|max:100',
        ]);

        // Calcular custos diretos
        $directCosts = 0;
        if ($validated['operation_type'] === 'commerce') {
            $directCosts = ($validated['purchase_price'] ?? 0) 
                + ($validated['freight'] ?? 0)
                + ($validated['input_taxes'] ?? 0)
                + ($validated['packaging'] ?? 0)
                + ($validated['other_direct_costs'] ?? 0);
        } else {
            // Serviços: somar horas * taxa
            $directCosts = ($validated['transport'] ?? 0)
                + ($validated['accommodation'] ?? 0)
                + ($validated['specific_materials'] ?? 0)
                + ($validated['other_inputs'] ?? 0);
            
            if (isset($validated['service_hours'])) {
                foreach ($validated['service_hours'] as $hour) {
                    $directCosts += ($hour['hourly_rate'] * $hour['hours']);
                }
            }
        }

        // Buscar custos indiretos (rateio)
        $allocation = IndirectCostAllocation::where('user_id', Auth::id())
            ->where('reference_month', now()->month)
            ->where('reference_year', now()->year)
            ->first();

        $indirectCosts = 0;
        if ($allocation && $allocation->allocation_base === 'percent_revenue') {
            // Calcular como % sobre o faturamento (será calculado depois)
            $indirectCostsPercentage = $allocation->allocation_percentage ?? 0;
        }

        // Calcular preço mínimo (sem margem)
        $totalCost = $directCosts + $indirectCosts;
        $minPrice = $totalCost / (1 - ($validated['tax_rate'] / 100));

        // Calcular preço ideal (com margem)
        $idealPrice = $minPrice / (1 - ($validated['desired_margin'] / 100));

        // Recalcular custos indiretos baseado no preço ideal
        if ($allocation && $allocation->allocation_base === 'percent_revenue') {
            $indirectCosts = ($idealPrice * $allocation->allocation_percentage) / 100;
            $totalCost = $directCosts + $indirectCosts;
            $minPrice = $totalCost / (1 - ($validated['tax_rate'] / 100));
            $idealPrice = $minPrice / (1 - ($validated['desired_margin'] / 100));
        }

        $taxes = ($idealPrice * $validated['tax_rate']) / 100;
        $profit = $idealPrice - $totalCost - $taxes;
        $realMargin = $idealPrice > 0 ? ($profit / $idealPrice) * 100 : 0;

        return response()->json([
            'direct_costs' => $directCosts,
            'indirect_costs' => $indirectCosts,
            'taxes' => $taxes,
            'total_cost' => $totalCost,
            'min_price' => $minPrice,
            'ideal_price' => $idealPrice,
            'profit' => $profit,
            'real_margin' => $realMargin,
        ]);
    }
}
