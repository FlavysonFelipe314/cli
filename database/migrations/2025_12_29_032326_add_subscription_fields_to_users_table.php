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
        Schema::table('users', function (Blueprint $table) {
            $table->string('asaas_customer_id')->nullable()->after('email');
            $table->string('cpf_cnpj')->nullable()->after('asaas_customer_id');
            $table->string('phone')->nullable()->after('cpf_cnpj');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['asaas_customer_id', 'cpf_cnpj', 'phone']);
        });
    }
};
