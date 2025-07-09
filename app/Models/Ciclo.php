<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ciclo extends Model
{
    use HasFactory;

    /**
     * Tabla asociada al modelo.
     */
    protected $table = 'ciclos';

    /**
     * Campos que se pueden rellenar masivamente.
     */
    protected $fillable = ['nombre'];

    /**
     * ¡NUEVO! Definimos la relación con las Correrías.
     * Un Ciclo puede tener MUCHAS Correrías (Rutas).
     */
    public function correrias()
    {
        // Esto le dice a Laravel: "Cuando busque las correrías de este ciclo,
        // ve a la tabla 'correrias' y encuentra todos los registros
        // donde 'ciclo_id' sea igual al 'id' de este ciclo".
        return $this->hasMany(Correria::class);
    }
}