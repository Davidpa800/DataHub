<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Este archivo define la estructura de tu tabla 'users'
 * en la base de datos.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // Columna 'id'
            $table->string('name'); // Columna 'name'
            $table->string('email')->unique(); // Columna 'email' (debe ser única)
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password'); // Columna 'password' (aquí se guarda el HASH)
            $table->rememberToken();
            $table->timestamps(); // Columnas 'created_at' y 'updated_at'
        });

        // (Las otras tablas son para el 'remember token' y jobs fallidos,
        // la importante es 'users')

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('failed_jobs');
    }
};
