<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - OSH Consulting</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.cdnfonts.com/css/museo-300" rel="stylesheet">
</head>
<body style="font-family: 'Museo 300', sans-serif;" class="bg-gray-50 flex items-center justify-center min-h-screen">

<div class="w-full max-w-md p-8 space-y-6 bg-white rounded-lg shadow-md">
    <div class="flex justify-center">
        {{-- Asumimos que tu logo está en public/img/osh_logo.png --}}
        <img src="{{ asset('img/osh_logo.png') }}" width="250" height="40" alt="OSH Consulting Logo">
    </div>
    <h2 class="text-2xl font-bold text-center text-gray-900">
        Iniciar Sesión
    </h2>

    <!--
      Este es un formulario de login de ejemplo.
      Deberás crear la ruta POST y el método en el controlador para manejarlo.
    -->
    <form class="space-y-6" action="#" method="POST">
        {{--
        @csrf
        --}}
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">
                Correo Electrónico
            </label>
            <div class="mt-1">
                <input id="email" name="email" type="email" autocomplete="email" required
                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm appearance-none focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">
                Contraseña
            </label>
            <div class="mt-1">
                <input id="password" name="password" type="password" autocomplete="current-password" required
                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm appearance-none focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
        </div>

        <div>
            <button type="submit"
                    class="flex justify-center w-full px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Entrar
            </button>
        </div>
    </form>
    <div class="text-center text-sm">
        <a href="{{ route('inicio') }}" class="font-medium text-blue-600 hover:text-blue-500">
            &larr; Volver al inicio
        </a>
    </div>
</div>

</body>
</html>
