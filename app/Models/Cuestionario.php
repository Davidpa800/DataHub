<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cuestionario extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
        'activo',
    ];

    /**
     * Define la relación: Un Cuestionario tiene muchas Secciones.
     */
    public function secciones(): HasMany
    {
        return $this->hasMany(Seccion::class);
    }

    /**
     * Define la relación: Un Cuestionario tiene muchas Preguntas.
     */
    public function preguntas(): HasMany
    {
        return $this->hasMany(Pregunta::class);
    }

    /**
     * Define la relación: Muchos Cuestionarios pertenecen a muchos Contratos.
     */
    public function contratos(): BelongsToMany
    {
        return $this->belongsToMany(Contrato::class, 'contrato_cuestionario');
    }
}
