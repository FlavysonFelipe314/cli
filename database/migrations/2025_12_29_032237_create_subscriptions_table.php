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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('plan_id')->constrained()->onDelete('cascade');
            $table->string('asaas_subscription_id')->unique()->nullable(); // ID da assinatura no Asaas
            $table->string('asaas_customer_id')->nullable(); // ID do cliente no Asaas
            $table->enum('status', ['active', 'inactive', 'cancelled', 'expired', 'pending'])->default('pending');
            $table->date('starts_at');
            $table->date('ends_at')->nullable();
            $table->date('next_billing_date')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
