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
        Schema::create('contrato_cuestionario', function (Blueprint $table) {
            $table->foreignId('contrato_id')->constrained('contratos')->cascadeOnDelete();
            $table->foreignId('cuestionario_id')->constrained('cuestionarios')->cascadeOnDelete();
            $table->timestamps(); // Useful to know when a questionnaire was added to a contract

            $table->primary(['contrato_id', 'cuestionario_id']); // Composite primary key
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contrato_cuestionario');
    }
};
