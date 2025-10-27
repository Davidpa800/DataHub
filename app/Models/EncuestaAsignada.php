<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EncuestaAsignada extends Model
{
    use HasFactory;

    protected $table = 'encuestas_asignadas';

    protected $fillable = [
        'empleado_id',
        'cuestionario_id',
        'contrato_id', // Añadido para facilitar consultas
        'token', // <--- CORREGIDO: Debe ser 'token' (Nombre de la columna en BD y usado en el controlador)
        'fecha_asignacion',
        'fecha_limite',
        'fecha_completado',
        'estado', // Ej: 'pendiente', 'en_progreso', 'completado'
        'progreso_actual',
        'total_preguntas', // Podría venir del cuestionario relacionado
    ];

    protected $casts = [
        'fecha_asignacion' => 'datetime',
        'fecha_limite' => 'datetime',
        'fecha_completado' => 'datetime',
    ];

    /**
     * Relación: Una encuesta asignada pertenece a un empleado.
     */
    public function empleado(): BelongsTo
    {
        return $this->belongsTo(Empleado::class);
    }

    /**
     * Relación: Una encuesta asignada pertenece a un cuestionario maestro.
     */
    public function cuestionario(): BelongsTo
    {
        return $this->belongsTo(Cuestionario::class); // Asegúrate de tener el modelo Cuestionario
    }

    /**
     * Relación: Una encuesta asignada pertenece a un contrato.
     */
    public function contrato(): BelongsTo
    {
        return $this->belongsTo(Contrato::class);
    }


    /**
     * Relación: Una encuesta asignada tiene muchas respuestas.
     */
    public function respuestas(): HasMany
    {
        return $this->hasMany(Respuesta::class); // Asegúrate de tener el modelo Respuesta
    }
}
