@extends('dashboard') {{-- Asegúrate que tu layout principal se llame 'dashboard.blade.php' --}}

@section('title', 'Panel de Administración Principal') {{-- Título específico para esta página --}}

@section('content')
    {{-- Mensajes de estado/error generales --}}
    @if(session('status'))
        <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-900 dark:text-green-300" role="alert">
            {{ session('status') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-900 dark:text-red-300" role="alert">
            {{ session('error') }}
        </div>
    @endif

    {{-- Tarjetas de Resumen Generales --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">

        <!-- Card Total Usuarios -->
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6 flex items-center space-x-4">
            <div class="flex-shrink-0 bg-teal-100 dark:bg-teal-900 rounded-full p-3">
                <i class="fas fa-users fa-lg text-teal-600 dark:text-teal-400"></i>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase museo-500">Total Usuarios</h3>
                <p class="mt-1 text-3xl font-semibold text-teal-600 dark:text-teal-400">{{ $totalUsuarios ?? 0 }}</p>
            </div>
        </div>

        <!-- Card Total Roles -->
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6 flex items-center space-x-4">
            <div class="flex-shrink-0 bg-cyan-100 dark:bg-cyan-900 rounded-full p-3">
                <i class="fas fa-user-tag fa-lg text-cyan-600 dark:text-cyan-400"></i>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase museo-500">Total Roles</h3>
                <p class="mt-1 text-3xl font-semibold text-cyan-600 dark:text-cyan-400">{{ $totalRoles ?? 0 }}</p>
            </div>
        </div>

        <!-- Card Total Empresas (General) -->
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6 flex items-center space-x-4">
            <div class="flex-shrink-0 bg-blue-100 dark:bg-blue-900 rounded-full p-3">
                <i class="fas fa-building fa-lg text-blue-600 dark:text-blue-400"></i>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase museo-500">Total Empresas</h3>
                <p class="mt-1 text-3xl font-semibold text-blue-600 dark:text-blue-400">{{ $totalEmpresas ?? 0 }}</p>
            </div>
        </div>

        <!-- Card Total Contratos (General) -->
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6 flex items-center space-x-4">
            <div class="flex-shrink-0 bg-green-100 dark:bg-green-900 rounded-full p-3">
                <i class="fas fa-file-contract fa-lg text-green-600 dark:text-green-400"></i>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase museo-500">Total Contratos</h3>
                <p class="mt-1 text-3xl font-semibold text-green-600 dark:text-green-400">{{ $totalContratos ?? 0 }}</p>
            </div>
        </div>

    </div>

    {{-- Aquí podrías añadir más información o gráficas generales si lo necesitas --}}
    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6">
        <h4 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4 museo-500 border-b pb-2 dark:border-gray-700">
            Bienvenido al Panel de Administración
        </h4>
        <p class="text-gray-600 dark:text-gray-300">
            Utiliza el menú lateral para navegar por las diferentes secciones del sistema. Aquí podrás gestionar usuarios, permisos, los módulos específicos como NOM-035 y configurar los parámetros generales.
        </p>
        {{-- Ejemplo de Enlace Rápido --}}
        {{-- @can('gestionar nom035')
        <div class="mt-6">
             <a href="{{ route('nom035.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                 Ir a Gestión NOM-035 <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
        @endcan --}}
    </div>

@endsection

@push('styles')
    {{-- Puedes añadir estilos específicos para esta página si es necesario --}}
@endpush

@push('scripts')
    {{-- Puedes añadir scripts específicos para esta página si es necesario --}}
@endpush

