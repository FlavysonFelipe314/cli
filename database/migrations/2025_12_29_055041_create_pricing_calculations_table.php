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
        Schema::create('pricing_calculations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('operation_type', ['commerce', 'service'])->default('commerce');
            $table->string('name');
            $table->decimal('purchase_price', 10, 2)->default(0);
            $table->decimal('freight', 10, 2)->default(0);
            $table->decimal('input_taxes', 10, 2)->default(0);
            $table->decimal('packaging', 10, 2)->default(0);
            $table->decimal('other_direct_costs', 10, 2)->default(0);
            $table->decimal('transport', 10, 2)->default(0);
            $table->decimal('accommodation', 10, 2)->default(0);
            $table->decimal('specific_materials', 10, 2)->default(0);
            $table->decimal('other_inputs', 10, 2)->default(0);
            $table->decimal('tax_rate', 5, 2)->default(15);
            $table->decimal('desired_margin', 5, 2)->default(30);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pricing_calculations');
    }
};
