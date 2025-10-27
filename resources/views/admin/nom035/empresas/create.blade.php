@extends('dashboard') {{-- Asegúrate que tu layout principal se llame 'dashboard.blade.php' --}}

@section('title', 'Crear Nueva Empresa') {{-- Título específico --}}

@section('content')
    <div class="bg-white dark:bg-gray-800 shadow-xl rounded-xl p-6 lg:p-8 max-w-4xl mx-auto border border-gray-200 dark:border-gray-700"> {{-- Card con más sombra y borde --}}
        <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-100 mb-6 museo-500 border-b border-gray-200 dark:border-gray-700 pb-4">
            <i class="fas fa-plus-circle mr-2 text-blue-500"></i> Registrar Nueva Empresa
        </h2>

        {{-- Mostrar errores generales --}}
        @if(session('error'))
            <div class="mb-6 p-4 text-sm text-red-800 bg-red-100 dark:bg-red-900 dark:text-red-300 rounded-lg border border-red-300 dark:border-red-600 flex items-center" role="alert">
                <i class="fas fa-exclamation-triangle mr-3"></i>
                <div>
                    <span class="font-medium">Error:</span> {{ session('error') }}
                </div>
            </div>
        @endif

        {{-- Formulario --}}
        <form method="POST" action="{{ route('nom035.empresas.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6"> {{-- Ajustado gap --}}

                {{-- Columna Izquierda --}}
                <div class="space-y-5">
                    {{-- Nombre Comercial --}}
                    <div>
                        <label for="nombre" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300 museo-500">Nombre Comercial <span class="text-red-500">*</span></label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <i class="fas fa-building text-gray-400 w-5 h-5"></i>
                            </div>
                            <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}" required
                                   class="form-input block w-full pl-10 @error('nombre') border-red-500 ring-red-500 @enderror" placeholder="Nombre de la empresa">
                        </div>
                        @error('nombre') <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                    {{-- Razón Social --}}
                    <div>
                        <label for="razon_social" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300 museo-500">Razón Social</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <i class="fas fa-briefcase text-gray-400 w-5 h-5"></i>
                            </div>
                            <input type="text" name="razon_social" id="razon_social" value="{{ old('razon_social') }}"
                                   class="form-input block w-full pl-10 @error('razon_social') border-red-500 ring-red-500 @enderror" placeholder="Razón social completa (opcional)">
                        </div>
                        @error('razon_social') <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                    {{-- RFC --}}
                    <div>
                        <label for="rfc" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300 museo-500">RFC</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <i class="fas fa-id-card text-gray-400 w-5 h-5"></i>
                            </div>
                            <input type="text" name="rfc" id="rfc" value="{{ old('rfc') }}" maxlength="13" style="text-transform: uppercase;"
                                   class="form-input block w-full pl-10 @error('rfc') border-red-500 ring-red-500 @enderror" placeholder="XAXX010101000 (opcional)">
                        </div>
                        @error('rfc') <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                    {{-- Actividad Principal --}}
                    <div>
                        <label for="actividad_principal" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300 museo-500">Actividad Principal</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <i class="fas fa-industry text-gray-400 w-5 h-5"></i>
                            </div>
                            <input type="text" name="actividad_principal" id="actividad_principal" value="{{ old('actividad_principal') }}" placeholder="Ej: Consultoría, Manufactura..."
                                   class="form-input block w-full pl-10 @error('actividad_principal') border-red-500 ring-red-500 @enderror">
                        </div>
                        @error('actividad_principal') <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                </div>

                {{-- Columna Derecha --}}
                <div class="space-y-5">
                    {{-- Dirección --}}
                    <div>
                        <label for="direccion" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300 museo-500">Dirección</label>
                        <textarea name="direccion" id="direccion" rows="4" placeholder="Calle, Número, Colonia, CP, Ciudad, Estado (opcional)"
                                  class="form-textarea block w-full @error('direccion') border-red-500 ring-red-500 @enderror">{{ old('direccion') }}</textarea>
                        @error('direccion') <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>

                    {{-- Logo --}}
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300 museo-500">Logo de la Empresa (Opcional)</label>
                        {{-- Input de archivo oculto --}}
                        <input type="file" name="logo" id="logo" accept="image/png, image/jpeg, image/gif, image/svg+xml" class="hidden" onchange="previewLogo(event)">

                        {{-- Área de carga visual --}}
                        <div class="mt-1 flex items-center space-x-4">
                            {{-- Vista previa --}}
                            <div class="flex-shrink-0 h-20 w-20 rounded-md border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 flex items-center justify-center overflow-hidden">
                                <img id="logo-preview" src="#" alt="Vista previa" class="h-full w-full object-contain hidden">
                                <i id="logo-placeholder" class="fas fa-image text-4xl text-gray-300 dark:text-gray-500"></i> {{-- Icono placeholder --}}
                            </div>
                            {{-- Botón y texto --}}
                            <div class="flex flex-col">
                                <button type="button" onclick="document.getElementById('logo').click()" class="px-3 py-2 text-sm font-medium leading-4 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                                    <i class="fas fa-upload mr-2"></i> Seleccionar Logo
                                </button>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">PNG, JPG, GIF, SVG hasta 2MB.</p>
                            </div>
                        </div>
                        @error('logo') <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>
                </div>

            </div>

            {{-- Botones de Acción --}}
            <div class="flex justify-end pt-8 mt-8 border-t border-gray-200 dark:border-gray-700 space-x-3">
                <a href="{{ route('nom035.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                    <i class="fas fa-times mr-2"></i> Cancelar
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition disabled:opacity-50">
                    <i class="fas fa-save mr-2"></i> Guardar Empresa
                </button>
            </div>
        </form>
    </div>

    {{-- Definición de clases reutilizables de Tailwind con @apply (colocar en app.css o layout si prefieres) --}}
    @push('styles')
        <style>
            .form-input {
                @apply block w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md text-sm shadow-sm placeholder-gray-400 dark:placeholder-gray-500 dark:text-white
                focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500
                disabled:bg-gray-50 disabled:text-gray-500 disabled:border-gray-200 disabled:shadow-none
                dark:disabled:bg-gray-800 dark:disabled:text-gray-400 dark:disabled:border-gray-700
                transition duration-150 ease-in-out;
            }
            .form-textarea {
                @apply block w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md text-sm shadow-sm placeholder-gray-400 dark:placeholder-gray-500 dark:text-white
                focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500
                disabled:bg-gray-50 disabled:text-gray-500 disabled:border-gray-200 disabled:shadow-none
                dark:disabled:bg-gray-800 dark:disabled:text-gray-400 dark:disabled:border-gray-700
                transition duration-150 ease-in-out;
            }
            /* Puedes definir btn-primary, btn-secondary aquí si no están globales */
        </style>
    @endpush

    @push('scripts')
        {{-- Incluir SweetAlert2 CDN --}}
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        {{-- Script para vista previa del logo --}}
        <script>
            function previewLogo(event) {
                const reader = new FileReader();
                const preview = document.getElementById('logo-preview');
                const placeholder = document.getElementById('logo-placeholder');
                reader.onload = function(){
                    if (preview && placeholder) {
                        preview.src = reader.result;
                        preview.classList.remove('hidden');
                        placeholder.classList.add('hidden'); // Ocultar placeholder
                    }
                }
                if (event.target.files[0]) {
                    reader.readAsDataURL(event.target.files[0]);
                } else {
                    if (preview && placeholder) {
                        preview.src = '#'; // Limpiar si no hay archivo
                        preview.classList.add('hidden');
                        placeholder.classList.remove('hidden'); // Mostrar placeholder
                    }
                }
            }
        </script>

        {{-- Script para mostrar alerta de SweetAlert2 si hay mensaje de éxito --}}
        @if (session('status'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Guardado!',
                        text: "{{ session('status') }}", // Muestra el mensaje de la sesión
                        confirmButtonText: 'Entendido',
                        confirmButtonColor: '#2563EB' // Azul (accent)
                    });
                });
            </script>
        @endif

        {{-- Script opcional para deshabilitar botón al enviar --}}
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.querySelector('form');
                const submitButton = form.querySelector('button[type="submit"]');
                if (form && submitButton) {
                    form.addEventListener('submit', function() {
                        submitButton.disabled = true;
                        submitButton.innerHTML = `<i class="fas fa-spinner fa-spin mr-2"></i> Guardando...`;
                    });
                }
            });
        </script>


    @endpush

@endsection

