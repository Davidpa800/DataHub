@extends('dashboard') {{-- Usar el layout principal --}}

@section('title', 'Gestión de Contratos NOM-035') {{-- Título específico --}}

@section('content')
    <div class="space-y-10"> {{-- Espacio entre secciones --}}

        {{-- Encabezado y Botón para mostrar/ocultar formulario --}}
        <div x-data="{ showForm: {{ $errors->any() ? 'true' : 'false' }} }" @content-update.window="showForm = $event.detail.showForm" class="bg-white dark:bg-gray-800 shadow-xl rounded-xl p-6 lg:p-8 border border-gray-200 dark:border-gray-700">
            <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4 border-b border-gray-200 dark:border-gray-700 pb-4">
                <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-100 museo-500 flex items-center">
                    <i class="fas fa-file-contract mr-3 text-blue-500"></i> Gestión de Contratos
                </h2>
                <div class="flex items-center space-x-2 w-full sm:w-auto">
                    <a href="{{ route('nom035.index') }}" class="inline-flex items-center justify-center px-6 py-3 text-sm font-semibold leading-5 text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 active:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-600 dark:active:bg-gray-500 transition-all duration-200 ease-in-out w-full sm:w-auto">
                        <i class="fas fa-arrow-left mr-2"></i> Regresar
                    </a>
                    <button @click="showForm = !showForm"
                            class="inline-flex items-center justify-center px-6 py-3 text-sm font-semibold leading-5 text-white bg-blue-600 border border-transparent rounded-lg shadow-sm hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-300 ease-in-out w-full sm:w-auto">
                        <i class="fas transition-transform duration-300 mr-2" :class="showForm ? 'fa-minus-circle rotate-180' : 'fa-plus-circle'"></i>
                        <span x-text="showForm ? 'Ocultar Formulario' : 'Crear Contrato'"></span>
                    </button>
                </div>
            </div>

            {{-- Formulario de Creación (colapsable) --}}
            <div x-show="showForm" x-collapse>
                @if(session('error') && $errors->isEmpty())
                    <div class="mb-5 p-4 text-sm text-red-800 bg-red-100 rounded-lg dark:bg-red-900 dark:text-red-300 border border-red-300 dark:border-red-600 flex items-center" role="alert">
                        <i class="fas fa-exclamation-triangle mr-3 flex-shrink-0"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                {{-- INICIO DEL FORMULARIO REDISEÑADO --}}
                <form method="POST" action="{{ route('nom035.contratos.store') }}" class="space-y-8 mt-6">
                    @csrf

                    {{-- Sección 1: Detalles del Contrato --}}
                    <div class="space-y-6">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 museo-500">1. Detalles del Contrato</h4>
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                            {{-- Empresa --}}
                            <div class="lg:col-span-1">
                                <label for="empresa_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-100">Empresa <span class="text-red-500">*</span></label>
                                <select name="empresa_id" id="empresa_id" required
                                        class="block w-full px-4 py-2.5 bg-white dark:bg-gray-700/50 border border-gray-300 dark:border-gray-600 rounded-lg text-sm shadow-sm placeholder-gray-400 dark:placeholder-gray-500 text-gray-900 dark:text-white focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/50 dark:focus:ring-blue-500/60 transition duration-150 ease-in-out pr-10 appearance-none bg-no-repeat bg-right @error('empresa_id') border-red-500 ring-red-500 @enderror"
                                        style="background-image: url('data:image/svg+xml;charset=utf-8,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' fill=\'none\' viewBox=\'0 0 20 20\'%3E%3Cpath stroke=\'%236b7280\' stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'1.5\' d=\'M6 8l4 4 4-4\'/%3E%3C/svg%3E'); background-position: right 0.75rem center; background-size: 1.25em 1.25em;">
                                    <option value="" disabled selected>Selecciona una empresa...</option>
                                    @foreach($empresasParaSelect ?? [] as $id => $nombre)
                                        <option value="{{ $id }}" {{ old('empresa_id') == $id ? 'selected' : '' }}>{{ $nombre }}</option>
                                    @endforeach
                                </select>
                                @error('empresa_id') <p class="mt-2 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                            </div>
                            {{-- Nombre Contrato --}}
                            <div class="lg:col-span-2">
                                <label for="nombre" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-100">Nombre del Contrato <span class="text-red-500">*</span></label>
                                <div class="relative rounded-md shadow-sm">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3"><i class="far fa-file-alt text-gray-400 dark:text-gray-500 w-5 h-5"></i></div>
                                    <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}" required placeholder="Ej: Aplicación NOM-035 2025"
                                           class="block w-full pl-10 px-4 py-2.5 bg-white dark:bg-gray-700/50 border border-gray-300 dark:border-gray-600 rounded-lg text-sm placeholder-gray-400 dark:placeholder-gray-500 text-gray-900 dark:text-white focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/50 dark:focus:ring-blue-500/60 transition duration-150 ease-in-out @error('nombre') border-red-500 ring-red-500 @enderror">
                                </div>
                                @error('nombre') <p class="mt-2 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                            </div>
                        </div>
                        {{-- Descripción --}}
                        <div class="mt-6">
                            <label for="descripcion" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-100">Descripción (Opcional)</label>
                            <textarea name="descripcion" id="descripcion" rows="3" placeholder="Detalles adicionales sobre el alcance del contrato, objetivos, etc."
                                      class="block w-full px-4 py-2.5 bg-white dark:bg-gray-700/50 border border-gray-300 dark:border-gray-600 rounded-lg text-sm shadow-sm placeholder-gray-400 dark:placeholder-gray-500 text-gray-900 dark:text-white focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/50 dark:focus:ring-blue-500/60 transition duration-150 ease-in-out @error('descripcion') border-red-500 ring-red-500 @enderror">{{ old('descripcion') }}</textarea>
                            @error('descripcion') <p class="mt-2 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Sección 2: Vigencia y Estado --}}
                    <div class="pt-8 space-y-6 border-t border-gray-200 dark:border-gray-700">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 museo-500">2. Vigencia y Estado</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="fecha_inicio" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-100">Fecha Inicio (Opcional)</label>
                                <input type="date" name="fecha_inicio" id="fecha_inicio" value="{{ old('fecha_inicio') }}"
                                       class="block w-full px-4 py-2.5 bg-white dark:bg-gray-700/50 border border-gray-300 dark:border-gray-600 rounded-lg text-sm shadow-sm text-gray-900 dark:text-white focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/50 dark:focus:ring-blue-500/60 transition duration-150 ease-in-out @error('fecha_inicio') border-red-500 ring-red-500 @enderror">
                                @error('fecha_inicio') <p class="mt-2 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="fecha_fin" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-100">Fecha Fin (Opcional)</label>
                                <input type="date" name="fecha_fin" id="fecha_fin" value="{{ old('fecha_fin') }}"
                                       class="block w-full px-4 py-2.5 bg-white dark:bg-gray-700/50 border border-gray-300 dark:border-gray-600 rounded-lg text-sm shadow-sm text-gray-900 dark:text-white focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/50 dark:focus:ring-blue-500/60 transition duration-150 ease-in-out @error('fecha_fin') border-red-500 ring-red-500 @enderror">
                                @error('fecha_fin') <p class="mt-2 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="estado" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-100">Estado <span class="text-red-500">*</span></label>
                                <select name="estado" id="estado" required
                                        class="block w-full px-4 py-2.5 bg-white dark:bg-gray-700/50 border border-gray-300 dark:border-gray-600 rounded-lg text-sm shadow-sm text-gray-900 dark:text-white focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/50 dark:focus:ring-blue-500/60 transition duration-150 ease-in-out pr-10 appearance-none bg-no-repeat bg-right @error('estado') border-red-500 ring-red-500 @enderror"
                                        style="background-image: url('data:image/svg+xml;charset=utf-8,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' fill=\'none\' viewBox=\'0 0 20 20\'%3E%3Cpath stroke=\'%236b7280\' stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'1.5\' d=\'M6 8l4 4 4-4\'/%3E%3C/svg%3E'); background-position: right 0.75rem center; background-size: 1.25em 1.25em;">
                                    <option value="activo" {{ old('estado', 'activo') == 'activo' ? 'selected' : '' }}>Activo</option>
                                    <option value="completado" {{ old('estado') == 'completado' ? 'selected' : '' }}>Completado</option>
                                    <option value="cancelado" {{ old('estado') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                                </select>
                                @error('estado') <p class="mt-2 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Sección 3: Cuestionarios --}}
                    <div class="pt-8 space-y-6 border-t border-gray-200 dark:border-gray-700">
                        <h4 class="block text-lg font-semibold text-gray-900 dark:text-gray-100 museo-500">3. Cuestionarios a Incluir <span class="text-red-500">*</span></h4>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            @forelse($cuestionariosParaSelect ?? [] as $id => $nombre)
                                {{-- Tarjeta de Checkbox --}}
                                <label for="cuestionario_{{ $id }}"
                                       class="relative flex items-center p-4 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer transition-all duration-150
                                          hover:bg-gray-50 dark:hover:bg-gray-700 has-[:checked]:bg-blue-50 has-[:checked]:border-blue-500
                                          has-[:checked]:ring-2 has-[:checked]:ring-blue-500 dark:has-[:checked]:bg-gray-700 dark:has-[:checked]:border-blue-500
                                          group">

                                    <input id="cuestionario_{{ $id }}" name="cuestionarios[]" type="checkbox" value="{{ $id }}"
                                           {{ (is_array(old('cuestionarios')) && in_array($id, old('cuestionarios'))) ? 'checked' : '' }}
                                           class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 transition duration-150 ease-in-out">
                                    <span class="ml-3 block text-sm font-medium text-gray-800 dark:text-gray-200">{{ $nombre }}</span>
                                    {{-- Checkmark visible condicionalmente --}}
                                    <i class="fas fa-check-circle text-blue-600 dark:text-blue-400 absolute top-3 right-3 hidden group-has-[:checked]:block"></i>
                                </label>

                            @empty
                                <p class="text-sm text-center text-gray-500 dark:text-gray-400 italic py-3 sm:col-span-3">No hay cuestionarios NOM-035 disponibles.</p>
                            @endforelse
                        </div>
                        @error('cuestionarios') <p class="mt-2 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        @error('cuestionarios.*') <p class="mt-2 text-xs text-red-600 dark:text-red-400">Al menos uno de los cuestionarios seleccionados no es válido.</p> @enderror
                    </div>

                    {{-- Botones Formulario --}}
                    <div class="flex justify-end pt-8 mt-8 border-t border-gray-200 dark:border-gray-700 space-x-4">
                        <button type="button" @click="showForm = false" class="inline-flex items-center justify-center px-6 py-3 text-sm font-semibold leading-5 text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 active:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-600 dark:active:bg-gray-500 transition-all duration-200 ease-in-out">
                            <i class="fas fa-times mr-2"></i> Cancelar
                        </button>
                        <button type="submit" class="inline-flex items-center justify-center px-6 py-3 text-sm font-semibold leading-5 text-white bg-blue-600 border border-transparent rounded-lg shadow-md hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800 transition-all duration-200 ease-in-out">
                            <i class="fas fa-save mr-2"></i> Guardar Contrato
                        </button>
                    </div>
                </form>
            </div>
        </div>


        {{-- Listado de Contratos Existentes --}}
        <div class="bg-white dark:bg-gray-800 shadow-xl rounded-xl p-6 lg:p-8 border border-gray-200 dark:border-gray-700">
            <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-6 museo-500 border-b border-gray-200 dark:border-gray-700 pb-4">
                <i class="fas fa-list-alt mr-3 text-gray-400"></i> Contratos Registrados
            </h3>

            <div class="space-y-6">
                @forelse ($empresasConContratos ?? [] as $empresa)
                    {{-- Card por Empresa --}}
                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-200">
                        <h4 class="text-md font-semibold text-blue-800 dark:text-blue-300 museo-500 bg-gray-50 dark:bg-gray-700/50 px-5 py-4 border-b border-gray-200 dark:border-gray-600 flex items-center">
                            <i class="fas fa-building mr-3 text-blue-400"></i>
                            <span>{{ $empresa->nombre }}</span>
                        </h4>
                        @if($empresa->contratos->isEmpty())
                            <p class="px-5 py-4 text-sm text-gray-500 dark:text-gray-400 italic">No hay contratos NOM-035 registrados para esta empresa.</p>
                        @else
                            {{-- Lista de Contratos de la Empresa --}}
                            <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($empresa->contratos as $contrato)
                                    <li class="px-5 py-4 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition duration-150 ease-in-out group">
                                        <div class="flex flex-col sm:flex-row justify-between sm:items-start gap-3">
                                            {{-- Información Principal --}}
                                            <div class="flex-1 min-w-0">
                                                <p class="text-base font-medium text-gray-900 dark:text-gray-100 truncate">{{ $contrato->nombre }}</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 flex items-center">
                                                    <i class="far fa-calendar-alt mr-1.5 opacity-75"></i>
                                                    {{ $contrato->fecha_inicio ? \Carbon\Carbon::parse($contrato->fecha_inicio)->isoFormat('LL') : 'Fecha no especificada' }}
                                                    <span class="mx-1.5">&ndash;</span>
                                                    {{ $contrato->fecha_fin ? \Carbon\Carbon::parse($contrato->fecha_fin)->isoFormat('LL') : 'Indefinido' }}
                                                </p>
                                                @if($contrato->cuestionarios->isNotEmpty())
                                                    <div class="mt-2.5">
                                                        <p class="text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5">Cuestionarios:</p>
                                                        <div class="flex flex-wrap gap-1.5">
                                                            @foreach ($contrato->cuestionarios as $cuestionario)
                                                                <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">{{ $cuestionario->codigo }}</span>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                            {{-- Estado y Acciones --}}
                                            <div class="flex-shrink-0 flex flex-col sm:items-end space-y-2 mt-2 sm:mt-0">
                                            <span @class(['px-2.5 py-1 inline-flex text-xs leading-4 font-semibold rounded-full capitalize shadow-sm',
                                                'bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-100' => $contrato->estado == 'activo',
                                                'bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-100' => $contrato->estado == 'completado',
                                                'bg-red-100 text-red-800 dark:bg-red-700 dark:text-red-100' => $contrato->estado == 'cancelado',
                                            ])>
                                                {{ str_replace('_', ' ', $contrato->estado) }}
                                            </span>
                                                <div class="flex items-center space-x-3 pt-1 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity duration-200">
                                                    <a href="{{-- route('nom035.contratos.edit', $contrato->id) --}}#" title="Editar Contrato"
                                                       class="inline-flex items-center text-xs text-yellow-600 hover:text-yellow-800 dark:text-yellow-400 dark:hover:text-yellow-300 font-medium group">
                                                        <i class="fas fa-edit mr-1 group-hover:scale-110 transition-transform"></i> Editar
                                                    </a>
                                                    <form action="{{-- route('nom035.contratos.destroy', $contrato->id) --}}#" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este contrato?\nEsta acción no se puede deshacer.');" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" title="Eliminar Contrato"
                                                                class="inline-flex items-center text-xs text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 font-medium group">
                                                            <i class="fas fa-trash mr-1 group-hover:scale-110 transition-transform"></i> Eliminar
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                @empty
                    {{-- Estado vacío --}}
                    <div class="text-center py-12 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-lg">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" /></svg>
                        <h3 class="mt-2 text-sm font-semibold text-gray-900 dark:text-gray-200">No hay contratos registrados</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Empieza creando uno usando el formulario.</p>
                        <div class="mt-6">
                            <button @click="$dispatch('content-update', { showForm: true }); document.getElementById('empresa_id').focus();" type="button" class="btn btn-primary">
                                <i class="fas fa-plus mr-2"></i> Crear primer contrato
                            </button>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ELIMINADO @push('styles') - Ahora usamos clases de Tailwind directas --}}

    @push('scripts')
        {{-- Incluir SweetAlert2 CDN --}}
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        {{-- Script para SweetAlert2 (corregido para sessionStorage) --}}
        @if (session('status'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const alertKey = 'swal_contrato_status_{{ substr(sha1(session('status')), 0, 8) }}'; // Clave única por mensaje
                    if (!sessionStorage.getItem(alertKey)) {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Realizado!',
                            text: "{{ session('status') }}",
                            confirmButtonText: 'Entendido',
                            confirmButtonColor: '#2563EB',
                            timer: 3500,
                            timerProgressBar: true,
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false
                        });
                        sessionStorage.setItem(alertKey, 'true');
                    }
                });
            </script>
        @endif

        {{-- Script para deshabilitar botón al enviar y botón de estado vacío --}}
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Deshabilitar botón al enviar
                const form = document.querySelector('form[action="{{ route('nom035.contratos.store') }}"]');
                if (form) {
                    const submitButton = form.querySelector('button[type="submit"]');
                    if (submitButton) {
                        form.addEventListener('submit', function() {
                            submitButton.disabled = true;
                            submitButton.innerHTML = `<i class="fas fa-spinner fa-spin mr-2"></i> Guardando...`;
                        });
                    }
                }

                // Botón "Crear primer contrato" en estado vacío
                const createFirstBtn = document.querySelector('button[onclick*="$dispatch(\'content-update\'"]');
                if(createFirstBtn) {
                    createFirstBtn.addEventListener('click', () => {
                        window.dispatchEvent(new CustomEvent('content-update', { detail: { showForm: true } }));
                        setTimeout(() => {
                            const formElement = document.querySelector('form[action="{{ route('nom035.contratos.store') }}"]');
                            if(formElement) {
                                formElement.scrollIntoView({ behavior: 'smooth', block: 'start' });
                                const firstInput = formElement.querySelector('#empresa_id');
                                if(firstInput) firstInput.focus();
                            }
                        }, 350);
                    });
                }
            });
        </script>
    @endpush

@endsection

