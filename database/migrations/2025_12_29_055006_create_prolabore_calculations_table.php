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
        Schema::create('prolabore_calculations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('monthly_revenue', 10, 2)->default(0);
            $table->decimal('fixed_costs', 10, 2)->default(0);
            $table->decimal('variable_costs_percentage', 5, 2)->default(0);
            $table->decimal('current_prolabore', 10, 2)->nullable();
            $table->decimal('profit_margin_percentage', 5, 2)->default(20);
            $table->decimal('reinvestment_percentage', 5, 2)->default(30);
            $table->decimal('reserve_percentage', 5, 2)->default(20);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prolabore_calculations');
    }
};
