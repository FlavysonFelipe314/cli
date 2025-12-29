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
        Schema::create('calendar_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('event_date');
            $table->time('event_time')->nullable();
            $table->enum('event_type', ['conta_pagar', 'conta_receber', 'obrigacao_fiscal', 'tarefa', 'outro'])->default('outro');
            $table->enum('scope', ['PF', 'PJ', 'Todos'])->default('Todos');
            $table->string('related_model_type')->nullable(); // App\Models\Payable, etc
            $table->unsignedBigInteger('related_model_id')->nullable();
            $table->string('color')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calendar_events');
    }
};
