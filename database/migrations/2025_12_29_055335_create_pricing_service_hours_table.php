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
        Schema::create('pricing_service_hours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pricing_calculation_id')->constrained()->onDelete('cascade');
            $table->string('function_name');
            $table->decimal('hourly_rate', 10, 2)->default(0);
            $table->decimal('hours', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pricing_service_hours');
    }
};
