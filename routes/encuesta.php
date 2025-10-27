<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EncuestaEmpleadoController;

/*
|--------------------------------------------------------------------------
| Rutas de Encuesta para Empleados (Públicas con Token)
|--------------------------------------------------------------------------
|
| Estas rutas son totalmente públicas y accesibles solo mediante el TOKEN
| único asignado al empleado.
|
*/

// Ruta principal para mostrar la encuesta (GET)
Route::get('/encuesta/{token}', [EncuestaEmpleadoController::class, 'showEncuesta'])->name('encuesta.show');

// Ruta API para guardar respuestas (POST - Usada por Vue/JavaScript)
Route::post('/api/encuesta/{token}/respuesta', [EncuestaEmpleadoController::class, 'storeRespuesta'])->name('encuesta.respuesta');

// Ruta de Agradecimiento (Redirección final)
Route::get('/encuesta/{token}/gracias', [EncuestaEmpleadoController::class, 'showAgradecimiento'])->name('encuesta.agradecimiento');

// Ruta para mostrar errores (opcional)
Route::get('/encuesta/error', function() {
    return view('encuesta.error', ['message' => 'Enlace inválido o expirado.']);
})->name('encuesta.error');
