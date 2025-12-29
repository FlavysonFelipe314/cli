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
        Schema::create('indirect_cost_allocations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('reference_month');
            $table->integer('reference_year');
            $table->enum('allocation_mode', ['Simples', 'Avançado'])->default('Simples');
            $table->enum('allocation_base', ['percent_revenue', 'cost_per_unit', 'cost_per_hour'])->default('percent_revenue');
            $table->decimal('total_indirect_costs', 10, 2)->default(0);
            $table->decimal('total_revenue', 10, 2)->default(0);
            $table->decimal('allocation_percentage', 5, 2)->default(0);
            $table->text('settings')->nullable(); // JSON para configurações adicionais
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('indirect_cost_allocations');
    }
};
