@extends('dashboard') {{-- Asegúrate que tu layout principal se llame 'dashboard.blade.php' --}}

@section('title', 'Monitor de Progreso por Empleado')

@section('content')
    <div class="space-y-8">

        {{-- Encabezado --}}
        <div class="flex flex-col sm:flex-row justify-between items-center pb-5 mb-6 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-3xl font-semibold text-gray-800 dark:text-gray-100 museo-500 mb-3 sm:mb-0 flex items-center">
                <i class="fas fa-chart-area mr-4 text-blue-500"></i> Monitor de Progreso: <span class="text-purple-600 dark:text-purple-400 ml-2">{{ $empresa->nombre ?? 'N/A' }}</span>
            </h2>
            <a href="{{ route('nom035.index') }}" class="btn btn-secondary w-full sm:w-auto">
                <i class="fas fa-arrow-left mr-2"></i> Volver al Dashboard NOM-035
            </a>
        </div>

        {{-- Resumen Rápido --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @php
                // Cálculos para el resumen de esta empresa
                $totalAsignaciones = $empleados->sum(fn($e) => $e->encuestasAsignadas->count());
                $totalCompletadas = $empleados->sum(fn($e) => $e->encuestasAsignadas->where('estado', 'completado')->count());
                $totalPendientes = $empleados->sum(fn($e) => $e->encuestasAsignadas->whereIn('estado', ['pendiente', 'en_progreso'])->count());
                $progresoGeneral = $totalAsignaciones > 0 ? round(($totalCompletadas / $totalAsignaciones) * 100) : 0;
            @endphp

            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-4 border-l-4 border-blue-400">
                <p class="text-sm text-gray-500 dark:text-gray-400">Total Empleados</p>
                <p class="text-2xl font-semibold text-blue-600 dark:text-blue-400">{{ $empleados->count() }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-4 border-l-4 border-green-400">
                <p class="text-sm text-gray-500 dark:text-gray-400">Total Completadas</p>
                <p class="text-2xl font-semibold text-green-600 dark:text-green-400">{{ $totalCompletadas }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-4 border-l-4 border-red-400">
                <p class="text-sm text-gray-500 dark:text-gray-400">Pendientes/En Progreso</p>
                <p class="text-2xl font-semibold text-red-600 dark:text-red-400">{{ $totalPendientes }}</p>
            </div>
        </div>


        {{-- Tabla de Progreso Detallado por Empleado --}}
        <div class="bg-white dark:bg-gray-800 shadow-xl rounded-xl p-6 lg:p-8 border border-gray-200 dark:border-gray-700 overflow-x-auto">
            <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100 museo-500 mb-6 border-b border-gray-200 dark:border-gray-700 pb-4">
                Listado de Empleados y Avance
            </h3>

            @if($empleados->isEmpty())
                <div class="text-center py-10 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-lg">
                    <i class="fas fa-users text-4xl text-gray-400 mb-3"></i>
                    <p class="text-lg font-medium text-gray-500 dark:text-gray-400">No hay empleados registrados para esta empresa.</p>
                    <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Puedes agregarlos en la sección "Gestionar Empleados".</p>
                </div>
            @else
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider museo-500">Empleado (Clave)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider museo-500">Puesto / Centro</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider museo-500">Total Asignaciones</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider museo-500">Progreso General</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider museo-500">Acciones</th>
                    </tr>
                    </thead>

                    {{-- Itera sobre cada empleado, creando un <tbody> para cada uno para controlar el colapso --}}
                    @foreach($empleados as $empleado)
                        @php
                            $completedCount = $empleado->encuestasAsignadas->where('estado', 'completado')->count();
                            $totalAssigned = $empleado->encuestasAsignadas->count();
                            $overallProgress = $totalAssigned > 0 ? round(($completedCount / $totalAssigned) * 100) : 0;
                            $overallColor = $overallProgress == 100 ? 'bg-green-500' : ($overallProgress > 0 ? 'bg-yellow-500' : 'bg-gray-300');
                        @endphp

                        <tbody x-data="{ expanded: false }" class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        {{-- Fila Principal (Clickeable) --}}
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                <button @click="expanded = !expanded" class="flex items-center focus:outline-none text-blue-600 dark:text-blue-400 hover:text-blue-800">
                                    <i class="fas mr-2 transition-transform duration-200" :class="expanded ? 'fa-chevron-down' : 'fa-chevron-right'"></i>
                                    {{ $empleado->nombre_completo ?? 'N/A' }}
                                    <span class="ml-2 text-xs text-gray-500 dark:text-gray-400">({{ $empleado->clave ?? 'S/C' }})</span>
                                </button>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                {{ $empleado->puesto ?? 'N/A' }} <span class="text-xs text-gray-400 block">{{ $empleado->centro_trabajo }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                {{ $totalAssigned }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="w-24 bg-gray-200 rounded-full h-2 dark:bg-gray-600 shadow-inner">
                                    <div class="{{ $overallColor }} h-2 rounded-full" style="width: {{ $overallProgress }}%" title="{{ $overallProgress }}%"></div>
                                </div>
                                <span class="text-xs text-gray-500 dark:text-gray-400 mt-1 block">{{ $overallProgress }}% ({{ $completedCount }} completadas)</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                {{-- Acciones Generales del Empleado --}}
                                <a href="{{ route('nom035.monitoreo.email', ['empresa' => $empresa->id]) }}?empleado={{ $empleado->id }}" class="text-cyan-600 hover:text-cyan-800 dark:text-cyan-400 dark:hover:text-cyan-300" title="Reenviar Email">
                                    <i class="fas fa-envelope-open-text"></i>
                                </a>
                            </td>
                        </tr>
                        {{-- Fila de Detalle (Colapsable) --}}
                        <tr x-show="expanded" x-collapse.duration.300ms style="display: none;" class="last:border-b-0">
                            <td colspan="5" class="p-0 border-t-0 bg-gray-50 dark:bg-gray-800/80">
                                <div class="p-6">
                                    <h5 class="text-sm font-semibold uppercase text-gray-600 dark:text-gray-400 mb-3 border-b pb-2">Detalle de Encuestas Asignadas</h5>
                                    @if($empleado->encuestasAsignadas->isEmpty())
                                        <p class="text-sm text-gray-500 italic">No tiene encuestas NOM-035 asignadas en contratos activos.</p>
                                    @else
                                        <ul class="space-y-4">
                                            @foreach($empleado->encuestasAsignadas as $asignacion)
                                                @php
                                                    $progreso = $asignacion->total_preguntas > 0 ? round(($asignacion->progreso_actual / $asignacion->total_preguntas) * 100) : 0;
                                                    $color = $asignacion->estado === 'completado' ? 'bg-green-500' : ($asignacion->estado === 'pendiente' ? 'bg-red-500' : 'bg-yellow-500');
                                                    $canView = in_array($asignacion->estado, ['pendiente', 'en_progreso']) && $asignacion->token;
                                                    $link = $canView ? route('encuesta.show', ['token' => $asignacion->token]) : '#';
                                                @endphp
                                                <li class="p-4 border rounded-lg dark:border-gray-600 shadow-sm flex flex-col sm:flex-row justify-between items-start bg-white dark:bg-gray-700">
                                                    {{-- Columna 1: Información --}}
                                                    <div class="flex-1 min-w-0 pr-4">
                                                        <div class="font-medium text-gray-900 dark:text-gray-100">
                                                            {{ $asignacion->cuestionario->nombre ?? 'N/A' }} <span class="text-xs text-gray-500">({{ $asignacion->contrato->nombre ?? 'N/A Contrato' }})</span>
                                                        </div>
                                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Asignado: {{ $asignacion->fecha_asignacion->format('d/m/Y') }}</div>

                                                        {{-- Input oculto para copiar el enlace --}}
                                                        <input type="text" id="link-{{ $asignacion->id }}" value="{{ $link }}" class="sr-only">

                                                    </div>
                                                    {{-- Columna 2: Progreso --}}
                                                    <div class="w-full sm:w-1/3 flex flex-col items-start sm:items-end mt-3 sm:mt-0">
                                                          <span class="px-2 py-0.5 text-xs font-semibold rounded-full capitalize mb-1"
                                                                @class([
                                                                    'bg-green-100 text-green-800' => $asignacion->estado == 'completado',
                                                                    'bg-yellow-100 text-yellow-800' => $asignacion->estado == 'en_progreso',
                                                                    'bg-red-100 text-red-800' => $asignacion->estado == 'pendiente',
                                                                ])>
                                                                {{ $asignacion->estado }}
                                                            </span>
                                                        <div class="w-full bg-gray-200 rounded-full h-1.5 mt-1 dark:bg-gray-600">
                                                            <div class="{{ $color }} h-1.5 rounded-full" style="width: {{ $progreso }}%"></div>
                                                        </div>
                                                        <span class="text-xs text-gray-500 dark:text-gray-400 mt-1 block mb-2">{{ $progreso }}% ({{ $asignacion->progreso_actual }}/{{ $asignacion->total_preguntas }})</span>
                                                    </div>
                                                    {{-- Columna 3: Acciones --}}
                                                    <div class="w-full sm:w-auto sm:ml-4 flex items-center space-x-2 justify-start sm:justify-end mt-3 sm:mt-0">
                                                        {{-- Botón Visualizar Encuesta --}}
                                                        <a href="{{ $link }}" target="_blank"
                                                           class="text-xs font-semibold px-2 py-1 rounded transition-colors duration-150
                                                             @if($canView)
                                                                 text-blue-600 bg-blue-50 hover:bg-blue-100 dark:bg-blue-900/50 dark:text-blue-300
                                                             @else
                                                                 text-gray-500 bg-gray-100 cursor-not-allowed pointer-events-none opacity-50
                                                             @endif"
                                                           title="{{ $canView ? 'Abrir enlace de respuesta' : 'Encuesta completada o token ausente' }}">
                                                            <i class="fas fa-external-link-alt mr-1"></i> Visualizar
                                                        </a>

                                                        {{-- Botón Copiar Enlace --}}
                                                        <button type="button"
                                                                @if(!$canView) disabled @endif
                                                                @click="
                                                                    const linkInput = document.getElementById('link-{{ $asignacion->id }}');
                                                                    linkInput.select();
                                                                    linkInput.setSelectionRange(0, 99999);
                                                                    try {
                                                                        document.execCommand('copy');
                                                                        Swal.fire({toast:true, position:'top-end', title:'¡Enlace copiado!', icon:'success', timer:1500, showConfirmButton:false, timerProgressBar:true});
                                                                    } catch (err) {
                                                                        console.error('Error al copiar:', err);
                                                                        Swal.fire({toast:true, position:'top-end', title:'Error al copiar', icon:'error', timer:2000, showConfirmButton:false});
                                                                    }
                                                                    window.getSelection().removeAllRanges(); // Deseleccionar
                                                                  "
                                                                class="text-xs font-semibold px-2 py-1 rounded transition-colors duration-150
                                                                  @if($canView)
                                                                      text-gray-600 bg-gray-100 hover:bg-gray-200 dark:bg-gray-600 dark:text-gray-300 dark:hover:bg-gray-500
                                                                  @else
                                                                       text-gray-500 bg-gray-100 cursor-not-allowed pointer-events-none opacity-50
                                                                  @endif"
                                                                title="Copiar enlace manual">
                                                            <i class="fas fa-copy"></i>
                                                        </button>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        </tbody> {{-- Fin del tbody del empleado --}}
                    @endforeach

                </table>
            @endif
        </div>
    </div>

    {{-- Estilos comunes (solo los necesarios para esta vista) --}}
    @push('styles')
        <style>
            /* Estilos para que el select funcione correctamente */
            .form-select {
                @apply block w-full px-4 py-2.5 bg-white dark:bg-gray-700/50 border border-gray-300 dark:border-gray-600 rounded-lg text-sm shadow-sm placeholder-gray-400 dark:placeholder-gray-500 text-gray-900 dark:text-gray-100 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/50 dark:focus:ring-blue-500/60 disabled:bg-gray-100 disabled:text-gray-500 dark:disabled:bg-gray-800 dark:disabled:text-gray-400 transition duration-150 ease-in-out;
                /* Estilos de la flecha CSS para Select (sin sintaxis Blade) */
                background-image: url("data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath fill='%236b7280' d='M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z'/%3E%3C/svg%3E");
                background-position: right 0.75rem center; background-size: 1.25em 1.25em;
                appearance: none;
            }
            .dark .form-select { background-image: url("data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath fill='%239ca3af' d='M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z'/%3E%3C/svg%3E"); }
            .form-label { @apply block mb-2 text-sm font-medium text-gray-900 dark:text-gray-100; }
            .btn { @apply inline-flex items-center justify-center px-6 py-3 text-sm font-semibold leading-5 border rounded-lg shadow-sm transition-all duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-60 disabled:cursor-not-allowed dark:focus:ring-offset-gray-800; }
            .btn-secondary { @apply text-gray-700 bg-white border-gray-300 hover:bg-gray-50 active:bg-gray-100 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-600 dark:active:bg-gray-500; }
            /* Transición Alpine */
            [x-collapse] { overflow: hidden; }
            [x-collapse]:not([x-cloak]) { transition: height 300ms cubic-bezier(0.4, 0, 0.2, 1); }
            [x-cloak] { display: none !important; }
        </style>
    @endpush

    @push('scripts')
        {{-- SweetAlert2 (Necesario para el botón de copiar) --}}
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        {{-- Alpine.js (Asume que ya está cargado en el layout) --}}
    @endpush

@endsection

