<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use App\Models\EmployeeCostProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class EmployeeCostController extends Controller
{
    public function index()
    {
        $profiles = EmployeeCostProfile::where('user_id', Auth::id())
            ->orderBy('name')
            ->get();

        return view('tools.employee-cost.index', compact('profiles'));
    }

    public function calculate(Request $request)
    {
        $validated = $request->validate([
            'gross_salary' => 'required|numeric|min:0',
            'monthly_hours' => 'required|integer|min:1',
            'transport_allowance' => 'nullable|numeric|min:0',
            'meal_allowance' => 'nullable|numeric|min:0',
            'health_insurance' => 'nullable|numeric|min:0',
            'other_benefits' => 'nullable|numeric|min:0',
            'inss_rate' => 'nullable|numeric|min:0|max:100',
            'fgts_rate' => 'nullable|numeric|min:0|max:100',
            'thirteenth_provision' => 'nullable|numeric|min:0|max:100',
            'vacation_provision' => 'nullable|numeric|min:0|max:100',
            'severance_provision' => 'nullable|numeric|min:0|max:100',
            'other_charges' => 'nullable|numeric|min:0|max:100',
            'equipment_tools' => 'nullable|numeric|min:0',
            'training' => 'nullable|numeric|min:0',
            'epi' => 'nullable|numeric|min:0',
        ]);

        $salary = $validated['gross_salary'];
        $benefits = ($validated['transport_allowance'] ?? 0)
            + ($validated['meal_allowance'] ?? 0)
            + ($validated['health_insurance'] ?? 0)
            + ($validated['other_benefits'] ?? 0);

        $chargesRate = ($validated['inss_rate'] ?? 20)
            + ($validated['fgts_rate'] ?? 8)
            + ($validated['thirteenth_provision'] ?? 8.33)
            + ($validated['vacation_provision'] ?? 11.11)
            + ($validated['severance_provision'] ?? 4)
            + ($validated['other_charges'] ?? 0);

        $charges = ($salary * $chargesRate) / 100;
        $otherCosts = ($validated['equipment_tools'] ?? 0)
            + ($validated['training'] ?? 0)
            + ($validated['epi'] ?? 0);

        $totalMonthly = $salary + $benefits + $charges + $otherCosts;
        $costPerHour = $totalMonthly / ($validated['monthly_hours']);
        $costPerDay = $costPerHour * 8;

        return response()->json([
            'salary_cost' => $salary,
            'benefits' => $benefits,
            'charges' => $charges,
            'charges_rate' => $chargesRate,
            'other_costs' => $otherCosts,
            'total_monthly' => $totalMonthly,
            'cost_per_hour' => $costPerHour,
            'cost_per_day' => $costPerDay,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'employee_name' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'cost_center' => 'nullable|string|max:255',
            'gross_salary' => 'required|numeric|min:0',
            'monthly_hours' => 'required|integer|min:1',
            'transport_allowance' => 'nullable|numeric|min:0',
            'meal_allowance' => 'nullable|numeric|min:0',
            'health_insurance' => 'nullable|numeric|min:0',
            'other_benefits' => 'nullable|numeric|min:0',
            'inss_rate' => 'nullable|numeric|min:0|max:100',
            'fgts_rate' => 'nullable|numeric|min:0|max:100',
            'thirteenth_provision' => 'nullable|numeric|min:0|max:100',
            'vacation_provision' => 'nullable|numeric|min:0|max:100',
            'severance_provision' => 'nullable|numeric|min:0|max:100',
            'other_charges' => 'nullable|numeric|min:0|max:100',
            'equipment_tools' => 'nullable|numeric|min:0',
            'training' => 'nullable|numeric|min:0',
            'epi' => 'nullable|numeric|min:0',
        ]);

        $validated['user_id'] = Auth::id();
        EmployeeCostProfile::create($validated);

        return redirect()->route('tools.employee-cost.index')
            ->with('success', 'Perfil salvo com sucesso!');
    }

    public function destroy(EmployeeCostProfile $employeeCostProfile)
    {
        Gate::authorize('delete', $employeeCostProfile);
        $employeeCostProfile->delete();
        
        return redirect()->route('tools.employee-cost.index')
            ->with('success', 'Perfil removido com sucesso!');
    }
}
