@extends('dashboard') {{-- Usar el layout principal --}}

@section('title', 'Gestionar Empleados NOM-035') {{-- Título específico --}}

@section('content')
    <div class="space-y-10"> {{-- Mayor espacio entre secciones --}}

        {{-- Encabezado --}}
        <div class="flex flex-col sm:flex-row justify-between items-center pb-5 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-3xl font-semibold text-gray-800 dark:text-gray-100 museo-500 mb-3 sm:mb-0 flex items-center">
                <i class="fas fa-users-cog mr-4 text-purple-500"></i> Gestión de Empleados (NOM-035)
            </h2>
            <a href="{{ route('nom035.index') }}" class="btn btn-secondary w-full sm:w-auto">
                <i class="fas fa-arrow-left mr-2"></i> Volver al Dashboard NOM-035
            </a>
        </div>

        {{-- Mensajes generales y errores de carga masiva --}}
        @if(session('status'))
            {{-- SweetAlert lo maneja --}}
        @endif
        @if(session('error') || session('warning'))
            <div class="p-4 text-sm {{ session('error') ? 'text-red-800 bg-red-100 dark:bg-red-900 dark:text-red-300 border-red-300 dark:border-red-600' : 'text-yellow-800 bg-yellow-100 dark:bg-yellow-900 dark:text-yellow-300 border-yellow-300 dark:border-yellow-600' }} rounded-lg border flex items-center shadow-md" role="alert">
                <i class="fas {{ session('error') ? 'fa-exclamation-triangle' : 'fa-exclamation-circle' }} mr-3 flex-shrink-0 text-lg"></i>
                <span>{{ session('error') ?: session('warning') }}</span>
            </div>
        @endif
        @if(session('empleados_fallidos'))
            <div class="bg-red-50 dark:bg-red-900/50 border-l-4 border-red-500 dark:border-red-600 p-5 rounded-r-lg shadow-md" role="alert">
                <div class="flex items-center mb-3">
                    <div class="flex-shrink-0"> <i class="fas fa-times-circle text-red-600 dark:text-red-400 text-2xl"></i> </div>
                    <div class="ml-4">
                        <h3 class="text-md font-semibold text-red-800 dark:text-red-200 museo-500">Errores en la Carga Masiva:</h3>
                        <p class="mt-1 text-sm text-red-700 dark:text-red-300"> {{ count(session('empleados_fallidos')) }} fila(s) no cargadas. Revisa los detalles.</p>
                    </div>
                </div>
                <details class="mt-4 group bg-white dark:bg-gray-800 rounded border dark:border-gray-700 shadow-inner">
                    <summary class="px-4 py-2 text-xs text-red-700 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-800/50 cursor-pointer font-medium list-none flex justify-between items-center rounded-t">
                        <span>Mostrar/Ocultar detalles de errores</span> <i class="fas fa-chevron-down ml-1 text-xs transform group-open:rotate-180 transition-transform"></i>
                    </summary>
                    <ul class="text-sm max-h-60 overflow-y-auto p-4 space-y-3">
                        @foreach(session('empleados_fallidos') as $fallido)
                            <li class="border-b dark:border-gray-600 pb-3 last:border-b-0">
                                <strong class="block text-red-800 dark:text-red-300 text-xs uppercase tracking-wide">Fila CSV {{ $fallido['fila'] }}:</strong>
                                <p class="text-red-700 dark:text-red-400 text-sm mt-1">{{ implode(' ', $fallido['errores']) }}</p>
                                <pre class="mt-2 text-xs bg-gray-100 dark:bg-gray-700 p-2 rounded overflow-x-auto text-gray-600 dark:text-gray-300 font-mono">{{ json_encode($fallido['datos'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                            </li>
                        @endforeach
                    </ul>
                </details>
            </div>
        @endif


        {{-- Sección de Subida Masiva desde CSV --}}
        <div class="bg-white dark:bg-gray-800 shadow-xl rounded-xl p-6 lg:p-8 border border-gray-200 dark:border-gray-700">
            <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-4 museo-500 flex items-center border-b border-gray-200 dark:border-gray-700 pb-4">
                <i class="fas fa-file-csv mr-3 text-green-500 text-2xl"></i> Subida Masiva desde CSV
            </h3>
            <p class="text-base text-gray-600 dark:text-gray-400 mb-8">
                Selecciona la empresa y sube un archivo CSV (.csv o .txt) delimitado por comas o punto y coma. La primera fila debe ser la cabecera. Columnas requeridas: <code>nombre</code>, <code>apellido_paterno</code>. Opcionales: <code>clave</code> (única por empresa), <code>apellido_materno</code>, <code>email</code>, <code>puesto</code>, <code>departamento</code>, <code>centro_trabajo</code>, <code>fecha_ingreso</code> (YYYY-MM-DD o DD/MM/YYYY).
            </p>
            <form action="{{ route('nom035.empleados.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
                    <div>
                        <label for="empresa_id_upload" class="form-label">Empresa para la carga <span class="text-red-500">*</span></label>
                        <select name="empresa_id_upload" id="empresa_id_upload" required class="form-select @error('empresa_id_upload') input-error @enderror">
                            <option value="" disabled selected>Selecciona una empresa...</option>
                            @foreach($empresas ?? [] as $id => $nombre)
                                <option value="{{ $id }}" {{ old('empresa_id_upload') == $id ? 'selected' : '' }}>{{ $nombre }}</option>
                            @endforeach
                        </select>
                        @error('empresa_id_upload') <p class="form-error-message">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="archivo_empleados" class="form-label">Archivo CSV (.csv, .txt) <span class="text-red-500">*</span></label>
                        <label for="archivo_empleados" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 transition duration-150 ease-in-out @error('archivo_empleados') border-red-500 ring-1 ring-red-500 @enderror">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6 text-center"> <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 dark:text-gray-500 mb-3"></i> <p id="file-chosen-text-visual" class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Haz clic para subir</span> o arrastra y suelta</p> <p class="text-xs text-gray-500 dark:text-gray-400">CSV o TXT (MAX. 5MB)</p> </div>
                            <input type="file" name="archivo_empleados" id="archivo_empleados" required accept=".csv,.txt" class="hidden" onchange="document.getElementById('file-chosen-text-visual').innerHTML = this.files[0] ? `<span class='font-semibold text-blue-600 dark:text-blue-400'>${this.files[0].name}</span>` : '<span class=\'font-semibold\'>Haz clic para subir</span> o arrastra y suelta';"/>
                        </label>
                        @error('archivo_empleados') <p class="form-error-message mt-2">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="flex justify-end pt-6 border-t border-gray-200 dark:border-gray-700 mt-8">
                    <button type="submit" class="btn btn-primary bg-green-600 hover:bg-green-700 focus:ring-green-500 active:bg-green-800 dark:bg-green-500 dark:hover:bg-green-600 dark:focus:ring-green-700 shadow-lg hover:shadow-md focus:ring-offset-2 text-base px-6 py-3"> <i class="fas fa-upload mr-2"></i> Subir y Procesar Archivo </button>
                </div>
            </form>
        </div>

        {{-- Sección Agregar Individualmente (Colapsable con Alpine) --}}
        <div x-data="{ open: {{ ($errors->any() && old('_input_origin') == 'individual') ? 'true' : 'false' }} }" class="bg-white dark:bg-gray-800 shadow-xl rounded-xl p-6 lg:p-8 border border-gray-200 dark:border-gray-700">
            {{-- Encabezado con botón para mostrar/ocultar --}}
            <div class="flex justify-between items-center mb-6 pb-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100 museo-500 flex items-center">
                    <i class="fas fa-user-plus mr-3 text-purple-500 text-lg"></i> Agregar Empleado Individualmente
                </h3>
                <button @click="open = !open" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm font-medium focus:outline-none p-1 rounded inline-flex items-center transition-colors">
                    <span x-text="open ? 'Ocultar Formulario' : 'Mostrar Formulario'"></span>
                    <i class="fas ml-1.5 transition-transform duration-200 inline-block" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                </button>
            </div>

            {{-- Formulario Individual (Rediseñado) --}}
            <form x-show="open" x-collapse method="POST" action="{{ route('nom035.empleados.store') }}" class="space-y-8 mt-6">
                @csrf
                <input type="hidden" name="_input_origin" value="individual">

                {{-- Sección 1: Datos Principales --}}
                <div>
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-5">Datos Principales</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                        <div>
                            <label for="empresa_id_single_new" class="form-label">Empresa <span class="text-red-500">*</span></label>
                            <select name="empresa_id" id="empresa_id_single_new" required class="form-select @error('empresa_id') input-error @enderror">
                                <option value="" disabled selected>Selecciona la empresa...</option>
                                @foreach($empresas ?? [] as $id => $nombre)
                                    <option value="{{ $id }}" {{ old('empresa_id') == $id ? 'selected' : '' }}>{{ $nombre }}</option>
                                @endforeach
                            </select>
                            @error('empresa_id') <p class="form-error-message">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="clave_individual_new" class="form-label">Clave Empleado <span class="text-gray-400 text-xs">(Opcional)</span></label>
                            <div class="relative">
                                <div class="input-icon"> <i class="fas fa-key"></i> </div>
                                <input type="text" name="clave" id="clave_individual_new" value="{{ old('clave') }}" placeholder="Única por empresa" class="form-input with-icon @error('clave') input-error @enderror">
                            </div>
                            @error('clave') <p class="form-error-message">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Sección 2: Nombre Completo --}}
                <div class="pt-8 border-t border-gray-200 dark:border-gray-700">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-5">Nombre Completo</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-5">
                        <div>
                            <label for="nombre_individual_new" class="form-label">Nombre(s) <span class="text-red-500">*</span></label>
                            <input type="text" name="nombre" id="nombre_individual_new" value="{{ old('nombre') }}" required class="form-input @error('nombre') input-error @enderror">
                            @error('nombre') <p class="form-error-message">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="apellido_paterno_individual_new" class="form-label">Apellido Paterno <span class="text-red-500">*</span></label>
                            <input type="text" name="apellido_paterno" id="apellido_paterno_individual_new" value="{{ old('apellido_paterno') }}" required class="form-input @error('apellido_paterno') input-error @enderror">
                            @error('apellido_paterno') <p class="form-error-message">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="apellido_materno_individual_new" class="form-label">Apellido Materno</label>
                            <input type="text" name="apellido_materno" id="apellido_materno_individual_new" value="{{ old('apellido_materno') }}" class="form-input @error('apellido_materno') input-error @enderror">
                            @error('apellido_materno') <p class="form-error-message">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Sección 3: Información Adicional --}}
                <div class="pt-8 border-t border-gray-200 dark:border-gray-700">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-5">Información Adicional <span class="text-gray-400 text-xs">(Opcional)</span></h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5 mb-6">
                        <div>
                            <label for="email_individual_new" class="form-label">Correo Electrónico</label>
                            <div class="relative">
                                <div class="input-icon"> <i class="fas fa-envelope"></i> </div>
                                <input type="email" name="email" id="email_individual_new" value="{{ old('email') }}" placeholder="empleado@empresa.com" class="form-input with-icon @error('email') input-error @enderror">
                            </div>
                            @error('email') <p class="form-error-message">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="fecha_ingreso_individual_new" class="form-label">Fecha Ingreso</label>
                            <input type="date" name="fecha_ingreso" id="fecha_ingreso_individual_new" value="{{ old('fecha_ingreso') }}" class="form-input @error('fecha_ingreso') input-error @enderror">
                            @error('fecha_ingreso') <p class="form-error-message">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-5">
                        <div>
                            <label for="puesto_individual_new" class="form-label">Puesto</label>
                            <input type="text" name="puesto" id="puesto_individual_new" value="{{ old('puesto') }}" class="form-input @error('puesto') input-error @enderror">
                            @error('puesto') <p class="form-error-message">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="departamento_individual_new" class="form-label">Departamento</label>
                            <input type="text" name="departamento" id="departamento_individual_new" value="{{ old('departamento') }}" class="form-input @error('departamento') input-error @enderror">
                            @error('departamento') <p class="form-error-message">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="centro_trabajo_individual_new" class="form-label">Centro Trabajo</label>
                            <input type="text" name="centro_trabajo" id="centro_trabajo_individual_new" value="{{ old('centro_trabajo') }}" placeholder="Ej: Oficina Central" class="form-input @error('centro_trabajo') input-error @enderror">
                            @error('centro_trabajo') <p class="form-error-message">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Botones Formulario Individual --}}
                <div class="flex justify-end pt-8 mt-8 border-t border-gray-200 dark:border-gray-700 space-x-4">
                    <button type="button" @click="open = false; $el.closest('form').reset();" class="btn btn-secondary text-base px-6 py-3"> <i class="fas fa-times mr-2"></i> Cancelar </button>
                    <button type="submit" class="btn btn-primary bg-purple-600 hover:bg-purple-700 focus:ring-purple-500 active:bg-purple-800 dark:bg-purple-500 dark:hover:bg-purple-600 dark:focus:ring-purple-700 shadow-lg hover:shadow-md focus:ring-offset-2 text-base px-6 py-3">
                        <i class="fas fa-user-plus mr-2"></i> Guardar Empleado
                    </button>
                </div>
            </form>
        </div>

        {{-- Lista de Empleados (Placeholder) --}}
        <div class="bg-white dark:bg-gray-800 shadow-xl rounded-xl p-6 lg:p-8 border border-gray-200 dark:border-gray-700 mt-8">
            {{-- (Contenido sin cambios) --}}
            <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-6 museo-500 border-b border-gray-200 dark:border-gray-700 pb-4 flex items-center">
                <i class="fas fa-users mr-3 text-gray-400 text-lg"></i> Empleados Registrados
            </h3>
            <div class="text-center py-12 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-lg">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <h3 class="mt-2 text-sm font-semibold text-gray-900 dark:text-gray-200">Próximamente</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Aquí se mostrará la lista de empleados, con filtros y opciones de edición.</p>
            </div>
        </div>

    </div>

    {{-- Estilos reutilizables --}}
    @push('styles')
        <style>
            /* Estilos base para inputs, selects y textareas */
            .form-input, .form-select, .form-textarea {
                @apply block w-full px-4 py-2 bg-white dark:bg-gray-700/50 border border-gray-300 dark:border-gray-600 rounded-lg text-sm shadow-sm placeholder-gray-400 dark:placeholder-gray-500 text-gray-900 dark:text-white
                focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/50 dark:focus:ring-blue-500/60
                disabled:bg-gray-100 disabled:text-gray-500 dark:disabled:bg-gray-800 dark:disabled:text-gray-400
                transition duration-150 ease-in-out; /* Padding aumentado a py-2.5 */
            }
            .form-input.with-icon { @apply pl-10; } /* Padding para icono */
            .input-icon { @apply pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 dark:text-gray-500 w-5 h-5; }

            /* Estilo específico para selects */
            .form-select {
                @apply pr-10 appearance-none bg-no-repeat bg-right;
                background-image: url('data:image/svg+xml;charset=utf-8,%3Csvg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20"%3E%3Cpath stroke="%236b7280" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 8l4 4 4-4"/%3E%3C/svg%3E');
                background-position: right 0.75rem center;
                background-size: 1.25em 1.25em;
            }
            .dark .form-select {
                background-image: url('data:image/svg+xml;charset=utf-8,%3Csvg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20"%3E%3Cpath stroke="%239ca3af" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 8l4 4 4-4"/%3E%3C/svg%3E');
            }

            /* Estilo para checkboxes */
            .form-checkbox { @apply h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 transition duration-150 ease-in-out; }
            .input-error { @apply border-red-500 ring-1 ring-red-500 focus:border-red-500 focus:ring-red-500; }
            .form-error-message { @apply mt-2 text-xs text-red-600 dark:text-red-400; }
            .form-label { @apply block mb-2 text-sm font-medium text-gray-900 dark:text-gray-100; } /* Ajustado color y mb */

            /* Botones */
            .btn { @apply inline-flex items-center justify-center px-6 py-3 text-sm font-semibold leading-5 border rounded-lg shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-60 disabled:cursor-not-allowed dark:focus:ring-offset-gray-800 transition-all duration-200 ease-in-out; } /* Padding aumentado, sombra, font-semibold */
            .btn-primary { @apply text-white bg-blue-600 border-transparent hover:bg-blue-700 active:bg-blue-800 focus:ring-blue-500; }
            .btn-secondary { @apply text-gray-700 bg-white border-gray-300 hover:bg-gray-50 active:bg-gray-100 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-600 dark:active:bg-gray-500; }

            /* Colapso Alpine */
            [x-collapse] { overflow: hidden; }
            [x-collapse]:not([x-cloak]) { transition: height 300ms cubic-bezier(0.4, 0, 0.2, 1); }
            [x-cloak] { display: none !important; }
        </style>
    @endpush

    @push('scripts')
        {{-- Incluir SweetAlert2 y Alpine.js --}}
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        {{-- <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script> --}}

        {{-- Script para SweetAlert2 --}}
        @if (session('status') && !$errors->any())
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const alertKey = 'swal_empleado_status_{{ substr(sha1(session('status')), 0, 8) }}';
                    if (!sessionStorage.getItem(alertKey)) {
                        Swal.fire({
                            icon: 'success', title: '¡Éxito!', text: "{{ session('status') }}",
                            toast: true, position: 'top-end', showConfirmButton: false, timer: 4000, timerProgressBar: true
                        });
                        sessionStorage.setItem(alertKey, 'true');
                    }
                });
            </script>
        @endif

        {{-- Script para deshabilitar botones al enviar y actualizar nombre de archivo --}}
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Formulario individual
                const formIndividual = document.querySelector('form[action="{{ route('nom035.empleados.store') }}"]');
                if (formIndividual) {
                    const submitButton = formIndividual.querySelector('button[type="submit"]');
                    if (submitButton) {
                        formIndividual.addEventListener('submit', function() {
                            submitButton.disabled = true;
                            submitButton.innerHTML = `<i class="fas fa-spinner fa-spin mr-2"></i> Guardando...`;
                            setTimeout(() => { if (submitButton.disabled) { submitButton.disabled = false; submitButton.innerHTML = `<i class="fas fa-user-plus mr-2"></i> Guardar Empleado`; }}, 10000);
                        });
                    }
                }
                // Formulario masivo
                const formMasivo = document.querySelector('form[action="{{ route('nom035.empleados.upload') }}"]');
                if (formMasivo) {
                    const submitButtonMasivo = formMasivo.querySelector('button[type="submit"]');
                    const fileInput = formMasivo.querySelector('#archivo_empleados');
                    const fileChosenTextVisual = formMasivo.querySelector('#file-chosen-text-visual');

                    if (fileInput && fileChosenTextVisual) {
                        fileInput.addEventListener('change', function() {
                            fileChosenTextVisual.innerHTML = this.files.length > 0
                                ? `<span class="font-semibold text-blue-600 dark:text-blue-400 truncate block">${this.files[0].name}</span>` // Resaltar nombre y truncar si es largo
                                : '<span class="font-semibold">Haz clic para subir</span> o arrastra y suelta';
                            this.closest('label').classList.toggle('bg-blue-50', this.files.length > 0);
                            this.closest('label').classList.toggle('dark:bg-blue-900/20', this.files.length > 0);
                            this.closest('label').classList.toggle('border-blue-300', this.files.length > 0); // Borde azul al seleccionar
                            this.closest('label').classList.toggle('dark:border-blue-700', this.files.length > 0);
                        });
                    }

                    if (submitButtonMasivo) {
                        formMasivo.addEventListener('submit', function() {
                            submitButtonMasivo.disabled = true;
                            submitButtonMasivo.innerHTML = `<i class="fas fa-spinner fa-spin mr-2"></i> Procesando Archivo...`;
                            setTimeout(() => { if (submitButtonMasivo.disabled) { submitButtonMasivo.disabled = false; submitButtonMasivo.innerHTML = `<i class="fas fa-upload mr-2"></i> Subir y Procesar Archivo`; }}, 30000);
                        });
                    }
                }
            });
        </script>
    @endpush

@endsection

