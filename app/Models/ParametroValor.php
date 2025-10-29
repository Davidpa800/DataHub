<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ParametroValor extends Model
{
    use HasFactory;

    protected $table = 'parametro_valores';

    protected $fillable = [
        'parametro_id',
        'value',
        'empresa_id',
        'contrato_id',
    ];

    /**
     * Relación: Pertenece a un Parámetro Maestro.
     */
    public function parametro(): BelongsTo
    {
        return $this->belongsTo(Parametro::class, 'parametro_id');
    }

    /**
     * Relación: Pertenece a una Empresa.
     */
    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    /**
     * Relación: Pertenece a un Contrato.
     */
    public function contrato(): BelongsTo
    {
        return $this->belongsTo(Contrato::class);
    }
}
