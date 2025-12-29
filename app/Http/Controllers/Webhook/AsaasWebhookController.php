<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Mail\PaymentConfirmedMail;
use App\Models\Subscription;
use App\Models\User;
use App\Models\UserModule;
use App\Services\AsaasService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AsaasWebhookController extends Controller
{
    protected AsaasService $asaasService;

    public function __construct(AsaasService $asaasService)
    {
        $this->asaasService = $asaasService;
    }

    /**
     * Processar webhook do Asaas
     */
    public function handle(Request $request)
    {
        $event = $request->input('event');
        $data = $request->all();

        Log::info('Asaas Webhook Received', [
            'event' => $event,
            'data' => $data,
        ]);

        $paymentId = $data['payment']['id'] ?? null;
        
        // Primeiro, verificar se é pagamento de módulo
        $userModule = null;
        if ($paymentId) {
            $userModule = UserModule::where('asaas_payment_id', $paymentId)->first();
        }

        // Tentar obter subscription ID de diferentes lugares
        // Pode vir como objeto ou string dependendo do evento
        $subscriptionId = null;
        if (isset($data['subscription'])) {
            $subscriptionId = is_array($data['subscription']) 
                ? ($data['subscription']['id'] ?? null)
                : $data['subscription'];
        }
        if (!$subscriptionId && isset($data['payment']['subscription'])) {
            $subscriptionId = $data['payment']['subscription'];
        }
        
        $subscription = null;
        
        // Se temos subscription_id, buscar diretamente
        if ($subscriptionId) {
            $subscription = Subscription::where('asaas_subscription_id', $subscriptionId)->first();
        }
        
        // Se não encontrou e temos customer_id, buscar pela assinatura mais recente pendente do cliente
        if (!$subscription && isset($data['payment']['customer'])) {
            $customerId = $data['payment']['customer'];
            Log::info('Searching subscription by customer_id', [
                'customer_id' => $customerId,
                'event' => $event
            ]);
            
            $subscription = Subscription::where('asaas_customer_id', $customerId)
                ->where('status', 'pending')
                ->orderBy('created_at', 'desc')
                ->first();
            
            if (!$subscription) {
                // Se não encontrou pendente, buscar a mais recente
                $subscription = Subscription::where('asaas_customer_id', $customerId)
                    ->orderBy('created_at', 'desc')
                    ->first();
            }
        }
        
        // Se não encontrou subscription nem userModule, retornar erro apenas se não for evento de módulo
        if (!$subscription && !$userModule) {
            Log::warning('Subscription or UserModule not found in database', [
                'subscription_id' => $subscriptionId,
                'customer_id' => $data['payment']['customer'] ?? null,
                'event' => $event,
                'payment_id' => $paymentId
            ]);
            // Não retornar erro 404, apenas logar - pode ser um pagamento que não reconhecemos
            return response()->json(['success' => true, 'message' => 'Payment not associated with subscription or module']);
        }

        switch ($event) {
            case 'PAYMENT_CONFIRMED':
                // Processar pagamento de módulo primeiro (prioridade)
                if ($userModule) {
                    if ($userModule->status === 'inactive') {
                        // Ativar módulo do usuário
                        $userModule->update([
                            'status' => 'active',
                        ]);
                        
                        Log::info('User module activated', [
                            'user_module_id' => $userModule->id,
                            'module_id' => $userModule->module_id,
                            'user_id' => $userModule->user_id,
                            'payment_id' => $paymentId
                        ]);
                        
                        // Enviar email de confirmação (opcional)
                        $user = $userModule->user;
                        if ($user) {
                            try {
                                // Você pode criar um email específico para módulos se quiser
                                Log::info('Module purchase confirmed', [
                                    'user_id' => $user->id,
                                    'module_id' => $userModule->module_id
                                ]);
                            } catch (\Exception $e) {
                                Log::error('Failed to send module confirmation', [
                                    'user_id' => $user->id,
                                    'error' => $e->getMessage()
                                ]);
                            }
                        }
                    } else {
                        Log::info('User module already active', [
                            'user_module_id' => $userModule->id,
                            'status' => $userModule->status
                        ]);
                    }
                    // Se processou módulo, não processar subscription
                    return response()->json(['success' => true, 'message' => 'Module activated']);
                } elseif ($subscription) {
                    // Pagamento confirmado - ativar assinatura
                    $subscription->update([
                        'status' => 'active',
                        'next_billing_date' => isset($data['payment']['dueDate']) 
                            ? \Carbon\Carbon::parse($data['payment']['dueDate'])
                            : $subscription->next_billing_date,
                    ]);
                    
                    // Atualizar usuário (se necessário)
                    $user = $subscription->user;
                    
                    // Enviar email de confirmação
                    if ($user) {
                        try {
                            Mail::to($user->email)->send(new PaymentConfirmedMail($user, $subscription));
                            Log::info('Payment confirmation email sent', [
                                'user_id' => $user->id,
                                'email' => $user->email
                            ]);
                        } catch (\Exception $e) {
                            Log::error('Failed to send payment confirmation email', [
                                'user_id' => $user->id,
                                'error' => $e->getMessage()
                            ]);
                        }
                    }
                    
                    Log::info('Subscription activated', [
                        'subscription_id' => $subscription->id,
                        'asaas_subscription_id' => $subscription->asaas_subscription_id,
                        'user_id' => $user->id ?? null
                    ]);
                }
                break;

            case 'PAYMENT_CREATED':
            case 'PAYMENT_RECEIVED':
                // Manter como pending até ser confirmado
                if ($subscription && $subscription->status === 'pending') {
                    // Não alterar status ainda, aguardar confirmação
                }
                break;

            case 'PAYMENT_OVERDUE':
                if ($subscription) {
                    $subscription->update(['status' => 'inactive']);
                }
                break;

            case 'SUBSCRIPTION_DELETED':
                if ($subscription) {
                    $subscription->update([
                        'status' => 'cancelled',
                        'cancelled_at' => now(),
                    ]);
                }
                break;
        }

        return response()->json(['success' => true]);
    }
}
