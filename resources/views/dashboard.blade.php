<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Admin - OSH Consulting</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/flowbite@1.5.3/dist/flowbite.js"></script>
    <!-- Alpine.js para interactividad del sidebar (añadido 'defer' para mejor carga) -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        /* Estilos para scrollbar (opcional pero mejora la estética) */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1; /* cool-gray-300 */
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8; /* cool-gray-400 */
        }
    </style>
</head>

<body class="bg-gray-100 font-sans" style="font-family: 'Museo 300', sans-serif;">

<div x-data="{ sidebarOpen: true }" class="flex h-screen bg-gray-100">

    <!-- Sidebar -->
    <aside
        class="flex flex-col w-64 h-screen px-4 py-8 overflow-y-auto bg-white border-r rtl:border-r-0 rtl:border-l dark:bg-gray-900 dark:border-gray-700 transition-all duration-300"
        :class="{'-ml-64': !sidebarOpen}">

        <!-- Logo -->
        <a href="{{ route('dashboard') }}" class="mx-auto">
            <img class="w-auto h-10" src="{{ asset('img/osh_logo.png') }}" alt="OSH Consulting Logo">
        </a>

        <div class="flex flex-col items-center mt-6 -mx-2">
            <img class="object-cover w-24 h-24 mx-2 rounded-full"
                 src="https://placehold.co/100x100/e2e8f0/334155?text=Admin" alt="avatar">
            <h4 class="mx-2 mt-2 font-medium text-gray-800 dark:text-gray-200" style="font-family: 'Museo 500', sans-serif;">{{ Auth::user()->name }}</h4>
            <p class="mx-2 mt-1 text-sm font-normal text-gray-600 dark:text-gray-400">{{ Auth::user()->email }}</p>
        </div>

        <div class="flex flex-col justify-between flex-1 mt-6">
            <nav>
                <!-- Enlaces Principales -->
                <a class="flex items-center px-4 py-2 text-gray-700 bg-gray-100 rounded-lg dark:bg-gray-800 dark:text-gray-200"
                   href="{{ route('dashboard') }}">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M19 11H5M19 11C20.1046 11 21 11.8954 21 13V19C21 20.1046 20.1046 21 19 21H5C3.89543 21 3 20.1046 3 19V13C3 11.8954 3.89543 11 5 11M19 11V9C19 7.89543 18.1046 7 17 7M5 11V9C5 7.89543 5.89543 7 7 7M7 7V5C7 3.89543 7.89543 3 9 3H15C16.1046 3 17 3.89543 17 5V7M7 7H17"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <span class="mx-4 font-medium" style="font-family: 'Museo 500', sans-serif;">Dashboard</span>
                </a>

                <a class="flex items-center px-4 py-2 mt-5 text-gray-600 transition-colors duration-300 transform rounded-lg dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 dark:hover:text-gray-200"
                   href="{{ route('dashboard.profile') }}">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M16 7C16 9.20914 14.2091 11 12 11C9.79086 11 8 9.20914 8 7C8 4.79086 9.79086 3 12 3C14.2091 3 16 4.79086 16 7Z"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        <path
                            d="M12 14C8.13401 14 5 17.134 5 21H19C19 17.134 15.866 14 12 14Z"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <span class="mx-4 font-medium" style="font-family: 'Museo 500', sans-serif;">Perfil</span>
                </a>

                <!--
                    Sección de Administración
                    Ahora, esta sección completa solo se mostrará si el usuario tiene CUALQUIERA
                    de los permisos de administración (gestionar usuarios O gestionar parámetros)
                -->
                @if(auth()->user()->can('gestionar usuarios') || auth()->user()->can('gestionar parametros'))
                    <hr class="my-4 border-gray-200 dark:border-gray-600">
                    <span class="mx-4 text-xs text-gray-500 uppercase" style="font-family: 'Museo 500', sans-serif;">Administración</span>

                    {{-- Directiva @can de Spatie: solo muestra este enlace si el usuario tiene el permiso --}}
                    @can('gestionar usuarios')
                        <a class="flex items-center px-4 py-2 mt-2 text-gray-600 transition-colors duration-300 transform rounded-lg dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 dark:hover:text-gray-200"
                           href="{{ route('dashboard.users') }}">
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 00-12 0m12 0a9.094 9.094 0 01-12 0m12 0A9.094 9.094 0 013 18.72m18 0A9.094 9.094 0 003 18.72m15 0a9.094 9.094 0 01-12 0m12 0a9.094 9.094 0 00-12 0m12 0a.75.75 0 00-.62-.516c-.32.023-.626.04-.926.046a11.17 11.17 0 01-7.01-2.025A11.17 11.17 0 013.927 16.2a.75.75 0 00-.62.516" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 12a3 3 0 100-6 3 3 0 000 6z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="mx-4 font-medium" style="font-family: 'Museo 500', sans-serif;">Usuarios y Permisos</span>
                        </a>
                    @endcan

                    {{-- Directiva @can de Spatie: solo muestra este enlace si el usuario tiene el permiso --}}
                    @can('gestionar parametros')
                        <a class="flex items-center px-4 py-2 mt-2 text-gray-600 transition-colors duration-300 transform rounded-lg dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 dark:hover:text-gray-200"
                           href="{{ route('dashboard.settings') }}">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M10.3246 4.31731C10.751 2.5609 13.249 2.5609 13.6754 4.31731C13.9708 5.53962 15.0041 6.38624 16.208 6.48373C18.0163 6.61864 19.1441 8.8536 17.9255 10.3207C17.0623 11.372 17.0623 12.878 17.9255 13.9293C19.1441 15.3964 18.0163 17.6314 16.208 17.7663C15.0041 17.8638 13.9708 18.7104 13.6754 19.9327C13.249 21.6891 10.751 21.6891 10.3246 19.9327C10.0292 18.7104 8.9959 17.8638 7.79195 17.7663C5.98366 17.6314 4.85587 15.3964 6.07452 13.9293C6.93774 12.878 6.93774 11.372 6.07452 10.3207C4.85587 8.8536 5.98366 6.61864 7.79195 6.48373C8.9959 6.38624 10.0292 5.53962 10.3246 4.31731Z"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M12 15C13.6569 15 15 13.6569 15 12C15 10.3431 13.6569 9 12 9C10.3431 9 9 10.3431 9 12C9 13.6569 10.3431 15 12 15Z"
                                      stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <span class="mx-4 font-medium" style="font-family: 'Museo 500', sans-serif;">Parámetros</span>
                        </a>
                    @endcan
                @endif

            </nav>

            <!-- Botón de Logout -->
            <div class="mt-4">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="flex items-center w-full px-4 py-2 text-gray-600 transition-colors duration-300 transform rounded-lg dark:text-gray-400 hover:bg-red-100 dark:hover:bg-red-700 dark:hover:text-red-200 hover:text-red-700">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                             stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                        </svg>
                        <span class="mx-4 font-medium" style="font-family: 'Museo 500', sans-serif;">Cerrar Sesión</span>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Contenido Principal -->
    <div class="flex flex-col flex-1 overflow-y-auto">
        <header class="flex items-center justify-between h-16 px-6 bg-white border-b dark:bg-gray-900 dark:border-gray-700">
            <!-- Botón para toggle sidebar en móvil -->
            <button @click="sidebarOpen = !sidebarOpen"
                    class="text-gray-500 focus:outline-none focus:text-gray-600">
                <svg x-show="!sidebarOpen" class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg x-show="sidebarOpen" class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <!-- Título de la Sección -->
            <h1 class="text-xl font-semibold text-gray-800 dark:text-white" style="font-family: 'Museo 500', sans-serif;">
                @yield('title', 'Dashboard')
            </h1>

            <!-- Espaciador -->
            <div></div>
        </header>

        <!-- Contenido Dinámico -->
        <main class="flex-1 p-6 md:p-10">

            <!-- Aquí se cargará el contenido de las otras vistas (perfil, usuarios, etc.) -->
            @yield('content')

        </main>
    </div>
</div>

</body>
</html>

