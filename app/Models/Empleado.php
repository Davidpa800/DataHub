<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Empleado extends Model
{
    use HasFactory;

    protected $fillable = [
        'empresa_id', 'contrato_id', 'clave', 'nombre', 'apellido_paterno',
        'apellido_materno', 'email', 'puesto', 'departamento',
        'centro_trabajo', 'fecha_ingreso', 'datos_adicionales'
    ];

    // Propiedad calculada para el nombre completo
    protected $appends = ['nombre_completo'];

    /**
     * Accesor para obtener el nombre completo del empleado.
     */
    protected function getNombreCompletoAttribute(): string
    {
        return trim("{$this->nombre} {$this->apellido_paterno} {$this->apellido_materno}");
    }

    /**
     * Relación: Un empleado pertenece a una Empresa.
     */
    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    /**
     * Relación: Un empleado tiene muchas Encuestas Asignadas. <--- ¡NECESARIO PARA LA VISTA!
     */
    public function encuestasAsignadas(): HasMany
    {
        return $this->hasMany(EncuestaAsignada::class);
    }
}

