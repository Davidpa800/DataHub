<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - OSH Consulting</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // Configuración personalizada de Tailwind
        tailwind.config = {
            darkMode: 'class', // Habilitar modo oscuro basado en clase
            theme: {
                extend: {
                    colors: {
                        // Colores personalizados
                        lightbg: '#f0f6ff',   // azul muy claro para fondo claro
                        lightcard: '#ffffff', // blanco para tarjeta clara
                        lighttext: '#1e3a8a', // azul oscuro para texto claro
                        darkbg: '#0a192f',    // azul muy oscuro para fondo oscuro
                        darkcard: '#112240',  // azul intermedio para tarjeta oscura
                        darktext: '#e2e8f0'   // gris claro para texto oscuro
                    }
                }
            }
        };
    </script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />

    <!-- Fuente Museo -->
    <link href="https://fonts.cdnfonts.com/css/museo-500" rel="stylesheet">
    <style>
        @import url('https://fonts.cdnfonts.com/css/museo-500');
        @import url('https://fonts.cdnfonts.com/css/museo-300');
        body { font-family: 'Museo 300', sans-serif; }
        .museo-500 { font-family: 'Museo 500', sans-serif; }

        /* Spinner */
        .spinner {
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top: 2px solid #ffffff;
            width: 1em;
            height: 1em;
            animation: spin 1s linear infinite;
            display: inline-block;
            vertical-align: middle;
            margin-right: 0.5em;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>

    <!-- Configurar tema inicial antes de renderizar -->
    <script>
        // Aplica el tema guardado o el preferido por el sistema ANTES de cargar
        try {
            if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        } catch (e) {
            console.error("Error accessing localStorage for initial theme:", e);
            document.documentElement.classList.remove('dark'); // Fallback a modo claro
        }
    </script>
</head>
<body class="bg-lightbg dark:bg-darkbg transition-colors duration-500">

<div class="flex items-center justify-center min-h-screen px-4">
    <!-- Usamos los colores personalizados definidos en tailwind.config -->
    <div class="relative w-full max-w-md p-8 space-y-6 bg-lightcard dark:bg-darkcard rounded-lg shadow-xl transition-all duration-500">

        <!-- Botón de modo oscuro -->
        <button id="theme-toggle" type="button"
                class="absolute top-4 right-4 text-gray-600 dark:text-gray-300 hover:bg-blue-100 dark:hover:bg-[#1f2e4d] rounded-lg text-sm p-2.5 transition"> <!-- Ajustado hover oscuro -->
            <i id="theme-toggle-icon" class="fas"></i> <!-- Icono se define con JS -->
        </button>

        <!-- Logo -->
        <div class="flex justify-center mt-6">
            <a href="{{ route('inicio') }}">
                <!-- Logo se invierte en modo oscuro -->
                <img src="{{ asset('img/osh_logo.png') }}" class="h-12 w-auto dark:filter dark:invert" alt="OSH Consulting Logo"
                     onerror="this.src='https://placehold.co/250x50/cccccc/333333?text=OSH+Logo'; this.onerror=null;">
            </a>
        </div>

        <!-- Título -->
        <h1 class="text-2xl font-bold text-center museo-500 text-lighttext dark:text-darktext">
            Iniciar Sesión
        </h1>
        <p class="text-center text-sm text-blue-700 dark:text-blue-300">
            Accede a tu cuenta de OSH Consulting.
        </p>

        <!-- Mensajes -->
        @if ($errors->any())
            <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-opacity-20 dark:bg-red-900 dark:text-red-300" role="alert"> <!-- Ajustado modo oscuro -->
                <span class="font-medium">¡Error!</span>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (session('status'))
            <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-opacity-20 dark:bg-green-900 dark:text-green-300" role="alert"> <!-- Ajustado modo oscuro -->
                {{ session('status') }}
            </div>
        @endif

        <!-- Formulario -->
        <form class="space-y-6" method="POST" action="{{ route('login.handle') }}">
            @csrf

            <div>
                <label for="email" class="block mb-2 text-sm font-medium text-lighttext dark:text-darktext museo-500">
                    Correo Electrónico
                </label>
                <input type="email" name="email" id="email"
                       class="bg-white dark:bg-[#0f1c33] border border-blue-200 dark:border-blue-700 text-gray-900 dark:text-gray-100 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 transition-colors duration-200"
                       placeholder="tu@correo.com" required value="{{ old('email') }}" autocomplete="email">
            </div>

            <div>
                <label for="password" class="block mb-2 text-sm font-medium text-lighttext dark:text-darktext museo-500">
                    Contraseña
                </label>
                <input type="password" name="password" id="password"
                       placeholder="••••••••"
                       class="bg-white dark:bg-[#0f1c33] border border-blue-200 dark:border-blue-700 text-gray-900 dark:text-gray-100 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 transition-colors duration-200"
                       required autocomplete="current-password">
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-start">
                    <div class="flex items-center h-5"> <!-- Contenedor para alinear checkbox -->
                        <input id="remember-me" name="remember-me" type="checkbox"
                               class="w-4 h-4 text-blue-600 bg-gray-100 rounded border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:bg-gray-700 dark:border-gray-600 transition-colors duration-200">
                    </div>
                    <label for="remember-me" class="ml-2 text-sm text-blue-700 dark:text-blue-300">Recordarme</label>
                </div>
                {{-- <a href="#" class="text-sm text-blue-600 hover:underline dark:text-blue-500">¿Olvidaste tu contraseña?</a> --}}
            </div>

            <!-- Botón -->
            <button type="submit" id="login-button"
                    class="w-full text-white bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-400 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 museo-500 transition duration-150 ease-in-out">
                Ingresar
            </button>

            <p class="text-center text-xs text-blue-700 dark:text-blue-300 mt-6">
                &copy; {{ date('Y') }} OSH Consulting. Todos los derechos reservados.
            </p>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const themeToggleBtn = document.getElementById('theme-toggle');
        const themeToggleIcon = document.getElementById('theme-toggle-icon');
        const htmlElement = document.documentElement;

        // Función simple para actualizar icono
        function updateThemeIcon(isDarkMode) {
            themeToggleIcon.classList.remove('fa-sun', 'fa-moon'); // Limpiar clases previas
            themeToggleIcon.classList.add(isDarkMode ? 'fa-moon' : 'fa-sun');
        }

        // Inicializar UI basada en clase 'dark' en <html> (establecida por script en <head>)
        updateThemeIcon(htmlElement.classList.contains('dark'));

        // Listener para el botón
        themeToggleBtn.addEventListener('click', () => {
            // Alternar clase 'dark' en <html>
            const isDarkModeNow = htmlElement.classList.toggle('dark');
            // Guardar preferencia en localStorage
            try {
                localStorage.setItem('color-theme', isDarkModeNow ? 'dark' : 'light');
            } catch (e) {
                console.error("Error saving theme to localStorage:", e);
            }
            // Actualizar el icono
            updateThemeIcon(isDarkModeNow);
        });

        // Script para mostrar spinner en el botón al enviar el formulario
        const loginForm = document.querySelector('form');
        const loginButton = document.getElementById('login-button');

        if (loginForm && loginButton) {
            loginForm.addEventListener('submit', function() {
                loginButton.disabled = true;
                loginButton.innerHTML = '<span class="spinner"></span>Ingresando...';
            });
        } else {
            console.error('Login form or button not found for spinner script!');
        }
    });
</script>
</body>
</html>

