@extends('dashboard')

{{-- Título de la Sección --}}
@section('title', 'Parámetros del Sistema')

{{-- Contenido de la Página --}}
@section('content')
    <div class="bg-white p-6 shadow sm:rounded-lg dark:bg-gray-800 max-w-4xl mx-auto">

        <h2 class="text-xl font-semibold text-gray-900 dark:text-white" style="font-family: 'Museo 500', sans-serif;">
            Configuración General
        </h2>
        <p class="mt-1 mb-6 text-sm text-gray-600 dark:text-gray-400">
            Ajusta los parámetros generales de la aplicación.
        </p>

        <!-- Formulario de Configuración (Placeholder) -->
        <form action="#" method="POST" class="space-y-6">
            @csrf

            <!-- Nombre del Sitio -->
            <div>
                <label for="site_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Nombre del Sitio
                </label>
                <input id="site_name" name="site_name" type="text"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                       value="OSH Consulting" disabled>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Este valor se gestiona desde el archivo .env (APP_NAME)</p>
            </div>

            <!-- Email de Contacto Principal -->
            <div>
                <label for="contact_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Email de Contacto Principal
                </label>
                <input id="contact_email" name="contact_email" type="email"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                       value="contacto@oshconsulting.com.mx" placeholder="contacto@ejemplo.com">
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">El email que se mostrará como contacto principal en el sitio.</p>
            </div>

            <!-- Modo Mantenimiento -->
            <div>
                <label for="maintenance_mode" class="flex items-center">
                    <input id="maintenance_mode" name="maintenance_mode" type="checkbox"
                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600">
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Activar Modo Mantenimiento</span>
                </label>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Esto pondrá el sitio público fuera de línea (los admins pueden seguir accediendo).</p>
            </div>


            <div class="flex items-center gap-4 pt-4">
                <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Guardar Configuración
                </button>
            </div>
        </form>

    </div>
@endsection

