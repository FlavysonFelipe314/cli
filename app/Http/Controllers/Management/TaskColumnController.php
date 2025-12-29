<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\TaskColumn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class TaskColumnController extends Controller
{
    public function index()
    {
        $columns = TaskColumn::where('user_id', Auth::id())
            ->orderBy('order')
            ->withCount('tasks')
            ->get();

        return response()->json($columns);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'nullable|string|max:7',
        ]);

        $maxOrder = TaskColumn::where('user_id', Auth::id())->max('order') ?? 0;

        $validated['user_id'] = Auth::id();
        $validated['order'] = $maxOrder + 1;

        TaskColumn::create($validated);

        return redirect()->route('management.tasks.index')
            ->with('success', 'Coluna criada com sucesso!');
    }

    public function update(Request $request, TaskColumn $taskColumn)
    {
        Gate::authorize('update', $taskColumn);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'nullable|string|max:7',
            'order' => 'nullable|integer',
        ]);

        $taskColumn->update($validated);

        return response()->json(['success' => true]);
    }

    public function destroy(TaskColumn $taskColumn)
    {
        Gate::authorize('delete', $taskColumn);
        $taskColumn->delete();
        
        return redirect()->route('management.tasks.index')
            ->with('success', 'Coluna removida com sucesso!');
    }
}
