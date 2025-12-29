@extends('layouts.app')

@section('title', 'Configurações - CLIVUS')
@section('page-title', 'Configurações do Sistema')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Configurações do Asaas -->
        <div class="rounded-xl p-6" style="background-color: rgb(var(--card)); border: 1px solid rgb(var(--border)); box-shadow: var(--shadow);">
            <h3 class="text-xl font-bold mb-4 flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Gateway de Pagamento (Asaas)
            </h3>
            
            <div class="space-y-4">
                <div>
                    <label for="asaas_api_key" class="block text-sm font-medium mb-2">Chave API do Asaas *</label>
                    <input type="text" id="asaas_api_key" name="asaas_api_key" value="{{ old('asaas_api_key', env('ASAAS_API_KEY')) }}" required
                        class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                        style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                        placeholder="$aact_YTU5YTE0M2M2N2I4MTI5M2M2N2I4MTI5M2M2N2I4MTI5M2M2N2I4MTI5">
                </div>

                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="asaas_sandbox" value="1" {{ old('asaas_sandbox', env('ASAAS_SANDBOX', true)) ? 'checked' : '' }}
                            class="rounded border-gray-300" style="border-color: rgb(var(--border));">
                        <span class="ml-2 text-sm">Modo Sandbox (Ambiente de Testes)</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Configurações de Email (SMTP) -->
        <div class="rounded-xl p-6" style="background-color: rgb(var(--card)); border: 1px solid rgb(var(--border)); box-shadow: var(--shadow);">
            <h3 class="text-xl font-bold mb-4 flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                Configurações de Email (SMTP)
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="mail_mailer" class="block text-sm font-medium mb-2">Mailer</label>
                    <select id="mail_mailer" name="mail_mailer"
                        class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                        style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                        <option value="smtp" {{ old('mail_mailer', env('MAIL_MAILER')) === 'smtp' ? 'selected' : '' }}>SMTP</option>
                        <option value="sendmail" {{ old('mail_mailer', env('MAIL_MAILER')) === 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                    </select>
                </div>

                <div>
                    <label for="mail_host" class="block text-sm font-medium mb-2">Host SMTP</label>
                    <input type="text" id="mail_host" name="mail_host" value="{{ old('mail_host', env('MAIL_HOST')) }}"
                        class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                        style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                        placeholder="smtp.gmail.com">
                </div>

                <div>
                    <label for="mail_port" class="block text-sm font-medium mb-2">Porta</label>
                    <input type="number" id="mail_port" name="mail_port" value="{{ old('mail_port', env('MAIL_PORT')) }}"
                        class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                        style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                        placeholder="587">
                </div>

                <div>
                    <label for="mail_encryption" class="block text-sm font-medium mb-2">Criptografia</label>
                    <select id="mail_encryption" name="mail_encryption"
                        class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                        style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));">
                        <option value="tls" {{ old('mail_encryption', env('MAIL_ENCRYPTION')) === 'tls' ? 'selected' : '' }}>TLS</option>
                        <option value="ssl" {{ old('mail_encryption', env('MAIL_ENCRYPTION')) === 'ssl' ? 'selected' : '' }}>SSL</option>
                    </select>
                </div>

                <div>
                    <label for="mail_username" class="block text-sm font-medium mb-2">Usuário</label>
                    <input type="text" id="mail_username" name="mail_username" value="{{ old('mail_username', env('MAIL_USERNAME')) }}"
                        class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                        style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                        placeholder="seu@email.com">
                </div>

                <div>
                    <label for="mail_password" class="block text-sm font-medium mb-2">Senha</label>
                    <input type="password" id="mail_password" name="mail_password" value="{{ old('mail_password', env('MAIL_PASSWORD')) }}"
                        class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                        style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                        placeholder="••••••••">
                </div>

                <div>
                    <label for="mail_from_address" class="block text-sm font-medium mb-2">Email Remetente</label>
                    <input type="email" id="mail_from_address" name="mail_from_address" value="{{ old('mail_from_address', env('MAIL_FROM_ADDRESS')) }}"
                        class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                        style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                        placeholder="noreply@clivus.com">
                </div>

                <div>
                    <label for="mail_from_name" class="block text-sm font-medium mb-2">Nome Remetente</label>
                    <input type="text" id="mail_from_name" name="mail_from_name" value="{{ old('mail_from_name', env('MAIL_FROM_NAME')) }}"
                        class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                        style="background-color: rgb(var(--bg)); border-color: rgb(var(--border)); color: rgb(var(--text)); focus:ring-color: rgb(var(--primary));"
                        placeholder="CLIVUS">
                </div>
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="flex-1 px-6 py-3 rounded-lg font-medium text-white transition-all" style="background: linear-gradient(135deg, rgb(var(--primary)), rgb(var(--primary-dark)));">
                Salvar Configurações
            </button>
            <a href="{{ route('admin.dashboard') }}" class="flex-1 px-6 py-3 rounded-lg font-medium text-center transition-colors" style="background-color: rgba(var(--primary), 0.1); color: rgb(var(--primary));">
                Cancelar
            </a>
        </div>
    </form>
</div>
@endsection

