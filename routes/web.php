<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InicioController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Aquí es donde puedes registrar rutas web para tu aplicación. Estas
| rutas son cargadas por RouteServiceProvider y todas ellas serán
| asignadas al grupo de middleware "web".
|
*/

// Rutas Públicas (Cualquiera puede verlas)
Route::get('/', [InicioController::class, 'index'])->name('inicio');
Route::get('/login', [InicioController::class, 'showLogin'])->name('login');
Route::post('/enviar-correo', [InicioController::class, 'enviarCorreo'])->name('enviar.correo');

// Rutas de Vistas Simples (si no necesitan lógica)
Route::view('/servicios', 'servicios')->name('servicios');
Route::view('/publicaciones', 'publicaciones')->name('publicaciones');


/*
|--------------------------------------------------------------------------
| Lógica de Autenticación
|--------------------------------------------------------------------------
*/

// Ruta que maneja el envío del formulario de login
Route::post('/login', [InicioController::class, 'handleLogin'])->name('login.submit');

// Ruta para cerrar sesión
Route::post('/logout', [InicioController::class, 'logout'])->name('logout');


/*
|--------------------------------------------------------------------------
| Rutas Protegidas
|--------------------------------------------------------------------------
|
| El middleware 'auth' asegura que solo usuarios autenticados
| puedan acceder a estas rutas.
|
*/
Route::middleware(['auth'])->group(function () {
    // Dashboard (página principal después de iniciar sesión)
    Route::get('/dashboard', [InicioController::class, 'showDashboard'])->name('dashboard');

    // Aquí puedes agregar más rutas protegidas, ej:
    // Route::get('/perfil', [PerfilController::class, 'show'])->name('perfil');
    // Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
});

