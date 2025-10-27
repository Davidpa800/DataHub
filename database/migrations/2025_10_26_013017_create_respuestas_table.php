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
        Schema::create('respuestas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('encuesta_id')->constrained('encuestas_asignadas')->cascadeOnDelete();
            $table->foreignId('pregunta_id')->constrained('preguntas')->cascadeOnDelete();
            // Puede ser null si es una respuesta de texto abierto
            $table->foreignId('opcion_id')->nullable()->constrained('opciones_respuesta')->nullOnDelete();
            $table->text('valor_texto')->nullable()->comment('Para preguntas de texto abierto');
            $table->timestamps();

            // Evitar que un empleado responda dos veces la misma pregunta en la misma encuesta
            $table->unique(['encuesta_id', 'pregunta_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('respuestas');
    }
};
