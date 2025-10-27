<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Seccion;
use App\Models\PreguntaTipo;
use App\Models\Respuesta;

class Pregunta extends Model
{
    use HasFactory;

    protected $table = 'preguntas';

    /**
     * Los atributos que no son asignables masivamente.
     * @var array
     */
    protected $guarded = [];

    /**
     * Relación: Una pregunta pertenece a un Cuestionario.
     */
    public function cuestionario(): BelongsTo
    {
        return $this->belongsTo(Cuestionario::class);
    }

    /**
     * Relación: Una pregunta pertenece a una Sección.
     */
    public function seccion(): BelongsTo
    {
        return $this->belongsTo(Seccion::class);
    }

    /**
     * Relación: Una pregunta pertenece a un Tipo de Pregunta.
     */
    public function tipoPregunta(): BelongsTo
    {
        return $this->belongsTo(PreguntaTipo::class, 'pregunta_tipo_id');
    }

    /**
     * Relación: Carga las Opciones de Respuesta a través del Tipo de Pregunta.
     * ¡Este es el método que faltaba o era incorrecto!
     */
    public function opcionesRespuestas()
    {
        // La lógica es: la Pregunta tiene un Tipo, y el Tipo tiene Opciones.
        // Llama a la relación 'opcionesRespuesta' (singular) definida en el modelo PreguntaTipo.
        return $this->tipoPregunta->opcionesRespuesta();
    }

    /**
     * Relación: Una pregunta tiene muchas Respuestas (de usuarios).
     */
    public function respuestas(): HasMany
    {
        return $this->hasMany(Respuesta::class);
    }
}

