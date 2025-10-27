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
        Schema::create('preguntas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seccion_id')->constrained('secciones')->cascadeOnDelete();
            $table->foreignId('pregunta_tipo_id')->constrained('pregunta_tipos'); // No cascade, a type might be used elsewhere
            $table->text('texto_pregunta');
            $table->integer('orden')->default(0);
            $table->boolean('es_obligatoria')->default(true);
            // No timestamps needed if questions are static per questionnaire section
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preguntas');
    }
};
