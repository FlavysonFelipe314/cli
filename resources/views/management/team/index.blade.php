@extends('layouts.app')

@section('title', 'Gestão de Equipe - CLIVUS')
@section('page-title', 'Gestão de Equipe')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold mb-1">Gestão de Equipe</h2>
            <p class="text-sm" style="color: rgb(var(--text-secondary));">Gerencie os membros da sua equipe e convites</p>
        </div>
        <div class="flex gap-3">
            <button type="button" onclick="openInviteModal()" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-medium transition-all hover:scale-105" style="background-color: rgba(var(--primary), 0.1); color: rgb(var(--primary));">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                <span>Convidar</span>
            </button>
            <button type="button" onclick="openMemberModal()" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-medium text-white transition-all hover:scale-105" style="background: linear-gradient(135deg, rgb(var(--primary)), rgb(var(--primary-dark))); box-shadow: 0 4px 6px -1px rgba(var(--primary), 0.3);">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>+ Novo Membro</span>
            </button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="rounded-xl p-6" style="background-color: rgb(var(--card)); border: 1px solid rgb(var(--border)); box-shadow: var(--shadow);">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium" style="color: rgb(var(--text-secondary));">Total de Membros</span>
                <svg class="w-5 h-5" style="color: rgb(var(--primary));" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
            <p class="text-2xl font-bold">{{ $summary['total'] }}</p>
        </div>
        
        <div class="rounded-xl p-6" style="background: linear-gradient(135deg, rgba(34, 197, 94, 0.1), rgba(22, 163, 74, 0.1)); border: 1px solid rgb(34, 197, 94);">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium" style="color: rgb(22, 163, 74);">Ativos</span>
                <svg class="w-5 h-5" style="color: rgb(34, 197, 94);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
            <p class="text-2xl font-bold" style="color: rgb(22, 163, 74);">{{ $summary['active'] }}</p>
        </div>
        
        <div class="rounded-xl p-6" style="background: linear-gradient(135deg, rgba(251, 191, 36, 0.1), rgba(234, 179, 8, 0.1)); border: 1px solid rgb(251, 191, 36);">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium" style="color: rgb(234, 179, 8);">Convites Pendentes</span>
                <svg class="w-5 h-5" style="color: rgb(251, 191, 36);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
            </div>
            <p class="text-2xl font-bold" style="color: rgb(234, 179, 8);">{{ $summary['pending_invites'] }}</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="flex flex-col sm:flex-row gap-4">
        <select id="filterStatus" onchange="filterMembers()"
            class="px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
            <option value="">Status: Todos</option>
            <option value="active">Ativos</option>
            <option value="inactive">Inativos</option>
            <option value="pending">Pendentes</option>
        </select>
        <select id="filterEmploymentType" onchange="filterMembers()"
            class="px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
            <option value="">Tipo de Vínculo: Todos</option>
            <option value="CLT">CLT</option>
            <option value="PJ">PJ</option>
            <option value="Freelancer">Freelancer</option>
            <option value="Estagiário">Estagiário</option>
        </select>
        <input type="text" id="searchInput" placeholder="Nome, email ou cargo..." onkeyup="filterMembers()"
            class="flex-1 px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
    </div>

    <!-- Members List -->
    <div id="membersList" class="space-y-3">
        @forelse($members as $member)
        <div class="member-item rounded-lg p-4 transition-all hover:scale-[1.01]" 
            data-status="{{ $member->status }}"
            data-employment-type="{{ $member->employment_type }}"
            data-name="{{ strtolower($member->name) }}"
            data-email="{{ strtolower($member->email) }}"
            data-position="{{ strtolower($member->position ?? '') }}"
            style="background-color: rgba(var(--primary), 0.05); border: 1px solid rgb(var(--border));">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex-1">
                    <h4 class="font-semibold mb-2">{{ $member->name }}</h4>
                    <div class="flex flex-wrap items-center gap-3 text-sm" style="color: rgb(var(--text-secondary));">
                        <span class="inline-flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            {{ $member->email }}
                        </span>
                        @if($member->position)
                        <span>{{ $member->position }}</span>
                        @endif
                        <span class="px-2 py-1 rounded text-xs font-medium" style="background-color: rgba(var(--primary), 0.1); color: rgb(var(--primary));">
                            {{ $member->employment_type }}
                        </span>
                        <span class="px-2 py-1 rounded text-xs font-medium 
                            @if($member->status === 'active') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                            @elseif($member->status === 'inactive') bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200
                            @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                            @endif">
                            @if($member->status === 'active') Ativo
                            @elseif($member->status === 'inactive') Inativo
                            @else Pendente
                            @endif
                        </span>
                    </div>
                    <div class="mt-2 text-sm" style="color: rgb(var(--text-secondary));">
                        <span>Entrada: {{ $member->entry_date->format('d/m/Y') }}</span>
                        @if($member->estimated_monthly_cost > 0)
                        <span class="ml-4">Custo: R$ {{ number_format($member->estimated_monthly_cost, 2, ',', '.') }}</span>
                        @endif
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" onclick="openMemberModal({{ $member->id }})" class="p-2 rounded-lg hover:bg-opacity-50 transition-colors" style="background-color: rgba(var(--primary), 0.1);">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </button>
                    <form action="{{ route('management.team.destroy', $member) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja remover este membro?');">
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
        </div>
        @empty
        <div class="text-center py-12">
            <svg class="w-16 h-16 mx-auto mb-4 opacity-50" style="color: rgb(var(--text-secondary));" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            <h3 class="text-lg font-semibold mb-2">Nenhum membro cadastrado ainda</h3>
            <p class="text-sm mb-4" style="color: rgb(var(--text-secondary));">Comece adicionando membros à sua equipe</p>
            <button type="button" onclick="openMemberModal()" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-medium text-white transition-all" style="background: linear-gradient(135deg, rgb(var(--primary)), rgb(var(--primary-dark)));">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>+ Novo Membro</span>
            </button>
        </div>
        @endforelse
    </div>
</div>

<!-- Modal Novo Membro -->
<div id="memberModal" class="fixed inset-0 z-50 hidden overflow-y-auto" style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity backdrop-blur-md" style="background: rgba(0, 0, 0, 0.4);" onclick="closeMemberModal()"></div>
        <div class="relative inline-block w-full max-w-2xl p-6 my-8 text-left align-middle transition-all transform rounded-xl shadow-xl z-50" style="background-color: #ffffff; border: 1px solid rgb(var(--border)); box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold" id="memberModalTitle">Novo Membro da Equipe</h3>
                <button type="button" onclick="closeMemberModal()" class="p-2 rounded-lg hover:bg-opacity-50 transition-colors" style="background-color: rgba(239, 68, 68, 0.1); color: rgb(239, 68, 68);">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <p class="text-sm mb-6" style="color: rgb(var(--text-secondary));">Adicione um novo membro à sua equipe</p>
            <form id="memberForm" method="POST" class="space-y-6">
                @csrf
                <div id="memberFormMethod"></div>
                <div>
                    <label for="member_name" class="block text-sm font-medium mb-2">Nome *</label>
                    <input type="text" id="member_name" name="name" required
                        class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                        style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                        placeholder="Nome completo">
                </div>
                <div>
                    <label for="member_email" class="block text-sm font-medium mb-2">E-mail *</label>
                    <input type="email" id="member_email" name="email" required
                        class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                        style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                        placeholder="email@exemplo.com">
                </div>
                <div>
                    <label for="member_position" class="block text-sm font-medium mb-2">Cargo *</label>
                    <input type="text" id="member_position" name="position" required
                        class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                        style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                        placeholder="Ex: Desenvolvedor, Analista">
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="member_employment_type" class="block text-sm font-medium mb-2">Tipo de Vínculo *</label>
                        <select id="member_employment_type" name="employment_type" required
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                            <option value="CLT" selected>CLT</option>
                            <option value="PJ">PJ</option>
                            <option value="Freelancer">Freelancer</option>
                            <option value="Estagiário">Estagiário</option>
                        </select>
                    </div>
                    <div>
                        <label for="member_entry_date" class="block text-sm font-medium mb-2">Data de Entrada *</label>
                        <input type="date" id="member_entry_date" name="entry_date" required
                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                            value="{{ now()->format('Y-m-d') }}">
                    </div>
                </div>
                <div>
                    <label for="member_estimated_cost" class="block text-sm font-medium mb-2">Custo Mensal Estimado</label>
                    <input type="number" id="member_estimated_cost" name="estimated_monthly_cost" step="0.01" min="0" value="0"
                        class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                        style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                </div>
                <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t" style="border-color: rgb(var(--border));">
                    <button type="submit" class="flex-1 px-6 py-3 rounded-lg font-medium text-white transition-all hover:scale-105" style="background: linear-gradient(135deg, rgb(var(--primary)), rgb(var(--primary-dark))); box-shadow: 0 4px 6px -1px rgba(var(--primary), 0.3);">
                        <span id="memberSubmitText">Criar Membro</span>
                    </button>
                    <button type="button" onclick="closeMemberModal()" class="flex-1 px-6 py-3 rounded-lg font-medium text-center transition-colors" style="background-color: rgba(var(--primary), 0.1); color: rgb(var(--primary));">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const membersData = @json($members->keyBy('id'));

function filterMembers() {
    const status = document.getElementById('filterStatus').value;
    const employmentType = document.getElementById('filterEmploymentType').value;
    const search = document.getElementById('searchInput').value.toLowerCase();
    
    const items = document.querySelectorAll('.member-item');
    items.forEach(item => {
        const itemStatus = item.getAttribute('data-status');
        const itemEmploymentType = item.getAttribute('data-employment-type');
        const itemName = item.getAttribute('data-name');
        const itemEmail = item.getAttribute('data-email');
        const itemPosition = item.getAttribute('data-position');
        
        const statusMatch = !status || itemStatus === status;
        const typeMatch = !employmentType || itemEmploymentType === employmentType;
        const searchMatch = !search || 
            itemName.includes(search) || 
            itemEmail.includes(search) || 
            itemPosition.includes(search);
        
        if (statusMatch && typeMatch && searchMatch) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
}

function openMemberModal(memberId = null) {
    const modal = document.getElementById('memberModal');
    const form = document.getElementById('memberForm');
    const formMethod = document.getElementById('memberFormMethod');
    const title = document.getElementById('memberModalTitle');
    const submitText = document.getElementById('memberSubmitText');
    
    if (memberId && membersData[memberId]) {
        const member = membersData[memberId];
        title.textContent = 'Editar Membro da Equipe';
        submitText.textContent = 'Salvar Alterações';
        form.action = '{{ route("management.team.update", ":id") }}'.replace(':id', memberId);
        formMethod.innerHTML = '@method("PUT")';
        
        document.getElementById('member_name').value = member.name || '';
        document.getElementById('member_email').value = member.email || '';
        document.getElementById('member_position').value = member.position || '';
        document.getElementById('member_employment_type').value = member.employment_type || 'CLT';
        document.getElementById('member_entry_date').value = member.entry_date || '';
        document.getElementById('member_estimated_cost').value = member.estimated_monthly_cost || 0;
    } else {
        title.textContent = 'Novo Membro da Equipe';
        submitText.textContent = 'Criar Membro';
        form.action = '{{ route("management.team.store") }}';
        formMethod.innerHTML = '';
        form.reset();
        document.getElementById('member_employment_type').value = 'CLT';
        document.getElementById('member_entry_date').value = '{{ now()->format("Y-m-d") }}';
        document.getElementById('member_estimated_cost').value = 0;
    }
    
    modal.style.display = 'block';
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeMemberModal() {
    const modal = document.getElementById('memberModal');
    if (modal) {
        modal.style.display = 'none';
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
}

function openInviteModal() {
    // TODO: Implementar modal de convite
    alert('Funcionalidade de convite será implementada em breve');
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeMemberModal();
    }
});
</script>
@endsection

