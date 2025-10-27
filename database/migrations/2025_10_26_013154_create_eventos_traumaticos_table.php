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
        Schema::create('eventos_traumaticos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empleado_id')->constrained('empleados')->cascadeOnDelete();
            $table->foreignId('encuesta_id')->constrained('encuestas_asignadas')->cascadeOnDelete();
            $table->string('estatus_seguimiento')->default('Pendiente')->comment('Pendiente, En tratamiento, Cerrado');
            $table->text('notas_medicas')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eventos_traumaticos');
    }
};
