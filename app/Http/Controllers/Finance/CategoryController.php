<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::where('user_id', Auth::id())
            ->orderBy('name');

        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $categories = $query->paginate(20);

        return view('finance.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'type' => 'required|in:expense,revenue',
            'color' => 'nullable|string|max:7',
        ]);

        $validated['user_id'] = Auth::id();

        Category::create($validated);

        return redirect()->route('finance.categories.index')
            ->with('success', 'Categoria criada com sucesso!');
    }

    public function update(Request $request, Category $category)
    {
        Gate::authorize('update', $category);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'type' => 'required|in:expense,revenue',
            'color' => 'nullable|string|max:7',
        ]);

        $category->update($validated);

        return redirect()->route('finance.categories.index')
            ->with('success', 'Categoria atualizada com sucesso!');
    }

    public function destroy(Category $category)
    {
        Gate::authorize('delete', $category);
        $category->delete();
        
        return redirect()->route('finance.categories.index')
            ->with('success', 'Categoria removida com sucesso!');
    }
}
