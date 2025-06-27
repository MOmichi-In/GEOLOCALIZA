<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnidadTrabajo extends Model
{
    use HasFactory;

    protected $table = 'unidad_trabajos'; // Asegúrate que el nombre de la tabla es correcto

    protected $fillable = [
        'nombre',
    ];

    /**
     * Obtener los usuarios asociados a esta unidad de trabajo.
     */
    public function users()
    {
        // Asume que en tu modelo User tienes una columna 'unidad_trabajo_id'
        return $this->hasMany(User::class, 'unidad_trabajo_id');
    }
}