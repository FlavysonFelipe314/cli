<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskColumn;
use App\Models\TeamMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class TaskController extends Controller
{
    public function index(Request $request, TeamMember $team = null)
    {
        $teamOwnerId = Auth::id();
        $isTeamView = false;

        // Se for visualização de equipe específica
        if ($team) {
            // Verificar se o usuário faz parte desta equipe
            $userIsMember = $team->members()->where('user_id', Auth::id())->exists() || 
                           $team->member_user_id === Auth::id() ||
                           $team->user_id === Auth::id();
            
            if (!$userIsMember) {
                abort(403, 'Você não tem acesso a esta equipe.');
            }

            $teamOwnerId = $team->user_id;
            $isTeamView = true;
        } else {
            // Se não for equipe, verificar se tem plano
            if (!Auth::user()->hasActiveSubscription()) {
                return redirect()->route('subscriptions.index')
                    ->with('error', 'Você precisa de um plano ativo para acessar suas tarefas.');
            }
        }

        $columns = TaskColumn::where('user_id', $teamOwnerId)
            ->orderBy('order')
            ->with(['tasks' => function($query) {
                $query->orderBy('order')->with('assignedUser');
            }])
            ->get();

        $tasks = Task::where('user_id', $teamOwnerId)
            ->with(['column', 'assignedUser']);

        if ($request->has('status') && $request->status) {
            $tasks->where('status', $request->status);
        }
        if ($request->has('priority') && $request->priority) {
            $tasks->where('priority', $request->priority);
        }
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $tasks->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $allTasks = $tasks->get();

        $summary = [
            'total' => $allTasks->count(),
            'pending' => $allTasks->where('status', 'pending')->count(),
            'in_progress' => $allTasks->where('status', 'in_progress')->count(),
            'completed' => $allTasks->where('status', 'completed')->count(),
            'overdue' => $allTasks->filter(function($task) {
                return $task->due_date && $task->due_date->isPast() && $task->status !== 'completed';
            })->count(),
        ];

        // Buscar membros da equipe (incluindo o próprio usuário)
        $teamMembers = TeamMember::where('user_id', $teamOwnerId)
            ->where('status', 'active')
            ->whereNotNull('member_user_id')
            ->with('member')
            ->get();

        // Adicionar o próprio usuário à lista
        $allAssignees = collect([[
            'id' => $teamOwnerId,
            'name' => \App\Models\User::find($teamOwnerId)->name,
            'email' => \App\Models\User::find($teamOwnerId)->email,
        ]])->merge($teamMembers->map(function($member) {
            return [
                'id' => $member->member_user_id,
                'name' => $member->member->name ?? $member->name,
                'email' => $member->email,
            ];
        }));

        return view('management.tasks.index', compact('columns', 'summary', 'allAssignees', 'isTeamView', 'team', 'teamOwnerId'));
    }

    public function store(Request $request, TeamMember $team = null)
    {
        $teamOwnerId = Auth::id();

        // Se for tarefa de equipe, verificar permissão
        if ($team) {
            $userIsMember = $team->members()->where('user_id', Auth::id())->exists() || 
                           $team->member_user_id === Auth::id() ||
                           $team->user_id === Auth::id();
            
            if (!$userIsMember) {
                abort(403, 'Você não tem acesso a esta equipe.');
            }

            $teamOwnerId = $team->user_id;
        } else {
            // Se não for equipe, verificar se tem plano
            if (!Auth::user()->hasActiveSubscription()) {
                return redirect()->route('subscriptions.index')
                    ->with('error', 'Você precisa de um plano ativo para criar tarefas.');
            }
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'priority' => 'required|in:baixa,média,alta,urgente',
            'due_date' => 'nullable|date',
            'task_column_id' => 'required|exists:task_columns,id',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        // Verificar se a coluna pertence ao dono da equipe
        $column = TaskColumn::findOrFail($validated['task_column_id']);
        if ($column->user_id !== $teamOwnerId) {
            abort(403, 'Coluna inválida.');
        }

        $maxOrder = Task::where('task_column_id', $validated['task_column_id'])->max('order') ?? 0;

        $validated['user_id'] = $teamOwnerId;
        $validated['status'] = 'pending';
        $validated['order'] = $maxOrder + 1;

        Task::create($validated);

        $redirectRoute = $team 
            ? route('team.tasks', $team)
            : route('management.tasks.index');

        return redirect($redirectRoute)
            ->with('success', 'Tarefa criada com sucesso!');
    }

    public function update(Request $request, Task $task)
    {
        Gate::authorize('update', $task);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'priority' => 'required|in:baixa,média,alta,urgente',
            'due_date' => 'nullable|date',
            'task_column_id' => 'required|exists:task_columns,id',
            'assigned_to' => 'nullable|exists:users,id',
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'order' => 'nullable|integer',
        ]);

        $task->update($validated);

        return response()->json(['success' => true]);
    }

    public function destroy(Task $task)
    {
        Gate::authorize('delete', $task);
        $task->delete();
        
        return redirect()->route('management.tasks.index')
            ->with('success', 'Tarefa removida com sucesso!');
    }

    public function move(Request $request, Task $task)
    {
        Gate::authorize('update', $task);

        $validated = $request->validate([
            'task_column_id' => 'required|exists:task_columns,id',
            'order' => 'required|integer',
        ]);

        $task->update([
            'task_column_id' => $validated['task_column_id'],
            'order' => $validated['order'],
        ]);

        return response()->json(['success' => true]);
    }
}
