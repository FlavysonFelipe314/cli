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
        Schema::create('team_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Dono da equipe
            $table->foreignId('member_user_id')->nullable()->constrained('users')->onDelete('cascade'); // Usuário convidado (se já tiver conta)
            $table->string('name');
            $table->string('email');
            $table->string('position')->nullable();
            $table->enum('employment_type', ['CLT', 'PJ', 'Freelancer', 'Estagiário'])->default('CLT');
            $table->date('entry_date');
            $table->decimal('estimated_monthly_cost', 10, 2)->default(0);
            $table->enum('status', ['active', 'inactive', 'pending'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_members');
    }
};
