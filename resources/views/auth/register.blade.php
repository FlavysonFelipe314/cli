<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - CLIVUS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --primary: 102, 126, 234;
            --primary-dark: 118, 75, 162;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center" style="background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));">
    <div class="w-full max-w-md p-6">
        <div class="rounded-xl p-8 shadow-2xl" style="background-color: #ffffff; border: 1px solid rgba(102, 126, 234, 0.2);">
            <div class="text-center mb-8">
                <div class="inline-block w-16 h-16 rounded-lg flex items-center justify-center text-white font-bold text-2xl mb-4" style="background: linear-gradient(135deg, rgb(var(--primary)), rgb(var(--primary-dark)));">
                    C
                </div>
                <h1 class="text-3xl font-bold mb-2">Criar Conta</h1>
                @if($invitation)
                <p class="text-sm text-gray-600">Você foi convidado para a equipe de <strong>{{ $invitation->owner->name }}</strong></p>
                @elseif(session('message'))
                <p class="text-sm text-gray-600">{{ session('message') }}</p>
                @else
                <p class="text-sm text-gray-600">Crie sua conta para começar</p>
                @endif
            </div>

            @if(session('error'))
            <div class="mb-4 p-4 rounded-lg bg-red-50 border border-red-200 text-red-800 text-sm">
                {{ session('error') }}
            </div>
            @endif

            @if($errors->any())
            <div class="mb-4 p-4 rounded-lg bg-red-50 border border-red-200 text-red-800 text-sm">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf
                
                @if($invitationToken)
                <input type="hidden" name="invitation_token" value="{{ $invitationToken }}">
                @endif

                <div>
                    <label for="name" class="block text-sm font-medium mb-2">Nome Completo *</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $invitationEmail ? explode('@', $invitationEmail)[0] : '') }}" required
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-purple-500"
                        placeholder="Seu nome completo">
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium mb-2">E-mail *</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $invitationEmail ?? '') }}" required
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-purple-500"
                        placeholder="seu@email.com"
                        {{ $invitationEmail ? 'readonly' : '' }}>
                    @if($invitationEmail)
                    <p class="text-xs text-gray-500 mt-1">Este email está vinculado ao convite</p>
                    @endif
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium mb-2">Senha *</label>
                    <input type="password" id="password" name="password" required
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-purple-500"
                        placeholder="Mínimo 8 caracteres">
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium mb-2">Confirmar Senha *</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-purple-500"
                        placeholder="Digite a senha novamente">
                </div>

                @if($invitation)
                <div class="p-4 rounded-lg bg-blue-50 border border-blue-200">
                    <p class="text-sm text-blue-800">
                        <strong>Convite de Equipe:</strong><br>
                        Você será adicionado à equipe de <strong>{{ $invitation->owner->name }}</strong>
                        @if($invitation->teamMember)
                        <br>Cargo: {{ $invitation->teamMember->position ?? 'Não informado' }}
                        @endif
                    </p>
                </div>
                @endif

                <button type="submit" class="w-full py-3 rounded-lg font-semibold text-white transition-all hover:scale-105 shadow-lg" style="background: linear-gradient(135deg, rgb(var(--primary)), rgb(var(--primary-dark)));">
                    Criar Conta
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Já tem uma conta?
                    <a href="{{ route('login') }}" class="font-medium" style="color: rgb(var(--primary));">
                        Fazer login
                    </a>
                </p>
            </div>

            @if($invitation)
            <div class="mt-4 text-center">
                <p class="text-xs text-gray-500">
                    Este convite expira em: {{ $invitation->expires_at->diffForHumans() }}
                </p>
            </div>
            @endif
        </div>
    </div>
</body>
</html>

