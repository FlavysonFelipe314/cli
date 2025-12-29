<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Services\AsaasService;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    protected AsaasService $asaasService;

    public function __construct(AsaasService $asaasService)
    {
        $this->asaasService = $asaasService;
    }

    public function index()
    {
        $plans = Plan::orderBy('sort_order')->get();
        return view('admin.plans.index', compact('plans'));
    }

    public function create()
    {
        $modules = \App\Models\Module::where('active', true)->orderBy('category')->orderBy('sort_order')->get();
        return view('admin.plans.create', compact('modules'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:plans',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'billing_cycle' => 'required|in:monthly,yearly',
            'features' => 'nullable|array',
            'allowed_modules' => 'nullable|array',
            'allowed_modules.*' => 'exists:modules,slug',
            'max_accounts' => 'nullable|integer',
            'max_transactions_per_month' => 'nullable|integer',
            'active' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        Plan::create($validated);

        return redirect()->route('admin.plans.index')
            ->with('success', 'Plano criado com sucesso!');
    }

    public function edit(Plan $plan)
    {
        $modules = \App\Models\Module::where('active', true)->orderBy('category')->orderBy('sort_order')->get();
        return view('admin.plans.edit', compact('plan', 'modules'));
    }

    public function update(Request $request, Plan $plan)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:plans,slug,' . $plan->id,
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'billing_cycle' => 'required|in:monthly,yearly',
            'features' => 'nullable|array',
            'allowed_modules' => 'nullable|array',
            'allowed_modules.*' => 'exists:modules,slug',
            'max_accounts' => 'nullable|integer',
            'max_transactions_per_month' => 'nullable|integer',
            'active' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $plan->update($validated);

        return redirect()->route('admin.plans.index')
            ->with('success', 'Plano atualizado com sucesso!');
    }

    public function destroy(Plan $plan)
    {
        if ($plan->subscriptions()->count() > 0) {
            return back()->with('error', 'Não é possível excluir um plano que possui assinaturas ativas.');
        }

        $plan->delete();

        return redirect()->route('admin.plans.index')
            ->with('success', 'Plano excluído com sucesso!');
    }
}
