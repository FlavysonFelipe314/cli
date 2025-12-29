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
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nome do módulo (ex: "Pró-labore", "Precificação")
            $table->string('slug')->unique(); // Slug único (ex: "prolabore", "pricing")
            $table->text('description')->nullable(); // Descrição do módulo
            $table->string('route_name')->nullable(); // Nome da rota principal (ex: "tools.prolabore.index")
            $table->string('icon')->nullable(); // Ícone SVG ou classe
            $table->decimal('price', 10, 2)->default(0); // Preço adicional para adicionar ao plano
            $table->string('category')->default('tools'); // Categoria (tools, management, finance)
            $table->boolean('active')->default(true); // Se o módulo está ativo
            $table->integer('sort_order')->default(0); // Ordem de exibição
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modules');
    }
};
