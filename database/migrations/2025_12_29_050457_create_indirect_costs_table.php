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
        Schema::create('indirect_costs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('description');
            $table->string('category')->nullable();
            $table->enum('type', ['Fixo', 'Variável'])->default('Fixo');
            $table->decimal('monthly_value', 10, 2);
            $table->string('cost_center')->nullable();
            $table->boolean('include_in_allocation')->default(true);
            $table->integer('reference_month')->nullable();
            $table->integer('reference_year')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('indirect_costs');
    }
};
