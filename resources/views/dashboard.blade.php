<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class=""> <!-- Clase 'dark' se añade/quita con JS -->
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - OSH Consulting</title> <!-- Título dinámico -->

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // Configuración personalizada de Tailwind (opcional, pero útil si defines colores)
        tailwind.config = {
            darkMode: 'class', // Habilitar modo oscuro basado en clase
            theme: {
                extend: {
                    // Puedes añadir colores personalizados aquí si los necesitas
                    colors: {
                        // Colores personalizados para el tema claro
                        'osh-light-bg': '#F0F4F8', // Un gris azulado muy claro
                        'osh-light-sidebar': '#1E3A8A', // Azul oscuro para sidebar
                        'osh-light-sidebar-link': '#E0E7FF', // Azul muy pálido para texto link
                        'osh-light-sidebar-hover': '#1E40AF', // Azul un poco más claro para hover
                        'osh-light-sidebar-active': '#1C3D7A', // Azul más oscuro para activo
                        'osh-light-navbar': '#FFFFFF',
                        'osh-light-text': '#1F2937', // Gris oscuro para texto general
                        'osh-light-accent': '#2563EB', // Azul principal para acentos

                        // Colores personalizados para el tema oscuro
                        'osh-dark-bg': '#0F172A', // Azul muy oscuro casi negro
                        'osh-dark-sidebar': '#1E293B', // Gris azulado oscuro para sidebar
                        'osh-dark-sidebar-link': '#94A3B8', // Gris azulado claro para texto link
                        'osh-dark-sidebar-hover': '#334155', // Gris azulado medio para hover
                        'osh-dark-sidebar-active': '#0F172A', // Mismo que fondo para activo
                        'osh-dark-navbar': '#1E293B', // Mismo que sidebar oscuro
                        'osh-dark-text': '#E2E8F0', // Gris muy claro para texto general
                        'osh-dark-accent': '#3B82F6', // Azul más brillante para acentos
                    }
                }
            }
        };
    </script>

    <!-- Alpine JS (Para interactividad como dropdowns y sidebar móvil) -->
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Flowbite (Para componentes como dropdowns, modales, etc.) -->
    <link rel="stylesheet" href="https://unpkg.com/flowbite@1.5.3/dist/flowbite.min.css" />
    <script src="https://unpkg.com/flowbite@1.5.3/dist/flowbite.js"></script> <!-- Cargar después de Alpine si usas ambos -->

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />

    <!-- Fuentes -->
    <link href="https://fonts.cdnfonts.com/css/museo-500" rel="stylesheet">
    <style>
        @import url('https://fonts.cdnfonts.com/css/museo-500');
        @import url('https://fonts.cdnfonts.com/css/museo-300');
        body { font-family: 'Museo 300', sans-serif; }
        .museo-500 { font-family: 'Museo 500', sans-serif; }

        /* Estilos personalizados para scrollbars (opcional) */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
        .dark ::-webkit-scrollbar-track { background: #2d3748; }
        ::-webkit-scrollbar-thumb { background: #a0aec0; border-radius: 10px; }
        .dark ::-webkit-scrollbar-thumb { background: #4a5568; }
        ::-webkit-scrollbar-thumb:hover { background: #718096; }
        .dark ::-webkit-scrollbar-thumb:hover { background: #718096; }

        /* Pequeño ajuste para que Alpine no cause 'flash' al cargar */
        [x-cloak] { display: none !important; }
    </style>

    <!-- Script inicial modo oscuro (importante ponerlo en head) -->
    <script>
        try {
            if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        } catch (e) { document.documentElement.classList.remove('dark'); }
    </script>

    @stack('styles') {{-- Para añadir estilos específicos desde las vistas hijas --}}

</head>
<body class="bg-osh-light-bg dark:bg-osh-dark-bg text-osh-light-text dark:text-osh-dark-text transition-colors duration-300">

<div x-data="{ sidebarOpen: false }" @keydown.escape="sidebarOpen = false" class="flex h-screen bg-osh-light-bg dark:bg-osh-dark-bg overflow-hidden">
    <!-- Sidebar -->
    <aside
        :class="sidebarOpen ? 'translate-x-0 ease-out' : '-translate-x-full ease-in'"
        class="fixed inset-y-0 left-0 z-30 w-64 overflow-y-auto transition duration-300 transform bg-osh-light-sidebar dark:bg-osh-dark-sidebar lg:translate-x-0 lg:static lg:inset-0 shadow-lg print:hidden">

        <!-- Logo -->
        <div class="flex items-center justify-center mt-8 px-4">
            <a href="{{ route('inicio') }}">
                <img src="{{ asset('img/osh_logo_blanco.png') }}" alt="OSH Logo" class="h-10 w-auto"
                     onerror="this.src='https://placehold.co/150x40/ffffff/1E3A8A?text=OSH+Logo'; this.onerror=null;">
                <!-- Asegúrate de tener una versión blanca del logo -->
            </a>
        </div>

        <!-- Menú de Navegación -->
        <nav class="mt-10 px-2 space-y-1">
            {{-- Enlace al Dashboard Principal --}}
            <a class="flex items-center px-4 py-2 text-osh-light-sidebar-link rounded hover:bg-osh-light-sidebar-hover dark:text-osh-dark-sidebar-link dark:hover:bg-osh-dark-sidebar-hover transition-colors duration-200 {{ request()->routeIs('dashboard') ? 'bg-osh-light-sidebar-active dark:bg-osh-dark-sidebar-active font-semibold' : '' }}"
               href="{{ route('dashboard') }}">
                <i class="fas fa-tachometer-alt w-5 h-5 mr-3"></i>
                <span class="font-medium museo-500">Dashboard</span>
            </a>

            {{-- Enlace al Módulo NOM-035 (si tiene permiso) --}}
            @can('gestionar nom035')
                <a class="flex items-center px-4 py-2 text-osh-light-sidebar-link rounded hover:bg-osh-light-sidebar-hover dark:text-osh-dark-sidebar-link dark:hover:bg-osh-dark-sidebar-hover transition-colors duration-200 {{ request()->routeIs('nom035.*') ? 'bg-osh-light-sidebar-active dark:bg-osh-dark-sidebar-active font-semibold' : '' }}"
                   href="{{ route('nom035.index') }}">
                    <i class="fas fa-file-signature w-5 h-5 mr-3"></i>
                    <span class="font-medium museo-500">Gestión NOM-035</span>
                </a>
            @endcan

            {{-- Separador y Sección de Administración (si tiene algún permiso de admin) --}}
            @if(Auth::check() && (Auth::user()->can('gestionar usuarios') || Auth::user()->can('gestionar roles') || Auth::user()->can('gestionar permisos')))
                <hr class="my-4 border-blue-700 dark:border-gray-600">
                <span class="px-4 text-xs text-blue-300 dark:text-gray-400 uppercase museo-500 tracking-wider">Administración</span>

                @can('gestionar usuarios')
                    <a class="flex items-center px-4 py-2 mt-1 text-osh-light-sidebar-link rounded hover:bg-osh-light-sidebar-hover dark:text-osh-dark-sidebar-link dark:hover:bg-osh-dark-sidebar-hover transition-colors duration-200 {{ request()->routeIs('admin.users.index') ? 'bg-osh-light-sidebar-active dark:bg-osh-dark-sidebar-active font-semibold' : '' }}"
                       href="{{ route('admin.users.index') }}">
                        <i class="fas fa-users-cog w-5 h-5 mr-3"></i>
                        <span class="font-medium museo-500">Usuarios</span>
                    </a>
                @endcan
                {{-- @can('gestionar roles') --}}
                {{-- Futuro enlace a roles --}}
                {{-- @endcan --}}
                @can('gestionar permisos')
                    <a class="flex items-center px-4 py-2 mt-1 text-osh-light-sidebar-link rounded hover:bg-osh-light-sidebar-hover dark:text-osh-dark-sidebar-link dark:hover:bg-osh-dark-sidebar-hover transition-colors duration-200 {{ request()->routeIs('admin.permissions.index') ? 'bg-osh-light-sidebar-active dark:bg-osh-dark-sidebar-active font-semibold' : '' }}"
                       href="{{ route('admin.permissions.index') }}">
                        <i class="fas fa-key w-5 h-5 mr-3"></i>
                        <span class="font-medium museo-500">Permisos</span>
                    </a>
                @endcan
            @endif

            {{-- Separador Configuración --}}
            {{-- Enlace a Perfil/Configuración ahora está en el dropdown de usuario --}}

        </nav>
    </aside>

    <!-- Contenido Principal -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Navbar Superior -->
        <header class="flex items-center justify-between px-6 py-3 bg-osh-light-navbar border-b dark:bg-osh-dark-navbar dark:border-gray-700 shadow-sm print:hidden">
            <div class="flex items-center">
                <!-- Botón para abrir/cerrar sidebar en móvil -->
                <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 dark:text-gray-400 focus:outline-none lg:hidden mr-4">
                    <i class="fas fa-bars w-6 h-6"></i>
                </button>
                <!-- Título de la página (dinámico) -->
                <h1 class="text-xl font-semibold text-osh-light-text dark:text-osh-dark-text museo-500 hidden sm:block">
                    @yield('title', 'Dashboard') {{-- Título por defecto --}}
                </h1>
            </div>

            <!-- Parte derecha del Navbar -->
            <div class="flex items-center space-x-4">
                <!-- Botón Modo Oscuro -->
                <button id="theme-toggle" type="button"
                        class="text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg text-sm p-2.5 transition-colors duration-200">
                    <i id="theme-toggle-icon" class="fas"></i> <!-- Icono se define con JS -->
                </button>

                <!-- Dropdown de Usuario -->
                <div x-data="{ dropdownOpen: false }" class="relative">
                    <button @click="dropdownOpen = !dropdownOpen" @keydown.escape="dropdownOpen = false"
                            class="relative z-10 block h-8 w-8 overflow-hidden rounded-full shadow focus:outline-none border-2 border-transparent focus:border-osh-light-accent dark:focus:border-osh-dark-accent">
                        {{-- Placeholder Avatar --}}
                        <span class="inline-flex items-center justify-center h-full w-full rounded-full bg-osh-light-accent dark:bg-osh-dark-accent text-white dark:text-osh-dark-sidebar">
                                <span class="text-sm font-medium leading-none uppercase">{{ substr(Auth::user()->name ?? 'U', 0, 1) }}</span>
                            </span>
                        {{-- <img class="h-full w-full object-cover" src="{{ Auth::user()->profile_photo_url ?? default_avatar_url }}" alt="Tu Avatar"> --}}
                    </button>

                    <!-- Menú Dropdown -->
                    <div x-show="dropdownOpen"
                         @click.away="dropdownOpen = false"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 z-20 w-56 py-2 mt-2 overflow-hidden bg-white rounded-md shadow-xl dark:bg-gray-800"
                         x-cloak> <!-- x-cloak para evitar flash inicial -->

                        <div class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                            <div class="font-medium truncate">{{ Auth::user()->name ?? 'Usuario' }}</div>
                            <div class="text-xs text-gray-500 truncate dark:text-gray-400">{{ Auth::user()->email ?? '' }}</div>
                        </div>
                        <hr class="border-gray-200 dark:border-gray-700">
                        <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">
                            <i class="fas fa-user-cog w-4 h-4 mr-2"></i>Mi Perfil y Config.
                        </a>
                        <hr class="border-gray-200 dark:border-gray-700">
                        <!-- Formulario para Logout -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-gray-600 dark:text-red-400 dark:hover:text-red-300">
                                <i class="fas fa-sign-out-alt w-4 h-4 mr-2"></i>
                                Cerrar Sesión
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Área de Contenido Principal -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-osh-light-bg dark:bg-osh-dark-bg p-6">
            <div class="container mx-auto">
                <!-- Alertas de Sesión (Status) -->
                @if (session('status'))
                    <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-900 dark:text-green-300" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                <!-- Alertas de Sesión (Error) -->
                @if (session('error'))
                    <div class="mb-4 p-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-900 dark:text-red-300" role="alert">
                        {{ session('error') }}
                    </div>
                @endif
                <!-- Aquí se cargará el contenido específico de cada página -->
                @yield('content')
            </div>
        </main>
    </div>
</div>

<!-- Script Modo Oscuro (al final para asegurar que elementos existen) -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const themeToggleBtn = document.getElementById('theme-toggle');
        const themeToggleIcon = document.getElementById('theme-toggle-icon');
        const htmlElement = document.documentElement;

        if (!themeToggleBtn || !themeToggleIcon) {
            console.error("Theme toggle button or icon not found!");
            return;
        };

        function updateThemeUI(isDarkMode) {
            themeToggleIcon.classList.remove('fa-sun', 'fa-moon');
            themeToggleIcon.classList.add(isDarkMode ? 'fa-moon' : 'fa-sun');
        }

        // Inicializar UI basada en clase 'dark' en <html>
        updateThemeUI(htmlElement.classList.contains('dark'));

        themeToggleBtn.addEventListener('click', () => {
            const isDarkModeNow = htmlElement.classList.toggle('dark');
            try { localStorage.setItem('color-theme', isDarkModeNow ? 'dark' : 'light'); }
            catch (e) { console.error("Error saving theme preference:", e); }
            updateThemeUI(isDarkModeNow);
        });
    });
</script>
@stack('scripts') {{-- Para añadir scripts específicos desde las vistas hijas --}}

</body>
</html>

