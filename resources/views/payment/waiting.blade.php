<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aguardando Pagamento - CLIVUS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --primary: 139, 92, 246;
            --bg: 255, 255, 255;
            --text: 17, 24, 39;
            --text-secondary: 107, 114, 128;
        }
        @media (prefers-color-scheme: dark) {
            :root {
                --bg: 17, 24, 39;
                --text: 243, 244, 246;
                --text-secondary: 156, 163, 175;
            }
        }
    </style>
</head>
<body style="background-color: rgb(var(--bg)); min-height: 100vh; display: flex; align-items: center; justify-content: center;">
    <div class="max-w-md w-full mx-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl p-8 text-center shadow-lg" style="background-color: rgb(var(--bg)); border: 1px solid rgba(var(--primary), 0.2);">
            <div class="mb-6">
                <div class="inline-block animate-spin rounded-full h-16 w-16 border-t-4 border-b-4" style="border-color: rgb(var(--primary));"></div>
            </div>
            
            <h1 class="text-2xl font-bold mb-4" style="color: rgb(var(--text));">Aguardando Confirmação do Pagamento</h1>
            
            <p class="text-lg mb-6" style="color: rgb(var(--text-secondary));">
                Estamos verificando o status do seu pagamento. Você será redirecionado automaticamente quando for confirmado.
            </p>
            
            <div class="space-y-2 mb-6">
                <div class="flex items-center justify-center space-x-2">
                    <div class="w-2 h-2 rounded-full animate-pulse" style="background-color: rgb(var(--primary));"></div>
                    <span class="text-sm" style="color: rgb(var(--text-secondary));">Verificando pagamento...</span>
                </div>
            </div>
            
            <p class="text-sm mb-6" style="color: rgb(var(--text-secondary));">
                Isso pode levar alguns segundos. Por favor, aguarde.
                <br><br>
                <strong>Dica:</strong> Você pode ir para o dashboard agora. O sistema verificará automaticamente quando o pagamento for confirmado.
            </p>
            
            <div class="space-y-3">
                <a href="{{ route('dashboard.index') }}" class="inline-block px-8 py-3 rounded-lg font-medium text-white transition-all hover:scale-105 hover:shadow-lg" style="background: linear-gradient(135deg, rgb(139, 92, 246), rgb(124, 58, 237)); box-shadow: 0 4px 15px -3px rgba(139, 92, 246, 0.4);">
                    Ir para Dashboard
                </a>
                <br>
                <a href="{{ $redirectUrl }}" class="inline-block px-6 py-2 rounded-lg font-medium transition-colors hover:opacity-80" style="background-color: rgba(var(--primary), 0.1); color: rgb(var(--primary));">
                    Verificar Status Agora
                </a>
            </div>
        </div>
    </div>

    <script>
        // Fazer polling para verificar o status do pagamento
        let checkCount = 0;
        const maxChecks = 30; // Verificar por até 30 vezes (30 segundos)
        
        function checkPaymentStatus() {
            checkCount++;
            
            if (checkCount > maxChecks) {
                // Após 30 tentativas, redirecionar para dashboard
                // O dashboard verificará automaticamente os pagamentos pendentes
                window.location.href = "{{ route('dashboard.index') }}";
                return;
            }
            
            // Fazer requisição para verificar status
            fetch("{{ $redirectUrl }}", {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {
                if (data.redirect) {
                    // Se retornou redirect, significa que o pagamento foi confirmado
                    window.location.href = data.redirect;
                } else if (data.status === 'pending') {
                    // Se ainda está pendente, verificar novamente em 1 segundo
                    setTimeout(checkPaymentStatus, 1000);
                } else {
                    // Em caso de outro status, verificar novamente
                    setTimeout(checkPaymentStatus, 1000);
                }
            })
            .catch(error => {
                console.error('Erro ao verificar pagamento:', error);
                // Em caso de erro, verificar novamente
                setTimeout(checkPaymentStatus, 2000);
            });
        }
        
        // Iniciar verificação após 2 segundos
        setTimeout(checkPaymentStatus, 2000);
        
        // Também verificar quando a página recebe foco (usuário voltou do Asaas)
        window.addEventListener('focus', function() {
            checkPaymentStatus();
        });
    </script>
</body>
</html>

