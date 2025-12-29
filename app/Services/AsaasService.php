<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AsaasService
{
    private string $apiKey;
    private string $baseUrl;
    private bool $sandbox;

    public function __construct()
    {
        $this->apiKey = config('services.asaas.api_key', env('ASAAS_API_KEY'));
        $this->sandbox = config('services.asaas.sandbox', env('ASAAS_SANDBOX', true));
        $this->baseUrl = $this->sandbox 
            ? 'https://sandbox.asaas.com/api/v3'
            : 'https://www.asaas.com/api/v3';
    }

    /**
     * Criar cliente no Asaas
     */
    public function createCustomer(array $data): ?array
    {
        try {
            $response = Http::withHeaders([
                'access_token' => $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post("{$this->baseUrl}/customers", [
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'cpfCnpj' => $data['cpf_cnpj'] ?? null,
                'externalReference' => $data['user_id'] ?? null,
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Asaas API Error - Create Customer', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Asaas Service Exception - Create Customer', [
                'message' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Criar assinatura no Asaas
     */
    public function createSubscription(array $data): ?array
    {
        try {
            $payload = [
                'customer' => $data['customer_id'],
                'billingType' => $data['billing_type'] ?? 'CREDIT_CARD', // CREDIT_CARD, BOLETO, PIX, etc
                'value' => $data['value'],
                'nextDueDate' => $data['next_due_date'],
                'cycle' => $data['cycle'] ?? 'MONTHLY', // MONTHLY, YEARLY
                'description' => $data['description'] ?? null,
                'externalReference' => $data['subscription_id'] ?? null,
            ];

            // Adicionar URL de retorno se fornecida
            if (isset($data['return_url'])) {
                $payload['returnUrl'] = $data['return_url'];
            }

            $response = Http::withHeaders([
                'access_token' => $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post("{$this->baseUrl}/subscriptions", $payload);

            if ($response->successful()) {
                $result = $response->json();
                // NÃO adicionar checkoutUrl para assinaturas - precisa criar pagamento primeiro
                // O checkoutUrl só funciona para pagamentos individuais, não para assinaturas
                return $result;
            }

            Log::error('Asaas API Error - Create Subscription', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Asaas Service Exception - Create Subscription', [
                'message' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Obter assinatura do Asaas
     */
    public function getSubscription(string $subscriptionId): ?array
    {
        try {
            $response = Http::withHeaders([
                'access_token' => $this->apiKey,
            ])->get("{$this->baseUrl}/subscriptions/{$subscriptionId}");

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Asaas Service Exception - Get Subscription', [
                'message' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Obter pagamentos de uma assinatura
     */
    public function getSubscriptionPayments(string $subscriptionId): ?array
    {
        try {
            $response = Http::withHeaders([
                'access_token' => $this->apiKey,
            ])->get("{$this->baseUrl}/subscriptions/{$subscriptionId}/payments");

            if ($response->successful()) {
                $result = $response->json();
                // Retornar o primeiro pagamento pendente ou o mais recente
                if (isset($result['data']) && is_array($result['data']) && count($result['data']) > 0) {
                    // Buscar pagamento pendente primeiro
                    foreach ($result['data'] as $payment) {
                        if (isset($payment['status']) && $payment['status'] === 'PENDING') {
                            return $payment;
                        }
                    }
                    // Se não encontrou pendente, retornar o primeiro
                    return $result['data'][0];
                }
                return null;
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Asaas Service Exception - Get Subscription Payments', [
                'message' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Cancelar assinatura no Asaas
     */
    public function cancelSubscription(string $subscriptionId): bool
    {
        try {
            $response = Http::withHeaders([
                'access_token' => $this->apiKey,
            ])->delete("{$this->baseUrl}/subscriptions/{$subscriptionId}");

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Asaas Service Exception - Cancel Subscription', [
                'message' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Atualizar assinatura no Asaas
     */
    public function updateSubscription(string $subscriptionId, array $data): ?array
    {
        try {
            $response = Http::withHeaders([
                'access_token' => $this->apiKey,
                'Content-Type' => 'application/json',
            ])->put("{$this->baseUrl}/subscriptions/{$subscriptionId}", $data);

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Asaas Service Exception - Update Subscription', [
                'message' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Criar pagamento para assinatura
     */
    public function createPayment(array $data): ?array
    {
        try {
            $payload = [
                'customer' => $data['customer_id'],
                'billingType' => $data['billing_type'] ?? 'CREDIT_CARD',
                'value' => $data['value'],
                'dueDate' => $data['due_date'] ?? now()->format('Y-m-d'),
                'description' => $data['description'] ?? null,
                'subscription' => $data['subscription_id'] ?? null,
            ];

            // Adicionar callback com successUrl e autoRedirect se return_url fornecida
            // IMPORTANTE: O Asaas só aceita URLs de domínios válidos configurados na conta
            // Não usar callback para localhost ou URLs não configuradas
            if (isset($data['return_url']) && !str_contains($data['return_url'], 'localhost') && !str_contains($data['return_url'], '127.0.0.1')) {
                $payload['callback'] = [
                    'successUrl' => $data['return_url'],
                    'autoRedirect' => true,
                ];
            }

            $response = Http::withHeaders([
                'access_token' => $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post("{$this->baseUrl}/payments", $payload);

            if ($response->successful()) {
                $result = $response->json();
                // Adicionar checkoutUrl se não estiver presente
                if (!isset($result['checkoutUrl']) && isset($result['id'])) {
                    $baseUrl = $this->sandbox 
                        ? 'https://sandbox.asaas.com'
                        : 'https://www.asaas.com';
                    $result['checkoutUrl'] = "{$baseUrl}/c/{$result['id']}";
                }
                return $result;
            }

            Log::error('Asaas API Error - Create Payment', [
                'status' => $response->status(),
                'body' => $response->body(),
                'payload' => $payload,
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Asaas Service Exception - Create Payment', [
                'message' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Obter pagamento do Asaas
     */
    public function getPayment(string $paymentId): ?array
    {
        try {
            $response = Http::withHeaders([
                'access_token' => $this->apiKey,
            ])->get("{$this->baseUrl}/payments/{$paymentId}");

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Asaas API Error - Get Payment', [
                'status' => $response->status(),
                'body' => $response->body(),
                'payment_id' => $paymentId,
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Asaas Service Exception - Get Payment', [
                'message' => $e->getMessage(),
                'payment_id' => $paymentId,
            ]);
            return null;
        }
    }

    /**
     * Obter link de checkout do Asaas
     */
    public function getCheckoutUrl(string $subscriptionId): ?string
    {
        try {
            $subscription = $this->getSubscription($subscriptionId);
            if (!$subscription) {
                return null;
            }

            // O Asaas retorna o link de checkout na resposta da assinatura
            // ou você pode construir manualmente
            $baseUrl = $this->sandbox 
                ? 'https://sandbox.asaas.com'
                : 'https://www.asaas.com';
            
            return "{$baseUrl}/c/{$subscriptionId}";
        } catch (\Exception $e) {
            Log::error('Asaas Service Exception - Get Checkout URL', [
                'message' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Webhook do Asaas - processar eventos
     */
    public function processWebhook(array $data): void
    {
        $event = $data['event'] ?? null;
        $subscriptionId = $data['subscription'] ?? null;

        if (!$event || !$subscriptionId) {
            return;
        }

        // Processar eventos: PAYMENT_CREATED, PAYMENT_RECEIVED, PAYMENT_OVERDUE, etc.
        Log::info('Asaas Webhook Received', [
            'event' => $event,
            'subscription_id' => $subscriptionId,
            'data' => $data,
        ]);
    }
}

