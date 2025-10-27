@extends('dashboard')

@section('title', 'Asignar Encuestas NOM-035')

@section('content')
    <div class="space-y-8">

        {{-- Encabezado --}}
        <div class="flex flex-col sm:flex-row justify-between items-center pb-5 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-3xl font-semibold text-gray-800 dark:text-gray-100 museo-500 mb-3 sm:mb-0 flex items-center">
                <i class="fas fa-tasks mr-4 text-cyan-500"></i> Asignación de Encuestas
            </h2>
            <a href="{{ route('nom035.index') }}" class="btn btn-secondary w-full sm:w-auto">
                <i class="fas fa-arrow-left mr-2"></i> Regresar a Dashboard
            </a>
        </div>

        {{-- Mensajes y Alertas (Rediseño de contenedores) --}}
        @if(session('error') || session('warning'))
            <div class="p-4 text-sm {{ session('error') ? 'text-red-800 bg-red-100 border-red-300' : 'text-yellow-800 bg-yellow-100 border-yellow-300' }} rounded-lg border shadow-sm dark:border-{{ session('error') ? 'red' : 'yellow' }}-600 dark:bg-gray-800" role="alert">
                <i class="fas {{ session('error') ? 'fa-exclamation-triangle' : 'fa-exclamation-circle' }} mr-3 flex-shrink-0 text-lg"></i>
                <span>{{ session('error') ?: session('warning') }}</span>
            </div>
        @endif

        {{-- Card de Selección Progresiva (Paso 1 y 2) --}}
        <div class="bg-white dark:bg-gray-800 shadow-xl rounded-xl p-6 lg:p-8 border border-gray-200 dark:border-gray-700">
            <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-6 museo-500 border-b border-gray-200 dark:border-gray-700 pb-3">
                Paso 1: Seleccionar Contrato Destino
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Selector de Empresa --}}
                <div>
                    <label for="select_empresa_id" class="form-label">1. Selecciona la Empresa</label>
                    <div class="relative mt-1.5">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <i class="fas fa-building text-gray-400 dark:text-gray-500 w-5 h-5"></i>
                        </div>
                        <select id="select_empresa_id" onchange="loadContratos(this.value)" class="form-select pl-10">
                            <option value="" disabled {{ !request()->query('empresa_id') ? 'selected' : '' }}>Selecciona una empresa...</option>
                            @foreach($empresas ?? [] as $id => $nombre)
                                <option value="{{ $id }}"
                                    {{ (request()->query('empresa_id') == $id) || (isset($contratoSeleccionado) && $contratoSeleccionado->empresa_id == $id) ? 'selected' : '' }}>
                                    {{ $nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                {{-- Selector de Contrato --}}
                <div>
                    <label for="select_contrato_id" class="form-label">2. Selecciona el Contrato Activo</label>
                    <div class="relative mt-1.5">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <i class="fas fa-file-contract text-gray-400 dark:text-gray-500 w-5 h-5"></i>
                        </div>
                        <select id="select_contrato_id" name="contrato_id_selector" onchange="loadAssignmentData(this.value)" class="form-select pl-10"
                            {{ !$contratos->count() ? 'disabled' : '' }}>
                            <option value="" disabled {{ !request()->query('contrato_id') ? 'selected' : '' }}>
                                {{ !$contratos->count() ? 'Selecciona una empresa primero...' : 'Selecciona un contrato...' }}
                            </option>
                            @if($contratos->isNotEmpty())
                                @foreach($contratos as $id => $nombre)
                                    <option value="{{ $id }}" {{ request()->query('contrato_id') == $id ? 'selected' : '' }}>{{ $nombre }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
            </div>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-5 pt-4 border-t border-gray-100 dark:border-gray-700">
                **Nota:** La lista de contratos activos se actualizará al seleccionar una empresa.
            </p>
        </div>

        {{-- Formulario de Asignación (Paso 3 y 4) --}}
        @if(isset($contratoSeleccionado) && $contratoSeleccionado)
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-xl p-6 lg:p-8 border border-gray-200 dark:border-gray-700">
                <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-5 museo-500 border-b border-gray-200 dark:border-gray-700 pb-3">
                    <i class="fas fa-user-check mr-2 text-blue-600"></i> Asignar Encuesta para: <span class="text-blue-600 dark:text-blue-400">{{ $contratoSeleccionado->nombre }}</span>
                </h3>

                <form method="POST" action="{{ route('nom035.asignaciones.store') }}" class="space-y-8 mt-6">
                    @csrf
                    <input type="hidden" name="contrato_id" value="{{ $contratoSeleccionado->id }}">

                    {{-- Sección 1: Selección de Cuestionario --}}
                    <fieldset class="border border-gray-300 dark:border-gray-600 rounded-lg p-6 pt-4 shadow-sm bg-gray-50/30 dark:bg-gray-700/20">
                        <legend class="text-lg font-semibold text-gray-900 dark:text-gray-100 px-2 -ml-2 museo-500">1. Selecciona Cuestionario(s) <span class="text-red-500">*</span></legend>
                        @if($cuestionarios->isEmpty())
                            <div class="text-sm text-red-600 dark:text-red-400 italic bg-red-50 dark:bg-red-900/50 p-3 rounded border border-red-200 dark:border-red-700">
                                <i class="fas fa-exclamation-triangle mr-2"></i> Este contrato no tiene cuestionarios NOM-035 asociados.
                            </div>
                        @else
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 max-h-40 overflow-y-auto border border-gray-200 dark:border-gray-700 rounded-lg p-4 bg-white dark:bg-gray-700/50 shadow-inner mt-4">
                                @foreach($cuestionarios as $cuestionario)
                                    <label for="cuestionario_{{ $cuestionario->id }}"
                                           class="relative flex items-center p-4 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer transition-all duration-150
                                      hover:bg-gray-50 dark:hover:bg-gray-600 has-[:checked]:bg-blue-50 has-[:checked]:border-blue-500
                                      has-[:checked]:ring-2 has-[:checked]:ring-blue-500 dark:has-[:checked]:bg-gray-700 dark:has-[:checked]:border-blue-500
                                      group">
                                        <input id="cuestionario_{{ $cuestionario->id }}" name="cuestionario_ids[]" type="checkbox" value="{{ $cuestionario->id }}" {{ (is_array(old('cuestionario_ids')) && in_array($cuestionario->id, old('cuestionario_ids'))) ? 'checked' : '' }}
                                        class="form-checkbox">
                                        <label for="cuestionario_{{ $cuestionario->id }}" class="ml-3 block text-sm font-medium text-gray-800 dark:text-gray-200 cursor-pointer">
                                            {{ $cuestionario->nombre }} <span class="text-xs text-gray-500 dark:text-gray-400">({{ $cuestionario->codigo }})</span>
                                        </label>
                                        <i class="fas fa-check-circle text-blue-600 dark:text-blue-400 absolute top-3 right-3 hidden group-has-[:checked]:block"></i>
                                    </label>
                                @endforeach
                            </div>
                            @error('cuestionario_ids') <p class="form-error-message mt-2">{{ $message }}</p> @enderror
                            @error('cuestionario_ids.*') <p class="form-error-message mt-2">Al menos uno de los cuestionarios seleccionados no es válido.</p> @enderror
                        @endif
                    </fieldset>

                    {{-- Sección 2: Selección de Empleados --}}
                    <fieldset class="border-t border-gray-200 dark:border-gray-700 pt-8">
                        <legend class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 museo-500">2. Selecciona los Empleados <span class="text-red-500">*</span></legend>
                        @if($empleados->isEmpty())
                            <div class="text-sm text-yellow-700 dark:text-yellow-300 italic bg-yellow-50 dark:bg-yellow-900/50 p-3 rounded border border-yellow-200 dark:border-yellow-700">
                                <i class="fas fa-info-circle mr-2"></i> No hay empleados disponibles (ya sea que no hay, o todos están asignados).
                            </div>
                        @else
                            <div class="flex items-center mb-4">
                                <input type="checkbox" id="select-all-empleados" onclick="toggleSelectAll(this)" class="form-checkbox mr-2">
                                <label for="select-all-empleados" class="text-sm font-medium text-gray-700 dark:text-gray-300 cursor-pointer">Seleccionar Todos / Ninguno</label>
                            </div>
                            <div class="max-h-80 overflow-y-auto border border-gray-300 dark:border-gray-600 rounded-lg p-4 space-y-3 bg-gray-50 dark:bg-gray-700/50 shadow-inner">
                                @foreach($empleados as $empleado)
                                    <label for="empleado_{{ $empleado->id }}"
                                           class="flex items-center p-3 border border-gray-200 dark:border-gray-600 rounded-lg cursor-pointer bg-white dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 transition-all duration-150
                                      has-[:checked]:bg-blue-50 has-[:checked]:border-blue-400 dark:has-[:checked]:bg-gray-600 dark:has-[:checked]:border-blue-500">
                                        <input id="empleado_{{ $empleado->id }}" name="empleado_ids[]" type="checkbox" value="{{ $empleado->id }}"
                                               {{ (is_array(old('empleado_ids')) && in_array($empleado->id, old('empleado_ids'))) ? 'checked' : '' }}
                                               class="form-checkbox empleado-checkbox">
                                        <label for="empleado_{{ $empleado->id }}" class="ml-3 block text-sm font-medium text-gray-800 dark:text-gray-200 cursor-pointer flex justify-between w-full">
                                            <span>{{ $empleado->apellido_paterno }} {{ $empleado->apellido_materno }}, {{ $empleado->nombre }}</span>
                                            <span class="text-xs text-gray-500 dark:text-gray-400 ml-3">{{ $empleado->clave ? '('.$empleado->clave.')' : '' }}</span>
                                        </label>
                                    </label>
                                @endforeach
                            </div>
                            @error('empleado_ids') <p class="form-error-message mt-2">{{ $message }}</p> @enderror
                            @error('empleado_ids.*') <p class="form-error-message mt-2">Al menos uno de los empleados seleccionados no es válido.</p> @enderror
                        @endif
                    </fieldset>

                    {{-- Botón de Asignar --}}
                    <div class="flex justify-end pt-8 mt-8 border-t border-gray-200 dark:border-gray-700">
                        <button id="assign-button" type="submit" class="btn btn-primary bg-cyan-600 hover:bg-cyan-700 focus:ring-cyan-500 active:bg-cyan-800 dark:bg-cyan-500 dark:hover:bg-cyan-600 dark:focus:ring-cyan-700 shadow-lg hover:shadow-md focus:ring-offset-2 text-base px-6 py-3"
                                @if($cuestionarios->isEmpty() || $empleados->isEmpty()) disabled @endif>
                            <i class="fas fa-paper-plane mr-2"></i> Asignar Encuesta(s)
                        </button>
                    </div>
                </form>
            </div>
        @endif

    </div>

    {{-- Estilos (CORREGIDOS) --}}
    @push('styles')
        <style>
            /* Estilos base para inputs, selects y textareas */
            .form-input, .form-select, .form-textarea {
                @apply block w-full px-4 py-2.5 bg-white dark:bg-gray-700/50 border border-gray-300 dark:border-gray-600 rounded-lg text-sm shadow-sm placeholder-gray-400 dark:placeholder-gray-500 text-gray-900 dark:text-gray-100
                focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/50 dark:focus:ring-blue-500/60
                disabled:bg-gray-100 disabled:text-gray-500 dark:disabled:bg-gray-800 dark:disabled:text-gray-400
                transition duration-150 ease-in-out;
            }
            .form-input.with-icon { @apply pl-10; }
            .input-icon { @apply pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 dark:text-gray-500 w-5 h-5; }

            /* Estilo específico para selects */
            .form-select {
                @apply pr-10 appearance-none bg-no-repeat bg-right;
                /* CORRECCIÓN: Se usa la sintaxis CSS estándar para la URL, sin @apply */
                background-image: url("data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath fill='%236b7280' d='M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z'/%3E%3C/svg%3E");
                background-position: right 0.75rem center;
                background-size: 1.25em 1.25em;
            }
            .dark .form-select {
                background-image: url("data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath fill='%239ca3af' d='M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z'/%3E%3C/svg%3E");
            }

            .form-checkbox { @apply h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 transition duration-150 ease-in-out; }
            .form-radio { @apply h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 transition duration-150 ease-in-out; }

            .input-error { @apply border-red-500 ring-1 ring-red-500 focus:border-red-500 focus:ring-red-500; }
            .form-error-message { @apply mt-2 text-xs text-red-600 dark:text-red-400; }
            .form-label { @apply block mb-2 text-sm font-medium text-gray-900 dark:text-gray-100; }

            /* Botones */
            .btn { @apply inline-flex items-center justify-center px-6 py-3 text-sm font-semibold leading-5 border rounded-lg shadow-sm transition-all duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-60 disabled:cursor-not-allowed dark:focus:ring-offset-gray-800; }
            .btn-primary { @apply text-white bg-blue-600 border-transparent hover:bg-blue-700 active:bg-blue-800 focus:ring-blue-500; }
            .btn-secondary { @apply text-gray-700 bg-white border-gray-300 hover:bg-gray-50 active:bg-gray-100 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-600 dark:active:bg-gray-500; }

            /* Estilo para tarjetas de radio/checkbox */
            label:has(.form-radio:checked), label:has(.form-checkbox:checked) {
                @apply bg-blue-50 border-blue-500 ring-2 ring-blue-500 dark:bg-gray-700 dark:border-blue-500;
            }
        </style>
    @endpush

    @push('scripts')
        {{-- SweetAlert2 --}}
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        {{-- Script SweetAlert (para status/warning) --}}
        @if (session('status') || session('warning'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const statusMessage = "{{ session('status') }}";
                    const warningMessage = "{{ session('warning') }}";
                    let messageCombined = '';
                    let iconType = 'success';
                    let titleText = '¡Realizado!';
                    if (statusMessage) { messageCombined += statusMessage; }
                    if (warningMessage) {
                        messageCombined += (statusMessage ? '<br><br>' : '') + '<strong class="text-yellow-600 dark:text-yellow-400">Advertencia:</strong> ' + warningMessage;
                        if (!statusMessage) { iconType = 'warning'; titleText = '¡Atención!'; }
                        else { iconType = 'info'; titleText = 'Proceso Completado con Advertencias'; }
                    }
                    const alertKey = 'swal_assign_status_{{ substr(sha1(session('status').session('warning')), 0, 8) }}';
                    if (messageCombined && !sessionStorage.getItem(alertKey)) {
                        Swal.fire({
                            icon: iconType, title: titleText, html: messageCombined,
                            confirmButtonText: 'Entendido', confirmButtonColor: '#2563EB',
                            customClass: { htmlContainer: 'text-left' }
                        });
                        sessionStorage.setItem(alertKey, 'true');
                    }
                });
            </script>
        @endif

        {{-- Script para Select All, carga dinámica y SPINNER DE CARGA --}}
        <script>
            function loadContratos(empresaId) {
                // CORRECCIÓN: Si el usuario selecciona una empresa, recargamos la página CON el empresa_id.
                if (empresaId) {
                    window.location.href = `{{ route('nom035.asignaciones.create') }}?empresa_id=${empresaId}`;
                } else {
                    window.location.href = `{{ route('nom035.asignaciones.create') }}`;
                }
            }

            function loadAssignmentData(contratoId) {
                if (!contratoId) return;
                // CORRECCIÓN: Al seleccionar el CONTRATO, mantenemos el empresa_id.
                const currentEmpresaId = document.getElementById('select_empresa_id').value;
                window.location.href = `{{ route('nom035.asignaciones.create') }}?contrato_id=${contratoId}${currentEmpresaId ? '&empresa_id=' + currentEmpresaId : ''}`;
            }

            function toggleSelectAll(checkbox) {
                document.querySelectorAll('.empleado-checkbox').forEach(empCheckbox => {
                    empCheckbox.checked = checkbox.checked;
                });
            }

            document.addEventListener('DOMContentLoaded', () => {
                const empresaSelect = document.getElementById('select_empresa_id');
                const contratoSelect = document.getElementById('select_contrato_id');
                const selectedEmpresaId = '{{ request()->query('empresa_id') ?? (isset($contratoSeleccionado) ? $contratoSeleccionado->empresa_id : '') }}';

                if (selectedEmpresaId && empresaSelect.value === '') {
                    empresaSelect.value = selectedEmpresaId;
                }

                const assignForm = document.querySelector('form[action="{{ route('nom035.asignaciones.store') }}"]');
                const assignButton = document.getElementById('assign-button');
                const empleadoCheckboxes = document.querySelectorAll('.empleado-checkbox');
                const cuestionarioRadios = document.querySelectorAll('input[name="cuestionario_id"]');

                if (assignButton) {
                    const checkAssignmentReadiness = () => {
                        // CORREGIDO: Usar 'cuestionario_ids[]' (checkboxes)
                        const isCuestionarioSelected = document.querySelector('input[name="cuestionario_ids[]"]:checked');
                        const isEmpleadoSelected = document.querySelector('.empleado-checkbox:checked');
                        return !(isCuestionarioSelected && isEmpleadoSelected);
                    };
                    assignButton.disabled = checkAssignmentReadiness();

                    // Añadir listener a todos los inputs relevantes
                    document.querySelectorAll('input[name="cuestionario_ids[]"], .empleado-checkbox').forEach(input => {
                        input.addEventListener('change', () => {
                            assignButton.disabled = checkAssignmentReadiness();
                        });
                    });

                    // NUEVO: Lógica del spinner de carga al enviar
                    if (assignForm) {
                        assignForm.addEventListener('submit', function() {
                            assignButton.disabled = true;
                            assignButton.innerHTML = `<i class="fas fa-spinner fa-spin mr-2"></i> Asignando, por favor espera...`;

                            // Timeout de seguridad por si falla el envío de correo
                            setTimeout(() => {
                                if (assignButton.disabled) {
                                    assignButton.disabled = false;
                                    assignButton.innerHTML = `<i class="fas fa-paper-plane mr-2"></i> Asignar Encuesta(s)`;
                                }
                            }, 15000); // 15 segundos
                        });
                    }
                }
            });

        </script>
    @endpush
@endsection

