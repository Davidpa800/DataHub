<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PreguntaTipo extends Model
{
    use HasFactory;

    protected $table = 'pregunta_tipos';

    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
    ];

    /**
     * Define la relación: Un Tipo de Pregunta tiene muchas Opciones de Respuesta asociadas.
     */
    public function opcionesRespuesta(): HasMany
    {
        return $this->hasMany(OpcionRespuesta::class);
    }

    /**
     * Define la relación: Un Tipo de Pregunta tiene muchas Preguntas.
     */
    public function preguntas(): HasMany
    {
        return $this->hasMany(Pregunta::class);
    }
}
