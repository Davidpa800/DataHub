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
        Schema::create('secciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cuestionario_id')->constrained('cuestionarios')->cascadeOnDelete();
            $table->string('titulo')->comment('Ej: Dominio: Carga de Trabajo');
            $table->text('descripcion')->nullable();
            $table->integer('orden')->default(0);
            // No timestamps needed if sections are static per questionnaire
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('secciones');
    }
};
