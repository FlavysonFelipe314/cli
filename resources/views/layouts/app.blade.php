<!DOCTYPE html>
<html lang="pt-BR" data-theme="carbon-pro" data-color-mode="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'CLIVUS - Financeiro')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [data-theme="carbon-pro"] {
            --primary: 139 92 246;
            --primary-dark: 124 58 237;
            --bg: 255 255 255;
            --bg-secondary: 249 250 251;
            --text: 17 24 39;
            --text-secondary: 107 114 128;
            --border: 229 231 235;
            --card: 255 255 255;
            --shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1);
        }
        [data-theme="carbon-pro"][data-color-mode="dark"] {
            --primary: 167 139 250;
            --primary-dark: 139 92 246;
            --bg: 17 24 39;
            --bg-secondary: 31 41 55;
            --text: 243 244 246;
            --text-secondary: 156 163 175;
            --border: 55 65 81;
            --card: 31 41 55;
            --shadow: 0 1px 3px 0 rgb(0 0 0 / 0.3);
        }
        [data-theme="neo-glass"] {
            --primary: 99 102 241;
            --primary-dark: 79 70 229;
            --bg: 255 255 255;
            --bg-secondary: 248 250 252;
            --text: 15 23 42;
            --text-secondary: 100 116 139;
            --border: 226 232 240;
            --card: rgba(255, 255, 255, 0.8);
            --shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
        }
        [data-theme="neo-glass"][data-color-mode="dark"] {
            --primary: 129 140 248;
            --primary-dark: 99 102 241;
            --bg: 15 23 42;
            --bg-secondary: 30 41 59;
            --text: 241 245 249;
            --text-secondary: 148 163 184;
            --border: 51 65 85;
            --card: rgba(30, 41, 59, 0.8);
            --shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.5);
        }
        [data-theme="cyber-minimal"] {
            --primary: 34 197 94;
            --primary-dark: 22 163 74;
            --bg: 0 0 0;
            --bg-secondary: 17 17 17;
            --text: 255 255 255;
            --text-secondary: 163 163 163;
            --border: 38 38 38;
            --card: 17 17 17;
            --shadow: 0 0 20px rgba(34, 197, 94, 0.1);
        }
        [data-theme="cyber-minimal"][data-color-mode="light"] {
            --primary: 22 163 74;
            --primary-dark: 21 128 61;
            --bg: 255 255 255;
            --bg-secondary: 250 250 250;
            --text: 0 0 0;
            --text-secondary: 64 64 64;
            --border: 229 229 229;
            --card: 255 255 255;
            --shadow: 0 0 20px rgba(22, 163, 74, 0.1);
        }
        [data-theme="material-you"] {
            --primary: 103 80 164;
            --primary-dark: 81 63 129;
            --bg: 255 255 255;
            --bg-secondary: 249 250 251;
            --text: 28 28 30;
            --text-secondary: 99 99 102;
            --border: 229 229 234;
            --card: 255 255 255;
            --shadow: 0 1px 3px rgba(0, 0, 0, 0.12);
        }
        [data-theme="material-you"][data-color-mode="dark"] {
            --primary: 208 188 255;
            --primary-dark: 186 160 255;
            --bg: 28 28 30;
            --bg-secondary: 44 44 46;
            --text: 255 255 255;
            --text-secondary: 174 174 178;
            --border: 58 58 60;
            --card: 44 44 46;
            --shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
        }
        [data-theme="ocean-clivus"] {
            --primary: 14 165 233;
            --primary-dark: 2 132 199;
            --bg: 255 255 255;
            --bg-secondary: 240 249 255;
            --text: 15 23 42;
            --text-secondary: 100 116 139;
            --border: 226 232 240;
            --card: 255 255 255;
            --shadow: 0 4px 6px -1px rgba(14, 165, 233, 0.1);
        }
        [data-theme="ocean-clivus"][data-color-mode="dark"] {
            --primary: 56 189 248;
            --primary-dark: 14 165 233;
            --bg: 15 23 42;
            --bg-secondary: 30 58 138;
            --text: 241 245 249;
            --text-secondary: 148 163 184;
            --border: 51 65 85;
            --card: 30 58 138;
            --shadow: 0 4px 6px -1px rgba(56, 189, 248, 0.2);
        }
        [data-theme="padrao"] {
            --primary: 59 130 246;
            --primary-dark: 37 99 235;
            --bg: 255 255 255;
            --bg-secondary: 249 250 251;
            --text: 17 24 39;
            --text-secondary: 107 114 128;
            --border: 229 231 235;
            --card: 255 255 255;
            --shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1);
        }
        [data-theme="padrao"][data-color-mode="dark"] {
            --primary: 96 165 250;
            --primary-dark: 59 130 246;
            --bg: 17 24 39;
            --bg-secondary: 31 41 55;
            --text: 243 244 246;
            --text-secondary: 156 163 175;
            --border: 55 65 81;
            --card: 31 41 55;
            --shadow: 0 1px 3px 0 rgb(0 0 0 / 0.3);
        }
    </style>
</head>
<body class="min-h-screen" style="background-color: rgb(var(--bg)); color: rgb(var(--text)); transition: background-color 0.3s, color 0.3s;">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="hidden lg:flex lg:flex-col lg:fixed lg:left-0 lg:top-0 lg:h-screen lg:w-64 border-r z-20" style="background-color: rgb(var(--bg-secondary)); border-color: rgb(var(--border));">
            <div class="flex items-center gap-2 p-6 border-b" style="border-color: rgb(var(--border));">
                <img src="{{ asset('assets/logo.png') }}" alt="CLIVUS" class="h-10 w-auto">
                <span class="text-xl font-bold">CLIVUS</span>
            </div>
            <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
                @php
                    use App\Helpers\NavHelper;
                    $user = Auth::user();
                    $isSuperAdmin = $user && $user->isSuperAdmin();
                    $isToolsLocked = $user && !$isSuperAdmin ? NavHelper::isLocked('tools') : true;
                    $isFinanceLocked = $user && !$isSuperAdmin ? NavHelper::isLocked('finance') : true;
                    $isManagementLocked = $user && !$isSuperAdmin ? NavHelper::isLocked('management') : true;
                @endphp
                
                @if($isSuperAdmin)
                {{-- Menu Super Admin --}}
                <div class="mb-4">
                    <p class="text-xs font-semibold uppercase mb-2 px-3" style="color: rgb(var(--text-secondary));">ADMINISTRAÇÃO</p>
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? '' : 'hover:bg-opacity-50' }}" style="{{ request()->routeIs('admin.dashboard') ? 'background-color: rgb(var(--primary)); color: white;' : 'background-color: rgba(var(--primary), 0.1);' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('admin.plans.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.plans.*') ? '' : 'hover:bg-opacity-50' }}" style="{{ request()->routeIs('admin.plans.*') ? 'background-color: rgb(var(--primary)); color: white;' : 'background-color: rgba(var(--primary), 0.1);' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span>Planos</span>
                    </a>
                    <a href="{{ route('admin.modules.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.modules.*') ? '' : 'hover:bg-opacity-50' }}" style="{{ request()->routeIs('admin.modules.*') ? 'background-color: rgb(var(--primary)); color: white;' : 'background-color: rgba(var(--primary), 0.1);' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        <span>Módulos</span>
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.users.*') ? '' : 'hover:bg-opacity-50' }}" style="{{ request()->routeIs('admin.users.*') ? 'background-color: rgb(var(--primary)); color: white;' : 'background-color: rgba(var(--primary), 0.1);' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        <span>Usuários</span>
                    </a>
                    <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.settings.*') ? '' : 'hover:bg-opacity-50' }}" style="{{ request()->routeIs('admin.settings.*') ? 'background-color: rgb(var(--primary)); color: white;' : 'background-color: rgba(var(--primary), 0.1);' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span>Configurações</span>
                    </a>
                </div>
                @else
                {{-- Menu Usuário Normal --}}
                <div class="mb-4">
                    <p class="text-xs font-semibold uppercase mb-2 px-3" style="color: rgb(var(--text-secondary));">PRINCIPAL</p>
                    <a href="{{ route('dashboard.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('dashboard.index') ? '' : 'hover:bg-opacity-50' }}" style="{{ request()->routeIs('dashboard.index') ? 'background-color: rgb(var(--primary)); color: white;' : 'background-color: rgba(var(--primary), 0.1);' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        <span>Início</span>
                    </a>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase mb-2 px-3" style="color: rgb(var(--text-secondary));">FINANCEIRO</p>
                    <x-nav-link route="{{ route('finance.accounts.index') }}" text="Contas" icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>' :is-locked="NavHelper::isRouteLocked('finance.accounts.index')" />
                    <x-nav-link route="{{ route('finance.transactions.index') }}" text="Transações" icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>' :is-locked="NavHelper::isRouteLocked('finance.transactions.index')" />
                    <x-nav-link route="{{ route('finance.contacts.index') }}" text="Contatos" icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>' :is-locked="NavHelper::isRouteLocked('finance.contacts.index')" />
                    <x-nav-link route="{{ route('finance.payables.index') }}" text="Contas a Pagar" icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>' :is-locked="NavHelper::isRouteLocked('finance.payables.index')" />
                    <x-nav-link route="{{ route('finance.receivables.index') }}" text="Contas a Receber" icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>' :is-locked="NavHelper::isRouteLocked('finance.receivables.index')" />
                    <x-nav-link route="{{ route('finance.planning.index') }}" text="Planejamento" icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>' :is-locked="NavHelper::isRouteLocked('finance.planning.index')" />
                    <x-nav-link route="{{ route('finance.reconciliations.index') }}" text="Conciliação" icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>' :is-locked="NavHelper::isRouteLocked('finance.reconciliations.index')" />
                    <x-nav-link route="{{ route('finance.indirect-costs.index') }}" text="Custos Indiretos" icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>' :is-locked="NavHelper::isRouteLocked('finance.indirect-costs.index')" />
                    <x-nav-link route="{{ route('finance.categories.index') }}" text="Categorias" icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>' :is-locked="NavHelper::isRouteLocked('finance.categories.index')" />
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase mb-2 px-3" style="color: rgb(var(--text-secondary));">FERRAMENTAS</p>
                    <x-nav-link route="{{ route('tools.prolabore.index') }}" text="Pró-labore" icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>' :is-locked="$isToolsLocked || ($user && !$user->hasModuleAccess('prolabore'))" />
                    <x-nav-link route="{{ route('tools.pricing.index') }}" text="Precificação" icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>' :is-locked="$isToolsLocked || ($user && !$user->hasModuleAccess('pricing'))" />
                    <x-nav-link route="{{ route('tools.employee-cost.index') }}" text="Custo de Funcionário (CLT)" icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>' :is-locked="$isToolsLocked || ($user && !$user->hasModuleAccess('employee-cost'))" />
                    <x-nav-link route="{{ route('tools.compliance.index') }}" text="Compliance" icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>' :is-locked="$isToolsLocked || ($user && !$user->hasModuleAccess('compliance'))" />
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase mb-2 px-3" style="color: rgb(var(--text-secondary));">GESTÃO</p>
                    <x-nav-link route="{{ route('management.team.index') }}" text="Equipe" icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>' :is-locked="$isManagementLocked || ($user && !$user->hasModuleAccess('team-management'))" />
                    <x-nav-link route="{{ route('management.tasks.index') }}" text="Tarefas / Kanban" icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>' :is-locked="$isManagementLocked || ($user && !$user->hasModuleAccess('task-management'))" />
                    <x-nav-link route="{{ route('management.calendar.index') }}" text="Agenda / Calendário" icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>' :is-locked="$isManagementLocked || ($user && !$user->hasModuleAccess('calendar'))" />
                </div>
                @auth
                <div>
                    <p class="text-xs font-semibold uppercase mb-2 px-3" style="color: rgb(var(--text-secondary));">CONTA</p>
                    <a href="{{ route('team.teams') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('team.*') ? '' : 'hover:bg-opacity-50' }}" style="{{ request()->routeIs('team.*') ? 'background-color: rgb(var(--primary)); color: white;' : 'background-color: rgba(var(--primary), 0.1);' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span>Minhas Equipes</span>
                    </a>
                    <a href="{{ route('profile.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('profile.*') ? '' : 'hover:bg-opacity-50' }}" style="{{ request()->routeIs('profile.*') ? 'background-color: rgb(var(--primary)); color: white;' : 'background-color: rgba(var(--primary), 0.1);' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span>Meu Perfil</span>
                    </a>
                    <a href="{{ route('subscriptions.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('subscriptions.*') ? '' : 'hover:bg-opacity-50' }}" style="{{ request()->routeIs('subscriptions.*') ? 'background-color: rgb(var(--primary)); color: white;' : 'background-color: rgba(var(--primary), 0.1);' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                        <span>Assinaturas</span>
                    </a>
                    @if($user && $user->hasActiveSubscription())
                    <a href="{{ route('modules.store') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('modules.*') ? '' : 'hover:bg-opacity-50' }}" style="{{ request()->routeIs('modules.*') ? 'background-color: rgb(var(--primary)); color: white;' : 'background-color: rgba(var(--primary), 0.1);' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        <span>Loja de Módulos</span>
                    </a>
                    @endif
                </div>
                @endauth
                @endif
            </nav>
        </aside>

        <!-- Mobile Sidebar Toggle -->
        <button id="mobile-menu-toggle" class="lg:hidden fixed top-4 left-4 z-50 p-2 rounded-lg" style="background-color: rgb(var(--card)); border: 1px solid rgb(var(--border)); box-shadow: var(--shadow);">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>

        <!-- Mobile Sidebar -->
        <aside id="mobile-sidebar" class="lg:hidden fixed inset-y-0 left-0 z-40 w-64 transform -translate-x-full transition-transform duration-300" style="background-color: rgb(var(--bg-secondary)); border-right: 1px solid rgb(var(--border));">
            <div class="flex items-center gap-2 p-6 border-b" style="border-color: rgb(var(--border));">
                <img src="{{ asset('assets/logo.png') }}" alt="CLIVUS" class="h-10 w-auto">
                <span class="text-xl font-bold">CLIVUS</span>
            </div>
            <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
                @php
                    $userMobile = Auth::user();
                    $isSuperAdminMobile = $userMobile && $userMobile->isSuperAdmin();
                    $isToolsLockedMobile = $userMobile && !$isSuperAdminMobile ? NavHelper::isLocked('tools') : true;
                    $isFinanceLockedMobile = $userMobile && !$isSuperAdminMobile ? NavHelper::isLocked('finance') : true;
                    $isManagementLockedMobile = $userMobile && !$isSuperAdminMobile ? NavHelper::isLocked('management') : true;
                @endphp
                
                @if($isSuperAdminMobile)
                {{-- Menu Super Admin Mobile --}}
                <div class="mb-4">
                    <p class="text-xs font-semibold uppercase mb-2 px-3" style="color: rgb(var(--text-secondary));">ADMINISTRAÇÃO</p>
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? '' : 'hover:bg-opacity-50' }}" style="{{ request()->routeIs('admin.dashboard') ? 'background-color: rgb(var(--primary)); color: white;' : 'background-color: rgba(var(--primary), 0.1);' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('admin.plans.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.plans.*') ? '' : 'hover:bg-opacity-50' }}" style="{{ request()->routeIs('admin.plans.*') ? 'background-color: rgb(var(--primary)); color: white;' : 'background-color: rgba(var(--primary), 0.1);' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span>Planos</span>
                    </a>
                    <a href="{{ route('admin.modules.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.modules.*') ? '' : 'hover:bg-opacity-50' }}" style="{{ request()->routeIs('admin.modules.*') ? 'background-color: rgb(var(--primary)); color: white;' : 'background-color: rgba(var(--primary), 0.1);' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        <span>Módulos</span>
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.users.*') ? '' : 'hover:bg-opacity-50' }}" style="{{ request()->routeIs('admin.users.*') ? 'background-color: rgb(var(--primary)); color: white;' : 'background-color: rgba(var(--primary), 0.1);' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        <span>Usuários</span>
                    </a>
                    <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.settings.*') ? '' : 'hover:bg-opacity-50' }}" style="{{ request()->routeIs('admin.settings.*') ? 'background-color: rgb(var(--primary)); color: white;' : 'background-color: rgba(var(--primary), 0.1);' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span>Configurações</span>
                    </a>
                </div>
                @else
                {{-- Menu Usuário Normal Mobile --}}
                <div class="mb-4">
                    <p class="text-xs font-semibold uppercase mb-2 px-3" style="color: rgb(var(--text-secondary));">PRINCIPAL</p>
                    <a href="{{ route('dashboard.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('dashboard.index') ? '' : 'hover:bg-opacity-50' }}" style="{{ request()->routeIs('dashboard.index') ? 'background-color: rgb(var(--primary)); color: white;' : 'background-color: rgba(var(--primary), 0.1);' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        <span>Início</span>
                    </a>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase mb-2 px-3" style="color: rgb(var(--text-secondary));">FINANCEIRO</p>
                    <x-nav-link route="{{ route('finance.accounts.index') }}" text="Contas" icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>' :is-locked="NavHelper::isRouteLocked('finance.accounts.index')" />
                    <x-nav-link route="{{ route('finance.transactions.index') }}" text="Transações" icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>' :is-locked="NavHelper::isRouteLocked('finance.transactions.index')" />
                    <x-nav-link route="{{ route('finance.contacts.index') }}" text="Contatos" icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>' :is-locked="NavHelper::isRouteLocked('finance.contacts.index')" />
                    <x-nav-link route="{{ route('finance.payables.index') }}" text="Contas a Pagar" icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>' :is-locked="NavHelper::isRouteLocked('finance.payables.index')" />
                    <x-nav-link route="{{ route('finance.receivables.index') }}" text="Contas a Receber" icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>' :is-locked="NavHelper::isRouteLocked('finance.receivables.index')" />
                    <x-nav-link route="{{ route('finance.planning.index') }}" text="Planejamento" icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>' :is-locked="NavHelper::isRouteLocked('finance.planning.index')" />
                    <x-nav-link route="{{ route('finance.reconciliations.index') }}" text="Conciliação" icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>' :is-locked="NavHelper::isRouteLocked('finance.reconciliations.index')" />
                    <x-nav-link route="{{ route('finance.indirect-costs.index') }}" text="Custos Indiretos" icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>' :is-locked="NavHelper::isRouteLocked('finance.indirect-costs.index')" />
                    <x-nav-link route="{{ route('finance.categories.index') }}" text="Categorias" icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>' :is-locked="NavHelper::isRouteLocked('finance.categories.index')" />
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase mb-2 px-3" style="color: rgb(var(--text-secondary));">FERRAMENTAS</p>
                    <x-nav-link route="{{ route('tools.prolabore.index') }}" text="Pró-labore" icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>' :is-locked="$isToolsLockedMobile || ($userMobile && !$userMobile->hasModuleAccess('prolabore'))" />
                    <x-nav-link route="{{ route('tools.pricing.index') }}" text="Precificação" icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>' :is-locked="$isToolsLockedMobile || ($userMobile && !$userMobile->hasModuleAccess('pricing'))" />
                    <x-nav-link route="{{ route('tools.employee-cost.index') }}" text="Custo de Funcionário (CLT)" icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>' :is-locked="$isToolsLockedMobile || ($userMobile && !$userMobile->hasModuleAccess('employee-cost'))" />
                    <x-nav-link route="{{ route('tools.compliance.index') }}" text="Compliance" icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>' :is-locked="$isToolsLockedMobile || ($userMobile && !$userMobile->hasModuleAccess('compliance'))" />
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase mb-2 px-3" style="color: rgb(var(--text-secondary));">GESTÃO</p>
                    <x-nav-link route="{{ route('management.team.index') }}" text="Equipe" icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>' :is-locked="$isManagementLockedMobile || ($userMobile && !$userMobile->hasModuleAccess('team-management'))" />
                    <x-nav-link route="{{ route('management.tasks.index') }}" text="Tarefas / Kanban" icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>' :is-locked="$isManagementLockedMobile || ($userMobile && !$userMobile->hasModuleAccess('task-management'))" />
                    <x-nav-link route="{{ route('management.calendar.index') }}" text="Agenda / Calendário" icon='<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>' :is-locked="$isManagementLockedMobile || ($userMobile && !$userMobile->hasModuleAccess('calendar'))" />
                </div>
                @endif
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-w-0 lg:ml-64">
            <!-- Header -->
            <header class="sticky top-0 z-30 border-b" style="background-color: rgb(var(--card)); border-color: rgb(var(--border)); box-shadow: var(--shadow);">
                <div class="flex items-center justify-between p-4">
                    <div class="flex items-center gap-4">
                        <h1 class="text-2xl font-bold">@yield('page-title', 'Dashboard')</h1>
                    </div>
                    <div class="flex items-center gap-3">
                        <!-- Theme Selector -->
                        <div class="relative">
                            <button id="theme-toggle" class="p-2 rounded-lg hover:bg-opacity-50 transition-colors" style="background-color: rgba(var(--primary), 0.1);">
                                <svg id="theme-icon-light" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                                <svg id="theme-icon-dark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                                </svg>
                            </button>
                            <div id="theme-dropdown" class="hidden absolute right-0 mt-2 w-48 rounded-lg shadow-lg z-50" style="background-color: rgb(var(--card)); border: 1px solid rgb(var(--border)); box-shadow: var(--shadow);">
                                <div class="p-2">
                                    <button class="theme-option w-full text-left px-3 py-2 rounded-lg hover:bg-opacity-50 transition-colors flex items-center justify-between" data-theme="neo-glass" style="background-color: rgba(var(--primary), 0.1);">
                                        <span>Neo Glass</span>
                                    </button>
                                    <button class="theme-option w-full text-left px-3 py-2 rounded-lg hover:bg-opacity-50 transition-colors flex items-center justify-between" data-theme="cyber-minimal" style="background-color: rgba(var(--primary), 0.1);">
                                        <span>Cyber Minimal</span>
                                    </button>
                                    <button class="theme-option w-full text-left px-3 py-2 rounded-lg hover:bg-opacity-50 transition-colors flex items-center justify-between" data-theme="material-you" style="background-color: rgba(var(--primary), 0.1);">
                                        <span>Material You</span>
                                    </button>
                                    <button class="theme-option w-full text-left px-3 py-2 rounded-lg hover:bg-opacity-50 transition-colors flex items-center justify-between" data-theme="carbon-pro" style="background-color: rgba(var(--primary), 0.1);">
                                        <span>Carbon Pro</span>
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </button>
                                    <button class="theme-option w-full text-left px-3 py-2 rounded-lg hover:bg-opacity-50 transition-colors flex items-center justify-between" data-theme="ocean-clivus" style="background-color: rgba(var(--primary), 0.1);">
                                        <span>Ocean (Clivus)</span>
                                    </button>
                                    <button class="theme-option w-full text-left px-3 py-2 rounded-lg hover:bg-opacity-50 transition-colors flex items-center justify-between" data-theme="padrao" style="background-color: rgba(var(--primary), 0.1);">
                                        <span>Padrão</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @auth
                        <a href="{{ route('profile.index') }}" class="flex items-center gap-2 px-3 py-1 rounded-lg transition-colors hover:bg-opacity-50" style="background-color: rgba(var(--primary), 0.1);">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span class="text-sm font-medium">{{ Auth::user()->name ?? 'Usuário' }}</span>
                        </a>
                        @if(Auth::user()->isSuperAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 rounded-lg font-medium transition-colors" style="background-color: rgba(239, 68, 68, 0.1); color: rgb(239, 68, 68);">
                            Admin
                        </a>
                        @endif
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="px-4 py-2 rounded-lg font-medium transition-colors" style="background-color: rgba(239, 68, 68, 0.1); color: rgb(239, 68, 68);">
                                Sair
                            </button>
                        </form>
                        @endauth
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 p-4 lg:p-6 overflow-y-auto">
                @if(session('success'))
                <div class="mb-4 p-4 rounded-lg" style="background-color: rgba(34, 197, 94, 0.1); border: 1px solid rgb(34, 197, 94); color: rgb(22, 163, 74);">
                    {{ session('success') }}
                </div>
                @endif

                @if($errors->any())
                <div class="mb-4 p-4 rounded-lg" style="background-color: rgba(239, 68, 68, 0.1); border: 1px solid rgb(239, 68, 68); color: rgb(220, 38, 38);">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Overlay for mobile menu -->
    <div id="mobile-overlay" class="lg:hidden fixed inset-0 bg-black bg-opacity-50 z-30 hidden"></div>

    <script>
        // Theme Management
        const themes = ['neo-glass', 'cyber-minimal', 'material-you', 'carbon-pro', 'ocean-clivus', 'padrao'];
        const colorModes = ['light', 'dark'];
        
        // Load saved theme
        const savedTheme = localStorage.getItem('theme') || 'carbon-pro';
        const savedColorMode = localStorage.getItem('colorMode') || 'light';
        
        document.documentElement.setAttribute('data-theme', savedTheme);
        document.documentElement.setAttribute('data-color-mode', savedColorMode);
        updateThemeIcon(savedColorMode);
        
        // Theme toggle (light/dark)
        document.getElementById('theme-toggle').addEventListener('click', (e) => {
            e.stopPropagation();
            const currentMode = document.documentElement.getAttribute('data-color-mode');
            const newMode = currentMode === 'light' ? 'dark' : 'light';
            document.documentElement.setAttribute('data-color-mode', newMode);
            localStorage.setItem('colorMode', newMode);
            updateThemeIcon(newMode);
        });
        
        // Theme selector dropdown
        let themeDropdownOpen = false;
        document.getElementById('theme-toggle').addEventListener('click', (e) => {
            e.stopPropagation();
            const dropdown = document.getElementById('theme-dropdown');
            themeDropdownOpen = !themeDropdownOpen;
            dropdown.classList.toggle('hidden', !themeDropdownOpen);
        });
        
        document.querySelectorAll('.theme-option').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                const theme = btn.getAttribute('data-theme');
                document.documentElement.setAttribute('data-theme', theme);
                localStorage.setItem('theme', theme);
                
                // Update checkmarks
                document.querySelectorAll('.theme-option svg').forEach(svg => svg.remove());
                btn.innerHTML += '<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>';
                
                document.getElementById('theme-dropdown').classList.add('hidden');
                themeDropdownOpen = false;
            });
        });
        
        function updateThemeIcon(mode) {
            document.getElementById('theme-icon-light').classList.toggle('hidden', mode !== 'light');
            document.getElementById('theme-icon-dark').classList.toggle('hidden', mode !== 'dark');
        }
        
        // Close dropdown when clicking outside
        document.addEventListener('click', () => {
            if (themeDropdownOpen) {
                document.getElementById('theme-dropdown').classList.add('hidden');
                themeDropdownOpen = false;
            }
        });
        
        // Mobile menu toggle
        const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
        const mobileSidebar = document.getElementById('mobile-sidebar');
        const mobileOverlay = document.getElementById('mobile-overlay');
        
        mobileMenuToggle.addEventListener('click', () => {
            mobileSidebar.classList.toggle('-translate-x-full');
            mobileOverlay.classList.toggle('hidden');
        });
        
        mobileOverlay.addEventListener('click', () => {
            mobileSidebar.classList.add('-translate-x-full');
            mobileOverlay.classList.add('hidden');
        });
    </script>
    <!-- Modal de Módulo Bloqueado -->
    <div id="module-locked-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4" style="background-color: rgba(0, 0, 0, 0.5); backdrop-filter: blur(8px);">
        <div class="bg-white rounded-2xl p-8 max-w-md w-full shadow-2xl transform transition-all" style="background-color: rgb(255, 255, 255);">
            <div class="text-center mb-6">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full mb-4" style="background-color: rgba(239, 68, 68, 0.1);">
                    <svg class="w-8 h-8" style="color: rgb(239, 68, 68);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold mb-2" style="color: rgb(17, 24, 39);">Módulo Bloqueado</h3>
                <p class="text-sm" id="module-locked-name" style="color: rgb(107, 114, 128);"></p>
            </div>
            
            <p class="text-center mb-6" style="color: rgb(107, 114, 128);">
                Este módulo não está disponível no seu plano atual. Escolha uma das opções abaixo para desbloquear:
            </p>
            
            <div class="space-y-3">
                <a href="{{ route('subscriptions.index') }}" class="block w-full px-6 py-3 rounded-lg font-medium text-white text-center transition-all hover:scale-105" style="background: linear-gradient(135deg, rgb(var(--primary)), rgb(var(--primary-dark)));">
                    Fazer Upgrade de Plano
                </a>
                <a href="{{ route('modules.store') }}" class="block w-full px-6 py-3 rounded-lg font-medium text-center transition-colors" style="background-color: rgba(var(--primary), 0.1); color: rgb(var(--primary)); border: 1px solid rgb(var(--primary));">
                    Comprar Módulo na Loja
                </a>
                <button onclick="closeModuleLockedModal()" class="block w-full px-6 py-3 rounded-lg font-medium text-center transition-colors" style="background-color: rgb(249, 250, 251); color: rgb(107, 114, 128);">
                    Cancelar
                </button>
            </div>
        </div>
    </div>

    <script>
        function showModuleLockedModal(moduleName) {
            document.getElementById('module-locked-name').textContent = moduleName;
            document.getElementById('module-locked-modal').classList.remove('hidden');
        }

        function closeModuleLockedModal() {
            document.getElementById('module-locked-modal').classList.add('hidden');
        }

        // Fechar modal ao clicar fora
        document.getElementById('module-locked-modal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModuleLockedModal();
            }
        });
    </script>
    </body>
</html>

