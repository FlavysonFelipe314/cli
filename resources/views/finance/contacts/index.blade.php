@extends('layouts.app')

@section('title', 'Contatos - CLIVUS')
@section('page-title', 'Contatos')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold mb-1">Contatos</h2>
            <p class="text-sm" style="color: rgb(var(--text-secondary));">Gerencie seus contatos e fornecedores</p>
        </div>
        <button type="button" onclick="event.preventDefault(); openContactModal();" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-medium text-white transition-all hover:scale-105" style="background: linear-gradient(135deg, rgb(var(--primary)), rgb(var(--primary-dark))); box-shadow: 0 4px 6px -1px rgba(var(--primary), 0.3);">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span>Novo Contato</span>
        </button>
    </div>

    <!-- Filters -->
    <div class="flex flex-col sm:flex-row gap-4">
        <div class="flex-1">
            <input type="text" id="searchInput" placeholder="Buscar contatos..." 
                class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                onkeyup="filterContacts()">
        </div>
        <select id="typeFilter" onchange="filterContacts()"
            class="px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
            <option value="">Todos os tipos</option>
            <option value="Cliente">Cliente</option>
            <option value="Fornecedor">Fornecedor</option>
            <option value="Funcionário">Funcionário</option>
            <option value="Outro">Outro</option>
        </select>
    </div>

    <!-- Contacts List -->
    @if($contacts->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="contactsList">
        @foreach($contacts as $contact)
        <div class="contact-item rounded-xl p-6 transition-all hover:scale-[1.02] cursor-pointer" 
            data-name="{{ strtolower($contact->name) }}"
            data-type="{{ $contact->type }}"
            style="background-color: rgb(var(--card)); border: 1px solid rgb(var(--border)); box-shadow: var(--shadow);" 
            onclick="event.stopPropagation(); openContactModal({{ $contact->id }});">
            <div class="flex items-start justify-between mb-4">
                <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background: linear-gradient(135deg, rgba(var(--primary), 0.2), rgba(var(--primary-dark), 0.2));">
                    <svg class="w-6 h-6" style="color: rgb(var(--primary));" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div class="flex gap-2" onclick="event.stopPropagation();">
                    <button type="button" onclick="event.stopPropagation(); openContactModal({{ $contact->id }});" class="p-2 rounded-lg hover:bg-opacity-50 transition-colors" style="background-color: rgba(var(--primary), 0.1);">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </button>
                    <form action="{{ route('finance.contacts.destroy', $contact) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja remover este contato?');">
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
            
            <h3 class="text-lg font-semibold mb-2">{{ $contact->name }}</h3>
            
            <div class="space-y-2">
                <div class="flex items-center gap-2 text-sm">
                    <span class="px-2 py-1 rounded text-xs font-medium" style="background-color: rgba(var(--primary), 0.1); color: rgb(var(--primary));">
                        {{ $contact->type }}
                    </span>
                </div>
                @if($contact->email)
                <div class="flex items-center gap-2 text-sm" style="color: rgb(var(--text-secondary));">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    <span>{{ $contact->email }}</span>
                </div>
                @endif
                @if($contact->phone)
                <div class="flex items-center gap-2 text-sm" style="color: rgb(var(--text-secondary));">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                    <span>{{ $contact->phone }}</span>
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="text-center py-12 rounded-xl" style="background-color: rgb(var(--card)); border: 1px solid rgb(var(--border));">
        <svg class="w-16 h-16 mx-auto mb-4 opacity-50" style="color: rgb(var(--text-secondary));" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
        </svg>
        <h3 class="text-lg font-semibold mb-2">Nenhum contato cadastrado</h3>
        <p class="text-sm mb-4" style="color: rgb(var(--text-secondary));">Comece criando seu primeiro contato</p>
        <button type="button" onclick="event.preventDefault(); openContactModal();" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-medium text-white transition-all" style="background: linear-gradient(135deg, rgb(var(--primary)), rgb(var(--primary-dark)));">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span>Criar Contato</span>
        </button>
    </div>
    @endif
</div>

<!-- Modal de Contato -->
<div id="contactModal" class="fixed inset-0 z-50 hidden overflow-y-auto" style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <!-- Overlay com efeito blur dark glass -->
        <div class="fixed inset-0 transition-opacity backdrop-blur-md" style="background: rgba(0, 0, 0, 0.4);" onclick="closeContactModal()"></div>

        <!-- Modal -->
        <div class="relative inline-block w-full max-w-3xl p-6 my-8 text-left align-middle transition-all transform rounded-xl shadow-xl z-50" style="background-color: #ffffff; border: 1px solid rgb(var(--border)); box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold" id="contactModalTitle">Criar Novo Contato</h3>
                <button type="button" onclick="closeContactModal()" class="p-2 rounded-lg hover:bg-opacity-50 transition-colors" style="background-color: rgba(239, 68, 68, 0.1); color: rgb(239, 68, 68);">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form id="contactForm" method="POST" class="space-y-6">
                @csrf
                <div id="contactFormMethod"></div>
                
                @if($errors->any())
                <div class="p-4 rounded-lg mb-4" style="background-color: rgba(239, 68, 68, 0.1); border: 1px solid rgb(239, 68, 68);">
                    <ul class="list-disc list-inside text-sm" style="color: rgb(220, 38, 38);">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                
                <div class="space-y-6 max-h-[70vh] overflow-y-auto pr-2">
                    <div>
                        <h4 class="text-lg font-semibold mb-4">Dados Básicos</h4>
                        <div class="space-y-4">
                            <div>
                                <label for="contact_name" class="block text-sm font-medium mb-2">Nome *</label>
                                <input type="text" id="contact_name" name="name" required
                                    class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                                    style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                                    placeholder="Ex: João Silva ou Empresa LTDA">
                                <p class="text-xs mt-1" style="color: rgb(var(--text-secondary));">Nome completo ou razão social</p>
                            </div>

                            <div>
                                <label for="contact_type" class="block text-sm font-medium mb-2">Tipo *</label>
                                <select id="contact_type" name="type" required
                                    class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                                    style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                                    <option value="Cliente">Cliente</option>
                                    <option value="Fornecedor">Fornecedor</option>
                                    <option value="Funcionário">Funcionário</option>
                                    <option value="Outro">Outro</option>
                                </select>
                            </div>

                            <div>
                                <label for="contact_cpf_cnpj" class="block text-sm font-medium mb-2">CPF/CNPJ</label>
                                <input type="text" id="contact_cpf_cnpj" name="cpf_cnpj"
                                    class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                                    style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                                    placeholder="Ex: 123.456.789-00 ou 12.345.678/0001-99">
                                <p class="text-xs mt-1" style="color: rgb(var(--text-secondary));">Informe apenas números. Validamos automaticamente o documento.</p>
                            </div>

                            <div>
                                <label for="contact_email" class="block text-sm font-medium mb-2">E-mail</label>
                                <input type="email" id="contact_email" name="email"
                                    class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                                    style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                                    placeholder="Ex: contato@email.com">
                            </div>

                            <div>
                                <label for="contact_phone" class="block text-sm font-medium mb-2">Telefone</label>
                                <input type="text" id="contact_phone" name="phone"
                                    class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                                    style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                                    placeholder="Ex: (11) 98765-4321">
                            </div>
                        </div>
                    </div>

                    <div class="border-t pt-6" style="border-color: rgb(var(--border));">
                        <h4 class="text-lg font-semibold mb-4">Endereço (Opcional)</h4>
                        <div class="space-y-4">
                            <div>
                                <label for="contact_zipcode" class="block text-sm font-medium mb-2">CEP</label>
                                <input type="text" id="contact_zipcode" name="zipcode" maxlength="9"
                                    class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                                    style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                                    placeholder="Ex: 12345-678"
                                    onblur="searchCEP(this.value)">
                                <p class="text-xs mt-1" style="color: rgb(var(--text-secondary));">Digite o CEP e pressione Tab. Buscaremos o endereço automaticamente.</p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="contact_street" class="block text-sm font-medium mb-2">Logradouro</label>
                                    <input type="text" id="contact_street" name="street"
                                        class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                                        style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                                        placeholder="Ex: Rua das Flores">
                                </div>

                                <div>
                                    <label for="contact_number" class="block text-sm font-medium mb-2">Número</label>
                                    <input type="text" id="contact_number" name="number"
                                        class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                                        style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                                        placeholder="Ex: 123">
                                </div>
                            </div>

                            <div>
                                <label for="contact_complement" class="block text-sm font-medium mb-2">Complemento</label>
                                <input type="text" id="contact_complement" name="complement"
                                    class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                                    style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                                    placeholder="Ex: Apto 45">
                            </div>

                            <div>
                                <label for="contact_neighborhood" class="block text-sm font-medium mb-2">Bairro</label>
                                <input type="text" id="contact_neighborhood" name="neighborhood"
                                    class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                                    style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                                    placeholder="Ex: Centro">
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="contact_city" class="block text-sm font-medium mb-2">Cidade</label>
                                    <input type="text" id="contact_city" name="city"
                                        class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                                        style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                                        placeholder="Ex: São Paulo">
                                </div>

                                <div>
                                    <label for="contact_state" class="block text-sm font-medium mb-2">UF</label>
                                    <input type="text" id="contact_state" name="state" maxlength="2"
                                        class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                                        style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                                        placeholder="Ex: SP">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t" style="border-color: rgb(var(--border));">
                    <button type="submit" class="flex-1 px-6 py-3 rounded-lg font-medium text-white transition-all hover:scale-105" style="background: linear-gradient(135deg, rgb(var(--primary)), rgb(var(--primary-dark))); box-shadow: 0 4px 6px -1px rgba(var(--primary), 0.3);">
                        <span id="contactSubmitText">Criar Contato</span>
                    </button>
                    <button type="button" onclick="closeContactModal()" class="flex-1 px-6 py-3 rounded-lg font-medium text-center transition-colors" style="background-color: rgba(var(--primary), 0.1); color: rgb(var(--primary));">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const contactsData = @json($contacts->keyBy('id'));

function filterContacts() {
    const search = document.getElementById('searchInput').value.toLowerCase();
    const type = document.getElementById('typeFilter').value;
    const items = document.querySelectorAll('.contact-item');
    
    items.forEach(item => {
        const name = item.getAttribute('data-name');
        const itemType = item.getAttribute('data-type');
        const matchesSearch = !search || name.includes(search);
        const matchesType = !type || itemType === type;
        
        item.style.display = (matchesSearch && matchesType) ? 'block' : 'none';
    });
}

async function searchCEP(cep) {
    cep = cep.replace(/\D/g, '');
    if (cep.length !== 8) return;
    
    try {
        const response = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
        const data = await response.json();
        
        if (!data.erro) {
            document.getElementById('contact_street').value = data.logradouro || '';
            document.getElementById('contact_neighborhood').value = data.bairro || '';
            document.getElementById('contact_city').value = data.localidade || '';
            document.getElementById('contact_state').value = data.uf || '';
        }
    } catch (error) {
        console.error('Erro ao buscar CEP:', error);
    }
}

function openContactModal(contactId = null) {
    const modal = document.getElementById('contactModal');
    if (!modal) return;
    
    const form = document.getElementById('contactForm');
    const formMethod = document.getElementById('contactFormMethod');
    const title = document.getElementById('contactModalTitle');
    const submitText = document.getElementById('contactSubmitText');
    
    if (contactId && contactsData[contactId]) {
        const contact = contactsData[contactId];
        title.textContent = 'Editar Contato';
        submitText.textContent = 'Salvar Alterações';
        form.action = '{{ route("finance.contacts.update", ":id") }}'.replace(':id', contactId);
        formMethod.innerHTML = '@method("PUT")';
        
        document.getElementById('contact_name').value = contact.name || '';
        document.getElementById('contact_type').value = contact.type || 'Cliente';
        document.getElementById('contact_cpf_cnpj').value = contact.cpf_cnpj || '';
        document.getElementById('contact_email').value = contact.email || '';
        document.getElementById('contact_phone').value = contact.phone || '';
        document.getElementById('contact_zipcode').value = contact.zipcode || '';
        document.getElementById('contact_street').value = contact.street || '';
        document.getElementById('contact_number').value = contact.number || '';
        document.getElementById('contact_complement').value = contact.complement || '';
        document.getElementById('contact_neighborhood').value = contact.neighborhood || '';
        document.getElementById('contact_city').value = contact.city || '';
        document.getElementById('contact_state').value = contact.state || '';
    } else {
        title.textContent = 'Criar Novo Contato';
        submitText.textContent = 'Criar Contato';
        form.action = '{{ route("finance.contacts.store") }}';
        formMethod.innerHTML = '';
        form.reset();
    }
    
    modal.style.display = 'block';
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeContactModal() {
    const modal = document.getElementById('contactModal');
    if (modal) {
        modal.style.display = 'none';
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeContactModal();
    }
});
</script>
@endsection

