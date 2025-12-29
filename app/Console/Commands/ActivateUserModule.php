<?php

namespace App\Console\Commands;

use App\Models\UserModule;
use Illuminate\Console\Command;

class ActivateUserModule extends Command
{
    protected $signature = 'module:activate {payment_id}';
    protected $description = 'Ativa um módulo do usuário pelo ID do pagamento';

    public function handle()
    {
        $paymentId = $this->argument('payment_id');
        
        $userModule = UserModule::where('asaas_payment_id', $paymentId)->first();
        
        if (!$userModule) {
            $this->error("Módulo não encontrado para o payment_id: {$paymentId}");
            return 1;
        }
        
        if ($userModule->status === 'active') {
            $this->info("Módulo já está ativo!");
            return 0;
        }
        
        $userModule->update(['status' => 'active']);
        
        $this->info("Módulo ativado com sucesso!");
        $this->info("User ID: {$userModule->user_id}");
        $this->info("Module ID: {$userModule->module_id}");
        
        return 0;
    }
}
