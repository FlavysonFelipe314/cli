<!DOCTYPE html>
<html lang="pt-BR" data-theme="carbon-pro" data-color-mode="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cadastro - CLIVUS</title>
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
                <a href="{{ route('public.plans') }}" class="flex items-center gap-3">
                    <img src="{{ asset('assets/logo.png') }}" alt="CLIVUS" class="h-10 w-auto">
                    <h1 class="text-2xl font-bold" style="color: rgb(139, 92, 246);">CLIVUS</h1>
                </a>
                <a href="{{ route('public.plans') }}" class="px-4 py-2 rounded-lg font-medium transition-colors hover:shadow-lg" style="background: linear-gradient(135deg, rgb(139, 92, 246), rgb(124, 58, 237)); color: white; box-shadow: 0 4px 6px -1px rgba(139, 92, 246, 0.3);">
                    ← Voltar aos Planos
                </a>
            </div>
        </header>

        <!-- Content -->
        <section class="py-12 px-4 flex-1">
            <div class="max-w-4xl mx-auto">
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold mb-2" style="color: rgb(var(--text));">Cadastro - {{ $plan->name }}</h2>
                    <p class="text-lg" style="color: rgb(var(--text-secondary));">Preencha seus dados e escolha a forma de pagamento</p>
                </div>

                @if($errors->any())
                <div class="mb-6 p-4 rounded-lg" style="background-color: rgba(239, 68, 68, 0.1); border: 1px solid rgb(220, 38, 38);">
                    <ul class="list-disc list-inside text-sm" style="color: rgb(220, 38, 38);">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Resumo do Plano -->
                    <div class="lg:col-span-1">
                        <div class="rounded-xl p-6 sticky top-4" style="background-color: rgb(var(--card)); border: 1px solid rgb(var(--border)); box-shadow: var(--shadow);">
                            <h3 class="text-xl font-bold mb-4" style="color: rgb(var(--text));">Resumo do Plano</h3>
                            <div class="mb-4">
                                <p class="text-sm mb-1" style="color: rgb(var(--text-secondary));">Plano</p>
                                <p class="text-lg font-semibold" style="color: rgb(var(--text));">{{ $plan->name }}</p>
                            </div>
                            <div class="mb-4">
                                <p class="text-sm mb-1" style="color: rgb(var(--text-secondary));">Valor</p>
                                <p class="text-2xl font-bold" style="color: rgb(var(--primary));">R$ {{ number_format($plan->price, 2, ',', '.') }}</p>
                                <p class="text-sm" style="color: rgb(var(--text-secondary));">/{{ $plan->billing_cycle === 'monthly' ? 'mês' : 'ano' }}</p>
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
                            
                            <div class="pt-4 border-t" style="border-color: rgb(var(--border));">
                                <h4 class="text-sm font-semibold mb-3 flex items-center">
                                    <svg class="w-4 h-4 mr-2" style="color: rgb(34, 197, 94);" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Módulos Incluídos
                                </h4>
                                @if($includedModules->count() > 0)
                                <ul class="space-y-2 mb-4">
                                    @foreach($includedModules as $module)
                                    <li class="flex items-center gap-2 text-sm">
                                        <svg class="w-3 h-3 flex-shrink-0" style="color: rgb(34, 197, 94);" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span style="color: rgb(34, 197, 94);">{{ $module->name }}</span>
                                    </li>
                                    @endforeach
                                </ul>
                                @else
                                <p class="text-xs mb-4" style="color: rgb(var(--text-secondary));">Nenhum módulo incluído</p>
                                @endif
                                
                                <h4 class="text-sm font-semibold mb-3 flex items-center mt-4">
                                    <svg class="w-4 h-4 mr-2" style="color: rgb(239, 68, 68);" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    </svg>
                                    Não Incluídos
                                </h4>
                                @if($excludedModules->count() > 0)
                                <ul class="space-y-2">
                                    @foreach($excludedModules->take(5) as $module)
                                    <li class="flex items-center gap-2 text-sm">
                                        <svg class="w-3 h-3 flex-shrink-0" style="color: rgb(239, 68, 68);" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span style="color: rgb(239, 68, 68);">{{ $module->name }}</span>
                                    </li>
                                    @endforeach
                                    @if($excludedModules->count() > 5)
                                    <li class="text-xs" style="color: rgb(var(--text-secondary));">+ {{ $excludedModules->count() - 5 }} mais</li>
                                    @endif
                                </ul>
                                @else
                                <p class="text-xs" style="color: rgb(var(--text-secondary));">Todos os módulos incluídos</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Formulário de Cadastro -->
                    <div class="lg:col-span-2">
                        <div class="rounded-xl p-6 lg:p-8" style="background-color: rgb(var(--card)); border: 1px solid rgb(var(--border)); box-shadow: var(--shadow);">
                            <form action="{{ route('public.signup.store', $plan) }}" method="POST" class="space-y-6">
                                @csrf

                                <div>
                                    <h3 class="text-xl font-bold mb-6" style="color: rgb(var(--text));">Dados Pessoais</h3>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label for="name" class="block text-sm font-medium mb-2">Nome Completo *</label>
                                            <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                                class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                                                style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                                                placeholder="Seu nome completo">
                                            @error('name')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="email" class="block text-sm font-medium mb-2">Email *</label>
                                            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                                class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                                                style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                                                placeholder="seu@email.com">
                                            @error('email')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="password" class="block text-sm font-medium mb-2">Senha *</label>
                                            <input type="password" id="password" name="password" required
                                                class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                                                style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                                                placeholder="Mínimo 8 caracteres">
                                            @error('password')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="password_confirmation" class="block text-sm font-medium mb-2">Confirmar Senha *</label>
                                            <input type="password" id="password_confirmation" name="password_confirmation" required
                                                class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                                                style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                                                placeholder="Confirme sua senha">
                                        </div>

                                        <div>
                                            <label for="cpf_cnpj" class="block text-sm font-medium mb-2">CPF/CNPJ *</label>
                                            <input type="text" id="cpf_cnpj" name="cpf_cnpj" value="{{ old('cpf_cnpj') }}" required
                                                class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                                                style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                                                placeholder="000.000.000-00">
                                            @error('cpf_cnpj')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="phone" class="block text-sm font-medium mb-2">Telefone *</label>
                                            <input type="text" id="phone" name="phone" value="{{ old('phone') }}" required
                                                class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                                                style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                                                placeholder="(00) 00000-0000">
                                            @error('phone')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="pt-6 border-t" style="border-color: rgb(var(--border));">
                                    <h3 class="text-xl font-bold mb-6" style="color: rgb(var(--text));">Forma de Pagamento</h3>
                                    
                                    <div>
                                        <label for="billing_type" class="block text-sm font-medium mb-2">Método de Pagamento *</label>
                                        <select id="billing_type" name="billing_type" required
                                            class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                                            style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                                            <option value="">Selecione o método</option>
                                            <option value="CREDIT_CARD" {{ old('billing_type') == 'CREDIT_CARD' ? 'selected' : '' }}>Cartão de Crédito</option>
                                            <option value="BOLETO" {{ old('billing_type') == 'BOLETO' ? 'selected' : '' }}>Boleto Bancário</option>
                                            <option value="PIX" {{ old('billing_type') == 'PIX' ? 'selected' : '' }}>PIX</option>
                                        </select>
                                        @error('billing_type')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="flex flex-col sm:flex-row gap-3 pt-4">
                                    <button type="submit" class="flex-1 px-6 py-3 rounded-lg font-medium text-white transition-all hover:scale-105 hover:shadow-lg" style="background: linear-gradient(135deg, rgb(139, 92, 246), rgb(124, 58, 237)); box-shadow: 0 4px 15px -3px rgba(139, 92, 246, 0.4);">
                                        Criar Conta e Assinar
                                    </button>
                                    <a href="{{ route('public.plans') }}" class="flex-1 px-6 py-3 rounded-lg font-medium text-center transition-colors" style="background-color: rgba(139, 92, 246, 0.1); color: rgb(139, 92, 246);">
                                        Cancelar
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</body>
</html>

