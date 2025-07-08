<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnidadTrabajo extends Model
{
    use HasFactory;

    /**
     * El nombre de la tabla en la base de datos.
     */
    protected $table = 'unidad_trabajos';

    /**
     * Los atributos que se pueden asignar de forma masiva.
     * Nos aseguramos de que 'supervisor_id' esté aquí.
     */
    protected $fillable = [
        'nombre',
        'supervisor_id',
    ];

    /**
     * RELACIÓN: Una unidad de trabajo pertenece a UN supervisor.
     */
    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    /**
     * RELACIÓN: Una unidad de trabajo puede tener MUCHOS operadores asignados.
     */
    public function operadores()
    {
        // Esta relación busca en la tabla 'users' a todos aquellos
        // cuya columna 'unidad_trabajo_id' coincide con el 'id' de esta unidad.
        return $this->hasMany(User::class, 'unidad_trabajo_id');
    }
}