<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EncuestaAsignada;
use App\Models\Pregunta;
use App\Models\OpcionRespuesta; // Importar OpcionRespuesta
use App\Models\Respuesta;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Carbon\Carbon; // Importar Carbon

class EncuestaEmpleadoController extends Controller
{
    // --- Listas de Puntuación Inversa (Sentido Positivo) ---
    // Estas preguntas se califican al revés (Siempre=0, Nunca=4)
    // El seeder las tiene como (Siempre=4, Nunca=0), así que debemos invertirlas
    private $positiveG2 = [
        'P1', 'P4', 'P12', 'P13', 'P14', 'P15', 'P16', 'P17', 'P18', 'P19', 'P20',
        'P25', 'P28', 'P29', 'P30', 'P31', 'P32', 'P33', 'P34', 'P35', 'P36',
        'P39', 'P40', 'P41'
    ];

    private $positiveG3 = [
        'P1', 'P2', 'P3', 'P15', 'P16', 'P17', 'P18', 'P19', 'P20', 'P22', 'P23',
        'P24', 'P25', 'P32', 'P33', 'P34', 'P35', 'P36', 'P37', 'P38', 'P39',
        'P40', 'P41', 'P42', 'P43', 'P44', 'P45', 'P46', 'P47', 'P48', 'P49',
        'P50', 'P51', 'P52', 'P53', 'P54', 'P55', 'P58', 'P59', 'P60', 'P61',
        'P62', 'P63', 'P64', 'P65', 'P66', 'P67', 'P68', 'P69', 'P70', 'P71', 'P72'
    ];
    // Las preguntas P21, P22, P23, P24, P26, P27 (G2) y P21, P26, P27, P28, P29, P30, P31, P56, P57 (G3)
    // son de sentido negativo y se califican como vienen (Siempre=4, Nunca=0)

    /**
     * Muestra la vista del cuestionario para el empleado usando un token único.
     */
    public function showEncuesta(string $token)
    {
        // Buscar la asignación pendiente por el token
        $asignacion = EncuestaAsignada::where('token', $token)
            ->whereIn('estado', ['pendiente', 'en_progreso']) // Aceptar pendientes o en progreso
            ->with([
                // Cargar preguntas, secciones, y opciones de respuesta anidadas
                'cuestionario.secciones.preguntas.tipoPregunta.opcionesRespuesta' => function($query) {
                    $query->orderBy('valor', 'desc'); // Ordenar opciones (Siempre, Casi Siempre...)
                },
                'empleado.empresa' // Cargar la empresa para el logo
            ])
            ->first();

        // Si no se encuentra o ya está completada, mostrar error
        if (!$asignacion) {
            Log::warning("Intento de acceso a encuesta inválida o completada.", ['token' => $token]);
            return view('encuesta.error', [
                'message' => 'Este enlace de encuesta no es válido, ya ha sido completado o ha expirado. Por favor, contacta a tu administrador.'
            ]);
        }

        // Marcar como 'en_progreso' si es el primer acceso
        if ($asignacion->estado == 'pendiente') {
            $asignacion->update(['estado' => 'en_progreso']);
        }

        // --- Lógica de Encuestas Consecutivas ---
        $contestar_consecutivamente = config('app.nom035_consecutiva', false); // <-- Parámetro de configuración (ej: en .env)

        $encuestasFaltantes = collect();
        $encuestaActual = $asignacion;

        if ($contestar_consecutivamente) {
            // Lógica avanzada: Buscar otras encuestas (G1, G2, G3) asignadas a este empleado
            $encuestasPendientes = EncuestaAsignada::where('empleado_id', $asignacion->empleado_id)
                ->where('estado', 'pendiente') // Solo las que no ha empezado
                ->where('id', '!=', $asignacion->id)
                ->with('cuestionario:id,nombre')
                ->orderBy('cuestionario_id') // Asume que ID 1=G1, 2=G2, 3=G3
                ->get();

            $encuestasFaltantes = $encuestasPendientes;
        }

        // Cargar respuestas ya guardadas (si está en_progreso)
        $respuestasGuardadas = Respuesta::where('encuesta_asignada_id', $asignacion->id)
            ->pluck('opcion_respuesta_id', 'pregunta_id');

        return view('encuesta.cuestionario', compact(
            'encuestaActual',
            'encuestasFaltantes',
            'contestar_consecutivamente',
            'respuestasGuardadas'
        ));
    }

    /**
     * Guarda una respuesta de encuesta (usado por Vue/AJAX).
     */
    public function storeRespuesta(Request $request, string $token)
    {
        // Validar respuesta
        $validated = $request->validate([
            'pregunta_id' => 'required|exists:preguntas,id',
            'opcion_id' => 'required_without:respuesta_texto|nullable|exists:opciones_respuesta,id',
            'respuesta_texto' => 'required_without:opcion_id|nullable|string|max:500', // Para preguntas abiertas
        ]);

        $asignacion = EncuestaAsignada::where('token', $token)
            ->whereIn('estado', ['pendiente', 'en_progreso'])
            ->with('cuestionario') // Cargar cuestionario para saber si es G2 o G3
            ->first();

        if (!$asignacion) {
            return response()->json(['message' => 'Asignación inválida o terminada.'], 403);
        }

        try {
            DB::beginTransaction();

            $pregunta = Pregunta::find($validated['pregunta_id']);
            $opcion = $validated['opcion_id'] ? OpcionRespuesta::find($validated['opcion_id']) : null;

            // Texto de la respuesta (para reportes cualitativos)
            $respuestaTexto = $opcion ? $opcion->texto : ($validated['respuesta_texto'] ?? null);

            // --- CÁLCULO DE PONDERACIÓN (VALOR) ---
            $valorDB = $opcion->valor ?? 0; // Valor del seeder (Ej: Siempre=4, Nunca=0)
            $valorFinal = $valorDB; // Por defecto, el valor es el de la BD (sentido negativo)

            $cuestionarioCodigo = $asignacion->cuestionario->codigo;
            $numeroPregunta = $pregunta->numero;

            // Invertir puntuación si es una pregunta de sentido POSITIVO
            if ($cuestionarioCodigo === 'NOM035_G2' && in_array($numeroPregunta, $this->positiveG2)) {
                // Invertir G2: (4 - 4 = 0 [Siempre])
                $valorFinal = 4 - $valorDB;
            }
            elseif ($cuestionarioCodigo === 'NOM035_G3' && in_array($numeroPregunta, $this->positiveG3)) {
                // Invertir G3: (4 - 4 = 0 [Siempre])
                $valorFinal = 4 - $valorDB;
            }
            // NOTA: Para Guía I (NOM035_G1), 'Sí' (valor 1) y 'No' (valor 0) se quedan igual.

            // Crear o actualizar la respuesta
            Respuesta::updateOrCreate(
                [
                    // Claves para buscar
                    'encuesta_asignada_id' => $asignacion->id,
                    'pregunta_id' => $validated['pregunta_id'],
                ],
                [
                    // Valores a insertar/actualizar
                    'opcion_respuesta_id' => $opcion->id ?? null,
                    'respuesta_texto' => $respuestaTexto, // Texto (Ej: "Siempre")
                    'valor_respuesta' => $valorFinal,     // Ponderación (Ej: 0)
                ]
            );

            // Actualizar el progreso de la asignación
            $progresoActual = Respuesta::where('encuesta_asignada_id', $asignacion->id)->count();

            // Comprobar si está completa
            $completado = ($progresoActual >= $asignacion->total_preguntas);

            $asignacion->estado = $completado ? 'completado' : 'en_progreso';
            $asignacion->progreso_actual = $progresoActual;

            if ($completado && !$asignacion->fecha_completado) { // Guardar solo la primera vez
                $asignacion->fecha_completado = Carbon::now();
                // event(new EncuestaCompletada($asignacion));
            }

            $asignacion->save();

            DB::commit();

            // Buscar si hay una siguiente encuesta en el flujo
            $siguienteUrl = null;
            $contestar_consecutivamente = config('app.nom035_consecutiva', false);
            if ($completado && $contestar_consecutivamente) {
                $siguienteEncuesta = EncuestaAsignada::where('empleado_id', $asignacion->empleado_id)
                    ->where('estado', 'pendiente')
                    ->orderBy('cuestionario_id')
                    ->first();
                if ($siguienteEncuesta) {
                    $siguienteUrl = route('encuesta.show', ['token' => $siguienteEncuesta->token]);
                }
            }

            return response()->json([
                'status' => 'success',
                'progreso' => $asignacion->progreso_actual,
                'total' => $asignacion->total_preguntas,
                'completado' => $completado,
                'siguiente_url' => $completado ? ($siguienteUrl ?? route('encuesta.agradecimiento', ['token' => $asignacion->token])) : null
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error al guardar respuesta de encuesta (Token: {$token}): " . $e->getMessage());
            return response()->json(['message' => 'Error interno al guardar la respuesta.'], 500);
        }
    }

    /**
     * Muestra la pantalla de agradecimiento después de completar la encuesta.
     */
    public function showAgradecimiento(string $token)
    {
        $asignacion = EncuestaAsignada::where('token', $token)
            ->first();

        if (!$asignacion) {
            return redirect()->route('encuesta.error');
        }

        return view('encuesta.agradecimiento', compact('token'));
    }
}

