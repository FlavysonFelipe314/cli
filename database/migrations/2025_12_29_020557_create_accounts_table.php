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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('type')->default('Conta Corrente'); // Conta Corrente, Poupança, etc
            $table->string('bank')->nullable();
            $table->string('agency')->nullable();
            $table->string('account_number')->nullable();
            $table->string('holder')->nullable();
            $table->string('cpf')->nullable();
            $table->string('pix_key')->nullable();
            $table->decimal('balance', 15, 2)->default(0);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
