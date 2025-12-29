@extends('layouts.app')

@section('title', 'Criar Módulo - CLIVUS')
@section('page-title', 'Criar Módulo')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="rounded-xl p-6" style="background-color: rgb(var(--card)); border: 1px solid rgb(var(--border)); box-shadow: var(--shadow);">
        <h2 class="text-2xl font-bold mb-6">Criar Novo Módulo</h2>
        
        <form action="{{ route('admin.modules.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label for="name" class="block text-sm font-medium mb-2">Nome *</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                        class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                        style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                </div>
                
                <div>
                    <label for="slug" class="block text-sm font-medium mb-2">Slug *</label>
                    <input type="text" id="slug" name="slug" value="{{ old('slug') }}" required
                        class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                        style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                        placeholder="prolabore">
                </div>
                
                <div>
                    <label for="category" class="block text-sm font-medium mb-2">Categoria *</label>
                    <select id="category" name="category" required
                        class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                        style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                        <option value="tools">Ferramentas</option>
                        <option value="management">Gestão</option>
                        <option value="finance">Financeiro</option>
                    </select>
                </div>
                
                <div>
                    <label for="price" class="block text-sm font-medium mb-2">Preço *</label>
                    <input type="number" id="price" name="price" value="{{ old('price', 0) }}" step="0.01" min="0" required
                        class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                        style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                </div>
                
                <div>
                    <label for="route_name" class="block text-sm font-medium mb-2">Nome da Rota</label>
                    <input type="text" id="route_name" name="route_name" value="{{ old('route_name') }}"
                        class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                        style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                        placeholder="tools.prolabore.index">
                </div>
                
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium mb-2">Descrição</label>
                    <textarea id="description" name="description" rows="3"
                        class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2 resize-none"
                        style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">{{ old('description') }}</textarea>
                </div>
                
                <div class="md:col-span-2">
                    <label for="icon" class="block text-sm font-medium mb-2">Ícone (SVG ou classe)</label>
                    <input type="text" id="icon" name="icon" value="{{ old('icon') }}"
                        class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                        style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                        placeholder="<svg>...</svg> ou classe CSS">
                </div>
                
                <div>
                    <label for="sort_order" class="block text-sm font-medium mb-2">Ordem</label>
                    <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}" min="0"
                        class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                        style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                </div>
                
                <div class="md:col-span-2">
                    <label class="flex items-center">
                        <input type="checkbox" name="active" value="1" {{ old('active', true) ? 'checked' : '' }}
                            class="rounded border-gray-300" style="border-color: rgb(var(--border));">
                        <span class="ml-2 text-sm">Módulo ativo</span>
                    </label>
                </div>
            </div>
            
            <div class="flex gap-3 pt-4">
                <button type="submit" class="flex-1 px-6 py-3 rounded-lg font-medium text-white transition-all" style="background: linear-gradient(135deg, rgb(var(--primary)), rgb(var(--primary-dark)));">
                    Criar Módulo
                </button>
                <a href="{{ route('admin.modules.index') }}" class="flex-1 px-6 py-3 rounded-lg font-medium text-center transition-colors" style="background-color: rgba(var(--primary), 0.1); color: rgb(var(--primary));">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

