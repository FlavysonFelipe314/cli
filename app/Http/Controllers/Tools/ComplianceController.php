<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use App\Models\FiscalObligation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ComplianceController extends Controller
{
    public function index(Request $request)
    {
        $query = FiscalObligation::where('user_id', Auth::id())
            ->orderBy('next_due_date', 'asc');

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $obligations = $query->get();

        // Calcular próximas datas de vencimento
        foreach ($obligations as $obligation) {
            if (!$obligation->next_due_date) {
                $obligation->next_due_date = $this->calculateNextDueDate($obligation);
                $obligation->save();
            }
        }

        $summary = [
            'total' => $obligations->count(),
            'next_7_days' => $obligations->where('next_due_date', '>=', now())
                ->where('next_due_date', '<=', now()->addDays(7))
                ->where('status', '!=', 'completed')
                ->count(),
            'pending' => $obligations->where('status', 'pending')->count(),
            'overdue' => $obligations->where('status', 'overdue')->count(),
            'completed' => $obligations->where('status', 'completed')->count(),
        ];

        return view('tools.compliance.index', compact('obligations', 'summary'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'periodicity' => 'required|in:daily,weekly,monthly,quarterly,yearly',
            'scope' => 'required|in:PF,PJ',
            'due_day' => 'required|integer|min:1|max:31',
            'responsible' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['status'] = 'pending';
        
        // Criar objeto temporário para calcular data
        $tempObligation = (object)[
            'due_day' => (int)$validated['due_day'],
            'periodicity' => $validated['periodicity'],
        ];
        $validated['next_due_date'] = $this->calculateNextDueDate($tempObligation);

        FiscalObligation::create($validated);

        return redirect()->route('tools.compliance.index')
            ->with('success', 'Obrigação fiscal criada com sucesso!');
    }

    public function update(Request $request, FiscalObligation $fiscalObligation)
    {
        Gate::authorize('update', $fiscalObligation);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'periodicity' => 'required|in:daily,weekly,monthly,quarterly,yearly',
            'scope' => 'required|in:PF,PJ',
            'due_day' => 'required|integer|min:1|max:31',
            'responsible' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'status' => 'required|in:pending,completed,overdue,cancelled',
        ]);

        if ($validated['status'] === 'completed' && !$fiscalObligation->last_completed_at) {
            $validated['last_completed_at'] = now();
            
            // Criar objeto temporário para calcular data
            $tempObligation = (object)[
                'due_day' => (int)$validated['due_day'],
                'periodicity' => $validated['periodicity'],
            ];
            $validated['next_due_date'] = $this->calculateNextDueDate($tempObligation);
        }

        $fiscalObligation->update($validated);

        return redirect()->route('tools.compliance.index')
            ->with('success', 'Obrigação fiscal atualizada com sucesso!');
    }

    public function destroy(FiscalObligation $fiscalObligation)
    {
        Gate::authorize('delete', $fiscalObligation);
        $fiscalObligation->delete();
        
        return redirect()->route('tools.compliance.index')
            ->with('success', 'Obrigação fiscal removida com sucesso!');
    }

    private function calculateNextDueDate($obligation)
    {
        $now = Carbon::now();
        $day = is_numeric($obligation->due_day) ? (int)$obligation->due_day : 1;
        $day = max(1, min(31, $day)); // Garantir que está entre 1 e 31

        switch ($obligation->periodicity) {
            case 'daily':
                return $now->copy()->addDay();
            case 'weekly':
                return $now->copy()->next(Carbon::MONDAY);
            case 'monthly':
                $date = $now->copy();
                $maxDay = min($day, $date->daysInMonth);
                $date->day($maxDay);
                if ($date->isPast()) {
                    $date->addMonth();
                    $maxDay = min($day, $date->daysInMonth);
                    $date->day($maxDay);
                }
                return $date;
            case 'quarterly':
                $date = $now->copy();
                $maxDay = min($day, $date->daysInMonth);
                $date->day($maxDay);
                while ($date->isPast() || !in_array($date->month, [1, 4, 7, 10])) {
                    $date->addMonth();
                    $maxDay = min($day, $date->daysInMonth);
                    $date->day($maxDay);
                }
                return $date;
            case 'yearly':
                $date = $now->copy()->month(1);
                $maxDay = min($day, $date->daysInMonth);
                $date->day($maxDay);
                if ($date->isPast()) {
                    $date->addYear();
                    $maxDay = min($day, $date->daysInMonth);
                    $date->day($maxDay);
                }
                return $date;
            default:
                return $now->copy()->addMonth();
        }
    }
}
