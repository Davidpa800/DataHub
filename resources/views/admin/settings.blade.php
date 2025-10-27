@extends('dashboard')

{{-- Título de la Sección --}}
@section('title', 'Parámetros del Sistema')

{{-- Contenido de la Página --}}
@section('content')

    <div class="max-w-7xl mx-auto space-y-8">

        <div class="mb-4 border-b border-gray-200 dark:border-gray-700">
            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="myTab" data-tabs-toggle="#myTabContent" role="tablist">

                {{-- Pestaña 1: Parámetros Generales --}}
                @can('gestionar parametros generales')
                    <li class="mr-2" role="presentation">
                        <button class="inline-block p-4 border-b-2 rounded-t-lg" id="general-tab" data-tabs-target="#general" type="button" role="tab" aria-controls="general" aria-selected="false">
                            Parámetros Generales
                        </button>
                    </li>
                @endcan

                {{-- Pestaña 2: Parámetros de Correo --}}
                @can('gestionar parametros correo')
                    <li class="mr-2" role="presentation">
                        <button class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300" id="email-tab" data-tabs-target="#email" type="button" role="tab" aria-controls="email" aria-selected="false">
                            Parámetros de Correo
                        </button>
                    </li>
                @endcan

                {{-- (Puedes añadir más pestañas aquí con sus @can) --}}
            </ul>
        </div>

        {{-- Contenido de las Pestañas --}}
        <div id="myTabContent">

            {{-- Contenido Pestaña 1: Parámetros Generales --}}
            @can('gestionar parametros generales')
                <div class="hidden p-4 rounded-lg bg-white shadow sm:rounded-lg dark:bg-gray-800" id="general" role="tabpanel" aria-labelledby="general-tab">

                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white" style="font-family: 'Museo 500', sans-serif;">
                        Módulo de Parámetros Generales
                    </h2>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Aquí puedes configurar los ajustes generales del sitio, como el nombre de la aplicación, el modo de mantenimiento, etc.
                    </p>

                    <form method="POST" action="{{ route('dashboard.settings.general.update') }}" class="mt-6 space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Nombre del Sitio -->
                        <div>
                            <label for="app_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Nombre del Sitio
                            </label>
                            <input id="app_name" name="app_name" type="text"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                   value="{{ config('app.name') }}" required>
                        </div>

                        <!-- Modo Mantenimiento -->
                        <div>
                            <label for="maintenance_mode" class="flex items-center">
                                <input id="maintenance_mode" name="maintenance_mode" type="checkbox"
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600">
                                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Activar Modo Mantenimiento</span>
                            </label>
                        </div>

                        <div class="flex items-center gap-4">
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Guardar Cambios
                            </button>
                        </div>
                    </form>

                </div>
            @endcan

            {{-- Contenido Pestaña 2: Parámetros de Correo --}}
            @can('gestionar parametros correo')
                <div class="hidden p-4 rounded-lg bg-white shadow sm:rounded-lg dark:bg-gray-800" id="email" role="tabpanel" aria-labelledby="email-tab">

                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white" style="font-family: 'Museo 500', sans-serif;">
                        Módulo de Parámetros de Correo (Mail)
                    </h2>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Configura el driver de correo, el host, el puerto y las credenciales para el envío de correos transaccionales.
                    </p>

                    <form method="POST" action="{{ route('dashboard.settings.email.update') }}" class="mt-6 space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Mailer -->
                        <div>
                            <label for="mail_mailer" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Mailer (Driver)
                            </label>
                            <input id="mail_mailer" name="mail_mailer" type="text"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                   value="{{ config('mail.default') }}" placeholder="smtp">
                        </div>

                        <!-- Mail Host -->
                        <div>
                            <label for="mail_host" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Host
                            </label>
                            <input id="mail_host" name="mail_host" type="text"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                   value="{{ config('mail.mailers.smtp.host') }}" placeholder="smtp.mailgun.org">
                        </div>

                        <!-- ... (Aquí irían más campos: puerto, usuario, contraseña) ... -->

                        <div class="flex items-center gap-4">
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Guardar Cambios
                            </button>
                        </div>
                    </form>

                </div>
            @endcan

        </div>
    </div>
@endsection

