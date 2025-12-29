<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use App\Services\AsaasService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SyncAsaasSubscriptions extends Command
{
    protected $signature = 'asaas:sync-subscriptions {--user-id= : ID do usuário específico}';
    protected $description = 'Sincronizar status das assinaturas com o Asaas';

    protected AsaasService $asaasService;

    public function __construct(AsaasService $asaasService)
    {
        parent::__construct();
        $this->asaasService = $asaasService;
    }

    public function handle()
    {
        $userId = $this->option('user-id');
        
        $query = Subscription::whereNotNull('asaas_subscription_id');
        
        if ($userId) {
            $query->where('user_id', $userId);
        }
        
        $subscriptions = $query->get();
        
        $this->info("Sincronizando {$subscriptions->count()} assinaturas...");
        
        $updated = 0;
        foreach ($subscriptions as $subscription) {
            $asaasData = $this->asaasService->getSubscription($subscription->asaas_subscription_id);
            
            if ($asaasData) {
                $oldStatus = $subscription->status;
                $newStatus = strtolower($asaasData['status'] ?? 'pending');
                
                // Mapear status do Asaas para nosso sistema
                $statusMap = [
                    'active' => 'active',
                    'pending' => 'pending',
                    'inactive' => 'inactive',
                    'deleted' => 'cancelled',
                ];
                
                $mappedStatus = $statusMap[$newStatus] ?? 'pending';
                
                $subscription->update([
                    'status' => $mappedStatus,
                    'next_billing_date' => isset($asaasData['nextDueDate']) 
                        ? \Carbon\Carbon::parse($asaasData['nextDueDate'])
                        : $subscription->next_billing_date,
                ]);
                
                if ($oldStatus !== $mappedStatus) {
                    $this->info("Assinatura #{$subscription->id} atualizada: {$oldStatus} -> {$mappedStatus}");
                    $updated++;
                }
            } else {
                $this->warn("Não foi possível obter dados da assinatura #{$subscription->id} do Asaas");
            }
        }
        
        $this->info("Sincronização concluída! {$updated} assinaturas atualizadas.");
        
        return 0;
    }
}
