<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class SettingsController extends Controller
{
    public function index()
    {
        return view('admin.settings.index');
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'asaas_api_key' => 'nullable|string',
            'asaas_sandbox' => 'boolean',
            'mail_mailer' => 'nullable|string',
            'mail_host' => 'nullable|string',
            'mail_port' => 'nullable|integer',
            'mail_username' => 'nullable|string',
            'mail_password' => 'nullable|string',
            'mail_encryption' => 'nullable|string',
            'mail_from_address' => 'nullable|email',
            'mail_from_name' => 'nullable|string',
        ]);

        // Atualizar configurações do Asaas
        if (isset($validated['asaas_api_key'])) {
            $this->updateEnv('ASAAS_API_KEY', $validated['asaas_api_key']);
        }
        if (isset($validated['asaas_sandbox'])) {
            $this->updateEnv('ASAAS_SANDBOX', $validated['asaas_sandbox'] ? 'true' : 'false');
        }

        // Atualizar configurações de email
        foreach ($validated as $key => $value) {
            if (str_starts_with($key, 'mail_')) {
                $envKey = strtoupper($key);
                $this->updateEnv($envKey, $value);
            }
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Configurações atualizadas com sucesso!');
    }

    private function updateEnv($key, $value)
    {
        $envFile = base_path('.env');
        if (!file_exists($envFile)) {
            return;
        }

        $env = file_get_contents($envFile);
        $pattern = "/^{$key}=.*/m";
        
        if (preg_match($pattern, $env)) {
            $env = preg_replace($pattern, "{$key}={$value}", $env);
        } else {
            $env .= "\n{$key}={$value}";
        }

        file_put_contents($envFile, $env);
    }
}
