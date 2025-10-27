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
        Schema::create('opciones_respuesta', function (Blueprint $table) {
            $table->id();
            // Vinculada al TIPO de pregunta, no a la pregunta individual
            $table->foreignId('pregunta_tipo_id')->constrained('pregunta_tipos')->cascadeOnDelete();
            $table->string('texto_opcion');
            $table->integer('valor_numerico')->default(0);
            $table->integer('orden')->default(0);
            // No timestamps needed
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opciones_respuesta');
    }
};
