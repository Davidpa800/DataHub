@extends('dashboard') {{-- Usar el layout principal --}}

@section('title', 'Gestión NOM-035') {{-- Título específico para esta página --}}

@section('content')

    {{-- Encabezado del Módulo y Botones de Acción --}}
    <div class="flex flex-col sm:flex-row justify-between items-center pb-5 mb-6 border-b border-gray-200 dark:border-gray-700">
        <h2 class="text-3xl font-semibold text-gray-800 dark:text-gray-100 museo-500 mb-3 sm:mb-0 flex items-center">
            <i class="fas fa-chart-bar mr-4 text-cyan-500"></i> Dashboard NOM-035
        </h2>
        {{-- Botones de Acción --}}
        <div class="flex flex-wrap gap-2 justify-end w-full sm:w-auto">
            <a href="{{ route('nom035.empresas.create') }}" class="btn btn-primary bg-green-600 hover:bg-green-700 focus:ring-green-500 text-sm px-4 py-2">
                <i class="fas fa-plus mr-1"></i> Nueva Empresa
            </a>
            <a href="{{ route('nom035.contratos.create') }}" class="btn btn-primary bg-blue-600 hover:bg-blue-700 focus:ring-blue-500 text-sm px-4 py-2">
                <i class="fas fa-file-contract mr-1"></i> Gestionar Contratos
            </a>
            <a href="{{ route('nom035.empleados.index') }}" class="btn btn-primary bg-purple-600 hover:bg-purple-700 focus:ring-purple-500 text-sm px-4 py-2">
                <i class="fas fa-users mr-1"></i> Gestionar Empleados
            </a>
            <a href="{{ route('nom035.asignaciones.create') }}" class="btn btn-primary bg-cyan-600 hover:bg-cyan-700 focus:ring-cyan-500 text-sm px-4 py-2">
                <i class="fas fa-paper-plane mr-1"></i> Asignar Encuestas
            </a>
        </div>
    </div>

    {{-- Monitores y Tarjetas de Resumen --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        @php
            $pendiente = $monitores['progreso_estado']['pendiente'] ?? 0;
            $en_progreso = $monitores['progreso_estado']['en_progreso'] ?? 0;
            $completado = $monitores['progreso_estado']['completado'] ?? 0;
            $total = $totalEncuestasAsignadas ?: 1; // Evita división por cero
        @endphp

            <!-- Card Total Asignadas -->
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-5 flex items-center space-x-4 border-l-4 border-indigo-500">
            <div class="flex-shrink-0 bg-indigo-100 dark:bg-indigo-900 rounded-full p-3">
                <i class="fas fa-list-alt fa-lg text-indigo-600 dark:text-indigo-400"></i>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase museo-500">Encuestas Asignadas</h3>
                <p class="mt-1 text-3xl font-semibold text-indigo-600 dark:text-indigo-400">{{ $totalEncuestasAsignadas ?? 0 }}</p>
            </div>
        </div>

        <!-- Card Completadas -->
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-5 flex items-center space-x-4 border-l-4 border-green-500">
            <div class="flex-shrink-0 bg-green-100 dark:bg-green-900 rounded-full p-3">
                <i class="fas fa-check-circle fa-lg text-green-600 dark:text-green-400"></i>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase museo-500">Encuestas Completadas</h3>
                <p class="mt-1 text-3xl font-semibold text-green-600 dark:text-green-400">{{ $completado }}</p>
            </div>
        </div>

        <!-- Card Monitor de Contratos (NUEVA TARJETA) -->
        <a href="{{ route('nom035.monitoreo.index') }}" class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-5 flex items-center space-x-4 border-l-4 border-cyan-500 hover:shadow-xl transition-shadow duration-150">
            <div class="flex-shrink-0 bg-cyan-100 dark:bg-cyan-900 rounded-full p-3">
                <i class="fas fa-chart-pie fa-lg text-cyan-600 dark:text-cyan-400"></i>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase museo-500">Monitor de Contratos</h3>
                <p class="mt-1 text-3xl font-semibold text-cyan-600 dark:text-cyan-400">{{ $totalContratosNom035 ?? 0 }}</p>
            </div>
        </a>

        <!-- Card Pendientes -->
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-5 flex items-center space-x-4 border-l-4 border-red-500">
            <div class="flex-shrink-0 bg-red-100 dark:bg-red-900 rounded-full p-3">
                <i class="fas fa-clock fa-lg text-red-600 dark:text-red-400"></i>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase museo-500">Pendientes</h3>
                <p class="mt-1 text-3xl font-semibold text-red-600 dark:text-red-400">{{ $pendiente }}</p>
            </div>
        </div>
    </div>


    {{-- Sección de Monitoreo de Progreso y Riesgo --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        {{-- Monitor de Progreso por Estado --}}
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-6 lg:col-span-2 border border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-medium text-gray-700 dark:text-gray-200 museo-500 mb-6 flex items-center">
                <i class="fas fa-funnel-dollar mr-2 text-blue-500"></i> Avance de Respuestas (%)
            </h3>
            <div class="space-y-4">
                @php
                    $porcentaje_pendiente = round(($pendiente / $total) * 100);
                    $porcentaje_progreso = round(($en_progreso / $total) * 100);
                    $porcentaje_completado = round(($completado / $total) * 100);
                @endphp

                {{-- Barra de Progreso Combinada --}}
                <div class="h-5 bg-gray-200 dark:bg-gray-700 rounded-lg flex overflow-hidden shadow-inner">
                    <div style="width: {{ $porcentaje_completado }}%" class="bg-green-500" title="Completadas: {{ $porcentaje_completado }}%"></div>
                    <div style="width: {{ $porcentaje_progreso }}%" class="bg-yellow-500" title="En Progreso: {{ $porcentaje_progreso }}%"></div>
                    <div style="width: {{ $porcentaje_pendiente }}%" class="bg-red-500" title="Pendientes: {{ $porcentaje_pendiente }}%"></div>
                </div>

                {{-- Leyenda Detallada --}}
                <div class="grid grid-cols-3 text-center text-sm font-medium pt-3 border-t border-gray-100 dark:border-gray-700">
                    <div class="space-y-1">
                        <span class="text-green-600 dark:text-green-400">{{ $porcentaje_completado }}%</span>
                        <div class="text-gray-500 dark:text-gray-400">Completadas ({{ $completado }})</div>
                    </div>
                    <div class="space-y-1">
                        <span class="text-yellow-600 dark:text-yellow-400">{{ $porcentaje_progreso }}%</span>
                        <div class="text-gray-500 dark:text-gray-400">En Progreso ({{ $en_progreso }})</div>
                    </div>
                    <div class="space-y-1">
                        <span class="text-red-600 dark:text-red-400">{{ $porcentaje_pendiente }}%</span>
                        <div class="text-gray-500 dark:text-gray-400">Pendientes ({{ $pendiente }})</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Historial de Respuestas Recientes por Encuesta --}}
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-5 border border-gray-200 dark:border-gray-700">
            <h4 class="text-md font-semibold text-gray-700 dark:text-gray-200 museo-500 mb-4 border-b pb-2">
                <i class="fas fa-clipboard-check mr-2 text-indigo-500"></i> Últimas 5 Respuestas
            </h4>
            <ul class="space-y-3">
                @forelse($historialRespuestas as $cuestionarioNombre => $respuestas)
                    <li class="font-semibold text-sm text-gray-800 dark:text-gray-200 border-b border-gray-100 dark:border-gray-700/50 pb-1 mt-3">{{ $cuestionarioNombre }}</li>
                    @foreach($respuestas as $respuesta)
                        <li class="text-sm text-gray-600 dark:text-gray-300 flex justify-between">
                            <span class="truncate pr-2">{{ $respuesta->empleado->nombre_completo ?? 'Empleado Anónimo' }}</span>
                            <span class="flex-shrink-0 text-xs text-gray-400 dark:text-gray-500">{{ $respuesta->fecha_completado->diffForHumans() }}</span>
                        </li>
                    @endforeach
                @empty
                    <li class="text-sm text-gray-500 italic">No hay encuestas completadas para mostrar.</li>
                @endforelse
            </ul>
        </div>
    </div>


    {{-- Tabla de Empresas con Contratos NOM-035 --}}
    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-6 overflow-x-auto border border-gray-200 dark:border-gray-700">
        <h4 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4 museo-500 border-b pb-2 dark:border-gray-700">
            Empresas con Contratos NOM-035 Registrados
        </h4>

        {{-- Mensajes de estado/error --}}
        @if(session('status')) <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-900 dark:text-green-300" role="alert">{{ session('status') }}</div> @endif
        @if(session('error')) <div class="mb-4 p-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-900 dark:text-red-300" role="alert">{{ session('error') }}</div> @endif

        {{-- Tabla de Empresas --}}
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
            <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider museo-500">Nombre Empresa</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider museo-500">RFC</th>
                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider museo-500">Contratos NOM-035</th>
                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider museo-500">Acciones</th>
            </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
            {{-- Iterar sobre las empresas paginadas --}}
            @forelse($empresasNom035 ?? [] as $empresa)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ $empresa->nombre }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $empresa->rfc ?? 'N/A' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500 dark:text-gray-300">{{ $empresa->contratos_nom035_count ?? 0 }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-center font-medium space-x-2">
                        {{-- Botón 1: Monitor de Progreso por Empleado (Progreso) --}}
                        <a href="{{ route('nom035.monitoreo.empleados', $empresa->id) }}" title="Monitor de Progreso por Empleado" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300"><i class="fas fa-chart-area"></i></a>
                        {{-- Botón 2: Monitor de Email (Notificación) --}}
                        <a href="{{ route('nom035.monitoreo.email', $empresa->id) }}" title="Gestión de Notificaciones y Envíos" class="text-cyan-600 hover:text-cyan-800 dark:text-cyan-400 dark:hover:text-cyan-300"><i class="fas fa-envelope"></i></a>
                        {{-- Botón 3: Generación de Reportes (Resultados) --}}
                        <a href="{{ route('nom035.monitoreo.reportes', $empresa->id) }}" title="Generar Reportes y Tablas" class="text-orange-600 hover:text-orange-800 dark:text-orange-400 dark:hover:text-orange-300"><i class="fas fa-file-export"></i></a>
                        {{-- Botón 4: Editar Empresa --}}
                        <a href="#" title="Editar Empresa" class="text-yellow-600 hover:text-yellow-800 dark:text-yellow-400 dark:hover:text-yellow-300"><i class="fas fa-edit"></i></a>
                    </td>
                </tr>
            @empty
                {{-- Mensaje si no hay empresas --}}
                <tr><td colspan="4" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400 italic">No se encontraron empresas con contratos NOM-035. Puedes crear una nueva usando el botón superior.</td></tr>
            @endforelse
            </tbody>
        </table>

        {{-- Paginación --}}
        @if($empresasNom035 && $empresasNom035->hasPages())
            <div class="mt-4 px-2 py-2 border-t dark:border-gray-700">
                {{-- Renderizar los enlaces de paginación --}}
                {{ $empresasNom035->links() }}
            </div>
        @endif

    </div>

@endsection
