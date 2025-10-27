<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contrato extends Model
{
    use HasFactory;

    protected $table = 'contratos';

    protected $fillable = [
        'empresa_id',
        'nombre',
        'descripcion',
        'fecha_inicio',
        'fecha_fin',
        'estado',
    ];

    /**
     * Relación: Un contrato pertenece a una Empresa.
     */
    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    /**
     * Relación: Un contrato tiene muchas Encuestas Asignadas.
     */
    public function encuestasAsignadas(): HasMany
    {
        return $this->hasMany(EncuestaAsignada::class);
    }

    /**
     * Relación: Un contrato puede tener muchos Cuestionarios (Guías).
     */
    public function cuestionarios(): BelongsToMany
    {
        // Usa la tabla pivote 'contrato_cuestionario'
        return $this->belongsToMany(Cuestionario::class, 'contrato_cuestionario');
    }

    /**
     * Relación: Un contrato (indirectamente) tiene muchos Empleados.
     * Esta relación es opcional, ya que los empleados se ligan a la empresa,
     * pero puede ser útil si los contratos definen a los empleados.
     */
    public function empleados(): HasMany
    {
        return $this->hasMany(Empleado::class);
    }
}

