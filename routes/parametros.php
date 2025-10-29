<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ParametrosController;

/*
|--------------------------------------------------------------------------
| Parámetros Routes
|--------------------------------------------------------------------------
*/

// Muestra la página principal de pestañas
Route::get('/', [ParametrosController::class, 'index'])->name('index');

// Rutas POST para guardar cada pestaña
Route::post('/sistema', [ParametrosController::class, 'storeSistema'])->name('store.sistema');
Route::post('/app', [ParametrosController::class, 'storeApp'])->name('store.app');
Route::post('/web-nom035', [ParametrosController::class, 'storeWebNom035'])->name('store.web_nom035');
Route::post('/empresa', [ParametrosController::class, 'storeEmpresa'])->name('store.empresa');
Route::post('/contratos', [ParametrosController::class, 'storeContratos'])->name('store.contratos');

// --- RUTA AÑADIDA PARA EL MODAL ---
Route::post('/nuevo', [ParametrosController::class, 'storeNuevoParametro'])->name('store.nuevo');

