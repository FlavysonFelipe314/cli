@extends('layouts.app')

@section('title', 'Gerenciar Módulos - CLIVUS')
@section('page-title', 'Gerenciar Módulos')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold">Módulos do Sistema</h2>
        <a href="{{ route('admin.modules.create') }}" class="px-4 py-2 rounded-lg font-medium text-white transition-all" style="background: linear-gradient(135deg, rgb(var(--primary)), rgb(var(--primary-dark)));">
            Novo Módulo
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($modules as $module)
        <div class="rounded-xl p-6" style="background-color: rgb(var(--card)); border: 1px solid rgb(var(--border)); box-shadow: var(--shadow);">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="text-xl font-bold">{{ $module->name }}</h3>
                    <p class="text-sm" style="color: rgb(var(--text-secondary));">{{ $module->description ?? 'Sem descrição' }}</p>
                </div>
                @if($module->active)
                <span class="px-2 py-1 rounded text-xs" style="background-color: rgba(34, 197, 94, 0.1); color: rgb(22, 163, 74);">Ativo</span>
                @else
                <span class="px-2 py-1 rounded text-xs" style="background-color: rgba(239, 68, 68, 0.1); color: rgb(220, 38, 38);">Inativo</span>
                @endif
            </div>
            
            <div class="mb-4 space-y-2">
                <div>
                    <span class="text-sm font-medium" style="color: rgb(var(--text-secondary));">Categoria:</span>
                    <span class="text-sm">{{ ucfirst($module->category) }}</span>
                </div>
                <div>
                    <span class="text-sm font-medium" style="color: rgb(var(--text-secondary));">Preço:</span>
                    <span class="text-lg font-bold" style="color: rgb(var(--primary));">R$ {{ number_format($module->price, 2, ',', '.') }}</span>
                </div>
                @if($module->route_name)
                <div>
                    <span class="text-sm font-medium" style="color: rgb(var(--text-secondary));">Rota:</span>
                    <span class="text-sm font-mono">{{ $module->route_name }}</span>
                </div>
                @endif
            </div>
            
            <div class="flex gap-2">
                <a href="{{ route('admin.modules.edit', $module) }}" class="flex-1 px-4 py-2 rounded-lg font-medium text-center transition-colors" style="background-color: rgba(var(--primary), 0.1); color: rgb(var(--primary));">
                    Editar
                </a>
                <form action="{{ route('admin.modules.destroy', $module) }}" method="POST" class="flex-1" onsubmit="return confirm('Tem certeza?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full px-4 py-2 rounded-lg font-medium transition-colors" style="background-color: rgba(239, 68, 68, 0.1); color: rgb(239, 68, 68);">
                        Excluir
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection

