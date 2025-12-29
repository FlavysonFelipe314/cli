<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\UserModule;
use App\Services\AsaasService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ModuleStoreController extends Controller
{
    protected AsaasService $asaasService;

    public function __construct(AsaasService $asaasService)
    {
        $this->asaasService = $asaasService;
    }

    /**
     * Exibe a loja de módulos disponíveis
     */
    public function index()
    {
        $user = Auth::user();
        
        // Módulos disponíveis para compra
        $availableModules = Module::where('active', true)
            ->where('price', '>', 0)
            ->orderBy('category')
            ->orderBy('sort_order')
            ->get();

        // Módulos já comprados pelo usuário
        $purchasedModuleIds = $user->userModules()
            ->where('status', 'active')
            ->pluck('module_id')
            ->toArray();

        // Módulos incluídos no plano atual
        $subscription = $user->activeSubscription();
        $planModuleSlugs = [];
        if ($subscription && $subscription->plan) {
            $planModuleSlugs = $subscription->plan->allowed_modules ?? [];
        }

        // Filtrar módulos já incluídos no plano
        $availableModules = $availableModules->filter(function($module) use ($purchasedModuleIds, $planModuleSlugs) {
            return !in_array($module->id, $purchasedModuleIds) && 
                   !in_array($module->slug, $planModuleSlugs);
        });

        // Módulos comprados pelo usuário
        $userModules = $user->userModules()
            ->with('module')
            ->where('status', 'active')
            ->get();

        return view('modules.store', compact('availableModules', 'userModules'));
    }

    /**
     * Processa a compra de um módulo
     */
    public function purchase(Request $request, Module $module)
    {
        $validated = $request->validate([
            'billing_type' => 'required|in:PIX,CREDIT_CARD',
        ]);

        $user = Auth::user();

        // Verificar se já possui o módulo
        $existingModule = $user->userModules()
            ->where('module_id', $module->id)
            ->where('status', 'active')
            ->first();

        if ($existingModule) {
            return redirect()->route('modules.store')
                ->with('error', 'Você já possui este módulo.');
        }

        // Verificar se está incluído no plano
        $subscription = $user->activeSubscription();
        if ($subscription && $subscription->plan) {
            $allowedModules = $subscription->plan->allowed_modules ?? [];
            if (in_array($module->slug, $allowedModules)) {
                return redirect()->route('modules.store')
                    ->with('error', 'Este módulo já está incluído no seu plano.');
            }
        }

        // Verificar se o usuário tem asaas_customer_id
        if (!$user->asaas_customer_id) {
            return redirect()->route('modules.store')
                ->with('error', 'Você precisa ter um plano ativo para comprar módulos adicionais.');
        }

        // Criar pagamento no Asaas
        try {
            $returnUrl = route('modules.payment.callback') . '?module=' . $module->id;
            
            // Verificar se já existe um UserModule pendente para este módulo
            $existingUserModule = UserModule::where('user_id', $user->id)
                ->where('module_id', $module->id)
                ->first();
            
            if ($existingUserModule) {
                // Se já existe e está inativo, tentar usar o pagamento existente
                if ($existingUserModule->status === 'inactive' && $existingUserModule->asaas_payment_id) {
                    // Buscar informações do pagamento no Asaas para obter invoiceUrl
                    try {
                        $existingPayment = $this->asaasService->getPayment($existingUserModule->asaas_payment_id);
                        if ($existingPayment) {
                            if (isset($existingPayment['invoiceUrl'])) {
                                return redirect($existingPayment['invoiceUrl']);
                            } elseif (isset($existingPayment['invoiceNumber'])) {
                                $baseUrl = config('services.asaas.sandbox', true) 
                                    ? 'https://sandbox.asaas.com'
                                    : 'https://www.asaas.com';
                                return redirect("{$baseUrl}/i/{$existingPayment['invoiceNumber']}");
                            }
                        }
                    } catch (\Exception $e) {
                        \Log::warning('Erro ao buscar pagamento existente, criando novo', [
                            'payment_id' => $existingUserModule->asaas_payment_id,
                            'error' => $e->getMessage(),
                        ]);
                        // Continuar para criar novo pagamento
                    }
                }
                
                // Se já existe e está ativo, retornar erro
                if ($existingUserModule->status === 'active') {
                    return redirect()->route('modules.store')
                        ->with('error', 'Você já possui este módulo.');
                }
            }
            
            $payment = $this->asaasService->createPayment([
                'customer_id' => $user->asaas_customer_id,
                'billing_type' => $validated['billing_type'],
                'value' => $module->price,
                'due_date' => now()->addDays(3)->format('Y-m-d'),
                'description' => "Compra do módulo: {$module->name}",
                'return_url' => $returnUrl,
            ]);

            if (!$payment) {
                throw new \Exception('Erro ao criar pagamento no Asaas');
            }

            // Criar ou atualizar registro do módulo do usuário
            if ($existingUserModule) {
                // Atualizar registro existente
                $existingUserModule->update([
                    'subscription_id' => $subscription?->id,
                    'price_paid' => $module->price,
                    'asaas_payment_id' => $payment['id'] ?? null,
                    'status' => 'inactive', // Será ativado quando o pagamento for confirmado via webhook
                    'purchased_at' => now(),
                ]);
            } else {
                // Criar novo registro
                UserModule::create([
                    'user_id' => $user->id,
                    'module_id' => $module->id,
                    'subscription_id' => $subscription?->id,
                    'price_paid' => $module->price,
                    'asaas_payment_id' => $payment['id'] ?? null,
                    'status' => 'inactive', // Será ativado quando o pagamento for confirmado via webhook
                    'purchased_at' => now(),
                ]);
            }

            // Obter URL de pagamento - SEMPRE usar invoiceUrl ou invoiceNumber
            // NÃO usar payment_id diretamente na URL /i/ - o Asaas precisa do invoiceNumber
            $paymentUrl = null;
            if (isset($payment['invoiceUrl'])) {
                // Melhor opção: usar invoiceUrl diretamente do Asaas
                $paymentUrl = $payment['invoiceUrl'];
            } elseif (isset($payment['invoiceNumber'])) {
                // Segunda opção: construir URL usando invoiceNumber
                $baseUrl = config('services.asaas.sandbox', true) 
                    ? 'https://sandbox.asaas.com'
                    : 'https://www.asaas.com';
                $paymentUrl = "{$baseUrl}/i/{$payment['invoiceNumber']}";
            } elseif (isset($payment['checkoutUrl'])) {
                // Terceira opção: usar checkoutUrl se disponível
                $paymentUrl = $payment['checkoutUrl'];
            } elseif (isset($payment['id'])) {
                // Última opção: usar checkout URL com payment_id (não invoice URL)
                $baseUrl = config('services.asaas.sandbox', true) 
                    ? 'https://sandbox.asaas.com'
                    : 'https://www.asaas.com';
                // Usar /c/ para checkout, não /i/ para invoice
                $paymentUrl = "{$baseUrl}/c/{$payment['id']}";
            }

            if ($paymentUrl) {
                \Log::info('Redirecionando para pagamento de módulo', [
                    'module_id' => $module->id,
                    'payment_id' => $payment['id'] ?? null,
                    'payment_url' => $paymentUrl,
                ]);
                return redirect($paymentUrl);
            }

            return redirect()->route('modules.store')
                ->with('info', 'Pagamento criado! O link será enviado por email.');
        } catch (\Exception $e) {
            \Log::error('Erro ao processar compra de módulo: ' . $e->getMessage(), [
                'module_id' => $module->id,
                'user_id' => $user->id,
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->route('modules.store')
                ->with('error', 'Erro ao processar compra: ' . $e->getMessage());
        }
    }

    /**
     * Callback após pagamento do módulo
     */
    public function paymentCallback(Request $request)
    {
        $moduleId = $request->query('module');
        $user = Auth::user();

        if (!$moduleId || !$user) {
            return redirect()->route('modules.store')
                ->with('error', 'Parâmetros inválidos.');
        }

        // Verificar se o módulo foi ativado
        $userModule = UserModule::where('user_id', $user->id)
            ->where('module_id', $moduleId)
            ->where('status', 'active')
            ->first();

        if ($userModule) {
            return redirect()->route('modules.store')
                ->with('success', 'Módulo ativado com sucesso!');
        }

        // Se ainda não foi ativado, verificar se o pagamento está pendente
        $userModule = UserModule::where('user_id', $user->id)
            ->where('module_id', $moduleId)
            ->where('status', 'inactive')
            ->first();

        if ($userModule) {
            return redirect()->route('modules.store')
                ->with('info', 'Aguardando confirmação do pagamento. O módulo será ativado automaticamente quando o pagamento for confirmado.');
        }

        return redirect()->route('modules.store')
            ->with('error', 'Módulo não encontrado.');
    }
}
