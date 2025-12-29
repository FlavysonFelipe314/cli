<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('module_id')->constrained()->onDelete('cascade');
            $table->foreignId('subscription_id')->nullable()->constrained()->onDelete('cascade'); // Assinatura que incluiu o módulo
            $table->decimal('price_paid', 10, 2)->default(0); // Preço pago pelo módulo
            $table->string('asaas_payment_id')->nullable(); // ID do pagamento no Asaas (se aplicável)
            $table->enum('status', ['active', 'inactive', 'expired'])->default('active');
            $table->timestamp('purchased_at')->nullable();
            $table->timestamp('expires_at')->nullable(); // Se o módulo expira (opcional)
            $table->timestamps();
            
            $table->unique(['user_id', 'module_id']); // Um usuário só pode ter um módulo uma vez
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_modules');
    }
};
