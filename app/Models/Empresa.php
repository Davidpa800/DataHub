<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany; // Importar HasMany

class Empresa extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre',
        'razon_social',         // <-- Añadido aquí
        'rfc',
        'direccion',
        'logo_path',            // <-- Añadido aquí (si lo quieres fillable)
        'actividad_principal',  // <-- Añadido aquí
    ];

    /**
     * Define la relación: Una Empresa tiene muchos Contratos.
     */
    public function contratos(): HasMany
    {
        return $this->hasMany(Contrato::class);
    }

    /**
     * Define la relación: Una Empresa tiene muchos Empleados.
     */
    public function empleados(): HasMany
    {
        return $this->hasMany(Empleado::class);
    }

    /**
     * Accesor para obtener la URL completa del logo.
     * Asume que 'logo_path' guarda la ruta relativa dentro de 'storage/app/public'.
     * Requiere haber ejecutado 'php artisan storage:link'.
     *
     * @return string|null
     */
    public function getLogoUrlAttribute(): ?string
    {
        if ($this->logo_path) {
            // Usa Storage::url() para generar la URL pública correcta
            return \Illuminate\Support\Facades\Storage::disk('public')->url($this->logo_path);
        }
        // Devuelve null o una URL de placeholder si no hay logo
        // return 'https://placehold.co/100x100/cccccc/333333?text=Logo';
        return null;
    }
}

