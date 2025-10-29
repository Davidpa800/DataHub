@extends('dashboard')

@section('title', 'Parámetros del Sistema')

{{--
    El Controller debe pasar:
    - $parameters (Colección clave => valor, incluye textos de UI)
    - $contratosListado (Colección de Contratos con sus relaciones)
    - $overrideableParams (Colección de claves de parámetros que se pueden anular)
    - $webNom035Params (Colección de objetos Parametro para la Pestaña 3: module=NOM035, param_group=web)
    - $appNom035Params (NUEVA: Colección de objetos Parametro para la Pestaña 2: module=NOM035, param_group=app)
    - Variables individuales (ej: $mail_host, $flujo_consecutivo, etc.)
--}}

@php
    // La colección $parameters es provista por el controlador.
    // Aseguramos su existencia con un valor por defecto (colección vacía) por robustez.
    $parameters = $parameters ?? collect([]);
    $contratosListado = $contratosListado ?? collect([]);
    $webNom035Params = $webNom035Params ?? collect([]);
    $appNom035Params = $appNom035Params ?? collect([]); // Asegurar existencia

    // Función de ayuda para obtener el valor del parámetro de la colección principal
    // El valor por defecto es una cadena vacía ('') para eliminar todos los strings estáticos de la UI.
    $getParam = fn ($key, $default = '') => $parameters->get($key, $default);

    // Se asegura que $overrideableParams sea una colección, pero su contenido es 100% externo.
    $overrideableParams = $overrideableParams ?? collect([]);

    // Función de ayuda para chequear el valor booleano desde el objeto Parametro
    $isParamChecked = function($param) {
        $key = $param->key;
        // Asume que $param es un objeto (modelo) con default_value.
        $value = old($key, $param->default_value ?? 'false');
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    };
@endphp

@section('content')
    <div x-data="{ activeTab: 'sistema' }" class="space-y-8">

        {{-- Encabezado --}}
        <div class="flex flex-col sm:flex-row justify-between items-center pb-5 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-3xl font-semibold text-gray-800 dark:text-gray-100 museo-500 mb-3 sm:mb-0 flex items-center">
                <i class="fas fa-cogs mr-4 text-blue-500"></i> {{ $getParam('page.title', 'Parámetros del Sistema') }}
            </h2>
            {{-- Botón para abrir Modal --}}
            <button type="button" data-modal-toggle="nuevo-parametro-modal"
                    class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-4 focus:ring-green-300 dark:focus:ring-green-800 rounded-lg shadow-sm transition-colors duration-200 w-full sm:w-auto">
                <i class="fas fa-plus mr-2"></i> {{ $getParam('page.button.new', 'Nuevo Parámetro') }}
            </button>
        </div>

        {{-- Contenedor de Pestañas --}}
        <div class="bg-white dark:bg-gray-900 shadow-xl rounded-2xl p-6 lg:p-8 border border-gray-200 dark:border-gray-700">

            {{-- Pestañas de Navegación --}}
            <div class="mb-6 border-b border-gray-200 dark:border-gray-700">
                <nav class="-mb-px flex space-x-6 overflow-x-auto" aria-label="Tabs">
                    <button @click="activeTab = 'sistema'" :class="{ 'border-blue-500 text-blue-600 dark:border-blue-400 dark:text-blue-300': activeTab === 'sistema', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:border-gray-600': activeTab !== 'sistema' }"
                            class="flex items-center whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm transition-colors duration-150 ease-in-out focus:outline-none">
                        <i class="fas fa-desktop mr-2"></i> {{ $getParam('tab.sistema.label', 'Sistema (Email)') }}
                    </button>
                    <button @click="activeTab = 'app'" :class="{ 'border-blue-500 text-blue-600 dark:border-blue-400 dark:text-blue-300': activeTab === 'app', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:border-gray-600': activeTab !== 'app' }"
                            class="flex items-center whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm transition-colors duration-150 ease-in-out focus:outline-none">
                        <i class="fas fa-chart-bar mr-2"></i> {{ $getParam('tab.app.label', 'App (NOM-035)') }}
                    </button>
                    <button @click="activeTab = 'web_nom035'" :class="{ 'border-blue-500 text-blue-600 dark:border-blue-400 dark:text-blue-300': activeTab === 'web_nom035', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:border-gray-600': activeTab !== 'web_nom035' }"
                            class="flex items-center whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm transition-colors duration-150 ease-in-out focus:outline-none">
                        <i class="fas fa-window-maximize mr-2"></i> {{ $getParam('tab.web_nom035.label', 'Web (NOM-035)') }}
                    </button>
                    <button @click="activeTab = 'empresa'" :class="{ 'border-blue-500 text-blue-600 dark:border-blue-400 dark:text-blue-300': activeTab === 'empresa', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:border-gray-600': activeTab !== 'empresa' }"
                            class="flex items-center whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm transition-colors duration-150 ease-in-out focus:outline-none">
                        <i class="fas fa-building mr-2"></i> {{ $getParam('tab.empresa.label', 'Empresa') }}
                    </button>
                    <button @click="activeTab = 'contratos'" :class="{ 'border-blue-500 text-blue-600 dark:border-blue-400 dark:text-blue-300': activeTab === 'contratos', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:border-gray-600': activeTab !== 'contratos' }"
                            class="flex items-center whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm transition-colors duration-150 ease-in-out focus:outline-none">
                        <i class="fas fa-file-contract mr-2"></i> {{ $getParam('tab.contratos.label', 'Contratos') }}
                    </button>
                </nav>
            </div>

            {{-- Contenido de las Pestañas --}}
            <div>
                {{-- Panel 1: Sistema (Email) --}}
                <div x-show="activeTab === 'sistema'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    <form action="{{ route('admin.parametros.store.sistema') }}" method="POST" class="space-y-8">
                        @csrf
                        <div class="border border-gray-300 dark:border-gray-700 rounded-lg p-6 pt-4 shadow-sm bg-gray-50/30 dark:bg-gray-800/20">
                            {{-- Título de la sección dinámico --}}
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 museo-500 mb-6">{{ $getParam('sistema.email.title', 'Parámetros de Correo (SMTP)') }}</h4>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {{-- Mail Host --}}
                                <div>
                                    <label for="mail_host" class="form-label">{{ $getParam('sistema.mail_host.label', 'Host (Servidor)') }}</label>
                                    <div class="relative mt-1.5">
                                        <span class="input-icon"><i class="fas fa-server"></i></span>
                                        <input type="text" name="mail_host" id="mail_host"
                                               value="{{ old('mail_host', $mail_host ?? '') }}" placeholder="{{ $getParam('sistema.mail_host.placeholder', '') }}"
                                               class="form-input with-icon @error('mail_host') input-error @enderror">
                                    </div>
                                    @error('mail_host') <p class="form-error-message">{{ $message }}</p> @enderror
                                </div>
                                {{-- Mail Port --}}
                                <div>
                                    <label for="mail_port" class="form-label">{{ $getParam('sistema.mail_port.label', 'Puerto') }}</label>
                                    <div class="relative mt-1.5">
                                        <span class="input-icon"><i class="fas fa-ethernet"></i></span>
                                        <input type="text" name="mail_port" id="mail_port"
                                               value="{{ old('mail_port', $mail_port ?? '') }}" placeholder="{{ $getParam('sistema.mail_port.placeholder', '') }}"
                                               class="form-input with-icon @error('mail_port') input-error @enderror">
                                    </div>
                                    @error('mail_port') <p class="form-error-message">{{ $message }}</p> @enderror
                                </div>
                                {{-- Mail Username --}}
                                <div>
                                    <label for="mail_username" class="form-label">{{ $getParam('sistema.mail_username.label', 'Usuario') }}</label>
                                    <div class="relative mt-1.5">
                                        <span class="input-icon"><i class="fas fa-user"></i></span>
                                        <input type="text" name="mail_username" id="mail_username"
                                               value="{{ old('mail_username', $mail_username ?? '') }}" placeholder="{{ $getParam('sistema.mail_username.placeholder', '') }}"
                                               class="form-input with-icon @error('mail_username') input-error @enderror">
                                    </div>
                                    @error('mail_username') <p class="form-error-message">{{ $message }}</p> @enderror
                                </div>
                                {{-- Mail Password (Solo se ingresa para cambiar) --}}
                                <div>
                                    <label for="mail_password" class="form-label">{{ $getParam('sistema.mail_password.label', 'Contraseña') }}</label>
                                    <div class="relative mt-1.5">
                                        <span class="input-icon"><i class="fas fa-lock"></i></span>
                                        <input type="password" name="mail_password" id="mail_password" placeholder="{{ $getParam('sistema.mail_password.placeholder', '') }}"
                                               class="form-input with-icon @error('mail_password') input-error @enderror">
                                    </div>
                                    @error('mail_password') <p class="form-error-message">{{ $message }}</p> @enderror
                                </div>
                                {{-- Mail From Address --}}
                                <div>
                                    <label for="mail_from_address" class="form-label">{{ $getParam('sistema.mail_from_address.label', 'Correo Remitente') }}</label>
                                    <div class="relative mt-1.5">
                                        <span class="input-icon"><i class="fas fa-at"></i></span>
                                        <input type="email" name="mail_from_address" id="mail_from_address"
                                               value="{{ old('mail_from_address', $mail_from_address ?? '') }}" placeholder="{{ $getParam('sistema.mail_from_address.placeholder', '') }}"
                                               class="form-input with-icon @error('mail_from_address') input-error @enderror">
                                    </div>
                                    @error('mail_from_address') <p class="form-error-message">{{ $message }}</p> @enderror
                                </div>
                                {{-- Mail From Name --}}
                                <div>
                                    <label for="mail_from_name" class="form-label">{{ $getParam('sistema.mail_from_name.label', 'Nombre Remitente') }}</label>
                                    <div class="relative mt-1.5">
                                        <span class="input-icon"><i class="fas fa-signature"></i></span>
                                        <input type="text" name="mail_from_name" id="mail_from_name"
                                               value="{{ old('mail_from_name', $mail_from_name ?? '') }}" placeholder="{{ $getParam('sistema.mail_from_name.placeholder', '') }}"
                                               class="form-input with-icon @error('mail_from_name') input-error @enderror">
                                    </div>
                                    @error('mail_from_name') <p class="form-error-message">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end items-center pt-6 mt-6 border-t border-gray-200 dark:border-gray-700">
                            <button
                                type="submit"
                                class="inline-flex items-center gap-2 px-6 py-3 text-base font-semibold rounded-xl
           text-white bg-gray-600 hover:bg-gray-700 focus:ring-4 focus:ring-gray-300
           dark:bg-gray-700 dark:hover:bg-gray-800 dark:focus:ring-gray-900
           transition-all duration-200 shadow-sm hover:shadow-md
           active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed"
                                disabled
                            >
                                <i class="fas fa-save text-white text-lg"></i>
                                <span>{{ $getParam('sistema.button.save', 'Guardar Cambios (Sistema)') }}</span>
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Panel 2: App (NOM-035) --}}
                <div x-show="activeTab === 'app'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    <form action="{{ route('admin.parametros.store.app') }}" method="POST">
                        @csrf
                        <fieldset class="border border-gray-300 dark:border-gray-600 rounded-lg p-6 pt-4 shadow-sm bg-gray-50/30 dark:bg-gray-800/20">
                            {{-- Título de la sección dinámico --}}
                            <legend class="text-lg font-semibold text-gray-900 dark:text-gray-100 museo-500 px-3 -ml-3">{{ $getParam('app.nom035.title', '') }}</legend>
                            <div class="mt-5 space-y-4">
                                {{-- BUCLE DINÁMICO para los parámetros de la Pestaña 2 --}}
                                @forelse($appNom035Params as $param)
                                    @php
                                        $key = $param->key;
                                        // El input_name se limpia para que el controlador lo reciba sin prefijo (web.)
                                        $input_name = str_replace(['web.', 'app.', 'sistema.'], '', $key);
                                        $is_checked = $isParamChecked($param);

                                        // Fallback para el label y description, usando la descripción del objeto Parametro si existe.
                                        $label = $getParam("{$key}.label", ucfirst(str_replace(['web_', 'app_', 'sistema_', '.'], ['', '', '', ' '], $key)));
                                        $description = $getParam("{$key}.description", $param->description ?? '');
                                    @endphp

                                    <label for="{{ $input_name }}" class="flex items-center p-4 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm hover:bg-gray-50/50 dark:hover:bg-gray-700/30 cursor-pointer has-[:checked]:bg-blue-50 has-[:checked]:border-blue-500 dark:has-[:checked]:bg-gray-700 dark:has-[:checked]:border-blue-500">
                                        <input id="{{ $input_name }}" name="{{ $input_name }}" type="checkbox" class="form-checkbox" value="1"
                                            {{ old($input_name, $is_checked) ? 'checked' : '' }}>
                                        <span class="ml-3 block text-sm font-medium text-gray-800 dark:text-gray-200">
                                            {{ $label }}
                                            <span class="block text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                {{ $description }}
                                            </span>
                                        </span>
                                    </label>
                                @empty
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        {{ $getParam('app.nom035.empty_message', 'No se encontraron parámetros para este módulo.') }}
                                    </p>
                                @endforelse
                            </div>
                        </fieldset>
                        <div class="flex justify-end items-center pt-6 mt-8 border-t border-gray-200 dark:border-gray-700">
                            <button
                                type="submit"
                                class="inline-flex items-center gap-2 px-6 py-3 text-base font-semibold rounded-xl
           text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300
           dark:bg-blue-700 dark:hover:bg-blue-800 dark:focus:ring-blue-900
           transition-all duration-200 shadow-sm hover:shadow-md
           active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                <i class="fas fa-save text-white text-lg"></i>
                                <span>{{ $getParam('app.button.save', 'Guardar Cambios (App)') }}</span>
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Panel 3: Web (NOM-035) --}}
                <div x-show="activeTab === 'web_nom035'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    <form action="{{ route('admin.parametros.store.web_nom035') }}" method="POST">
                        @csrf
                        <fieldset class="border border-gray-300 dark:border-gray-600 rounded-lg p-6 pt-4 shadow-sm bg-gray-50/30 dark:bg-gray-800/20">
                            {{-- Título de la sección dinámico --}}
                            <legend class="text-lg font-semibold text-gray-900 dark:text-gray-100 museo-500 px-3 -ml-3">{{ $getParam('web.nom035.title', '') }}</legend>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-6 mt-2">
                                {{ $getParam('web.nom035.description', '') }}
                            </p>
                            <div class="space-y-4">
                                {{-- BUCLE DINÁMICO para los parámetros de la Pestaña 3 --}}
                                @forelse($webNom035Params as $param)
                                    @php
                                        // Usar la clave del parámetro para el ID, name y lookups de la UI
                                        $key = $param->key;
                                        // El input_name se limpia para que el controlador lo reciba sin prefijo (web.)
                                        $input_name = str_replace(['web.', 'app.', 'sistema.'], '', $key);
                                        $is_checked = $isParamChecked($param);

                                        // Fallback para el label y description, usando la descripción del objeto Parametro si existe.
                                        // Se mantiene el fallback de limpieza de clave para generar una etiqueta si no hay una clave específica.
                                        $label = $getParam("{$key}.label", ucfirst(str_replace(['web_', 'app_', 'sistema_', '.'], ['', '', '', ' '], $key)));
                                        $description = $getParam("{$key}.description", $param->description ?? '');
                                    @endphp

                                    <label for="{{ $input_name }}" class="flex items-center p-4 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm hover:bg-gray-50/50 dark:hover:bg-gray-700/30 cursor-pointer has-[:checked]:bg-blue-50 has-[:checked]:border-blue-500 dark:has-[:checked]:bg-gray-700 dark:has-[:checked]:border-blue-500">
                                        {{-- El campo de entrada debe usar $input_name --}}
                                        <input id="{{ $input_name }}" name="{{ $input_name }}" type="checkbox" class="form-checkbox" value="1"
                                            {{ old($input_name, $is_checked) ? 'checked' : '' }}>
                                        <span class="ml-3 block text-sm font-medium text-gray-800 dark:text-gray-200">
                                            {{ $label }}
                                            <span class="block text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                {{ $description }}
                                            </span>
                                        </span>
                                    </label>
                                @empty
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        {{ $getParam('web.nom035.empty_message', 'No se encontraron parámetros para este módulo.') }}
                                    </p>
                                @endforelse


                            </div>
                        </fieldset>

                        <div class="flex justify-end items-center pt-6 mt-8 border-t border-gray-200 dark:border-gray-700">
                            <button
                                type="submit"
                                class="inline-flex items-center gap-2 px-6 py-3 text-base font-medium rounded-xl
           text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:ring-green-300
           dark:focus:ring-green-800 dark:bg-green-700 dark:hover:bg-green-800
           transition-all duration-200 shadow-sm disabled:opacity-50 disabled:cursor-not-allowed"

                            >
                                <i class="fas fa-save text-white text-lg"></i>
                                <span>{{ $getParam('web.button.save', 'Guardar Cambios (Web)') }}</span>
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Panel 4: Empresa --}}
                <div x-show="activeTab === 'empresa'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    <form action="{{ route('admin.parametros.store.empresa') }}" method="POST">
                        @csrf
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 museo-500 mb-5">{{ $getParam('empresa.title', 'Parámetros de Empresa') }}</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400"> {{ $getParam('empresa.message', '(Pendiente de implementación)') }} </p>
                        <div class="flex justify-end items-center pt-6 mt-6 border-t border-gray-200 dark:border-gray-700">
                            {{-- ELIMINAMOS disabled --}}
                            <button
                                type="submit"
                                class="inline-flex items-center gap-2 px-6 py-3 text-base font-semibold rounded-xl
           text-white bg-amber-600 hover:bg-amber-700 focus:ring-4 focus:ring-amber-300
           dark:bg-amber-700 dark:hover:bg-amber-800 dark:focus:ring-amber-900
           transition-all duration-200 shadow-sm hover:shadow-md
           active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                <i class="fas fa-save text-white text-lg"></i>
                                <span>{{ $getParam('empresa.button.save', 'Guardar Cambios (Empresa)') }}</span>
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Panel 5: Contratos --}}
                <div x-show="activeTab === 'contratos'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    <form action="{{ route('admin.parametros.store.contratos') }}" method="POST">
                        @csrf
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 museo-500 mb-5">{{ $getParam('contratos.title', 'Parámetros de Contratos') }}</h4>

                        {{-- LISTADO DINÁMICO DE CONTRATOS --}}
                        <div class="mt-6">
                            @forelse($contratosListado as $contrato)
                                <div x-data="{ open: false }" class="mb-4 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm overflow-hidden">
                                    <div @click="open = !open" class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                        <div class="flex items-center">
                                            <i class="fas fa-chevron-right mr-3 transition-transform duration-200" :class="{ 'rotate-90': open }"></i>
                                            <div class="flex flex-col text-sm">
                                                <span class="font-semibold text-gray-800 dark:text-gray-200">{{ $contrato->nombre }} ({{ $contrato->empresa->nombre ?? $getParam('contratos.no_company', 'Sin Empresa') }})</span>
                                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 space-x-2">
                                                    {{-- Mapeo de cuestionarios --}}
                                                    @foreach($contrato->cuestionarios as $cuestionario)
                                                        <span class="font-medium">{{ $cuestionario->codigo }}</span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">Empleados: {{ $contrato->empleados_count ?? 0 }}</span>
                                    </div>

                                    {{-- Contenido de anulación de parámetros --}}
                                    <div x-show="open" x-collapse>
                                        <div class="p-4 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 space-y-4">

                                            <h5 class="text-md font-semibold text-indigo-600 dark:text-indigo-400 mb-3">
                                                {{ $getParam('contrato.override.subtitle', 'Anulación de Parámetros Web (NOM-035)') }}
                                            </h5>

                                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                                {{ $getParam('contrato.override.description', 'Estos valores anularán los ajustes globales de la pestaña "Web (NOM-035)" solo para este contrato.') }}
                                            </p>

                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                {{-- BUCLE DINÁMICO para los parámetros anulables (Overrideable Params) --}}
                                                @foreach($overrideableParams as $key)
                                                    @php
                                                        // Intentar obtener el valor de anulación del array de overrides del contrato
                                                        $override_value = $contrato->override[$key] ?? null;
                                                        // Generar una clave de label más flexible
                                                        $label_key = "contrato.override.{$key}.label";
                                                        // Fallback dinámico: limpia la clave de la BD para mostrar algo legible si la etiqueta no está en $parameters
                                                        $default_label = ucfirst(str_replace('_', ' ', $key));
                                                    @endphp

                                                    <label class="flex items-center border p-3 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700
                                                        {{ $override_value === true ? 'has-[:checked]:bg-blue-50 has-[:checked]:border-blue-500 dark:has-[:checked]:bg-gray-700 dark:has-[:checked]:border-blue-500' : '' }}">

                                                        <input type="checkbox"
                                                               name="contrato_override[{{ $contrato->id }}][{{ $key }}]"
                                                               class="form-checkbox"
                                                               value="1"
                                                            {{-- CORRECCIÓN CLAVE: Usar old() para priorizar el estado de la sesión, luego el valor de la BD --}}
                                                            {{ (old('contrato_override.' . $contrato->id . '.' . $key) == 1 || $override_value === true) ? 'checked' : '' }}>

                                                        <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">
                                                            {{ $getParam($label_key, $default_label) }}
                                                        </span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $getParam('contratos.empty', 'No se encontraron contratos activos con encuestas NOM-035.') }}</p>
                            @endforelse
                        </div>

                        <div class="flex justify-end items-center pt-6 mt-6 border-t border-gray-200 dark:border-gray-700">
                            {{-- ELIMINAMOS disabled --}}
                            <button
                                type="submit"
                                class="inline-flex items-center gap-2 px-6 py-3 text-base font-semibold rounded-xl
           text-white bg-purple-600 hover:bg-purple-700 focus:ring-4 focus:ring-purple-300
           dark:bg-purple-700 dark:hover:bg-purple-800 dark:focus:ring-purple-900
           transition-all duration-200 shadow-sm hover:shadow-md
           active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                <i class="fas fa-save text-white text-lg"></i>
                                <span>{{ $getParam('contratos.button.save', 'Guardar Cambios (Contratos)') }}</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    {{-- Modal para Nuevo Parámetro --}}
    <div id="nuevo-parametro-modal" tabindex="-1" aria-hidden="true"
         class="hidden fixed inset-0 z-50 flex items-center justify-center overflow-y-auto overflow-x-hidden bg-black/40 backdrop-blur-sm">

        <div class="relative w-full max-w-2xl p-4">
            {{-- Contenedor principal --}}
            <div class="relative bg-white dark:bg-gray-900 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700">

                {{-- Encabezado --}}
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                        <i class="fas fa-plus-circle text-green-500"></i>
                        {{ $getParam('modal.new_param.title', 'Agregar Nuevo Parámetro') }}
                    </h3>
                    <button type="button"
                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 rounded-lg p-1.5 transition"
                            data-modal-toggle="nuevo-parametro-modal">
                        <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                             xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                  d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                  clip-rule="evenodd"></path>
                        </svg>
                        <span class="sr-only">{{ $getParam('modal.new_param.close_label', 'Cerrar modal') }}</span>
                    </button>
                </div>

                {{-- Cuerpo del formulario --}}
                <form action="{{ route('admin.parametros.store.nuevo') }}" method="POST" class="px-6 py-6 space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Clave --}}
                        <div>
                            <label for="param_key" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ $getParam('modal.new_param.key.label', 'Clave (Key)') }} <span class="text-red-500">*</span>
                            </label>
                            <div class="relative mt-1">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                <i class="fas fa-key"></i>
                            </span>
                                <input type="text" name="param_key" id="param_key"
                                       value="{{ old('param_key') }}"
                                       class="pl-10 w-full border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm
                                       focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                                       bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-400 @error('param_key') input-error @enderror"
                                       placeholder="{{ $getParam('modal.new_param.key.placeholder', '') }}" required>
                            </div>
                            @error('param_key')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Valor --}}
                        <div>
                            <label for="param_value" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ $getParam('modal.new_param.value.label', 'Valor') }} <span class="text-red-500">*</span>
                            </label>
                            <div class="relative mt-1">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                <i class="fas fa-align-left"></i>
                            </span>
                                <input type="text" name="param_value" id="param_value"
                                       value="{{ old('param_value') }}"
                                       class="pl-10 w-full border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm
                                       focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                                       bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-400 @error('param_value') input-error @enderror"
                                       placeholder="{{ $getParam('modal.new_param.value.placeholder', '') }}" required>
                            </div>
                            @error('param_value')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Grupo --}}
                        <div>
                            <label for="param_group" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ $getParam('modal.new_param.group.label', 'Grupo') }} <span class="text-red-500">*</span>
                            </label>
                            <div class="relative mt-1">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                <i class="fas fa-layer-group"></i>
                            </span>
                                @php
                                    // Lista de grupos (estática en el código, pero labels dinámicos)
                                    $groups = [
                                        'sistema' => $getParam('group.sistema.label', 'Sistema'),
                                        'app' => $getParam('group.app.label', 'App (NOM-035)'),
                                        'web_nom035' => $getParam('group.web_nom035.label', 'Web (NOM-035)'),
                                        'empresa' => $getParam('group.empresa.label', 'Empresa'),
                                        'contratos' => $getParam('group.contratos.label', 'Contratos')
                                    ];
                                @endphp
                                <select name="param_group" id="param_group" required
                                        class="form-select pl-10 w-full @error('param_group') input-error @enderror">
                                    <option value="" disabled {{ old('param_group') ? '' : 'selected' }}>{{ $getParam('modal.new_param.group.default', 'Selecciona un grupo...') }}</option>
                                    @foreach($groups as $key => $label)
                                        <option value="{{ $key }}" {{ old('param_group') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('param_group')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Módulo (El campo que estaba codificado estáticamente) --}}
                        <div>
                            <label for="module" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ $getParam('modal.new_param.module.label', 'Módulo') }} <span class="text-red-500">*</span>
                            </label>
                            <div class="relative mt-1">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                    <i class="fas fa-cubes"></i>
                                </span>
                                @php
                                    $modules = [
                                        'NOM035' => 'NOM035',
                                        'GENERAL' => 'GENERAL',
                                    ];
                                @endphp
                                <select name="module" id="module" required
                                        class="form-select pl-10 w-full @error('module') input-error @enderror">
                                    <option value="" disabled {{ old('module') ? '' : 'selected' }}>{{ $getParam('modal.new_param.module.default', 'Selecciona un módulo...') }}</option>
                                    @foreach($modules as $key => $label)
                                        <option value="{{ $key }}" {{ old('module') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('module')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>


                        {{-- Descripción --}}
                        <div class="md:col-span-2">
                            <label for="param_description"
                                   class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ $getParam('modal.new_param.description.label', 'Descripción') }}</label>
                            <textarea name="param_description" id="param_description" rows="3"
                                      class="form-input w-full @error('param_description') input-error @enderror"
                                      placeholder="{{ $getParam('modal.new_param.description.placeholder', '') }}">{{ old('param_description') }}</textarea>
                            @error('param_description')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div
                        class="flex items-center justify-end pt-5 mt-5 border-t border-gray-200 dark:border-gray-700 space-x-3">
                        <button data-modal-toggle="nuevo-parametro-modal" type="button"
                                class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-lg
                               hover:bg-gray-200 dark:hover:bg-gray-600 transition font-medium shadow-sm">
                            {{ $getParam('modal.new_param.button.cancel', 'Cancelar') }}
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium
                               focus:ring-4 focus:ring-green-300 dark:focus:ring-green-700 shadow-sm transition">
                            <i class="fas fa-save mr-1"></i> {{ $getParam('modal.new_param.button.create', 'Crear Parámetro') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    {{-- Estilos --}}
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

            /* Estilo flecha del Select (CSS estándar) */
            .form-select {
                @apply pr-10 appearance-none bg-no-repeat bg-right;
                background-image: url("data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath fill='%236b7280' d='M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z'/%3E%3C/svg%3E");
                background-position: right 0.75rem center;
                background-size: 1.25em 1.25em;
            }
            .dark .form-select {
                background-image: url("data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath fill='%239ca3af' d='M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z'/%3E%3C/svg%3E");
            }

            /* Estilo para checkboxes (Tarjetas) */
            .form-checkbox { @apply h-5 w-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 transition duration-150 ease-in-out; }
            label:has(.form-checkbox:checked) {
                @apply bg-blue-50 border-blue-500 ring-2 ring-blue-500 dark:bg-gray-700 dark:border-blue-500;
            }
            .input-error { @apply border-red-500 ring-1 ring-red-500 focus:border-red-500 focus:ring-red-500; }
            .form-error-message { @apply mt-2 text-xs text-red-600 dark:text-red-400; }
            .form-label { @apply block mb-2 text-sm font-medium text-gray-900 dark:text-gray-100; }

            /* Alpine (Mantenidos para la funcionalidad) */
            [x-collapse] { overflow: hidden; }
            [x-collapse]:not([x-cloak]) { transition: height 300ms cubic-bezier(0.4, 0, 0.2, 1); }
            [x-cloak] { display: none !important; }
        </style>
    @endpush

    @push('scripts')
        {{-- Manejo de SweetAlert2 y Errores de Pestaña --}}
        @if (session('status') || $errors->any())
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    @if (session('status'))
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: "{{ session('status') }}",
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3500,
                        timerProgressBar: true
                    });
                    @endif

                    @if ($errors->any())
                    @php
                        // Lógica para determinar la pestaña a mostrar en caso de errores de validación
                        $targetTab = 'sistema'; // Default
                        $modalErrors = ['param_key', 'param_value', 'param_group', 'param_description', 'module'];

                        if ($errors->hasAny($modalErrors)) {
                            // Error en el modal: Abre el modal
                            echo "const modal = document.getElementById('nuevo-parametro-modal'); if (modal) { modal.classList.remove('hidden'); }";
                        } elseif ($errors->hasAny(['flujo_consecutivo'])) {
                            $targetTab = 'app';
                        } elseif ($errors->hasAny(['web_mostrar_logo_empresa', 'web_mostrar_logo_osh', 'web_permitir_modo_oscuro'])) {
                            $targetTab = 'web_nom035';
                        } elseif ($errors->hasAny(['mail_host', 'mail_port', 'mail_username', 'mail_password', 'mail_from_address', 'mail_from_name'])) {
                            $targetTab = 'sistema';
                        }

                        // Si los errores no son del modal, cambia a la pestaña que contiene los errores
                        if (! $errors->hasAny($modalErrors)) {
                            echo "document.querySelector('[x-data]').__x.$data.activeTab = '{$targetTab}';";
                        }
                    @endphp

                    Swal.fire({
                        icon: 'error',
                        title: 'Error de Validación',
                        text: "Por favor, revisa los campos con errores en el formulario.",
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 5000,
                        timerProgressBar: true
                    });
                    @endif
                });
            </script>
        @endif
    @endpush

@endsection
