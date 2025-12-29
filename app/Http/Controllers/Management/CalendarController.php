<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\CalendarEvent;
use App\Models\Payable;
use App\Models\Receivable;
use App\Models\FiscalObligation;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', now()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->addMonth()->format('Y-m-d'));
        $eventTypes = $request->input('event_types', ['conta_pagar', 'conta_receber', 'obrigacao_fiscal', 'tarefa']);
        $scope = $request->input('scope', 'Todos');

        $events = collect();

        // Buscar contas a pagar
        if (in_array('conta_pagar', $eventTypes)) {
            $payables = Payable::where('user_id', Auth::id())
                ->whereBetween('due_date', [$startDate, $endDate])
                ->get();
            
            foreach ($payables as $payable) {
                $events->push([
                    'id' => 'payable_' . $payable->id,
                    'title' => $payable->description,
                    'description' => 'Conta a Pagar',
                    'event_date' => $payable->due_date,
                    'event_type' => 'conta_pagar',
                    'scope' => $payable->type === 'Pessoa Física (PF)' ? 'PF' : 'PJ',
                    'color' => '#ef4444',
                ]);
            }
        }

        // Buscar contas a receber
        if (in_array('conta_receber', $eventTypes)) {
            $receivables = Receivable::where('user_id', Auth::id())
                ->whereBetween('due_date', [$startDate, $endDate])
                ->get();
            
            foreach ($receivables as $receivable) {
                $events->push([
                    'id' => 'receivable_' . $receivable->id,
                    'title' => $receivable->description,
                    'description' => 'Conta a Receber',
                    'event_date' => $receivable->due_date,
                    'event_type' => 'conta_receber',
                    'scope' => $receivable->type === 'Pessoa Física (PF)' ? 'PF' : 'PJ',
                    'color' => '#22c55e',
                ]);
            }
        }

        // Buscar obrigações fiscais
        if (in_array('obrigacao_fiscal', $eventTypes)) {
            $obligations = FiscalObligation::where('user_id', Auth::id())
                ->whereBetween('next_due_date', [$startDate, $endDate])
                ->get();
            
            foreach ($obligations as $obligation) {
                $events->push([
                    'id' => 'obligation_' . $obligation->id,
                    'title' => $obligation->name,
                    'description' => 'Obrigação Fiscal',
                    'event_date' => $obligation->next_due_date,
                    'event_type' => 'obrigacao_fiscal',
                    'scope' => $obligation->scope,
                    'color' => '#f59e0b',
                ]);
            }
        }

        // Buscar tarefas
        if (in_array('tarefa', $eventTypes)) {
            $tasks = Task::where('user_id', Auth::id())
                ->whereNotNull('due_date')
                ->whereBetween('due_date', [$startDate, $endDate])
                ->get();
            
            foreach ($tasks as $task) {
                $events->push([
                    'id' => 'task_' . $task->id,
                    'title' => $task->title,
                    'description' => 'Tarefa',
                    'event_date' => $task->due_date,
                    'event_type' => 'tarefa',
                    'scope' => 'Todos',
                    'color' => '#3b82f6',
                ]);
            }
        }

        // Filtrar por escopo
        if ($scope !== 'Todos') {
            $events = $events->filter(function($event) use ($scope) {
                return $event['scope'] === $scope || $event['scope'] === 'Todos';
            });
        }

        $events = $events->sortBy('event_date')->values();

        return view('management.calendar.index', compact('events', 'startDate', 'endDate', 'eventTypes', 'scope'));
    }
}
