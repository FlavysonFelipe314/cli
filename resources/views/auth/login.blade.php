<!DOCTYPE html>
<html lang="pt-BR" data-theme="carbon-pro" data-color-mode="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - CLIVUS</title>
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
<body class="min-h-screen flex items-center justify-center p-4" style="background: linear-gradient(135deg, rgba(var(--primary), 0.1), rgba(var(--primary-dark), 0.05)); color: rgb(var(--text));">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center mb-4">
                <img src="{{ asset('assets/logo.png') }}" alt="CLIVUS" class="h-20 w-auto">
            </div>
            <h1 class="text-4xl font-bold mb-2" style="color: rgb(139, 92, 246);">CLIVUS</h1>
            <p class="text-sm font-medium" style="color: rgb(107, 114, 128);">Sistema Financeiro Completo</p>
        </div>

        <div class="rounded-2xl p-8 shadow-2xl backdrop-blur-sm" style="background-color: rgba(255, 255, 255, 0.95); border: 1px solid rgba(139, 92, 246, 0.2);">
            <h2 class="text-2xl font-bold mb-6 text-center" style="color: rgb(139, 92, 246);">Entrar</h2>

            @if(session('message'))
            <div class="mb-4 p-4 rounded-lg" style="background-color: rgba(59, 130, 246, 0.1); border: 1px solid rgb(59, 130, 246); color: rgb(37, 99, 235);">
                <p class="text-sm">{{ session('message') }}</p>
            </div>
            @endif

            @if($errors->any())
            <div class="mb-4 p-4 rounded-lg" style="background-color: rgba(239, 68, 68, 0.1); border: 1px solid rgb(239, 68, 68); color: rgb(220, 38, 38);">
                <ul class="list-disc list-inside text-sm">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="space-y-6">
                @csrf
                
                @if(session('invitation_token'))
                <input type="hidden" name="invitation_token" value="{{ session('invitation_token') }}">
                @endif

                <div>
                    <label for="email" class="block text-sm font-medium mb-2" style="color: rgb(17, 24, 39);">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full px-4 py-3 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                        style="background-color: rgb(255, 255, 255); border-color: rgb(229, 231, 235); color: rgb(17, 24, 39); focus:ring-color: rgb(139, 92, 246);"
                        placeholder="seu@email.com">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium mb-2" style="color: rgb(17, 24, 39);">Senha</label>
                    <input type="password" id="password" name="password" required
                        class="w-full px-4 py-3 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                        style="background-color: rgb(255, 255, 255); border-color: rgb(229, 231, 235); color: rgb(17, 24, 39); focus:ring-color: rgb(139, 92, 246);"
                        placeholder="••••••••">
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" class="rounded border-gray-300"
                            style="border-color: rgb(229, 231, 235);">
                        <span class="ml-2 text-sm" style="color: rgb(107, 114, 128);">Lembrar-me</span>
                    </label>
                </div>

                <button type="submit" class="w-full px-6 py-3 rounded-lg font-medium text-white transition-all hover:scale-105 hover:shadow-lg" style="background: linear-gradient(135deg, rgb(139, 92, 246), rgb(124, 58, 237)); box-shadow: 0 4px 15px -3px rgba(139, 92, 246, 0.4);">
                    Entrar
                </button>
            </form>
        </div>

        <p class="text-center mt-6 text-sm" style="color: rgb(107, 114, 128);">
            Não tem uma conta? <a href="{{ route('public.plans') }}" class="font-medium hover:underline transition-all" style="color: rgb(139, 92, 246);">Veja nossos planos</a>
        </p>
    </div>

    <script>
        // Theme Management (mesmo código do layout principal)
        const savedTheme = localStorage.getItem('theme') || 'carbon-pro';
        const savedColorMode = localStorage.getItem('colorMode') || 'light';
        document.documentElement.setAttribute('data-theme', savedTheme);
        document.documentElement.setAttribute('data-color-mode', savedColorMode);
    </script>
</body>
</html>

