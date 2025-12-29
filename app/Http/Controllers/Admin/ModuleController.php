<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Module;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $modules = Module::orderBy('category')->orderBy('sort_order')->get();
        return view('admin.modules.index', compact('modules'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.modules.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:modules',
            'description' => 'nullable|string',
            'route_name' => 'nullable|string|max:255',
            'icon' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|in:tools,management,finance',
            'active' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        Module::create($validated);

        return redirect()->route('admin.modules.index')
            ->with('success', 'Módulo criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Module $module)
    {
        return view('admin.modules.show', compact('module'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Module $module)
    {
        return view('admin.modules.edit', compact('module'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Module $module)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:modules,slug,' . $module->id,
            'description' => 'nullable|string',
            'route_name' => 'nullable|string|max:255',
            'icon' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|in:tools,management,finance',
            'active' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $module->update($validated);

        return redirect()->route('admin.modules.index')
            ->with('success', 'Módulo atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Module $module)
    {
        $module->delete();

        return redirect()->route('admin.modules.index')
            ->with('success', 'Módulo removido com sucesso!');
    }
}
