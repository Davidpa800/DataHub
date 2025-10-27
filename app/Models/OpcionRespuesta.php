<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OpcionRespuesta extends Model
{
    use HasFactory;

    protected $table = 'opciones_respuesta';

    protected $fillable = [
        'pregunta_tipo_id',
        'texto',
        'valor',
        'orden',
    ];

    /**
     * Define la relación: Una Opción pertenece a un Tipo de Pregunta.
     */
    public function preguntaTipo(): BelongsTo
    {
        return $this->belongsTo(PreguntaTipo::class);
    }
}
