<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InicioController;
use App\Http\Controllers\Nom035Controller; // <-- Añadido el use del controlador NOM-035

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- Rutas Públicas ---
Route::get('/', [InicioController::class, 'showInicio'])->name('inicio');
Route::get('/servicios', [InicioController::class, 'showServicios'])->name('servicios');
Route::get('/publicaciones', [InicioController::class, 'showPublicaciones'])->name('publicaciones');
Route::post('/enviar-correo', [InicioController::class, 'handleContactForm'])->name('enviar.correo');

// --- Rutas de Autenticación ---
Route::middleware('guest')->group(function () {
    Route::get('/login', [InicioController::class, 'showLogin'])->name('login');
    Route::post('/login', [InicioController::class, 'handleLogin'])->name('login.handle');
});
Route::post('/logout', [InicioController::class, 'handleLogout'])->name('logout')->middleware('auth');

// --- Rutas del Dashboard y Administración (Protegidas por 'auth') ---
Route::middleware(['auth'])->prefix('dashboard')->group(function () {

    // Dashboard Principal
    Route::get('/', [InicioController::class, 'showDashboardIndex'])->name('dashboard');

    // Perfil y Configuraciones
    Route::get('/profile', [InicioController::class, 'showProfile'])->name('profile.show');
    Route::put('/profile/update', [InicioController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/update-password', [InicioController::class, 'updatePassword'])->name('password.update');
    Route::put('/settings/general', [InicioController::class, 'updateGeneralSettings'])->name('settings.general.update')->middleware('can:gestionar parametros generales');
    Route::put('/settings/email', [InicioController::class, 'updateEmailSettings'])->name('settings.email.update')->middleware('can:gestionar parametros correo');

    // Gestión de Permisos
    Route::get('/permissions', [InicioController::class, 'showPermissions'])->name('admin.permissions.index')->middleware('can:gestionar permisos');
    Route::post('/permissions', [InicioController::class, 'storePermission'])->name('admin.permissions.store')->middleware('can:gestionar permisos');

    // Gestión de Usuarios
    Route::get('/users', [InicioController::class, 'showUsers'])->name('admin.users.index')->middleware('can:gestionar usuarios');

    // --- Cargar Rutas del Módulo NOM-035 ---
    // Aplica prefijo de URL ('dashboard/nom035'), prefijo de nombre ('nom035.')
    // y middleware ('auth', 'can:gestionar nom035') al archivo incluido
    Route::prefix('nom035')
        ->name('nom035.')
        ->middleware('can:gestionar nom035')
        ->group(base_path('routes/nom035.php'));

});

// --- Cargar Rutas del Módulo de Encuestas (Públicas) ---
// La ruta 'encuesta.show' está definida aquí
Route::group([], base_path('routes/encuesta.php'));
