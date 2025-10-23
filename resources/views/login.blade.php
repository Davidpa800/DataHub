<!DOCTYPE html>
<html lang="es" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - OSH Consulting</title>
    {{-- Scripts y Estilos --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.cdnfonts.com/css/museo-300" rel="stylesheet">
    <link href="https://fonts.cdnfonts.com/css/museo-500" rel="stylesheet">
    <link rel="icon" href="{{ asset('img/osh.png') }}">
    <style>
        /* Aplicamos la fuente base al body */
        body {
            font-family: 'Museo 300', sans-serif;
        }
        /* Usamos la fuente más gruesa para elementos clave */
        .font-museo-500 {
            font-family: 'Museo 500', sans-serif;
        }
    </style>
</head>
<body class="h-full flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">

<div class="w-full max-w-md space-y-8">
    <div>
        {{-- Logo responsivo: centrado y con altura definida --}}
        <img class="mx-auto h-12 w-auto" src="{{ asset('img/osh_logo.png') }}" alt="OSH Consulting">
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900 font-museo-500">
            Bienvenido de nuevo
        </h2>
        <p class="mt-2 text-center text-sm text-gray-600">
            Inicia sesión en tu cuenta
        </p>
    </div>

    {{-- Mostrar errores de validación (ej. credenciales incorrectas) --}}
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">¡Error!</strong>
            <span class="block sm:inline">{{ $errors->first() }}</span>
        </div>
    @endif

    {{--
      =================================================
      CAMBIO DE LÓGICA 1:
      - 'action' ahora apunta a la ruta 'login.submit'.
      - 'method' es 'POST'.
      - Se descomentó @csrf (¡Obligatorio!)
      =================================================
    --}}
    <form class="mt-8 space-y-6" action="{{ route('login.submit') }}" method="POST">
        @csrf
        <input type="hidden" name="remember" value="true">
        <div class="rounded-md shadow-sm -space-y-px">
            <div>
                <label for="email-address" class="sr-only">Correo Electrónico</label>
                <input id="email-address" name="email" type="email" autocomplete="email" required
                       class="appearance-none rounded-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                       placeholder="Correo Electrónico" value="{{ old('email') }}">
            </div>
            <div>
                <label for="password" class="sr-only">Contraseña</label>
                <input id="password" name="password" type="password" autocomplete="current-password" required
                       class="appearance-none rounded-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                       placeholder="Contraseña">
            </div>
        </div>

        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <input id="remember-me" name="remember-me" type="checkbox"
                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                <label for="remember-me" class="ml-2 block text-sm text-gray-900">
                    Recordarme
                </label>
            </div>

            <div class="text-sm">
                <a href="#" class="font-medium text-blue-600 hover:text-blue-500">
                    ¿Olvidaste tu contraseña?
                </a>
            </div>
        </div>

        <div>
            <button type="submit"
                    class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 font-museo-500">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <!-- Icono de candado de Heroicons -->
                        <svg class="h-5 w-5 text-blue-500 group-hover:text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
                Entrar
            </button>
        </div>
    </form>

    <div class="text-center text-sm text-gray-600">
        <a href="{{ route('inicio') }}" class="font-medium text-blue-600 hover:text-blue-500">
            &larr; Volver a la página de inicio
        </a>
    </div>
</div>

</body>
</html>

