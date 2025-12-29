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
        Schema::create('employee_cost_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('employee_name')->nullable();
            $table->string('position')->nullable();
            $table->string('cost_center')->nullable();
            $table->decimal('gross_salary', 10, 2)->default(0);
            $table->integer('monthly_hours')->default(220);
            $table->decimal('transport_allowance', 10, 2)->default(0);
            $table->decimal('meal_allowance', 10, 2)->default(0);
            $table->decimal('health_insurance', 10, 2)->default(0);
            $table->decimal('other_benefits', 10, 2)->default(0);
            $table->decimal('inss_rate', 5, 2)->default(20);
            $table->decimal('fgts_rate', 5, 2)->default(8);
            $table->decimal('thirteenth_provision', 5, 2)->default(8.33);
            $table->decimal('vacation_provision', 5, 2)->default(11.11);
            $table->decimal('severance_provision', 5, 2)->default(4);
            $table->decimal('other_charges', 5, 2)->default(0);
            $table->decimal('equipment_tools', 10, 2)->default(0);
            $table->decimal('training', 10, 2)->default(0);
            $table->decimal('epi', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_cost_profiles');
    }
};
