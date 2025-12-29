<!DOCTYPE html>
<html lang="pt-BR" data-theme="carbon-pro" data-color-mode="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pagamento Realizado - CLIVUS</title>
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
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="max-w-2xl w-full">
            <div class="rounded-xl p-8 lg:p-12 text-center" style="background-color: rgb(var(--card)); border: 1px solid rgb(var(--border)); box-shadow: var(--shadow);">
                <div class="mb-6">
                    <svg class="w-20 h-20 mx-auto" style="color: rgb(34, 197, 94);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                
                <h1 class="text-3xl font-bold mb-4" style="color: rgb(var(--text));">Pagamento Realizado com Sucesso!</h1>
                
                <p class="text-lg mb-8" style="color: rgb(var(--text-secondary));">
                    Seu pagamento está sendo processado. Você receberá um email de confirmação em breve.
                </p>
                
                <div class="space-y-4">
                    <a href="{{ route('dashboard.index') }}" class="inline-block px-8 py-3 rounded-lg font-medium text-white transition-all hover:scale-105 hover:shadow-lg" style="background: linear-gradient(135deg, rgb(139, 92, 246), rgb(124, 58, 237)); box-shadow: 0 4px 15px -3px rgba(139, 92, 246, 0.4);">
                        Acessar Sistema
                    </a>
                    <br>
                    <a href="{{ route('subscriptions.index') }}" class="inline-block px-8 py-3 rounded-lg font-medium transition-colors" style="background-color: rgba(139, 92, 246, 0.1); color: rgb(139, 92, 246);">
                        Ver Minha Assinatura
                    </a>
                </div>
                
                <script>
                    // Redirecionar automaticamente após 3 segundos
                    setTimeout(function() {
                        window.location.href = "{{ route('dashboard.index') }}";
                    }, 3000);
                </script>
            </div>
        </div>
    </div>
</body>
</html>

