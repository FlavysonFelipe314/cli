@extends('layouts.app')

@section('title', 'Categorias - CLIVUS')
@section('page-title', 'Categorias')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold mb-1">Categorias</h2>
            <p class="text-sm" style="color: rgb(var(--text-secondary));">Gerencie suas categorias de receitas e despesas</p>
        </div>
        <button type="button" onclick="event.preventDefault(); openCategoryModal();" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-medium text-white transition-all hover:scale-105" style="background: linear-gradient(135deg, rgb(var(--primary)), rgb(var(--primary-dark))); box-shadow: 0 4px 6px -1px rgba(var(--primary), 0.3);">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span>Nova Categoria</span>
        </button>
    </div>

    <!-- Filters -->
    <div class="flex flex-col sm:flex-row gap-4">
        <div class="flex-1">
            <input type="text" id="searchInput" placeholder="Buscar categorias..." 
                class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                onkeyup="filterCategories()">
        </div>
        <select id="typeFilter" onchange="filterCategories()"
            class="px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
            <option value="">Todos os tipos</option>
            <option value="expense">Despesa</option>
            <option value="revenue">Receita</option>
        </select>
    </div>

    <!-- Categories List -->
    @if($categories->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="categoriesList">
        @foreach($categories as $category)
        <div class="category-item rounded-xl p-6 transition-all hover:scale-[1.02] cursor-pointer" 
            data-name="{{ strtolower($category->name) }}"
            data-type="{{ $category->type }}"
            style="background-color: rgb(var(--card)); border: 1px solid rgb(var(--border)); box-shadow: var(--shadow);" 
            onclick="event.stopPropagation(); openCategoryModal({{ $category->id }});">
            <div class="flex items-start justify-between mb-4">
                <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background: linear-gradient(135deg, rgba(var(--primary), 0.2), rgba(var(--primary-dark), 0.2));">
                    <svg class="w-6 h-6" style="color: rgb(var(--primary));" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                </div>
                <div class="flex gap-2" onclick="event.stopPropagation();">
                    <button type="button" onclick="event.stopPropagation(); openCategoryModal({{ $category->id }});" class="p-2 rounded-lg hover:bg-opacity-50 transition-colors" style="background-color: rgba(var(--primary), 0.1);">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </button>
                    <form action="{{ route('finance.categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja remover esta categoria?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-2 rounded-lg hover:bg-opacity-50 transition-colors" style="background-color: rgba(239, 68, 68, 0.1); color: rgb(239, 68, 68);">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
            
            <h3 class="text-lg font-semibold mb-2">{{ $category->name }}</h3>
            
            <div class="space-y-2">
                <div class="flex items-center gap-2 text-sm">
                    <span class="px-2 py-1 rounded text-xs font-medium {{ $category->type === 'revenue' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                        {{ $category->type === 'revenue' ? 'Receita' : 'Despesa' }}
                    </span>
                </div>
                @if($category->description)
                <p class="text-sm" style="color: rgb(var(--text-secondary));">{{ $category->description }}</p>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="text-center py-12 rounded-xl" style="background-color: rgb(var(--card)); border: 1px solid rgb(var(--border));">
        <svg class="w-16 h-16 mx-auto mb-4 opacity-50" style="color: rgb(var(--text-secondary));" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
        </svg>
        <h3 class="text-lg font-semibold mb-2">Nenhuma categoria cadastrada</h3>
        <p class="text-sm mb-4" style="color: rgb(var(--text-secondary));">Comece criando sua primeira categoria</p>
        <button type="button" onclick="event.preventDefault(); openCategoryModal();" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-medium text-white transition-all" style="background: linear-gradient(135deg, rgb(var(--primary)), rgb(var(--primary-dark)));">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span>Criar Categoria</span>
        </button>
    </div>
    @endif
</div>

<!-- Modal de Categoria -->
<div id="categoryModal" class="fixed inset-0 z-50 hidden overflow-y-auto" style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity backdrop-blur-md" style="background: rgba(0, 0, 0, 0.4);" onclick="closeCategoryModal()"></div>

        <div class="relative inline-block w-full max-w-2xl p-6 my-8 text-left align-middle transition-all transform rounded-xl shadow-xl z-50" style="background-color: #ffffff; border: 1px solid rgb(var(--border)); box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold" id="categoryModalTitle">Nova Categoria</h3>
                <button type="button" onclick="closeCategoryModal()" class="p-2 rounded-lg hover:bg-opacity-50 transition-colors" style="background-color: rgba(239, 68, 68, 0.1); color: rgb(239, 68, 68);">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form id="categoryForm" method="POST" class="space-y-6">
                @csrf
                <div id="categoryFormMethod"></div>
                
                @if($errors->any())
                <div class="p-4 rounded-lg mb-4" style="background-color: rgba(239, 68, 68, 0.1); border: 1px solid rgb(239, 68, 68);">
                    <ul class="list-disc list-inside text-sm" style="color: rgb(220, 38, 38);">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                
                <div class="space-y-6">
                    <div>
                        <label for="category_name" class="block text-sm font-medium mb-2">Nome *</label>
                        <input type="text" id="category_name" name="name" required
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                            placeholder="Nome da categoria">
                    </div>

                    <div>
                        <label for="category_type" class="block text-sm font-medium mb-2">Tipo *</label>
                        <select id="category_type" name="type" required
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                            <option value="expense">Despesa</option>
                            <option value="revenue">Receita</option>
                        </select>
                    </div>

                    <div>
                        <label for="category_description" class="block text-sm font-medium mb-2">Descrição</label>
                        <textarea id="category_description" name="description" rows="3"
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                            placeholder="Descrição da categoria"></textarea>
                    </div>

                    <div>
                        <label for="category_color" class="block text-sm font-medium mb-2">Cor</label>
                        <input type="color" id="category_color" name="color"
                            class="w-full h-12 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border));">
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t" style="border-color: rgb(var(--border));">
                    <button type="submit" class="flex-1 px-6 py-3 rounded-lg font-medium text-white transition-all hover:scale-105" style="background: linear-gradient(135deg, rgb(var(--primary)), rgb(var(--primary-dark))); box-shadow: 0 4px 6px -1px rgba(var(--primary), 0.3);">
                        <span id="categorySubmitText">Criar Categoria</span>
                    </button>
                    <button type="button" onclick="closeCategoryModal()" class="flex-1 px-6 py-3 rounded-lg font-medium text-center transition-colors" style="background-color: rgba(var(--primary), 0.1); color: rgb(var(--primary));">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const categoriesData = @json($categories->count() > 0 ? $categories->keyBy('id') : []);

function filterCategories() {
    const search = document.getElementById('searchInput').value.toLowerCase();
    const type = document.getElementById('typeFilter').value;
    const items = document.querySelectorAll('.category-item');
    
    items.forEach(item => {
        const name = item.getAttribute('data-name');
        const itemType = item.getAttribute('data-type');
        const matchesSearch = !search || name.includes(search);
        const matchesType = !type || itemType === type;
        
        item.style.display = (matchesSearch && matchesType) ? 'block' : 'none';
    });
}

function openCategoryModal(categoryId = null) {
    const modal = document.getElementById('categoryModal');
    if (!modal) return;
    
    const form = document.getElementById('categoryForm');
    const formMethod = document.getElementById('categoryFormMethod');
    const title = document.getElementById('categoryModalTitle');
    const submitText = document.getElementById('categorySubmitText');
    
    if (categoryId && categoriesData[categoryId]) {
        const category = categoriesData[categoryId];
        title.textContent = 'Editar Categoria';
        submitText.textContent = 'Salvar Alterações';
        form.action = '{{ route("finance.categories.update", ":id") }}'.replace(':id', categoryId);
        formMethod.innerHTML = '@method("PUT")';
        
        document.getElementById('category_name').value = category.name || '';
        document.getElementById('category_type').value = category.type || 'expense';
        document.getElementById('category_description').value = category.description || '';
        document.getElementById('category_color').value = category.color || '#8b5cf6';
    } else {
        title.textContent = 'Nova Categoria';
        submitText.textContent = 'Criar Categoria';
        form.action = '{{ route("finance.categories.store") }}';
        formMethod.innerHTML = '';
        form.reset();
    }
    
    modal.style.display = 'block';
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeCategoryModal() {
    const modal = document.getElementById('categoryModal');
    if (modal) {
        modal.style.display = 'none';
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeCategoryModal();
    }
});
</script>
@endsection

