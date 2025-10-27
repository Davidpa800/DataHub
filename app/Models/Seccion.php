<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Seccion extends Model
{
    use HasFactory;

    protected $table = 'secciones';

    /**
     * Los atributos que no son asignables masivamente.
     * Dejamos $guarded vacío para permitir la asignación masiva en el Seeder.
     * @var array
     */
    protected $guarded = [];

    /**
     * Relación: Una sección pertenece a un Cuestionario.
     */
    public function cuestionario(): BelongsTo
    {
        return $this->belongsTo(Cuestionario::class);
    }

    /**
     * Relación: Una sección tiene muchas Preguntas.
     */
    public function preguntas(): HasMany
    {
        return $this->hasMany(Pregunta::class);
    }
}

