<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Respuesta extends Model
{
    use HasFactory;

    protected $table = 'respuestas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'encuesta_asignada_id',
        'pregunta_id',
        'opcion_respuesta_id',
        'respuesta_texto',
        'valor_respuesta',
    ];

    /**
     * Relación: Una respuesta pertenece a una Encuesta Asignada.
     */
    public function encuestaAsignada(): BelongsTo
    {
        return $this->belongsTo(EncuestaAsignada::class, 'encuesta_asignada_id');
    }

    /**
     * Relación: Una respuesta pertenece a una Pregunta.
     */
    public function pregunta(): BelongsTo
    {
        return $this->belongsTo(Pregunta::class);
    }

    /**
     * Relación: Una respuesta (opcionalmente) pertenece a una Opción de Respuesta.
     */
    public function opcionRespuesta(): BelongsTo
    {
        return $this->belongsTo(OpcionRespuesta::class, 'opcion_respuesta_id');
    }
}
