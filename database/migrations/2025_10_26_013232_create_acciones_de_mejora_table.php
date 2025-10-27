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
        Schema::create('acciones_de_mejora', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')->constrained('planes_de_accion')->cascadeOnDelete();
            $table->foreignId('responsable_id')->constrained('users')->cascadeOnDelete()->comment('El USERS (admin) a cargo');
            $table->string('accion_descripcion');
            $table->date('fecha_vencimiento')->nullable();
            $table->string('estatus')->default('Pendiente')->comment('Pendiente, Completado');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acciones_de_mejora');
    }
};
