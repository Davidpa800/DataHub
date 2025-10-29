<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Parametro extends Model
{
    use HasFactory;

    protected $table = 'parametros';

    protected $fillable = [
        'key',
        'default_value',
        'param_group',
        'module',
        'description',
    ];

    /**
     * Relación: Un Parámetro Maestro puede tener múltiples anulaciones de valor.
     */
    public function valores(): HasMany
    {
        return $this->hasMany(ParametroValor::class, 'parametro_id');
    }
}
