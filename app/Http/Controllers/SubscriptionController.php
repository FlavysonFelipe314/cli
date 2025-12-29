<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Subscription;
use App\Services\AsaasService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SubscriptionController extends Controller
{
    protected AsaasService $asaasService;

    public function __construct(AsaasService $asaasService)
    {
        $this->asaasService = $asaasService;
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Recarregar assinaturas do banco para garantir dados atualizados
        $user->load(['subscriptions' => function($query) {
            $query->where('status', 'active')
                  ->where(function($q) {
                      $q->whereNull('ends_at')
                        ->orWhere('ends_at', '>=', now());
                  })
                  ->latest();
        }, 'subscriptions.plan']);
        
        $plans = Plan::where('active', true)->orderBy('sort_order')->get();
        $activeSubscription = $user->subscriptions->first();
        $subscriptions = $user->subscriptions()->with('plan')->orderBy('created_at', 'desc')->get();

        // Mensagem de sucesso se voltou do pagamento
        if ($request->has('payment') && $request->payment === 'success') {
            session()->flash('success', 'Pagamento realizado com sucesso! Sua assinatura está ativa.');
        }

        return view('subscriptions.index', compact('plans', 'activeSubscription', 'subscriptions'));
    }

    public function subscribe(Request $request, Plan $plan)
    {
        $user = Auth::user();

        // Verificar se já tem assinatura ativa
        if ($user->hasActiveSubscription()) {
            return back()->with('error', 'Você já possui uma assinatura ativa.');
        }

        $validated = $request->validate([
            'billing_type' => 'required|in:CREDIT_CARD,BOLETO,PIX',
            'cpf_cnpj' => 'required|string|max:20',
            'phone' => 'required|string|max:20',
        ]);

        DB::beginTransaction();
        try {
            // Atualizar dados do usuário
            $user->update([
                'cpf_cnpj' => $validated['cpf_cnpj'],
                'phone' => $validated['phone'],
            ]);

            // Criar ou obter cliente no Asaas
            if (!$user->asaas_customer_id) {
                $customerData = $this->asaasService->createCustomer([
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'cpf_cnpj' => $user->cpf_cnpj,
                    'user_id' => $user->id,
                ]);

                if (!$customerData) {
                    throw new \Exception('Erro ao criar cliente no Asaas');
                }

                $user->update(['asaas_customer_id' => $customerData['id']]);
            }

            // Criar assinatura no Asaas
            $returnUrl = route('subscriptions.index') . '?payment=success';
            $subscriptionData = $this->asaasService->createSubscription([
                'customer_id' => $user->asaas_customer_id,
                'billing_type' => $validated['billing_type'],
                'value' => $plan->price,
                'next_due_date' => now()->addMonth()->format('Y-m-d'),
                'cycle' => $plan->billing_cycle === 'yearly' ? 'YEARLY' : 'MONTHLY',
                'description' => "Assinatura {$plan->name}",
                'subscription_id' => null, // Será atualizado após criar
                'return_url' => $returnUrl,
            ]);

            if (!$subscriptionData) {
                throw new \Exception('Erro ao criar assinatura no Asaas');
            }

            // Criar assinatura local
            $subscription = Subscription::create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'asaas_subscription_id' => $subscriptionData['id'],
                'asaas_customer_id' => $user->asaas_customer_id,
                'status' => 'pending',
                'starts_at' => now(),
                'next_billing_date' => $subscriptionData['nextDueDate'] ?? now()->addMonth(),
            ]);

            DB::commit();

            // Obter link de pagamento
            // O Asaas cria automaticamente um pagamento quando cria uma assinatura
            $paymentUrl = null;
            
            // Aguardar um pouco para o Asaas processar e criar o pagamento
            sleep(1);
            
            // Buscar pagamentos da assinatura (o Asaas cria automaticamente)
            $paymentData = $this->asaasService->getSubscriptionPayments($subscriptionData['id']);
            
            if ($paymentData && isset($paymentData['invoiceUrl'])) {
                $paymentUrl = $paymentData['invoiceUrl'];
                \Log::info('Usando invoiceUrl do pagamento criado automaticamente', [
                    'payment_id' => $paymentData['id'] ?? null,
                    'invoice_url' => $paymentUrl,
                ]);
            } elseif ($paymentData && isset($paymentData['invoiceNumber'])) {
                $baseUrl = config('services.asaas.sandbox', true) 
                    ? 'https://sandbox.asaas.com'
                    : 'https://www.asaas.com';
                $paymentUrl = "{$baseUrl}/i/{$paymentData['invoiceNumber']}";
            } elseif (isset($subscriptionData['invoiceUrl'])) {
                // Fallback: usar invoiceUrl da assinatura se disponível
                $paymentUrl = $subscriptionData['invoiceUrl'];
            } elseif (isset($subscriptionData['invoiceNumber'])) {
                $baseUrl = config('services.asaas.sandbox', true) 
                    ? 'https://sandbox.asaas.com'
                    : 'https://www.asaas.com';
                $paymentUrl = "{$baseUrl}/i/{$subscriptionData['invoiceNumber']}";
            }
            
            // Se ainda não temos URL, aguardar mais um pouco e tentar novamente
            if (!$paymentUrl) {
                sleep(2);
                $paymentData = $this->asaasService->getSubscriptionPayments($subscriptionData['id']);
                if ($paymentData && isset($paymentData['invoiceUrl'])) {
                    $paymentUrl = $paymentData['invoiceUrl'];
                }
            }

            if ($paymentUrl) {
                // Redirecionar para o pagamento do Asaas
                return redirect($paymentUrl);
            }

            // Fallback: retornar para página de assinaturas com mensagem
            return redirect()->route('subscriptions.index')
                ->with('info', 'Assinatura criada com sucesso! O link de pagamento será enviado por email.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erro ao processar assinatura: ' . $e->getMessage());
        }
    }

    public function paymentCallback(Request $request)
    {
        // Callback quando o Asaas redireciona após pagamento
        $paymentId = $request->input('payment');
        $status = $request->input('status');
        $subscriptionId = $request->input('subscription');
        
        $user = Auth::user();
        
        if ($user) {
            // Verificar se há assinatura pendente
            $subscription = $user->subscriptions()
                ->where('status', 'pending')
                ->orWhere('status', 'active')
                ->latest()
                ->first();
            
            if ($subscription) {
                // Verificar status no Asaas
                if ($subscriptionId) {
                    $subscriptionData = $this->asaasService->getSubscription($subscriptionId);
                    if ($subscriptionData && isset($subscriptionData['status'])) {
                        if ($subscriptionData['status'] === 'ACTIVE') {
                            $subscription->update(['status' => 'active']);
                            return redirect()->route('dashboard.index')
                                ->with('success', 'Pagamento confirmado! Sua assinatura está ativa.');
                        }
                    }
                }
            }
            
            return redirect()->route('dashboard.index')
                ->with('info', 'Processando seu pagamento. Você receberá um email quando for confirmado.');
        }
        
        return redirect()->route('login')
            ->with('info', 'Faça login para verificar o status do seu pagamento.');
    }

    public function cancel(Subscription $subscription)
    {
        $user = Auth::user();

        if ($subscription->user_id !== $user->id) {
            abort(403);
        }

        if ($this->asaasService->cancelSubscription($subscription->asaas_subscription_id)) {
            $subscription->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
            ]);

            return back()->with('success', 'Assinatura cancelada com sucesso!');
        }

        return back()->with('error', 'Erro ao cancelar assinatura.');
    }
}
