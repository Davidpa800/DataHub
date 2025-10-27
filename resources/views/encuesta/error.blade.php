<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error de Encuesta</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <link href="https://fonts.cdnfonts.com/css/museo-500" rel="stylesheet">
    <style>
        body { font-family: 'Museo 300', sans-serif; background-color: #f1f5f9; }
        .museo-500 { font-family: 'Museo 500', sans-serif; }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen bg-gray-100 p-6">

<div class="w-full max-w-lg bg-white p-8 sm:p-12 rounded-xl shadow-xl text-center border-t-8 border-red-500">
    <div class="w-24 h-24 bg-red-100 rounded-full flex items-center justify-center mx-auto">
        <i class="fas fa-exclamation-triangle text-red-500 text-5xl"></i>
    </div>

    <h1 class="text-3xl font-bold text-gray-800 mt-6 mb-4 museo-500">Acceso Inválido</h1>
    <p class="text-lg text-gray-600 mb-8">
        {{ $message ?? 'No pudimos encontrar la encuesta que buscas. Es posible que el enlace haya expirado, ya haya sido completada o sea incorrecto.' }}
    </p>

    <p class="text-xs text-gray-500 mt-8">
        Por favor, contacta al administrador del sistema si crees que esto es un error.
    </p>
</div>

</body>
</html>
