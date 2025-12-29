<?php

namespace App\Http\Controllers;

use App\Models\UserModule;
use App\Models\Subscription;
use App\Services\AsaasService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentStatusController extends Controller
{
    protected AsaasService $asaasService;

    public function __construct(AsaasService $asaasService)
    {
        $this->asaasService = $asaasService;
    }

    /**
     * Verificar status do pagamento e redirecionar
     */
    public function checkStatus(Request $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['redirect' => route('login')], 302);
        }

        $moduleId = $request->query('module');
        $paymentId = $request->query('payment_id');
        $subscriptionId = $request->query('subscription_id');

        // Verificar pagamento de módulo
        if ($moduleId) {
            // Primeiro tentar com payment_id se fornecido
            $userModule = null;
            if ($paymentId) {
                $userModule = UserModule::where('user_id', $user->id)
                    ->where('module_id', $moduleId)
                    ->where('asaas_payment_id', $paymentId)
                    ->first();
            }
            
            // Se não encontrou, buscar qualquer módulo pendente deste usuário
            if (!$userModule) {
                $userModule = UserModule::where('user_id', $user->id)
                    ->where('module_id', $moduleId)
                    ->where('status', 'inactive')
                    ->latest()
                    ->first();
                
                // Se encontrou, usar o payment_id dele
                if ($userModule && $userModule->asaas_payment_id) {
                    $paymentId = $userModule->asaas_payment_id;
                }
            }

            if ($userModule) {
                // Se já está ativo, redirecionar
                if ($userModule->status === 'active') {
                    if ($request->wantsJson() || $request->ajax()) {
                        return response()->json(['redirect' => route('dashboard.index') . '?payment=success'], 200);
                    }
                    return redirect()->route('dashboard.index')
                        ->with('success', 'Módulo ativado com sucesso!');
                }

                // Verificar status no Asaas
                try {
                    $payment = $this->asaasService->getPayment($paymentId);
                    if ($payment && isset($payment['status']) && $payment['status'] === 'CONFIRMED') {
                        // Ativar módulo se ainda não estiver ativo
                        if ($userModule->status === 'inactive') {
                            $userModule->update(['status' => 'active']);
                        }
                        if ($request->wantsJson() || $request->ajax()) {
                            return response()->json(['redirect' => route('dashboard.index') . '?payment=success'], 200);
                        }
                        return redirect()->route('dashboard.index')
                            ->with('success', 'Módulo ativado com sucesso!');
                    }
                } catch (\Exception $e) {
                    \Log::warning('Erro ao verificar pagamento de módulo', [
                        'payment_id' => $paymentId,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        }

        // Verificar pagamento de assinatura
        if ($subscriptionId) {
            $subscription = Subscription::where('user_id', $user->id)
                ->where('asaas_subscription_id', $subscriptionId)
                ->first();
        } else {
            // Se não forneceu subscription_id, buscar assinatura pendente mais recente
            $subscription = Subscription::where('user_id', $user->id)
                ->where('status', 'pending')
                ->latest()
                ->first();
            
            if ($subscription) {
                $subscriptionId = $subscription->asaas_subscription_id;
            }
        }
        
        if ($subscription) {

            if ($subscription) {
                // Se já está ativo, redirecionar
                if ($subscription->status === 'active') {
                    if ($request->wantsJson() || $request->ajax()) {
                        return response()->json(['redirect' => route('dashboard.index') . '?payment=success'], 200);
                    }
                    return redirect()->route('dashboard.index')
                        ->with('success', 'Assinatura ativada com sucesso! Bem-vindo ao CLIVUS!');
                }

                // Verificar status no Asaas
                try {
                    $subscriptionData = $this->asaasService->getSubscription($subscriptionId);
                    if ($subscriptionData && isset($subscriptionData['status']) && $subscriptionData['status'] === 'ACTIVE') {
                        $subscription->update(['status' => 'active']);
                        if ($request->wantsJson() || $request->ajax()) {
                            return response()->json(['redirect' => route('dashboard.index') . '?payment=success'], 200);
                        }
                        return redirect()->route('dashboard.index')
                            ->with('success', 'Assinatura ativada com sucesso! Bem-vindo ao CLIVUS!');
                    }

                    // Verificar pagamentos da assinatura
                    $payment = $this->asaasService->getSubscriptionPayments($subscriptionId);
                    if ($payment && isset($payment['status']) && $payment['status'] === 'CONFIRMED') {
                        $subscription->update(['status' => 'active']);
                        if ($request->wantsJson() || $request->ajax()) {
                            return response()->json(['redirect' => route('dashboard.index') . '?payment=success'], 200);
                        }
                        return redirect()->route('dashboard.index')
                            ->with('success', 'Assinatura ativada com sucesso! Bem-vindo ao CLIVUS!');
                    }
                } catch (\Exception $e) {
                    \Log::warning('Erro ao verificar assinatura', [
                        'subscription_id' => $subscriptionId,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        }

        // Se não encontrou nada e é requisição AJAX, retornar que ainda está aguardando
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['status' => 'pending'], 200);
        }

        // Se não encontrou nada, redirecionar para dashboard
        return redirect()->route('dashboard.index')
            ->with('info', 'Aguardando confirmação do pagamento. Você será notificado quando for confirmado.');
    }

    /**
     * Página de aguardando pagamento (com polling)
     */
    public function waiting(Request $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        // Obter dados da sessão ou da URL
        $pendingPayment = session()->get('pending_payment');
        
        $moduleId = $request->query('module') ?? $pendingPayment['module_id'] ?? null;
        $paymentId = $request->query('payment_id') ?? $pendingPayment['payment_id'] ?? null;
        $subscriptionId = $request->query('subscription_id') ?? $pendingPayment['subscription_id'] ?? null;
        
        // Se não temos nenhum ID, tentar buscar automaticamente
        if (!$moduleId && !$subscriptionId) {
            // Buscar módulo pendente mais recente
            $pendingModule = \App\Models\UserModule::where('user_id', $user->id)
                ->where('status', 'inactive')
                ->whereNotNull('asaas_payment_id')
                ->latest()
                ->first();
            
            if ($pendingModule) {
                $moduleId = $pendingModule->module_id;
                $paymentId = $pendingModule->asaas_payment_id;
            } else {
                // Buscar assinatura pendente mais recente
                $pendingSubscription = \App\Models\Subscription::where('user_id', $user->id)
                    ->where('status', 'pending')
                    ->whereNotNull('asaas_subscription_id')
                    ->latest()
                    ->first();
                
                if ($pendingSubscription) {
                    $subscriptionId = $pendingSubscription->asaas_subscription_id;
                }
            }
        }
        
        // Limpar sessão após usar
        if ($pendingPayment) {
            session()->forget('pending_payment');
        }
        
        $redirectUrl = route('payment.status.check', [
            'module' => $moduleId,
            'payment_id' => $paymentId,
            'subscription_id' => $subscriptionId,
        ]);

        return view('payment.waiting', compact('redirectUrl', 'moduleId', 'paymentId', 'subscriptionId'));
    }
}

