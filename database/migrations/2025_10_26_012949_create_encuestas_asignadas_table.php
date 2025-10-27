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
        Schema::create('encuestas_asignadas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empleado_id')->constrained('empleados')->cascadeOnDelete();
            $table->foreignId('cuestionario_id')->constrained('cuestionarios'); // No cascade, questionnaire is master data
            $table->foreignId('contrato_id')->constrained('contratos')->cascadeOnDelete();
            $table->string('token_unico')->unique();
            $table->enum('estatus', ['pendiente', 'en_progreso', 'completado'])->default('pendiente');
            $table->integer('progreso_actual')->default(0);
            $table->timestamp('fecha_completado')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('encuestas_asignadas');
    }
};
