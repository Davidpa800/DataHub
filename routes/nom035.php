<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Nom035Controller;

/*
|--------------------------------------------------------------------------
| NOM-035 Routes
|--------------------------------------------------------------------------
|
| Estas rutas son incluidas por routes/web.php y automáticamente
| tienen el prefijo /dashboard/nom035, el nombre nom035.
| y el middleware ['auth', 'can:gestionar nom035'].
|
*/

// Dashboard principal del módulo NOM-035
Route::get('/', [Nom035Controller::class, 'index'])->name('index');

// --- MONITOR DE CONTRATOS GENERAL ---
Route::get('/monitoreo', [Nom035Controller::class, 'showContratosMonitor'])->name('monitoreo.index');

// --- Monitores Detallados POR EMPRESA (Route Model Binding: {empresa}) ---
Route::prefix('monitoreo/{empresa}')->name('monitoreo.')->group(function () {
    // Monitor 1: Progreso por Empleado (Muestra la tabla de asignaciones)
    Route::get('/empleados', [Nom035Controller::class, 'showMonitorEmpleados'])->name('empleados');

    // Monitor 2: Gestión de Email y Notificaciones
    Route::get('/email', [Nom035Controller::class, 'showMonitorEmail'])->name('email');

    // Monitor 3: Reportes y Descargas (Resultados)
    Route::get('/reportes', [Nom035Controller::class, 'showMonitorReportes'])->name('reportes');
});

// --- Gestión de Empresas ---
Route::get('/empresas/create', [Nom035Controller::class, 'createEmpresa'])->name('empresas.create');
Route::post('/empresas', [Nom035Controller::class, 'storeEmpresa'])->name('empresas.store');

// --- Gestión de Contratos ---
Route::get('/contratos/create', [Nom035Controller::class, 'createContrato'])->name('contratos.create'); // Muestra lista y form
Route::post('/contratos', [Nom035Controller::class, 'storeContrato'])->name('contratos.store');

// --- Gestión de Empleados ---
Route::get('/empleados', [Nom035Controller::class, 'indexEmpleados'])->name('empleados.index'); // Muestra pág. gestión (subida + form individual)
Route::post('/empleados', [Nom035Controller::class, 'storeEmpleado'])->name('empleados.store'); // Guarda individual
Route::post('/empleados/upload', [Nom035Controller::class, 'uploadEmpleados'])->name('empleados.upload'); // Procesa CSV

// --- Gestión de Asignación de Encuestas ---
Route::get('/asignaciones/create', [Nom035Controller::class, 'showAssignForm'])->name('asignaciones.create'); // Muestra el formulario/interfaz de asignación
Route::post('/asignaciones', [Nom035Controller::class, 'storeAssignment'])->name('asignaciones.store'); // Procesa la asignación
