@extends('layouts.app')

@section('title', 'Gestão de Tarefas - CLIVUS')
@section('page-title', 'Gestão de Tarefas')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold mb-1">
                @if(isset($isTeamView) && $isTeamView && $team)
                Tarefas - {{ $team->name ?? 'Equipe' }}
                @else
                Gestão de Tarefas
                @endif
            </h2>
            <p class="text-sm" style="color: rgb(var(--text-secondary));">
                @if(isset($isTeamView) && $isTeamView && $team)
                Gerencie as tarefas da equipe de {{ $team->owner->name }}
                @else
                Gerencie suas tarefas com o quadro Kanban
                @endif
            </p>
        </div>
        <div class="flex gap-3">
            <button type="button" onclick="openColumnModal()" class="px-4 py-2 rounded-lg font-medium transition-all hover:scale-105" style="background-color: rgba(var(--primary), 0.1); color: rgb(var(--primary));">
                Nova Coluna
            </button>
            <button type="button" onclick="openTaskModal()" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-medium text-white transition-all hover:scale-105" style="background: linear-gradient(135deg, rgb(var(--primary)), rgb(var(--primary-dark))); box-shadow: 0 4px 6px -1px rgba(var(--primary), 0.3);">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>+ Nova Tarefa</span>
            </button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-5 gap-4">
        <div class="rounded-xl p-6" style="background-color: rgb(var(--card)); border: 1px solid rgb(var(--border)); box-shadow: var(--shadow);">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium" style="color: rgb(var(--text-secondary));">Total</span>
                <svg class="w-5 h-5" style="color: rgb(var(--primary));" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
            </div>
            <p class="text-2xl font-bold">{{ $summary['total'] }}</p>
        </div>
        <div class="rounded-xl p-6" style="background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(37, 99, 235, 0.1)); border: 1px solid rgb(59, 130, 246);">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium" style="color: rgb(37, 99, 235);">A Fazer</span>
                <svg class="w-5 h-5" style="color: rgb(59, 130, 246);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <p class="text-2xl font-bold" style="color: rgb(37, 99, 235);">{{ $summary['pending'] }}</p>
        </div>
        <div class="rounded-xl p-6" style="background: linear-gradient(135deg, rgba(251, 146, 60, 0.1), rgba(234, 88, 12, 0.1)); border: 1px solid rgb(251, 146, 60);">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium" style="color: rgb(234, 88, 12);">Em Andamento</span>
                <svg class="w-5 h-5" style="color: rgb(251, 146, 60);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <p class="text-2xl font-bold" style="color: rgb(234, 88, 12);">{{ $summary['in_progress'] }}</p>
        </div>
        <div class="rounded-xl p-6" style="background: linear-gradient(135deg, rgba(34, 197, 94, 0.1), rgba(22, 163, 74, 0.1)); border: 1px solid rgb(34, 197, 94);">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium" style="color: rgb(22, 163, 74);">Concluídas</span>
                <svg class="w-5 h-5" style="color: rgb(34, 197, 94);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <p class="text-2xl font-bold" style="color: rgb(22, 163, 74);">{{ $summary['completed'] }}</p>
        </div>
        <div class="rounded-xl p-6" style="background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), rgba(220, 38, 38, 0.1)); border: 1px solid rgb(239, 68, 68);">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium" style="color: rgb(220, 38, 38);">Vencidas</span>
                <svg class="w-5 h-5" style="color: rgb(239, 68, 68);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <p class="text-2xl font-bold" style="color: rgb(220, 38, 38);">{{ $summary['overdue'] }}</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="flex flex-col sm:flex-row gap-4">
        <select id="filterStatus" onchange="filterTasks()"
            class="px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
            <option value="">Status: Todos</option>
            <option value="pending">A Fazer</option>
            <option value="in_progress">Em Andamento</option>
            <option value="completed">Concluídas</option>
        </select>
        <select id="filterPriority" onchange="filterTasks()"
            class="px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
            <option value="">Prioridade: Todas</option>
            <option value="baixa">Baixa</option>
            <option value="média">Média</option>
            <option value="alta">Alta</option>
            <option value="urgente">Urgente</option>
        </select>
        <input type="text" id="searchInput" placeholder="Título ou descrição..." onkeyup="filterTasks()"
            class="flex-1 px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
    </div>

    <!-- Kanban Board -->
    <div class="flex gap-4 overflow-x-auto pb-4" id="kanbanBoard">
        @forelse($columns as $column)
        <div class="kanban-column flex-shrink-0 w-80 rounded-xl p-4" style="background-color: rgb(var(--card)); border: 1px solid rgb(var(--border)); box-shadow: var(--shadow);">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold">{{ $column->name }}</h3>
                <span class="px-2 py-1 rounded text-xs font-medium" style="background-color: rgba(var(--primary), 0.1); color: rgb(var(--primary));">
                    {{ $column->tasks_count }}
                </span>
            </div>
            <div class="space-y-3 min-h-[200px]" data-column-id="{{ $column->id }}" ondrop="drop(event)" ondragover="allowDrop(event)">
                @forelse($column->tasks as $task)
                <div class="task-card rounded-lg p-3 cursor-move transition-all hover:scale-[1.02]" 
                    draggable="true" 
                    ondragstart="drag(event)"
                    data-task-id="{{ $task->id }}"
                    style="background-color: rgba(var(--primary), 0.05); border: 1px solid rgb(var(--border));">
                    <h4 class="font-medium mb-1">{{ $task->title }}</h4>
                    @if($task->description)
                    <p class="text-xs mb-2" style="color: rgb(var(--text-secondary));">{{ \Illuminate\Support\Str::limit($task->description, 50) }}</p>
                    @endif
                    @if($task->assignedUser)
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-4 h-4" style="color: rgb(var(--text-secondary));" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span class="text-xs font-medium" style="color: rgb(var(--primary));">{{ $task->assignedUser->name }}</span>
                    </div>
                    @else
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-4 h-4" style="color: rgb(var(--text-secondary));" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span class="text-xs italic" style="color: rgb(var(--text-secondary));">Sem atribuição</span>
                    </div>
                    @endif
                    <div class="flex items-center justify-between text-xs">
                        <span class="px-2 py-1 rounded" style="background-color: {{ $task->priority === 'urgente' ? 'rgba(239, 68, 68, 0.1)' : ($task->priority === 'alta' ? 'rgba(251, 146, 60, 0.1)' : ($task->priority === 'média' ? 'rgba(251, 191, 36, 0.1)' : 'rgba(34, 197, 94, 0.1)')) }}; color: {{ $task->priority === 'urgente' ? 'rgb(239, 68, 68)' : ($task->priority === 'alta' ? 'rgb(251, 146, 60)' : ($task->priority === 'média' ? 'rgb(251, 191, 36)' : 'rgb(34, 197, 94)')) }};">
                            {{ ucfirst($task->priority) }}
                        </span>
                        @if($task->due_date)
                        <span style="color: rgb(var(--text-secondary));">{{ $task->due_date->format('d/m/Y') }}</span>
                        @endif
                    </div>
                </div>
                @empty
                <div class="text-center py-8 rounded-lg" style="background-color: rgba(var(--primary), 0.02); border: 2px dashed rgb(var(--border));">
                    <svg class="w-8 h-8 mx-auto mb-2 opacity-50" style="color: rgb(var(--text-secondary));" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <p class="text-xs" style="color: rgb(var(--text-secondary));">Arraste tarefas aqui</p>
                    <p class="text-xs" style="color: rgb(var(--text-secondary));">Esta coluna está vazia</p>
                </div>
                @endforelse
            </div>
        </div>
        @empty
        <div class="w-full text-center py-12">
            <p class="text-sm mb-4" style="color: rgb(var(--text-secondary));">Nenhuma coluna criada. Crie uma coluna para começar.</p>
            <button type="button" onclick="openColumnModal()" class="px-4 py-2 rounded-lg font-medium text-white transition-all" style="background: linear-gradient(135deg, rgb(var(--primary)), rgb(var(--primary-dark)));">
                Criar Primeira Coluna
            </button>
        </div>
        @endforelse
    </div>
</div>

<!-- Modal Nova Tarefa -->
<div id="taskModal" class="fixed inset-0 z-50 hidden overflow-y-auto" style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity backdrop-blur-md" style="background: rgba(0, 0, 0, 0.4);" onclick="closeTaskModal()"></div>
        <div class="relative inline-block w-full max-w-2xl p-6 my-8 text-left align-middle transition-all transform rounded-xl shadow-xl z-50" style="background-color: #ffffff; border: 1px solid rgb(var(--border)); box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold">Nova Tarefa</h3>
                <button type="button" onclick="closeTaskModal()" class="p-2 rounded-lg hover:bg-opacity-50 transition-colors" style="background-color: rgba(239, 68, 68, 0.1); color: rgb(239, 68, 68);">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <p class="text-sm mb-6" style="color: rgb(var(--text-secondary));">Crie uma nova tarefa para sua equipe</p>
            <form id="taskForm" method="POST" action="{{ isset($isTeamView) && $isTeamView && $team ? route('team.tasks.store', $team) : route('management.tasks.store') }}" class="space-y-6">
                @csrf
                <div>
                    <label for="task_title" class="block text-sm font-medium mb-2">Título *</label>
                    <input type="text" id="task_title" name="title" required
                        class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                        style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                        placeholder="Título da tarefa">
                </div>
                <div>
                    <label for="task_description" class="block text-sm font-medium mb-2">Descrição</label>
                    <textarea id="task_description" name="description" rows="3"
                        class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                        style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                        placeholder="Descrição da tarefa"></textarea>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="task_priority" class="block text-sm font-medium mb-2">Prioridade</label>
                        <select id="task_priority" name="priority"
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                            <option value="baixa">Baixa</option>
                            <option value="média" selected>Média</option>
                            <option value="alta">Alta</option>
                            <option value="urgente">Urgente</option>
                        </select>
                    </div>
                    <div>
                        <label for="task_due_date" class="block text-sm font-medium mb-2">Data de Vencimento</label>
                        <input type="date" id="task_due_date" name="due_date"
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="task_column_id" class="block text-sm font-medium mb-2">Coluna *</label>
                        <select id="task_column_id" name="task_column_id" required
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                            @foreach($columns as $column)
                            <option value="{{ $column->id }}">{{ $column->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="task_assigned_to" class="block text-sm font-medium mb-2">Atribuir a</label>
                        <select id="task_assigned_to" name="assigned_to"
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                            <option value="">Ninguém</option>
                            @if(isset($allAssignees))
                            @foreach($allAssignees as $assignee)
                            <option value="{{ $assignee['id'] }}">{{ $assignee['name'] }}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t" style="border-color: rgb(var(--border));">
                    <button type="submit" class="flex-1 px-6 py-3 rounded-lg font-medium text-white transition-all hover:scale-105" style="background: linear-gradient(135deg, rgb(var(--primary)), rgb(var(--primary-dark))); box-shadow: 0 4px 6px -1px rgba(var(--primary), 0.3);">
                        Criar Tarefa
                    </button>
                    <button type="button" onclick="closeTaskModal()" class="flex-1 px-6 py-3 rounded-lg font-medium text-center transition-colors" style="background-color: rgba(var(--primary), 0.1); color: rgb(var(--primary));">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Nova Coluna -->
<div id="columnModal" class="fixed inset-0 z-50 hidden overflow-y-auto" style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity backdrop-blur-md" style="background: rgba(0, 0, 0, 0.4);" onclick="closeColumnModal()"></div>
        <div class="relative inline-block w-full max-w-md p-6 my-8 text-left align-middle transition-all transform rounded-xl shadow-xl z-50" style="background-color: #ffffff; border: 1px solid rgb(var(--border)); box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);">
            <h3 class="text-xl font-bold mb-4">Nova Coluna</h3>
            <form method="POST" action="{{ route('management.task-columns.store') }}">
                @csrf
                <div class="mb-4">
                    <label for="column_name" class="block text-sm font-medium mb-2">Nome da Coluna *</label>
                    <input type="text" id="column_name" name="name" required
                        class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                        style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                        placeholder="Ex: A Fazer">
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="flex-1 px-6 py-3 rounded-lg font-medium text-white transition-all" style="background: linear-gradient(135deg, rgb(var(--primary)), rgb(var(--primary-dark)));">
                        Criar Coluna
                    </button>
                    <button type="button" onclick="closeColumnModal()" class="flex-1 px-6 py-3 rounded-lg font-medium text-center transition-colors" style="background-color: rgba(var(--primary), 0.1); color: rgb(var(--primary));">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function allowDrop(ev) {
    ev.preventDefault();
}

function drag(ev) {
    ev.dataTransfer.setData("text", ev.target.getAttribute('data-task-id'));
}

function drop(ev) {
    ev.preventDefault();
    const taskId = ev.dataTransfer.getData("text");
    const columnId = ev.currentTarget.getAttribute('data-column-id');
    
    // TODO: Implementar movimentação via AJAX
    fetch(`/dashboard/management/tasks/${taskId}/move`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            task_column_id: columnId,
            order: 0
        })
    }).then(() => {
        location.reload();
    });
}

function filterTasks() {
    // TODO: Implementar filtro
}

function openTaskModal() {
    document.getElementById('taskModal').style.display = 'block';
    document.getElementById('taskModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeTaskModal() {
    document.getElementById('taskModal').style.display = 'none';
    document.getElementById('taskModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function openColumnModal() {
    document.getElementById('columnModal').style.display = 'block';
    document.getElementById('columnModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeColumnModal() {
    document.getElementById('columnModal').style.display = 'none';
    document.getElementById('columnModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeTaskModal();
        closeColumnModal();
    }
});
</script>
@endsection

