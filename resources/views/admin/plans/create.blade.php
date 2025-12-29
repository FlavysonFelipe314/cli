@extends('layouts.app')

@section('title', 'Criar Plano - CLIVUS')
@section('page-title', 'Criar Plano')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="rounded-xl p-6" style="background-color: rgb(var(--card)); border: 1px solid rgb(var(--border)); box-shadow: var(--shadow);">
        <h2 class="text-2xl font-bold mb-6">Criar Novo Plano</h2>
        
        <form action="{{ route('admin.plans.store') }}" method="POST" class="space-y-6">
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
                        placeholder="plano-basico">
                </div>
                
                <div>
                    <label for="price" class="block text-sm font-medium mb-2">Preço *</label>
                    <input type="number" id="price" name="price" value="{{ old('price') }}" step="0.01" min="0" required
                        class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                        style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                </div>
                
                <div>
                    <label for="billing_cycle" class="block text-sm font-medium mb-2">Ciclo *</label>
                    <select id="billing_cycle" name="billing_cycle" required
                        class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                        style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                        <option value="monthly">Mensal</option>
                        <option value="yearly">Anual</option>
                    </select>
                </div>
                
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium mb-2">Descrição</label>
                    <textarea id="description" name="description" rows="3"
                        class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2 resize-none"
                        style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">{{ old('description') }}</textarea>
                </div>
                
                <div>
                    <label for="max_accounts" class="block text-sm font-medium mb-2">Máx. Contas</label>
                    <input type="number" id="max_accounts" name="max_accounts" value="{{ old('max_accounts') }}" min="0"
                        class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                        style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                </div>
                
                <div>
                    <label for="max_transactions_per_month" class="block text-sm font-medium mb-2">Máx. Transações/Mês</label>
                    <input type="number" id="max_transactions_per_month" name="max_transactions_per_month" value="{{ old('max_transactions_per_month') }}" min="0"
                        class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                        style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                </div>
                
                <div class="md:col-span-2">
                    <label class="flex items-center">
                        <input type="checkbox" name="active" value="1" {{ old('active', true) ? 'checked' : '' }}
                            class="rounded border-gray-300" style="border-color: rgb(var(--border));">
                        <span class="ml-2 text-sm">Plano ativo</span>
                    </label>
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-3">Módulos Incluídos no Plano</label>
                    <div class="space-y-3 max-h-64 overflow-y-auto p-4 rounded-lg border" style="background-color: rgb(var(--bg-secondary)); border-color: rgb(var(--border));">
                        @php
                            $modulesByCategory = $modules->groupBy('category');
                        @endphp
                        @foreach($modulesByCategory as $category => $categoryModules)
                        <div class="mb-4">
                            <h4 class="text-sm font-semibold mb-2" style="color: rgb(var(--text-secondary));">
                                {{ ucfirst($category) === 'Tools' ? 'Ferramentas' : (ucfirst($category) === 'Management' ? 'Gestão' : 'Financeiro') }}
                            </h4>
                            <div class="space-y-2">
                                @foreach($categoryModules as $module)
                                <label class="flex items-center p-2 rounded-lg hover:bg-opacity-50 transition-colors" style="background-color: rgba(var(--primary), 0.05);">
                                    <input type="checkbox" name="allowed_modules[]" value="{{ $module->slug }}" {{ in_array($module->slug, old('allowed_modules', [])) ? 'checked' : '' }}
                                        class="rounded border-gray-300" style="border-color: rgb(var(--border));">
                                    <div class="ml-3 flex-1">
                                        <span class="text-sm font-medium">{{ $module->name }}</span>
                                        @if($module->description)
                                        <p class="text-xs" style="color: rgb(var(--text-secondary));">{{ $module->description }}</p>
                                        @endif
                                    </div>
                                    <span class="text-sm font-bold" style="color: rgb(var(--primary));">R$ {{ number_format($module->price, 2, ',', '.') }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <p class="text-xs mt-2" style="color: rgb(var(--text-secondary));">Selecione os módulos que serão incluídos neste plano</p>
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-3">Resumo do Plano</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 rounded-lg border" style="background-color: rgb(var(--bg-secondary)); border-color: rgb(var(--border));">
                        <div>
                            <h4 class="text-sm font-semibold mb-2 flex items-center">
                                <svg class="w-4 h-4 mr-2" style="color: rgb(34, 197, 94);" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                Incluídos
                            </h4>
                            <div id="included-modules" class="space-y-1 text-sm" style="color: rgb(34, 197, 94);">
                                <p class="text-xs" style="color: rgb(var(--text-secondary));">Selecione módulos acima</p>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-sm font-semibold mb-2 flex items-center">
                                <svg class="w-4 h-4 mr-2" style="color: rgb(239, 68, 68);" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                                Não Incluídos
                            </h4>
                            <div id="excluded-modules" class="space-y-1 text-sm" style="color: rgb(239, 68, 68);">
                                @foreach($modules as $module)
                                <div class="flex items-center">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span>{{ $module->name }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const checkboxes = document.querySelectorAll('input[name="allowed_modules[]"]');
                    const includedDiv = document.getElementById('included-modules');
                    const excludedDiv = document.getElementById('excluded-modules');
                    
                    function updateSummary() {
                        const included = [];
                        const excluded = [];
                        
                        checkboxes.forEach(cb => {
                            const moduleName = cb.closest('label').querySelector('.text-sm.font-medium').textContent;
                            if (cb.checked) {
                                included.push(moduleName);
                            } else {
                                excluded.push(moduleName);
                            }
                        });
                        
                        includedDiv.innerHTML = included.length > 0 
                            ? included.map(m => `<div class="flex items-center"><svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg><span>${m}</span></div>`).join('')
                            : '<p class="text-xs" style="color: rgb(var(--text-secondary));">Nenhum módulo selecionado</p>';
                        
                        excludedDiv.innerHTML = excluded.length > 0 
                            ? excluded.map(m => `<div class="flex items-center"><svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg><span>${m}</span></div>`).join('')
                            : '<p class="text-xs" style="color: rgb(var(--text-secondary));">Todos os módulos incluídos</p>';
                    }
                    
                    checkboxes.forEach(cb => {
                        cb.addEventListener('change', updateSummary);
                    });
                    
                    updateSummary();
                });
            </script>
            
            <div class="flex gap-3 pt-4">
                <button type="submit" class="flex-1 px-6 py-3 rounded-lg font-medium text-white transition-all" style="background: linear-gradient(135deg, rgb(var(--primary)), rgb(var(--primary-dark)));">
                    Criar Plano
                </button>
                <a href="{{ route('admin.plans.index') }}" class="flex-1 px-6 py-3 rounded-lg font-medium text-center transition-colors" style="background-color: rgba(var(--primary), 0.1); color: rgb(var(--primary));">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

