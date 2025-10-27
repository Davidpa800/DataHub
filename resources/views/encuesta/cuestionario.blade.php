<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      x-data="{
          initTheme() {
              if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                  document.documentElement.classList.add('dark');
              } else {
                  document.documentElement.classList.remove('dark');
              }
          }
      }" x-init="initTheme()">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Encuesta NOM-035 | {{ $encuestaActual->cuestionario->nombre ?? 'Cuestionario' }}</title>

    <!-- CORRECCIÓN: Meta CSRF (Importante para Axios) -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Scripts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // Configuración personalizada de Tailwind (COPIADA DEL DASHBOARD)
        tailwind.config = {
            darkMode: 'class', // Habilitar modo oscuro basado en clase
            theme: {
                extend: {
                    colors: {
                        'osh-light-bg': '#F0F4F8',
                        'osh-light-card': '#FFFFFF',
                        'osh-light-text': '#1F2937',
                        'osh-light-accent': '#2563EB',
                        'osh-dark-bg': '#0F172A',
                        'osh-dark-navbar': '#1E293B',
                        'osh-dark-card': '#1E293B',
                        'osh-dark-text': '#E2E8F0',
                        'osh-dark-accent': '#3B82F6',
                    }
                }
            }
        };
    </script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <!-- Estilos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <link href="https://fonts.cdnfonts.com/css/museo-500" rel="stylesheet">
    <style>
        body { font-family: 'Museo 300', sans-serif; }
        .museo-500 { font-family: 'Museo 500', sans-serif; }
        /* Definimos form-radio aquí pero lo ocultaremos */
        .form-radio { @apply h-5 w-5 text-osh-light-accent dark:text-osh-dark-accent border-gray-300 dark:border-gray-600 focus:ring-osh-light-accent dark:focus:ring-osh-dark-accent dark:bg-gray-700 dark:ring-offset-gray-800 transition duration-150; }
        .form-radio:disabled { @apply opacity-50 cursor-not-allowed; }
        .card-shadow { box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.07), 0 4px 6px -2px rgba(0, 0, 0, 0.05); }
        [x-cloak] { display: none !important; }

        /* Estilos del Spinner (si aún lo quieres para la carga inicial) */
        #loading-splash {
            display: flex;
            align-items: center;
            justify-content: center;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #F0F4F8; /* CORREGIDO: Usar color light-bg */
            z-index: 9999;
            transition: opacity 0.5s ease-out;
        }
        .dark #loading-splash {
            background-color: #0F172A; /* CORREGIDO: Usar color dark-bg */
        }
        .loading-spinner {
            border: 5px solid #e0e0e0;
            border-top: 5px solid #2563EB; /* CORREGIDO: Usar color light-accent */
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
        }
        .dark .loading-spinner {
            border-color: #334155;
            border-top-color: #3B82F6; /* CORREGIDO: Usar color dark-accent */
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>

    <!-- Configurar tema inicial antes de renderizar -->
    <script>
        try {
            if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        } catch (e) { document.documentElement.classList.remove('dark'); }
    </script>

    {{-- SCRIPT DE LA APLICACIÓN MOVIDO AL HEAD --}}
    <script>
        function surveyApp(config) {
            return {
                token: config.token,
                submitUrl: config.submitUrl,
                totalQuestions: config.totalQuestions,
                savedAnswers: config.savedAnswers || {},
                savingStatus: {},
                isSubmittingFinal: false,
                answeredCount: Object.keys(config.savedAnswers).length,
                consecutiveFlow: config.consecutiveFlow,
                nextSurveyUrl: config.nextSurveyUrl,
                thankYouUrl: config.thankYouUrl,
                isLoading: true, // Estado de carga

                // --- Computed Properties ---
                get progressPercentage() {
                    if (this.totalQuestions === 0) return this.answeredCount > 0 ? 100 : 0;
                    return Math.round((this.answeredCount / this.totalQuestions) * 100);
                },
                get isComplete() {
                    return this.answeredCount >= this.totalQuestions;
                },

                // --- Methods ---
                // MÉTODO init() MÁGICO DE ALPINE
                init() {
                    try {
                        console.log('App iniciada. Progreso:', this.answeredCount, '/', this.totalQuestions);
                        // CORRECCIÓN: Verificar si el meta tag existe antes de leer 'content'
                        const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
                        if (csrfTokenMeta) {
                            axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfTokenMeta.content;
                        } else {
                            console.warn('Meta tag CSRF-TOKEN no encontrado.');
                            // Considerar mostrar un error al usuario si es crítico
                        }
                        axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
                        this.updateProgressUI(this.answeredCount);

                        // Ocultar el splash screen
                        setTimeout(() => {
                            this.isLoading = false;
                            const splash = document.getElementById('loading-splash');
                            if (splash) {
                                splash.style.opacity = '0';
                                setTimeout(() => { splash.style.display = 'none'; }, 500); // 500ms para la transición
                            }
                        }, 300); // 300ms de retraso mínimo
                    } catch(e) {
                        console.error("Error fatal en init() de Alpine:", e);
                        // Si algo falla aquí, al menos ocultamos el spinner para que no se atasque
                        this.isLoading = false;
                        const splash = document.getElementById('loading-splash');
                        if (splash) splash.style.display = 'none';
                    }
                },

                updateProgressUI(newCount) {
                    if (newCount > this.totalQuestions) newCount = this.totalQuestions;
                    this.answeredCount = newCount;
                },

                saveAnswer(radioInput) {
                    const preguntaId = radioInput.dataset.preguntaId;
                    const opcionId = radioInput.value;

                    this.savingStatus[preguntaId] = 'saving';
                    this.savedAnswers[preguntaId] = opcionId;

                    axios.post(this.submitUrl, {
                        pregunta_id: preguntaId,
                        opcion_id: opcionId,
                    })
                        .then(response => {
                            this.savingStatus[preguntaId] = 'saved';
                            this.updateProgressUI(Object.keys(this.savedAnswers).length);
                            setTimeout(() => { this.savingStatus[preguntaId] = null; }, 2000);
                        })
                        .catch(error => {
                            this.savingStatus[preguntaId] = 'error';
                            console.error("Error al guardar respuesta:", error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error de Conexión',
                                text: 'No se pudo guardar tu respuesta. Por favor, verifica tu conexión e inténtalo de nuevo.',
                                confirmButtonColor: '#E11D48'
                            });

                            radioInput.checked = false;
                            delete this.savedAnswers[preguntaId];
                            this.updateProgressUI(Object.keys(this.savedAnswers).length);
                        })
                        .finally(() => {});
                },

                finishSurvey() {
                    if (!this.isComplete || this.isSubmittingFinal) return;
                    this.isSubmittingFinal = true;

                    Swal.fire({
                        title: 'Finalizando...',
                        text: 'Estamos guardando y procesando tus respuestas.',
                        allowOutsideClick: false,
                        didOpen: () => { Swal.showLoading(); }
                    });

                    setTimeout(() => {
                        if (this.consecutiveFlow && this.nextSurveyUrl) {
                            window.location.href = this.nextSurveyUrl;
                        } else {
                            window.location.href = this.thankYouUrl;
                        }
                    }, 1500);
                }
            }
        }
    </script>
</head>
<body class="bg-osh-light-bg dark:bg-osh-dark-bg transition-colors duration-500"
      x-cloak
      x-data="surveyApp({
         token: '{{ $encuestaActual->token }}',
         submitUrl: '{{ route("encuesta.respuesta", ["token" => $encuestaActual->token]) }}',
         totalQuestions: {{ $encuestaActual->total_preguntas ?? 0 }},
         initialProgress: {{ $encuestaActual->progreso_actual ?? 0 }},
         savedAnswers: {{ json_encode($respuestasGuardadas) }},
         consecutiveFlow: {{ $contestar_consecutivamente ? 'true' : 'false' }},
         nextSurveyUrl: '{{ $contestar_consecutivamente && $encuestasFaltantes->isNotEmpty() ? route("encuesta.show", ["token" => $encuestasFaltantes->first()->token]) : '' }}',
         thankYouUrl: '{{ route("encuesta.agradecimiento", ["token" => $encuestaActual->token]) }}'
     })"
      x-init="init()">

{{-- Splash Screen de Carga (AHORA CONTROLADO POR ALPINE) --}}
<div id="loading-splash" x-show="isLoading" x-transition:leave="transition ease-in duration-500" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak>
    <div class="loading-spinner"></div>
</div>

{{-- Contenido Principal (Oculto hasta que isLoading = false) --}}
<div x-show="!isLoading" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
     class="min-h-screen flex flex-col items-center py-6 sm:py-10 px-4">

    {{-- Contenedor Principal (Tarjeta) --}}
    <div class="w-full max-w-4xl card-shadow bg-osh-light-card dark:bg-osh-dark-card rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">

        {{-- 1. Encabezado (Logos, Título, Progreso) --}}
        {{-- CORRECCIÓN: Clases 'sticky top-0 z-10' ELIMINADAS --}}
        <div class="bg-osh-light-card dark:bg-osh-dark-navbar border-b border-gray-200 dark:border-gray-700">

            {{-- Sección de Logos --}}
            <div class="flex justify-between items-center p-4 sm:p-6 bg-gray-50 dark:bg-osh-dark-bg border-b border-gray-200 dark:border-gray-700">
                <img src="{{ $encuestaActual->empleado->empresa->logo_url ?? asset('img/placeholder_empresa.png') }}"
                     alt="{{ $encuestaActual->empleado->empresa->nombre ?? 'Logo Empresa' }}" class="h-10 w-auto max-h-12 object-contain"
                     onerror="this.src='{{ asset('img/placeholder_empresa.png') }}'; this.onerror=null;">
                <img src="{{ asset('img/osh_logo.png') }}"
                     alt="OSH Consulting Logo" class="h-8 w-auto object-contain dark:filter dark:invert"
                     onerror="this.src='https://placehold.co/100x32/333333/FFFFFF?text=OSH';">
            </div>

            {{-- Sección de Título y Progreso --}}
            <div class="p-4 sm:p-6 text-center space-y-3">
                <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">ID de Sesión: {{ substr($encuestaActual->token, 0, 5) }}... (Anónimo)</p>
                <h1 class="text-2xl font-semibold text-osh-light-text dark:text-osh-dark-text museo-500">{{ $encuestaActual->cuestionario->nombre ?? 'Cuestionario' }}</h1>

                <div class="w-full max-w-md mx-auto pt-2">
                    <div class="flex justify-between text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">
                        <span>Progreso</span>
                        <span x-text="`${progressPercentage}%`">0%</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5 shadow-inner overflow-hidden">
                        <div id="progress-bar" class="bg-osh-light-accent dark:bg-osh-dark-accent h-2.5 rounded-full transition-all duration-500 ease-out"
                             :style="`width: ${progressPercentage}%`"></div>
                    </div>
                    <p id="progress-text" class="text-center text-xs text-gray-500 dark:text-gray-400 mt-1"
                       x-text="`Pregunta ${answeredCount} de ${totalQuestions}`"></p>
                </div>
            </div>
        </div>

        {{-- 2. Contenido de la Encuesta (Formulario) --}}
        <div class="p-6 sm:p-8">
            <div class="text-center mb-10">
                <h2 class="text-xl font-semibold text-osh-light-text dark:text-osh-dark-text museo-500">Instrucciones</h2>
                <p class="text-gray-600 dark:text-gray-300 mt-2 max-w-2xl mx-auto">Por favor, lee atentamente cada pregunta y selecciona la opción que mejor describa tu situación. Tu respuesta es confidencial y será guardada automáticamente.</p>
            </div>

            <form id="encuesta-form" class="space-y-12">
                @csrf
                @php $preguntaIndex = 0; @endphp
                @forelse($encuestaActual->cuestionario->secciones->sortBy('orden') as $seccion)

                    <fieldset class="border border-gray-200 dark:border-gray-700 rounded-lg p-5 pt-3 shadow-sm bg-gray-50/50 dark:bg-osh-dark-bg/30">
                        <legend class="text-lg font-semibold text-osh-light-accent dark:text-osh-dark-accent px-3 museo-500 -ml-3">
                            {{ $seccion->nombre }}
                        </legend>

                        <div class="space-y-8 divide-y divide-gray-200 dark:divide-gray-700/50 mt-4">
                            @foreach($seccion->preguntas->sortBy('orden') as $pregunta)
                                @php
                                    $preguntaIndex++;
                                    $respuestas = $pregunta->tipoPregunta->opcionesRespuesta->sortBy('valor');
                                    $respuestaGuardadaId = $respuestasGuardadas[$pregunta->id] ?? null;
                                @endphp

                                <div class="pt-6 first:pt-0">
                                    <p class="font-medium text-osh-light-text dark:text-osh-dark-text text-base">
                                        <span class="font-bold text-osh-light-accent dark:text-osh-dark-accent">{{ $pregunta->numero ?? $preguntaIndex }}.</span> {{ $pregunta->texto }}
                                    </p>

                                    <div class="mt-4 space-y-3">
                                        @foreach($respuestas as $opcion)
                                            <label for="opcion-{{ $opcion->id }}"
                                                   class="flex items-center justify-center p-3 border border-gray-300 dark:border-gray-600 rounded-lg transition-all duration-150"
                                                   :class="{
                                                           'bg-blue-100 dark:bg-gray-700 border-osh-light-accent dark:border-osh-dark-accent ring-2 ring-blue-300 dark:ring-blue-500': savedAnswers['{{ $pregunta->id }}'] == '{{ $opcion->id }}',
                                                           'hover:bg-blue-50 dark:hover:bg-gray-700 cursor-pointer': !savedAnswers.hasOwnProperty('{{ $pregunta->id }}'),
                                                           'opacity-60 cursor-not-allowed': savedAnswers.hasOwnProperty('{{ $pregunta->id }}') && savedAnswers['{{ $pregunta->id }}'] != '{{ $opcion->id }}'
                                                       }">
                                                <input type="radio" id="opcion-{{ $opcion->id }}"
                                                       name="respuesta-{{ $pregunta->id }}"
                                                       value="{{ $opcion->id }}"
                                                       data-pregunta-id="{{ $pregunta->id }}"
                                                       @change="saveAnswer($event.target)"
                                                       class="form-radio save-trigger hidden"
                                                       {{ $opcion->id == $respuestaGuardadaId ? 'checked' : '' }}
                                                       required
                                                       :disabled="savedAnswers.hasOwnProperty('{{ $pregunta->id }}')">
                                                <span class="text-sm text-gray-700 dark:text-gray-200">{{ $opcion->texto }}</span>
                                            </label>
                                        @endforeach
                                    </div>

                                    <div class="h-4 mt-2">
                                            <span :id="'status-' + {{ $pregunta->id }}" class="text-xs italic transition-opacity duration-300 opacity-0"
                                                  :class="{ 'opacity-100 text-green-600 dark:text-green-500': savingStatus['{{ $pregunta->id }}'] === 'saved', 'opacity-100 text-red-600 dark:text-red-500': savingStatus['{{ $pregunta->id }}'] === 'error', 'opacity-100 text-gray-500': savingStatus['{{ $pregunta->id }}'] === 'saving' }"
                                                  x-text="savingStatus['{{ $pregunta->id }}'] === 'saving' ? 'Guardando...' : (savingStatus['{{ $pregunta->id }}'] === 'saved' ? 'Guardado' : (savingStatus['{{ $pregunta->id }}'] === 'error' ? 'Error al guardar' : ''))">
                                            </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </fieldset>

                @empty
                    <p class="text-lg text-red-500 text-center">No se encontraron preguntas para esta encuesta.</p>
                @endforelse

                {{-- Botón de Finalizar --}}
                <div classs="flex justify-center pt-8 border-t border-gray-200 dark:border-gray-700">
                    <button type="button" @click="finishSurvey"
                            :disabled="!isComplete || isSubmittingFinal"
                            class="w-full md:w-1/2 mx-auto py-3 px-6 bg-osh-light-accent text-white text-lg font-semibold rounded-lg shadow-md hover:bg-blue-700 transition duration-150
                                       disabled:opacity-50 disabled:cursor-not-allowed disabled:bg-gray-400
                                       flex items-center justify-center museo-500">
                            <span x-show="!isSubmittingFinal">
                                <i class="fas fa-check-circle mr-2"></i> Finalizar Encuesta
                            </span>
                        <span x-show="isSubmittingFinal">
                                <i class="fas fa-spinner fa-spin mr-2"></i> Finalizando...
                            </span>
                    </button>
                </div>

                {{-- Mensaje de flujo consecutivo --}}
                @if($contestar_consecutivamente && $encuestasFaltantes->isNotEmpty())
                    <div class="mt-10 border-t border-gray-200 dark:border-gray-700 pt-6 text-center">
                        <h3 class="text-lg font-semibold text-orange-600 museo-500">Próximos Pasos</h3>
                        <p class="text-gray-600 dark:text-gray-300 mt-2">Al finalizar, serás redirigido automáticamente a la siguiente encuesta:</p>
                        <p class="font-semibold text-gray-700 dark:text-gray-100 mt-1">{{ $encuestasFaltantes->first()->cuestionario->nombre }}</p>
                    </div>
                @elseif($contestar_consecutivamente)
                    <div class="mt-10 border-t border-gray-200 dark:border-gray-700 pt-6 text-center">
                        <h3 class="text-lg font-semibold text-green-600 museo-500">¡Felicidades!</h3>
                        <p class="text-gray-600 dark:text-gray-300 mt-2">Has completado todas las encuestas asignadas. Presiona "Finalizar" para terminar.</p>
                    </div>
                @endif
            </form>
        </div>
    </div>
</div>

</div>

{{-- SCRIPT MOVIDO AL HEAD --}}

</body>
</html>

