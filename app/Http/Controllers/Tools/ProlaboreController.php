<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use App\Models\ProlaboreCalculation;
use App\Models\ProlaborePartner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProlaboreController extends Controller
{
    public function index()
    {
        return view('tools.prolabore.index');
    }

    public function calculate(Request $request)
    {
        $validated = $request->validate([
            'monthly_revenue' => 'required|numeric|min:0',
            'fixed_costs' => 'required|numeric|min:0',
            'variable_costs_percentage' => 'required|numeric|min:0|max:100',
            'current_prolabore' => 'nullable|numeric|min:0',
            'profit_margin_percentage' => 'required|numeric|min:0|max:100',
            'reinvestment_percentage' => 'required|numeric|min:0|max:100',
            'reserve_percentage' => 'required|numeric|min:0|max:100',
            'partners' => 'required|array|min:1',
            'partners.*.name' => 'required|string|max:255',
            'partners.*.distribution_percentage' => 'required|numeric|min:0|max:100',
        ]);

        // Validar que a soma das distribuições seja 100%
        $totalDistribution = collect($validated['partners'])->sum('distribution_percentage');
        if (abs($totalDistribution - 100) > 0.01) {
            return back()->withErrors(['partners' => 'A soma das distribuições deve ser 100%'])->withInput();
        }

        // Calcular valores
        $variableCosts = ($validated['monthly_revenue'] * $validated['variable_costs_percentage']) / 100;
        $netProfit = $validated['monthly_revenue'] - $validated['fixed_costs'] - $variableCosts;
        $reinvestment = ($netProfit * $validated['reinvestment_percentage']) / 100;
        $reserve = ($netProfit * $validated['reserve_percentage']) / 100;
        $availableForProlabore = $netProfit - $reinvestment - $reserve;

        return response()->json([
            'variable_costs' => $variableCosts,
            'net_profit' => $netProfit,
            'reinvestment' => $reinvestment,
            'reserve' => $reserve,
            'available_for_prolabore' => $availableForProlabore,
        ]);
    }
}
