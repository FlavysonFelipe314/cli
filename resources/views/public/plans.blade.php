<!DOCTYPE html>
<html lang="pt-BR" data-theme="carbon-pro" data-color-mode="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Planos - CLIVUS</title>
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
    </style>
</head>
<body style="background-color: rgb(var(--bg)); min-height: 100vh;">
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <header class="py-6 px-4" style="background-color: rgb(var(--card)); border-bottom: 1px solid rgb(var(--border));">
            <div class="max-w-7xl mx-auto flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('assets/logo.png') }}" alt="CLIVUS" class="h-10 w-auto">
                    <h1 class="text-2xl font-bold" style="color: rgb(139, 92, 246);">CLIVUS</h1>
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('login') }}" class="px-4 py-2 rounded-lg font-medium transition-colors hover:shadow-lg" style="background: linear-gradient(135deg, rgb(139, 92, 246), rgb(124, 58, 237)); color: white; box-shadow: 0 4px 6px -1px rgba(139, 92, 246, 0.3);">
                        Entrar
                    </a>
                </div>
            </div>
        </header>

        <!-- Hero Section -->
        <section class="py-16 px-4 text-center">
            <div class="max-w-4xl mx-auto">
                <h2 class="text-4xl md:text-5xl font-bold mb-4" style="color: rgb(var(--text));">
                    Escolha o Plano Ideal para Você
                </h2>
                <p class="text-lg mb-8" style="color: rgb(var(--text-secondary));">
                    Gerencie suas finanças de forma inteligente e profissional
                </p>
            </div>
        </section>

        <!-- Planos -->
        <section class="py-8 px-4 flex-1">
            <div class="max-w-7xl mx-auto">
                @if(session('success'))
                <div class="mb-6 p-4 rounded-lg" style="background-color: rgba(34, 197, 94, 0.1); border: 1px solid rgb(22, 163, 74);">
                    <p style="color: rgb(22, 163, 74);">{{ session('success') }}</p>
                </div>
                @endif

                @if(session('error'))
                <div class="mb-6 p-4 rounded-lg" style="background-color: rgba(239, 68, 68, 0.1); border: 1px solid rgb(220, 38, 38);">
                    <p style="color: rgb(220, 38, 38);">{{ session('error') }}</p>
                </div>
                @endif

                @if($plans->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($plans as $plan)
                    <div class="rounded-xl p-8 transition-all hover:scale-105" style="background-color: rgb(var(--card)); border: 2px solid rgb(var(--border)); box-shadow: var(--shadow);">
                        <div class="text-center mb-6">
                            <div class="flex justify-center mb-4">
                                <img src="{{ asset('assets/logo.png') }}" alt="CLIVUS" class="h-16 w-auto">
                            </div>
                            <h3 class="text-2xl font-bold mb-2" style="color: rgb(var(--text));">{{ $plan->name }}</h3>
                            <p class="text-sm mb-4" style="color: rgb(var(--text-secondary));">{{ $plan->description }}</p>
                            <div class="mb-4">
                                <span class="text-4xl font-bold" style="color: rgb(139, 92, 246);">R$ {{ number_format($plan->price, 2, ',', '.') }}</span>
                                <span class="text-sm" style="color: rgb(var(--text-secondary));">/{{ $plan->billing_cycle === 'monthly' ? 'mês' : 'ano' }}</span>
                            </div>
                        </div>
                        
                        @php
                            $planAllowedModules = $plan->allowed_modules ?? [];
                            $includedModules = $allModules->filter(function($module) use ($planAllowedModules) {
                                return in_array($module->slug, $planAllowedModules);
                            });
                            $excludedModules = $allModules->filter(function($module) use ($planAllowedModules) {
                                return !in_array($module->slug, $planAllowedModules);
                            });
                        @endphp
                        
                        <div class="mb-6">
                            <h4 class="text-sm font-semibold mb-3 flex items-center">
                                <svg class="w-4 h-4 mr-2" style="color: rgb(34, 197, 94);" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                Incluídos
                            </h4>
                            @if($includedModules->count() > 0)
                            <ul class="space-y-2 mb-4">
                                @foreach($includedModules as $module)
                                <li class="flex items-center gap-2 text-sm">
                                    <svg class="w-4 h-4 flex-shrink-0" style="color: rgb(34, 197, 94);" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span style="color: rgb(34, 197, 94);">{{ $module->name }}</span>
                                </li>
                                @endforeach
                            </ul>
                            @else
                            <p class="text-xs" style="color: rgb(var(--text-secondary));">Nenhum módulo incluído</p>
                            @endif
                            
                            <h4 class="text-sm font-semibold mb-3 flex items-center mt-4">
                                <svg class="w-4 h-4 mr-2" style="color: rgb(239, 68, 68);" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                                Não Incluídos
                            </h4>
                            @if($excludedModules->count() > 0)
                            <ul class="space-y-2">
                                @foreach($excludedModules as $module)
                                <li class="flex items-center gap-2 text-sm">
                                    <svg class="w-4 h-4 flex-shrink-0" style="color: rgb(239, 68, 68);" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span style="color: rgb(239, 68, 68);">{{ $module->name }}</span>
                                </li>
                                @endforeach
                            </ul>
                            @else
                            <p class="text-xs" style="color: rgb(var(--text-secondary));">Todos os módulos incluídos</p>
                            @endif
                        </div>
                        
                        <a href="{{ route('public.signup', $plan) }}" class="block w-full px-6 py-4 rounded-lg font-medium text-white text-center transition-all hover:scale-105 hover:shadow-lg" style="background: linear-gradient(135deg, rgb(139, 92, 246), rgb(124, 58, 237)); box-shadow: 0 4px 15px -3px rgba(139, 92, 246, 0.4);">
                            Assinar Agora
                        </a>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-12 rounded-xl" style="background-color: rgb(var(--card)); border: 1px solid rgb(var(--border));">
                    <p class="text-lg mb-4" style="color: rgb(var(--text-secondary));">Nenhum plano disponível no momento.</p>
                </div>
                @endif
            </div>
        </section>

        <!-- Footer -->
        <footer class="py-8 px-4 mt-auto" style="background-color: rgb(var(--bg-secondary)); border-top: 1px solid rgb(var(--border));">
            <div class="max-w-7xl mx-auto text-center">
                <p class="text-sm" style="color: rgb(var(--text-secondary));">
                    &copy; {{ date('Y') }} CLIVUS. Todos os direitos reservados.
                </p>
            </div>
        </footer>
    </div>
</body>
</html>

