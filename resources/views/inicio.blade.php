
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * Este es el controlador para las páginas principales (Home, Contacto, etc.)
 * Si este archivo no existe, puedes crearlo con el comando:
 * php artisan make:controller InicioController
 */
class InicioController extends Controller
{
    /**
     * Muestra la página de inicio (el "index" de la aplicación).
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // --- Aquí va tu lógica ---
        // Por ejemplo, puedes cargar datos de la base de datos.
        // De momento, solo definiremos unas variables para enviar a la vista.
        $titulo = "Bienvenido a Mi Aplicación";
        $descripcion = "Esta es la nueva página de inicio, ¡modificada como queríamos!";

        // 3. Retornamos la vista (el archivo .blade.php)
        // Buscamos el archivo "inicio.blade.php" en la carpeta "resources/views/"
        // y le pasamos las variables $titulo y $descripcion.
        return view('inicio', [
            'titulo_de_pagina' => $titulo,
            'texto_principal' => $descripcion
        ]);
    }

    /*
    // Si hubiéramos creado la ruta /contacto, este sería su método:
    public function contacto()
    {
        return view('contacto'); // Cargaría resources/views/contacto.blade.php
    }
    */
}
