<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - OSH Consulting</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.cdnfonts.com/css/museo-300" rel="stylesheet">
    <link rel="icon" href="{{ asset('img/osh.png') }}">
    <style>
        body { font-family: 'Museo 300', sans-serif; }
    </style>
</head>
<body class="bg-gray-100">

<nav class="bg-white shadow-md">
    <div class="container mx-auto px-6 py-4 flex justify-between items-center">
        <div>
            <img class="h-10 w-auto" src="{{ asset('img/osh_logo.png') }}" alt="OSH Consulting Logo">
        </div>
        <div>
            {{-- Formulario de Logout --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-gray-600 hover:text-blue-600">
                    Cerrar Sesión
                </button>
            </form>
        </div>
    </div>
</nav>

<main class="container mx-auto px-6 py-12">
    <h1 class="text-3xl font-bold text-gray-900">
        ¡Bienvenido, {{ Auth::user()->name ?? 'Usuario' }}!
    </h1>
    <p class="mt-2 text-gray-700">
        Has iniciado sesión correctamente. Este es tu panel de control.
    </p>

    <div class="mt-8 p-6 bg-white rounded-lg shadow-md">
        <h2 class="text-xl font-semibold">Panel Protegido</h2>
        <p class="mt-4">Aquí puedes administrar tu cuenta, ver reportes o cualquier otra cosa que solo los usuarios autenticados puedan hacer.</p>
    </div>
</main>

</body>
</html>
