<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>¡Gracias!</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <style>body { background-color: #f7fafc; }</style>
</head>
<body class="flex items-center justify-center min-h-screen bg-gray-50">

<div class="w-full max-w-lg bg-white p-10 rounded-xl shadow-2xl text-center border-t-4 border-green-500">
    <i class="fas fa-check-circle text-green-500 text-6xl mb-6 animate-pulse"></i>
    <h1 class="text-3xl font-bold text-gray-800 mb-4">¡Gracias por tu participación!</h1>
    <p class="text-lg text-gray-600 mb-6">
        Has completado exitosamente la encuesta. Tu información es invaluable para el cumplimiento de la NOM-035.
    </p>

    {{-- Opción para salir --}}
    <a href="#" onclick="window.close()" class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
        <i class="fas fa-sign-out-alt mr-2"></i> Cerrar Ventana
    </a>
</div>

</body>
</html>
