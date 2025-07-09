<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Correria extends Model
{
    use HasFactory;

    /**
     * Tabla asociada al modelo.
     */
    protected $table = 'correrias';

    /**
     * Campos que se pueden rellenar masivamente.
     */
    protected $fillable = ['nombre', 'ciclo_id'];

    /**
     * ¡LA RELACIÓN CLAVE!
     * Una Correría pertenece a UN solo Ciclo.
     */
    public function ciclo()
    {
        // Esto le dice a Laravel: "Para encontrar el ciclo de esta correría,
        // busca en la tabla 'ciclos' un registro cuyo 'id' coincida con
        // el valor de mi columna 'ciclo_id'".
        return $this->belongsTo(Ciclo::class);
    }
}