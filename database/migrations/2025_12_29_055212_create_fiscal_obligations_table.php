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
        Schema::create('fiscal_obligations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->enum('periodicity', ['daily', 'weekly', 'monthly', 'quarterly', 'yearly'])->default('monthly');
            $table->enum('scope', ['PF', 'PJ'])->default('PF');
            $table->integer('due_day')->default(1);
            $table->string('responsible')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'completed', 'overdue', 'cancelled'])->default('pending');
            $table->date('last_completed_at')->nullable();
            $table->date('next_due_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fiscal_obligations');
    }
};
