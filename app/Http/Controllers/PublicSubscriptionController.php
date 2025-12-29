<?php

namespace App\Http\Controllers;

use App\Mail\UserCredentialsMail;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use App\Services\AsaasService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PublicSubscriptionController extends Controller
{
    protected AsaasService $asaasService;

    public function __construct(AsaasService $asaasService)
    {
        $this->asaasService = $asaasService;
    }

    /**
     * Exibir planos disponíveis (página pública)
     */
    public function showPlans()
    {
        $plans = Plan::where('active', true)
            ->orderBy('sort_order')
            ->orderBy('price')
            ->get();

        $allModules = \App\Models\Module::where('active', true)
            ->orderBy('category')
            ->orderBy('sort_order')
            ->get();

        return view('public.plans', compact('plans', 'allModules'));
    }

    /**
     * Exibir formulário de cadastro para um plano específico
     */
    public function showSignup(Plan $plan)
    {
        if (!$plan->active) {
            return redirect()->route('public.plans')
                ->with('error', 'Este plano não está disponível.');
        }

        $allModules = \App\Models\Module::where('active', true)
            ->orderBy('category')
            ->orderBy('sort_order')
            ->get();

        return view('public.signup', compact('plan', 'allModules'));
    }

    /**
     * Processar cadastro e criar assinatura
     */
    public function signup(Request $request, Plan $plan)
    {
        if (!$plan->active) {
            return back()->with('error', 'Este plano não está disponível.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'cpf_cnpj' => 'required|string|max:20',
            'phone' => 'required|string|max:20',
            'billing_type' => 'required|in:CREDIT_CARD,BOLETO,PIX',
        ]);

        DB::beginTransaction();
        try {
            // Criar usuário
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'cpf_cnpj' => $validated['cpf_cnpj'],
                'phone' => $validated['phone'],
                'role' => 'user',
            ]);

            // Criar cliente no Asaas
            $customerData = $this->asaasService->createCustomer([
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'cpf_cnpj' => $user->cpf_cnpj,
                'user_id' => $user->id,
            ]);

            if (!$customerData || !isset($customerData['id'])) {
                throw new \Exception('Erro ao criar cliente no Asaas: ' . ($customerData['errors'][0]['description'] ?? 'Erro desconhecido'));
            }

            $user->update(['asaas_customer_id' => $customerData['id']]);

            // Criar assinatura no Asaas
            $returnUrl = route('public.payment.success');
            $subscriptionData = $this->asaasService->createSubscription([
                'customer_id' => $user->asaas_customer_id,
                'billing_type' => $validated['billing_type'],
                'value' => $plan->price,
                'next_due_date' => now()->addMonth()->format('Y-m-d'),
                'cycle' => $plan->billing_cycle === 'yearly' ? 'YEARLY' : 'MONTHLY',
                'description' => "Assinatura {$plan->name}",
                'subscription_id' => null,
                'return_url' => $returnUrl,
            ]);

            if (!$subscriptionData || !isset($subscriptionData['id'])) {
                throw new \Exception('Erro ao criar assinatura no Asaas: ' . ($subscriptionData['errors'][0]['description'] ?? 'Erro desconhecido'));
            }

            // Criar assinatura local
            $subscription = Subscription::create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'asaas_subscription_id' => $subscriptionData['id'],
                'asaas_customer_id' => $user->asaas_customer_id,
                'status' => 'pending',
                'starts_at' => now(),
                'next_billing_date' => isset($subscriptionData['nextDueDate']) 
                    ? \Carbon\Carbon::parse($subscriptionData['nextDueDate'])
                    : now()->addMonth(),
            ]);

            DB::commit();

            // Enviar email com credenciais
            try {
                Mail::to($user->email)->send(new UserCredentialsMail($user, $validated['password']));
            } catch (\Exception $e) {
                Log::error('Failed to send credentials email', ['error' => $e->getMessage()]);
            }

            // Obter link de pagamento
            // O Asaas cria automaticamente um pagamento quando cria uma assinatura
            $paymentUrl = null;
            
            // Aguardar um pouco para o Asaas processar e criar o pagamento
            sleep(1);
            
            // Buscar pagamentos da assinatura (o Asaas cria automaticamente)
            $paymentData = $this->asaasService->getSubscriptionPayments($subscriptionData['id']);
            
            if ($paymentData && isset($paymentData['invoiceUrl'])) {
                $paymentUrl = $paymentData['invoiceUrl'];
                Log::info('Usando invoiceUrl do pagamento criado automaticamente', [
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
                // Fazer login automático e redirecionar para pagamento
                Auth::login($user);
                return redirect($paymentUrl);
            }

            // Fallback: fazer login e redirecionar para página de assinaturas
            Auth::login($user);
            return redirect()->route('subscriptions.index')
                ->with('info', 'Cadastro realizado com sucesso! O link de pagamento será enviado por email.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Erro ao processar cadastro: ' . $e->getMessage());
        }
    }

    /**
     * Página de sucesso após pagamento
     */
    public function paymentSuccess()
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('info', 'Faça login para acessar sua conta.');
        }

        return view('public.payment-success');
    }
}
